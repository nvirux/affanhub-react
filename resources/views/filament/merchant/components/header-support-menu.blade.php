@php
    $tenant = filament()->getTenant();
    $faqUrl = $tenant ? route('filament.merchant.pages.help-faq', ['tenant' => $tenant->public_id]) : '#';
    $blogUrl = rtrim(config('app.url'), '/') . '/blog';
@endphp

<div class="fi-topbar-support-container flex items-center">
    <x-filament::dropdown
        placement="bottom-end"
        teleport
        width="xs"
    >
        <x-slot name="trigger">
            <x-filament::icon-button
                color="gray"
                icon="heroicon-o-chat-bubble-left-right"
                icon-size="lg"
                label="Help & Support"
                tooltip="Help & Support"
                class="fi-topbar-support-btn"
            />
        </x-slot>

        <x-filament::dropdown.header
            color="gray"
            icon="heroicon-o-chat-bubble-left-right"
        >
            Help & Support
        </x-filament::dropdown.header>

        <x-filament::dropdown.list>
            {{-- 1. Direct 1-on-1 WhatsApp Support --}}
            <x-filament::dropdown.list.item
                tag="a"
                href="https://wa.me/2347011507950?text=Hello%20AffanHub%20Support%2C%20I%20need%20assistance%20with%20my%20store"
                target="_blank"
                rel="noopener noreferrer"
                icon="heroicon-o-chat-bubble-left-right"
                badge="07011507950"
                badge-color="success"
            >
                Talk to Support (WhatsApp)
            </x-filament::dropdown.list.item>

            {{-- 2. WhatsApp Community Group --}}
            <x-filament::dropdown.list.item
                tag="a"
                href="https://chat.whatsapp.com/L12iIqD89F82T04aD02yXN"
                target="_blank"
                rel="noopener noreferrer"
                icon="heroicon-o-user-group"
                badge="Community"
                badge-color="primary"
            >
                WhatsApp Community Group
            </x-filament::dropdown.list.item>

            {{-- 3. Help & FAQs --}}
            <x-filament::dropdown.list.item
                tag="a"
                :href="$faqUrl"
                icon="heroicon-o-question-mark-circle"
            >
                Help & FAQs
            </x-filament::dropdown.list.item>

            {{-- 4. Guides & Tutorials --}}
            <x-filament::dropdown.list.item
                tag="a"
                :href="$blogUrl"
                target="_blank"
                rel="noopener noreferrer"
                icon="heroicon-o-book-open"
            >
                Guides & Tutorials
            </x-filament::dropdown.list.item>

            {{-- 5. Email Support --}}
            <x-filament::dropdown.list.item
                tag="a"
                href="mailto:support@affanhub.com"
                icon="heroicon-o-envelope"
            >
                Email Support
            </x-filament::dropdown.list.item>
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
