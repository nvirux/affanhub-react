<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Owner;
use App\Models\Service;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VirtualAccount;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use PDO;

class MigrateOldAffanHubCommand extends Command
{
    protected $signature = 'affanhub:migrate-old-db 
                            {--sqlite= : Absolute or relative path to old database.sqlite}
                            {--connection=old_affanhub : Database connection name defined in config/database.php}
                            {--force : Actually execute the migration into the database}';

    protected $description = 'Migrate old AffanHub database (Admins, Owners, Stores, Customers, Wallets, VAs, and Transactions) into the new architecture.';

    protected PDO $oldPdo;

    public function handle(): int
    {
        $this->info('=====================================================');
        $this->info('       AffanHub v1 -> v2 Migration Engine            ');
        $this->info('=====================================================');

        // 1. Establish Source PDO Connection
        if (! $this->establishSourceConnection()) {
            return Command::FAILURE;
        }

        $isDryRun = ! $this->option('force');

        if ($isDryRun) {
            $this->warn('>> RUNNING IN DRY-RUN MODE (--dry-run). No database changes will be saved.');
            $this->line('>> Pass --force when you are ready to apply changes permanently.');
        } else {
            $this->alert('>> EXECUTION MODE: Applying changes to database!');
        }

        $this->newLine();

        // 2. Fetch Source Data
        $oldAdmins = $this->queryOld('SELECT * FROM admins');
        $oldTenants = $this->queryOld('SELECT * FROM tenants');
        $oldUsers = $this->queryOld('SELECT * FROM users');
        $oldTenantUsers = $this->queryOld('SELECT * FROM tenant_user');
        $oldTenantWallets = $this->queryOld('SELECT * FROM tenant_wallets');
        $oldProfitWallets = $this->tableExists('tenant_profit_wallets') ? $this->queryOld('SELECT * FROM tenant_profit_wallets') : [];
        $oldVirtualAccounts = $this->tableExists('customer_virtual_accounts') ? $this->queryOld('SELECT * FROM customer_virtual_accounts') : [];
        $oldTransactions = $this->tableExists('transactions') ? $this->queryOld('SELECT * FROM transactions') : [];

        $this->info(sprintf(
            'Found in old database: %d Admins, %d Tenants/Stores, %d Users, %d Virtual Accounts, %d Transactions.',
            count($oldAdmins),
            count($oldTenants),
            count($oldUsers),
            count($oldVirtualAccounts),
            count($oldTransactions)
        ));

        // 3. Map & Plan
        // Identify owners: in old DB, users where role = 'owner' or mapped in tenant_user as 'owner'
        $ownerUserIds = [];
        foreach ($oldUsers as $u) {
            if (($u['role'] ?? '') === 'owner') {
                $ownerUserIds[$u['id']] = true;
            }
        }
        foreach ($oldTenantUsers as $tu) {
            if (($tu['role'] ?? '') === 'owner') {
                $ownerUserIds[$tu['user_id']] = true;
            }
        }

        $dryRunSummary = [
            'Admins to migrate' => count($oldAdmins),
            'Owners to create' => count($ownerUserIds),
            'Stores to create (tenant_id preserved)' => count($oldTenants),
            'Customers to migrate' => count($oldUsers) - count($ownerUserIds),
            'Virtual Accounts to migrate' => count($oldVirtualAccounts),
            'Transactions to migrate' => count($oldTransactions),
        ];

        $this->table(['Entity', 'Count'], collect($dryRunSummary)->map(fn ($val, $key) => [$key, $val])->toArray());

        if ($isDryRun) {
            $this->newLine();
            $this->info('Dry-run completed successfully! Use --force to execute the migration.');

            return Command::SUCCESS;
        }

        // 4. EXECUTE MIGRATION
        DB::beginTransaction();

        try {
            // A. Migrate Admins
            $this->info('Migrating Admins...');
            foreach ($oldAdmins as $adm) {
                Admin::updateOrCreate(
                    ['email' => $adm['email']],
                    [
                        'name' => $adm['name'],
                        'password' => $adm['password'],
                        'role' => ($adm['role'] ?? '') === 'super_admin' ? 'superadmin' : 'admin',
                        'email_verified_at' => $adm['email_verified_at'] ?? now(),
                        'created_at' => $adm['created_at'] ?? now(),
                        'updated_at' => $adm['updated_at'] ?? now(),
                    ]
                );
            }

            // B. Migrate Owners
            $this->info('Migrating Store Owners...');
            $ownerIdMap = []; // old user_id => new owner_id
            foreach ($oldUsers as $u) {
                if (isset($ownerUserIds[$u['id']])) {
                    $owner = Owner::updateOrCreate(
                        ['email' => $u['email']],
                        [
                            'name' => $u['name'],
                            'phone' => $u['phone_number'] ?? $u['phone'] ?? null,
                            'password' => $u['password'],
                            'bvn' => $u['bvn'] ?? null,
                            'nin' => $u['nin'] ?? null,
                            'max_stores' => 5,
                            'email_verified_at' => $u['email_verified_at'] ?? now(),
                            'created_at' => $u['created_at'] ?? now(),
                            'updated_at' => $u['updated_at'] ?? now(),
                        ]
                    );
                    $ownerIdMap[$u['id']] = $owner->id;
                }
            }

            // Fallback owner if tenant had an unmapped user
            $defaultOwner = Owner::first();

            // C. Migrate Stores (Preserving exact tenant_id -> store_id!)
            $this->info('Migrating Stores (preserving exact IDs)...');
            $tenantProfitMap = collect($oldProfitWallets)->keyBy('tenant_id');
            $tenantWalletMap = collect($oldTenantWallets)->keyBy('tenant_id');

            foreach ($oldTenants as $t) {
                $tenantId = (int) $t['id'];

                // Find owner of this tenant from tenant_user
                $assignedOwnerId = null;
                foreach ($oldTenantUsers as $tu) {
                    if ((int) $tu['tenant_id'] === $tenantId && ($tu['role'] ?? '') === 'owner') {
                        $assignedOwnerId = $ownerIdMap[$tu['user_id']] ?? null;
                        if ($assignedOwnerId) {
                            break;
                        }
                    }
                }

                if (! $assignedOwnerId) {
                    $assignedOwnerId = $defaultOwner ? $defaultOwner->id : 1;
                }

                // Domain or Slug
                $slug = Str::slug($t['slug'] ?? $t['name']);
                $domain = $slug.'.'.(parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost');

                // Upsert Store with exact ID
                $store = Store::updateOrCreate(
                    ['id' => $tenantId],
                    [
                        'name' => $t['name'],
                        'public_id' => 'str_'.strtolower(Str::random(10)),
                        'owner_id' => $assignedOwnerId,
                        'created_at' => $t['created_at'] ?? now(),
                        'updated_at' => $t['updated_at'] ?? now(),
                    ]
                );

                // Attach to members pivot if not attached
                if (! $store->members()->where('owner_id', $assignedOwnerId)->exists()) {
                    $store->members()->attach($assignedOwnerId, ['role' => 'owner']);
                }

                // Attach domain if domains table exists in new DB
                if (Schema::hasTable('domains')) {
                    DB::table('domains')->updateOrInsert(
                        ['store_id' => $store->id],
                        [
                            'domain' => $domain,
                            'is_primary' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                // D. Store Wallets (Main & Profit Accounting)
                $oldMainBalance = (float) ($tenantWalletMap[$tenantId]['balance'] ?? 0.00);
                $oldProfitBalance = (float) ($tenantProfitMap[$tenantId]['balance'] ?? 0.00);

                // Deduct profit from main so money is never duplicated
                $cleanMainBalance = max(0.00, $oldMainBalance - $oldProfitBalance);
                $cleanProfitBalance = $oldProfitBalance;

                // 1. Setup Main Wallet
                $mainWallet = $store->mainWallet();
                $mainWallet->update(['balance' => $cleanMainBalance]);

                WalletTransaction::updateOrCreate(
                    ['reference' => 'MIG_MAIN_TENANT_'.$tenantId],
                    [
                        'wallet_id' => $mainWallet->id,
                        'type' => 'credit',
                        'category' => 'migration_balance',
                        'amount' => $cleanMainBalance,
                        'balance_before' => 0.00,
                        'balance_after' => $cleanMainBalance,
                        'description' => "Initial Operating Balance migrated from AffanHub v1 (Total old: ₦{$oldMainBalance}, Profit swept: ₦{$oldProfitBalance})",
                        'status' => 'success',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // 2. Setup Profit Wallet
                if ($cleanProfitBalance > 0) {
                    $profitWallet = $store->profitWallet();
                    $profitWallet->update(['balance' => $cleanProfitBalance]);

                    WalletTransaction::updateOrCreate(
                        ['reference' => 'MIG_PRF_TENANT_'.$tenantId],
                        [
                            'wallet_id' => $profitWallet->id,
                            'type' => 'credit',
                            'category' => 'migration_profit',
                            'amount' => $cleanProfitBalance,
                            'balance_before' => 0.00,
                            'balance_after' => $cleanProfitBalance,
                            'description' => 'Earned Withdrawable Profit migrated from AffanHub v1',
                            'status' => 'success',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }

            // E. Migrate Customers (Users who are not owners)
            $this->info('Migrating End-Users / Store Customers...');
            $userIdMap = []; // old user_id => new user_id
            foreach ($oldUsers as $u) {
                if (! isset($ownerUserIds[$u['id']])) {
                    $storeId = (int) ($u['tenant_id'] ?? 1);
                    if (! Store::where('id', $storeId)->exists()) {
                        $storeId = Store::first()?->id ?? 1;
                    }

                    $phone = $u['phone_number'] ?? $u['phone'] ?? ('080'.rand(10000000, 99999999));

                    $newUser = User::updateOrCreate(
                        ['email' => $u['email']],
                        [
                            'store_id' => $storeId,
                            'name' => $u['name'],
                            'phone' => $phone,
                            'bvn' => $u['bvn'] ?? null,
                            'nin' => $u['nin'] ?? null,
                            'password' => $u['password'],
                            'email_verified_at' => $u['email_verified_at'] ?? now(),
                            'created_at' => $u['created_at'] ?? now(),
                            'updated_at' => $u['updated_at'] ?? now(),
                        ]
                    );

                    $userIdMap[$u['id']] = $newUser->id;

                    // Migrate user balance
                    $userBal = (float) ($u['balance'] ?? 0.00);
                    $userWallet = $newUser->wallet('main');
                    $userWallet->update(['balance' => $userBal]);

                    if ($userBal > 0) {
                        WalletTransaction::updateOrCreate(
                            ['reference' => 'MIG_USER_'.$u['id']],
                            [
                                'wallet_id' => $userWallet->id,
                                'type' => 'credit',
                                'category' => 'migration_balance',
                                'amount' => $userBal,
                                'balance_before' => 0.00,
                                'balance_after' => $userBal,
                                'description' => 'Initial Customer Balance migrated from AffanHub v1',
                                'status' => 'success',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }
                }
            }

            // F. Migrate Virtual Accounts
            $this->info('Migrating Virtual Accounts...');
            foreach ($oldVirtualAccounts as $va) {
                $mappedUserId = $userIdMap[$va['user_id']] ?? null;
                if (! $mappedUserId) {
                    continue;
                }

                VirtualAccount::updateOrCreate(
                    ['account_number' => $va['account_number']],
                    [
                        'holder_type' => User::class,
                        'holder_id' => $mappedUserId,
                        'bank_name' => $va['bank_name'] ?? 'Bank',
                        'account_name' => $va['account_name'] ?? $va['customer_name'] ?? 'AffanHub Customer',
                        'email_alias' => $va['customer_email'] ?? null,
                        'provider' => $va['provider'] ?? 'paymint',
                        'status' => $va['status'] ?? 'active',
                        'reference' => $va['account_reference'] ?? ('VA_'.Str::random(12)),
                        'meta' => ! empty($va['meta']) ? json_decode($va['meta'], true) : [],
                        'created_at' => $va['created_at'] ?? now(),
                        'updated_at' => $va['updated_at'] ?? now(),
                    ]
                );
            }

            // G. Migrate Transactions History
            $this->info('Migrating Transactions History...');
            $services = Service::pluck('id', 'key');

            foreach ($oldTransactions as $tx) {
                $txStoreId = (int) ($tx['tenant_id'] ?? 1);
                $mappedUserId = $userIdMap[$tx['user_id']] ?? null;
                $serviceKey = strtolower($tx['type'] ?? 'data');
                $serviceId = $services[$serviceKey] ?? $services['data'] ?? null;

                // Extract recipient from meta if possible
                $recipient = 'N/A';
                if (! empty($tx['meta'])) {
                    $metaDecoded = json_decode($tx['meta'], true);
                    $recipient = $metaDecoded['phone_number'] ?? $metaDecoded['phone'] ?? 'N/A';
                }

                Transaction::updateOrCreate(
                    ['reference' => $tx['reference']],
                    [
                        'user_id' => $mappedUserId,
                        'store_id' => Store::where('id', $txStoreId)->exists() ? $txStoreId : 1,
                        'service_id' => $serviceId,
                        'service_type' => $serviceKey,
                        'amount' => (float) ($tx['amount'] ?? 0.00),
                        'discount' => 0.00,
                        'amount_paid' => (float) ($tx['amount'] ?? 0.00),
                        'cost_price' => max(0.00, (float) ($tx['amount'] ?? 0.00) - (float) ($tx['profit'] ?? 0.00)),
                        'vendor_cost' => max(0.00, (float) ($tx['amount'] ?? 0.00) - (float) ($tx['profit'] ?? 0.00)),
                        'profit' => (float) ($tx['profit'] ?? 0.00),
                        'platform_profit' => 0.00,
                        'recipient' => $recipient,
                        'status' => in_array($tx['status'], ['success', 'successful']) ? 'successful' : ($tx['status'] ?? 'failed'),
                        'api_response' => ! empty($tx['provider_response']) ? json_decode($tx['provider_response'], true) : [],
                        'created_at' => $tx['created_at'] ?? now(),
                        'updated_at' => $tx['updated_at'] ?? now(),
                    ]
                );
            }

            DB::commit();
            $this->info('=====================================================');
            $this->info('  Migration Completed Successfully with Zero Errors! ');
            $this->info('=====================================================');

            return Command::SUCCESS;

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Migration Failed: '.$e->getMessage());
            $this->line($e->getTraceAsString());

            return Command::FAILURE;
        }
    }

    protected function establishSourceConnection(): bool
    {
        $sqlitePath = $this->option('sqlite');

        if ($sqlitePath) {
            if (! file_exists($sqlitePath)) {
                $this->error("Specified SQLite file does not exist at: {$sqlitePath}");

                return false;
            }
            $this->info("Connecting to SQLite source: {$sqlitePath}");
            $this->oldPdo = new PDO("sqlite:{$sqlitePath}");

            return true;
        }

        // Try MySQL remote connection
        $connectionName = $this->option('connection');
        $this->info("Attempting connection to remote MySQL connection: [{$connectionName}]...");

        try {
            $this->oldPdo = DB::connection($connectionName)->getPdo();
            $this->info('Successfully connected to MySQL database: '.config("database.connections.{$connectionName}.database"));

            return true;
        } catch (\Throwable $e) {
            $this->warn('Remote MySQL connection failed: '.$e->getMessage());

            // Check if local default sqlite exists as fallback
            $fallbackSqlite = 'C:/Users/Admin/projects/affanhub/database/database.sqlite';
            if (file_exists($fallbackSqlite)) {
                $this->info("Falling back to local SQLite at: {$fallbackSqlite}");
                $this->oldPdo = new PDO("sqlite:{$fallbackSqlite}");

                return true;
            }

            $this->error('Unable to connect to source database via MySQL or local SQLite.');

            return false;
        }
    }

    protected function queryOld(string $sql): array
    {
        $stmt = $this->oldPdo->query($sql);

        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    protected function tableExists(string $tableName): bool
    {
        try {
            $driver = $this->oldPdo->getAttribute(PDO::ATTR_DRIVER_NAME);
            if ($driver === 'sqlite') {
                $stmt = $this->oldPdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='{$tableName}'");

                return (bool) $stmt->fetchColumn();
            } else {
                $stmt = $this->oldPdo->query("SHOW TABLES LIKE '{$tableName}'");

                return (bool) $stmt->fetchColumn();
            }
        } catch (\Throwable) {
            return false;
        }
    }
}
