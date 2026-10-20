<?php

namespace App\Console\Commands;

use App\Models\StoreMobileApp;
use App\Services\MobileAppBuilderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncBuildingMobileAppsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mobile-app:sync-builds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll GitHub Actions and sync compilation status for mobile apps currently in building status.';

    /**
     * Execute the console command.
     */
    public function handle(MobileAppBuilderService $builder): int
    {
        $buildingApps = StoreMobileApp::where('status', 'building')->get();

        if ($buildingApps->isEmpty()) {
            return self::SUCCESS;
        }

        $this->info("Found {$buildingApps->count()} mobile app(s) in building status. Syncing with GitHub...");

        foreach ($buildingApps as $app) {
            try {
                $result = $builder->syncBuildStatus($app);
                $this->line("App #{$app->id} ({$app->app_name}): status is now {$result['status']}");

                if ($result['ready']) {
                    Log::info("Background sync: Mobile app #{$app->id} for store #{$app->store_id} ({$app->app_name}) is now ready.");
                }
            } catch (\Throwable $e) {
                Log::warning("Background sync failed for mobile app #{$app->id}: {$e->getMessage()}");
                $this->error("Failed syncing app #{$app->id}: {$e->getMessage()}");
            }
        }

        return self::SUCCESS;
    }
}
