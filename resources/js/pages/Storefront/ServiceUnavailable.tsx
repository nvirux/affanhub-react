import React from 'react';
import { Head, Link } from '@inertiajs/react';
import {
    ChevronLeft,
    Headphones,
    MessageCircle,
    LayoutGrid,
    Lock,
    ArrowLeft,
    ArrowUpRight,
} from 'lucide-react';
import { dashboard } from '@/routes';
import cacIcon from '@/assets/icons/cac.png';
import nimcIcon from '@/assets/icons/nimc.png';
import nibssIcon from '@/assets/icons/nibss.png';
import verificationIcon from '@/assets/icons/verification.png';

interface ServiceUnavailableProps {
    serviceName?: string;
    serviceKey?: string;
    category?: string;
    message?: string;
    store?: {
        name?: string;
        contact_email?: string | null;
        contact_phone?: string | null;
        contact_phone_2?: string | null;
        whatsapp_chat_phone?: string | null;
        social_whatsapp?: string | null;
        primary_color?: string;
    };
}

export default function ServiceUnavailable({
    serviceName = 'Service',
    serviceKey = '',
    category = 'Identity',
    message = 'This service is currently not active. Please contact support for assistance.',
    store,
}: ServiceUnavailableProps) {
    const storeName = store?.name || 'Store';

    // Format WhatsApp Link
    const rawWhatsapp = store?.whatsapp_chat_phone || store?.contact_phone || '';
    const cleanWhatsapp = rawWhatsapp.replace(/\D/g, '');
    const whatsappNumber = cleanWhatsapp.startsWith('0')
        ? '234' + cleanWhatsapp.slice(1)
        : cleanWhatsapp;

    const whatsappUrl = store?.social_whatsapp?.startsWith('http')
        ? store.social_whatsapp
        : whatsappNumber
          ? `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(
                `Hello ${storeName} Support, I noticed ${serviceName} is currently unavailable. Could you please assist me?`
            )}`
          : null;

    // Resolve an appropriate brand icon
    const renderEmblem = () => {
        const key = (serviceKey || '').toLowerCase();
        if (key.includes('cac')) {
            return <img src={cacIcon} alt="CAC" className="w-10 h-10 object-contain" />;
        }
        if (key.includes('nin')) {
            return <img src={nimcIcon} alt="NIMC" className="w-10 h-10 object-contain" />;
        }
        if (key.includes('bvn')) {
            return <img src={nibssIcon} alt="NIBSS" className="w-10 h-10 object-contain" />;
        }
        if (key.includes('verification') || key.includes('slip') || key.includes('modification')) {
            return <img src={verificationIcon} alt="Verification" className="w-10 h-10 object-contain" />;
        }

        return <Lock className="w-7 h-7 text-amber-500" />;
    };

    return (
        <div className="min-h-screen bg-[#f8fafc] dark:bg-[#12121e] text-slate-900 dark:text-slate-100 flex flex-col justify-between antialiased selection:bg-primary selection:text-white">
            <Head title={`${serviceName} - ${storeName}`} />

            {/* ── STICKY TOP HEADER (Identical to Nin.tsx / Services.tsx / Contact.tsx) ── */}
            <header className="sticky top-0 z-30 bg-white/95 dark:bg-[#181826]/95 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 py-3 px-4 flex items-center justify-between shadow-2xs">
                <div className="flex items-center gap-3 min-w-0">
                    <Link
                        href="/services"
                        className="p-1 text-gray-700 dark:text-gray-200 hover:text-primary transition-colors cursor-pointer shrink-0"
                        title="Back to Services"
                    >
                        <ChevronLeft className="w-6 h-6 stroke-[2.2]" />
                    </Link>
                    <div className="min-w-0">
                        <h1 className="font-extrabold text-base text-gray-900 dark:text-white tracking-tight leading-tight truncate">
                            {serviceName}
                        </h1>
                        <p className="text-xs text-slate-400 dark:text-slate-400 font-normal truncate mt-0.5">
                            {category ? `${category} Service • ` : ''}Currently Inactive
                        </p>
                    </div>
                </div>

                <Link
                    href="/contact"
                    className="w-8 h-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center hover:bg-primary/20 transition-colors shrink-0"
                    title="Customer Support"
                >
                    <Headphones className="w-4 h-4 stroke-[2]" />
                </Link>
            </header>

            {/* ── MAIN CONTENT CONTAINER (Top-aligned, proper mobile padding) ── */}
            <main className="max-w-xl mx-auto px-4 py-6 md:py-8 w-full flex-1 pb-28 space-y-4">
                <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 sm:p-8 text-center shadow-xs space-y-4 relative overflow-hidden">
                    {/* Top ambient glow */}
                    <div className="absolute -top-16 left-1/2 -translate-x-1/2 w-40 h-40 bg-amber-500/10 dark:bg-amber-400/10 rounded-full blur-2xl pointer-events-none" />

                    {/* Service Emblem with Lock Badge */}
                    <div className="relative inline-flex items-center justify-center mx-auto">
                        <div className="w-16 h-16 rounded-2xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80 p-2.5 flex items-center justify-center shadow-xs">
                            {renderEmblem()}
                        </div>
                        <div
                            className="absolute -bottom-1.5 -right-1.5 w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center shadow-sm border-2 border-white dark:border-[#181826]"
                            title="Unavailable"
                        >
                            <Lock className="w-3 h-3 stroke-[2.5]" />
                        </div>
                    </div>

                    {/* Status Pill */}
                    <div>
                        <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 text-xs font-bold border border-amber-500/20">
                            <span className="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse" />
                            <span>Service Not Active</span>
                        </div>
                    </div>

                    {/* Headline */}
                    <div className="space-y-1.5">
                        <h2 className="text-lg sm:text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            {serviceName}
                        </h2>
                        <p className="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed max-w-sm mx-auto">
                            {message}
                        </p>
                    </div>

                    {/* Primary & Secondary Action Buttons */}
                    <div className="pt-2 flex flex-col gap-2.5 max-w-sm mx-auto w-full">
                        <Link
                            href="/services"
                            className="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-primary text-white text-xs sm:text-sm font-bold hover:bg-primary/90 transition-all shadow-xs cursor-pointer"
                        >
                            <LayoutGrid className="w-4 h-4" />
                            <span>Explore Other Services</span>
                        </Link>

                        <Link
                            href="/contact"
                            className="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 text-xs sm:text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-700 transition-all cursor-pointer"
                        >
                            <Headphones className="w-4 h-4" />
                            <span>Contact Store Support</span>
                        </Link>
                    </div>

                    {/* Optional Direct WhatsApp Link */}
                    {whatsappUrl && (
                        <div className="pt-1">
                            <a
                                href={whatsappUrl}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="inline-flex items-center justify-center gap-1.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline"
                            >
                                <MessageCircle className="w-3.5 h-3.5 fill-current" />
                                <span>Chat with {storeName} on WhatsApp</span>
                                <ArrowUpRight className="w-3 h-3 opacity-60" />
                            </a>
                        </div>
                    )}
                </div>

                {/* Secondary navigation shortcut */}
                <div className="text-center pt-2">
                    <Link
                        href={dashboard().url}
                        className="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors"
                    >
                        <ArrowLeft className="w-3.5 h-3.5" />
                        <span>Return to Dashboard</span>
                    </Link>
                </div>
            </main>
        </div>
    );
}
