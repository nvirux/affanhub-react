<x-filament-panels::page>
    <style>
        .faq-wrap {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            max-width: 960px;
            margin: 0 auto;
            width: 100%;
        }

        /* HEADER CARD */
        .faq-header-card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .dark .faq-header-card {
            background-color: #111827;
            border-color: #1f2937;
        }

        @media (min-width: 640px) {
            .faq-header-card {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .faq-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
            line-height: 1.3;
        }

        .dark .faq-title {
            color: #f9fafb;
        }

        .faq-subtitle {
            font-size: 0.8125rem;
            color: #6b7280;
            margin: 4px 0 0 0;
        }

        .dark .faq-subtitle {
            color: #9ca3af;
        }

        /* SEARCH INPUT */
        .faq-search-wrapper {
            position: relative;
            width: 100%;
            max-width: 320px;
        }

        .faq-search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px !important;
            height: 16px !important;
            min-width: 16px !important;
            max-width: 16px !important;
            min-height: 16px !important;
            max-height: 16px !important;
            color: #9ca3af;
            pointer-events: none;
        }

        .faq-search-input {
            width: 100%;
            padding: 8px 14px 8px 36px;
            font-size: 0.8125rem;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            background-color: #ffffff;
            color: #111827;
            outline: none;
            transition: all 0.15s ease;
            box-sizing: border-box;
        }

        .dark .faq-search-input {
            background-color: #1f2937;
            border-color: #374151;
            color: #f9fafb;
        }

        .faq-search-input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
        }

        /* CATEGORY TABS */
        .faq-tabs-row {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            border-top: 1px solid #f3f4f6;
            padding-top: 0.75rem;
            margin-top: 0.75rem;
        }

        .dark .faq-tabs-row {
            border-top-color: #1f2937;
        }

        .faq-tab-btn {
            padding: 5px 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 6px;
            border: none;
            background-color: transparent;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .dark .faq-tab-btn {
            color: #9ca3af;
        }

        .faq-tab-btn:hover {
            background-color: #f3f4f6;
            color: #111827;
        }

        .dark .faq-tab-btn:hover {
            background-color: #1f2937;
            color: #ffffff;
        }

        .faq-tab-btn.active {
            background-color: #fef3c7;
            color: #92400e;
            font-weight: 600;
        }

        .dark .faq-tab-btn.active {
            background-color: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
        }

        /* ACCORDION ITEMS */
        .faq-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .faq-item {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            transition: border-color 0.15s ease;
        }

        .dark .faq-item {
            background-color: #111827;
            border-color: #1f2937;
        }

        .faq-item:hover {
            border-color: #cbd5e1;
        }

        .dark .faq-item:hover {
            border-color: #374151;
        }

        .faq-item-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            text-align: left;
            background: transparent;
            border: none;
            cursor: pointer;
            gap: 12px;
        }

        .faq-item-cat {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-bottom: 2px;
        }

        .dark .faq-item-cat {
            color: #9ca3af;
        }

        .faq-item-q {
            font-size: 0.875rem;
            font-weight: 600;
            color: #111827;
        }

        .dark .faq-item-q {
            color: #f9fafb;
        }

        .faq-chevron {
            width: 18px !important;
            height: 18px !important;
            min-width: 18px !important;
            max-width: 18px !important;
            min-height: 18px !important;
            max-height: 18px !important;
            flex-shrink: 0;
            color: #9ca3af;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .faq-item-btn.open .faq-chevron {
            transform: rotate(180deg);
            color: #f59e0b;
        }

        .faq-item-ans {
            padding: 14px 18px;
            border-top: 1px solid #f3f4f6;
            background-color: #fcfcfd;
            font-size: 0.8125rem;
            line-height: 1.6;
            color: #4b5563;
            white-space: pre-line;
        }

        .dark .faq-item-ans {
            background-color: rgba(17, 24, 39, 0.4);
            border-top-color: #1f2937;
            color: #cbd5e1;
        }

        /* BLOG GUIDES */
        .faq-guides-section {
            margin-top: 0.5rem;
        }

        .faq-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .faq-section-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .dark .faq-section-title {
            color: #f9fafb;
        }

        .faq-section-desc {
            font-size: 0.75rem;
            color: #6b7280;
            margin: 2px 0 0 0;
        }

        .dark .faq-section-desc {
            color: #9ca3af;
        }

        .faq-blog-link {
            font-size: 0.75rem;
            font-weight: 600;
            color: #f59e0b;
            text-decoration: none;
        }

        .faq-blog-link:hover {
            text-decoration: underline;
        }

        .faq-guides-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        @media (min-width: 640px) {
            .faq-guides-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .faq-guide-card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px 16px;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.15s ease;
        }

        .dark .faq-guide-card {
            background-color: #111827;
            border-color: #1f2937;
        }

        .faq-guide-card:hover {
            border-color: #f59e0b;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
        }

        .faq-guide-tag {
            font-size: 0.625rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #d97706;
            margin-bottom: 4px;
        }

        .dark .faq-guide-tag {
            color: #fbbf24;
        }

        .faq-guide-name {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
            line-height: 1.35;
        }

        .dark .faq-guide-name {
            color: #f9fafb;
        }

        .faq-guide-desc {
            font-size: 0.75rem;
            color: #6b7280;
            line-height: 1.45;
        }

        .dark .faq-guide-desc {
            color: #9ca3af;
        }

        /* SUPPORT FOOTER */
        .faq-support-box {
            background-color: #ffffff;
            border: 1px dashed #d1d5db;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            font-size: 0.75rem;
            color: #4b5563;
        }

        .dark .faq-support-box {
            background-color: #111827;
            border-color: #374151;
            color: #9ca3af;
        }

        @media (min-width: 640px) {
            .faq-support-box {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .faq-whatsapp-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #059669;
            text-decoration: none;
            padding: 4px 10px;
            border-radius: 6px;
            background-color: #ecfdf5;
            transition: background-color 0.15s ease;
            width: fit-content;
        }

        .dark .faq-whatsapp-btn {
            background-color: rgba(5, 150, 105, 0.15);
            color: #34d399;
        }

        .faq-whatsapp-btn:hover {
            background-color: #d1fae5;
        }

        /* EMPTY STATE */
        .faq-empty {
            background-color: #ffffff;
            border: 1px dashed #e5e7eb;
            border-radius: 12px;
            padding: 2.5rem 1rem;
            text-align: center;
        }

        .dark .faq-empty {
            background-color: #111827;
            border-color: #1f2937;
        }
    </style>

    <div class="faq-wrap" x-data="{ openFaq: null }">

        {{-- CLEAN HEADER CARD --}}
        <div class="faq-header-card">
            <div>
                <h1 class="faq-title">Merchant Help & Knowledge Hub</h1>
                <p class="faq-subtitle">
                    Instant answers on customer float capital, automated failovers, profit withdrawals, and store operations.
                </p>

                {{-- FILTER TABS --}}
                <div class="faq-tabs-row">
                    <button
                        type="button"
                        wire:click="selectCategory('all')"
                        class="faq-tab-btn {{ $activeCategory === 'all' ? 'active' : '' }}"
                    >
                        All Questions
                    </button>
                    <button
                        type="button"
                        wire:click="selectCategory('capital')"
                        class="faq-tab-btn {{ $activeCategory === 'capital' ? 'active' : '' }}"
                    >
                        ₦0 Capital & Float
                    </button>
                    <button
                        type="button"
                        wire:click="selectCategory('operations')"
                        class="faq-tab-btn {{ $activeCategory === 'operations' ? 'active' : '' }}"
                    >
                        Operations & Failover
                    </button>
                    <button
                        type="button"
                        wire:click="selectCategory('pricing')"
                        class="faq-tab-btn {{ $activeCategory === 'pricing' ? 'active' : '' }}"
                    >
                        Pricing & Wholesale
                    </button>
                    <button
                        type="button"
                        wire:click="selectCategory('branding')"
                        class="faq-tab-btn {{ $activeCategory === 'branding' ? 'active' : '' }}"
                    >
                        Domains & Branding
                    </button>
                </div>
            </div>

            {{-- SEARCH BOX --}}
            <div class="faq-search-wrapper">
                <svg class="faq-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.250ms="search"
                    placeholder="Search keywords..."
                    class="faq-search-input"
                />
            </div>
        </div>

        {{-- FAQS ACCORDION LIST --}}
        <div class="faq-list">
            @forelse ($this->faqs as $faq)
                <div class="faq-item">
                    <button
                        type="button"
                        class="faq-item-btn"
                        :class="openFaq === {{ $faq->id }} ? 'open' : ''"
                        @click="openFaq = openFaq === {{ $faq->id }} ? null : {{ $faq->id }}"
                    >
                        <div>
                            <div class="faq-item-cat">
                                @switch($faq->category)
                                    @case('capital')
                                        ₦0 Capital & Float
                                        @break
                                    @case('operations')
                                        Operations & Failover
                                        @break
                                    @case('pricing')
                                        Pricing & Margins
                                        @break
                                    @case('branding')
                                        Domains & Branding
                                        @break
                                    @default
                                        General
                                @endswitch
                            </div>
                            <div class="faq-item-q">{{ $faq->question }}</div>
                        </div>

                        <svg class="faq-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        x-show="openFaq === {{ $faq->id }}"
                        x-collapse
                        class="faq-item-ans"
                    >
                        {{ $faq->answer }}
                    </div>
                </div>
            @empty
                <div class="faq-empty">
                    <div style="font-size: 0.875rem; font-weight: 600; color: #111827;" class="dark:text-white">
                        No questions matched your search
                    </div>
                    <div style="font-size: 0.75rem; color: #6b7280; margin-top: 4px;">
                        Try different keywords or select "All Questions" to browse everything.
                    </div>
                </div>
            @endforelse
        </div>

        {{-- IN-DEPTH BLOG GUIDES --}}
        <div class="faq-guides-section">
            <div class="faq-section-header">
                <div>
                    <h3 class="faq-section-title">Knowledge Hub Guides & Tutorials</h3>
                    <p class="faq-section-desc">Practical masterclasses from our blog to help you grow store revenue.</p>
                </div>
                <a
                    href="{{ rtrim(config('app.url'), '/') }}/blog"
                    target="_blank"
                    class="faq-blog-link"
                >
                    Visit Blog &rarr;
                </a>
            </div>

            @if ($this->guides->isNotEmpty())
                <div class="faq-guides-grid">
                    @foreach ($this->guides as $guide)
                        <a
                            href="{{ rtrim(config('app.url'), '/') }}/blog/{{ $guide->slug }}"
                            target="_blank"
                            class="faq-guide-card"
                        >
                            <div>
                                <div class="faq-guide-tag">{{ $guide->category ?? 'Guide' }}</div>
                                <div class="faq-guide-name">{{ $guide->title }}</div>
                                <div class="faq-guide-desc">{{ $guide->excerpt }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- WHATSAPP SUPPORT BANNER --}}
        <div class="faq-support-box">
            <div>
                <strong style="color: #111827;" class="dark:text-white">Still have questions?</strong>
                Join our official merchant WhatsApp community for live telecom uptime alerts, provider rate drops, and direct support.
            </div>
            <a
                href="https://chat.whatsapp.com/L12iIqD89F82T04aD02yXN"
                target="_blank"
                class="faq-whatsapp-btn"
            >
                Join WhatsApp Group &rarr;
            </a>
        </div>

    </div>
</x-filament-panels::page>
