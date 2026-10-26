<?php

namespace App\Filament\Merchant\Pages;

use App\Models\ActivityLog;
use App\Models\SettlementAccount;
use App\Models\Withdrawal;
use App\Services\Payment\PayMintService;
use App\Services\WalletService;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoreWallet extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWallet;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance & Wallet';

    protected string $view = 'filament.merchant.pages.store-wallet';

    protected static ?string $slug = 'wallet';

    protected static ?string $title = 'Wallet';

    protected static ?string $navigationLabel = 'Wallet';

    protected static ?int $navigationSort = 1;

    // Active tab state: 'overview', 'payouts', 'policy'
    public string $activeTab = 'overview';

    // Virtual Account State
    public string $kycType = 'nin';

    public string $kycNumber = '';

    public string $phone = '';

    // Customer Funding Fee State
    public string $customerFeeType = 'free'; // 'free', 'flat', 'percentage'

    public float $customerFeeAmount = 0.0;

    public float $customerFeeCap = 100.0;

    // Profit Withdrawal State
    public ?float $withdrawalAmount = null;

    public bool $withdrawalModalOpen = false;

    public float $minWithdrawal = 1000.0;

    public float $payoutFee = 100.0;

    // Settlement Bank Account State
    public string $settlementBankName = '';

    public string $settlementAccountNumber = '';

    public string $settlementAccountName = '';

    public bool $settlementModalOpen = false;

    public static function getNigerianBanks(): array
    {
        return [
            'Access Bank' => 'Access Bank',
            'Access Bank (Diamond)' => 'Access Bank (Diamond)',
            'Ecobank Nigeria' => 'Ecobank Nigeria',
            'Fidelity Bank' => 'Fidelity Bank',
            'First Bank of Nigeria' => 'First Bank of Nigeria',
            'First City Monument Bank (FCMB)' => 'First City Monument Bank (FCMB)',
            'Globus Bank' => 'Globus Bank',
            'Guaranty Trust Bank (GTBank)' => 'Guaranty Trust Bank (GTBank)',
            'Heritage Bank' => 'Heritage Bank',
            'Keystone Bank' => 'Keystone Bank',
            'Kuda Microfinance Bank' => 'Kuda Microfinance Bank',
            'Moniepoint Microfinance Bank' => 'Moniepoint Microfinance Bank',
            'OPay Digital Services' => 'OPay Digital Services',
            'PalmPay' => 'PalmPay',
            'Polaris Bank' => 'Polaris Bank',
            'PremiumTrust Bank' => 'PremiumTrust Bank',
            'Providus Bank' => 'Providus Bank',
            'Stanbic IBTC Bank' => 'Stanbic IBTC Bank',
            'Standard Chartered Bank' => 'Standard Chartered Bank',
            'Sterling Bank' => 'Sterling Bank',
            'SunTrust Bank' => 'SunTrust Bank',
            'Titan Trust Bank' => 'Titan Trust Bank',
            'Union Bank of Nigeria' => 'Union Bank of Nigeria',
            'United Bank for Africa (UBA)' => 'United Bank for Africa (UBA)',
            'Unity Bank' => 'Unity Bank',
            'VFD Microfinance Bank' => 'VFD Microfinance Bank',
            'Wema Bank (ALAT)' => 'Wema Bank (ALAT)',
            'Zenith Bank' => 'Zenith Bank',
        ];
    }

    public function mount(): void
    {
        $store = Filament::getTenant();
        if ($store) {
            $this->customerFeeType = $store->customer_funding_fee_type ?? 'free';
            $this->customerFeeAmount = (float) ($store->customer_funding_fee_amount ?? 0.0);
            $this->customerFeeCap = (float) ($store->customer_funding_fee_cap ?? 100.0);

            if ($store->owner) {
                $this->phone = $store->owner->phone ?? '';
                $this->kycNumber = $store->owner->nin ?? $store->owner->bvn ?? '';
                if (! empty($store->owner->bvn) && empty($store->owner->nin)) {
                    $this->kycType = 'bvn';
                }
            }

            // Pre-fill existing settlement account if any
            $settlement = SettlementAccount::where('store_id', $store->id)->latest()->first();
            if ($settlement) {
                $this->settlementBankName = $settlement->bank_name;
                $this->settlementAccountNumber = $settlement->account_number;
                $this->settlementAccountName = $settlement->account_name;
            }
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function setWithdrawalAmount(float $amount): void
    {
        $this->withdrawalAmount = $amount;
    }

    public function openWithdrawalModal(): void
    {
        $store = Filament::getTenant();
        if (! $store) {
            return;
        }

        $profitWallet = $store->profitWallet();
        $profitBalance = (float) ($profitWallet?->balance ?? 0.0);

        if ($profitBalance >= $this->minWithdrawal) {
            $this->withdrawalAmount = $profitBalance;
        } else {
            $this->withdrawalAmount = null;
        }

        $this->withdrawalModalOpen = true;
    }

    public function closeWithdrawalModal(): void
    {
        $this->withdrawalModalOpen = false;
    }

    public function openSettlementModal(): void
    {
        $this->settlementModalOpen = true;
    }

    public function closeSettlementModal(): void
    {
        $this->settlementModalOpen = false;
    }

    public function requestProfitWithdrawal(): void
    {
        $this->validate([
            'withdrawalAmount' => ['required', 'numeric', 'min:'.$this->minWithdrawal],
        ], [
            'withdrawalAmount.min' => 'Minimum profit withdrawal amount is ₦'.number_format($this->minWithdrawal, 2).'.',
        ]);

        $store = Filament::getTenant();
        if (! $store) {
            Notification::make()->title('Store context not found.')->danger()->send();

            return;
        }

        $settlement = SettlementAccount::where('store_id', $store->id)
            ->where('status', 'approved')
            ->where('is_active', true)
            ->first();

        if (! $settlement) {
            Notification::make()
                ->title('Active, Approved Settlement Bank Account Required')
                ->body('Your bank account must be approved before requesting a payout.')
                ->danger()
                ->send();

            return;
        }

        $profitWallet = $store->profitWallet();
        $profitBalance = (float) ($profitWallet?->balance ?? 0.0);
        $amount = (float) $this->withdrawalAmount;

        if ($profitBalance < $amount) {
            Notification::make()
                ->title('Insufficient Profit Balance')
                ->body('Available profit: ₦'.number_format($profitBalance, 2).'. Requested: ₦'.number_format($amount, 2).'.')
                ->danger()
                ->send();

            return;
        }

        $fee = $this->payoutFee;
        $netAmount = max(0, $amount - $fee);

        try {
            $reference = 'WTH_'.strtoupper(Str::random(10));
            $walletService = app(WalletService::class);

            // 1. Debit Merchant Profit Wallet
            $walletService->debit(
                $profitWallet,
                $amount,
                'profit_withdrawal',
                sprintf('Profit Payout Request to %s (%s - %s)', $settlement->account_name, $settlement->bank_name, $settlement->account_number),
                [
                    'bank_name' => $settlement->bank_name,
                    'account_number' => $settlement->account_number,
                    'account_name' => $settlement->account_name,
                    'payout_fee' => $fee,
                    'net_amount' => $netAmount,
                ],
                $reference
            );

            // 2. Create Withdrawal Record
            Withdrawal::create([
                'store_id' => $store->id,
                'amount' => $amount,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'bank_name' => $settlement->bank_name,
                'account_number' => $settlement->account_number,
                'account_name' => $settlement->account_name,
                'status' => 'pending',
                'reference' => $reference,
            ]);

            // 3. Log Activity
            ActivityLog::create([
                'tenant_id' => $store->id,
                'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                'causer_id' => Auth::id(),
                'event' => 'profit_withdrawal_requested',
                'description' => sprintf(
                    'Requested ₦%s profit payout (Fee: ₦%s, Net: ₦%s) to %s (%s - %s)',
                    number_format($amount, 2),
                    number_format($fee, 2),
                    number_format($netAmount, 2),
                    $settlement->account_name,
                    $settlement->bank_name,
                    $settlement->account_number
                ),
                'properties' => [
                    'amount' => $amount,
                    'fee' => $fee,
                    'net_amount' => $netAmount,
                    'bank' => $settlement->bank_name,
                    'account_number' => $settlement->account_number,
                ],
            ]);

            $this->withdrawalModalOpen = false;
            $this->withdrawalAmount = null;

            Notification::make()
                ->title('Profit Payout Request Submitted!')
                ->body(sprintf(
                    'Requested ₦%s (Fee: ₦%s, Net Payout: ₦%s) to %s. Payout reference: %s',
                    number_format($amount, 2),
                    number_format($fee, 2),
                    number_format($netAmount, 2),
                    $settlement->bank_name,
                    $reference
                ))
                ->success()
                ->persistent()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Withdrawal Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function saveSettlementAccount(): void
    {
        $this->validate([
            'settlementBankName' => 'required|string',
            'settlementAccountNumber' => ['required', 'regex:/^\d{10}$/'],
            'settlementAccountName' => 'required|string|min:3|max:150',
        ], [
            'settlementAccountNumber.regex' => 'NUBAN account number must be exactly 10 digits.',
        ]);

        $store = Filament::getTenant();
        if (! $store) {
            Notification::make()->title('Store context not found.')->danger()->send();

            return;
        }

        SettlementAccount::updateOrCreate(
            ['store_id' => $store->id],
            [
                'bank_name' => $this->settlementBankName,
                'account_number' => $this->settlementAccountNumber,
                'account_name' => $this->settlementAccountName,
                'status' => 'pending', // Awaits admin verification
                'is_active' => true,
            ]
        );

        ActivityLog::create([
            'tenant_id' => $store->id,
            'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
            'causer_id' => Auth::id(),
            'event' => 'settlement_account_submitted',
            'description' => sprintf(
                'Submitted settlement bank: %s (%s - %s) for approval',
                $this->settlementAccountName,
                $this->settlementBankName,
                $this->settlementAccountNumber
            ),
        ]);

        $this->settlementModalOpen = false;

        Notification::make()
            ->title('Settlement Account Submitted!')
            ->body('Your bank account has been saved and submitted for Super Admin verification. You will be able to request payouts once approved.')
            ->info()
            ->persistent()
            ->send();
    }

    public function saveFundingFeeSettings(): void
    {
        $this->validate([
            'customerFeeType' => 'required|in:free,flat,percentage',
            'customerFeeAmount' => 'required|numeric|min:0|max:100',
            'customerFeeCap' => 'required|numeric|min:0|max:200',
        ]);

        $store = Filament::getTenant();

        if (! $store) {
            Notification::make()->title('Store not found.')->danger()->send();

            return;
        }

        $store->customer_funding_fee_type = $this->customerFeeType;
        $store->customer_funding_fee_amount = $this->customerFeeType === 'free' ? 0.0 : $this->customerFeeAmount;
        $store->customer_funding_fee_cap = $this->customerFeeCap;
        $store->save();

        Notification::make()
            ->title('Customer Funding Fee Saved!')
            ->body('Your retail deposit policy has been updated.')
            ->success()
            ->send();
    }

    public function updatedKycType($value): void
    {
        $store = Filament::getTenant();
        if ($store && $store->owner) {
            if ($value === 'nin' && ! empty($store->owner->nin)) {
                $this->kycNumber = $store->owner->nin;
            } elseif ($value === 'bvn' && ! empty($store->owner->bvn)) {
                $this->kycNumber = $store->owner->bvn;
            }
        }
    }

    public function generateVirtualAccount(): void
    {
        $this->validate([
            'kycType' => 'required|in:nin,bvn',
            'kycNumber' => 'required|string|size:11',
            'phone' => 'required|string',
        ]);

        $store = Filament::getTenant();

        if (! $store) {
            Notification::make()->title('Store not found.')->danger()->send();

            return;
        }

        try {
            if ($store->owner && empty($store->owner->phone) && ! empty($this->phone)) {
                $store->owner->phone = $this->phone;
                $store->owner->save();
            }

            $payMintService = app(PayMintService::class);
            $account = $payMintService->createVirtualAccount(
                $store,
                $this->kycType,
                $this->kycNumber,
                $this->phone,
                'AffanHub - '.$store->name
            );

            Notification::make()
                ->title('Store Virtual Account Created!')
                ->body("Bank: {$account->bank_name} | Account Number: {$account->account_number}")
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Account Generation Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
