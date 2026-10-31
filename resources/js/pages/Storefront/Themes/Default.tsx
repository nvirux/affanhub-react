import { Head, Link, usePage } from '@inertiajs/react';
import { useState } from 'react';
import './Default.css';

interface StoreData {
    name?: string;
    description?: string;
    contact_email?: string;
    contact_phone?: string;
    contact_phone_2?: string;
    whatsapp_chat_phone?: string;
    social_whatsapp?: string;
    social_instagram?: string;
    social_facebook?: string;
    logo_url?: string | null;
    primary_color?: string;
    landing_page_template?: string;
    [key: string]: any;
}

interface PageProps {
    store?: StoreData;
    auth?: {
        user?: {
            id: number;
            name: string;
            email: string;
            [key: string]: any;
        } | null;
    };
    [key: string]: any;
}

export default function DefaultTheme() {
    const { store, auth } = usePage<PageProps>().props;
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    const storeName = store?.name || 'Your Store';
    const storeLogo = store?.logo_url;
    const storeDesc =
        store?.description ||
        'The smartest way to handle your digital payments, virtual accounts, and corporate onboarding.';

    // Normalizing dynamic contact numbers from store settings (no hardcoded credentials)
    const rawWhatsapp = store?.whatsapp_chat_phone || store?.contact_phone || '';
    const whatsappNum = rawWhatsapp.replace(/\D/g, '');
    const whatsappDisplay = whatsappNum
        ? whatsappNum.startsWith('234')
            ? '0' + whatsappNum.slice(3)
            : whatsappNum.startsWith('0')
              ? whatsappNum
              : '0' + whatsappNum
        : null;

    const contactPhone1 = store?.contact_phone || null;
    const contactPhone2 = store?.contact_phone_2 || null;
    const contactEmail = store?.contact_email || null;
    const whatsappGroup = store?.social_whatsapp || null;
    const currentYear = new Date().getFullYear();
    const isLoggedIn = !!auth?.user;

    return (
        <div className="theme-yushpay min-h-screen bg-white text-slate-600 font-sans antialiased selection:bg-primary selection:text-white">
            <Head title={`${storeName} - Your Ultimate Financial Hub`}>
                <meta name="theme-color" content={store?.primary_color || '#0A4D8C'} />
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link
                    href="https://fonts.bunny.net/css?family=dm-sans:400,500,700|sora:400,600,700,800"
                    rel="stylesheet"
                />
            </Head>

            {/* Navigation */}
            <nav className="border-b border-slate-100 bg-white/90 backdrop-blur-md fixed w-full z-50 transition-all duration-300">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-20">
                        {/* Logo & Store Name */}
                        <Link href="/" className="flex items-center gap-3 group">
                            {storeLogo ? (
                                <img
                                    src={storeLogo}
                                    alt={`${storeName} Logo`}
                                    className="w-10 h-10 rounded-xl object-contain bg-white shadow-lg shadow-primary/30 p-1 border border-slate-100"
                                />
                            ) : (
                                <div className="w-10 h-10 rounded-xl bg-primary text-white font-sora font-bold text-xl flex items-center justify-center shadow-lg shadow-primary/30 transition-transform group-hover:scale-105">
                                    {storeName.charAt(0).toUpperCase()}
                                </div>
                            )}
                            <span className="font-sora font-bold text-2xl tracking-tight text-slate-900 group-hover:text-primary transition-colors">
                                {storeName}
                            </span>
                        </Link>

                        {/* Desktop Links */}
                        <div className="hidden md:flex items-center gap-8 font-medium">
                            <a href="#features" className="text-slate-500 hover:text-primary transition">
                                Features
                            </a>
                            <a href="#how-it-works" className="text-slate-500 hover:text-primary transition">
                                How it Works
                            </a>
                            <a href="#contact" className="text-slate-500 hover:text-primary transition">
                                Contact
                            </a>
                            <a
                                href="#"
                                onClick={(e) => e.preventDefault()}
                                className="text-primary font-bold hover:text-primary-600 transition flex items-center gap-1 cursor-default"
                            >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                    />
                                </svg>
                                Download APK
                            </a>
                        </div>

                        {/* Auth Buttons */}
                        <div className="hidden md:flex items-center gap-4">
                            {isLoggedIn ? (
                                <Link
                                    href="/dashboard"
                                    className="bg-primary hover:bg-primary-600 text-white px-5 py-2.5 rounded-lg font-medium transition shadow-lg shadow-primary/25"
                                >
                                    Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href="/login"
                                        className="text-slate-600 hover:text-primary transition font-medium"
                                    >
                                        Log in
                                    </Link>
                                    <Link
                                        href="/register"
                                        className="bg-primary hover:bg-primary-600 text-white px-5 py-2.5 rounded-lg font-medium transition shadow-lg shadow-primary/25"
                                    >
                                        Get Started
                                    </Link>
                                </>
                            )}
                        </div>

                        {/* Mobile Menu Button */}
                        <div className="md:hidden flex items-center gap-2">
                            <button
                                type="button"
                                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                                className="p-2 text-slate-600 hover:text-slate-900 focus:outline-none"
                                aria-label="Toggle navigation"
                            >
                                {mobileMenuOpen ? (
                                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                ) : (
                                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        />
                                    </svg>
                                )}
                            </button>
                        </div>
                    </div>

                    {/* Mobile Drawer */}
                    {mobileMenuOpen && (
                        <div className="md:hidden py-4 border-t border-slate-100 flex flex-col gap-3 font-medium">
                            <a
                                href="#features"
                                onClick={() => setMobileMenuOpen(false)}
                                className="px-2 py-2 text-slate-600 hover:text-primary transition"
                            >
                                Features
                            </a>
                            <a
                                href="#how-it-works"
                                onClick={() => setMobileMenuOpen(false)}
                                className="px-2 py-2 text-slate-600 hover:text-primary transition"
                            >
                                How it Works
                            </a>
                            <a
                                href="#contact"
                                onClick={() => setMobileMenuOpen(false)}
                                className="px-2 py-2 text-slate-600 hover:text-primary transition"
                            >
                                Contact
                            </a>
                            <a
                                href="#"
                                onClick={(e) => {
                                    e.preventDefault();
                                    setMobileMenuOpen(false);
                                }}
                                className="px-2 py-2 text-primary font-bold flex items-center gap-2 cursor-default"
                            >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                    />
                                </svg>
                                Download APK
                            </a>
                            <div className="pt-2 border-t border-slate-100 flex flex-col gap-2">
                                {isLoggedIn ? (
                                    <Link
                                        href="/dashboard"
                                        className="w-full text-center bg-primary text-white py-2.5 rounded-lg font-medium shadow-md shadow-primary/25"
                                    >
                                        Dashboard
                                    </Link>
                                ) : (
                                    <>
                                        <Link
                                            href="/login"
                                            className="w-full text-center py-2.5 text-slate-700 border border-slate-200 rounded-lg font-medium"
                                        >
                                            Log in
                                        </Link>
                                        <Link
                                            href="/register"
                                            className="w-full text-center bg-primary text-white py-2.5 rounded-lg font-medium shadow-md shadow-primary/25"
                                        >
                                            Get Started
                                        </Link>
                                    </>
                                )}
                            </div>
                        </div>
                    )}
                </div>
            </nav>

            {/* Hero Section */}
            <main className="pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-slate-50 relative">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                    {/* Background decorative shapes */}
                    <div className="absolute top-10 right-10 w-72 h-72 bg-primary/10 rounded-full blur-3xl pointer-events-none" />
                    <div className="absolute bottom-10 left-10 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl pointer-events-none" />

                    <div className="grid lg:grid-cols-2 gap-12 items-center relative z-10">
                        {/* Left text */}
                        <div className="text-center lg:text-left">
                            <h1 className="font-sora text-5xl lg:text-6xl xl:text-7xl font-extrabold text-slate-900 tracking-tight mb-6 leading-tight">
                                The ultimate <span className="gradient-primary-text">financial hub</span>
                            </h1>
                            <p className="text-xl text-slate-600 mb-8 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                                Seamlessly manage data &amp; airtime top-ups, utility bills, bulk SMS, exam pins, and
                                identity verification—all in one powerful platform.
                            </p>
                            <div className="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                                <Link
                                    href="/register"
                                    className="w-full sm:w-auto px-8 py-4 bg-primary hover:bg-primary-600 text-white rounded-xl font-medium text-lg transition shadow-xl shadow-primary/30 flex items-center justify-center gap-2 transform hover:-translate-y-1"
                                >
                                    Create Free Account
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6"
                                        />
                                    </svg>
                                </Link>
                                <a
                                    href="#"
                                    onClick={(e) => e.preventDefault()}
                                    className="w-full sm:w-auto px-8 py-4 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl font-medium text-lg transition flex items-center justify-center shadow-sm hover:shadow-md gap-2 cursor-default"
                                >
                                    <svg
                                        className="w-5 h-5 text-primary"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                        />
                                    </svg>
                                    Download APK
                                </a>
                            </div>

                            <div className="mt-10 flex items-center justify-center lg:justify-start gap-4 text-sm text-slate-500">
                                <div className="flex -space-x-2">
                                    {/* User 1 */}
                                    <div className="w-10 h-10 rounded-full border-2 border-white bg-blue-100 text-blue-500 flex items-center justify-center overflow-hidden">
                                        <svg className="w-6 h-6 mt-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                fillRule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clipRule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    {/* User 2 */}
                                    <div className="w-10 h-10 rounded-full border-2 border-white bg-rose-100 text-rose-500 flex items-center justify-center overflow-hidden">
                                        <svg className="w-6 h-6 mt-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                fillRule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clipRule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    {/* User 3 */}
                                    <div className="w-10 h-10 rounded-full border-2 border-white bg-amber-100 text-amber-500 flex items-center justify-center overflow-hidden">
                                        <svg className="w-6 h-6 mt-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                fillRule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clipRule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    <div className="w-10 h-10 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600 shadow-sm">
                                        +10k
                                    </div>
                                </div>
                                <p>Trusted by merchants nationwide</p>
                            </div>
                        </div>

                        {/* Right Mockup */}
                        <div className="hidden lg:block relative">
                            <div className="absolute inset-0 bg-gradient-to-tr from-primary-100 to-white rounded-3xl transform rotate-3 scale-105 pointer-events-none" />
                            <div className="relative bg-white p-6 rounded-3xl shadow-2xl border border-slate-100">
                                {/* Fake App Dashboard UI */}
                                <div className="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                                    <div className="flex items-center gap-3">
                                        <div className="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-400">
                                            <svg
                                                className="w-5 h-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                />
                                            </svg>
                                        </div>
                                        <div>
                                            <div className="text-sm font-bold text-slate-900">John Doe Ent.</div>
                                            <div className="text-xs text-slate-500">Verified Merchant</div>
                                        </div>
                                    </div>
                                    <div className="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                        Active
                                    </div>
                                </div>

                                <div className="bg-slate-900 text-white p-6 rounded-2xl mb-6 relative overflow-hidden">
                                    <div className="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-primary rounded-full blur-2xl opacity-50" />
                                    <div className="text-sm text-slate-300 mb-1">Available Balance</div>
                                    <div className="text-3xl font-sora font-bold mb-4">₦2,450,000.00</div>
                                    <div className="flex gap-2">
                                        <button className="bg-primary hover:bg-primary-600 flex-1 py-2 rounded-lg text-sm font-medium transition cursor-pointer">
                                            Fund Wallet
                                        </button>
                                        <button className="bg-slate-800 hover:bg-slate-700 flex-1 py-2 rounded-lg text-sm font-medium transition cursor-pointer">
                                            Transfer
                                        </button>
                                    </div>
                                </div>

                                <div className="grid grid-cols-2 gap-4">
                                    <div className="p-4 border border-slate-100 rounded-xl hover:shadow-md transition cursor-pointer">
                                        <div className="w-8 h-8 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center mb-2">
                                            <svg
                                                className="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                                />
                                            </svg>
                                        </div>
                                        <div className="text-sm font-bold text-slate-900">Buy Airtime</div>
                                    </div>
                                    <div className="p-4 border border-slate-100 rounded-xl hover:shadow-md transition cursor-pointer">
                                        <div className="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-2">
                                            <svg
                                                className="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                                />
                                            </svg>
                                        </div>
                                        <div className="text-sm font-bold text-slate-900">Buy Data</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            {/* Stats Section */}
            <section className="py-12 border-b border-slate-100 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-slate-100">
                        <div>
                            <div className="text-4xl font-sora font-extrabold text-slate-900 mb-2">99.9%</div>
                            <div className="text-slate-500 font-medium">Uptime Guarantee</div>
                        </div>
                        <div>
                            <div className="text-4xl font-sora font-extrabold text-slate-900 mb-2">2M+</div>
                            <div className="text-slate-500 font-medium">Transactions Processed</div>
                        </div>
                        <div>
                            <div className="text-4xl font-sora font-extrabold text-slate-900 mb-2">&lt;1s</div>
                            <div className="text-slate-500 font-medium">Average API Latency</div>
                        </div>
                        <div>
                            <div className="text-4xl font-sora font-extrabold text-slate-900 mb-2">24/7</div>
                            <div className="text-slate-500 font-medium">Customer Support</div>
                        </div>
                    </div>
                </div>
            </section>

            {/* How It Works Section */}
            <section id="how-it-works" className="scroll-mt-20 py-16 md:py-24 bg-slate-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12 md:mb-16 px-4">
                        <h2 className="font-sora text-3xl sm:text-4xl md:text-5xl font-bold text-slate-900 mb-4 sm:mb-6">
                            Start transacting in minutes
                        </h2>
                        <p className="text-slate-600 text-base sm:text-lg md:text-xl max-w-3xl mx-auto leading-relaxed">
                            We&apos;ve streamlined the process so you can focus on what matters: growing your business
                            and managing your finances.
                        </p>
                    </div>

                    <div className="grid md:grid-cols-3 gap-12 relative">
                        {/* Connecting line */}
                        <div className="hidden md:block absolute top-12 left-[15%] right-[15%] h-0.5 gradient-step-line" />

                        {/* Step 1 */}
                        <div className="relative text-center">
                            <div className="w-24 h-24 mx-auto bg-white rounded-full border-4 border-slate-50 shadow-xl flex items-center justify-center mb-6 relative z-10">
                                <div className="w-16 h-16 bg-primary-50 rounded-full flex items-center justify-center text-primary font-sora text-2xl font-bold">
                                    1
                                </div>
                            </div>
                            <h3 className="font-sora text-xl font-bold text-slate-900 mb-3">Create an Account</h3>
                            <p className="text-slate-600">
                                Sign up in seconds. All you need is your basic information to get started on{' '}
                                {storeName}.
                            </p>
                        </div>

                        {/* Step 2 */}
                        <div className="relative text-center">
                            <div className="w-24 h-24 mx-auto bg-white rounded-full border-4 border-slate-50 shadow-xl flex items-center justify-center mb-6 relative z-10">
                                <div className="w-16 h-16 bg-primary-50 rounded-full flex items-center justify-center text-primary font-sora text-2xl font-bold">
                                    2
                                </div>
                            </div>
                            <h3 className="font-sora text-xl font-bold text-slate-900 mb-3">Verify Identity</h3>
                            <p className="text-slate-600">
                                Complete our automated KYC process securely using your BVN or NIN for full platform
                                access.
                            </p>
                        </div>

                        {/* Step 3 */}
                        <div className="relative text-center">
                            <div className="w-24 h-24 mx-auto bg-white rounded-full border-4 border-slate-50 shadow-xl flex items-center justify-center mb-6 relative z-10">
                                <div className="w-16 h-16 bg-primary-50 rounded-full flex items-center justify-center text-primary font-sora text-2xl font-bold">
                                    3
                                </div>
                            </div>
                            <h3 className="font-sora text-xl font-bold text-slate-900 mb-3">Start Transacting</h3>
                            <p className="text-slate-600">
                                Fund your wallet via your dedicated virtual account and instantly pay bills or purchase
                                VTU.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* Features Section */}
            <section id="features" className="scroll-mt-20 py-16 md:py-24 bg-slate-50 border-t border-slate-200/60 relative z-10">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12 md:mb-16 px-4">
                        <h2 className="font-sora text-3xl sm:text-4xl md:text-5xl font-bold text-slate-900 mb-4 sm:mb-6">
                            Everything you need, in one place
                        </h2>
                        <p className="text-slate-600 text-base sm:text-lg md:text-xl max-w-3xl mx-auto leading-relaxed">
                            We provide a comprehensive suite of digital services designed to make your daily
                            transactions fast, secure, and incredibly rewarding.
                        </p>
                    </div>

                    <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
                        {/* Feature 1: Airtime & Data */}
                        <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 flex flex-col justify-between transform hover:-translate-y-1 transition duration-300">
                            <div>
                                <div className="w-14 h-14 bg-primary-50 text-primary rounded-2xl flex items-center justify-center mb-6">
                                    <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                        />
                                    </svg>
                                </div>
                                <h3 className="font-sora text-2xl font-bold text-slate-900 mb-3">Airtime &amp; Data</h3>
                                <p className="text-slate-600 leading-relaxed">
                                    Instant top-ups for MTN, Glo, Airtel, and 9mobile. Enjoy ultra-fast delivery and highly
                                    discounted rates on all data bundles.
                                </p>
                            </div>
                        </div>

                        {/* Feature 2: Utilities */}
                        <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 flex flex-col justify-between transform hover:-translate-y-1 transition duration-300">
                            <div>
                                <div className="w-14 h-14 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mb-6">
                                    <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"
                                        />
                                    </svg>
                                </div>
                                <h3 className="font-sora text-2xl font-bold text-slate-900 mb-3">Cable &amp; Electricity</h3>
                                <p className="text-slate-600 leading-relaxed">
                                    Pay IKEDC, EKEDC, KEDCO bills and subscribe to DSTV, GOTV, and StarTimes instantly. Never
                                    miss a moment or stay in the dark.
                                </p>
                            </div>
                        </div>

                        {/* Feature 3: Bulk SMS */}
                        <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 flex flex-col justify-between transform hover:-translate-y-1 transition duration-300">
                            <div>
                                <div className="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mb-6">
                                    <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                                        />
                                    </svg>
                                </div>
                                <h3 className="font-sora text-2xl font-bold text-slate-900 mb-3">Bulk SMS Service</h3>
                                <p className="text-slate-600 leading-relaxed">
                                    Reach thousands instantly. Send customized SMS with your own dedicated Sender ID.
                                    Includes robust DND bypass support.
                                </p>
                            </div>
                        </div>

                        {/* Feature 4: Airtime to Cash */}
                        <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 flex flex-col justify-between transform hover:-translate-y-1 transition duration-300">
                            <div>
                                <div className="w-14 h-14 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center mb-6">
                                    <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"
                                        />
                                    </svg>
                                </div>
                                <h3 className="font-sora text-2xl font-bold text-slate-900 mb-3">Airtime to Cash</h3>
                                <p className="text-slate-600 leading-relaxed">
                                    Made a mistake while recharging? Easily convert your excess airtime back into real cash
                                    deposited straight into your wallet.
                                </p>
                            </div>
                        </div>

                        {/* Feature 5: Exam Pins */}
                        <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 flex flex-col justify-between transform hover:-translate-y-1 transition duration-300">
                            <div>
                                <div className="w-14 h-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mb-6">
                                    <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                        />
                                    </svg>
                                </div>
                                <h3 className="font-sora text-2xl font-bold text-slate-900 mb-3">Exam Pins</h3>
                                <p className="text-slate-600 leading-relaxed">
                                    Instantly generate valid result checker pins and registration tokens for WAEC, NECO,
                                    NABTEB, and JAMB.
                                </p>
                            </div>
                        </div>

                        {/* Feature 6: KYC & Identity */}
                        <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 flex flex-col justify-between transform hover:-translate-y-1 transition duration-300">
                            <div>
                                <div className="w-14 h-14 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center mb-6">
                                    <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                        />
                                    </svg>
                                </div>
                                <h3 className="font-sora text-2xl font-bold text-slate-900 mb-3">Identity &amp; KYC</h3>
                                <p className="text-slate-600 leading-relaxed">
                                    Robust BVN and NIN verification systems ensuring absolute compliance, account security,
                                    and trust for all platform users.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Contact Section */}
            <section id="contact" className="scroll-mt-20 py-16 md:py-24 bg-slate-50 relative overflow-hidden">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div className="text-center mb-12 md:mb-16 px-4">
                        <h2 className="font-sora text-3xl sm:text-4xl md:text-5xl font-bold text-slate-900 mb-4 sm:mb-6">
                            Connect with us
                        </h2>
                        <p className="text-slate-600 text-base sm:text-lg md:text-xl max-w-3xl mx-auto leading-relaxed">
                            Have questions or need support? Reach out to us through our direct communication channels.
                        </p>
                    </div>

                    {/* Contact Channels Grid */}
                    <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-7xl mx-auto">
                        {/* WhatsApp Support */}
                        <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 flex flex-col justify-between transform hover:-translate-y-1 transition duration-300">
                            <div>
                                <div className="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6">
                                    <svg className="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.262 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.456L0 24zm5.835-4.117c1.62.962 3.2 1.488 4.916 1.489 5.482.002 9.943-4.461 9.945-9.947.002-2.657-1.026-5.155-2.897-7.029C16.086 2.522 13.593 1.49 10.932 1.49c-5.485 0-9.946 4.463-9.948 9.949-.001 1.83.499 3.619 1.447 5.233l-1.01 3.687 3.836-.989z" />
                                    </svg>
                                </div>
                                <h3 className="font-sora text-2xl font-bold text-slate-900 mb-3">WhatsApp Support</h3>
                                <p className="text-slate-500 text-sm leading-relaxed mb-6">
                                    Chat directly with our support team for quick resolution of transaction-related
                                    issues or account queries.
                                </p>
                            </div>
                            {whatsappNum ? (
                                <a
                                    href={`https://wa.me/${whatsappNum}?text=${encodeURIComponent(store?.whatsapp_chat_message || 'Hello, I need assistance.')}`}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl text-center transition flex items-center justify-center gap-2"
                                >
                                    Message {whatsappDisplay || whatsappNum}
                                </a>
                            ) : (
                                <span className="w-full py-3 bg-slate-100 text-slate-400 font-bold rounded-xl text-center flex items-center justify-center gap-2 cursor-not-allowed text-sm">
                                    Chat Unavailable
                                </span>
                            )}
                        </div>

                        {/* Phone Support */}
                        <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 flex flex-col justify-between transform hover:-translate-y-1 transition duration-300">
                            <div>
                                <div className="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                                    <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                        />
                                    </svg>
                                </div>
                                <h3 className="font-sora text-2xl font-bold text-slate-900 mb-3">Phone Support</h3>
                                <p className="text-slate-500 text-sm leading-relaxed mb-6">
                                    Speak directly with a representative for immediate assistance. Support lines are
                                    active during work hours.
                                </p>
                            </div>
                            <div className="space-y-2">
                                {contactPhone1 && (
                                    <a
                                        href={`tel:${contactPhone1.replace(/\s+/g, '')}`}
                                        className="w-full py-3 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-xl text-center transition flex items-center justify-center gap-2"
                                    >
                                        Call {contactPhone1}
                                    </a>
                                )}
                                {contactPhone2 && (
                                    <a
                                        href={`tel:${contactPhone2.replace(/\s+/g, '')}`}
                                        className="w-full py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-center transition flex items-center justify-center gap-2"
                                    >
                                        Call {contactPhone2}
                                    </a>
                                )}
                                {!contactPhone1 && !contactPhone2 && (
                                    <span className="w-full py-3 bg-slate-100 text-slate-400 font-bold rounded-xl text-center flex items-center justify-center gap-2 cursor-not-allowed text-sm">
                                        Support Lines Unavailable
                                    </span>
                                )}
                            </div>
                        </div>

                        {/* WhatsApp Group */}
                        <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 flex flex-col justify-between transform hover:-translate-y-1 transition duration-300">
                            <div>
                                <div className="w-14 h-14 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center mb-6">
                                    <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                        />
                                    </svg>
                                </div>
                                <h3 className="font-sora text-2xl font-bold text-slate-900 mb-3">WhatsApp Group</h3>
                                <p className="text-slate-500 text-sm leading-relaxed mb-6">
                                    Join our official community group. Connect with other users, receive general
                                    updates, and access general help.
                                </p>
                            </div>
                            {whatsappGroup ? (
                                <a
                                    href={whatsappGroup}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="w-full py-3 bg-primary hover:bg-primary-600 text-white font-bold rounded-xl text-center transition flex items-center justify-center gap-2"
                                >
                                    Join WhatsApp Group
                                </a>
                            ) : (
                                <span className="w-full py-3 bg-slate-100 text-slate-400 font-bold rounded-xl text-center flex items-center justify-center gap-2 cursor-not-allowed text-sm">
                                    No Group Available
                                </span>
                            )}
                        </div>

                        {/* Email Support */}
                        <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 flex flex-col justify-between transform hover:-translate-y-1 transition duration-300">
                            <div>
                                <div className="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                                    <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        />
                                    </svg>
                                </div>
                                <h3 className="font-sora text-2xl font-bold text-slate-900 mb-3">Email Support</h3>
                                <p className="text-slate-500 text-sm leading-relaxed mb-6">
                                    Send us an email for corporate proposals, general inquiries, or detailed bug
                                    reports.
                                </p>
                            </div>
                            {contactEmail ? (
                                <a
                                    href={`mailto:${contactEmail}`}
                                    className="w-full py-3 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-xl text-center transition flex items-center justify-center gap-2 truncate px-2"
                                >
                                    {contactEmail}
                                </a>
                            ) : (
                                <span className="w-full py-3 bg-slate-100 text-slate-400 font-bold rounded-xl text-center flex items-center justify-center gap-2 cursor-not-allowed text-sm">
                                    Email Unavailable
                                </span>
                            )}
                        </div>
                    </div>
                </div>
            </section>

            {/* CTA Section */}
            <section className="py-24 bg-primary relative overflow-hidden mt-12 lg:m-8 lg:rounded-[3rem]">
                <div className="absolute inset-0 gradient-primary-cta pointer-events-none" />
                {/* Decorative circle */}
                <div className="absolute top-0 right-0 -mr-32 -mt-32 w-[30rem] h-[30rem] bg-white/10 rounded-full blur-3xl pointer-events-none" />
                <div className="absolute bottom-0 left-0 -ml-32 -mb-32 w-[30rem] h-[30rem] bg-blue-900/20 rounded-full blur-3xl pointer-events-none" />

                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                    <h2 className="font-sora text-4xl md:text-6xl font-extrabold text-white mb-6 tracking-tight">
                        Ready to revolutionize your payments?
                    </h2>
                    <p className="text-xl md:text-2xl text-primary-50 mb-12 font-medium">
                        Join thousands of users who trust {storeName} for their daily financial needs.
                    </p>
                    <div className="flex flex-col sm:flex-row justify-center gap-4">
                        <Link
                            href="/register"
                            className="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white hover:bg-slate-50 text-primary rounded-xl font-bold text-lg transition shadow-xl hover:shadow-2xl transform hover:-translate-y-1"
                        >
                            Start Your Journey Today
                            <svg className="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"
                                />
                            </svg>
                        </Link>
                        <a
                            href="#how-it-works"
                            className="inline-flex items-center justify-center gap-2 px-8 py-4 bg-primary-700/50 hover:bg-primary-700 backdrop-blur-sm text-white rounded-xl font-bold text-lg transition border border-primary-400"
                        >
                            Learn More
                        </a>
                    </div>
                </div>
            </section>

            {/* Footer */}
            <footer className="bg-white pt-20 pb-10 border-t border-slate-100">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                        <div className="col-span-1 md:col-span-2">
                            <div className="flex items-center gap-3 mb-6">
                                {storeLogo ? (
                                    <img
                                        src={storeLogo}
                                        alt={`${storeName} Logo`}
                                        className="w-10 h-10 rounded-xl object-contain bg-white shadow-md p-1 border border-slate-100"
                                    />
                                ) : (
                                    <div className="w-10 h-10 rounded-xl bg-primary text-white font-sora font-bold text-xl flex items-center justify-center shadow-md">
                                        {storeName.charAt(0).toUpperCase()}
                                    </div>
                                )}
                                <span className="font-sora font-bold text-2xl text-slate-900">{storeName}</span>
                            </div>
                            <p className="text-slate-500 text-lg max-w-sm leading-relaxed">{storeDesc}</p>
                        </div>
                        <div>
                            <h4 className="font-bold text-slate-900 mb-4 uppercase tracking-wider text-sm">Platform</h4>
                            <ul className="space-y-3 text-slate-500">
                                <li>
                                    <a href="#features" className="hover:text-primary transition">
                                        Features
                                    </a>
                                </li>
                                <li>
                                    <a href="#how-it-works" className="hover:text-primary transition">
                                        How it works
                                    </a>
                                </li>
                                <li>
                                    <a href="#pricing" className="hover:text-primary transition">
                                        Pricing
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="font-bold text-slate-900 mb-4 uppercase tracking-wider text-sm">Company</h4>
                            <ul className="space-y-3 text-slate-500">
                                <li>
                                    <a href="#about" className="hover:text-primary transition">
                                        About Us
                                    </a>
                                </li>
                                <li>
                                    <a href="#contact" className="hover:text-primary transition">
                                        Contact
                                    </a>
                                </li>
                                <li>
                                    <Link href="/privacy" className="hover:text-primary transition">
                                        Privacy Policy
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/delete-account" className="hover:text-primary transition">
                                        Delete Account
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/terms" className="hover:text-primary transition">
                                        Terms of Service
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div className="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
                        <div className="text-slate-400 text-sm">
                            <p className="mb-1">
                                &copy; {currentYear} {storeName}. All rights reserved.
                            </p>
                            <p>
                                Developed by{' '}
                                <a
                                    href="https://netvirux.dev/"
                                    target="_blank"
                                    rel="noreferrer"
                                    className="text-primary hover:text-primary-700 font-medium transition"
                                >
                                    Umar Net Viruz
                                </a>
                            </p>
                        </div>
                        <div className="flex gap-4">
                            {/* Twitter / X */}
                            <a
                                href={store?.social_instagram || '#'}
                                target="_blank"
                                rel="noreferrer"
                                className="w-10 h-10 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition"
                                aria-label="Social Link"
                            >
                                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                                </svg>
                            </a>
                            {/* Facebook */}
                            <a
                                href={store?.social_facebook || '#'}
                                target="_blank"
                                rel="noreferrer"
                                className="w-10 h-10 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition"
                                aria-label="Facebook Link"
                            >
                                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3 8h-1.35c-.538 0-.65.221-.65.778v1.222h2l-.209 2h-1.791v7h-3v-7h-2v-2h2v-2.308c0-1.769.931-2.692 3.029-2.692h1.971v3z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    );
}
