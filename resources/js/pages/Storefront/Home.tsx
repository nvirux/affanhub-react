import { Head, Link, usePage } from '@inertiajs/react';
import {
    Smartphone, Wifi, Tv, GraduationCap, ShieldCheck, FileBadge,
    Building2, CreditCard, Menu, X, ArrowRight, Zap, Lock, Clock,
    CheckCircle2, ChevronRight
} from 'lucide-react';
import { useState, useEffect } from 'react';

export default function Home() {
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
    const storeDescription = store?.description || `Welcome to ${storeName}. Buy airtime, data, TV subs, and run identity verifications — instantly, securely, 24/7.`;

    return (
        <div className="min-h-screen bg-white text-slate-900 flex flex-col" style={{ fontFamily: "'Inter', system-ui, sans-serif" }}>
            <Head title={`${storeName} — Digital Services`} />

            {/* ─── NAVBAR ─── */}
            <header className="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur-sm border-b border-slate-200/80">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-6">
                    {/* Logo */}
                    <div className="flex items-center gap-2.5 shrink-0">
                        {store?.logo_url ? (
                            <img src={store.logo_url} alt={storeName} className="h-8 max-w-[150px] object-contain" />
                        ) : (
                            <>
                                <div className="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white font-bold text-sm tracking-tight">
                                    {storeInitial}
                                </div>
                                <span className="font-semibold text-slate-900 text-[15px] tracking-tight">{storeName}</span>
                            </>
                        )}
                    </div>

                    {/* Desktop Nav */}
                    <nav className="hidden md:flex items-center gap-1">
                        <a href="#services" className="px-3 py-1.5 text-sm text-slate-600 hover:text-slate-900 rounded-md hover:bg-slate-50 transition-all">Services</a>
                        <a href="#how-it-works" className="px-3 py-1.5 text-sm text-slate-600 hover:text-slate-900 rounded-md hover:bg-slate-50 transition-all">How it works</a>
                    </nav>

                    {/* Desktop CTA */}
                    <div className="hidden md:flex items-center gap-3">
                        <Link href="/login" className="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
                            Sign in
                        </Link>
                        <Link href="/register">
                            <span className="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:opacity-90 transition-all shadow-sm shadow-primary/20">
                                Get Started <ArrowRight className="w-3.5 h-3.5" />
                            </span>
                        </Link>
                    </div>

                    {/* Mobile */}
                    <button className="md:hidden p-2 text-slate-500" onClick={() => setMobileMenuOpen(!mobileMenuOpen)}>
                        {mobileMenuOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
                    </button>
                </div>

                {/* Mobile Menu */}
                {mobileMenuOpen && (
                    <div className="md:hidden bg-white border-t border-slate-100 px-4 py-4 space-y-1">
                        <a href="#services" onClick={() => setMobileMenuOpen(false)} className="block px-3 py-2 text-sm text-slate-600 rounded-md hover:bg-slate-50">Services</a>
                        <a href="#how-it-works" onClick={() => setMobileMenuOpen(false)} className="block px-3 py-2 text-sm text-slate-600 rounded-md hover:bg-slate-50">How it works</a>
                        <div className="pt-3 border-t border-slate-100 flex flex-col gap-2">
                            <Link href="/login" className="block px-3 py-2 text-sm font-medium text-slate-600 text-center">Sign in</Link>
                            <Link href="/register" className="block px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg text-center">Get Started</Link>
                        </div>
                    </div>
                )}
            </header>

            <main className="flex-1 pt-16">

                {/* ─── HERO ─── */}
                <section className="relative pt-20 pb-28 md:pt-32 md:pb-40 overflow-hidden">
                    {/* Subtle background pattern */}
                    <div className="absolute inset-0 pointer-events-none">
                        <div className="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[600px] rounded-full opacity-[0.07]"
                             style={{ background: 'radial-gradient(circle, oklch(0.50 0.25 260) 0%, transparent 70%)' }} />
                    </div>

                    <div className="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">


                        <h1 className="text-4xl sm:text-5xl md:text-[64px] font-extrabold text-slate-900 leading-[1.08] tracking-tight mb-7">
                            Instant digital services,<br className="hidden sm:block" />
                            <span className="text-primary"> all in one place.</span>
                        </h1>

                        <p className="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed mb-10">
                            {storeDescription}
                        </p>

                        <div className="flex flex-col sm:flex-row gap-3 justify-center items-center">
                            <Link href="/register">
                                <span className="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-semibold rounded-lg text-base hover:opacity-90 transition-all shadow-lg shadow-primary/20">
                                    Create Free Account <ArrowRight className="w-4 h-4" />
                                </span>
                            </Link>
                            <Link href="/login">
                                <span className="inline-flex items-center gap-2 px-6 py-3 text-slate-700 font-semibold rounded-lg text-base border border-slate-200 hover:bg-slate-50 transition-all">
                                    Sign in to Dashboard
                                </span>
                            </Link>
                        </div>

                        {/* Trust badges */}
                        <div className="mt-10 flex flex-wrap items-center justify-center gap-5 text-sm text-slate-500">
                            <span className="flex items-center gap-1.5"><CheckCircle2 className="w-4 h-4 text-primary" /> Instant delivery</span>
                            <span className="flex items-center gap-1.5"><CheckCircle2 className="w-4 h-4 text-primary" /> Secured wallet</span>
                            <span className="flex items-center gap-1.5"><CheckCircle2 className="w-4 h-4 text-primary" /> No hidden fees</span>
                        </div>
                    </div>
                </section>

                {/* ─── SOCIAL PROOF / STAT BAR ─── */}
                <section className="border-y border-slate-100 bg-slate-50/70 py-8">
                    <div className="max-w-5xl mx-auto px-4 sm:px-6">
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-y-6 gap-x-4 md:gap-0 md:divide-x divide-slate-200">
                            <StatBox value="< 3s" label="Avg. delivery time" />
                            <StatBox value="99.9%" label="Platform uptime" />
                            <StatBox value="8+" label="Service categories" />
                            <StatBox value="24/7" label="System availability" />
                        </div>
                    </div>
                </section>

                {/* ─── NETWORKS WE SUPPORT ─── */}
                <section className="py-8 bg-white border-b border-slate-100">
                    <div className="max-w-5xl mx-auto px-4 sm:px-6">
                        <p className="text-center text-xs font-bold tracking-widest text-slate-400 uppercase mb-6">Networks & Providers Supported</p>
                        <div className="flex flex-wrap items-center justify-center gap-2 md:gap-3">
                            {['MTN', 'Airtel', 'GLO', '9Mobile', 'DSTV', 'GOtv', 'Startimes', 'WAEC', 'JAMB', 'NECO'].map(n => (
                                <span key={n} className="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-full text-sm font-semibold text-slate-600">
                                    {n}
                                </span>
                            ))}
                        </div>
                    </div>
                </section>

                {/* ─── SERVICES ─── */}
                <section id="services" className="py-20 md:py-32">
                    <div className="max-w-6xl mx-auto px-4 sm:px-6">

                        {/* Section header */}
                        <div className="max-w-2xl mb-14">
                            <p className="text-xs font-bold tracking-widest text-primary uppercase mb-3">Services</p>
                            <h2 className="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight leading-tight mb-4">
                                Everything your customers need.
                            </h2>
                            <p className="text-slate-500 text-lg leading-relaxed">
                                We connect directly to major telecoms and government APIs so your customers always get the best rates and fastest delivery.
                            </p>
                        </div>

                        {/* VTU & Bills */}
                        <div className="mb-14">
                            <p className="text-xs font-bold tracking-widest text-slate-400 uppercase mb-5">Utilities & Bills</p>
                            <div className="grid grid-cols-2 lg:grid-cols-4 gap-3">
                                <ServiceCard icon={<Smartphone className="w-5 h-5" />} title="Airtime" desc="MTN, Airtel, GLO, 9Mobile — instant top-up." accent="blue" />
                                <ServiceCard icon={<Wifi className="w-5 h-5" />} title="Data Bundles" desc="Discounted data plans, delivered immediately." accent="blue" />
                                <ServiceCard icon={<Tv className="w-5 h-5" />} title="TV Subscription" desc="DSTV, GOtv, and Startimes renewals." accent="blue" />
                                <ServiceCard icon={<GraduationCap className="w-5 h-5" />} title="Education Pins" desc="WAEC, NECO, JAMB scratch cards." accent="blue" />
                            </div>
                        </div>

                        {/* Identity */}
                        <div>
                            <p className="text-xs font-bold tracking-widest text-slate-400 uppercase mb-5">Identity & Verification</p>
                            <div className="grid grid-cols-2 lg:grid-cols-4 gap-3">
                                <ServiceCard icon={<FileBadge className="w-5 h-5" />} title="NIN Services" desc="Verify and modify National Identity records." accent="indigo" />
                                <ServiceCard icon={<ShieldCheck className="w-5 h-5" />} title="BVN Validation" desc="Instant Bank Verification Number checks." accent="indigo" />
                                <ServiceCard icon={<Building2 className="w-5 h-5" />} title="CAC Lookup" desc="Corporate Affairs Commission verification." accent="indigo" />
                                <ServiceCard icon={<CreditCard className="w-5 h-5" />} title="Wallet Funding" desc="Automated, secure deposits via bank transfer." accent="indigo" />
                            </div>
                        </div>
                    </div>
                </section>

                {/* ─── WHY US ─── */}
                <section className="py-20 md:py-28 bg-slate-50 border-y border-slate-100">
                    <div className="max-w-6xl mx-auto px-4 sm:px-6">
                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
                            <div>
                                <p className="text-xs font-bold tracking-widest text-primary uppercase mb-3">Why {storeName}</p>
                                <h2 className="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight leading-tight mb-6">
                                    Built for speed.<br /> Secured by design.
                                </h2>
                                <p className="text-slate-500 text-lg leading-relaxed mb-8">
                                    We've engineered every layer of the platform for reliability. Your funds are protected, your data is encrypted, and our systems run around the clock.
                                </p>
                                <ul className="space-y-4">
                                    <WhyItem icon={<Zap className="w-4 h-4" />} title="Lightning Fast Processing" desc="Automated API routing completes transactions in under 3 seconds on average." />
                                    <WhyItem icon={<Lock className="w-4 h-4" />} title="Bank-Grade Security" desc="End-to-end encryption protects every transaction and identity record." />
                                    <WhyItem icon={<Clock className="w-4 h-4" />} title="Always Available" desc="99.9% uptime with 24/7 automated monitoring and fail-overs." />
                                </ul>
                            </div>

                            {/* Visual block */}
                            <div className="relative">
                                <div className="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
                                    {/* Fake dashboard UI */}
                                    <div className="bg-slate-800 px-5 py-3 flex items-center gap-2">
                                        <span className="w-3 h-3 rounded-full bg-red-400"></span>
                                        <span className="w-3 h-3 rounded-full bg-yellow-400"></span>
                                        <span className="w-3 h-3 rounded-full bg-green-400"></span>
                                        <span className="ml-4 text-slate-400 text-xs font-mono">/dashboard</span>
                                    </div>
                                    <div className="p-5 space-y-4">
                                        <div className="bg-primary/6 border border-primary/15 rounded-xl p-4">
                                            <p className="text-xs text-slate-500 mb-1 font-medium">Wallet Balance</p>
                                            <p className="text-2xl font-bold text-slate-900">₦ 14,850.00</p>
                                            <p className="text-xs text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                                                <CheckCircle2 className="w-3 h-3" /> Funded & Active
                                            </p>
                                        </div>
                                        <div className="grid grid-cols-2 gap-3">
                                            {['Airtime', 'Data', 'TV', 'Verify'].map(s => (
                                                <div key={s} className="bg-slate-50 border border-slate-100 rounded-lg p-3 flex items-center gap-2">
                                                    <div className="w-7 h-7 rounded-md bg-primary/10 flex items-center justify-center">
                                                        <ChevronRight className="w-3.5 h-3.5 text-primary" />
                                                    </div>
                                                    <span className="text-sm font-semibold text-slate-700">{s}</span>
                                                </div>
                                            ))}
                                        </div>
                                        <div className="space-y-2">
                                            {['Airtime — MTN ₦500', 'Data — Airtel 1GB', 'NIN Verify — Success'].map((tx, i) => (
                                                <div key={i} className="flex items-center justify-between text-xs py-1.5 border-b border-slate-50">
                                                    <span className="text-slate-600 font-medium">{tx}</span>
                                                    <span className="text-emerald-600 font-bold">✓</span>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* ─── HOW IT WORKS ─── */}
                <section id="how-it-works" className="py-20 md:py-32">
                    <div className="max-w-5xl mx-auto px-4 sm:px-6 text-center">
                        <p className="text-xs font-bold tracking-widest text-primary uppercase mb-3">Process</p>
                        <h2 className="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight mb-4">Start in minutes, not days.</h2>
                        <p className="text-lg text-slate-500 max-w-xl mx-auto mb-16">No complicated onboarding. Create an account, fund your wallet, and start transacting.</p>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 text-left">
                            <StepCard n="01" title="Create an Account" desc="Sign up in under 60 seconds with just your email address. No paperwork required." />
                            <StepCard n="02" title="Fund Your Wallet" desc="Get a unique virtual account number. Transfer any amount and your balance updates instantly." />
                            <StepCard n="03" title="Start Transacting" desc="Buy airtime, data, pay bills, or run identity checks — all from one clean dashboard." active />
                        </div>
                    </div>
                </section>

                {/* ─── TESTIMONIALS ─── */}
                <section className="py-20 md:py-28 bg-slate-50 border-y border-slate-100">
                    <div className="max-w-6xl mx-auto px-4 sm:px-6">
                        <div className="text-center mb-12">
                            <p className="text-xs font-bold tracking-widest text-primary uppercase mb-3">Trusted by users</p>
                            <h2 className="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">What our customers say.</h2>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <TestimonialCard
                                quote="I fund my wallet once and handle airtime, data, and TV from one screen. Incredibly fast."
                                name="Fatima A."
                                role="Business Owner"
                                initial="F"
                            />
                            <TestimonialCard
                                quote="The NIN verification was done in seconds. I was skeptical at first but this platform genuinely delivers."
                                name="Emeka O."
                                role="Freelancer"
                                initial="E"
                            />
                            <TestimonialCard
                                quote="Finally a service with zero excuses. My data subscription renews instantly every time."
                                name="Aisha M."
                                role="Student"
                                initial="A"
                            />
                        </div>
                    </div>
                </section>

                {/* ─── CTA ─── */}
                <section className="py-16 md:py-24 bg-slate-900">
                    <div className="max-w-4xl mx-auto px-4 sm:px-6 text-center">
                        <h2 className="text-3xl md:text-4xl font-bold text-white tracking-tight mb-5">
                            Ready to get started?
                        </h2>
                        <p className="text-slate-400 text-lg mb-10 max-w-xl mx-auto">
                            Join {storeName} today. Create your free account and start making instant transactions in under 5 minutes.
                        </p>
                        <div className="flex flex-col sm:flex-row gap-3 justify-center">
                            <Link href="/register" className="w-full sm:w-auto">
                                <span className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-primary text-white font-semibold rounded-lg text-base hover:opacity-90 transition-all shadow-lg shadow-primary/30">
                                    Create Free Account <ArrowRight className="w-4 h-4" />
                                </span>
                            </Link>
                            <Link href="/login" className="w-full sm:w-auto">
                                <span className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 text-slate-300 font-semibold rounded-lg text-base border border-slate-700 hover:bg-slate-800 transition-all">
                                    Already have an account?
                                </span>
                            </Link>
                        </div>
                    </div>
                </section>
            </main>

            {/* ─── FOOTER ─── */}
            <footer className="bg-slate-900 border-t border-slate-800 py-12">
                <div className="max-w-6xl mx-auto px-4 sm:px-6">
                    <div className="flex flex-col md:flex-row items-start justify-between gap-10 mb-10">
                        <div className="max-w-xs">
                            <div className="flex items-center gap-2 mb-4">
                                {store?.logo_url ? (
                                    <img src={store.logo_url} alt={storeName} className="h-8 max-w-[150px] object-contain bg-white rounded p-0.5" />
                                ) : (
                                    <>
                                        <div className="w-7 h-7 rounded-md bg-primary flex items-center justify-center text-white font-bold text-xs">
                                            {storeInitial}
                                        </div>
                                        <span className="font-semibold text-white text-[15px]">{storeName}</span>
                                    </>
                                )}
                            </div>
                            <p className="text-sm text-slate-500 leading-relaxed mb-4">
                                {storeDescription}
                            </p>
                            <div className="flex gap-4">
                                {store?.social_instagram && (
                                    <a href={`https://instagram.com/${store.social_instagram}`} target="_blank" rel="noreferrer" className="text-xs text-slate-500 hover:text-white transition-colors">
                                        Instagram
                                    </a>
                                )}
                                {store?.social_facebook && (
                                    <a href={store.social_facebook} target="_blank" rel="noreferrer" className="text-xs text-slate-500 hover:text-white transition-colors">
                                        Facebook
                                    </a>
                                )}
                                {store?.social_whatsapp && (
                                    <a href={`https://wa.me/${store.social_whatsapp}`} target="_blank" rel="noreferrer" className="text-xs text-slate-500 hover:text-white transition-colors">
                                        WhatsApp
                                    </a>
                                )}
                            </div>
                        </div>
                        <div className="grid grid-cols-2 gap-10 sm:gap-20">
                            <div>
                                <p className="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Platform</p>
                                <ul className="space-y-2.5 text-sm">
                                    <li><a href="#services" className="text-slate-500 hover:text-white transition-colors">Services</a></li>
                                    <li><a href="#how-it-works" className="text-slate-500 hover:text-white transition-colors">How it works</a></li>
                                    <li><Link href="/register" className="text-slate-500 hover:text-white transition-colors">Register</Link></li>
                                    <li><Link href="/login" className="text-slate-500 hover:text-white transition-colors">Sign in</Link></li>
                                </ul>
                            </div>
                            <div>
                                <p className="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Contact & Legal</p>
                                <ul className="space-y-2.5 text-sm">
                                    {store?.contact_email && (
                                        <li>
                                            <a href={`mailto:${store.contact_email}`} className="text-slate-500 hover:text-white transition-colors font-medium">
                                                {store.contact_email}
                                            </a>
                                        </li>
                                    )}
                                    {store?.contact_phone && (
                                        <li>
                                            <span className="text-slate-500">
                                                {store.contact_phone}
                                            </span>
                                        </li>
                                    )}
                                    <li><a href="#" className="text-slate-500 hover:text-white transition-colors">Privacy Policy</a></li>
                                    <li><a href="#" className="text-slate-500 hover:text-white transition-colors">Terms of Service</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div className="pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <p className="text-xs text-slate-600">&copy; {new Date().getFullYear()} {storeName}. All rights reserved.</p>
                        <p className="text-xs text-slate-600">Powered by <a href="https://affanhub.com" className="text-slate-500 hover:text-white transition-colors">AffanHub</a></p>
                    </div>
                </div>
            </footer>
            {/* ─── WHATSAPP SUPPORT BUBBLE ─── */}
            {store?.whatsapp_chat_enabled && store?.whatsapp_chat_phone && (
                <a
                    href={`https://wa.me/${store.whatsapp_chat_phone}?text=${encodeURIComponent(store.whatsapp_chat_message || 'Hello, I have a question.')}`}
                    target="_blank"
                    rel="noreferrer"
                    className={`fixed right-6 z-50 bg-[#25D366] text-white p-3.5 rounded-full shadow-lg hover:scale-110 hover:shadow-xl active:scale-95 transition-all flex items-center justify-center ${
                        store?.tawk_chat_enabled ? 'bottom-24' : 'bottom-6'
                    }`}
                    title="Chat with us on WhatsApp"
                    style={{ boxShadow: '0 4px 14px rgba(37, 211, 102, 0.4)' }}
                >
                    <svg className="w-6 h-6 fill-white" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.458L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.963C16.588 2.02 14.12 1.002 11.5 1.002c-5.442 0-9.87 4.372-9.874 9.802-.001 1.769.471 3.498 1.362 5.031L2.002 21.5l5.727-1.492zm11.233-5.918c-.3-.15-1.771-.875-2.046-.975-.276-.1-.477-.15-.677.15-.2.3-.777.975-.951 1.175-.175.2-.35.225-.65.075-3.04-1.522-4.14-2.522-4.9-3.825-.2-.35-.022-.538.127-.687.135-.135.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525-.075-.15-.677-1.625-.926-2.225-.244-.589-.493-.51-.677-.52l-.578-.01c-.2 0-.525.075-.8.375-.275.3-1.05 1.025-1.05 2.5s1.07 2.9 1.22 3.1c.15.2 2.105 3.213 5.098 4.5 1.205.52 2.146.83 2.875 1.06.73.23 1.396.197 1.92.12.584-.087 1.771-.725 2.021-1.425.25-.7.25-1.3 1.75-1.425-.075-.125-.375-.275-.675-.425z"/>
                    </svg>
                </a>
            )}
        </div>
    );
}

// ─── SUB COMPONENTS ───

function StatBox({ value, label }: { value: string; label: string }) {
    return (
        <div className="text-center py-2 md:py-0">
            <p className="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">{value}</p>
            <p className="text-sm text-slate-500 mt-1">{label}</p>
        </div>
    );
}

function ServiceCard({ icon, title, desc, accent }: { icon: React.ReactNode; title: string; desc: string; accent: 'blue' | 'indigo' }) {
    const accentClass = accent === 'blue'
        ? 'bg-sky-50 text-sky-600 border-sky-100 group-hover:bg-sky-100'
        : 'bg-primary/8 text-primary border-primary/15 group-hover:bg-primary/15';

    return (
        <div className="group bg-white border border-slate-200 rounded-xl p-5 hover:shadow-md hover:border-slate-300 transition-all cursor-default">
            <div className={`w-9 h-9 rounded-lg border flex items-center justify-center mb-4 transition-colors ${accentClass}`}>
                {icon}
            </div>
            <h3 className="font-semibold text-slate-900 text-[15px] mb-1">{title}</h3>
            <p className="text-sm text-slate-500 leading-relaxed">{desc}</p>
        </div>
    );
}

function WhyItem({ icon, title, desc }: { icon: React.ReactNode; title: string; desc: string }) {
    return (
        <li className="flex items-start gap-4">
            <div className="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0 mt-0.5">
                {icon}
            </div>
            <div>
                <p className="font-semibold text-slate-900 mb-0.5">{title}</p>
                <p className="text-sm text-slate-500 leading-relaxed">{desc}</p>
            </div>
        </li>
    );
}

function StepCard({ n, title, desc, active = false }: { n: string; title: string; desc: string; active?: boolean }) {
    return (
        <div className={`rounded-2xl border p-7 transition-all ${active ? 'bg-primary text-white border-primary shadow-lg shadow-primary/20' : 'bg-white border-slate-200 hover:border-slate-300 hover:shadow-sm'}`}>
            <p className={`text-4xl font-black mb-4 tracking-tight ${active ? 'text-white/30' : 'text-slate-100'}`}>{n}</p>
            <h3 className={`font-bold text-lg mb-2 ${active ? 'text-white' : 'text-slate-900'}`}>{title}</h3>
            <p className={`text-sm leading-relaxed ${active ? 'text-white/80' : 'text-slate-500'}`}>{desc}</p>
        </div>
    );
}

function TestimonialCard({ quote, name, role, initial }: { quote: string; name: string; role: string; initial: string }) {
    return (
        <div className="bg-white border border-slate-200 rounded-xl p-6 flex flex-col gap-4">
            <p className="text-slate-600 leading-relaxed text-sm">"{quote}"</p>
            <div className="flex items-center gap-3 mt-auto pt-4 border-t border-slate-100">
                <div className="w-9 h-9 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm shrink-0">
                    {initial}
                </div>
                <div>
                    <p className="font-semibold text-slate-900 text-sm">{name}</p>
                    <p className="text-xs text-slate-500">{role}</p>
                </div>
            </div>
        </div>
    );
}
