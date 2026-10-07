<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Services\Cloudflare\CloudflareSaaSService;
use App\Services\Domain\DnsVerificationService;
use Illuminate\Console\Command;

class SyncCloudflareCustomDomainsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domains:sync-cloudflare {--dry-run : Output domains that would be registered without calling Cloudflare API}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all existing merchant custom domains with Cloudflare for SaaS custom hostnames';

    /**
     * Execute the console command.
     */
    public function handle(CloudflareSaaSService $saasService, DnsVerificationService $dns): int
    {
        $isDryRun = (bool) $this->option('dry-run');

        $this->info('Scanning database for merchant custom domains...');

        $domains = Domain::all()->filter(fn (Domain $domain) => $domain->isCustom());

        if ($domains->isEmpty()) {
            $this->warn('No custom domains found in database.');

            return Command::SUCCESS;
        }

        $this->info("Found {$domains->count()} custom domain(s) to process.");

        if ($isDryRun) {
            $this->warn('DRY RUN MODE ENABLED - No changes will be made.');
        }

        $successCount = 0;
        $failedCount = 0;

        foreach ($domains as $domain) {
            $domainName = strtolower(trim($domain->domain));
            $this->line("Processing: <comment>{$domainName}</comment> (Store ID: {$domain->tenant_id})");

            $isCloudflare = $dns->isCloudflareManaged($domainName);

            if ($isDryRun) {
                $cfBadge = $isCloudflare ? 'Cloudflare DNS' : 'External DNS';
                $this->info("  [Dry Run] Would register '{$domainName}' ({$cfBadge}) with Cloudflare for SaaS.");

                continue;
            }

            $result = $saasService->createCustomHostname($domainName);

            if ($result['success']) {
                $updates = ['cloudflare_detected' => $isCloudflare];
                if (filled($result['id'] ?? null)) {
                    $updates['cloudflare_hostname_id'] = $result['id'];
                }
                $domain->updateQuietly($updates);

                $this->info("  ✔ Successfully registered: {$domainName}".(isset($result['id']) ? " (ID: {$result['id']})" : ''));
                $successCount++;
            } else {
                $this->error("  ✖ Failed to register: {$domainName} - Error: {$result['message']}");
                $failedCount++;
            }
        }

        $this->newLine();
        if ($isDryRun) {
            $this->info("Dry run completed. Processed {$domains->count()} domain(s).");
        } else {
            $this->info("Sync completed! Successfully synced: {$successCount}, Failed: {$failedCount}");
        }

        return $failedCount === 0 ? Command::SUCCESS : Command::FAILURE;
    }
}
