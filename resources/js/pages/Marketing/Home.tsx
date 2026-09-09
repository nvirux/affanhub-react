import { Head, Link, usePage } from '@inertiajs/react';
import {
    ArrowRight, Menu, X, CheckCircle2, Zap, Users, BarChart3,
    Globe, Shield, Wallet, Store, Smartphone, Wifi, Tv, GraduationCap,
    FileBadge, ShieldCheck, ChevronRight
} from 'lucide-react';
import { useState } from 'react';

const NAV_LINKS = [
    { label: 'Features', href: '#features' },
    { label: 'How it works', href: '#how-it-works' },
    { label: 'Pricing', href: '#pricing' },
];

const FEATURES = [
    { icon: <Store className="w-5 h-5" />, title: 'Branded Storefront', desc: 'Your own subdomain or custom domain. Fully white-labelled — customers never see AffanHub.' },
    { icon: <BarChart3 className="w-5 h-5" />, title: 'Total Pricing Control', desc: 'Set your own margins per service. Real-time cost tracking keeps you profitable on every sale.' },
    { icon: <Users className="w-5 h-5" />, title: 'Built-in CRM', desc: 'Understand your customers. Manage balances, monitor activity, and handle support from one place.' },
    { icon: <Wallet className="w-5 h-5" />, title: 'Automated Wallet System', desc: 'Funding and withdrawals that just work. A complete wallet engine built for commercial flow.' },
    { icon: <Shield className="w-5 h-5" />, title: 'Staff Permissions', desc: 'Assign roles, restrict access, and track team activity without compromising security.' },
    { icon: <Globe className="w-5 h-5" />, title: 'Custom Domain Support', desc: 'Bring your own domain. Point your DNS and your store lives at your brand URL instantly.' },
];

const SERVICES = [
    { icon: <Smartphone className="w-4 h-4" />, label: 'Airtime' },
    { icon: <Wifi className="w-4 h-4" />, label: 'Data' },
    { icon: <Tv className="w-4 h-4" />, label: 'Cable TV' },
    { icon: <GraduationCap className="w-4 h-4" />, label: 'Education' },
    { icon: <Zap className="w-4 h-4" />, label: 'Electricity' },
    { icon: <FileBadge className="w-4 h-4" />, label: 'NIN' },
    { icon: <ShieldCheck className="w-4 h-4" />, label: 'BVN' },
    { icon: <Store className="w-4 h-4" />, label: 'CAC' },
];

const PLANS = [
    {
        name: 'Starter',
        price: 'Free',
        sub: 'No credit card required',
        highlight: false,
        features: ['2 Staff Accounts', '1,000 Customers', 'Standard Subdomain', 'All Core Services', 'Withdrawals Enabled'],
        cta: 'Start Free',
        href: '/register',
    },
    {
        name: 'Pro',
        price: '₦5,000',
        sub: 'per month',
        highlight: true,
        features: ['10 Staff Accounts', '5,000 Customers', 'Custom Domain', 'Full Branding Control', 'Priority Support'],
        cta: 'Get Pro',
        href: '/register',
    },
    {
        name: 'Enterprise',
        price: 'Custom',
        sub: 'For high-volume operations',
        highlight: false,
        features: [
            'Everything in Pro',
            'Unlimited Staff & Customers',
            'Custom API Access & Integrations',
            'Bespoke Platform Settings',
            'Need custom options? Talk to us!'
        ],
        cta: 'Contact Sales',
        href: 'mailto:sales@affanhub.com',
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

const STEPS = [
    { n: '01', title: 'Create Your Business', desc: 'Sign up, pick a store name, and claim your unique subdomain in under 2 minutes.' },
    { n: '02', title: 'Configure & Brand', desc: 'Upload your logo, set your customer-facing prices, and personalize your store\'s look.' },
    { n: '03', title: 'Select a Plan', desc: 'Choose the features and scale that match your ambition. Upgrade anytime, no lock-in.' },
    { n: '04', title: 'Go Live', desc: 'Launch your storefront and start processing real orders immediately.' },
];

const FAQS = [
    {
        q: 'Do I need technical skills to start?',
        a: 'Absolutely not. You can set up your store, change margins, and go live with just a few clicks from a simple, clean dashboard.'
    },
    {
        q: 'How do I make money with AffanHub?',
        a: 'You set your own customer-facing prices. For example, if a data plan costs you ₦210, you can sell it for ₦250 and keep the ₦40 profit automatically.'
    },
    {
        q: 'Can I connect my own domain name?',
        a: 'Yes. With Pro and Enterprise plans, you can map your custom domain (e.g. www.yourbrand.com) so your customers never see AffanHub.'
    },
    {
        q: 'How do wallet fundings work?',
        a: 'We generate dedicated virtual bank accounts for your store. When your customers transfer money, their wallet updates instantly in real time.'
    }
];

export default function MarketingHome() {
    const { merchant_url } = usePage<any>().props;
    const [mobileOpen, setMobileOpen] = useState(false);
    const getMerchantUrl = (path = '') => resolveMerchantUrl(merchant_url, path);

    return (
        <div className="min-h-screen bg-white text-slate-900 flex flex-col" style={{ fontFamily: "'Inter', system-ui, sans-serif" }}>
            <Head title="AffanHub — Launch Your Digital Services Business" />

            {/* NAV */}
            <header className="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur-sm border-b border-slate-200/80">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                    <div className="flex items-center gap-2 shrink-0">
                        <div className="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white font-black text-base">A</div>
                        <span className="font-bold text-slate-900 text-lg tracking-tight">AffanHub</span>
                    </div>

                    <nav className="hidden md:flex items-center gap-1">
                        {NAV_LINKS.map(l => (
                            <a key={l.label} href={l.href} className="px-3 py-1.5 text-sm text-slate-600 hover:text-slate-900 rounded-md hover:bg-slate-50 transition-all">{l.label}</a>
                        ))}
                        <a href="https://demo.affanhub.com" target="_blank" rel="noreferrer" className="px-3 py-1.5 text-sm text-slate-600 hover:text-slate-900 rounded-md hover:bg-slate-50 transition-all">Live Demo</a>
                    </nav>

                    <div className="hidden md:flex items-center gap-3">
                        <a href={getMerchantUrl('/login')} className="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Sign in</a>
                        <a href={getMerchantUrl('/register')} className="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:opacity-90 transition-all shadow-sm shadow-primary/20">
                            Launch My Store <ArrowRight className="w-3.5 h-3.5" />
                        </a>
                    </div>

                    <button className="md:hidden p-2 text-slate-500" onClick={() => setMobileOpen(!mobileOpen)}>
                        {mobileOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
                    </button>
                </div>

                {mobileOpen && (
                    <div className="md:hidden bg-white border-t border-slate-100 px-4 py-4 space-y-1">
                        {NAV_LINKS.map(l => (
                            <a key={l.label} href={l.href} onClick={() => setMobileOpen(false)} className="block px-3 py-2 text-sm text-slate-600 rounded-md hover:bg-slate-50">{l.label}</a>
                        ))}
                        <div className="pt-3 border-t border-slate-100 flex flex-col gap-2">
                            <a href={getMerchantUrl('/login')} className="block px-3 py-2 text-sm font-medium text-slate-600 text-center">Sign in</a>
                            <a href={getMerchantUrl('/register')} className="block px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg text-center">Launch My Store</a>
                        </div>
                    </div>
                )}
            </header>

            <main className="flex-1 pt-16">

                {/* HERO */}
                <section className="relative pt-20 pb-28 md:pt-36 md:pb-44 overflow-hidden">
                    <div className="absolute inset-0 pointer-events-none">
                        <div className="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[700px] rounded-full opacity-[0.06]"
                            style={{ background: 'radial-gradient(circle, oklch(0.50 0.25 260) 0%, transparent 70%)' }} />
                    </div>

                    <div className="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">

                        <h1 className="text-4xl sm:text-5xl md:text-[68px] font-extrabold text-slate-900 leading-[1.06] tracking-tight mb-7">
                            Launch your digital<br className="hidden sm:block" />
                            <span className="text-primary"> services empire.</span>
                        </h1>

                        <p className="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed mb-10">
                            AffanHub is the all-in-one platform to sell airtime, data, TV subs, and identity services under your own brand. We handle the engine — you own the business.
                        </p>

                        <div className="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href={getMerchantUrl('/register')} className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-primary text-white font-semibold rounded-lg text-base hover:opacity-90 transition-all shadow-lg shadow-primary/20">
                                Launch My Store Free <ArrowRight className="w-4 h-4" />
                            </a>
                            <a href="https://demo.affanhub.com" target="_blank" rel="noreferrer" className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 text-slate-700 font-semibold rounded-lg text-base border border-slate-200 hover:bg-slate-50 transition-all">
                                Try Live Demo
                            </a>
                        </div>

                        <p className="mt-5 text-xs text-slate-400 font-medium">No credit card required · Free plan available · Setup in 2 minutes</p>
                    </div>
                </section>

                {/* STAT BAR */}
                <section className="border-y border-slate-100 bg-slate-50/70 py-8">
                    <div className="max-w-5xl mx-auto px-4 sm:px-6">
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-y-6 gap-x-4 md:gap-0 md:divide-x divide-slate-200">
                            <StatBox value="2 min" label="Average setup time" />
                            <StatBox value="8+" label="Service categories" />
                            <StatBox value="99.9%" label="Platform uptime" />
                            <StatBox value="₦0" label="To get started" />
                        </div>
                    </div>
                </section>

                {/* SERVICES STRIP */}
                <section className="py-10 bg-white border-b border-slate-100">
                    <div className="max-w-5xl mx-auto px-4 sm:px-6">
                        <p className="text-center text-xs font-bold tracking-widest text-slate-400 uppercase mb-6">Services your store can sell</p>
                        <div className="flex flex-wrap items-center justify-center gap-3">
                            {SERVICES.map(s => (
                                <div key={s.label} className="flex items-center gap-2 px-4 py-2 bg-slate-50 border border-slate-200 rounded-full text-sm font-semibold text-slate-600">
                                    <span className="text-primary">{s.icon}</span>
                                    {s.label}
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* FEATURES */}
                <section id="features" className="py-20 md:py-32">
                    <div className="max-w-6xl mx-auto px-4 sm:px-6">
                        <div className="text-center mb-16 max-w-2xl mx-auto">
                            <p className="text-xs font-bold tracking-widest text-primary uppercase mb-3">Features</p>
                            <h2 className="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight mb-4">
                                Everything you need to run a serious business.
                            </h2>
                            <p className="text-slate-500 text-lg">Simple, powerful tools built for Nigerian entrepreneurs and digital service resellers.</p>
                        </div>

                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            {FEATURES.map(f => (
                                <FeatureCard key={f.title} icon={f.icon} title={f.title} desc={f.desc} />
                            ))}
                        </div>
                    </div>
                </section>

                {/* MERCHANT DASHBOARD VISUAL PREVIEW */}
                <section className="py-20 md:py-28 bg-slate-50 border-y border-slate-100">
                    <div className="max-w-6xl mx-auto px-4 sm:px-6">
                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
                            <div>
                                <p className="text-xs font-bold tracking-widest text-primary uppercase mb-3">Merchant Console</p>
                                <h2 className="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight leading-tight mb-6">
                                    Control your entire business from one dashboard.
                                </h2>
                                <p className="text-slate-500 text-lg leading-relaxed mb-6">
                                    Our merchant administration panel gives you complete authority over your business. Manage your customers, track transactions, set individual service margins, and monitor performance in real time.
                                </p>
                                <div className="space-y-4">
                                    <div className="flex items-start gap-3">
                                        <CheckCircle2 className="w-5 h-5 text-primary shrink-0 mt-0.5" />
                                        <div>
                                            <p className="font-semibold text-slate-900">Custom Margin Manager</p>
                                            <p className="text-sm text-slate-500">Instantly increase or decrease your markup rates for Airtime, Data, and Bills.</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start gap-3">
                                        <CheckCircle2 className="w-5 h-5 text-primary shrink-0 mt-0.5" />
                                        <div>
                                            <p className="font-semibold text-slate-900">Customer Wallet Audits</p>
                                            <p className="text-sm text-slate-500">Monitor deposits, debit history, and manually credit or debit customer wallets if needed.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Dashboard Mockup */}
                            <div className="bg-slate-900 rounded-2xl border border-slate-800 shadow-2xl overflow-hidden text-slate-300">
                                <div className="bg-slate-950 px-4 py-3 flex items-center justify-between border-b border-slate-800">
                                    <div className="flex items-center gap-1.5">
                                        <span className="w-3 h-3 rounded-full bg-red-500/80"></span>
                                        <span className="w-3 h-3 rounded-full bg-yellow-500/80"></span>
                                        <span className="w-3 h-3 rounded-full bg-green-500/80"></span>
                                        <span className="ml-3 text-xs text-slate-500 font-mono select-none">AffanHub Merchant Console</span>
                                    </div>
                                    <span className="text-[10px] bg-primary/20 text-primary font-bold px-2 py-0.5 rounded">Store Admin</span>
                                </div>
                                <div className="p-6 space-y-6">
                                    {/* Stats grid */}
                                    <div className="grid grid-cols-3 gap-3">
                                        <div className="bg-slate-950/60 p-3 rounded-lg border border-slate-800/80">
                                            <p className="text-[10px] text-slate-500 uppercase font-semibold">Total Revenue</p>
                                            <p className="text-sm font-bold text-white mt-0.5">₦348,250.00</p>
                                        </div>
                                        <div className="bg-slate-950/60 p-3 rounded-lg border border-slate-800/80">
                                            <p className="text-[10px] text-slate-500 uppercase font-semibold">Net Profit</p>
                                            <p className="text-sm font-bold text-emerald-400 mt-0.5">+₦41,890.00</p>
                                        </div>
                                        <div className="bg-slate-950/60 p-3 rounded-lg border border-slate-800/80">
                                            <p className="text-[10px] text-slate-500 uppercase font-semibold">Total Users</p>
                                            <p className="text-sm font-bold text-white mt-0.5">1,240</p>
                                        </div>
                                    </div>

                                    {/* Pricing Margin Manager mockup */}
                                    <div className="bg-slate-950/40 p-4 rounded-xl border border-slate-800 space-y-3">
                                        <p className="text-xs font-bold text-white flex items-center gap-1.5">
                                            <Zap className="w-3.5 h-3.5 text-primary" /> Margin & Pricing Controller
                                        </p>
                                        <div className="space-y-2">
                                            {[
                                                { label: 'MTN Data (1GB)', cost: '₦210', price: '₦250', margin: '+19%' },
                                                { label: 'Airtel Airtime', cost: 'Cost - 3%', price: 'Cost - 1%', margin: '+2%' },
                                            ].map((item, idx) => (
                                                <div key={idx} className="flex items-center justify-between text-xs py-2 border-b border-slate-800/60 last:border-0">
                                                    <div>
                                                        <p className="font-semibold text-slate-300">{item.label}</p>
                                                        <p className="text-[10px] text-slate-500">API Cost: {item.cost}</p>
                                                    </div>
                                                    <div className="text-right">
                                                        <p className="font-bold text-white">{item.price}</p>
                                                        <p className="text-[10px] text-emerald-400 font-medium">{item.margin} margin</p>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* HOW IT WORKS */}
                <section id="how-it-works" className="py-20 md:py-32">
                    <div className="max-w-5xl mx-auto px-4 sm:px-6">
                        <div className="text-center mb-16">
                            <p className="text-xs font-bold tracking-widest text-primary uppercase mb-3">Process</p>
                            <h2 className="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight mb-4">Go live in 4 simple steps.</h2>
                            <p className="text-lg text-slate-500 max-w-xl mx-auto">We've stripped away the technical complexity. Focus on your brand — we handle the engine.</p>
                        </div>

                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            {STEPS.map((s, i) => (
                                <div key={s.n} className="relative bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-sm transition-all">
                                    {i < STEPS.length - 1 && (
                                        <ChevronRight className="hidden lg:block absolute -right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-300 z-10" />
                                    )}
                                    <p className="text-3xl font-black text-slate-100 mb-4 tracking-tight">{s.n}</p>
                                    <h3 className="font-bold text-slate-900 mb-2">{s.title}</h3>
                                    <p className="text-sm text-slate-500 leading-relaxed">{s.desc}</p>
                                </div>
                            ))}
                        </div>

                        {/* Demo callout */}
                        <div className="mt-12 bg-white border border-slate-200 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                            <div>
                                <p className="font-bold text-slate-900 text-lg mb-1">Try the customer experience before you launch.</p>
                                <p className="text-slate-500 text-sm">Open the live demo, sign in with the shared demo account, and explore wallet funding, purchases, and the dashboard flow.</p>
                            </div>
                            <a href="https://demo.affanhub.com" target="_blank" rel="noreferrer"
                                className="shrink-0 inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white text-sm font-semibold rounded-lg hover:opacity-90 transition-all whitespace-nowrap">
                                Open Live Demo <ArrowRight className="w-4 h-4" />
                            </a>
                        </div>
                    </div>
                </section>

                {/* PRICING */}
                <section id="pricing" className="py-20 md:py-32 bg-slate-50 border-y border-slate-100">
                    <div className="max-w-5xl mx-auto px-4 sm:px-6">
                        <div className="text-center mb-16">
                            <p className="text-xs font-bold tracking-widest text-primary uppercase mb-3">Pricing</p>
                            <h2 className="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight mb-4">Start free. Scale as you grow.</h2>
                            <p className="text-lg text-slate-500">No lock-in. Upgrade or downgrade anytime. Cancel whenever.</p>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-5 items-start">
                            {PLANS.map(p => (
                                <div key={p.name} className={`rounded-2xl border p-7 flex flex-col gap-6 transition-all ${p.highlight ? 'bg-primary border-primary shadow-xl shadow-primary/15 text-white' : 'bg-white border-slate-200 hover:shadow-md hover:border-slate-300'}`}>
                                    <div>
                                        {p.highlight && (
                                            <span className="inline-block px-2.5 py-0.5 bg-white/20 text-white text-xs font-bold rounded-full mb-3">Most Popular</span>
                                        )}
                                        <p className={`font-bold text-lg mb-1 ${p.highlight ? 'text-white' : 'text-slate-900'}`}>{p.name}</p>
                                        <p className={`text-3xl font-extrabold tracking-tight ${p.highlight ? 'text-white' : 'text-slate-900'}`}>{p.price}</p>
                                        <p className={`text-xs mt-1 ${p.highlight ? 'text-white/70' : 'text-slate-400'}`}>{p.sub}</p>
                                    </div>
                                    <ul className="space-y-3 flex-1">
                                        {p.features.map(f => (
                                            <li key={f} className={`flex items-center gap-2.5 text-sm ${p.highlight ? 'text-white/90' : 'text-slate-600'}`}>
                                                <CheckCircle2 className={`w-4 h-4 shrink-0 ${p.highlight ? 'text-white/80' : 'text-primary'}`} />
                                                {f}
                                            </li>
                                        ))}
                                    </ul>
                                    {p.name === 'Enterprise' ? (
                                        <a href={p.href}
                                            className={`inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg transition-all ${p.highlight ? 'bg-white text-primary hover:bg-white/90' : 'bg-primary text-white hover:opacity-90 shadow-sm shadow-primary/20'}`}>
                                            {p.cta} <ArrowRight className="w-3.5 h-3.5" />
                                        </a>
                                    ) : (
                                        <a href={getMerchantUrl(p.href)}
                                            className={`inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg transition-all ${p.highlight ? 'bg-white text-primary hover:bg-white/90' : 'bg-primary text-white hover:opacity-90 shadow-sm shadow-primary/20'}`}>
                                            {p.cta} <ArrowRight className="w-3.5 h-3.5" />
                                        </a>
                                    )}
                                </div>
                            ))}
                        </div>
                        <p className="text-center text-xs text-slate-400 mt-6">Payment secured · Cancel anytime · No hidden fees</p>
                    </div>
                </section>

                {/* FAQ SECTION */}
                <section className="py-20 md:py-32 bg-white">
                    <div className="max-w-3xl mx-auto px-4 sm:px-6">
                        <div className="text-center mb-16">
                            <p className="text-xs font-bold tracking-widest text-primary uppercase mb-3">FAQ</p>
                            <h2 className="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">Frequently Asked Questions</h2>
                        </div>
                        <div className="space-y-4">
                            {FAQS.map((faq, idx) => (
                                <FaqRow key={idx} question={faq.q} answer={faq.a} />
                            ))}
                        </div>
                    </div>
                </section>

                {/* CTA */}
                <section className="py-16 md:py-24 bg-slate-900">
                    <div className="max-w-4xl mx-auto px-4 sm:px-6 text-center">
                        <h2 className="text-3xl md:text-4xl font-bold text-white tracking-tight mb-5">
                            Ready to launch your digital empire?
                        </h2>
                        <p className="text-slate-400 text-lg mb-10 max-w-xl mx-auto">
                            Join thousands of entrepreneurs who trust AffanHub to power their digital services business.
                        </p>
                        <div className="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href={getMerchantUrl('/register')} className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-primary text-white font-semibold rounded-lg text-base hover:opacity-90 transition-all shadow-lg shadow-primary/30">
                                Get Started Today — It's Free <ArrowRight className="w-4 h-4" />
                            </a>
                            <a href="mailto:hello@affanhub.com" className="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 text-slate-300 font-semibold rounded-lg text-base border border-slate-700 hover:bg-slate-800 transition-all">
                                Contact us
                            </a>
                        </div>
                        <p className="mt-5 text-xs text-slate-600">No credit card required · Instant setup</p>
                    </div>
                </section>
            </main>

            {/* FOOTER */}
            <footer className="bg-slate-900 border-t border-slate-800 py-14">
                <div className="max-w-6xl mx-auto px-4 sm:px-6">
                    <div className="flex flex-col md:flex-row items-start justify-between gap-10 mb-12">
                        <div className="max-w-xs">
                            <div className="flex items-center gap-2 mb-4">
                                <div className="w-7 h-7 rounded-md bg-primary flex items-center justify-center text-white font-black text-sm">A</div>
                                <span className="font-bold text-white text-lg">AffanHub</span>
                            </div>
                            <p className="text-sm text-slate-500 leading-relaxed">
                                The simplest way to run a digital services business for entrepreneurs and resellers.
                            </p>
                        </div>

                        <div className="grid grid-cols-2 gap-10 sm:gap-24">
                            <div>
                                <p className="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Platform</p>
                                <ul className="space-y-2.5 text-sm">
                                    <li><a href="#features" className="text-slate-500 hover:text-white transition-colors">Features</a></li>
                                    <li><a href="#how-it-works" className="text-slate-500 hover:text-white transition-colors">How it works</a></li>
                                    <li><a href="#pricing" className="text-slate-500 hover:text-white transition-colors">Pricing</a></li>
                                    <li><a href="https://demo.affanhub.com" className="text-slate-500 hover:text-white transition-colors">Live Demo</a></li>
                                </ul>
                            </div>
                            <div>
                                <p className="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Company</p>
                                <ul className="space-y-2.5 text-sm">
                                    <li><a href={getMerchantUrl('/login')} className="text-slate-500 hover:text-white transition-colors">Sign in</a></li>
                                    <li><a href={getMerchantUrl('/register')} className="text-slate-500 hover:text-white transition-colors">Create Account</a></li>
                                    <li><Link href="/privacy" className="text-slate-500 hover:text-white transition-colors">Privacy Policy</Link></li>
                                    <li><Link href="/terms" className="text-slate-500 hover:text-white transition-colors">Terms of Service</Link></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div className="pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <p className="text-xs text-slate-600">&copy; {new Date().getFullYear()} AffanHub. All rights reserved.</p>
                        <p className="text-xs text-slate-600">Built for Nigerian entrepreneurs.</p>
                    </div>
                </div>
            </footer>
        </div>
    );
}

function StatBox({ value, label }: { value: string; label: string }) {
    return (
        <div className="text-center py-2 md:py-0 md:px-8">
            <p className="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">{value}</p>
            <p className="text-sm text-slate-500 mt-1">{label}</p>
        </div>
    );
}

function FeatureCard({ icon, title, desc }: { icon: React.ReactNode; title: string; desc: string }) {
    return (
        <div className="bg-white border border-slate-200 rounded-xl p-6 hover:shadow-md hover:border-slate-300 transition-all group cursor-default">
            <div className="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mb-5 group-hover:bg-primary group-hover:text-white transition-all">
                {icon}
            </div>
            <h3 className="font-semibold text-slate-900 mb-2">{title}</h3>
            <p className="text-sm text-slate-500 leading-relaxed">{desc}</p>
        </div>
    );
}

function FaqRow({ question, answer }: { question: string; answer: string }) {
    const [isOpen, setIsOpen] = useState(false);
    return (
        <div className="border-b border-slate-200 py-4 last:border-0">
            <button
                className="w-full flex items-center justify-between text-left font-semibold text-slate-900 py-2 focus:outline-none"
                onClick={() => setIsOpen(!isOpen)}
            >
                <span className="pr-4">{question}</span>
                <span className="text-slate-400 text-lg font-mono select-none">{isOpen ? '−' : '+'}</span>
            </button>
            {isOpen && (
                <p className="text-sm text-slate-500 pb-2 leading-relaxed">
                    {answer}
                </p>
            )}
        </div>
    );
}
