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

        {{-- Meta Pixel for Central Marketing & Registration Funnel (Only on AffanHub central website) --}}
        @if(!tenant())
            @php
                $testEventCode = request()->query('test_event_code') 
                    ?? session('meta_test_event_code') 
                    ?? env('META_TEST_EVENT_CODE');

                if (request()->filled('test_event_code')) {
                    session(['meta_test_event_code' => request()->query('test_event_code')]);
                }
            @endphp
            <!-- Meta Pixel Code -->
            <script>
                !function(f,b,e,v,n,t,s)
                {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');

                try {
                    const urlParams = new URLSearchParams(window.location.search);
                    const urlTestCode = urlParams.get('test_event_code');
                    if (urlTestCode) {
                        localStorage.setItem('meta_test_event_code', urlTestCode);
                    }
                } catch (e) {}

                const storedTestCode = (function() {
                    try {
                        return localStorage.getItem('meta_test_event_code') || @json($testEventCode);
                    } catch(e) {
                        return @json($testEventCode);
                    }
                })();

                if (storedTestCode) {
                    fbq('init', '1955741159167125', {}, { test_event_code: storedTestCode });
                    fbq('track', 'PageView', {}, { test_event_code: storedTestCode });
                } else {
                    fbq('init', '1955741159167125');
                    fbq('track', 'PageView');
                }
            </script>
            <noscript>
                <img height="1" width="1" style="display:none"
                     src="https://www.facebook.com/tr?id=1955741159167125&ev=PageView&noscript=1" />
            </noscript>
            <!-- End Meta Pixel Code -->

            @if(session()->has('pixel_event'))
                @php
                    $pixelEvent = session()->pull('pixel_event');
                    $eventParams = $pixelEvent['params'] ?? [];
                    if (!empty($testEventCode) && !isset($eventParams['test_event_code'])) {
                        $eventParams['test_event_code'] = $testEventCode;
                    }
                @endphp
                <script>
                    (function() {
                        if (typeof window.fbq !== 'function') return;
                        const params = @json($eventParams);
                        let testCode = params.test_event_code;
                        try {
                            testCode = testCode || localStorage.getItem('meta_test_event_code');
                        } catch(e) {}

                        if (testCode) {
                            params.test_event_code = testCode;
                            window.fbq('track', '{{ $pixelEvent['event'] }}', params, { test_event_code: testCode });
                        } else {
                            window.fbq('track', '{{ $pixelEvent['event'] }}', params);
                        }
                    })();
                </script>
            @endif
        @endif
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />

        {{-- Chatwoot Live Support Widget for Central Marketing Domain (Landing Page & Blog) --}}
        @if(!tenant())
            <script>
              window.chatwootSettings = {"position":"right","type":"standard","launcherTitle":""};
              (function(d,t) {
                var BASE_URL="https://app.chatwoot.com";
                var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
                g.src=BASE_URL+"/packs/js/sdk.js";
                g.async = true;
                s.parentNode.insertBefore(g,s);
                g.onload=function(){
                  window.chatwootSDK.run({
                    websiteToken: 'EsKYqLKgZB74sjE2jMpaoXZE',
                    baseUrl: BASE_URL
                  })
                }
              })(document,"script");
            </script>
        @endif
    </body>
</html>
