<?php

namespace App\Services;

use App\Models\StoreMobileApp;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class MobileAppBuilderService
{
    protected ?string $token;

    protected string $repo;

    public function __construct()
    {
        $this->token = config('services.github_app_builder.token');
        $this->repo = config('services.github_app_builder.repo', 'nvirux/affanhub-mobile-template');
    }

    /**
     * Dispatch the GitHub Actions cloud build workflow for a merchant store app.
     */
    public function dispatchBuild(StoreMobileApp $mobileApp, string $storefrontUrl, string $buildType = 'release'): array
    {
        $store = $mobileApp->store;
        $iconUrl = $mobileApp->app_icon_path ? Storage::url($mobileApp->app_icon_path) : null;

        if ($iconUrl && ! str_starts_with($iconUrl, 'http')) {
            $iconUrl = url($iconUrl);
        }

        // Custom keystore support (for legacy stores already published on Google Play)
        $customKeystoreBase64 = '';
        if (! empty($mobileApp->custom_keystore_path)) {
            if (Storage::disk('local')->exists($mobileApp->custom_keystore_path)) {
                $customKeystoreBase64 = base64_encode(Storage::disk('local')->get($mobileApp->custom_keystore_path));
            } elseif (file_exists($mobileApp->custom_keystore_path)) {
                $customKeystoreBase64 = base64_encode(file_get_contents($mobileApp->custom_keystore_path));
            }
        }

        $inputs = [
            'app_name' => $mobileApp->app_name,
            'store_url' => $storefrontUrl,
            'package_id' => $mobileApp->package_id,
            'icon_url' => $iconUrl ?? '',
            'version_code' => (string) $mobileApp->version_code,
            'version_name' => (string) $mobileApp->version_name,
            'build_type' => $buildType,
            'store_id' => (string) $mobileApp->store_id,
            'custom_keystore_base64' => $customKeystoreBase64,
            'custom_keystore_alias' => $mobileApp->custom_keystore_alias ?? '',
            'custom_keystore_password' => $mobileApp->custom_keystore_password ?? '',
        ];

        if (empty($this->token)) {
            Log::warning('GitHub App Builder Token missing. Simulating build in local/sandbox mode.');
            $mobileApp->update([
                'status' => 'building',
                'last_built_at' => now(),
            ]);

            return [
                'success' => true,
                'simulated' => true,
                'message' => 'Build initiated in simulated mode (Add GITHUB_APP_TOKEN to .env to connect to live GitHub Actions runner).',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/vnd.github+json',
                'Authorization' => "Bearer {$this->token}",
                'X-GitHub-Api-Version' => '2022-11-28',
            ])->post("https://api.github.com/repos/{$this->repo}/actions/workflows/build-apk.yml/dispatches", [
                'ref' => 'main',
                'inputs' => $inputs,
            ]);

            if ($response->successful()) {
                $mobileApp->update([
                    'status' => 'building',
                    'failure_reason' => null,
                    'last_built_at' => now(),
                ]);

                Log::info("GitHub APK build dispatched for store #{$store->id} ({$mobileApp->app_name})");

                return [
                    'success' => true,
                    'message' => 'Mobile app compilation initiated in the cloud. It usually takes about 2 minutes.',
                ];
            }

            $errorMessage = $response->json('message') ?? $response->body();
            Log::error("GitHub APK dispatch failed: {$errorMessage}");

            $mobileApp->update([
                'status' => 'failed',
                'failure_reason' => $errorMessage,
            ]);

            return [
                'success' => false,
                'message' => "GitHub build dispatch failed: {$errorMessage}",
            ];
        } catch (\Throwable $e) {
            Log::error("GitHub APK Exception: {$e->getMessage()}");

            $mobileApp->update([
                'status' => 'failed',
                'failure_reason' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => "Build error: {$e->getMessage()}",
            ];
        }
    }

    /**
     * Check GitHub Actions for the latest completed build run and transfer artifacts directly to Cloudflare R2.
     */
    public function syncBuildStatus(StoreMobileApp $mobileApp): array
    {
        if (empty($this->token)) {
            return [
                'status' => $mobileApp->status,
                'ready' => $mobileApp->isReady(),
            ];
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/vnd.github+json',
                'Authorization' => "Bearer {$this->token}",
                'X-GitHub-Api-Version' => '2022-11-28',
            ])->get("https://api.github.com/repos/{$this->repo}/actions/workflows/build-apk.yml/runs", [
                'per_page' => 1,
            ]);

            if ($response->successful()) {
                $runs = $response->json('workflow_runs') ?? [];
                if (! empty($runs)) {
                    $latestRun = $runs[0];
                    $runStatus = $latestRun['status']; // queued, in_progress, completed
                    $conclusion = $latestRun['conclusion']; // success, failure, cancelled

                    if ($runStatus === 'completed') {
                        if ($conclusion === 'success') {
                            $artifactsUrl = $latestRun['artifacts_url'];
                            $this->transferArtifactsToR2($mobileApp, $artifactsUrl, (string) $latestRun['id']);
                        } elseif ($conclusion === 'failure') {
                            $mobileApp->update([
                                'status' => 'failed',
                                'failure_reason' => 'GitHub Action build failed. Check workflow logs.',
                            ]);
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Could not sync GitHub status: {$e->getMessage()}");
        }

        return [
            'status' => $mobileApp->status,
            'ready' => $mobileApp->isReady(),
        ];
    }

    /**
     * Download artifacts from GitHub and store permanently on Cloudflare R2 / S3 storage.
     */
    public function transferArtifactsToR2(StoreMobileApp $mobileApp, string $artifactsUrl, string $runId): void
    {
        $artResponse = Http::withHeaders([
            'Accept' => 'application/vnd.github+json',
            'Authorization' => "Bearer {$this->token}",
        ])->get($artifactsUrl);

        $artifacts = $artResponse->json('artifacts') ?? [];
        if (empty($artifacts)) {
            return;
        }

        $storageDisk = config('filesystems.default'); // 'r2'
        $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace(' ', '-', $mobileApp->app_name)) ?: 'app';
        $apkFileName = "{$cleanName}-v{$mobileApp->version_name}.apk";
        $aabFileName = "{$cleanName}-v{$mobileApp->version_name}.aab";

        $apkRelativePath = "apps/{$mobileApp->store_id}/{$apkFileName}";
        $aabRelativePath = "apps/{$mobileApp->store_id}/{$aabFileName}";

        $apkUrl = null;
        $aabUrl = null;

        foreach ($artifacts as $artifact) {
            $downloadUrl = $artifact['archive_download_url'] ?? null;
            if (! $downloadUrl) {
                continue;
            }

            try {
                $zipRes = Http::withHeaders([
                    'Authorization' => "Bearer {$this->token}",
                    'Accept' => 'application/vnd.github+json',
                ])->withOptions([
                    'follow_redirects' => true,
                    'timeout' => 90,
                ])->get($downloadUrl);

                if ($zipRes->successful()) {
                    $zipBinary = $zipRes->body();
                    $tempZipPath = tempnam(sys_get_temp_dir(), 'affan_art_');
                    file_put_contents($tempZipPath, $zipBinary);

                    $zip = new ZipArchive;
                    if ($zip->open($tempZipPath) === true) {
                        for ($i = 0; $i < $zip->numFiles; $i++) {
                            $entryName = strtolower($zip->getNameIndex($i));
                            if (str_ends_with($entryName, '.apk')) {
                                $apkContent = $zip->getFromIndex($i);
                                Storage::disk($storageDisk)->put($apkRelativePath, $apkContent, 'public');
                                if ($storageDisk !== 'public') {
                                    Storage::disk('public')->put($apkRelativePath, $apkContent);
                                }
                                $apkUrl = Storage::disk($storageDisk)->url($apkRelativePath);
                            } elseif (str_ends_with($entryName, '.aab')) {
                                $aabContent = $zip->getFromIndex($i);
                                Storage::disk($storageDisk)->put($aabRelativePath, $aabContent, 'public');
                                if ($storageDisk !== 'public') {
                                    Storage::disk('public')->put($aabRelativePath, $aabContent);
                                }
                                $aabUrl = Storage::disk($storageDisk)->url($aabRelativePath);
                            }
                        }
                        $zip->close();
                    }
                    @unlink($tempZipPath);
                }
            } catch (\Throwable $e) {
                Log::error("Failed extracting artifact for store #{$mobileApp->store_id}: {$e->getMessage()}");
            }
        }

        $mobileApp->update([
            'status' => 'ready',
            'github_run_id' => $runId,
            'apk_download_url' => $apkUrl ?? route('merchant.mobile-app.download', ['store' => $mobileApp->store->public_id]),
            'aab_download_url' => $aabUrl,
        ]);
    }

    /**
     * Normalize any uploaded image or existing store logo into a pristine 512x512 square PNG
     * with transparency support, avoiding any stretching or distortion.
     */
    public function processAndStoreAppIcon(mixed $source, int $storeId): string
    {
        $rawBinary = null;

        if (is_string($source)) {
            $disk = config('filesystems.default');
            if (Storage::disk($disk)->exists($source)) {
                $rawBinary = Storage::disk($disk)->get($source);
            } elseif (Storage::disk('public')->exists($source)) {
                $rawBinary = Storage::disk('public')->get($source);
            } elseif (file_exists($source)) {
                $rawBinary = file_get_contents($source);
            }
        } elseif (is_object($source) && method_exists($source, 'getRealPath')) {
            $rawBinary = file_get_contents($source->getRealPath());
        }

        if (empty($rawBinary)) {
            throw new \InvalidArgumentException('Unable to read image binary data for app icon processing.');
        }

        $srcImg = @imagecreatefromstring($rawBinary);
        if (! $srcImg) {
            throw new \RuntimeException('Failed to initialize image resource for app icon.');
        }

        $origWidth = imagesx($srcImg);
        $origHeight = imagesy($srcImg);
        $targetSize = 512;

        // Calculate aspect-ratio preserving dimensions that contain the logo neatly
        $scale = min($targetSize / $origWidth, $targetSize / $origHeight);
        $newWidth = (int) round($origWidth * $scale);
        $newHeight = (int) round($origHeight * $scale);
        $dstX = (int) round(($targetSize - $newWidth) / 2);
        $dstY = (int) round(($targetSize - $newHeight) / 2);

        // Create 512x512 canvas with full alpha transparency
        $canvas = imagecreatetruecolor($targetSize, $targetSize);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefilledrectangle($canvas, 0, 0, $targetSize, $targetSize, $transparent);
        imagealphablending($canvas, true);

        // Resample centered onto canvas
        imagecopyresampled($canvas, $srcImg, $dstX, $dstY, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        ob_start();
        imagepng($canvas, null, 9);
        $pngData = ob_get_clean();

        imagedestroy($srcImg);
        imagedestroy($canvas);

        $storageDisk = config('filesystems.default');
        $fileName = 'icon_'.time().'.png';
        $storedPath = "store-app-icons/{$storeId}/{$fileName}";

        Storage::disk($storageDisk)->put($storedPath, $pngData, 'public');
        if ($storageDisk !== 'public') {
            Storage::disk('public')->put($storedPath, $pngData);
        }

        return $storedPath;
    }

    /**
     * Delete an old custom icon to prevent storage accumulation.
     */
    public function deleteOldIcon(?string $oldPath): void
    {
        if (empty($oldPath)) {
            return;
        }

        $storageDisk = config('filesystems.default');
        Storage::disk($storageDisk)->delete($oldPath);
        Storage::disk('public')->delete($oldPath);
    }

    /**
     * Delete previously built APK to conserve storage upon rebuild.
     */
    public function cleanupOldBuilds(StoreMobileApp $mobileApp, ?string $newUrl = null): void
    {
        $storageDisk = config('filesystems.default');
        if (! empty($mobileApp->apk_download_url) && $mobileApp->apk_download_url !== $newUrl) {
            $parsed = parse_url($mobileApp->apk_download_url, PHP_URL_PATH);
            if ($parsed) {
                $relative = ltrim(str_replace('/storage/', '', $parsed), '/');
                if (Storage::disk($storageDisk)->exists($relative)) {
                    Storage::disk($storageDisk)->delete($relative);
                }
                if (Storage::disk('public')->exists($relative)) {
                    Storage::disk('public')->delete($relative);
                }
            }
        }
    }
}
