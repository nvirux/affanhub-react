<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        {{-- Tenant Favicon or System Default --}}
        @if(tenant() && tenant('favicon_path'))
            <link rel="icon" href="{{ \Illuminate\Support\Facades\Storage::url(tenant('favicon_path')) }}" sizes="any">
            <link rel="apple-touch-icon" href="{{ \Illuminate\Support\Facades\Storage::url(tenant('favicon_path')) }}">
        @else
            <link rel="icon" href="/affan-icon.png" type="image/png">
            <link rel="shortcut icon" href="/affan-icon.png" type="image/png">
            <link rel="apple-touch-icon" href="/affan-icon.png">
        @endif

        {{-- Dynamic App Name for Inertia Head and Title Resolvers --}}
        <meta name="app-name" content="{{ tenant() ? tenant('name') : config('app.name', 'AffanHub') }}">

        @php
            $post = $page['props']['post'] ?? null;
            $appName = tenant() ? tenant('name') : config('app.name', 'AffanHub');

            if ($post) {
                $metaTitle = ($post['seo_title'] ?? $post['title']) . ' — ' . $appName;
                $metaDescription = $post['seo_description'] ?? $post['excerpt'] ?? 'Discover insights, guides, and updates from ' . $appName;

                $metaImage = null;
                if (!empty($post['cover_image_url'])) {
                    $metaImage = str_starts_with($post['cover_image_url'], 'http')
                        ? $post['cover_image_url']
                        : url($post['cover_image_url']);
                } else {
                    $metaImage = url('/affan-icon.png');
                }

                $metaUrl = url('/blog/' . ($post['slug'] ?? ''));
                $metaType = 'article';
            } else {
                $metaTitle = tenant() ? tenant('name') : config('app.name', 'AffanHub');
                $metaDescription = tenant()
                    ? (tenant('tagline') ?? 'Fast & Reliable Telecom Services')
                    : 'All-in-one automated VTU & digital telecom reselling platform. Sell airtime, data, cable TV, and electricity with instant delivery.';

                $metaImage = null;
                if (tenant() && tenant('logo_path')) {
                    $metaImage = \Illuminate\Support\Facades\Storage::url(tenant('logo_path'));
                    if (!str_starts_with($metaImage, 'http')) {
                        $metaImage = url($metaImage);
                    }
                } else {
                    $metaImage = url('/affan-icon.png');
                }

                $metaUrl = request()->url();
                $metaType = 'website';
            }
        @endphp

        {{-- Server-Rendered SEO & Social Meta Tags (Read immediately by WhatsApp, Facebook, Googlebot, Twitter) --}}
        <meta name="description" content="{{ $metaDescription }}">
        <link rel="canonical" href="{{ $metaUrl }}">

        <!-- Open Graph / WhatsApp / Facebook -->
        <meta property="og:type" content="{{ $metaType }}">
        <meta property="og:site_name" content="{{ $appName }}">
        <meta property="og:url" content="{{ $metaUrl }}">
        <meta property="og:title" content="{{ $metaTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:image" content="{{ $metaImage }}">
        <meta property="og:image:secure_url" content="{{ $metaImage }}">
        <meta property="og:image:alt" content="{{ $metaTitle }}">

        <!-- Twitter / X Cards -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ $metaUrl }}">
        <meta name="twitter:title" content="{{ $metaTitle }}">
        <meta name="twitter:description" content="{{ $metaDescription }}">
        <meta name="twitter:image" content="{{ $metaImage }}">

        {{-- Mobile App & Browser Theme Color for Dynamic Status Bar --}}
        @php
            $storeThemeColor = tenant() && tenant('primary_color') ? tenant('primary_color') : '#10b981';
        @endphp
        <meta name="theme-color" content="{{ $storeThemeColor }}">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">

        <script>
            (function() {
                const storeColor = '{{ $storeThemeColor }}';
                // Sync with Native Android Status Bar if running inside the AffanHub Native Mobile App
                if (window.AffanBridge && typeof window.AffanBridge.setStatusBarColor === 'function') {
                    window.AffanBridge.setStatusBarColor(storeColor);
                }
                document.addEventListener('DOMContentLoaded', function() {
                    if (window.AffanBridge && typeof window.AffanBridge.setStatusBarColor === 'function') {
                        window.AffanBridge.setStatusBarColor(storeColor);
                    }
                });
            })();
        </script>

        @fonts

        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])
        <x-inertia::head>
            <title>{{ $metaTitle }}</title>
        </x-inertia::head>

        {{-- Tenant Dynamic Brand Theme & Foreground Contrast (Loaded last to ensure absolute cascade priority) --}}
        @if(tenant() && tenant('primary_color'))
            @php
                $tenantPrimary = tenant('primary_color');
                $tenantForeground = \App\Services\Branding\ColorHelper::getContrastForeground($tenantPrimary);
            @endphp
            <style id="tenant-brand-theme">
                :root, html, body {
                    --primary: {{ $tenantPrimary }} !important;
                    --color-primary: {{ $tenantPrimary }} !important;
                    --primary-foreground: {{ $tenantForeground }} !important;
                    --color-primary-foreground: {{ $tenantForeground }} !important;
                    --ring: {{ $tenantPrimary }} !important;
                    --color-ring: {{ $tenantPrimary }} !important;
                }
                .dark, html.dark, body.dark {
                    --primary: {{ $tenantPrimary }} !important;
                    --color-primary: {{ $tenantPrimary }} !important;
                    --primary-foreground: {{ $tenantForeground }} !important;
                    --color-primary-foreground: {{ $tenantForeground }} !important;
                    --ring: {{ $tenantPrimary }} !important;
                    --color-ring: {{ $tenantPrimary }} !important;
                }
            </style>
        @endif
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
