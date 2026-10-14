import React, { useState, useEffect } from 'react';
import { Link } from '@inertiajs/react';
import {
    Info,
    AlertTriangle,
    AlertOctagon,
    Sparkles,
    X,
    ArrowRight,
    CheckCircle2,
    Megaphone,
} from 'lucide-react';
import { cn } from '@/lib/utils';

export interface StoreAnnouncement {
    id?: number | string;
    title?: string;
    text: string;
    type?: 'info' | 'warning' | 'danger' | 'promo';
    style?: 'modal' | 'banner' | 'ticker';
    action_label?: string | null;
    action_url?: string | null;
    dismissible?: boolean;
}

interface AnnouncementBarProps {
    announcement?: StoreAnnouncement | null;
    className?: string;
}

export default function AnnouncementBar({ announcement, className }: AnnouncementBarProps) {
    if (!announcement || !announcement.text?.trim()) {
        return null;
    }

    const {
        id = 'current',
        title,
        text,
        type = 'info',
        style = 'modal',
        action_label,
        action_url,
        dismissible = true,
    } = announcement;

    const [isOpen, setIsOpen] = useState(false);
    const [dontShowAgain, setDontShowAgain] = useState(true);

    // Unique storage key based on announcement ID or message hash
    const storageKey = `dismissed_announcement_${id}_${text.trim().substring(0, 16)}`;

    useEffect(() => {
        const isDismissed = localStorage.getItem(storageKey);
        if (!isDismissed) {
            setIsOpen(true);
        }
    }, [storageKey]);

    const handleDismiss = () => {
        if (dontShowAgain || style === 'modal') {
            localStorage.setItem(storageKey, 'true');
        }
        setIsOpen(false);
    };

    if (!isOpen) {
        return null;
    }

    // Mood configuration with ultra-clean, minimal palettes and icons
    const moodConfig = {
        info: {
            badge: 'Store Update',
            badgeClass: 'bg-sky-50 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300 border-sky-200/80 dark:border-sky-800/50',
            iconBox: 'bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-400/20',
            buttonClass: 'bg-sky-600 hover:bg-sky-700 text-white shadow-sm shadow-sky-600/20',
            pillBg: 'bg-sky-50/90 dark:bg-sky-950/30 border-sky-200/80 dark:border-sky-800/40 text-sky-950 dark:text-sky-100',
            pillIconBox: 'w-6 h-6 rounded-full flex items-center justify-center bg-sky-500/15 text-sky-600 dark:text-sky-400 shrink-0',
            actionText: 'text-[11px] font-bold text-sky-600 dark:text-sky-400 hover:text-sky-700 flex items-center gap-0.5 underline underline-offset-2 shrink-0',
            icon: <Info className="w-5 h-5 stroke-[2]" />,
            smallIcon: <Info className="w-3.5 h-3.5 stroke-[2]" />,
        },
        warning: {
            badge: 'Provider Notice',
            badgeClass: 'bg-amber-50 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border-amber-200/80 dark:border-amber-800/50',
            iconBox: 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-400/20',
            buttonClass: 'bg-amber-600 hover:bg-amber-700 text-white shadow-sm shadow-amber-600/20',
            pillBg: 'bg-amber-50/90 dark:bg-amber-950/30 border-amber-200/80 dark:border-amber-800/40 text-amber-950 dark:text-amber-100',
            pillIconBox: 'w-6 h-6 rounded-full flex items-center justify-center bg-amber-500/15 text-amber-600 dark:text-amber-400 shrink-0',
            actionText: 'text-[11px] font-bold text-amber-600 dark:text-amber-400 hover:text-amber-700 flex items-center gap-0.5 underline underline-offset-2 shrink-0',
            icon: <AlertTriangle className="w-5 h-5 stroke-[2]" />,
            smallIcon: <AlertTriangle className="w-3.5 h-3.5 stroke-[2]" />,
        },
        danger: {
            badge: 'Service Alert',
            badgeClass: 'bg-rose-50 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border-rose-200/80 dark:border-rose-800/50',
            iconBox: 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-400/20',
            buttonClass: 'bg-rose-600 hover:bg-rose-700 text-white shadow-sm shadow-rose-600/20',
            pillBg: 'bg-rose-50/90 dark:bg-rose-950/30 border-rose-200/80 dark:border-rose-800/40 text-rose-950 dark:text-rose-100',
            pillIconBox: 'w-6 h-6 rounded-full flex items-center justify-center bg-rose-500/15 text-rose-600 dark:text-rose-400 shrink-0',
            actionText: 'text-[11px] font-bold text-rose-600 dark:text-rose-400 hover:text-rose-700 flex items-center gap-0.5 underline underline-offset-2 shrink-0',
            icon: <AlertOctagon className="w-5 h-5 stroke-[2]" />,
            smallIcon: <AlertOctagon className="w-3.5 h-3.5 stroke-[2]" />,
        },
        promo: {
            badge: 'Special Offer',
            badgeClass: 'bg-purple-50 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300 border-purple-200/80 dark:border-purple-800/50',
            iconBox: 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-400/20',
            buttonClass: 'bg-purple-600 hover:bg-purple-700 text-white shadow-sm shadow-purple-600/20',
            pillBg: 'bg-purple-50/90 dark:bg-purple-950/30 border-purple-200/80 dark:border-purple-800/40 text-purple-950 dark:text-purple-100',
            pillIconBox: 'w-6 h-6 rounded-full flex items-center justify-center bg-purple-500/15 text-purple-600 dark:text-purple-400 shrink-0',
            actionText: 'text-[11px] font-bold text-purple-600 dark:text-purple-400 hover:text-purple-700 flex items-center gap-0.5 underline underline-offset-2 shrink-0',
            icon: <Sparkles className="w-5 h-5 stroke-[2]" />,
            smallIcon: <Sparkles className="w-3.5 h-3.5 stroke-[2]" />,
        },
    }[type] || {
        badge: 'Notice',
        badgeClass: 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200 border-gray-200',
        iconBox: 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
        buttonClass: 'bg-primary hover:opacity-90 text-primary-foreground',
        pillBg: 'bg-gray-50 dark:bg-zinc-800/70 border-gray-200/80 dark:border-gray-700/60 text-gray-900 dark:text-white',
        pillIconBox: 'w-6 h-6 rounded-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 shrink-0',
        actionText: 'text-[11px] font-bold text-primary hover:opacity-80 flex items-center gap-0.5 underline underline-offset-2 shrink-0',
        icon: <Megaphone className="w-5 h-5 stroke-[2]" />,
        smallIcon: <Megaphone className="w-3.5 h-3.5 stroke-[2]" />,
    };

    const isExternal = action_url && (action_url.startsWith('http://') || action_url.startsWith('https://'));

    // ─────────────────────────────────────────────────────────────
    // STYLE 1: POPUP MODAL DIALOG (Clean, Elegant & Focused)
    // ─────────────────────────────────────────────────────────────
    if (style === 'modal') {
        return (
            <div className="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto animate-in fade-in duration-200">
                {/* Backdrop Blur */}
                <div
                    className="fixed inset-0 bg-black/55 dark:bg-black/75 backdrop-blur-sm transition-opacity"
                    onClick={dismissible ? handleDismiss : undefined}
                />

                {/* Modal Container Card */}
                <div className="relative w-full max-w-sm sm:max-w-md bg-white dark:bg-[#181822] rounded-2xl sm:rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-800 p-5 sm:p-6 text-center overflow-hidden z-10 transition-all">
                    
                    {/* Top Decorative Ambient Glow */}
                    <div className="absolute -top-12 left-1/2 -translate-x-1/2 w-40 h-20 bg-gradient-to-b from-primary/20 to-transparent blur-2xl pointer-events-none" />

                    {/* Close 'X' Button */}
                    {dismissible && (
                        <button
                            type="button"
                            onClick={handleDismiss}
                            className="absolute top-3.5 right-3.5 w-7 h-7 rounded-full bg-gray-100 dark:bg-zinc-800 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 flex items-center justify-center transition-colors cursor-pointer"
                            aria-label="Close dialog"
                        >
                            <X className="w-3.5 h-3.5" />
                        </button>
                    )}

                    {/* Centered Large Icon Container */}
                    <div className="flex justify-center mb-3">
                        <div className={cn('w-12 h-12 rounded-xl flex items-center justify-center shadow-inner', moodConfig.iconBox)}>
                            {moodConfig.icon}
                        </div>
                    </div>

                    {/* Notice Mood Badge */}
                    <div className="inline-flex items-center gap-1 mb-2.5 select-none">
                        <span className={cn('px-2.5 py-0.5 rounded-full border text-[10px] font-bold uppercase tracking-wider', moodConfig.badgeClass)}>
                            {moodConfig.badge}
                        </span>
                    </div>

                    {/* Title */}
                    <h3 className="text-[16px] sm:text-[18px] font-extrabold text-gray-900 dark:text-white tracking-tight leading-snug mb-2">
                        {title || 'Announcement'}
                    </h3>

                    {/* Message Body */}
                    <div className="text-[13px] text-gray-600 dark:text-gray-300 leading-relaxed font-normal mb-5 whitespace-pre-line text-left bg-gray-50/80 dark:bg-zinc-900/60 p-3.5 rounded-xl border border-gray-100 dark:border-gray-800/80 max-h-52 overflow-y-auto">
                        {text}
                    </div>

                    {/* Action Buttons */}
                    <div className="flex flex-col gap-2">
                        {action_label && action_url && (
                            isExternal ? (
                                <a
                                    href={action_url}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    onClick={handleDismiss}
                                    className={cn(
                                        'w-full py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-transform active:scale-[0.98]',
                                        moodConfig.buttonClass
                                    )}
                                >
                                    <span>{action_label}</span>
                                    <ArrowRight className="w-3.5 h-3.5" />
                                </a>
                            ) : (
                                <Link
                                    href={action_url}
                                    onClick={handleDismiss}
                                    className={cn(
                                        'w-full py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-transform active:scale-[0.98]',
                                        moodConfig.buttonClass
                                    )}
                                >
                                    <span>{action_label}</span>
                                    <ArrowRight className="w-3.5 h-3.5" />
                                </Link>
                            )
                        )}

                        <button
                            type="button"
                            onClick={handleDismiss}
                            className="w-full py-2 px-4 rounded-xl text-xs font-semibold text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
                        >
                            {action_label ? 'Maybe Later' : 'I Understand, Got it'}
                        </button>
                    </div>

                    {/* Don't show again checkbox */}
                    <div className="mt-3 pt-2.5 border-t border-gray-100 dark:border-gray-800/60 flex items-center justify-center gap-2">
                        <label className="flex items-center gap-2 text-[11px] text-gray-400 dark:text-gray-500 cursor-pointer select-none">
                            <input
                                type="checkbox"
                                checked={dontShowAgain}
                                onChange={(e) => setDontShowAgain(e.target.checked)}
                                className="w-3.5 h-3.5 rounded text-primary focus:ring-0 cursor-pointer"
                            />
                            <span>Don't show this announcement again today</span>
                        </label>
                    </div>
                </div>
            </div>
        );
    }

    // ─────────────────────────────────────────────────────────────
    // STYLE 2: SCROLLING TICKER MARQUEE (Ultra-Slim Pill)
    // ─────────────────────────────────────────────────────────────
    if (style === 'ticker') {
        return (
            <div
                className={cn(
                    'relative w-full overflow-hidden rounded-xl sm:rounded-full border py-1.5 px-3 flex items-center gap-2 transition-all shadow-[0_1px_3px_rgba(0,0,0,0.02)]',
                    moodConfig.pillBg,
                    className
                )}
            >
                <div className="flex items-center gap-1.5 shrink-0 select-none">
                    <div className={moodConfig.pillIconBox}>
                        {moodConfig.smallIcon}
                    </div>
                </div>

                <div className="flex-1 overflow-hidden relative group">
                    <div className="whitespace-nowrap inline-block animate-marquee group-hover:[animation-play-state:paused]">
                        <span className="text-[11.5px] sm:text-xs font-medium pr-8 text-gray-800 dark:text-gray-200">
                            {title ? <strong className="text-gray-900 dark:text-white mr-1.5">{title}:</strong> : null}
                            {text}
                        </span>
                    </div>
                </div>

                <div className="flex items-center gap-2 shrink-0">
                    {action_label && action_url && (
                        isExternal ? (
                            <a
                                href={action_url}
                                target="_blank"
                                rel="noopener noreferrer"
                                className={moodConfig.actionText}
                            >
                                <span>{action_label}</span>
                                <ArrowRight className="w-3 h-3" />
                            </a>
                        ) : (
                            <Link
                                href={action_url}
                                className={moodConfig.actionText}
                            >
                                <span>{action_label}</span>
                                <ArrowRight className="w-3 h-3" />
                            </Link>
                        )
                    )}

                    {dismissible && (
                        <button
                            type="button"
                            onClick={handleDismiss}
                            className="p-1 rounded-full text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors cursor-pointer"
                            aria-label="Dismiss announcement"
                        >
                            <X className="w-3.5 h-3.5" />
                        </button>
                    )}
                </div>

                <style>{`
                    @keyframes marquee {
                        0% { transform: translateX(100%); }
                        100% { transform: translateX(-100%); }
                    }
                    .animate-marquee {
                        display: inline-block;
                        white-space: nowrap;
                        animation: marquee 25s linear infinite;
                    }
                `}</style>
            </div>
        );
    }

    // ─────────────────────────────────────────────────────────────
    // STYLE 3: ULTRA-SLIM & SLEEK NOTIFICATION PILL (Compact & Never Intrusive)
    // ─────────────────────────────────────────────────────────────
    return (
        <div
            className={cn(
                'relative w-full rounded-xl sm:rounded-full border px-3 py-1.5 sm:py-2 flex items-center justify-between gap-2.5 transition-all shadow-[0_1px_3px_rgba(0,0,0,0.02)]',
                moodConfig.pillBg,
                className
            )}
        >
            <div className="flex items-center gap-2 min-w-0 flex-1">
                <div className={moodConfig.pillIconBox}>
                    {moodConfig.smallIcon}
                </div>
                <div className="flex items-center gap-1.5 min-w-0 overflow-hidden text-[11.5px] sm:text-[12px] leading-tight">
                    {title && (
                        <span className="font-bold shrink-0 text-gray-900 dark:text-white">
                            {title}:
                        </span>
                    )}
                    <span className="truncate font-medium text-gray-700 dark:text-gray-300">
                        {text}
                    </span>
                </div>
            </div>

            <div className="flex items-center gap-2 shrink-0">
                {action_label && action_url && (
                    isExternal ? (
                        <a
                            href={action_url}
                            target="_blank"
                            rel="noopener noreferrer"
                            className={moodConfig.actionText}
                        >
                            <span>{action_label}</span>
                            <ArrowRight className="w-3 h-3" />
                        </a>
                    ) : (
                        <Link
                            href={action_url}
                            className={moodConfig.actionText}
                        >
                            <span>{action_label}</span>
                            <ArrowRight className="w-3 h-3" />
                        </Link>
                    )
                )}

                {dismissible && (
                    <button
                        type="button"
                        onClick={handleDismiss}
                        className="p-1 rounded-full text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors cursor-pointer"
                        aria-label="Dismiss notice"
                    >
                        <X className="w-3.5 h-3.5" />
                    </button>
                )}
            </div>
        </div>
    );
}
