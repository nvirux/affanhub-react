<?php

namespace App\Services\Notification;

use App\Models\Domain;
use App\Models\Owner;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class MerchantNotificationService
{
    /**
     * Get all merchant recipients (Owner + Store Managers) for a store.
     *
     * @return Collection<int, Owner>
     */
    public function getStoreRecipients(Store $store): Collection
    {
        $recipients = collect();

        if ($store->owner) {
            $recipients->push($store->owner);
        }

        if (method_exists($store, 'members')) {
            $managers = $store->members()
                ->whereIn('role', ['owner', 'manager'])
                ->get();

            $recipients = $recipients->merge($managers);
        }

        return $recipients->unique('id');
    }

    /**
     * Notify merchant when a customer deposits money into their wallet.
     */
    public function notifyCustomerWalletFunded(
        User $customer,
        float $amount,
        string $reference,
        ?string $description = null
    ): void {
        try {
            $store = $customer->store;
            if (! $store) {
                return;
            }

            $recipients = $this->getStoreRecipients($store);
            if ($recipients->isEmpty()) {
                return;
            }

            $formattedAmount = number_format($amount, 2);
            $desc = $description ? " ({$description})" : '';

            $notification = Notification::make()
                ->title("Customer Wallet Funded (+₦{$formattedAmount})")
                ->body("{$customer->name} funded their wallet with ₦{$formattedAmount}{$desc}. Ref: {$reference}")
                ->icon('heroicon-o-banknotes')
                ->iconColor('success');

            foreach ($recipients as $recipient) {
                $notification->sendToDatabase($recipient);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to dispatch wallet funded merchant notification: '.$e->getMessage());
        }
    }

    /**
     * Notify merchant when a new customer registers on their storefront.
     */
    public function notifyNewCustomer(User $customer): void
    {
        try {
            $store = $customer->store;
            if (! $store) {
                return;
            }

            $recipients = $this->getStoreRecipients($store);
            if ($recipients->isEmpty()) {
                return;
            }

            $phoneInfo = $customer->phone ? " | {$customer->phone}" : '';

            $notification = Notification::make()
                ->title('New Customer Registered')
                ->body("{$customer->name} ({$customer->email}{$phoneInfo}) just created an account on your store.")
                ->icon('heroicon-o-user-plus')
                ->iconColor('info');

            foreach ($recipients as $recipient) {
                $notification->sendToDatabase($recipient);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to dispatch new customer merchant notification: '.$e->getMessage());
        }
    }

    /**
     * Notify merchant when a VTU transaction fails and is auto-refunded.
     */
    public function notifyTransactionFailed(Store $store, Transaction $transaction, string $reason): void
    {
        try {
            $recipients = $this->getStoreRecipients($store);
            if ($recipients->isEmpty()) {
                return;
            }

            $service = strtoupper($transaction->service_type ?? 'VTU');
            $recipientNumber = $transaction->recipient ?? ($transaction->phone ?? 'Customer');
            $cost = number_format((float) ($transaction->amount ?? 0), 2);

            $notification = Notification::make()
                ->title('Transaction Failed & Auto-Refunded')
                ->body("{$service} order for {$recipientNumber} (₦{$cost}) failed: {$reason}. Customer wallet was refunded.")
                ->icon('heroicon-o-exclamation-triangle')
                ->iconColor('danger');

            foreach ($recipients as $recipient) {
                $notification->sendToDatabase($recipient);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to dispatch transaction failed merchant notification: '.$e->getMessage());
        }
    }

    /**
     * Notify merchant when a custom domain is verified.
     */
    public function notifyDomainVerified(Store $store, Domain $domain): void
    {
        try {
            $recipients = $this->getStoreRecipients($store);
            if ($recipients->isEmpty()) {
                return;
            }

            $notification = Notification::make()
                ->title('Custom Domain Verified!')
                ->body("{$domain->domain} is now active and routing traffic to {$store->name}.")
                ->icon('heroicon-o-globe-alt')
                ->iconColor('success');

            foreach ($recipients as $recipient) {
                $notification->sendToDatabase($recipient);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to dispatch domain verified merchant notification: '.$e->getMessage());
        }
    }

    /**
     * Broadcast an announcement notification to a specific merchant owner.
     */
    public function notifyAnnouncement(
        Owner $owner,
        string $title,
        string $message,
        string $type = 'info'
    ): void {
        try {
            $icon = match ($type) {
                'success' => 'heroicon-o-check-circle',
                'warning' => 'heroicon-o-exclamation-circle',
                'danger' => 'heroicon-o-x-circle',
                default => 'heroicon-o-bell',
            };

            Notification::make()
                ->title($title)
                ->body($message)
                ->icon($icon)
                ->iconColor($type)
                ->sendToDatabase($owner);
        } catch (\Throwable $e) {
            Log::warning('Failed to dispatch announcement notification: '.$e->getMessage());
        }
    }
}
