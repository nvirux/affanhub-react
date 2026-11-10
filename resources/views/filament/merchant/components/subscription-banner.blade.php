@php
    $tenant = \Filament\Facades\Filament::getTenant();
    $subscription = $tenant?->subscription;
@endphp

@if($subscription && ($subscription->isInGracePeriod() || $subscription->isExpiringSoon(3)))
    @php
        $isInGrace = $subscription->isInGracePeriod();
        $planName = $subscription->plan?->name ?? 'Pro';
        $billingUrl = \App\Filament\Merchant\Pages\Billing::getUrl(['tenant' => $tenant->public_id]);
    @endphp

    <div style="margin-bottom: 1.25rem;">
        @if($isInGrace)
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border: 1.5px solid #f87171; border-radius: 12px; padding: 0.875rem 1.25rem; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.08);" class="dark:bg-red-950/40 dark:border-red-700/60">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; background: #fee2e2; color: #dc2626; font-size: 1.1rem; flex-shrink: 0;" class="dark:bg-red-900/50 dark:text-red-300">
                        🚨
                    </span>
                    <div>
                        <div style="font-size: 0.875rem; font-weight: 800; color: #991b1b;" class="dark:text-red-300">
                            Grace Period Active: {{ $planName }} Plan Expired
                        </div>
                        <div style="font-size: 0.8125rem; color: #7f1d1d; margin-top: 0.125rem;" class="dark:text-red-200">
                            You have <strong>{{ $subscription->daysUntilGraceEnd() }} {{ \Illuminate\Support\Str::plural('day', $subscription->daysUntilGraceEnd()) }}</strong> remaining until <strong>{{ $subscription->grace_ends_at?->format('M j, Y') }}</strong> before your store transitions to the Starter Plan.
                        </div>
                    </div>
                </div>

                <a href="{{ $billingUrl }}" style="display: inline-flex; align-items: center; gap: 0.375rem; background: #dc2626; color: #ffffff; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 700; text-decoration: none; box-shadow: 0 1px 3px rgba(220, 38, 38, 0.3); transition: background 0.15s ease;" onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                    <span>Renew Plan Now</span>
                    <span style="font-weight: 800;">&rarr;</span>
                </a>
            </div>
        @else
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border: 1.5px solid #fcd34d; border-radius: 12px; padding: 0.875rem 1.25rem; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.08);" class="dark:bg-amber-950/40 dark:border-amber-700/60">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; background: #fef3c7; color: #d97706; font-size: 1.1rem; flex-shrink: 0;" class="dark:bg-amber-900/50 dark:text-amber-300">
                        ⏳
                    </span>
                    <div>
                        <div style="font-size: 0.875rem; font-weight: 800; color: #92400e;" class="dark:text-amber-300">
                            {{ $planName }} Plan Expires in {{ $subscription->daysUntilExpiration() }} {{ \Illuminate\Support\Str::plural('Day', $subscription->daysUntilExpiration()) }}
                        </div>
                        <div style="font-size: 0.8125rem; color: #78350f; margin-top: 0.125rem;" class="dark:text-amber-200">
                            Renew by <strong>{{ $subscription->ends_at?->format('F j, Y') }}</strong> to maintain custom domain routing and wholesale data pricing without disruption.
                        </div>
                    </div>
                </div>

                <a href="{{ $billingUrl }}" style="display: inline-flex; align-items: center; gap: 0.375rem; background: #d97706; color: #ffffff; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 700; text-decoration: none; box-shadow: 0 1px 3px rgba(217, 119, 6, 0.3); transition: background 0.15s ease;" onmouseover="this.style.background='#b45309'" onmouseout="this.style.background='#d97706'">
                    <span>Renew Subscription</span>
                    <span style="font-weight: 800;">&rarr;</span>
                </a>
            </div>
        @endif
    </div>
@endif
