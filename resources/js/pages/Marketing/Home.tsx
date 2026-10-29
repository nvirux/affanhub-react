/*
 * Hallmark · v2.0.0
 * theme: modern-minimal / affanhub-cobalt · brand: AffanHub
 * shape: Split Studio · hero: Counter · nav: edge-aligned · footer: colophon block
 * material: real (telecom carrier icons, live margin controller, authentic plans and limits)
 * critique: authoritative fintech SaaS, structured hierarchy, high readability, accessible contrast
 * answered: 46a verified from project's original StatBox and architecture
 */

import { Head, Link, usePage } from '@inertiajs/react';
import {
    ArrowRight, Menu, X, CheckCircle2, Zap, Users, BarChart3,
    Globe, Shield, Wallet, Store, Smartphone, Wifi, Tv, GraduationCap,
    FileBadge, ShieldCheck, ExternalLink, Check, Sliders
} from 'lucide-react';
import { useState, useMemo } from 'react';

interface NavLink {
    label: string;
    href: string;
}

const NAV_LINKS: NavLink[] = [
    { label: 'Features', href: '#features' },
    { label: 'Live preview', href: '#preview' },
    { label: 'Services', href: '#services' },
    { label: 'Profit calculator', href: '#calculator' },
    { label: 'How it works', href: '#how-it-works' },
    { label: 'Pricing', href: '#pricing' },
    { label: 'FAQs', href: '#faqs' },
    { label: 'Guides', href: '/blog' },
];

const FEATURES = [
    {
        icon: <Globe className="size-5 text-primary" />,
        title: 'Custom domains and automated SSL',
        desc: 'Connect your own .com or .ng domain with 1-click Cloudflare DNS. Your customers see only your brand name.',
    },
    {
        icon: <BarChart3 className="size-5 text-primary" />,
        title: 'Total margin and pricing control',
        desc: 'Set custom retail markups per service. Live cost tracking ensures every automated sale remains profitable.',
    },
    {
        icon: <Wallet className="size-5 text-primary" />,
        title: 'Automated virtual bank accounts',
        desc: 'Dedicated Moniepoint and Wema accounts generated for every customer. Deposits fund orders in under 5 seconds.',
    },
    {
        icon: <Shield className="size-5 text-primary" />,
        title: 'Multi-tenant staff permissions',
        desc: 'Assign operator roles, restrict sensitive actions, and inspect detailed audit trails without security risks.',
    },
    {
        icon: <Users className="size-5 text-primary" />,
        title: 'Customer management and wallet audits',
        desc: 'Manage customer accounts, verify transaction histories, and resolve customer requests directly from one console.',
    },
    {
        icon: <Zap className="size-5 text-primary" />,
        title: 'Instant telecom delivery pipeline',
        desc: 'Direct carrier integrations with automatic failover ensure airtime, data, and tokens deliver in under 3 seconds.',
    },
];

const SERVICES = [
    { icon: <Smartphone className="size-4" />, name: 'Airtime VTU', networks: 'MTN, Airtel, Glo, 9mobile', plan: 'All plans' },
    { icon: <Wifi className="size-4" />, name: 'Data bundles', networks: 'SME, Corporate Gifting, Direct', plan: 'All plans' },
    { icon: <Tv className="size-4" />, name: 'Cable television', networks: 'DStv, GOtv, Startimes renewals', plan: 'All plans' },
    { icon: <Zap className="size-4" />, name: 'Electricity tokens', networks: 'All 11 Nigerian electricity Discos', plan: 'All plans' },
    { icon: <GraduationCap className="size-4" />, name: 'Education PINs', networks: 'WAEC and JAMB registrations', plan: 'All plans' },
    { icon: <FileBadge className="size-4" />, name: 'NIN verification', networks: 'National identity lookups and slips', plan: 'Pro & Enterprise' },
    { icon: <ShieldCheck className="size-4" />, name: 'BVN verification', networks: 'Instant bank verification number lookup', plan: 'Pro & Enterprise' },
    { icon: <Store className="size-4" />, name: 'CAC registry', networks: 'Business name registration filings', plan: 'Pro & Enterprise' },
];

const PLANS = [
    {
        name: 'Starter',
        price: '₦0',
        sub: 'Zero upfront payment',
        desc: 'Essential features to launch your branded digital store.',
        highlight: false,
        features: [
            '2 staff accounts',
            '1,000 customer capacity',
            'Standard AffanHub subdomain',
            'Core telecom and utility services',
            'Direct bank withdrawals enabled',
        ],
        cta: 'Start for free',
        href: '/register',
    },
    {
        name: 'Pro',
        price: '₦4,999',
        sub: 'per month',
        desc: 'Advanced branding and custom domain for scaling merchants.',
        highlight: true,
        features: [
            '10 staff accounts',
            '5,000 customer capacity',
            'Custom domain connection (yourbrand.ng)',
            'Full white-label branding',
            'Custom profit margins and pricing control',
            'Customer referral rewards program',
            'Priority support SLA',
        ],
        cta: 'Start 14-day free trial',
        href: '/register',
    },
    {
        name: 'Enterprise',
        price: 'Custom',
        sub: 'volume based',
        desc: 'Bespoke infrastructure for aggregator operations and fintechs.',
        highlight: false,
        features: [
            'Unlimited staff accounts',
            'Unlimited customer volume',
            'Direct developer API access and webhooks',
            'Dedicated account manager',
            'Custom SLA and failover infrastructure',
        ],
        cta: 'Contact enterprise sales',
        href: 'mailto:sales@affanhub.com',
    },
];

const STEPS = [
    {
        step: '01',
        title: 'Create your account',
        desc: 'Register in under 2 minutes and claim your free storefront subdomain on AffanHub.',
    },
    {
        step: '02',
        title: 'Configure prices and branding',
        desc: 'Upload your store logo, connect your custom domain, and choose your wholesale markup margins.',
    },
    {
        step: '03',
        title: 'Serve customers and withdraw profits',
        desc: 'Customers fund dedicated virtual accounts, orders deliver automatically, and profits settle instantly to your bank.',
    },
];

const FAQS = [
    {
        q: 'Can I really start with ₦0 upfront capital?',
        a: 'Yes. Setting up your storefront on AffanHub is free with zero upfront fees. When customers deposit money into their dedicated virtual accounts, those deposits fund your working balance for the wholesale order, while your profit markup is credited immediately to your merchant wallet.',
    },
    {
        q: 'Why is AffanHub better than purchasing a VTU PHP script?',
        a: 'Traditional scripts require ongoing cPanel or VPS hosting, manual security patches, fragile telecom APIs, and developer maintenance. AffanHub runs a modern multi-tenant cloud platform with automated Cloudflare SSL, 99.9% uptime, failover telecom routes, and automatic feature updates with zero maintenance required from you.',
    },
    {
        q: 'What happens when telecom APIs or prices change?',
        a: 'You never manage code or pay developers for upgrades. Whenever telecom networks alter their APIs or AffanHub adds new services (such as new electricity Discos, exam PINs, or identity verification), your store updates automatically with zero downtime.',
    },
    {
        q: 'How do I set my prices and withdraw my profits?',
        a: 'You define customer retail prices directly from your merchant console. For example, if 1GB of MTN SME data costs ₦215 wholesale, you can sell it for ₦260 and keep ₦45 net profit per gigabyte. You can withdraw your wallet balance to any Nigerian commercial or microfinance bank with instant settlement.',
    },
    {
        q: 'Can I connect my own custom brand domain name?',
        a: 'Yes. On Pro and Enterprise plans, you can connect your custom domain (such as yourbrand.ng or yourbrand.com) with automated Cloudflare SSL. Your storefront is completely white-labeled, and customers never see AffanHub.',
    },
    {
        q: 'How do automated customer wallet deposits work?',
        a: 'AffanHub generates dedicated virtual bank accounts (Moniepoint, Wema Bank) for each registered customer. When they transfer funds through their mobile banking app or USSD, their customer wallet credits automatically in under 5 seconds.',
    },
    {
        q: 'Do I need technical programming skills or a computer?',
        a: 'No programming skills or computer are required. The entire merchant management console is fully responsive, allowing you to configure markups, track customer transactions, and withdraw earnings directly from any smartphone.',
    },
];

const resolveMerchantUrl = (merchantBase?: string, path = '') => {
    if (merchantBase) return `${merchantBase}${path}`;
    if (typeof window !== 'undefined') {
        const host = window.location.host;
        const protocol = window.location.protocol;
        if (host.includes('localhost') || host.includes('127.0.0.1')) {
            return `${protocol}//merchant.localhost:8000${path}`;
        }
        return `${protocol}//merchant.${host}${path}`;
    }
    return `http://merchant.localhost:8000${path}`;
};

export default function MarketingHome() {
    const { merchant_url, proPrice } = usePage<any>().props;
    const [mobileOpen, setMobileOpen] = useState(false);
    const [previewTab, setPreviewTab] = useState<'console' | 'storefront'>('console');

    // Interactive Profit Estimator
    const [dailyOrders, setDailyOrders] = useState<number>(120);
    const [marginPerOrder, setMarginPerOrder] = useState<number>(45);

    const monthlyEstimatedProfit = useMemo(() => {
        return dailyOrders * marginPerOrder * 30;
    }, [dailyOrders, marginPerOrder]);

    const getMerchantUrl = (path = '') => resolveMerchantUrl(merchant_url, path);

    const plans = PLANS.map((p) => {
        if (p.name === 'Pro' && proPrice) {
            return { ...p, price: proPrice };
        }
        return p;
    });

    return (
        <div className="min-h-screen bg-slate-50 text-slate-900 flex flex-col selection:bg-primary/20 selection:text-primary">
            <Head title="AffanHub — Launch Your White-Label VTU Business in Nigeria" />

            {/* STICKY MAIN NAVIGATION */}
            <header className="sticky top-0 inset-x-0 z-50 bg-white border-b border-slate-200">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                    <Link
                        href="/"
                        className="flex items-center gap-2 shrink-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-md active:translate-y-px"
                        aria-label="AffanHub home"
                    >
                        <img
                            src="/images/affan-logo.png"
                            alt="AffanHub"
                            className="h-8 sm:h-9 w-auto object-contain"
                        />
                    </Link>

                    {/* Desktop Navigation Links */}
                    <nav className="hidden lg:flex items-center gap-1 text-xs font-medium text-slate-600">
                        {NAV_LINKS.map((l) => (
                            l.href.startsWith('/') ? (
                                <Link
                                    key={l.label}
                                    href={l.href}
                                    className="px-3 py-1.5 rounded-md hover:text-slate-900 hover:bg-slate-100 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary active:translate-y-px"
                                >
                                    {l.label}
                                </Link>
                            ) : (
                                <a
                                    key={l.label}
                                    href={l.href}
                                    className="px-3 py-1.5 rounded-md hover:text-slate-900 hover:bg-slate-100 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary active:translate-y-px"
                                >
                                    {l.label}
                                </a>
                            )
                        ))}
                    </nav>

                    {/* Desktop Actions */}
                    <div className="hidden md:flex items-center gap-3">
                        <a
                            href={getMerchantUrl('/login')}
                            className="text-xs font-semibold text-slate-700 hover:text-slate-900 px-3 py-2 rounded-md hover:bg-slate-100 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary active:translate-y-px"
                        >
                            Merchant sign in
                        </a>
                        <a
                            href={getMerchantUrl('/register')}
                            className="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-white text-xs font-semibold rounded-md hover:bg-blue-700 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 shadow-xs active:translate-y-px"
                        >
                            Start for free <ArrowRight className="size-3.5" />
                        </a>
                    </div>

                    {/* Mobile Menu Button */}
                    <button
                        type="button"
                        className="lg:hidden p-2 text-slate-600 hover:text-slate-900 rounded-md hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary active:translate-y-px"
                        onClick={() => setMobileOpen(!mobileOpen)}
                        aria-expanded={mobileOpen}
                        aria-label="Toggle navigation menu"
                    >
                        {mobileOpen ? <X className="size-5" /> : <Menu className="size-5" />}
                    </button>
                </div>

                {/* Mobile Menu Dropdown */}
                {mobileOpen && (
                    <div className="lg:hidden bg-white border-b border-slate-200 px-4 py-4 space-y-2 shadow-lg">
                        {NAV_LINKS.map((l) => (
                            l.href.startsWith('/') ? (
                                <Link
                                    key={l.label}
                                    href={l.href}
                                    onClick={() => setMobileOpen(false)}
                                    className="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-50 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary active:translate-y-px"
                                >
                                    {l.label}
                                </Link>
                            ) : (
                                <a
                                    key={l.label}
                                    href={l.href}
                                    onClick={() => setMobileOpen(false)}
                                    className="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-50 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary active:translate-y-px"
                                >
                                    {l.label}
                                </a>
                            )
                        ))}
                        <div className="pt-3 border-t border-slate-100 flex flex-col gap-2">
                            <a
                                href={getMerchantUrl('/login')}
                                className="block px-3 py-2 text-sm font-semibold text-slate-700 text-center rounded-md border border-slate-200 hover:bg-slate-50 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary active:translate-y-px"
                            >
                                Merchant sign in
                            </a>
                            <a
                                href={getMerchantUrl('/register')}
                                className="block px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-md text-center hover:bg-blue-700 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 shadow-xs active:translate-y-px"
                            >
                                Start for free
                            </a>
                        </div>
                    </div>
                )}
            </header>

            <main className="flex-1">

                {/* HERO SECTION */}
                <section className="relative pt-12 pb-16 md:pt-20 md:pb-24 bg-white border-b border-slate-200">
                    <div className="max-w-5xl mx-auto px-4 sm:px-6 text-center">

                        {/* Primary Headline (Hallmark budget: 5 words, 33 characters) */}
                        <h1 className="text-3xl sm:text-5xl md:text-6xl font-black text-slate-900 leading-[1.08] tracking-tight max-w-3xl mx-auto">
                            Launch your branded VTU business
                        </h1>

                        {/* Subhead (Hallmark budget: 9 words) */}
                        <p className="text-base sm:text-lg md:text-xl text-slate-600 max-w-xl mx-auto leading-relaxed mt-4 mb-8">
                            Sell airtime, data, and bill payments with zero code.
                        </p>

                        {/* Call to Actions */}
                        <div className="flex flex-col sm:flex-row gap-3 justify-center items-center">
                            <a
                                href={getMerchantUrl('/register')}
                                className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-primary text-white font-semibold rounded-lg text-sm sm:text-base hover:bg-blue-700 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 shadow-sm active:translate-y-px"
                            >
                                Start for free <ArrowRight className="size-4" />
                            </a>
                            <a
                                href="https://demo.affanhub.com"
                                target="_blank"
                                rel="noreferrer"
                                className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 text-slate-700 bg-white font-semibold rounded-lg text-sm sm:text-base border border-slate-300 hover:bg-slate-50 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2 active:translate-y-px"
                            >
                                Explore live demo <ExternalLink className="size-4 text-slate-400" />
                            </a>
                        </div>

                        {/* Concrete Guarantees */}
                        <div className="mt-8 flex flex-wrap items-center justify-center gap-6 text-xs text-slate-600">
                            <span className="inline-flex items-center gap-1.5 font-medium">
                                <Check className="size-4 text-emerald-600" /> Sub-3s automated delivery
                            </span>
                            <span className="inline-flex items-center gap-1.5 font-medium">
                                <Check className="size-4 text-emerald-600" /> Dedicated virtual accounts
                            </span>
                            <span className="inline-flex items-center gap-1.5 font-medium">
                                <Check className="size-4 text-emerald-600" /> Store setup in 2 minutes
                            </span>
                        </div>
                    </div>
                </section>

                {/* ARCHITECTURE METRICS STRIP */}
                <section className="bg-slate-50 border-b border-slate-200 py-8">
                    <div className="max-w-5xl mx-auto px-4 sm:px-6">
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-0 md:divide-x md:divide-slate-200">
                            <div className="text-center md:px-4">
                                <p className="text-2xl sm:text-3xl font-extrabold text-slate-900">2 min</p>
                                <p className="text-xs text-slate-600 mt-0.5">Average store setup time</p>
                            </div>
                            <div className="text-center md:px-4">
                                <p className="text-2xl sm:text-3xl font-extrabold text-slate-900">8 lines</p>
                                <p className="text-xs text-slate-600 mt-0.5">Digital services ready to sell</p>
                            </div>
                            <div className="text-center md:px-4">
                                <p className="text-2xl sm:text-3xl font-extrabold text-slate-900">99.9%</p>
                                <p className="text-xs text-slate-600 mt-0.5">API failover uptime rate</p>
                            </div>
                            <div className="text-center md:px-4">
                                <p className="text-2xl sm:text-3xl font-extrabold text-slate-900">36 states</p>
                                <p className="text-xs text-slate-600 mt-0.5">Instant settlement across Nigeria</p>
                            </div>
                        </div>
                    </div>
                </section>

                {/* DUAL CONSOLE SHOWCASE */}
                <section id="preview" className="py-16 md:py-24 bg-white border-b border-slate-200">
                    <div className="max-w-6xl mx-auto px-4 sm:px-6">
                        <div className="text-center max-w-2xl mx-auto mb-10">
                            <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Two interfaces built for Nigerian merchants
                            </h2>
                            <p className="text-slate-600 text-sm sm:text-base mt-2">
                                You manage wholesale markups and customers from your back-office console, while your buyers experience a fast mobile shopping checkout.
                            </p>

                            {/* View Switcher Controls */}
                            <div className="inline-flex p-1 bg-slate-100 rounded-lg border border-slate-200 mt-6">
                                <button
                                    type="button"
                                    onClick={() => setPreviewTab('console')}
                                    className={`px-4 py-2 rounded-md text-xs font-semibold whitespace-nowrap transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary active:translate-y-px disabled:opacity-50 disabled:cursor-not-allowed ${
                                        previewTab === 'console'
                                            ? 'bg-white text-slate-900 shadow-xs'
                                            : 'text-slate-600 hover:text-slate-900'
                                    }`}
                                >
                                    Merchant console
                                </button>
                                <button
                                    type="button"
                                    onClick={() => setPreviewTab('storefront')}
                                    className={`px-4 py-2 rounded-md text-xs font-semibold whitespace-nowrap transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary active:translate-y-px disabled:opacity-50 disabled:cursor-not-allowed ${
                                        previewTab === 'storefront'
                                            ? 'bg-white text-slate-900 shadow-xs'
                                            : 'text-slate-600 hover:text-slate-900'
                                    }`}
                                >
                                    Customer storefront
                                </button>
                            </div>
                        </div>

                        {/* Interactive Console Mockup */}
                        {previewTab === 'console' ? (
                            <div className="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden text-slate-200 max-w-4xl mx-auto">
                                <div className="bg-slate-950 px-5 py-3 flex items-center justify-between border-b border-slate-800">
                                    <div className="flex items-center gap-2">
                                        <span className="size-2.5 rounded-full bg-rose-500 inline-block" />
                                        <span className="size-2.5 rounded-full bg-amber-500 inline-block" />
                                        <span className="size-2.5 rounded-full bg-emerald-500 inline-block" />
                                        <span className="ml-3 text-xs text-slate-400">console.affanhub.com/dashboard</span>
                                    </div>
                                    <span className="text-xs bg-slate-800 text-slate-300 font-medium px-2.5 py-0.5 rounded border border-slate-700">
                                        Merchant back-office
                                    </span>
                                </div>
                                <div className="p-6 sm:p-8 space-y-6">
                                    {/* Console Stats */}
                                    <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div className="bg-slate-950 p-4 rounded-xl border border-slate-800">
                                            <p className="text-xs text-slate-400">Today store sales</p>
                                            <p className="text-xl font-bold text-white mt-1">₦348,250.00</p>
                                            <span className="text-xs text-emerald-400">+18.4% compared to yesterday</span>
                                        </div>
                                        <div className="bg-slate-950 p-4 rounded-xl border border-slate-800">
                                            <p className="text-xs text-slate-400">Retained margin profit</p>
                                            <p className="text-xl font-bold text-emerald-400 mt-1">+₦41,890.00</p>
                                            <span className="text-xs text-slate-400">Available for instant bank payout</span>
                                        </div>
                                        <div className="bg-slate-950 p-4 rounded-xl border border-slate-800">
                                            <p className="text-xs text-slate-400">Registered customers</p>
                                            <p className="text-xl font-bold text-white mt-1">1,240 users</p>
                                            <span className="text-xs text-blue-400">98 active today</span>
                                        </div>
                                    </div>

                                    {/* Wholesale Margin Overrides Table */}
                                    <div className="bg-slate-950 p-5 rounded-xl border border-slate-800 space-y-3">
                                        <div className="flex items-center justify-between border-b border-slate-800 pb-3">
                                            <p className="text-xs font-semibold text-white flex items-center gap-2">
                                                <Sliders className="size-4 text-primary" /> Live wholesale margin overrides
                                            </p>
                                            <span className="text-xs text-emerald-400">Applied instantly</span>
                                        </div>
                                        <div className="space-y-2">
                                            {[
                                                { service: 'MTN SME Data (1GB)', cost: '₦215.00', retail: '₦260.00', profit: '+₦45.00 (+20.9%)' },
                                                { service: 'Airtel Corporate Data (2GB)', cost: '₦430.00', retail: '₦520.00', profit: '+₦90.00 (+20.9%)' },
                                                { service: 'DStv Compact Renewal', cost: '₦15,500.00', retail: '₦15,750.00', profit: '+₦250.00 convenience fee' },
                                                { service: 'Moniepoint Virtual Account', cost: '₦0.00 fee', retail: 'Sub-5s credit', profit: 'Automated wallet funding' },
                                            ].map((row, i) => (
                                                <div key={i} className="flex flex-col sm:flex-row sm:items-center justify-between text-xs py-2.5 px-3 rounded-lg bg-slate-900 border border-slate-800 gap-2">
                                                    <div>
                                                        <p className="font-semibold text-slate-200">{row.service}</p>
                                                        <p className="text-xs text-slate-400">Wholesale cost: {row.cost}</p>
                                                    </div>
                                                    <div className="flex items-center gap-4">
                                                        <div className="text-left sm:text-right">
                                                            <p className="font-bold text-white">Your retail price: {row.retail}</p>
                                                            <p className="text-xs text-emerald-400 font-medium">{row.profit}</p>
                                                        </div>
                                                        <span className="px-2 py-1 bg-slate-800 rounded text-xs text-slate-300 border border-slate-700">
                                                            Active
                                                        </span>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ) : (
                            /* Customer Storefront View */
                            <div className="bg-white rounded-2xl border border-slate-200 overflow-hidden max-w-md mx-auto text-slate-900 shadow-md">
                                <div className="bg-slate-900 text-white px-5 py-3 flex items-center justify-between">
                                    <div className="flex items-center gap-2">
                                        <span className="size-2 rounded-full bg-emerald-400" />
                                        <span className="text-xs font-semibold">quickdata.ng</span>
                                    </div>
                                    <span className="text-xs bg-slate-800 text-slate-300 px-2 py-0.5 rounded">
                                        Customer storefront
                                    </span>
                                </div>
                                <div className="p-6 space-y-4 bg-slate-50">
                                    <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
                                        <div>
                                            <p className="text-xs text-slate-500">Dedicated virtual wallet</p>
                                            <p className="text-lg font-bold text-slate-900">₦12,450.00</p>
                                            <p className="text-xs text-slate-400">Wema Bank account: 8219042812</p>
                                        </div>
                                        <button
                                            type="button"
                                            className="px-3 py-1.5 bg-primary text-white text-xs font-semibold rounded-md hover:bg-blue-700 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary active:translate-y-px disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            + Fund wallet
                                        </button>
                                    </div>

                                    <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-xs space-y-3">
                                        <p className="text-xs font-bold text-slate-800">Select telecom network</p>
                                        <div className="grid grid-cols-4 gap-2">
                                            {[
                                                { name: 'MTN', logo: '/images/icons/mtn.png', active: true },
                                                { name: 'Airtel', logo: '/images/icons/airtel.png', active: false },
                                                { name: 'Glo', logo: '/images/icons/glo.png', active: false },
                                                { name: '9mobile', logo: '/images/icons/9mobile.png', active: false },
                                            ].map((carrier) => (
                                                <button
                                                    key={carrier.name}
                                                    type="button"
                                                    className={`py-2 px-1 text-xs font-semibold rounded-lg border text-center flex flex-col items-center gap-1 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary active:translate-y-px disabled:opacity-50 disabled:cursor-not-allowed ${
                                                        carrier.active
                                                            ? 'bg-blue-50 border-primary text-primary font-bold'
                                                            : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'
                                                    }`}
                                                >
                                                    <img
                                                        src={carrier.logo}
                                                        alt={carrier.name}
                                                        className="size-5 object-contain"
                                                    />
                                                    <span>{carrier.name}</span>
                                                </button>
                                            ))}
                                        </div>
                                        <div className="space-y-1">
                                            <label htmlFor="selected-plan-input" className="text-xs font-medium text-slate-600">Selected data plan</label>
                                            <div id="selected-plan-input" className="p-2.5 bg-slate-50 rounded-lg border border-slate-200 flex justify-between items-center text-xs">
                                                <span className="font-semibold text-slate-800">1.0 GB SME Data (30 days)</span>
                                                <span className="font-bold text-primary">₦260.00</span>
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            className="w-full py-2.5 bg-slate-900 text-white font-semibold rounded-lg text-xs flex items-center justify-center gap-1.5 hover:bg-slate-800 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 active:translate-y-px disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            Purchase bundle <ArrowRight className="size-3.5" />
                                        </button>
                                    </div>
                                    <p className="text-xs text-center text-slate-500">
                                        100% white-labeled. Your customers never see AffanHub.
                                    </p>
                                </div>
                            </div>
                        )}
                    </div>
                </section>

                {/* CORE PLATFORM FEATURES & BENEFITS */}
                <section id="features" className="py-16 md:py-24 bg-slate-50 border-b border-slate-200">
                    <div className="max-w-6xl mx-auto px-4 sm:px-6">
                        <div className="text-center max-w-2xl mx-auto mb-14">
                            <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Built for serious digital entrepreneurs
                            </h2>
                            <p className="text-slate-600 text-sm sm:text-base mt-2">
                                Bank-grade tools designed specifically for Nigerian resellers and telecom businesses.
                            </p>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {FEATURES.map((b) => (
                                <div
                                    key={b.title}
                                    className="bg-white border border-slate-200 rounded-xl p-6 shadow-xs hover:border-slate-300 transition-colors"
                                >
                                    <div className="size-10 rounded-lg bg-blue-50 text-primary flex items-center justify-center mb-4">
                                        {b.icon}
                                    </div>
                                    <h3 className="font-bold text-slate-900 text-base mb-2">
                                        {b.title}
                                    </h3>
                                    <p className="text-sm text-slate-600 leading-relaxed">
                                        {b.desc}
                                    </p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* DIGITAL SERVICES GRID */}
                <section id="services" className="py-16 md:py-24 bg-white border-b border-slate-200">
                    <div className="max-w-6xl mx-auto px-4 sm:px-6">
                        <div className="text-center max-w-2xl mx-auto mb-14">
                            <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Complete catalogue of digital services
                            </h2>
                            <p className="text-slate-600 text-sm sm:text-base mt-2">
                                Ready to sell immediately upon registration with automated provider connections.
                            </p>
                        </div>

                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            {SERVICES.map((s) => (
                                <div
                                    key={s.name}
                                    className="bg-slate-50 border border-slate-200 rounded-xl p-5 hover:bg-slate-100/70 transition-colors"
                                >
                                    <div className="flex items-center justify-between mb-3">
                                        <div className="size-8 rounded-lg bg-white border border-slate-200 text-primary flex items-center justify-center">
                                            {s.icon}
                                        </div>
                                        <span className="text-xs text-slate-500 bg-white px-2 py-0.5 rounded border border-slate-200">
                                            {s.plan}
                                        </span>
                                    </div>
                                    <h3 className="font-bold text-slate-900 text-sm">
                                        {s.name}
                                    </h3>
                                    <p className="text-xs text-slate-500 mt-1">
                                        {s.networks}
                                    </p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* INTERACTIVE PROFIT ESTIMATOR */}
                <section id="calculator" className="py-16 md:py-24 bg-slate-50 border-b border-slate-200">
                    <div className="max-w-4xl mx-auto px-4 sm:px-6">
                        <div className="text-center max-w-xl mx-auto mb-12">
                            <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Estimate your monthly reseller profit
                            </h2>
                            <p className="text-slate-600 text-sm sm:text-base mt-2">
                                You determine your own retail prices. Adjust the controls to project your earnings.
                            </p>
                        </div>

                        <div className="bg-slate-900 text-white rounded-2xl p-6 sm:p-10 border border-slate-800">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <div className="space-y-6">
                                    {/* Slider 1: Daily Orders */}
                                    <div>
                                        <div className="flex justify-between items-center mb-2">
                                            <label htmlFor="daily-orders-slider" className="text-xs font-semibold text-slate-300">
                                                Daily customer orders
                                            </label>
                                            <span className="text-sm font-bold text-white bg-slate-800 px-2.5 py-1 rounded border border-slate-700">
                                                {dailyOrders} orders per day
                                            </span>
                                        </div>
                                        <input
                                            id="daily-orders-slider"
                                            type="range"
                                            min="10"
                                            max="1000"
                                            step="10"
                                            value={dailyOrders}
                                            onChange={(e) => setDailyOrders(Number(e.target.value))}
                                            className="w-full accent-primary h-2 bg-slate-800 rounded-lg cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary disabled:opacity-50 disabled:cursor-not-allowed"
                                        />
                                        <div className="flex justify-between text-xs text-slate-400 mt-1">
                                            <span>10 orders</span>
                                            <span>500 orders</span>
                                            <span>1,000+ orders</span>
                                        </div>
                                    </div>

                                    {/* Slider 2: Average Profit per Order */}
                                    <div>
                                        <div className="flex justify-between items-center mb-2">
                                            <label htmlFor="margin-slider" className="text-xs font-semibold text-slate-300">
                                                Average profit margin per sale
                                            </label>
                                            <span className="text-sm font-bold text-emerald-400 bg-slate-800 px-2.5 py-1 rounded border border-slate-700">
                                                ₦{marginPerOrder} per sale
                                            </span>
                                        </div>
                                        <input
                                            id="margin-slider"
                                            type="range"
                                            min="15"
                                            max="200"
                                            step="5"
                                            value={marginPerOrder}
                                            onChange={(e) => setMarginPerOrder(Number(e.target.value))}
                                            className="w-full accent-emerald-500 h-2 bg-slate-800 rounded-lg cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 disabled:opacity-50 disabled:cursor-not-allowed"
                                        />
                                        <div className="flex justify-between text-xs text-slate-400 mt-1">
                                            <span>₦15 (Airtime/SME)</span>
                                            <span>₦100 (Bills/TV)</span>
                                            <span>₦200+ (Services)</span>
                                        </div>
                                    </div>

                                    <div className="p-3 bg-slate-950 rounded-lg border border-slate-800 text-xs text-slate-300 leading-relaxed">
                                        Example: Selling 100GB of MTN SME data daily at ₦45 margin yields ₦135,000 monthly profit from data bundles alone.
                                    </div>
                                </div>

                                {/* Projected Total Card */}
                                <div className="bg-slate-950 p-6 sm:p-8 rounded-xl border border-slate-800 text-center flex flex-col justify-between">
                                    <div>
                                        <p className="text-xs text-slate-400 font-medium">Projected monthly net profit</p>
                                        <div className="text-4xl sm:text-5xl font-black text-emerald-400 tracking-tight my-3">
                                            ₦{monthlyEstimatedProfit.toLocaleString()}
                                        </div>
                                        <p className="text-xs text-slate-400">
                                            Approximately ₦{(dailyOrders * marginPerOrder).toLocaleString()} net profit deposited each day
                                        </p>
                                    </div>

                                    <div className="mt-8 pt-6 border-t border-slate-800">
                                        <a
                                            href={getMerchantUrl('/register')}
                                            className="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-lg text-sm transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 focus-visible:ring-offset-2 active:translate-y-px"
                                        >
                                            Start earning today <ArrowRight className="size-4" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* HOW IT WORKS */}
                <section id="how-it-works" className="py-16 md:py-24 bg-white border-b border-slate-200">
                    <div className="max-w-5xl mx-auto px-4 sm:px-6">
                        <div className="text-center max-w-2xl mx-auto mb-14">
                            <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Go live in three straightforward steps
                            </h2>
                            <p className="text-slate-600 text-sm sm:text-base mt-2">
                                We handle the telecom pipeline while you focus on acquiring and serving your customers.
                            </p>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {STEPS.map((s) => (
                                <div
                                    key={s.step}
                                    className="bg-slate-50 border border-slate-200 rounded-xl p-6 relative"
                                >
                                    <span className="inline-block text-xs font-bold text-primary bg-blue-50 px-2.5 py-1 rounded mb-3">
                                        Step {s.step}
                                    </span>
                                    <h3 className="font-bold text-slate-900 text-base mb-2">
                                        {s.title}
                                    </h3>
                                    <p className="text-sm text-slate-600 leading-relaxed">
                                        {s.desc}
                                    </p>
                                </div>
                            ))}
                        </div>

                        {/* Demo Store Sandbox Box */}
                        <div className="mt-12 bg-slate-50 border border-slate-200 rounded-2xl p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                            <div className="space-y-1 text-center md:text-left">
                                <h3 className="font-bold text-slate-900 text-base sm:text-lg">
                                    Test the customer checkout before launching
                                </h3>
                                <p className="text-slate-600 text-xs sm:text-sm max-w-xl">
                                    Open our live demo store to simulate real wallet fundings, purchase airtime, and verify delivery speed firsthand.
                                </p>
                            </div>
                            <a
                                href="https://demo.affanhub.com"
                                target="_blank"
                                rel="noreferrer"
                                className="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-semibold rounded-lg transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 active:translate-y-px"
                            >
                                Open live demo <ExternalLink className="size-4" />
                            </a>
                        </div>
                    </div>
                </section>

                {/* PRICING SECTION */}
                <section id="pricing" className="py-16 md:py-24 bg-slate-50 border-b border-slate-200">
                    <div className="max-w-5xl mx-auto px-4 sm:px-6">
                        <div className="text-center max-w-2xl mx-auto mb-14">
                            <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Transparent subscription plans
                            </h2>
                            <p className="text-slate-600 text-sm sm:text-base mt-2">
                                Start with our free tier, then upgrade as your customer volume expands.
                            </p>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
                            {plans.map((p) => (
                                <div
                                    key={p.name}
                                    className={`rounded-2xl p-6 sm:p-8 flex flex-col justify-between transition-colors relative ${
                                        p.highlight
                                            ? 'bg-slate-900 border border-slate-800 text-white shadow-lg'
                                            : 'bg-white border border-slate-200 shadow-xs'
                                    }`}
                                >
                                    {p.highlight && (
                                        <div className="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 bg-emerald-500 text-slate-950 text-xs font-bold rounded-full">
                                            Most popular
                                        </div>
                                    )}

                                    <div>
                                        <div className="flex items-center justify-between mb-1 mt-1">
                                            <h3 className={`font-bold text-lg ${p.highlight ? 'text-white' : 'text-slate-900'}`}>{p.name}</h3>
                                            <span className={`text-xs px-2 py-0.5 rounded font-medium ${
                                                p.highlight ? 'bg-slate-800 text-emerald-400' : 'bg-slate-100 text-slate-600'
                                            }`}>
                                                {p.name === 'Starter' ? 'Free forever' : p.name === 'Pro' ? '14-day trial' : 'High volume'}
                                            </span>
                                        </div>
                                        <div className="flex items-baseline gap-1 my-3">
                                            <span className={`text-3xl sm:text-4xl font-extrabold ${p.highlight ? 'text-white' : 'text-slate-900'}`}>{p.price}</span>
                                            <span className={`text-xs ${p.highlight ? 'text-slate-400' : 'text-slate-500'}`}>{p.sub}</span>
                                        </div>
                                        <p className={`text-xs mb-6 ${p.highlight ? 'text-slate-400' : 'text-slate-500'}`}>{p.desc}</p>
                                        
                                        <div className={`h-px mb-6 ${p.highlight ? 'bg-slate-800' : 'bg-slate-100'}`} />

                                        <ul className={`space-y-3 mb-8 text-xs sm:text-sm ${p.highlight ? 'text-slate-200' : 'text-slate-600'}`}>
                                            {p.features.map((feature) => (
                                                <li key={feature} className="flex items-start gap-2.5">
                                                    <CheckCircle2 className={`size-4 shrink-0 mt-0.5 ${p.highlight ? 'text-emerald-400' : 'text-emerald-600'}`} />
                                                    <span>{feature}</span>
                                                </li>
                                            ))}
                                        </ul>
                                    </div>

                                    {p.name === 'Enterprise' ? (
                                        <a
                                            href={p.href}
                                            className="w-full inline-flex items-center justify-center gap-2 px-5 py-3 text-xs sm:text-sm font-semibold rounded-lg bg-slate-900 hover:bg-slate-800 text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 active:translate-y-px"
                                        >
                                            {p.cta} <ArrowRight className="size-3.5" />
                                        </a>
                                    ) : (
                                        <a
                                            href={getMerchantUrl(p.href)}
                                            className={`w-full inline-flex items-center justify-center gap-2 px-5 py-3 text-xs sm:text-sm font-semibold rounded-lg transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 active:translate-y-px ${
                                                p.highlight
                                                    ? 'bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold focus-visible:ring-emerald-400'
                                                    : 'bg-primary hover:bg-blue-700 text-white focus-visible:ring-primary'
                                            }`}
                                        >
                                            {p.cta} <ArrowRight className="size-3.5" />
                                        </a>
                                    )}
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* FAQS SECTION */}
                <section id="faqs" className="py-16 md:py-24 bg-white border-b border-slate-200">
                    <div className="max-w-3xl mx-auto px-4 sm:px-6">
                        <div className="text-center max-w-xl mx-auto mb-12">
                            <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Frequently asked questions
                            </h2>
                            <p className="text-slate-600 text-sm sm:text-base mt-2">
                                Clear answers about launching your VTU business on AffanHub.
                            </p>
                        </div>

                        <div className="space-y-3">
                            {FAQS.map((faq, idx) => (
                                <FaqRow key={idx} question={faq.q} answer={faq.a} />
                            ))}
                        </div>

                        {/* Guides Callout Box */}
                        <div className="mt-12 p-6 bg-slate-50 border border-slate-200 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                            <div>
                                <h3 className="font-bold text-slate-900 text-sm">
                                    Need step-by-step strategy guidance?
                                </h3>
                                <p className="text-xs text-slate-600 mt-0.5">
                                    Read setup walkthroughs, profit strategy tips, and tutorials on the blog.
                                </p>
                            </div>
                            <Link
                                href="/blog"
                                className="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-white rounded-md text-xs font-semibold hover:bg-blue-700 transition-colors shrink-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary shadow-xs active:translate-y-px"
                            >
                                Read guides <ArrowRight className="size-3.5" />
                            </Link>
                        </div>
                    </div>
                </section>

                {/* FINAL REGISTRATION CALL TO ACTION */}
                <section className="py-16 md:py-24 bg-slate-900 text-white">
                    <div className="max-w-4xl mx-auto px-4 sm:px-6 text-center">
                        <h2 className="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4">
                            Ready to launch your digital services business?
                        </h2>
                        <p className="text-slate-400 text-sm sm:text-base mb-8 max-w-xl mx-auto leading-relaxed">
                            Start selling Airtime, Data, and Utilities today. Your branded storefront can be live in under 2 minutes.
                        </p>
                        <div className="flex flex-col sm:flex-row gap-3 justify-center items-center">
                            <a
                                href={getMerchantUrl('/register')}
                                className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white font-semibold rounded-lg text-sm sm:text-base hover:bg-blue-700 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 shadow-sm active:translate-y-px"
                            >
                                Start for free <ArrowRight className="size-4" />
                            </a>
                            <a
                                href="mailto:hello@affanhub.com"
                                className="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 text-slate-300 font-semibold rounded-lg text-sm sm:text-base border border-slate-700 hover:bg-slate-800 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 active:translate-y-px"
                            >
                                Speak with sales
                            </a>
                        </div>
                    </div>
                </section>
            </main>

            {/* FOOTER */}
            <footer className="bg-slate-950 border-t border-slate-800 py-14 text-slate-400 text-xs">
                <div className="max-w-6xl mx-auto px-4 sm:px-6">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                        <div className="md:col-span-2 space-y-4">
                            <div className="bg-white rounded-lg px-3 py-1.5 inline-block">
                                <img
                                    src="/images/affan-logo.png"
                                    alt="AffanHub"
                                    className="h-7 w-auto object-contain"
                                />
                            </div>
                            <p className="text-slate-400 text-xs sm:text-sm leading-relaxed max-w-md">
                                Multi-tenant white-label telecom platform for Nigerian entrepreneurs and resellers to operate custom-branded digital utilities with automated delivery.
                            </p>
                            <div className="flex items-center gap-2 text-xs text-slate-400">
                                <span className="size-2 rounded-full bg-emerald-500 inline-block" />
                                <span>Operational across all 36 Nigerian states and the FCT</span>
                            </div>
                        </div>

                        <div>
                            <p className="text-xs font-bold text-white mb-3">Platform</p>
                            <ul className="space-y-2">
                                <li>
                                    <a href="#features" className="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded active:translate-y-px">
                                        Features
                                    </a>
                                </li>
                                <li>
                                    <a href="#preview" className="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded active:translate-y-px">
                                        Live preview
                                    </a>
                                </li>
                                <li>
                                    <a href="#services" className="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded active:translate-y-px">
                                        Services
                                    </a>
                                </li>
                                <li>
                                    <a href="#calculator" className="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded active:translate-y-px">
                                        Profit calculator
                                    </a>
                                </li>
                                <li>
                                    <a href="#how-it-works" className="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded active:translate-y-px">
                                        How it works
                                    </a>
                                </li>
                                <li>
                                    <a href="#pricing" className="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded active:translate-y-px">
                                        Pricing
                                    </a>
                                </li>
                                <li>
                                    <Link href="/blog" className="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded active:translate-y-px">
                                        Guides and articles
                                    </Link>
                                </li>
                                <li>
                                    <a href="https://demo.affanhub.com" target="_blank" rel="noreferrer" className="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded active:translate-y-px">
                                        Live demo store
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <p className="text-xs font-bold text-white mb-3">Account and legal</p>
                            <ul className="space-y-2">
                                <li>
                                    <a href={getMerchantUrl('/login')} className="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded active:translate-y-px">
                                        Merchant sign in
                                    </a>
                                </li>
                                <li>
                                    <a href={getMerchantUrl('/register')} className="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded active:translate-y-px">
                                        Create account free
                                    </a>
                                </li>
                                <li>
                                    <Link href="/privacy" className="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded active:translate-y-px">
                                        Privacy policy
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/terms" className="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded active:translate-y-px">
                                        Terms of service
                                    </Link>
                                </li>
                                <li>
                                    <a href="mailto:support@affanhub.com" className="hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded active:translate-y-px">
                                        Support desk
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div className="pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-slate-500 text-xs">
                        <p>&copy; {new Date().getFullYear()} AffanHub Technologies. All rights reserved.</p>
                        <p className="text-slate-400">Built for Nigerian telecom resellers and digital entrepreneurs.</p>
                    </div>
                </div>
            </footer>
        </div>
    );
}

function FaqRow({ question, answer }: { question: string; answer: string }) {
    const [isOpen, setIsOpen] = useState(false);
    return (
        <div className="bg-slate-50 border border-slate-200 rounded-xl overflow-hidden transition-colors">
            <button
                type="button"
                className="w-full flex items-center justify-between text-left font-bold text-slate-900 px-5 py-4 hover:bg-slate-100 transition-colors cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary active:translate-y-px"
                onClick={() => setIsOpen(!isOpen)}
                aria-expanded={isOpen}
            >
                <span className="pr-4 text-sm sm:text-base font-semibold text-slate-900">{question}</span>
                <span className="size-6 rounded-md bg-white border border-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold shrink-0">
                    {isOpen ? '−' : '+'}
                </span>
            </button>
            {isOpen && (
                <div className="px-5 pb-5 pt-1 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-200 bg-white">
                    {answer}
                </div>
            )}
        </div>
    );
}
