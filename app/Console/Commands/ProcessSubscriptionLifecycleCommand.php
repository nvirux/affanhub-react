<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Subscription\SubscriptionLifecycleService;
use Illuminate\Console\Command;

class ProcessSubscriptionLifecycleCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'subscriptions:process-lifecycle';

    /**
     * The console command description.
     */
    protected $description = 'Process expiring subscriptions, send reminders, activate grace periods, and downgrade expired stores';

    /**
     * Execute the console command.
     */
    public function handle(SubscriptionLifecycleService $service): int
    {
        $this->info('Starting subscription lifecycle processing...');

        $results = $service->processAll();

        $this->info(sprintf(
            'Completed: %d reminders sent, %d grace transitions activated, %d stores downgraded to Starter.',
            $results['reminders_sent'],
            $results['grace_transitions'],
            $results['downgrades']
        ));

        return Command::SUCCESS;
    }
}
