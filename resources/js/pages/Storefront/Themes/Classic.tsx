import { Head, Link, usePage } from '@inertiajs/react';
import {
    Smartphone, Wifi, Tv, GraduationCap, ShieldCheck, FileBadge,
    Building2, CreditCard, Menu, X, ArrowUpRight, Zap, Lock, Clock,
    Check
} from 'lucide-react';
import { useState, useEffect } from 'react';
import './Default.css';

export default function DefaultTheme() {
    const { store } = usePage<any>().props;
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    useEffect(() => {
        if (store?.tawk_chat_enabled && store?.tawk_property_id && store?.tawk_widget_id) {
            const s1 = document.createElement("script");
            const s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = `https://embed.tawk.to/${store.tawk_property_id}/${store.tawk_widget_id}`;
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0?.parentNode?.insertBefore(s1, s0);

            return () => {
                s1.remove();
                if ((window as any).Tawk_API) {
                    try {
                        (window as any).Tawk_API.hideWidget();
                    } catch (e) {}
                }
            };
        }
    }, [store]);

    const storeName = store?.name || 'Your Store';
    const storeInitial = storeName.charAt(0).toUpperCase();
    const storeDescription = store?.description || `Welcome to ${storeName}. Airtime, data bundles, utility bills, and identity verifications delivered with automated speed 24/7.`;

    const providers = ['MTN', 'Airtel', 'GLO', '9Mobile', 'DSTV', 'GOtv', 'Startimes', 'WAEC', 'JAMB', 'NECO'];

    return (
        <div className="storefront-theme min-h-screen flex flex-col bg-white text-slate-900 selection:bg-primary/15 selection:text-slate-900">
            <Head title={`${storeName} | Digital Services & Verification`}>
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
                <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible+Mono:wght@400;500;700&family=Host+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet" />
            </Head>

            {/* ─── NAVIGATION BAR ─── */}
            <header className="sticky top-0 z-[var(--z-sticky-nav)] border-b border-slate-200/80 bg-white/95 backdrop-blur-md">
                <div className="max-w-[var(--container)] mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-6">
                    {/* Brand Identifier */}
                    <div className="flex items-center gap-3 shrink-0">
                        {store?.logo_url ? (
                            <img src={store.logo_url} alt={storeName} className="h-8 max-w-[150px] object-contain" />
                        ) : (
                            <div className="flex items-center gap-2.5">
                                <div className="w-8 h-8 rounded-lg bg-primary text-primary-foreground flex items-center justify-center font-bold text-sm tracking-tight shadow-sm shadow-primary/20">
                                    {storeInitial}
                                </div>
                                <span className="font-semibold text-slate-900 text-sm tracking-tight">{storeName}</span>
                            </div>
                        )}
                    </div>

                    {/* Desktop Navigation Links */}
                    <nav className="hidden md:flex items-center gap-6" aria-label="Main Navigation">
                        <a href="#services" className="text-xs font-medium text-slate-600 hover:text-slate-900 transition-colors duration-[var(--dur-short)]">
                            Services
                        </a>
                        <a href="#how-it-works" className="text-xs font-medium text-slate-600 hover:text-slate-900 transition-colors duration-[var(--dur-short)]">
                            How it works
                        </a>
                    </nav>

                    {/* Desktop User Actions */}
                    <div className="hidden md:flex items-center gap-3">
                        <Link
                            href="/login"
                            className="px-3.5 py-1.5 text-xs font-medium text-slate-700 hover:text-slate-900 border border-slate-200 hover:border-slate-300 rounded-[var(--radius-pill)] transition-colors duration-[var(--dur-short)] focus-visible:outline-2 focus-visible:outline-primary"
                        >
                            Sign in
                        </Link>
                        <Link
                            href="/register"
                            className="inline-flex items-center gap-1.5 px-4 py-1.5 bg-primary text-primary-foreground text-xs font-semibold rounded-[var(--radius-pill)] hover:opacity-90 shadow-sm shadow-primary/20 transition-opacity duration-[var(--dur-short)] focus-visible:outline-2 focus-visible:outline-primary"
                        >
                            <span>Get started</span>
                            <ArrowUpRight className="w-3.5 h-3.5" aria-hidden="true" />
                        </Link>
                    </div>

                    {/* Mobile Drawer Toggle */}
                    <button
                        type="button"
                        aria-label={mobileMenuOpen ? "Close navigation menu" : "Open navigation menu"}
                        className="md:hidden p-2 text-slate-600 hover:text-slate-900 focus-visible:outline-2 focus-visible:outline-primary disabled:opacity-50 disabled:pointer-events-none"
                        onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                    >
                        {mobileMenuOpen ? <X className="w-5 h-5" aria-hidden="true" /> : <Menu className="w-5 h-5" aria-hidden="true" />}
                    </button>
                </div>

                {/* Mobile Drawer */}
                {mobileMenuOpen && (
                    <div className="md:hidden border-t border-slate-100 bg-white px-4 py-4 space-y-3">
                        <div className="space-y-1">
                            <a
                                href="#services"
                                onClick={() => setMobileMenuOpen(false)}
                                className="block px-3 py-2 text-sm text-slate-700 hover:text-slate-900 hover:bg-slate-50 rounded-[var(--radius-pill)] transition-colors duration-[var(--dur-short)]"
                            >
                                Services
                            </a>
                            <a
                                href="#how-it-works"
                                onClick={() => setMobileMenuOpen(false)}
                                className="block px-3 py-2 text-sm text-slate-700 hover:text-slate-900 hover:bg-slate-50 rounded-[var(--radius-pill)] transition-colors duration-[var(--dur-short)]"
                            >
                                How it works
                            </a>
                        </div>
                        <div className="pt-3 border-t border-slate-100 flex flex-col gap-2">
                            <Link
                                href="/login"
                                className="w-full text-center px-3 py-2 text-sm font-medium text-slate-700 border border-slate-200 rounded-[var(--radius-pill)]"
                            >
                                Sign in
                            </Link>
                            <Link
                                href="/register"
                                className="w-full text-center px-4 py-2.5 bg-primary text-primary-foreground text-sm font-semibold rounded-[var(--radius-pill)] shadow-sm shadow-primary/20"
                            >
                                Get started
                            </Link>
                        </div>
                    </div>
                )}
            </header>

            <main className="flex-1">
                {/* ─── HERO: STAGE COMPOSITION ─── */}
                <section className="relative border-b border-slate-200/80 bg-white pt-16 pb-20 md:pt-24 md:pb-28">
                    {/* Subtle merchant-colored glow */}
                    <div className="absolute inset-0 pointer-events-none overflow-hidden">
                        <div
                            className="absolute -top-32 left-1/2 -translate-x-1/2 w-[720px] h-[480px] rounded-full opacity-[0.06] blur-3xl"
                            style={{ background: 'var(--primary, var(--color-accent))' }}
                        />
                    </div>

                    <div className="relative max-w-[var(--container)] mx-auto px-4 sm:px-6">
                        <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                            {/* Headline & Actions (Columns 1-7) */}
                            <div className="lg:col-span-7">
                                <h1 className="text-3xl sm:text-4xl md:text-[52px] font-medium leading-[1.05] tracking-tight text-slate-900 mb-5 [overflow-wrap:break-word] min-w-0">
                                    Instant digital services and verified identity access.
                                </h1>

                                <p className="text-base sm:text-lg text-slate-600 leading-relaxed max-w-xl mb-8">
                                    {storeDescription}
                                </p>

                                <div className="flex flex-wrap items-center gap-3 mb-10">
                                    <Link
                                        href="/register"
                                        className="inline-flex items-center gap-2 px-6 py-3 bg-primary text-primary-foreground font-semibold text-sm rounded-[var(--radius-pill)] hover:opacity-90 shadow-md shadow-primary/25 transition-opacity duration-[var(--dur-short)] focus-visible:outline-2 focus-visible:outline-primary"
                                    >
                                        <span>Create free account</span>
                                        <ArrowUpRight className="w-4 h-4" aria-hidden="true" />
                                    </Link>
                                    <Link
                                        href="/login"
                                        className="inline-flex items-center gap-2 px-5 py-3 border border-slate-200 text-slate-800 font-medium text-sm rounded-[var(--radius-pill)] bg-white hover:bg-slate-50 transition-colors duration-[var(--dur-short)] focus-visible:outline-2 focus-visible:outline-primary"
                                    >
                                        Sign in
                                    </Link>
                                </div>

                                {/* Operational Guarantees */}
                                <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-6 border-t border-slate-200/80">
                                    <div className="flex items-center gap-2 text-xs text-slate-600">
                                        <Check className="w-4 h-4 text-emerald-600 shrink-0" aria-hidden="true" />
                                        <span>Instant delivery</span>
                                    </div>
                                    <div className="flex items-center gap-2 text-xs text-slate-600">
                                        <Check className="w-4 h-4 text-emerald-600 shrink-0" aria-hidden="true" />
                                        <span>Secured wallet</span>
                                    </div>
                                    <div className="flex items-center gap-2 text-xs text-slate-600">
                                        <Check className="w-4 h-4 text-emerald-600 shrink-0" aria-hidden="true" />
                                        <span>No hidden fees</span>
                                    </div>
                                </div>
                            </div>

                            {/* Live Telemetry Stage (Columns 8-12) */}
                            <div className="lg:col-span-5">
                                <div className="bg-white border border-slate-200/90 rounded-[var(--radius-card)] shadow-lg shadow-slate-200/40 overflow-hidden">
                                    {/* Panel Header */}
                                    <div className="px-4 py-3 border-b border-slate-100 bg-slate-50/80 flex items-center justify-between text-xs">
                                        <span className="text-slate-600 font-medium">Platform telemetry</span>
                                        <span className="measured text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200/60 font-semibold flex items-center gap-1.5 text-[11px]">
                                            <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            routing: active
                                        </span>
                                    </div>

                                    {/* Telemetry Metrics */}
                                    <div className="p-5 space-y-4">
                                        <div className="bg-primary/5 border border-primary/20 rounded-[var(--radius-pill)] p-4">
                                            <div className="text-xs text-slate-600 mb-1 font-medium">Account wallet balance</div>
                                            <div className="measured text-2xl font-bold text-slate-900">₦ 14,850.00</div>
                                            <div className="measured text-xs text-emerald-700 font-semibold mt-1 flex items-center gap-1.5">
                                                <Check className="w-3.5 h-3.5" aria-hidden="true" />
                                                <span>Funded & active</span>
                                            </div>
                                        </div>

                                        {/* Instrument Readout Grid */}
                                        <div className="grid grid-cols-2 gap-2 text-xs">
                                            <div className="bg-slate-50 border border-slate-100 rounded-[var(--radius-pill)] p-3">
                                                <div className="text-slate-500 font-medium">Avg delivery</div>
                                                <div className="measured text-base font-bold text-slate-900 mt-0.5">&lt; 3.0s</div>
                                            </div>
                                            <div className="bg-slate-50 border border-slate-100 rounded-[var(--radius-pill)] p-3">
                                                <div className="text-slate-500 font-medium">Verified uptime</div>
                                                <div className="measured text-base font-bold text-slate-900 mt-0.5">99.9%</div>
                                            </div>
                                        </div>

                                        {/* Recent Transaction Ledger */}
                                        <div className="space-y-1.5 pt-2 border-t border-slate-100">
                                            <div className="flex items-center justify-between text-xs py-1 text-slate-600">
                                                <span className="font-medium">Airtime: MTN ₦500</span>
                                                <span className="measured text-emerald-700 font-semibold">confirmed</span>
                                            </div>
                                            <div className="flex items-center justify-between text-xs py-1 text-slate-600">
                                                <span className="font-medium">Data: Airtel 1GB</span>
                                                <span className="measured text-emerald-700 font-semibold">confirmed</span>
                                            </div>
                                            <div className="flex items-center justify-between text-xs py-1 text-slate-600">
                                                <span className="font-medium">NIN verification</span>
                                                <span className="measured text-primary font-semibold">verified</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* ─── SYSTEM PERFORMANCE LEDGER ─── */}
                <section className="border-b border-slate-200/80 bg-slate-50/70 py-8">
                    <div className="max-w-[var(--container)] mx-auto px-4 sm:px-6">
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-0 md:divide-x md:divide-slate-200">
                            <div className="text-center md:px-4">
                                <div className="measured text-2xl sm:text-3xl font-bold text-slate-900">&lt; 3s</div>
                                <div className="text-xs text-slate-500 mt-1 font-medium">Avg. delivery time</div>
                            </div>
                            <div className="text-center md:px-4">
                                <div className="measured text-2xl sm:text-3xl font-bold text-slate-900">99.9%</div>
                                <div className="text-xs text-slate-500 mt-1 font-medium">Platform uptime</div>
                            </div>
                            <div className="text-center md:px-4">
                                <div className="measured text-2xl sm:text-3xl font-bold text-slate-900">8+</div>
                                <div className="text-xs text-slate-500 mt-1 font-medium">Service categories</div>
                            </div>
                            <div className="text-center md:px-4">
                                <div className="measured text-2xl sm:text-3xl font-bold text-slate-900">24/7</div>
                                <div className="text-xs text-slate-500 mt-1 font-medium">System availability</div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* ─── PROVIDER RECOGNITION ─── */}
                <section className="border-b border-slate-200/80 bg-white py-6">
                    <div className="max-w-[var(--container)] mx-auto px-4 sm:px-6">
                        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <span className="text-xs font-semibold text-slate-500 shrink-0">Supported networks and service providers</span>
                            <div className="flex flex-wrap items-center gap-2">
                                {providers.map(provider => (
                                    <span
                                        key={provider}
                                        className="measured px-3 py-1 text-xs border border-slate-200 rounded-full bg-slate-50 text-slate-700 font-semibold"
                                    >
                                        {provider}
                                    </span>
                                ))}
                            </div>
                        </div>
                    </div>
                </section>

                {/* ─── SERVICES SUITE ─── */}
                <section id="services" className="border-b border-slate-200/80 bg-white py-20 md:py-28">
                    <div className="max-w-[var(--container)] mx-auto px-4 sm:px-6">
                        <div className="max-w-2xl mb-14">
                            <h2 className="text-2xl sm:text-3xl md:text-4xl font-medium tracking-tight text-slate-900 mb-3 [overflow-wrap:break-word] min-w-0">
                                Direct telecom and verification catalog.
                            </h2>
                            <p className="text-base text-slate-600 leading-relaxed">
                                Automated API connectivity with primary telecom gateways and regulatory systems for real-time order completion.
                            </p>
                        </div>

                        {/* Utilities & Telecoms Grid */}
                        <div className="mb-14">
                            <div className="text-xs font-semibold text-slate-500 mb-4">Telecom utilities and vouchers</div>
                            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div className="bg-white border border-slate-200/80 rounded-[var(--radius-card)] p-5 hover:border-slate-300 transition-colors duration-[var(--dur-short)]">
                                    <div className="w-8 h-8 rounded-lg bg-primary/10 text-primary border border-primary/20 flex items-center justify-center mb-4">
                                        <Smartphone className="w-4 h-4" aria-hidden="true" />
                                    </div>
                                    <h3 className="font-semibold text-sm text-slate-900 mb-1">Airtime</h3>
                                    <p className="text-xs text-slate-500 leading-relaxed">MTN, Airtel, GLO, and 9Mobile instant top-ups.</p>
                                </div>

                                <div className="bg-white border border-slate-200/80 rounded-[var(--radius-card)] p-5 hover:border-slate-300 transition-colors duration-[var(--dur-short)]">
                                    <div className="w-8 h-8 rounded-lg bg-primary/10 text-primary border border-primary/20 flex items-center justify-center mb-4">
                                        <Wifi className="w-4 h-4" aria-hidden="true" />
                                    </div>
                                    <h3 className="font-semibold text-sm text-slate-900 mb-1">Data bundles</h3>
                                    <p className="text-xs text-slate-500 leading-relaxed">High-volume SME and direct data delivered automatically.</p>
                                </div>

                                <div className="bg-white border border-slate-200/80 rounded-[var(--radius-card)] p-5 hover:border-slate-300 transition-colors duration-[var(--dur-short)]">
                                    <div className="w-8 h-8 rounded-lg bg-primary/10 text-primary border border-primary/20 flex items-center justify-center mb-4">
                                        <Tv className="w-4 h-4" aria-hidden="true" />
                                    </div>
                                    <h3 className="font-semibold text-sm text-slate-900 mb-1">TV subscription</h3>
                                    <p className="text-xs text-slate-500 leading-relaxed">DSTV, GOtv, and Startimes renewal with zero delay.</p>
                                </div>

                                <div className="bg-white border border-slate-200/80 rounded-[var(--radius-card)] p-5 hover:border-slate-300 transition-colors duration-[var(--dur-short)]">
                                    <div className="w-8 h-8 rounded-lg bg-primary/10 text-primary border border-primary/20 flex items-center justify-center mb-4">
                                        <GraduationCap className="w-4 h-4" aria-hidden="true" />
                                    </div>
                                    <h3 className="font-semibold text-sm text-slate-900 mb-1">Education pins</h3>
                                    <p className="text-xs text-slate-500 leading-relaxed">WAEC, NECO, and JAMB validation tokens on demand.</p>
                                </div>
                            </div>
                        </div>

                        {/* Identity & Verification Grid */}
                        <div>
                            <div className="text-xs font-semibold text-slate-500 mb-4">Identity verification and funding</div>
                            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div className="bg-white border border-slate-200/80 rounded-[var(--radius-card)] p-5 hover:border-slate-300 transition-colors duration-[var(--dur-short)]">
                                    <div className="w-8 h-8 rounded-lg bg-primary/10 text-primary border border-primary/20 flex items-center justify-center mb-4">
                                        <FileBadge className="w-4 h-4" aria-hidden="true" />
                                    </div>
                                    <h3 className="font-semibold text-sm text-slate-900 mb-1">NIN services</h3>
                                    <p className="text-xs text-slate-500 leading-relaxed">Direct National Identity Number lookup and data confirmation.</p>
                                </div>

                                <div className="bg-white border border-slate-200/80 rounded-[var(--radius-card)] p-5 hover:border-slate-300 transition-colors duration-[var(--dur-short)]">
                                    <div className="w-8 h-8 rounded-lg bg-primary/10 text-primary border border-primary/20 flex items-center justify-center mb-4">
                                        <ShieldCheck className="w-4 h-4" aria-hidden="true" />
                                    </div>
                                    <h3 className="font-semibold text-sm text-slate-900 mb-1">BVN validation</h3>
                                    <p className="text-xs text-slate-500 leading-relaxed">Real-time Bank Verification Number check across banking nodes.</p>
                                </div>

                                <div className="bg-white border border-slate-200/80 rounded-[var(--radius-card)] p-5 hover:border-slate-300 transition-colors duration-[var(--dur-short)]">
                                    <div className="w-8 h-8 rounded-lg bg-primary/10 text-primary border border-primary/20 flex items-center justify-center mb-4">
                                        <Building2 className="w-4 h-4" aria-hidden="true" />
                                    </div>
                                    <h3 className="font-semibold text-sm text-slate-900 mb-1">CAC lookup</h3>
                                    <p className="text-xs text-slate-500 leading-relaxed">Corporate Affairs Commission business name confirmation.</p>
                                </div>

                                <div className="bg-white border border-slate-200/80 rounded-[var(--radius-card)] p-5 hover:border-slate-300 transition-colors duration-[var(--dur-short)]">
                                    <div className="w-8 h-8 rounded-lg bg-primary/10 text-primary border border-primary/20 flex items-center justify-center mb-4">
                                        <CreditCard className="w-4 h-4" aria-hidden="true" />
                                    </div>
                                    <h3 className="font-semibold text-sm text-slate-900 mb-1">Wallet funding</h3>
                                    <p className="text-xs text-slate-500 leading-relaxed">Dedicated virtual accounts with instant settlement.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* ─── INFRASTRUCTURE RELIABILITY (WHY US) ─── */}
                <section className="border-b border-slate-200/80 bg-slate-50/70 py-20 md:py-28">
                    <div className="max-w-[var(--container)] mx-auto px-4 sm:px-6">
                        <div className="max-w-2xl mb-12">
                            <h2 className="text-2xl sm:text-3xl md:text-4xl font-medium tracking-tight text-slate-900 mb-3 [overflow-wrap:break-word] min-w-0">
                                Built for speed. Secured by design.
                            </h2>
                            <p className="text-base text-slate-600 leading-relaxed">
                                We’ve engineered every layer of the platform for reliability. Your funds are protected, your data is encrypted, and our systems run around the clock.
                            </p>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div className="bg-white border border-slate-200/80 rounded-[var(--radius-card)] p-6 shadow-sm">
                                <div className="w-8 h-8 rounded-lg bg-primary/10 text-primary border border-primary/20 flex items-center justify-center mb-4">
                                    <Zap className="w-4 h-4" aria-hidden="true" />
                                </div>
                                <h3 className="font-semibold text-base text-slate-900 mb-2">Lightning fast processing</h3>
                                <p className="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                    Automated API routing completes transactions in under 3 seconds on average.
                                </p>
                            </div>

                            <div className="bg-white border border-slate-200/80 rounded-[var(--radius-card)] p-6 shadow-sm">
                                <div className="w-8 h-8 rounded-lg bg-primary/10 text-primary border border-primary/20 flex items-center justify-center mb-4">
                                    <Lock className="w-4 h-4" aria-hidden="true" />
                                </div>
                                <h3 className="font-semibold text-base text-slate-900 mb-2">Bank-grade security</h3>
                                <p className="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                    End-to-end encryption protects every transaction and identity record.
                                </p>
                            </div>

                            <div className="bg-white border border-slate-200/80 rounded-[var(--radius-card)] p-6 shadow-sm">
                                <div className="w-8 h-8 rounded-lg bg-primary/10 text-primary border border-primary/20 flex items-center justify-center mb-4">
                                    <Clock className="w-4 h-4" aria-hidden="true" />
                                </div>
                                <h3 className="font-semibold text-base text-slate-900 mb-2">Always available</h3>
                                <p className="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                    99.9% uptime with 24/7 automated monitoring and fail-overs.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                {/* ─── PROCESS WORKFLOW ─── */}
                <section id="how-it-works" className="border-b border-slate-200/80 bg-white py-20 md:py-28">
                    <div className="max-w-[var(--container)] mx-auto px-4 sm:px-6">
                        <div className="max-w-2xl mb-12">
                            <h2 className="text-2xl sm:text-3xl md:text-4xl font-medium tracking-tight text-slate-900 mb-3 [overflow-wrap:break-word] min-w-0">
                                Start in minutes, not days.
                            </h2>
                            <p className="text-base text-slate-600 leading-relaxed">
                                No complicated onboarding. Create an account, fund your wallet, and start transacting.
                            </p>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div className="bg-slate-50/70 border border-slate-200/80 rounded-[var(--radius-card)] p-6">
                                <div className="measured text-xs font-bold text-slate-400 mb-3">01</div>
                                <h3 className="font-semibold text-base text-slate-900 mb-2">Create an account</h3>
                                <p className="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                    Sign up in under 60 seconds with just your email address. No paperwork required.
                                </p>
                            </div>

                            <div className="bg-slate-50/70 border border-slate-200/80 rounded-[var(--radius-card)] p-6">
                                <div className="measured text-xs font-bold text-slate-400 mb-3">02</div>
                                <h3 className="font-semibold text-base text-slate-900 mb-2">Fund your wallet</h3>
                                <p className="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                    Get a unique virtual account number. Transfer any amount and your balance updates instantly.
                                </p>
                            </div>

                            <div className="bg-primary/5 border border-primary/30 rounded-[var(--radius-card)] p-6 shadow-sm">
                                <div className="measured text-xs font-bold text-primary mb-3">03</div>
                                <h3 className="font-semibold text-base text-slate-900 mb-2">Start transacting</h3>
                                <p className="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                    Buy airtime, data, pay bills, or run identity checks, all from one clean dashboard.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                {/* ─── CUSTOMER TESTIMONIALS ─── */}
                <section className="border-b border-slate-200/80 bg-slate-50/70 py-20 md:py-28">
                    <div className="max-w-[var(--container)] mx-auto px-4 sm:px-6">
                        <div className="max-w-2xl mb-12">
                            <h2 className="text-2xl sm:text-3xl md:text-4xl font-medium tracking-tight text-slate-900 mb-3 [overflow-wrap:break-word] min-w-0">
                                Feedback from active accounts.
                            </h2>
                            <p className="text-base text-slate-600 leading-relaxed">
                                Verified experiences across daily personal and business operations.
                            </p>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <TestimonialCard
                                quote="“I fund my wallet once and handle airtime, data, and TV from one screen. Incredibly fast.”"
                                name="Fatima A."
                                role="Business Owner"
                            />
                            <TestimonialCard
                                quote="“The NIN verification was done in seconds. I was skeptical at first but this platform genuinely delivers.”"
                                name="Emeka O."
                                role="Freelancer"
                            />
                            <TestimonialCard
                                quote="“Finally a service with zero excuses. My data subscription renews instantly every time.”"
                                name="Aisha M."
                                role="Student"
                            />
                        </div>
                    </div>
                </section>

                {/* ─── CALL TO ACTION BANNER ─── */}
                <section className="bg-white py-16 md:py-24">
                    <div className="max-w-[var(--container)] mx-auto px-4 sm:px-6">
                        <div className="bg-slate-900 rounded-[var(--radius-card)] p-8 sm:p-12 md:p-16 text-center max-w-3xl mx-auto shadow-xl">
                            <h2 className="text-2xl sm:text-3xl md:text-4xl font-medium tracking-tight text-white mb-4 [overflow-wrap:break-word] min-w-0">
                                Ready to get started?
                            </h2>
                            <p className="text-sm sm:text-base text-slate-300 leading-relaxed max-w-lg mx-auto mb-8">
                                Join {storeName} today. Create your free account and start making instant transactions in under 5 minutes.
                            </p>
                            <div className="flex flex-col sm:flex-row gap-3 justify-center">
                                <Link
                                    href="/register"
                                    className="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary text-primary-foreground font-semibold text-sm rounded-[var(--radius-pill)] hover:opacity-90 shadow-md shadow-primary/30 transition-opacity duration-[var(--dur-short)] focus-visible:outline-2 focus-visible:outline-primary"
                                >
                                    <span>Create free account</span>
                                    <ArrowUpRight className="w-4 h-4" aria-hidden="true" />
                                </Link>
                                <Link
                                    href="/login"
                                    className="inline-flex items-center justify-center gap-2 px-5 py-3 border border-slate-700 text-slate-200 font-medium text-sm rounded-[var(--radius-pill)] bg-slate-800 hover:bg-slate-700 transition-colors duration-[var(--dur-short)] focus-visible:outline-2 focus-visible:outline-primary"
                                >
                                    Already have an account?
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            {/* ─── FOOTER ─── */}
            <footer className="border-t border-slate-200/80 bg-slate-50 py-12 text-xs">
                <div className="max-w-[var(--container)] mx-auto px-4 sm:px-6">
                    <div className="grid grid-cols-1 md:grid-cols-12 gap-8 mb-10">
                        {/* Store Info (Cols 1-5) */}
                        <div className="md:col-span-5 space-y-3">
                            <div className="flex items-center gap-2.5">
                                {store?.logo_url ? (
                                    <img src={store.logo_url} alt={storeName} className="h-7 max-w-[140px] object-contain" />
                                ) : (
                                    <div className="flex items-center gap-2">
                                        <div className="w-6 h-6 rounded-md bg-primary text-primary-foreground flex items-center justify-center font-bold text-xs">
                                            {storeInitial}
                                        </div>
                                        <span className="font-semibold text-slate-900 text-sm">{storeName}</span>
                                    </div>
                                )}
                            </div>

                            <p className="text-slate-600 leading-relaxed max-w-sm">
                                {storeDescription}
                            </p>

                            <div className="flex flex-wrap gap-4 pt-1">
                                {store?.social_instagram && (
                                    <a
                                        href={`https://instagram.com/${store.social_instagram}`}
                                        target="_blank"
                                        rel="noreferrer"
                                        className="text-slate-500 hover:text-slate-900 transition-colors duration-[var(--dur-short)] focus-visible:outline-2 focus-visible:outline-primary"
                                    >
                                        Instagram
                                    </a>
                                )}
                                {store?.social_facebook && (
                                    <a
                                        href={store.social_facebook}
                                        target="_blank"
                                        rel="noreferrer"
                                        className="text-slate-500 hover:text-slate-900 transition-colors duration-[var(--dur-short)] focus-visible:outline-2 focus-visible:outline-primary"
                                    >
                                        Facebook
                                    </a>
                                )}
                                {store?.social_whatsapp && (
                                    <a
                                        href={`https://wa.me/${store.social_whatsapp}`}
                                        target="_blank"
                                        rel="noreferrer"
                                        className="text-slate-500 hover:text-slate-900 transition-colors duration-[var(--dur-short)] focus-visible:outline-2 focus-visible:outline-primary"
                                    >
                                        WhatsApp
                                    </a>
                                )}
                            </div>
                        </div>

                        {/* Navigation Links (Cols 6-8) */}
                        <div className="md:col-span-3 space-y-2.5">
                            <div className="text-slate-900 font-semibold">Platform</div>
                            <ul className="space-y-2 text-slate-600">
                                <li>
                                    <a href="#services" className="hover:text-slate-900 transition-colors duration-[var(--dur-short)]">
                                        Services
                                    </a>
                                </li>
                                <li>
                                    <a href="#how-it-works" className="hover:text-slate-900 transition-colors duration-[var(--dur-short)]">
                                        How it works
                                    </a>
                                </li>
                                <li>
                                    <Link href="/register" className="hover:text-slate-900 transition-colors duration-[var(--dur-short)]">
                                        Register
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/login" className="hover:text-slate-900 transition-colors duration-[var(--dur-short)]">
                                        Sign in
                                    </Link>
                                </li>
                            </ul>
                        </div>

                        {/* Contact & Legal (Cols 9-12) */}
                        <div className="md:col-span-4 space-y-2.5">
                            <div className="text-slate-900 font-semibold">Contact and legal</div>
                            <ul className="space-y-2 text-slate-600">
                                {store?.contact_email && (
                                    <li>
                                        <a href={`mailto:${store.contact_email}`} className="hover:text-slate-900 transition-colors duration-[var(--dur-short)]">
                                            {store.contact_email}
                                        </a>
                                    </li>
                                )}
                                {store?.contact_phone && (
                                    <li>
                                        <span>{store.contact_phone}</span>
                                    </li>
                                )}
                                <li>
                                    <Link href="/privacy-policy" className="hover:text-slate-900 transition-colors duration-[var(--dur-short)]">
                                        Privacy Policy
                                    </Link>
                                </li>
                                <li>
                                    <a href="#" className="hover:text-slate-900 transition-colors duration-[var(--dur-short)]">
                                        Terms of Service
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div className="pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-slate-500">
                        <p>&copy; {new Date().getFullYear()} {storeName}. All rights reserved.</p>
                        <p>
                            Powered by{' '}
                            <a
                                href="https://affanhub.com"
                                className="text-slate-700 hover:text-slate-900 transition-colors duration-[var(--dur-short)] font-medium"
                            >
                                AffanHub
                            </a>
                        </p>
                    </div>
                </div>
            </footer>

            {/* ─── WHATSAPP FLOATING TRIGGER ─── */}
            {store?.whatsapp_chat_enabled && store?.whatsapp_chat_phone && (
                <a
                    href={`https://wa.me/${store.whatsapp_chat_phone}?text=${encodeURIComponent(store.whatsapp_chat_message || 'Hello, I have a question.')}`}
                    target="_blank"
                    rel="noreferrer"
                    aria-label="Chat with us on WhatsApp"
                    className={`fixed right-6 z-[var(--z-sticky)] bg-[var(--color-wa)] text-white p-3.5 rounded-full hover:opacity-90 active:opacity-80 transition-opacity motion-reduce:transition-none duration-[var(--dur-short)] flex items-center justify-center focus-visible:outline-2 focus-visible:outline-primary ${
                        store?.tawk_chat_enabled ? 'bottom-24' : 'bottom-6'
                    }`}
                >
                    <svg className="w-6 h-6 fill-white" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.458L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.963C16.588 2.02 14.12 1.002 11.5 1.002c-5.442 0-9.87 4.372-9.874 9.802-.001 1.769.471 3.498 1.362 5.031L2.002 21.5l5.727-1.492zm11.233-5.918c-.3-.15-1.771-.875-2.046-.975-.276-.1-.477-.15-.677.15-.2.3-.777.975-.951 1.175-.175.2-.35.225-.65.075-3.04-1.522-4.14-2.522-4.9-3.825-.2-.35-.022-.538.127-.687.135-.135.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525-.075-.15-.677-1.625-.926-2.225-.244-.589-.493-.51-.677-.52l-.578-.01c-.2 0-.525.075-.8.375-.275.3-1.05 1.025-1.05 2.5s1.07 2.9 1.22 3.1c.15.2 2.105 3.213 5.098 4.5 1.205.52 2.146.83 2.875 1.06.73.23 1.396.197 1.92.12.584-.087 1.771-.725 2.021-1.425.25-.7.25-1.3 1.75-1.425-.075-.125-.375-.275-.675-.425z"/>
                    </svg>
                </a>
            )}
        </div>
    );
}

function TestimonialCard({ quote, name, role }: { quote: string; name: string; role: string }) {
    return (
        <div className="bg-white border border-slate-200/80 rounded-[var(--radius-card)] p-6 shadow-sm flex flex-col justify-between">
            <p className="text-xs sm:text-sm text-slate-700 leading-relaxed mb-6">
                {quote}
            </p>
            <div className="pt-4 border-t border-slate-100">
                <div className="font-semibold text-xs text-slate-900">{name}</div>
                <div className="text-[11px] text-slate-500">{role}</div>
            </div>
        </div>
    );
}
