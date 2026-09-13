<div class="space-y-3 py-1">
    <style>
        .fi-invite-card {
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 14px;
            padding: 14px 16px;
            background: #ffffff;
            transition: all 0.15s ease;
        }
        .dark .fi-invite-card {
            background: #111827;
            border-color: #1f2937;
        }
        .fi-invite-badge {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 2px 8px;
            border-radius: 6px;
            display: inline-block;
        }
        .fi-badge-manager {
            background: rgba(245, 158, 11, 0.12);
            color: #d97706;
            border: 1px solid rgba(245, 158, 11, 0.25);
        }
        .dark .fi-badge-manager {
            color: #fbbf24;
        }
        .fi-badge-staff {
            background: rgba(59, 130, 246, 0.12);
            color: #2563eb;
            border: 1px solid rgba(59, 130, 246, 0.25);
        }
        .dark .fi-badge-staff {
            color: #60a5fa;
        }
        .fi-action-link {
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            text-decoration: none;
            border: none;
            background: none;
            padding: 0;
            transition: opacity 0.15s ease;
        }
        .fi-action-link:hover {
            opacity: 0.8;
        }
        .fi-mini-icon {
            width: 14px !important;
            height: 14px !important;
            min-width: 14px !important;
            max-width: 14px !important;
            min-height: 14px !important;
            max-height: 14px !important;
            display: inline-block;
            vertical-align: middle;
        }
    </style>

    @if ($invitations->isEmpty())
        <div style="text-align: center; padding: 32px 16px; border: 1px dashed rgba(156, 163, 175, 0.3); border-radius: 14px;">
            <div style="width: 44px; height: 44px; border-radius: 50%; background: rgba(245, 158, 11, 0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                <svg class="fi-mini-icon" style="width: 22px !important; height: 22px !important; max-width: 22px !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h4 style="font-size: 14px; font-weight: 700; margin-bottom: 4px;">No Pending Invitations</h4>
            <p style="font-size: 12px; color: #6b7280;">All invited team members have already accepted or no invites are active.</p>
        </div>
    @else
        @foreach ($invitations as $invite)
            <div class="fi-invite-card">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;">
                    <div style="min-width: 0; flex: 1;">
                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <span style="font-size: 14px; font-weight: 800; color: inherit;">{{ $invite->email }}</span>
                            <span class="fi-invite-badge {{ $invite->role === 'manager' ? 'fi-badge-manager' : 'fi-badge-staff' }}">
                                {{ ucfirst($invite->role) }}
                            </span>
                        </div>
                        <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">
                            Invited {{ $invite->created_at->diffForHumans() }} • 
                            @if ($invite->isExpired())
                                <span style="color: #ef4444; font-weight: 700;">Expired</span>
                            @else
                                <span style="color: #10b981; font-weight: 600;">Expires in {{ $invite->expires_at->diffForHumans(null, true) }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 12px; padding-top: 10px; border-top: 1px solid rgba(226, 232, 240, 0.6);">
                    {{-- Copy Link Button --}}
                    <div x-data="{ copied: false }">
                        <button 
                            type="button"
                            @click="navigator.clipboard.writeText('{{ $invite->getAcceptUrl() }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            class="fi-action-link"
                            style="color: #f59e0b;"
                        >
                            <svg class="fi-mini-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                            <span x-text="copied ? 'Link Copied!' : 'Copy Invite Link'"></span>
                        </button>
                    </div>

                    <div style="display: flex; align-items: center; gap: 14px;">
                        {{-- Resend Button --}}
                        <button 
                            type="button" 
                            wire:click="resendInvitation({{ $invite->id }})"
                            class="fi-action-link"
                            style="color: #3b82f6;"
                        >
                            <svg class="fi-mini-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Resend Email</span>
                        </button>

                        <span style="color: #9ca3af;">•</span>

                        {{-- Revoke Button --}}
                        <button 
                            type="button" 
                            wire:click="revokeInvitation({{ $invite->id }})"
                            wire:confirm="Cancel and revoke this invitation for {{ $invite->email }}?"
                            class="fi-action-link"
                            style="color: #ef4444;"
                        >
                            <svg class="fi-mini-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Revoke</span>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
