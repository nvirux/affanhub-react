import { Link, usePage } from '@inertiajs/react';
import { ArrowRight, Menu, X } from 'lucide-react';
import { ReactNode, useState } from 'react';

const NAV_LINKS = [
    { label: 'Features', href: '/#features' },
    { label: 'How it works', href: '/#how-it-works' },
    { label: 'Pricing', href: '/#pricing' },
    { label: 'FAQs', href: '/#faqs' },
    { label: 'Blog', href: '/blog' },
    { label: 'Live Demo', href: 'https://demo.affanhub.com', external: true },
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

interface MarketingLayoutProps {
    children: ReactNode;
}

export default function MarketingLayout({ children }: MarketingLayoutProps) {
    const { merchant_url } = usePage<any>().props;
    const [mobileOpen, setMobileOpen] = useState(false);
    const getMerchantUrl = (path = '') => resolveMerchantUrl(merchant_url, path);

    return (
        <div className="min-h-screen bg-white text-slate-900 flex flex-col font-sans" style={{ fontFamily: "'Inter', system-ui, sans-serif" }}>
            {/* TOP NAVIGATION */}
            <header className="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur-sm border-b border-slate-200/80">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                    <Link href="/" className="flex items-center shrink-0 group">
                        <img src="/images/affan-logo.png" alt="AffanHub" className="h-8 sm:h-9 w-auto object-contain" />
                    </Link>

                    <nav className="hidden md:flex items-center gap-1">
                        {NAV_LINKS.map(l => (
                            l.external ? (
                                <a
                                    key={l.label}
                                    href={l.href}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="px-3 py-1.5 text-sm text-slate-600 hover:text-slate-900 rounded-md hover:bg-slate-50 transition-all"
                                >
                                    {l.label}
                                </a>
                            ) : (
                                <Link
                                    key={l.label}
                                    href={l.href}
                                    className="px-3 py-1.5 text-sm text-slate-600 hover:text-slate-900 rounded-md hover:bg-slate-50 transition-all"
                                >
                                    {l.label}
                                </Link>
                            )
                        ))}
                    </nav>

                    <div className="hidden md:flex items-center gap-3">
                        <a
                            href={getMerchantUrl('/login')}
                            className="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors"
                        >
                            Sign in
                        </a>
                        <a
                            href={getMerchantUrl('/register')}
                            className="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:opacity-90 transition-all shadow-sm shadow-primary/20"
                        >
                            Launch My Store <ArrowRight className="w-3.5 h-3.5" />
                        </a>
                    </div>

                    <button
                        className="md:hidden p-2 text-slate-500 rounded-lg hover:bg-slate-100"
                        onClick={() => setMobileOpen(!mobileOpen)}
                        aria-label="Toggle Navigation"
                    >
                        {mobileOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
                    </button>
                </div>

                {mobileOpen && (
                    <div className="md:hidden bg-white border-t border-slate-100 px-4 py-4 space-y-1 shadow-lg">
                        {NAV_LINKS.map(l => (
                            l.external ? (
                                <a
                                    key={l.label}
                                    href={l.href}
                                    target="_blank"
                                    rel="noreferrer"
                                    onClick={() => setMobileOpen(false)}
                                    className="block px-3 py-2 text-sm text-slate-600 rounded-md hover:bg-slate-50"
                                >
                                    {l.label}
                                </a>
                            ) : (
                                <Link
                                    key={l.label}
                                    href={l.href}
                                    onClick={() => setMobileOpen(false)}
                                    className="block px-3 py-2 text-sm text-slate-600 rounded-md hover:bg-slate-50"
                                >
                                    {l.label}
                                </Link>
                            )
                        ))}
                        <div className="pt-3 border-t border-slate-100 flex flex-col gap-2">
                            <a
                                href={getMerchantUrl('/login')}
                                className="block px-3 py-2 text-sm font-medium text-slate-600 text-center"
                            >
                                Sign in
                            </a>
                            <a
                                href={getMerchantUrl('/register')}
                                className="block px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg text-center"
                            >
                                Launch My Store
                            </a>
                        </div>
                    </div>
                )}
            </header>

            <main className="flex-1 pt-16">
                {children}
            </main>

            {/* FOOTER */}
            <footer className="bg-slate-950 text-white border-t border-slate-900 mt-20">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 py-14">
                    <div className="flex flex-col md:flex-row justify-between gap-10 mb-12">
                        <div className="max-w-sm">
                            <Link href="/" className="inline-block mb-4">
                                <div className="bg-white rounded-lg px-2.5 py-1.5 inline-block shadow-sm">
                                    <img src="/images/affan-logo.png" alt="AffanHub" className="h-7 w-auto object-contain" />
                                </div>
                            </Link>
                            <p className="text-sm text-slate-400 leading-relaxed">
                                The all-in-one white-label platform to launch your own automated VTU, telecom, and digital services business in minutes.
                            </p>
                        </div>

                        <div className="grid grid-cols-2 sm:grid-cols-3 gap-8 sm:gap-14">
                            <div>
                                <p className="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Platform</p>
                                <ul className="space-y-2.5 text-sm">
                                    <li><Link href="/#features" className="text-slate-400 hover:text-white transition-colors">Features</Link></li>
                                    <li><Link href="/#how-it-works" className="text-slate-400 hover:text-white transition-colors">How it works</Link></li>
                                    <li><Link href="/#pricing" className="text-slate-400 hover:text-white transition-colors">Pricing</Link></li>
                                    <li><a href="https://demo.affanhub.com" target="_blank" rel="noreferrer" className="text-slate-400 hover:text-white transition-colors">Live Demo</a></li>
                                </ul>
                            </div>
                            <div>
                                <p className="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Resources</p>
                                <ul className="space-y-2.5 text-sm">
                                    <li><Link href="/blog" className="text-slate-400 hover:text-white transition-colors">Blog & Guides</Link></li>
                                    <li><Link href="/#faqs" className="text-slate-400 hover:text-white transition-colors">FAQs</Link></li>
                                    <li><a href="mailto:support@affanhub.com" className="text-slate-400 hover:text-white transition-colors">Support</a></li>
                                </ul>
                            </div>
                            <div>
                                <p className="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Company</p>
                                <ul className="space-y-2.5 text-sm">
                                    <li><a href={getMerchantUrl('/login')} className="text-slate-400 hover:text-white transition-colors">Sign in</a></li>
                                    <li><a href={getMerchantUrl('/register')} className="text-slate-400 hover:text-white transition-colors">Create Account</a></li>
                                    <li><Link href="/privacy" className="text-slate-400 hover:text-white transition-colors">Privacy Policy</Link></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div className="pt-8 border-t border-slate-900 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                        <p>&copy; {new Date().getFullYear()} AffanHub. All rights reserved.</p>
                        <p>Built for ambitious Nigerian entrepreneurs.</p>
                    </div>
                </div>
            </footer>
        </div>
    );
}
