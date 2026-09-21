<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class MobileAppDownloadController extends Controller
{
    /**
     * Direct APK file delivery controller. Proxies and extracts GitHub Actions artifacts
     * so merchants and customers download a real .apk without authentication errors.
     */
    public function download(Request $request, Store $store): mixed
    {
        $app = $store->mobileApp;

        if (! $app || ! $app->isReady()) {
            abort(404, 'Your mobile app APK is not ready yet. Please check build status.');
        }

        $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace(' ', '-', $app->app_name));
        $fileName = ($cleanName ?: 'app')."-v{$app->version_name}.apk";
        $storageDisk = ! empty(config('filesystems.disks.r2.bucket'))
            ? 'r2'
            : (! empty(config('filesystems.disks.s3.bucket')) ? 's3' : config('filesystems.default'));
        $storedRelativePath = "apps/{$store->id}/{$fileName}";

        // 1. If the extracted .apk is already cached on storage, download it directly
        if (Storage::disk($storageDisk)->exists($storedRelativePath)) {
            return Storage::disk($storageDisk)->download($storedRelativePath, $fileName, [
                'Content-Type' => 'application/vnd.android.package-archive',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            ]);
        }

        if (Storage::disk('public')->exists($storedRelativePath)) {
            return response()->download(storage_path("app/public/{$storedRelativePath}"), $fileName, [
                'Content-Type' => 'application/vnd.android.package-archive',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            ]);
        }

        // 2. If it's a GitHub API artifact zip URL, authenticate from backend and extract the .apk
        $token = config('services.github_app_builder.token');
        $artifactUrl = $app->apk_download_url;

        if ($artifactUrl && $token && str_contains($artifactUrl, 'api.github.com')) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/vnd.github+json',
                    'X-GitHub-Api-Version' => '2022-11-28',
                ])->withOptions([
                    'follow_redirects' => true,
                    'timeout' => 60,
                ])->get($artifactUrl);

                if ($response->successful()) {
                    $zipBinary = $response->body();
                    $tempZipPath = tempnam(sys_get_temp_dir(), 'apk_artifact_');
                    file_put_contents($tempZipPath, $zipBinary);

                    $zip = new ZipArchive;
                    $extracted = false;

                    if ($zip->open($tempZipPath) === true) {
                        for ($i = 0; $i < $zip->numFiles; $i++) {
                            $entry = $zip->getNameIndex($i);
                            if (str_ends_with(strtolower($entry), '.apk')) {
                                $apkContent = $zip->getFromIndex($i);

                                Storage::disk($storageDisk)->put($storedRelativePath, $apkContent, 'public');
                                if ($storageDisk !== 'public') {
                                    Storage::disk('public')->put($storedRelativePath, $apkContent);
                                }

                                $extracted = true;
                                break;
                            }
                        }
                        $zip->close();
                    }

                    @unlink($tempZipPath);

                    if ($extracted && Storage::disk('public')->exists($storedRelativePath)) {
                        return response()->download(storage_path("app/public/{$storedRelativePath}"), $fileName, [
                            'Content-Type' => 'application/vnd.android.package-archive',
                            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
                        ]);
                    }
                } else {
                    Log::error('GitHub Artifact Download Failed with status '.$response->status().': '.$response->body());
                }
            } catch (\Throwable $e) {
                Log::error("Exception in MobileAppDownloadController: {$e->getMessage()}");
            }
        }

        // 3. Fallback if it's already a direct public URL
        if ($artifactUrl && ! str_contains($artifactUrl, 'api.github.com')) {
            return redirect()->away($artifactUrl);
        }

        abort(500, 'Unable to download the APK file at this moment. Please trigger a rebuild or contact support.');
    }
}
