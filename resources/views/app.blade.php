<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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
            <link rel="icon" href="{{ global_asset('storage/' . tenant('favicon_path')) }}" sizes="any">
        @else
            <link rel="icon" href="/favicon.ico" sizes="any">
            <link rel="icon" href="/favicon.svg" type="image/svg+xml">
            <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        @endif

        @fonts

        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])
        <x-inertia::head>
            <title>{{ config('app.name', 'Laravel') }}</title>
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
