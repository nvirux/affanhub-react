<x-filament-widgets::widget>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $brandColor = $tenant?->primary_color ?: '#059669';
        $data = $this->getStepsData();
        $steps = $data['steps'];
        $progress = $data['progress'];
        $completedCount = $data['completedCount'];
        $totalCount = $data['totalCount'];
        $storeUrl = $data['storeUrl'];
    @endphp

    <style>
        .ah-slim-checklist {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            position: relative;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            font-family: inherit;
            box-sizing: border-box;
            width: 100%;
        }

        .ah-slim-accent {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background-color: {{ $brandColor }};
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .ah-slim-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            padding-bottom: 0.4rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .ah-slim-header-left {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 0;
            flex: 1;
        }

        .ah-slim-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            white-space: nowrap;
        }

        .ah-slim-progress-badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.1rem 0.45rem;
            border-radius: 9999px;
            background-color: {{ $brandColor }}15;
            color: {{ $brandColor }};
            white-space: nowrap;
        }

        .ah-slim-progress-badge.done {
            background-color: #ecfdf5;
            color: #047857;
        }

        .ah-slim-header-right {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            flex-shrink: 0;
        }

        .ah-slim-btn-toggle {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            color: #4b5563;
            font-size: 0.725rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            transition: all 0.15s;
            white-space: nowrap;
        }

        .ah-slim-btn-toggle:hover {
            color: #111827;
            background-color: #f3f4f6;
            border-color: #d1d5db;
        }

        .ah-slim-progress-bar-bg {
            width: 100%;
            height: 3px;
            background-color: #f3f4f6;
            border-radius: 9999px;
            overflow: hidden;
            margin-top: 0.4rem;
            margin-bottom: 0.4rem;
        }

        .ah-slim-progress-bar-fill {
            height: 100%;
            border-radius: 9999px;
            background-color: {{ $progress === 100 ? '#10b981' : $brandColor }};
            transition: width 0.35s ease;
        }

        .ah-slim-steps {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            margin-top: 0.35rem;
        }

        .ah-slim-step-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.4rem 0.6rem;
            border-radius: 8px;
            background-color: #f9fafb;
            border: 1px solid #f3f4f6;
            gap: 0.5rem;
            box-sizing: border-box;
        }

        .ah-slim-step-row:hover {
            background-color: #f3f4f6;
        }

        .ah-slim-step-left {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 0;
            flex: 1;
        }

        .ah-slim-circle {
            width: 20px;
            height: 20px;
            min-width: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.675rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .ah-slim-circle.completed {
            background-color: #10b981;
            color: #ffffff;
        }

        .ah-slim-circle.completed svg {
            width: 12px;
            height: 12px;
        }

        .ah-slim-circle.pending {
            border: 1.5px solid {{ $brandColor }};
            color: {{ $brandColor }};
            background-color: transparent;
        }

        .ah-slim-step-title {
            font-size: 0.775rem;
            font-weight: 600;
            color: #1f2937;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ah-slim-step-actions {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            flex-shrink: 0;
        }

        .ah-slim-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            padding: 0.25rem 0.65rem;
            border-radius: 6px;
            font-size: 0.725rem;
            font-weight: 600;
            background-color: {{ $brandColor }};
            color: #ffffff !important;
            text-decoration: none !important;
            border: none;
            cursor: pointer;
            white-space: nowrap;
            transition: opacity 0.15s;
        }

        .ah-slim-btn-primary:hover {
            opacity: 0.9;
        }

        .ah-slim-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            padding: 0.25rem 0.55rem;
            border-radius: 6px;
            font-size: 0.725rem;
            font-weight: 600;
            background-color: #ffffff;
            color: #4b5563 !important;
            border: 1px solid #e5e7eb;
            text-decoration: none !important;
            cursor: pointer;
            white-space: nowrap;
            transition: background-color 0.15s;
        }

        .ah-slim-btn-secondary:hover {
            background-color: #f3f4f6;
            color: #111827 !important;
        }

        .ah-slim-badge-done {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.45rem;
            border-radius: 9999px;
            font-size: 0.675rem;
            font-weight: 600;
            background-color: #ecfdf5;
            color: #059669;
            white-space: nowrap;
        }

        @media (max-width: 480px) {
            .ah-slim-checklist {
                padding: 0.6rem 0.75rem;
            }
            .ah-slim-step-title {
                font-size: 0.725rem;
            }
            .ah-slim-btn-primary, .ah-slim-btn-secondary {
                padding: 0.2rem 0.45rem;
                font-size: 0.7rem;
            }
        }
    </style>

    <div
        x-data="{
            isCollapsed: false,
            toggle() {
                this.isCollapsed = !this.isCollapsed;
            }
        }"
        class="ah-slim-checklist"
    >
        {{-- Accent Top Line in Store Brand Color --}}
        <div class="ah-slim-accent"></div>

        {{-- Clean Slim Header --}}
        <div class="ah-slim-header">
            <div class="ah-slim-header-left">
                <h3 class="ah-slim-title">
                    <x-filament::icon icon="heroicon-m-rocket-launch" style="width: 15px; height: 15px; color: {{ $brandColor }};" />
                    <span>Get Your Website Ready for Business</span>
                </h3>
                <span class="ah-slim-progress-badge {{ $progress === 100 ? 'done' : '' }}">
                    @if($progress === 100)
                        🎉 100% Ready
                    @else
                        {{ $completedCount }}/{{ $totalCount }} ({{ $progress }}%)
                    @endif
                </span>
            </div>

            {{-- Right Action: Collapse / Expand Button in place of Dismiss --}}
            <div class="ah-slim-header-right">
                <button
                    type="button"
                    @click="toggle()"
                    class="ah-slim-btn-toggle"
                    title="Collapse or expand checklist"
                >
                    <span x-text="isCollapsed ? 'Show' : 'Hide'"></span>
                    <x-filament::icon
                        icon="heroicon-m-chevron-down"
                        style="width: 12px; height: 12px; transition: transform 0.2s;"
                        x-bind:style="isCollapsed ? 'transform: rotate(0deg);' : 'transform: rotate(180deg);'"
                    />
                </button>
            </div>
        </div>

        {{-- Slim Progress Bar --}}
        <div class="ah-slim-progress-bar-bg">
            <div
                class="ah-slim-progress-bar-fill"
                style="width: {{ $progress }}%;"
            ></div>
        </div>

        {{-- Slim Clean Step Rows --}}
        <div
            x-show="!isCollapsed"
            x-collapse
            class="ah-slim-steps"
        >
            @foreach($steps as $index => $step)
                <div class="ah-slim-step-row">
                    
                    {{-- Left Side: Number/Check + Title --}}
                    <div class="ah-slim-step-left">
                        @if($step['completed'])
                            <span class="ah-slim-circle completed">
                                <x-filament::icon icon="heroicon-m-check" />
                            </span>
                        @else
                            <span class="ah-slim-circle pending">
                                {{ $index + 1 }}
                            </span>
                        @endif

                        <span class="ah-slim-step-title">
                            {{ $step['title'] }}
                        </span>
                    </div>

                    {{-- Right Side Action Button --}}
                    <div class="ah-slim-step-actions">
                        @if($step['id'] === 'join_community')
                            @if($step['completed'])
                                <span class="ah-slim-badge-done">
                                    Joined ✓
                                </span>
                            @else
                                <a
                                    href="{{ $step['url'] }}"
                                    target="_blank"
                                    x-data
                                    @click="$wire.markCommunityJoined()"
                                    class="ah-slim-btn-primary"
                                >
                                    <span>Join Group</span>
                                    <x-filament::icon icon="heroicon-m-arrow-top-right-on-square" style="width: 11px; height: 11px;" />
                                </a>
                            @endif
                        @elseif($step['id'] === 'first_customer')
                            <button
                                type="button"
                                x-data
                                @click="navigator.clipboard.writeText('{{ $storeUrl }}'); $wire.markLinkCopied()"
                                class="ah-slim-btn-secondary"
                                title="Copy website link"
                            >
                                <x-filament::icon icon="heroicon-m-clipboard-document" style="width: 11px; height: 11px; color: #9ca3af;" />
                                <span>Copy Link</span>
                            </button>

                            <a
                                href="{{ $step['url'] }}"
                                target="_blank"
                                class="ah-slim-btn-primary"
                            >
                                <span>{{ $step['action_label'] }}</span>
                                <x-filament::icon icon="heroicon-m-arrow-top-right-on-square" style="width: 11px; height: 11px;" />
                            </a>
                        @elseif($step['url'])
                            <a
                                href="{{ $step['url'] }}"
                                class="{{ $step['completed'] ? 'ah-slim-btn-secondary' : 'ah-slim-btn-primary' }}"
                            >
                                <span>{{ $step['action_label'] }}</span>
                            </a>
                        @else
                            <span class="ah-slim-badge-done">
                                Done ✓
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>
