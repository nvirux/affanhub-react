<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\Vtu\VtuReconciliationService;
use Illuminate\Console\Command;

class ReconcilePendingVtuTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vtu:reconcile-pending {--limit=50 : Maximum number of transactions to reconcile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reconcile pending VTU (Data and Airtime) transactions against provider status';

    /**
     * Execute the console command.
     */
    public function handle(VtuReconciliationService $reconciliationService): int
    {
        $limit = (int) $this->option('limit');

        // Only check transactions that have had at least 30 seconds to settle
        $pendingTransactions = Transaction::whereIn('status', ['pending', 'processing'])
            ->where('created_at', '<=', now()->subSeconds(30))
            ->latest()
            ->limit($limit)
            ->get();

        if ($pendingTransactions->isEmpty()) {
            $this->info('No pending VTU transactions requiring reconciliation.');

            return self::SUCCESS;
        }

        $this->info("Found {$pendingTransactions->count()} pending transaction(s). Reconciling with provider...");

        $successCount = 0;
        $failedCount = 0;
        $stillPendingCount = 0;

        foreach ($pendingTransactions as $transaction) {
            $this->line("Checking Reference: {$transaction->reference} ({$transaction->service_type})...");

            $result = $reconciliationService->reconcile($transaction);

            if ($result['status'] === 'successful') {
                $this->info("  [SUCCESS] {$transaction->reference} completed. Profit swept.");
                $successCount++;
            } elseif ($result['status'] === 'failed') {
                $this->warn("  [FAILED] {$transaction->reference} failed. Customer wallet refunded.");
                $failedCount++;
            } else {
                $this->line("  [PENDING] {$transaction->reference} is still processing.");
                $stillPendingCount++;
            }
        }

        $this->info("Reconciliation summary: {$successCount} successful, {$failedCount} failed/refunded, {$stillPendingCount} still pending.");

        return self::SUCCESS;
    }
}
