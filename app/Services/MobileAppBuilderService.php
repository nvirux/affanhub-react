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
     * Resolve the most professional public URL for the store (custom domain first, then primary subdomain)
     * pointing directly to the /dashboard page for native app authentication and dashboard access.
     */
    public function resolveStorefrontUrl(StoreMobileApp $mobileApp, ?string $storefrontUrl = null): string
    {
        if (! empty($storefrontUrl)) {
            return $this->formatDashboardUrl($storefrontUrl);
        }

        $store = $mobileApp->store;
        if (! $store) {
            return url('/dashboard');
        }

        // 1. Prefer verified/healthy custom domain (e.g. adeolavtu.com)
        $customDomain = $store->domains()->get()->first(fn ($d) => $d->isCustom() && $d->isHealthy());
        if ($customDomain) {
            $scheme = str_contains($customDomain->domain, 'localhost') ? 'http' : 'https';

            return "{$scheme}://{$customDomain->domain}/dashboard";
        }

        // 2. Primary domain (e.g. adeola.affanhub.com)
        $primaryDomain = $store->domains()->where('is_primary', true)->first() ?? $store->domains()->first();
        if ($primaryDomain) {
            $scheme = str_contains($primaryDomain->domain, 'localhost') ? 'http' : 'https';

            return "{$scheme}://{$primaryDomain->domain}/dashboard";
        }

        return rtrim($store->getStoreUrl(), '/').'/dashboard';
    }

    protected function formatDashboardUrl(string $url): string
    {
        $trimmed = rtrim($url, '/');
        if (str_ends_with($trimmed, '/dashboard')) {
            return $trimmed;
        }

        return "{$trimmed}/dashboard";
    }

    /**
     * Dispatch the GitHub Actions cloud build workflow for a merchant store app.
     */
    public function getActiveStorageDisk(): string
    {
        return ! empty(config('filesystems.disks.r2.bucket'))
            ? 'r2'
            : (! empty(config('filesystems.disks.s3.bucket')) ? 's3' : config('filesystems.default'));
    }

    /**
     * Dispatch the GitHub Actions cloud build workflow for a merchant store app.
     */
    public function dispatchBuild(StoreMobileApp $mobileApp, ?string $storefrontUrl = null, string $buildType = 'release'): array
    {
        $store = $mobileApp->store;
        $resolvedStoreUrl = $this->resolveStorefrontUrl($mobileApp, $storefrontUrl);
        $targetDisk = $this->getActiveStorageDisk();

        $iconUrl = null;
        if (! empty($mobileApp->app_icon_path)) {
            try {
                $iconUrl = Storage::disk($targetDisk)->url($mobileApp->app_icon_path);
            } catch (\Throwable) {
                $iconUrl = Storage::url($mobileApp->app_icon_path);
            }

            if ($iconUrl && ! str_starts_with($iconUrl, 'http')) {
                $iconUrl = url($iconUrl);
            }

            // GitHub Actions cloud runner cannot access local machine URLs (localhost / 127.0.0.1)
            if ($iconUrl && (str_contains($iconUrl, 'localhost') || str_contains($iconUrl, '127.0.0.1'))) {
                Log::warning("App icon URL ({$iconUrl}) is local and unreachable from GitHub Actions cloud runner. Build will use template default branding.");
                $iconUrl = null;
            }
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
            'store_url' => $resolvedStoreUrl,
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
            Log::error('GitHub App Builder Token missing. Build cannot proceed without GITHUB_APP_TOKEN.');
            $mobileApp->update([
                'status' => 'failed',
                'failure_reason' => 'App builder configuration issue (missing GITHUB_APP_TOKEN in .env).',
            ]);

            return [
                'success' => false,
                'message' => 'Mobile app compiler is currently offline. Please contact support.',
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
            if ($mobileApp->isBuilding()) {
                $mobileApp->update([
                    'status' => 'failed',
                    'failure_reason' => 'Build stopped: missing GITHUB_APP_TOKEN in .env.',
                ]);
            }

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
                'per_page' => 5,
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

                            return [
                                'status' => 'ready',
                                'ready' => true,
                            ];
                        } elseif (in_array($conclusion, ['failure', 'cancelled', 'timed_out'])) {
                            Log::warning("GitHub Action compilation ended with [{$conclusion}] for store #{$mobileApp->store_id}");
                            $mobileApp->update([
                                'status' => 'failed',
                                'failure_reason' => 'Compilation could not be completed. Please try rebuilding or contact support.',
                            ]);

                            return [
                                'status' => 'failed',
                                'ready' => false,
                            ];
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Could not sync GitHub status: {$e->getMessage()}");
        }

        // Only auto-cancel / time-out if the runner is STILL queued or in_progress after 20 minutes
        if ($mobileApp->isBuilding() && $mobileApp->last_built_at && $mobileApp->last_built_at->diffInMinutes(now()) >= 20) {
            $mobileApp->update([
                'status' => 'failed',
                'failure_reason' => 'Build timed out after 20 minutes with no response from build runner.',
            ]);

            return [
                'status' => $mobileApp->status,
                'ready' => false,
            ];
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
            Log::warning("GitHub build completed for store #{$mobileApp->store_id}, but artifacts list was empty at {$artifactsUrl}");
            $mobileApp->update([
                'status' => 'failed',
                'failure_reason' => 'Compilation could not generate output binaries. Please try rebuilding.',
            ]);

            return;
        }

        $storageDisk = ! empty(config('filesystems.disks.r2.bucket'))
            ? 'r2'
            : (! empty(config('filesystems.disks.s3.bucket')) ? 's3' : config('filesystems.default'));
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
            $disks = array_filter(array_unique([
                $this->getActiveStorageDisk(),
                'r2',
                's3',
                'public',
                config('filesystems.default'),
                'local',
            ]));

            foreach ($disks as $disk) {
                try {
                    if (Storage::disk($disk)->exists($source)) {
                        $rawBinary = Storage::disk($disk)->get($source);
                        if (! empty($rawBinary)) {
                            break;
                        }
                    }
                } catch (\Throwable) {
                    // Try next disk
                }
            }

            if (empty($rawBinary)) {
                if (file_exists($source)) {
                    $rawBinary = file_get_contents($source);
                } elseif (str_starts_with($source, 'http://') || str_starts_with($source, 'https://')) {
                    try {
                        $rawBinary = Http::timeout(10)->get($source)->body();
                    } catch (\Throwable) {
                        // Remote fetch failed
                    }
                }
            }
        } elseif (is_object($source) && method_exists($source, 'getRealPath')) {
            $rawBinary = file_get_contents($source->getRealPath());
        }

        if (empty($rawBinary)) {
            throw new \InvalidArgumentException('Unable to read image binary data for app icon processing.');
        }

        $pngData = null;

        // 1. Try PHP GD extension if installed
        if (function_exists('imagecreatefromstring') && function_exists('imagecreatetruecolor')) {
            try {
                $srcImg = @imagecreatefromstring($rawBinary);
                if ($srcImg) {
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
                }
            } catch (\Throwable $e) {
                Log::warning("GD icon processing failed: {$e->getMessage()}. Using raw image binary.");
            }
        }

        // 2. Try Imagick extension if GD is unavailable
        if (empty($pngData) && class_exists(\Imagick::class)) {
            try {
                $im = new \Imagick;
                $im->readImageBlob($rawBinary);
                $im->setImageFormat('png');
                $im->thumbnailImage(512, 512, true, true);
                $pngData = $im->getImageBlob();
                $im->clear();
                $im->destroy();
            } catch (\Throwable $e) {
                Log::warning("Imagick icon processing failed: {$e->getMessage()}.");
            }
        }

        // 3. Fallback: If neither GD nor Imagick is installed in PHP, preserve raw image binary seamlessly
        if (empty($pngData)) {
            $pngData = $rawBinary;
        }

        $storageDisk = $this->getActiveStorageDisk();
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

        // SAFETY GUARD: Only delete dedicated app icons located in store-app-icons/.
        // NEVER touch merchant store logos (logos/) or any other website assets!
        if (! str_starts_with($oldPath, 'store-app-icons/')) {
            return;
        }

        $disks = array_filter(array_unique([
            $this->getActiveStorageDisk(),
            'r2',
            's3',
            'public',
            config('filesystems.default'),
        ]));

        foreach ($disks as $disk) {
            try {
                if (Storage::disk($disk)->exists($oldPath)) {
                    Storage::disk($disk)->delete($oldPath);
                }
            } catch (\Throwable) {
            }
        }
    }

    /**
     * Delete previously built APK, AAB binaries, and app icons from Cloudflare R2 / S3 upon rebuild or deletion.
     */
    public function cleanupOldBuilds(StoreMobileApp $mobileApp, ?string $newUrl = null): void
    {
        $dir = "apps/{$mobileApp->store_id}";
        $iconDir = "store-app-icons/{$mobileApp->store_id}";

        $disks = array_filter(array_unique([
            $this->getActiveStorageDisk(),
            'r2',
            's3',
            'public',
            config('filesystems.default'),
        ]));

        foreach ($disks as $disk) {
            if (empty(config("filesystems.disks.{$disk}"))) {
                continue;
            }

            try {
                // Delete app build directory
                if (Storage::disk($disk)->exists($dir)) {
                    Storage::disk($disk)->deleteDirectory($dir);
                }

                // Delete custom app icons folder
                if (Storage::disk($disk)->exists($iconDir)) {
                    Storage::disk($disk)->deleteDirectory($iconDir);
                }
            } catch (\Throwable $e) {
                Log::warning("Could not cleanup builds on disk [{$disk}] for store #{$mobileApp->store_id}: {$e->getMessage()}");
            }
        }
    }
}
