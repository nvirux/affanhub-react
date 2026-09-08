<x-filament-panels::page>
    <div style="font-family: inherit; display: flex; flex-direction: column; gap: 1.75rem;">

        {{-- PLAN FEATURE LOCKED BANNER (Clean merchant style matching ManageServices & Billing) --}}
        @if (! $hasAccess)
            <div style="background-color: white; border: 1px solid #fde68a; border-radius: 16px; padding: 1.5rem 1.75rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <div style="width: 2.75rem; height: 2.75rem; border-radius: 12px; background-color: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; shrink-0;">
                            <svg style="width: 1.5rem; height: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <h3 style="font-size: 1rem; font-weight: 700; color: #111827; margin: 0;">Referral System is Locked</h3>
                                <span style="font-size: 0.6875rem; font-weight: 700; background-color: #fef3c7; color: #92400e; padding: 0.125rem 0.5rem; border-radius: 9999px; text-transform: uppercase;">Pro & Enterprise</span>
                            </div>
                            <p style="font-size: 0.8125rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">
                                Upgrade your store to the Pro (₦5,000/mo) or Enterprise plan to activate customer referrals, automated wallet bonuses, and the /earn storefront hub.
                            </p>
                        </div>
                    </div>

                    <a href="{{ \App\Filament\Merchant\Pages\Billing::getUrl() }}" style="display: inline-flex; align-items: center; gap: 0.375rem; background-color: #4f46e5; color: white; font-size: 0.8125rem; font-weight: 700; padding: 0.625rem 1.25rem; border-radius: 10px; text-decoration: none; box-shadow: 0 1px 3px rgba(79, 70, 229, 0.25);">
                        <span>Upgrade Plan</span>
                        <svg style="width: 0.875rem; height: 0.875rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>
        @endif

        {{-- METRICS ROW --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem;">
            <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);">
                <div style="font-size: 0.75rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.05em;">Total Referrals</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #111111; margin-top: 0.5rem; letter-spacing: -0.02em;">{{ number_format($totalReferrals) }}</div>
                <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;">Registered peers</div>
            </div>

            <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);">
                <div style="font-size: 0.75rem; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: 0.05em;">Completed</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #059669; margin-top: 0.5rem; letter-spacing: -0.02em;">{{ number_format($completedReferrals) }}</div>
                <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;">Qualified & rewarded</div>
            </div>

            <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);">
                <div style="font-size: 0.75rem; font-weight: 700; color: #d97706; text-transform: uppercase; letter-spacing: 0.05em;">Pending</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #d97706; margin-top: 0.5rem; letter-spacing: -0.02em;">{{ number_format($pendingReferrals) }}</div>
                <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;">Awaiting qualification</div>
            </div>

            <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);">
                <div style="font-size: 0.75rem; font-weight: 700; color: #4f46e5; text-transform: uppercase; letter-spacing: 0.05em;">Total Rewards Paid</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #4f46e5; margin-top: 0.5rem; letter-spacing: -0.02em;">₦{{ number_format($totalRewardsPaid, 2) }}</div>
                <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;">Credited to wallets</div>
            </div>
        </div>

        {{-- SETTINGS FORM CARD --}}
        <form wire:submit.prevent="saveSettings" style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1.75rem; @if(! $hasAccess) opacity: 0.6; pointer-events: none; @endif">
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Referral Program Rules</h3>
                <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">Configure reward amounts and the qualification rules required before bonuses are issued.</p>
            </div>

            <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                {{-- Program Toggle --}}
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; background-color: #f9fafb; border-radius: 12px; border: 1px solid #f3f4f6;">
                    <div>
                        <div style="font-size: 0.875rem; font-weight: 700; color: #1e293b;">Enable Referral Program</div>
                        <div style="font-size: 0.75rem; color: #64748b;">Allow registered customers to access the /earn page and share their invite code.</div>
                    </div>
                    <label style="position: relative; display: inline-flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" wire:model="isEnabled" style="width: 1.25rem; height: 1.25rem; accent-color: #4f46e5; cursor: pointer;">
                    </label>
                </div>

                {{-- Reward Amount --}}
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">
                        Reward per Qualified Referral (₦)
                    </label>
                    <div style="position: relative; max-width: 320px;">
                        <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); font-weight: 700; color: #9ca3af;">₦</span>
                        <input type="number" step="1" wire:model="rewardAmount" style="width: 100%; padding: 0.625rem 1rem 0.625rem 2.25rem; border-radius: 10px; border: 1px solid #d1d5db; font-size: 0.9375rem; font-weight: 600; color: #111827;" placeholder="50">
                    </div>
                    <span style="font-size: 0.75rem; color: #6b7280;">Credited directly to the referrer's main wallet balance when qualification condition is met.</span>
                    @error('rewardAmount') <span style="font-size: 0.75rem; color: #dc2626;">{{ $message }}</span> @enderror
                </div>

                {{-- Condition Type --}}
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">
                        Qualification Condition
                    </label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                        <label style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 1rem; border-radius: 12px; border: 1.5px solid {{ $conditionType === 'first_deposit' ? '#4f46e5' : '#e5e7eb' }}; background-color: {{ $conditionType === 'first_deposit' ? '#f5f3ff' : '#ffffff' }}; cursor: pointer; transition: all 0.2s;">
                            <input type="radio" value="first_deposit" wire:model.live="conditionType" style="margin-top: 0.25rem; accent-color: #4f46e5;">
                            <div>
                                <div style="font-size: 0.875rem; font-weight: 700; color: #1e293b;">First Wallet Deposit</div>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Reward triggers when the new user funds their wallet with at least the minimum deposit amount.</div>
                            </div>
                        </label>

                        <label style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 1rem; border-radius: 12px; border: 1.5px solid {{ $conditionType === 'first_purchase' ? '#4f46e5' : '#e5e7eb' }}; background-color: {{ $conditionType === 'first_purchase' ? '#f5f3ff' : '#ffffff' }}; cursor: pointer; transition: all 0.2s;">
                            <input type="radio" value="first_purchase" wire:model.live="conditionType" style="margin-top: 0.25rem; accent-color: #4f46e5;">
                            <div>
                                <div style="font-size: 0.875rem; font-weight: 700; color: #1e293b;">First Purchase / Recharge</div>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Reward triggers when the new user successfully buys airtime or data on your storefront.</div>
                            </div>
                        </label>
                    </div>
                    @error('conditionType') <span style="font-size: 0.75rem; color: #dc2626;">{{ $message }}</span> @enderror
                </div>

                {{-- Minimum Deposit (if first_deposit selected) --}}
                @if ($conditionType === 'first_deposit')
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; background-color: #f9fafb; padding: 1.25rem; border-radius: 12px; border: 1px solid #f3f4f6;">
                        <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">
                            Minimum Qualifying Deposit (₦)
                        </label>
                        <div style="position: relative; max-width: 320px;">
                            <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); font-weight: 700; color: #9ca3af;">₦</span>
                            <input type="number" step="50" wire:model="minDepositAmount" style="width: 100%; padding: 0.625rem 1rem 0.625rem 2.25rem; border-radius: 10px; border: 1px solid #d1d5db; font-size: 0.9375rem; font-weight: 600; color: #111827;" placeholder="500">
                        </div>
                        <span style="font-size: 0.75rem; color: #64748b;">Deposits below this amount will not trigger the referral bonus.</span>
                        @error('minDepositAmount') <span style="font-size: 0.75rem; color: #dc2626;">{{ $message }}</span> @enderror
                    </div>
                @endif
            </div>

            <div style="display: flex; justify-content: flex-end; padding-top: 1rem; border-top: 1px solid #f3f4f6;">
                <button type="submit" wire:loading.attr="disabled" style="display: inline-flex; align-items: center; gap: 0.5rem; background-color: #4f46e5; color: white; font-weight: 700; font-size: 0.875rem; padding: 0.625rem 1.5rem; border-radius: 10px; border: none; cursor: pointer; box-shadow: 0 1px 3px rgba(79, 70, 229, 0.25);">
                    <span wire:loading.remove>Save Settings</span>
                    <span wire:loading>Saving...</span>
                </button>
            </div>
        </form>

        {{-- RECENT REFERRALS TABLE CARD --}}
        <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Recent Referral Activity</h3>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">Latest referral sign-ups across your store.</p>
                </div>
            </div>

            @if (count($recentReferrals) === 0)
                <div style="text-align: center; padding: 3rem 1rem; color: #9ca3af;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🎁</div>
                    <div style="font-size: 0.875rem; font-weight: 600; color: #4b5563;">No referral activity yet</div>
                    <div style="font-size: 0.75rem; margin-top: 0.25rem;">When customers invite their peers, the registration records will appear here.</div>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table style="width: 100%; text-align: left; border-collapse: collapse; font-size: 0.875rem;">
                        <thead>
                            <tr style="border-bottom: 1px solid #f1f5f9; color: #6b7280; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                <th style="padding: 0.75rem 1rem;">Referrer</th>
                                <th style="padding: 0.75rem 1rem;">Referred Customer</th>
                                <th style="padding: 0.75rem 1rem;">Status</th>
                                <th style="padding: 0.75rem 1rem;">Reward</th>
                                <th style="padding: 0.75rem 1rem;">Registered</th>
                                <th style="padding: 0.75rem 1rem;">Completed At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentReferrals as $ref)
                                <tr style="border-bottom: 1px solid #f9fafb;">
                                    <td style="padding: 0.875rem 1rem;">
                                        <div style="font-weight: 600; color: #111827;">{{ $ref['referrer_name'] }}</div>
                                        <div style="font-size: 0.75rem; color: #9ca3af;">{{ $ref['referrer_email'] }}</div>
                                    </td>
                                    <td style="padding: 0.875rem 1rem;">
                                        <div style="font-weight: 600; color: #111827;">{{ $ref['referred_name'] }}</div>
                                        <div style="font-size: 0.75rem; color: #9ca3af;">{{ $ref['referred_email'] }}</div>
                                    </td>
                                    <td style="padding: 0.875rem 1rem;">
                                        @if ($ref['status'] === 'completed')
                                            <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 700; color: #065f46; background-color: #d1fae5;">
                                                Completed
                                            </span>
                                        @else
                                            <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 700; color: #92400e; background-color: #fef3c7;">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 0.875rem 1rem; font-weight: 700; color: #111827;">
                                        ₦{{ number_format($ref['reward_amount'], 2) }}
                                    </td>
                                    <td style="padding: 0.875rem 1rem; color: #6b7280; font-size: 0.8125rem;">
                                        {{ $ref['created_at'] }}
                                    </td>
                                    <td style="padding: 0.875rem 1rem; color: #6b7280; font-size: 0.8125rem;">
                                        {{ $ref['completed_at'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</x-filament-panels::page>
