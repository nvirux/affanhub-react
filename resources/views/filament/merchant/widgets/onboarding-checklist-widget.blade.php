<x-filament-widgets::widget>
    @php
        $data = $this->getStepsData();
        $steps = $data['steps'];
        $progress = $data['progress'];
        $completedCount = $data['completedCount'];
        $totalCount = $data['totalCount'];
        $storeUrl = $data['storeUrl'];
    @endphp

    <x-filament::section
        icon="heroicon-m-rocket-launch"
        icon-color="warning"
    >
        <x-slot name="heading">
            <div style="display: flex; align-items: center; gap: 0.625rem; flex-wrap: wrap;">
                <span style="font-weight: 800; font-size: 1rem;">Get Your Store Ready for Business</span>
                @if($progress === 100)
                    <x-filament::badge color="success" size="sm">
                        🎉 100% Launch Ready
                    </x-filament::badge>
                @endif
            </div>
        </x-slot>

        <x-slot name="description">
            Complete these essential setup steps to start selling data, airtime, and bill payments smoothly.
        </x-slot>

        <x-slot name="headerEnd">
            <x-filament::button
                wire:click="dismiss"
                color="gray"
                size="xs"
                icon="heroicon-m-x-mark"
                icon-position="after"
            >
                Dismiss
            </x-filament::button>
        </x-slot>

        {{-- Progress Bar --}}
        <div style="margin-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.4rem;">
                <span style="color: #64748b;">Setup Progress</span>
                <span style="color: #d97706; font-weight: 700;">{{ $completedCount }} of {{ $totalCount }} completed ({{ $progress }}%)</span>
            </div>
            <div style="width: 100%; height: 7px; background-color: rgba(100, 116, 139, 0.15); border-radius: 9999px; overflow: hidden;">
                <div style="width: {{ $progress }}%; height: 100%; background: linear-gradient(90deg, #f59e0b, #d97706); border-radius: 9999px; transition: width 0.4s ease;"></div>
            </div>
        </div>

        {{-- Steps List (One Clean Line per Step) --}}
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            @foreach($steps as $index => $step)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.625rem 0.875rem; border-radius: 10px; border: 1px solid rgba(100, 116, 139, 0.14); gap: 0.75rem; background-color: rgba(248, 250, 252, 0.6); flex-wrap: nowrap;">
                    
                    {{-- Left Side: Number/Check + Title + Description in ONE strictly horizontal line --}}
                    <div style="display: flex; align-items: center; gap: 0.65rem; flex: 1; min-width: 0; overflow: hidden;">
                        @if($step['completed'])
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #ecfdf5; color: #059669; font-weight: 900; font-size: 0.75rem; flex-shrink: 0;">
                                ✓
                            </span>
                        @else
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; border: 1.5px solid #94a3b8; color: #64748b; font-weight: 800; font-size: 0.7rem; flex-shrink: 0;">
                                {{ $index + 1 }}
                            </span>
                        @endif

                        <div style="min-width: 0; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <span style="font-size: 0.85rem; font-weight: 700; color: {{ $step['completed'] ? '#94a3b8' : 'inherit' }}; text-decoration: {{ $step['completed'] ? 'line-through' : 'none' }};">
                                {{ $step['title'] }}
                            </span>
                            <span style="font-size: 0.775rem; color: #64748b; margin-left: 0.35rem;">
                                · {{ $step['description'] }}
                            </span>
                        </div>
                    </div>

                    {{-- Right Side: Action Button --}}
                    <div style="display: flex; align-items: center; gap: 0.4rem; flex-shrink: 0;">
                        @if($step['id'] === 'share_store')
                            <x-filament::button
                                type="button"
                                color="gray"
                                size="xs"
                                icon="heroicon-m-clipboard-document"
                                x-data
                                @click="navigator.clipboard.writeText('{{ $storeUrl }}'); $wire.markLinkCopied()"
                            >
                                Copy Link
                            </x-filament::button>

                            <x-filament::button
                                tag="a"
                                href="{{ $step['url'] }}"
                                target="_blank"
                                color="warning"
                                size="xs"
                                icon="heroicon-m-arrow-top-right-on-square"
                                icon-position="after"
                            >
                                View Store
                            </x-filament::button>
                        @elseif($step['url'])
                            <x-filament::button
                                tag="a"
                                href="{{ $step['url'] }}"
                                color="{{ $step['completed'] ? 'gray' : 'warning' }}"
                                size="xs"
                            >
                                {{ $step['action_label'] }}
                            </x-filament::button>
                        @else
                            <x-filament::badge color="success" size="sm">
                                Completed ✓
                            </x-filament::badge>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
