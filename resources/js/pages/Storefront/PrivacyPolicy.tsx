import { Head, Link, usePage } from '@inertiajs/react';
import {
    Shield, Lock, FileText, Eye, Share2, UserCheck,
    Server, Mail, Phone, MessageCircle, CheckCircle2,
    ChevronLeft, ExternalLink, Globe, ArrowLeft, Clock,
    HelpCircle, AlertCircle
} from 'lucide-react';

export default function PrivacyPolicy() {
    const { store, auth } = usePage<any>().props;

    const storeName = store?.name || 'Our Store';
    const contactEmail = store?.contact_email || 'support@' + (typeof window !== 'undefined' ? window.location.hostname : 'affanhub.com');
    const contactPhone = store?.contact_phone || null;
    const whatsappPhone = store?.whatsapp_chat_phone || (contactPhone ? contactPhone.replace(/\D/g, '') : null);
    const whatsappUrl = whatsappPhone
        ? `https://wa.me/${whatsappPhone}?text=${encodeURIComponent(`Hello ${storeName}, I have a question regarding your Privacy Policy.`)}`
        : null;

    const backUrl = auth?.user ? '/dashboard' : '/';
    const customPolicy = store?.privacy_policy;

    return (
        <div className="min-h-screen bg-slate-50 dark:bg-[#0f0f17] text-slate-900 dark:text-slate-100 transition-colors">
            <Head title={`Privacy Policy - ${storeName}`} />

            {/* ── STICKY TOP NAVBAR ── */}
            <header className="sticky top-0 z-40 bg-white/90 dark:bg-[#181826]/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 transition-colors">
                <div className="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
                    <Link
                        href={backUrl}
                        className="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary transition-colors group"
                    >
                        <ArrowLeft className="w-4 h-4 transition-transform group-hover:-translate-x-1" />
                        <span>{auth?.user ? 'Back to Dashboard' : 'Back to Store'}</span>
                    </Link>

                    <div className="flex items-center gap-2.5">
                        {store?.logo_url ? (
                            <img src={store.logo_url} alt={storeName} className="h-7 w-auto object-contain rounded" />
                        ) : (
                            <div className="w-7 h-7 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">
                                {storeName.charAt(0)}
                            </div>
                        )}
                        <span className="font-bold text-sm tracking-tight text-slate-800 dark:text-slate-200">
                            {storeName}
                        </span>
                    </div>
                </div>
            </header>

            {/* ── HERO BANNER ── */}
            <section className="border-b border-slate-200 dark:border-slate-800/80 bg-white dark:bg-[#141421] py-12 px-4 transition-colors">
                <div className="max-w-4xl mx-auto text-center space-y-4">
                    <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-semibold tracking-wide uppercase">
                        <Shield className="w-3.5 h-3.5" />
                        <span>Data Protection & Privacy</span>
                    </div>

                    <h1 className="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                        Privacy Policy
                    </h1>

                    <p className="max-w-2xl mx-auto text-sm sm:text-base text-slate-600 dark:text-slate-400 leading-relaxed">
                        At <span className="font-semibold text-slate-900 dark:text-white">{storeName}</span>, your trust and privacy are our highest priorities. This policy outlines how we collect, safeguard, and handle your data in accordance with data protection regulations, including the Nigeria Data Protection Act (NDPA).
                    </p>

                    <div className="inline-flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 font-medium pt-1">
                        <Clock className="w-3.5 h-3.5" />
                        <span>Last Updated: {new Date().toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}</span>
                    </div>
                </div>
            </section>

            {/* ── MAIN CONTENT ── */}
            <main className="max-w-4xl mx-auto px-4 py-10 space-y-8">

                {/* HIGHLIGHT PILLARS */}
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div className="bg-white dark:bg-[#181826] border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 shadow-xs flex items-start gap-3.5">
                        <div className="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                            <Lock className="w-5 h-5" />
                        </div>
                        <div>
                            <h2 className="text-sm font-bold text-slate-900 dark:text-white">Bank-Grade Encryption</h2>
                            <p className="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                                All web traffic and transactions are secured with 256-bit SSL encryption. Passwords and PINs are cryptographically hashed.
                            </p>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-[#181826] border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 shadow-xs flex items-start gap-3.5">
                        <div className="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                            <Eye className="w-5 h-5" />
                        </div>
                        <div>
                            <h2 className="text-sm font-bold text-slate-900 dark:text-white">Zero Data Resale</h2>
                            <p className="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                                We will never sell, rent, or trade your personal or contact details with third-party advertisers or data brokers.
                            </p>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-[#181826] border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 shadow-xs flex items-start gap-3.5">
                        <div className="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                            <Server className="w-5 h-5" />
                        </div>
                        <div>
                            <h2 className="text-sm font-bold text-slate-900 dark:text-white">Purpose-Driven Use</h2>
                            <p className="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                                Your data is processed strictly to deliver your requested telecommunications, utility payments, and verification slips.
                            </p>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-[#181826] border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 shadow-xs flex items-start gap-3.5">
                        <div className="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                            <UserCheck className="w-5 h-5" />
                        </div>
                        <div>
                            <h2 className="text-sm font-bold text-slate-900 dark:text-white">Full User Control</h2>
                            <p className="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                                You have 24/7 access to your transaction records, profile details, and wallet activity directly from your user dashboard.
                            </p>
                        </div>
                    </div>
                </div>

                {/* CUSTOM STORE POLICY NOTICE IF PRESENT */}
                {customPolicy && (
                    <div className="bg-amber-50/70 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/40 rounded-2xl p-6 space-y-3">
                        <div className="flex items-center gap-2 text-amber-700 dark:text-amber-300 font-bold text-sm">
                            <AlertCircle className="w-4 h-4" />
                            <span>Additional Merchant Terms for {storeName}</span>
                        </div>
                        <div className="text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line leading-relaxed">
                            {customPolicy}
                        </div>
                    </div>
                )}

                {/* POLICY SECTIONS */}
                <article className="bg-white dark:bg-[#181826] border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xs space-y-8 divide-y divide-slate-100 dark:divide-slate-800/60">

                    {/* Section 1 */}
                    <div className="space-y-3">
                        <div className="flex items-center gap-2.5 text-primary font-bold text-sm">
                            <FileText className="w-4 h-4" />
                            <span>1. Information We Collect</span>
                        </div>
                        <p className="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            When you register an account, top up your wallet, or purchase telecommunications and utility products through <span className="font-semibold text-slate-900 dark:text-white">{storeName}</span>, we collect the following types of information:
                        </p>
                        <ul className="text-sm text-slate-600 dark:text-slate-300 space-y-2 list-disc list-inside ml-2">
                            <li><strong className="text-slate-800 dark:text-slate-200">Account Credentials:</strong> Full name, verified mobile phone number, email address, and encrypted PIN or password.</li>
                            <li><strong className="text-slate-800 dark:text-slate-200">Transaction History:</strong> Recipient phone numbers for airtime/data top-ups, utility meter/decoder account numbers, purchase amounts, timestamps, and order statuses.</li>
                            <li><strong className="text-slate-800 dark:text-slate-200">Identity Verification Data:</strong> National Identification Numbers (NIN) or Bank Verification Numbers (BVN) provided explicitly by you to generate requested verification slips.</li>
                            <li><strong className="text-slate-800 dark:text-slate-200">Technical Device Data:</strong> IP addresses, browser specifications, and session identifiers collected automatically to maintain session security and prevent account takeover attempts.</li>
                        </ul>
                    </div>

                    {/* Section 2 */}
                    <div className="pt-8 space-y-3">
                        <div className="flex items-center gap-2.5 text-primary font-bold text-sm">
                            <UserCheck className="w-4 h-4" />
                            <span>2. How We Use Your Information</span>
                        </div>
                        <p className="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            We utilize the data collected solely for legitimate business operations and fulfilling services you request:
                        </p>
                        <ul className="text-sm text-slate-600 dark:text-slate-300 space-y-2 list-disc list-inside ml-2">
                            <li>To instantly process and fulfill mobile data subscriptions, airtime recharges, cable TV renewals, and electricity tokens.</li>
                            <li>To maintain an accurate, audit-proof digital ledger of your wallet balance and transactions.</li>
                            <li>To verify your identity upon login and protect your wallet balance using transaction PIN safeguards.</li>
                            <li>To offer customer service, resolve pending or failed network transactions, and initiate automatic wallet refunds.</li>
                            <li>To detect and prevent fraudulent, duplicate, or abusive automated transactions.</li>
                        </ul>
                    </div>

                    {/* Section 3 */}
                    <div className="pt-8 space-y-3">
                        <div className="flex items-center gap-2.5 text-primary font-bold text-sm">
                            <Share2 className="w-4 h-4" />
                            <span>3. Sharing with Authorized Third Parties</span>
                        </div>
                        <p className="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            To deliver telecom and utility services, <span className="font-semibold text-slate-900 dark:text-white">{storeName}</span> securely communicates with licensed infrastructure providers strictly on a need-to-know basis:
                        </p>
                        <ul className="text-sm text-slate-600 dark:text-slate-300 space-y-2 list-disc list-inside ml-2">
                            <li><strong className="text-slate-800 dark:text-slate-200">Telecommunications Networks:</strong> MTN Nigeria, Airtel Nigeria, Globacom (Glo), and 9mobile to deliver data bundles and airtime top-ups to the recipient phone numbers you designate.</li>
                            <li><strong className="text-slate-800 dark:text-slate-200">Licensed Payment Gateways:</strong> CBN-licensed payment institutions and virtual account providers to process your automated wallet deposits. We do not store raw debit card details on our servers.</li>
                            <li><strong className="text-slate-800 dark:text-slate-200">Identity Verification Partners:</strong> Licensed identity gateways (including IDCore) strictly to look up and generate the NIN/BVN slips requested by you.</li>
                        </ul>
                    </div>

                    {/* Section 4 */}
                    <div className="pt-8 space-y-3">
                        <div className="flex items-center gap-2.5 text-primary font-bold text-sm">
                            <Lock className="w-4 h-4" />
                            <span>4. Security Safeguards & Data Storage</span>
                        </div>
                        <p className="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            We employ modern industry security safeguards to protect your personal and financial information:
                        </p>
                        <ul className="text-sm text-slate-600 dark:text-slate-300 space-y-2 list-disc list-inside ml-2">
                            <li>High-grade 256-bit Secure Sockets Layer (SSL) encryption for all browser-to-server communications.</li>
                            <li>Irreversible cryptographic hashing for all login passwords and 4-digit transaction PINs.</li>
                            <li>Automated session expiration on browser close and inactivity protection to prevent unauthorized access on shared devices.</li>
                            <li>Strict database isolation separating store data and customer records.</li>
                        </ul>
                    </div>

                    {/* Section 5 */}
                    <div className="pt-8 space-y-3">
                        <div className="flex items-center gap-2.5 text-primary font-bold text-sm">
                            <Shield className="w-4 h-4" />
                            <span>5. Your Legal Rights</span>
                        </div>
                        <p className="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            In accordance with applicable data privacy laws, you possess the following rights regarding your information:
                        </p>
                        <ul className="text-sm text-slate-600 dark:text-slate-300 space-y-2 list-disc list-inside ml-2">
                            <li><strong className="text-slate-800 dark:text-slate-200">Right of Access:</strong> You can view all past transactions, receipts, and personal details anytime via your dashboard.</li>
                            <li><strong className="text-slate-800 dark:text-slate-200">Right to Rectification:</strong> You can update your contact information or reset your security PIN at any time.</li>
                            <li><strong className="text-slate-800 dark:text-slate-200">Right to Erasure / Deactivation:</strong> You may request the suspension or closure of your account by reaching out to our support channel.</li>
                        </ul>
                    </div>

                    {/* Section 6 */}
                    <div className="pt-8 space-y-3">
                        <div className="flex items-center gap-2.5 text-primary font-bold text-sm">
                            <Mail className="w-4 h-4" />
                            <span>6. Contact Us & Support</span>
                        </div>
                        <p className="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            If you have questions, feedback, or data privacy requests regarding this policy, please reach out to the <span className="font-semibold text-slate-900 dark:text-white">{storeName}</span> team directly:
                        </p>

                        <div className="pt-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            {contactEmail && (
                                <a
                                    href={`mailto:${contactEmail}`}
                                    className="p-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary/50 flex items-center gap-3 transition-colors group"
                                >
                                    <div className="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                        <Mail className="w-4 h-4" />
                                    </div>
                                    <div className="min-w-0">
                                        <p className="text-xs text-slate-500 dark:text-slate-400">Email Address</p>
                                        <p className="text-xs font-bold text-slate-800 dark:text-slate-200 truncate group-hover:text-primary transition-colors">
                                            {contactEmail}
                                        </p>
                                    </div>
                                </a>
                            )}

                            {contactPhone && (
                                <a
                                    href={`tel:${contactPhone}`}
                                    className="p-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary/50 flex items-center gap-3 transition-colors group"
                                >
                                    <div className="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                        <Phone className="w-4 h-4" />
                                    </div>
                                    <div className="min-w-0">
                                        <p className="text-xs text-slate-500 dark:text-slate-400">Customer Helpline</p>
                                        <p className="text-xs font-bold text-slate-800 dark:text-slate-200 truncate group-hover:text-emerald-500 transition-colors">
                                            {contactPhone}
                                        </p>
                                    </div>
                                </a>
                            )}

                            {whatsappUrl && (
                                <a
                                    href={whatsappUrl}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="p-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-emerald-500/50 flex items-center gap-3 transition-colors group col-span-1 sm:col-span-2"
                                >
                                    <div className="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                        <MessageCircle className="w-4 h-4" />
                                    </div>
                                    <div className="min-w-0 flex-1">
                                        <p className="text-xs text-slate-500 dark:text-slate-400">WhatsApp Support</p>
                                        <p className="text-xs font-bold text-slate-800 dark:text-slate-200 truncate group-hover:text-emerald-500 transition-colors">
                                            Chat with {storeName} Support
                                        </p>
                                    </div>
                                    <ExternalLink className="w-3.5 h-3.5 text-slate-400 group-hover:text-emerald-500 transition-colors" />
                                </a>
                            )}
                        </div>
                    </div>
                </article>
            </main>

            {/* ── FOOTER ── */}
            <footer className="border-t border-slate-200 dark:border-slate-800/80 py-8 px-4 text-center text-xs text-slate-500 dark:text-slate-500 transition-colors">
                <div className="max-w-4xl mx-auto space-y-2">
                    <p>&copy; {new Date().getFullYear()} {storeName}. All rights reserved.</p>
                    <p>Powered by <a href="https://affanhub.com" target="_blank" rel="noopener noreferrer" className="hover:text-primary transition-colors">AffanHub</a></p>
                </div>
            </footer>
        </div>
    );
}
