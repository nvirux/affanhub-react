<?php

namespace App\Console\Commands;

use App\Services\Vtu\DataPlanSyncService;
use Illuminate\Console\Command;

class SyncDataPlansCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vtu:sync-plans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all data plans and wholesale prices from upstream VTU provider API';

    /**
     * Execute the console command.
     */
    public function handle(DataPlanSyncService $syncService): int
    {
        $this->info('Connecting to VTU provider API to fetch data plans...');

        $result = $syncService->sync();

        if ($result['success']) {
            $this->info('SUCCESS: '.$result['message']);

            return Command::SUCCESS;
        }

        $this->error('ERROR: '.$result['message']);

        return Command::FAILURE;
    }
}
