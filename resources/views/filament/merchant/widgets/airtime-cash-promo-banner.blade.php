<div style="width: 100%; grid-column: 1 / -1;">
    @php
        $banner = $this->getBannerData();
    @endphp

    @if ($banner)
        <x-filament-widgets::widget>
            <style>
                .ac-minimal-banner {
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    justify-content: space-between;
                    gap: 1.25rem;
                    width: 100%;
                    box-sizing: border-box;
                    padding: 0.875rem 1.25rem;
                    border-radius: 12px;
                    background: #ffffff;
                    margin-bottom: 0.75rem;
                    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
                    transition: all 0.2s ease;
                }
                :is(.dark) .ac-minimal-banner {
                    background: #181826;
                }

                /* Type Variants */
                .ac-minimal-locked {
                    border: 1px solid #fee2e2;
                    border-left: 4px solid #f43f5e;
                }
                :is(.dark) .ac-minimal-locked {
                    border: 1px solid rgba(244, 63, 94, 0.25);
                    border-left: 4px solid #f43f5e;
                }

                .ac-minimal-promo {
                    border: 1px solid #fef3c7;
                    border-left: 4px solid #f59e0b;
                }
                :is(.dark) .ac-minimal-promo {
                    border: 1px solid rgba(245, 158, 11, 0.25);
                    border-left: 4px solid #f59e0b;
                }

                .ac-minimal-trial {
                    border: 1px solid #e0f2fe;
                    border-left: 4px solid #0ea5e9;
                }
                :is(.dark) .ac-minimal-trial {
                    border: 1px solid rgba(14, 165, 233, 0.25);
                    border-left: 4px solid #0ea5e9;
                }

                /* Content Area */
                .ac-minimal-content {
                    display: flex;
                    align-items: center;
                    gap: 0.875rem;
                    min-width: 0;
                    flex: 1;
                }

                .ac-minimal-icon-box {
                    width: 36px;
                    height: 36px;
                    min-width: 36px;
                    max-width: 36px;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.1rem;
                    flex-shrink: 0;
                }
                .ac-minimal-locked .ac-minimal-icon-box {
                    background: #fff1f2;
                }
                :is(.dark) .ac-minimal-locked .ac-minimal-icon-box {
                    background: rgba(244, 63, 94, 0.15);
                }

                .ac-minimal-promo .ac-minimal-icon-box {
                    background: #fffbeb;
                }
                :is(.dark) .ac-minimal-promo .ac-minimal-icon-box {
                    background: rgba(245, 158, 11, 0.15);
                }

                .ac-minimal-trial .ac-minimal-icon-box {
                    background: #f0f9ff;
                }
                :is(.dark) .ac-minimal-trial .ac-minimal-icon-box {
                    background: rgba(14, 165, 233, 0.15);
                }

                .ac-minimal-body {
                    min-width: 0;
                    flex: 1;
                }

                .ac-minimal-header-line {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    flex-wrap: wrap;
                }

                .ac-minimal-title {
                    font-size: 0.875rem;
                    font-weight: 700;
                    color: #0f172a;
                    margin: 0;
                    letter-spacing: -0.01em;
                }
                :is(.dark) .ac-minimal-title {
                    color: #f8fafc;
                }

                .ac-minimal-tag {
                    font-size: 0.65rem;
                    font-weight: 700;
                    letter-spacing: 0.04em;
                    text-transform: uppercase;
                    padding: 0.125rem 0.45rem;
                    border-radius: 6px;
                }
                .ac-minimal-locked .ac-minimal-tag {
                    background: #ffe4e6;
                    color: #e11d48;
                }
                :is(.dark) .ac-minimal-locked .ac-minimal-tag {
                    background: rgba(244, 63, 94, 0.2);
                    color: #fb7185;
                }

                .ac-minimal-promo .ac-minimal-tag {
                    background: #fef3c7;
                    color: #b45309;
                }
                :is(.dark) .ac-minimal-promo .ac-minimal-tag {
                    background: rgba(245, 158, 11, 0.2);
                    color: #fcd34d;
                }

                .ac-minimal-trial .ac-minimal-tag {
                    background: #e0f2fe;
                    color: #0369a1;
                }
                :is(.dark) .ac-minimal-trial .ac-minimal-tag {
                    background: rgba(14, 165, 233, 0.2);
                    color: #7dd3fc;
                }

                .ac-minimal-text {
                    font-size: 0.775rem;
                    color: #64748b;
                    margin: 0.15rem 0 0 0;
                    line-height: 1.45;
                }
                :is(.dark) .ac-minimal-text {
                    color: #94a3b8;
                }

                /* Minimal Button */
                .ac-minimal-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.45rem;
                    padding: 0.45rem 0.9rem;
                    border-radius: 9px;
                    font-size: 0.75rem;
                    font-weight: 700;
                    color: #0f172a;
                    background: #f1f5f9;
                    border: 1px solid #e2e8f0;
                    text-decoration: none;
                    white-space: nowrap;
                    flex-shrink: 0;
                    transition: all 0.15s ease;
                }
                :is(.dark) .ac-minimal-btn {
                    background: #27273a;
                    border: 1px solid #374151;
                    color: #f8fafc;
                }
                .ac-minimal-btn:hover {
                    background: #0f172a;
                    color: #ffffff;
                    border-color: #0f172a;
                }
                :is(.dark) .ac-minimal-btn:hover {
                    background: #ffffff;
                    color: #0f172a;
                    border-color: #ffffff;
                }
                .ac-minimal-btn svg {
                    width: 13px !important;
                    height: 13px !important;
                    min-width: 13px !important;
                    max-width: 13px !important;
                    display: inline-block;
                }

                @media (max-width: 640px) {
                    .ac-minimal-banner {
                        flex-direction: column;
                        align-items: flex-start;
                        gap: 0.75rem;
                    }
                    .ac-minimal-btn {
                        width: 100%;
                        justify-content: center;
                    }
                }
            </style>

            <div class="ac-minimal-banner ac-minimal-{{ $banner['type'] }}">
                <div class="ac-minimal-content">
                    <div class="ac-minimal-icon-box">
                        @if ($banner['type'] === 'promo')
                            🔥
                        @elseif ($banner['type'] === 'trial')
                            ⏱️
                        @else
                            🔒
                        @endif
                    </div>
                    <div class="ac-minimal-body">
                        <div class="ac-minimal-header-line">
                            <span class="ac-minimal-title">
                                @if ($banner['type'] === 'promo')
                                    Special Promotion Active
                                @elseif ($banner['type'] === 'trial')
                                    Free Trial Active
                                @else
                                    Plan Upgrade Required
                                @endif
                            </span>
                            <span class="ac-minimal-tag">{{ $banner['badge'] }}</span>
                        </div>
                        <p class="ac-minimal-text">{{ $banner['message'] }}</p>
                    </div>
                </div>

                @if (!empty($banner['action_url']))
                    <a href="{{ $banner['action_url'] }}" class="ac-minimal-btn">
                        <span>{{ $banner['action_label'] }}</span>
                        <svg style="width: 13px; height: 13px; min-width: 13px;" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                @endif
            </div>
        </x-filament-widgets::widget>
    @endif
</div>
