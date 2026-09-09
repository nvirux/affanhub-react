import { useState, useEffect } from 'react';
import { Head, usePage, Link } from '@inertiajs/react';
import {
    ChevronLeft, Headphones, MessageSquare, Phone, Mail,
    ExternalLink, CheckCircle2, Clock, ShieldCheck, Sparkles,
    ArrowUpRight
} from 'lucide-react';
import { dashboard } from '@/routes';

export default function Contact() {
    const { store } = usePage<any>().props;
    const [tawkLoaded, setTawkLoaded] = useState(false);
    const [isChatOpening, setIsChatOpening] = useState(false);

    // Initialize Tawk.to if configured for this store/tenant
    useEffect(() => {
        if (store?.tawk_chat_enabled && store?.tawk_property_id && store?.tawk_widget_id) {
            // Check if Tawk is already injected
            if ((window as any).Tawk_API) {
                setTawkLoaded(true);
                try {
                    (window as any).Tawk_API.showWidget?.();
                } catch (e) {}
                return;
            }

            const s1 = document.createElement('script');
            const s0 = document.getElementsByTagName('script')[0];
            s1.async = true;
            s1.src = `https://embed.tawk.to/${store.tawk_property_id}/${store.tawk_widget_id}`;
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s1.onload = () => {
                setTawkLoaded(true);
            };
            s0?.parentNode?.insertBefore(s1, s0);

            return () => {
                if ((window as any).Tawk_API) {
                    try {
                        (window as any).Tawk_API.hideWidget?.();
                    } catch (e) {}
                }
            };
        }
    }, [store]);

    const handleOpenTawk = () => {
        setIsChatOpening(true);
        if ((window as any).Tawk_API?.maximize) {
            (window as any).Tawk_API.maximize();
            setIsChatOpening(false);
        } else if ((window as any).Tawk_API?.toggle) {
            (window as any).Tawk_API.toggle();
            setIsChatOpening(false);
        } else {
            // Wait brief moment for Tawk script to initialize
            setTimeout(() => {
                if ((window as any).Tawk_API?.maximize) {
                    (window as any).Tawk_API.maximize();
                }
                setIsChatOpening(false);
            }, 1000);
        }
    };

    const storeName = store?.name || 'Customer Support';
    const whatsappPhone = store?.whatsapp_chat_phone || store?.contact_phone?.replace(/\D/g, '');
    const whatsappMessage = encodeURIComponent(
        store?.whatsapp_chat_message || `Hello ${storeName}, I need assistance with my account.`
    );
    const whatsappUrl = whatsappPhone ? `https://wa.me/${whatsappPhone}?text=${whatsappMessage}` : null;

    return (
        <>
            <Head title={`Support & Contact - ${storeName}`} />

            {/* ── MOBILE VIEW STICKY HEADER (flex md:hidden) ── */}
            <div className="sticky top-0 z-30 bg-white/95 dark:bg-[#181826]/95 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 py-3 px-4 flex md:hidden items-center justify-between shadow-2xs">
                <div className="flex items-center gap-3 min-w-0">
                    <Link
                        href={dashboard().url}
                        className="p-1 text-gray-700 dark:text-gray-200 hover:text-primary transition-colors cursor-pointer shrink-0"
                    >
                        <ChevronLeft className="w-6 h-6 stroke-[2.2]" />
                    </Link>
                    <div className="min-w-0">
                        <h1 className="font-extrabold text-base text-gray-900 dark:text-white tracking-tight leading-tight">
                            Help & Support
                        </h1>
                        <p className="text-xs text-slate-400 dark:text-slate-400 font-normal truncate mt-0.5">
                            We're here to help you 24/7
                        </p>
                    </div>
                </div>

                <div className="flex items-center gap-2 shrink-0 ml-2">
                    <div className="w-8 h-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <Headphones className="w-4 h-4 stroke-[2]" />
                    </div>
                </div>
            </div>

            {/* ── MAIN CONTENT CONTAINER ── */}
            <div className="max-w-xl mx-auto px-4 py-4 md:py-6 w-full space-y-4">

                {/* 1. Support Hero Card */}
                <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-xs text-center space-y-2">
                    <div className="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mx-auto mb-1">
                        <Headphones className="w-6 h-6 stroke-[1.8]" />
                    </div>
                    <div className="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[11px] font-bold">
                        <span className="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" />
                        Online Support
                    </div>
                    <h2 className="text-base font-extrabold text-gray-900 dark:text-white tracking-tight">
                        How can we help you today?
                    </h2>
                    <p className="text-xs text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                        Need quick help with your airtime, data purchase, or wallet deposit? Choose your preferred channel below.
                    </p>
                </div>

                {/* 2. Contact & Live Chat Options */}
                <div className="space-y-3">

                    {/* Tawk.to Live Chat Card (If enabled) */}
                    {store?.tawk_chat_enabled && store?.tawk_property_id && store?.tawk_widget_id && (
                        <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center justify-between gap-4">
                            <div className="flex items-center gap-3.5 min-w-0">
                                <div className="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                    <MessageSquare className="w-5 h-5 stroke-[1.8]" />
                                </div>
                                <div className="min-w-0">
                                    <div className="flex items-center gap-2">
                                        <h3 className="text-sm font-bold text-gray-900 dark:text-white">
                                            Real-Time Live Chat
                                        </h3>
                                        <span className="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500 text-white">
                                            Instant
                                        </span>
                                    </div>
                                    <p className="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                                        Chat directly with our support team on-screen
                                    </p>
                                </div>
                            </div>

                            <button
                                type="button"
                                onClick={handleOpenTawk}
                                disabled={isChatOpening}
                                className="px-4 py-2 rounded-xl bg-primary text-white text-xs font-bold hover:opacity-90 active:scale-95 transition-all shrink-0 cursor-pointer shadow-xs flex items-center gap-1.5"
                            >
                                <span>{isChatOpening ? 'Opening...' : 'Start Chat'}</span>
                                <ArrowUpRight className="w-3.5 h-3.5" />
                            </button>
                        </div>
                    )}

                    {/* WhatsApp Chat Card */}
                    {store?.whatsapp_chat_enabled && whatsappUrl && (
                        <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center justify-between gap-4">
                            <div className="flex items-center gap-3.5 min-w-0">
                                <div className="w-11 h-11 rounded-xl bg-[#25D366]/10 text-[#25D366] flex items-center justify-center shrink-0">
                                    <MessageSquare className="w-5 h-5 stroke-[1.8]" />
                                </div>
                                <div className="min-w-0">
                                    <h3 className="text-sm font-bold text-gray-900 dark:text-white">
                                        WhatsApp Support
                                    </h3>
                                    <p className="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                                        Fast response via WhatsApp messenger
                                    </p>
                                </div>
                            </div>

                            <a
                                href={whatsappUrl}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="px-4 py-2 rounded-xl bg-[#25D366] hover:bg-[#20ba59] text-white text-xs font-bold active:scale-95 transition-all shrink-0 cursor-pointer shadow-xs flex items-center gap-1.5"
                            >
                                <span>Chat Now</span>
                                <ExternalLink className="w-3.5 h-3.5" />
                            </a>
                        </div>
                    )}

                    {/* Phone Support Card */}
                    {store?.contact_phone && (
                        <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center justify-between gap-4">
                            <div className="flex items-center gap-3.5 min-w-0">
                                <div className="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                    <Phone className="w-5 h-5 stroke-[1.8]" />
                                </div>
                                <div className="min-w-0">
                                    <h3 className="text-sm font-bold text-gray-900 dark:text-white">
                                        Customer Care Line
                                    </h3>
                                    <p className="text-xs font-mono font-medium text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                                        {store.contact_phone}
                                    </p>
                                </div>
                            </div>

                            <a
                                href={`tel:${store.contact_phone}`}
                                className="px-4 py-2 rounded-xl bg-slate-100 dark:bg-zinc-800 text-gray-800 dark:text-gray-200 text-xs font-bold hover:bg-slate-200 dark:hover:bg-zinc-700 active:scale-95 transition-all shrink-0 cursor-pointer shadow-xs"
                            >
                                Call
                            </a>
                        </div>
                    )}

                    {/* Email Support Card */}
                    {store?.contact_email && (
                        <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center justify-between gap-4">
                            <div className="flex items-center gap-3.5 min-w-0">
                                <div className="w-11 h-11 rounded-xl bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                                    <Mail className="w-5 h-5 stroke-[1.8]" />
                                </div>
                                <div className="min-w-0">
                                    <h3 className="text-sm font-bold text-gray-900 dark:text-white">
                                        Email Helpdesk
                                    </h3>
                                    <p className="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                                        {store.contact_email}
                                    </p>
                                </div>
                            </div>

                            <a
                                href={`mailto:${store.contact_email}?subject=${encodeURIComponent('Customer Support Inquiry')}`}
                                className="px-4 py-2 rounded-xl bg-slate-100 dark:bg-zinc-800 text-gray-800 dark:text-gray-200 text-xs font-bold hover:bg-slate-200 dark:hover:bg-zinc-700 active:scale-95 transition-all shrink-0 cursor-pointer shadow-xs"
                            >
                                Email
                            </a>
                        </div>
                    )}

                    {/* Fallback info when merchant hasn't filled custom contacts yet */}
                    {!store?.tawk_chat_enabled && !whatsappUrl && !store?.contact_phone && !store?.contact_email && (
                        <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-xs text-center space-y-2">
                            <Headphones className="w-8 h-8 text-primary mx-auto" />
                            <h3 className="text-sm font-bold text-gray-900 dark:text-white">
                                Automated Customer Support
                            </h3>
                            <p className="text-xs text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                                All airtime, data, and wallet transactions are processed automatically in real-time. For order issues, please reach out to your administrator.
                            </p>
                        </div>
                    )}
                </div>

                {/* 3. Assurance & Operating Info Card */}
                <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 shadow-xs flex items-center gap-3">
                    <div className="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                        <Clock className="w-5 h-5 stroke-[1.8]" />
                    </div>
                    <div className="min-w-0 text-xs">
                        <p className="font-bold text-gray-900 dark:text-white">
                            24/7 Automated Infrastructure
                        </p>
                        <p className="text-gray-500 dark:text-gray-400 mt-0.5">
                            Wallet funding and recharge services run 24 hours a day, 7 days a week.
                        </p>
                    </div>
                </div>

            </div>
        </>
    );
}
