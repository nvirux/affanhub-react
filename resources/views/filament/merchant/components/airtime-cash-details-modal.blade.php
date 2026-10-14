@props(['record'])

<div style="display: flex; flex-direction: column; gap: 16px; font-family: inherit; font-size: 13px;">

    {{-- Top Header Banner --}}
    <div style="
        display: flex; 
        flex-wrap: wrap; 
        align-items: center; 
        justify-content: space-between; 
        gap: 12px; 
        padding: 14px 18px; 
        border-radius: 12px; 
        border: 1px solid {{ match($record->status) {
            'approved' => '#a7f3d0',
            'rejected' => '#fecdd3',
            default => '#fde68a',
        } }};
        background: {{ match($record->status) {
            'approved' => 'rgba(16, 185, 129, 0.08)',
            'rejected' => 'rgba(244, 63, 94, 0.08)',
            default => 'rgba(245, 158, 11, 0.08)',
        } }};
    ">
        <div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">
                    Order Reference
                </span>
                <span style="font-family: monospace; font-weight: 900; font-size: 15px; color: #0f172a;" class="dark:text-white">
                    #{{ $record->reference }}
                </span>
            </div>
            <p style="font-size: 11px; color: #64748b; margin-top: 2px; margin-bottom: 0;">
                Submitted on {{ $record->created_at->format('M d, Y • h:i A') }} ({{ $record->created_at->diffForHumans() }})
            </p>
        </div>

        <div>
            @if($record->status === 'approved')
                <span style="
                    display: inline-flex; 
                    align-items: center; 
                    gap: 6px; 
                    padding: 4px 12px; 
                    border-radius: 9999px; 
                    font-size: 12px; 
                    font-weight: 800; 
                    background: #d1fae5; 
                    color: #065f46; 
                    border: 1px solid #6ee7b7;
                ">
                    <svg width="14" height="14" style="width: 14px; height: 14px; min-width: 14px;" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Approved & Paid
                </span>
            @elseif($record->status === 'rejected')
                <span style="
                    display: inline-flex; 
                    align-items: center; 
                    gap: 6px; 
                    padding: 4px 12px; 
                    border-radius: 9999px; 
                    font-size: 12px; 
                    font-weight: 800; 
                    background: #ffe4e6; 
                    color: #9f1239; 
                    border: 1px solid #fda4af;
                ">
                    <svg width="14" height="14" style="width: 14px; height: 14px; min-width: 14px;" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Rejected
                </span>
            @else
                <span style="
                    display: inline-flex; 
                    align-items: center; 
                    gap: 6px; 
                    padding: 4px 12px; 
                    border-radius: 9999px; 
                    font-size: 12px; 
                    font-weight: 800; 
                    background: #fef3c7; 
                    color: #92400e; 
                    border: 1px solid #fcd34d;
                ">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #d97706; display: inline-block;"></span>
                    Pending Review
                </span>
            @endif
        </div>
    </div>

    {{-- Financial Statistics Grid (4 Key Metrics) --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 10px;">
        <div style="padding: 12px 14px; border-radius: 12px; border: 1px solid #e2e8f0; background: rgba(248, 250, 252, 0.7);" class="dark:border-gray-800 dark:bg-gray-900/60">
            <span style="font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase;">Airtime Received</span>
            <div style="font-size: 16px; font-weight: 900; color: #0f172a; margin-top: 4px;" class="dark:text-white">
                ₦{{ number_format((float) $record->airtime_amount, 2) }}
            </div>
            <span style="font-size: 10px; color: #94a3b8;">Customer Transfer</span>
        </div>

        <div style="padding: 12px 14px; border-radius: 12px; border: 1px solid #e2e8f0; background: rgba(248, 250, 252, 0.7);" class="dark:border-gray-800 dark:bg-gray-900/60">
            <span style="font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase;">Payout Rate</span>
            <div style="font-size: 16px; font-weight: 900; color: #3b82f6; margin-top: 4px;">
                {{ number_format((float) $record->exchange_rate, 0) }}%
            </div>
            <span style="font-size: 10px; color: #94a3b8;">Customer gets {{ number_format((float) $record->exchange_rate, 0) }}%</span>
        </div>

        <div style="padding: 12px 14px; border-radius: 12px; border: 1px solid #a7f3d0; background: rgba(16, 185, 129, 0.08);" class="dark:border-emerald-800 dark:bg-emerald-950/20">
            <span style="font-size: 10px; font-weight: 700; color: #047857; text-transform: uppercase;">Cash Payout</span>
            <div style="font-size: 16px; font-weight: 900; color: #059669; margin-top: 4px;">
                ₦{{ number_format((float) $record->cash_amount, 2) }}
            </div>
            <span style="font-size: 10px; color: #059669; font-weight: 600;">You Pay Customer</span>
        </div>

        <div style="padding: 12px 14px; border-radius: 12px; border: 1px solid #e9d5ff; background: rgba(168, 85, 247, 0.08);" class="dark:border-purple-800 dark:bg-purple-950/20">
            <span style="font-size: 10px; font-weight: 700; color: #7e22ce; text-transform: uppercase;">Merchant Fee</span>
            <div style="font-size: 16px; font-weight: 900; color: #9333ea; margin-top: 4px;">
                ₦{{ number_format((float) ($record->airtime_amount - $record->cash_amount), 2) }}
            </div>
            <span style="font-size: 10px; color: #9333ea; font-weight: 600;">Your Margin ({{ 100 - (float) $record->exchange_rate }}%)</span>
        </div>
    </div>

    {{-- 2-Column Split: Telecom Transfer & Payout Method --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 12px;">

        {{-- Telecom Transfer Card --}}
        <div style="padding: 14px; border-radius: 12px; border: 1px solid #e2e8f0; background: #ffffff;" class="dark:border-gray-800 dark:bg-gray-900">
            <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #334155; margin-bottom: 10px;" class="dark:text-gray-200">
                📲 Telecom Transfer Verification
            </div>

            <div style="display: flex; flex-direction: column; gap: 8px; font-size: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px;" class="dark:border-gray-800">
                    <span style="color: #64748b;">Telecom Network:</span>
                    <span style="font-weight: 800; text-transform: uppercase; padding: 2px 8px; border-radius: 6px; background: #f1f5f9; color: #0f172a;" class="dark:bg-gray-800 dark:text-white">
                        {{ $record->network?->name ?? 'N/A' }}
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px;" class="dark:border-gray-800">
                    <span style="color: #64748b;">Customer Sender SIM:</span>
                    <span style="font-family: monospace; font-weight: 800; padding: 2px 8px; border-radius: 6px; background: #fef3c7; color: #b45309; border: 1px solid #fde68a;">
                        {{ $record->sender_phone }}
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px;" class="dark:border-gray-800">
                    <span style="color: #64748b;">Merchant Receiving SIM:</span>
                    <span style="font-family: monospace; font-weight: 700; color: #0f172a;" class="dark:text-white">
                        {{ $record->receiving_phone }}
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #64748b;">Customer Account:</span>
                    <span style="font-weight: 700; color: #0f172a;" class="dark:text-white">
                        {{ $record->user?->name ?? 'Direct Customer' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Payout Destination Card --}}
        <div style="padding: 14px; border-radius: 12px; border: 1px solid #e2e8f0; background: #ffffff;" class="dark:border-gray-800 dark:bg-gray-900">
            <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #334155; margin-bottom: 10px;" class="dark:text-gray-200">
                💳 Payout Destination
            </div>

            @if($record->payout_method === 'wallet')
                <div style="padding: 12px; border-radius: 10px; background: rgba(16, 185, 129, 0.08); border: 1px solid #a7f3d0;" class="dark:border-emerald-800 dark:bg-emerald-950/20">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-weight: 800; font-size: 12px; color: #065f46;" class="dark:text-emerald-300">
                            Store Wallet Payout
                        </span>
                        <span style="font-size: 10px; font-weight: 800; padding: 2px 6px; border-radius: 4px; background: #059669; color: #ffffff;">
                            Instant Credit
                        </span>
                    </div>
                    <p style="font-size: 11px; color: #047857; margin-top: 6px; margin-bottom: 0; line-height: 1.4;" class="dark:text-emerald-400">
                        When approved, <strong>₦{{ number_format((float) $record->cash_amount, 2) }}</strong> will be deposited directly into customer's store balance.
                    </p>
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 8px; font-size: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px;" class="dark:border-gray-800">
                        <span style="color: #64748b;">Payout Method:</span>
                        <span style="font-weight: 800; padding: 2px 8px; border-radius: 6px; background: #f3e8ff; color: #6b21a8; border: 1px solid #e9d5ff;">
                            Direct Bank Wire
                        </span>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px;" class="dark:border-gray-800">
                        <span style="color: #64748b;">Bank Name:</span>
                        <span style="font-weight: 800; color: #0f172a;" class="dark:text-white">
                            {{ $record->bank_name ?? 'N/A' }}
                        </span>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px;" class="dark:border-gray-800">
                        <span style="color: #64748b;">Account Number:</span>
                        <span style="font-family: monospace; font-weight: 900; font-size: 14px; padding: 2px 8px; border-radius: 6px; background: #f1f5f9; color: #0f172a;" class="dark:bg-gray-800 dark:text-white">
                            {{ $record->account_number ?? 'N/A' }}
                        </span>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #64748b;">Account Name:</span>
                        <span style="font-weight: 700; color: #0f172a;" class="dark:text-white">
                            {{ $record->account_name ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Transfer Proof Screenshot Section (if customer attached one) --}}
    @if($record->proof_image)
        <div style="padding: 14px; border-radius: 12px; border: 1px solid #e2e8f0; background: #ffffff;" class="dark:border-gray-800 dark:bg-gray-900">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #334155;" class="dark:text-gray-200">
                    📸 Customer Transfer Proof / USSD Receipt
                </span>
                <a 
                    href="{{ asset('storage/' . $record->proof_image) }}" 
                    target="_blank" 
                    rel="noopener noreferrer" 
                    style="font-size: 11px; font-weight: 700; color: #3b82f6; text-decoration: underline;"
                >
                    Open in Full Size &rarr;
                </a>
            </div>

            <div style="padding: 8px; border-radius: 8px; background: #f8fafc; border: 1px solid #f1f5f9; text-align: center;" class="dark:bg-gray-950 dark:border-gray-800">
                <img 
                    src="{{ asset('storage/' . $record->proof_image) }}" 
                    alt="Transfer Proof Screenshot" 
                    style="max-height: 280px; max-width: 100%; object-fit: contain; border-radius: 6px; margin: 0 auto; display: block;"
                />
            </div>
        </div>
    @endif

    {{-- Audit / Action Notes --}}
    @if($record->rejection_reason)
        <div style="padding: 12px 14px; border-radius: 10px; background: #fff1f2; border: 1px solid #fecdd3; font-size: 12px; color: #9f1239;">
            <strong style="display: block; margin-bottom: 2px;">Rejection Reason:</strong>
            {{ $record->rejection_reason }}
            @if($record->rejected_at)
                <div style="font-size: 10px; color: #e11d48; margin-top: 4px;">
                    Rejected at: {{ $record->rejected_at->format('M d, Y • h:i A') }}
                </div>
            @endif
        </div>
    @endif

    @if($record->admin_notes)
        <div style="padding: 12px 14px; border-radius: 10px; background: #f8fafc; border: 1px solid #e2e8f0; font-size: 12px; color: #475569;" class="dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">
            <strong style="display: block; margin-bottom: 2px;">Merchant Notes / Payment Session ID:</strong>
            {{ $record->admin_notes }}
        </div>
    @endif

</div>
