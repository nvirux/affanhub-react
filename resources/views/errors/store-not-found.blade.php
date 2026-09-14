<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Not Found | AffanHub</title>
    <link rel="icon" href="/affan-icon.png" type="image/png">
    
    {{-- Google Fonts matching AffanHub Landing Page --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        :root {
            --primary: oklch(0.50 0.25 260);
            --primary-fallback: #4f46e5;
        }

        body {
            font-family: 'Instrument Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #ffffff;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .bg-brand-primary {
            background-color: var(--primary, #4f46e5);
        }

        .text-brand-primary {
            color: var(--primary, #4f46e5);
        }

        .shadow-brand {
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.25);
        }
    </style>
</head>
<body class="min-h-screen bg-white text-slate-900 flex flex-col antialiased selection:bg-indigo-500 selection:text-white">

    <!-- HEADER / NAV (Identical to Marketing Landing Page) -->
    <header class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur-sm border-b border-slate-200/80">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ $centralUrl ?? '/' }}" class="flex items-center gap-2.5 shrink-0 no-underline text-slate-900 group">
                <div class="w-8 h-8 rounded-lg bg-brand-primary flex items-center justify-center text-white font-black text-base shadow-sm transition-transform group-hover:scale-105">
                    A
                </div>
                <span class="font-bold text-slate-900 text-lg tracking-tight">AffanHub</span>
            </a>

            <nav class="hidden md:flex items-center gap-1">
                <a href="{{ $centralUrl ?? '/' }}#features" class="px-3 py-1.5 text-sm text-slate-600 hover:text-slate-900 rounded-md hover:bg-slate-50 transition-all">Features</a>
                <a href="{{ $centralUrl ?? '/' }}#how-it-works" class="px-3 py-1.5 text-sm text-slate-600 hover:text-slate-900 rounded-md hover:bg-slate-50 transition-all">How it works</a>
                <a href="{{ $centralUrl ?? '/' }}#pricing" class="px-3 py-1.5 text-sm text-slate-600 hover:text-slate-900 rounded-md hover:bg-slate-50 transition-all">Pricing</a>
                <a href="https://demo.affanhub.com" target="_blank" rel="noreferrer" class="px-3 py-1.5 text-sm text-slate-600 hover:text-slate-900 rounded-md hover:bg-slate-50 transition-all">Live Demo</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ $merchantLoginUrl ?? '/login' }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors px-2 py-1">
                    Sign in
                </a>
                <a href="{{ $merchantRegisterUrl ?? '/register' }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand-primary text-white text-sm font-semibold rounded-lg hover:opacity-95 transition-all shadow-sm shadow-indigo-500/20">
                    <span>Launch My Store</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-1 pt-16 flex flex-col justify-center relative overflow-hidden">
        {{-- Subtle radial ambient background from marketing hero --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[650px] rounded-full opacity-[0.07]"
                 style="background: radial-gradient(circle, #4f46e5 0%, transparent 70%);"></div>
        </div>

        <section class="relative pt-16 pb-20 md:pt-24 md:pb-28">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">

                {{-- Status Pill --}}
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-slate-100/90 border border-slate-200/80 rounded-full text-xs font-semibold text-slate-600 mb-6">
                    <span class="w-2 h-2 rounded-full bg-amber-500 ring-4 ring-amber-100"></span>
                    <span>404 · Store Not Found</span>
                </div>

                {{-- Heading --}}
                <h1 class="text-3xl sm:text-5xl md:text-[54px] font-extrabold text-slate-900 leading-[1.1] tracking-tight mb-5">
                    We couldn't find<br class="hidden sm:block" />
                    <span class="text-brand-primary">this store.</span>
                </h1>

                {{-- Domain Identifier --}}
                <div class="mb-5 inline-block">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 font-mono">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253" />
                        </svg>
                        <span class="font-semibold text-slate-900">{{ $domain ?? request()->getHost() }}</span>
                    </div>
                </div>

                {{-- Subtitle --}}
                <p class="text-base md:text-lg text-slate-500 max-w-xl mx-auto leading-relaxed mb-9">
                    There is no active storefront registered on this subdomain. The address might have a typo, or the merchant's store may have moved.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3.5 justify-center items-center mb-16">
                    <a href="{{ $merchantRegisterUrl ?? '/register' }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-brand-primary text-white font-semibold rounded-lg text-base hover:opacity-95 transition-all shadow-brand">
                        <span>Launch Your Store Free</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                    <a href="{{ $centralUrl ?? '/' }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 text-slate-700 font-semibold rounded-lg text-base border border-slate-200 hover:bg-slate-50 transition-all bg-white">
                        <span>Return to Homepage</span>
                    </a>
                </div>

                {{-- Helpful 2-Card Grid (Styled like Landing Page FeatureCards) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left max-w-2xl mx-auto">
                    <div class="bg-white border border-slate-200/90 rounded-xl p-5 hover:border-slate-300 transition-all">
                        <div class="w-9 h-9 rounded-lg bg-indigo-50 text-brand-primary flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900 text-sm mb-1">Are you this store's owner?</h3>
                        <p class="text-xs text-slate-500 leading-relaxed mb-3">
                            Sign in to your merchant dashboard to verify your custom domain DNS or check your active subscription.
                        </p>
                        <a href="{{ $merchantLoginUrl ?? '/login' }}" class="text-xs font-semibold text-brand-primary hover:underline inline-flex items-center gap-1">
                            Merchant Login →
                        </a>
                    </div>

                    <div class="bg-white border border-slate-200/90 rounded-xl p-5 hover:border-slate-300 transition-all">
                        <div class="w-9 h-9 rounded-lg bg-indigo-50 text-brand-primary flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900 text-sm mb-1">Want to claim this domain?</h3>
                        <p class="text-xs text-slate-500 leading-relaxed mb-3">
                            Create your digital services storefront in under 2 minutes with automated airtime, data, and wallet engine.
                        </p>
                        <a href="{{ $merchantRegisterUrl ?? '/register' }}" class="text-xs font-semibold text-brand-primary hover:underline inline-flex items-center gap-1">
                            Create a Store Now →
                        </a>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <!-- FOOTER (Matching Marketing Landing Page Footer) -->
    <footer class="bg-slate-950 text-slate-400 border-t border-slate-800 mt-auto">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-md bg-brand-primary flex items-center justify-center text-white font-black text-xs">A</div>
                    <span class="font-bold text-white text-sm tracking-tight">AffanHub</span>
                    <span class="text-xs text-slate-500 ml-2">· Digital Services Platform</span>
                </div>
                <div class="flex items-center gap-6 text-xs">
                    <a href="{{ $centralUrl ?? '/' }}#features" class="hover:text-white transition-colors">Features</a>
                    <a href="{{ $centralUrl ?? '/' }}#pricing" class="hover:text-white transition-colors">Pricing</a>
                    <a href="{{ $merchantLoginUrl ?? '/login' }}" class="hover:text-white transition-colors">Sign in</a>
                    <a href="{{ $merchantRegisterUrl ?? '/register' }}" class="text-white hover:underline font-medium">Get Started</a>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} AffanHub. All rights reserved.</p>
                <p>Built for Nigerian entrepreneurs.</p>
            </div>
        </div>
    </footer>

</body>
</html>
