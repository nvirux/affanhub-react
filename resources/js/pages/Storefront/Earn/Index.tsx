import { useState } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import {
    ChevronLeft, Copy, Check, Share2, Gift,
    AlertCircle, Headphones, CheckCircle, Clock
} from 'lucide-react';

interface ReferralItem {
    id: number;
    name: string;
    status: 'pending' | 'completed';
    reward_amount: number;
    date: string;
    completed_at?: string;
}

interface EarnPageProps {
    has_feature?: boolean;
    is_enabled?: boolean;
    referral_code?: string;
    referral_link?: string;
    reward_amount?: number;
    condition_type?: string;
    min_deposit_amount?: number;
    stats?: {
        total_earned: number;
        total_referrals: number;
        completed_referrals: number;
        pending_referrals: number;
    };
    referrals?: ReferralItem[];
    wallet_balance?: number;
}

export default function EarnPage({
    has_feature = true,
    is_enabled = true,
    referral_code = '',
    referral_link = '',
    reward_amount = 50,
    condition_type = 'first_deposit',
    min_deposit_amount = 500,
    stats = { total_earned: 0, total_referrals: 0, completed_referrals: 0, pending_referrals: 0 },
    referrals = [],
    wallet_balance = 0,
}: EarnPageProps) {
    const { store } = usePage<any>().props;
    const [copiedCode, setCopiedCode] = useState(false);
    const [copiedLink, setCopiedLink] = useState(false);

    const handleCopyCode = () => {
        if (!referral_code) return;
        navigator.clipboard.writeText(referral_code);
        setCopiedCode(true);
        setTimeout(() => setCopiedCode(false), 2000);
    };

    const handleCopyLink = () => {
        if (!referral_link) return;
        navigator.clipboard.writeText(referral_link);
        setCopiedLink(true);
        setTimeout(() => setCopiedLink(false), 2000);
    };

    const storeName = store?.name || 'our platform';
    const whatsappText = encodeURIComponent(
        `Hey! I use ${storeName} to buy cheap Data, Airtime, and pay bills instantly. Sign up with my link to get started: ${referral_link}`
    );
    const whatsappUrl = `https://api.whatsapp.com/send?text=${whatsappText}`;

    return (
        <>
            <Head title="Earn & Refer" />

            {/* ── MOBILE STICKY HEADER ── */}
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
                            Earn & Refer
                        </h1>
                        <p className="text-xs text-slate-400 dark:text-slate-400 font-normal truncate mt-0.5">
                            Invite friends & get paid
                        </p>
                    </div>
                </div>

                <div className="flex items-center gap-2 shrink-0 ml-2">
                    <Link
                        href="/contact"
                        className="p-1.5 text-slate-600 dark:text-slate-300 hover:text-primary transition-colors cursor-pointer"
                        title="Customer Support"
                    >
                        <Headphones className="w-5 h-5 stroke-[1.8]" />
                    </Link>
                </div>
            </div>

            {/* ── MAIN CONTENT CONTAINER (Super Compact & Focused) ── */}
            <div className="max-w-md mx-auto px-4 py-4 md:py-6 w-full space-y-3">

                {/* If Program Inactive Alert */}
                {!is_enabled && (
                    <div className="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-xl p-3 text-amber-800 dark:text-amber-300 flex items-center gap-2.5 text-xs">
                        <AlertCircle className="w-4 h-4 shrink-0 text-amber-600" />
                        <span>Referral rewards are temporarily paused on this store.</span>
                    </div>
                )}

                {/* ALL-IN-ONE HERO REFERRAL CARD */}
                <div className="bg-white dark:bg-[#1e1e2d] rounded-2xl p-5 border border-gray-100 dark:border-gray-800 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] text-center space-y-4">
                    
                    {/* Header Icon + Headline */}
                    <div className="space-y-1">
                        <div className="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mx-auto mb-2">
                            <Gift className="w-6 h-6 stroke-[1.8]" />
                        </div>
                        <h2 className="text-base font-extrabold text-gray-900 dark:text-white tracking-tight">
                            Invite Friends, Earn ₦{reward_amount}
                        </h2>
                        <p className="text-xs text-gray-500 dark:text-gray-400 max-w-xs mx-auto">
                            {condition_type === 'first_deposit'
                                ? `Earn ₦${reward_amount} instantly when your friend registers and deposits ₦${min_deposit_amount}+.`
                                : `Earn ₦${reward_amount} instantly on your friend's first recharge.`}
                        </p>
                    </div>

                    {/* Referral Code Box */}
                    <div className="bg-gray-50 dark:bg-zinc-800/60 border border-dashed border-gray-200 dark:border-gray-700 rounded-xl p-3 flex items-center justify-between">
                        <div className="text-left">
                            <span className="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block">
                                Referral Code
                            </span>
                            <span className="font-mono font-extrabold text-base tracking-wider text-gray-900 dark:text-white">
                                {referral_code || '------'}
                            </span>
                        </div>
                        <button
                            type="button"
                            onClick={handleCopyCode}
                            className="px-3.5 py-1.5 rounded-lg bg-primary text-white text-xs font-bold flex items-center gap-1.5 hover:opacity-90 active:scale-95 transition-all cursor-pointer shadow-xs"
                        >
                            {copiedCode ? <Check className="w-3.5 h-3.5" /> : <Copy className="w-3.5 h-3.5" />}
                            <span>{copiedCode ? 'Copied' : 'Copy'}</span>
                        </button>
                    </div>

                    {/* Action Buttons: WhatsApp + Copy Link */}
                    <div className="grid grid-cols-2 gap-2">
                        <a
                            href={whatsappUrl}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="py-2.5 px-3 rounded-xl bg-[#25D366] hover:bg-[#20ba59] text-white flex items-center justify-center gap-1.5 font-bold text-xs transition-all active:scale-98 cursor-pointer shadow-xs"
                        >
                            <Share2 className="w-3.5 h-3.5 fill-current" />
                            <span>WhatsApp</span>
                        </a>

                        <button
                            type="button"
                            onClick={handleCopyLink}
                            className="py-2.5 px-3 rounded-xl bg-gray-100 dark:bg-zinc-800 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-700 text-xs font-bold flex items-center justify-center gap-1.5 hover:bg-gray-200 dark:hover:bg-zinc-700 active:scale-98 transition-all cursor-pointer"
                        >
                            {copiedLink ? <Check className="w-3.5 h-3.5 text-emerald-600" /> : <Copy className="w-3.5 h-3.5" />}
                            <span>{copiedLink ? 'Link Copied' : 'Copy Link'}</span>
                        </button>
                    </div>

                    {/* Compact Stats Strip */}
                    <div className="grid grid-cols-3 gap-2 pt-3 border-t border-gray-100 dark:border-gray-800 text-center">
                        <div>
                            <span className="text-[10px] text-gray-400 font-medium block">Earned</span>
                            <span className="text-sm font-extrabold text-gray-900 dark:text-white mt-0.5 block">
                                ₦{Number(stats.total_earned).toLocaleString('en-NG', { minimumFractionDigits: 0 })}
                            </span>
                        </div>
                        <div className="border-x border-gray-100 dark:border-gray-800">
                            <span className="text-[10px] text-gray-400 font-medium block">Invited</span>
                            <span className="text-sm font-extrabold text-gray-900 dark:text-white mt-0.5 block">
                                {stats.total_referrals}
                            </span>
                        </div>
                        <div>
                            <span className="text-[10px] text-gray-400 font-medium block">Qualified</span>
                            <span className="text-sm font-extrabold text-emerald-600 dark:text-emerald-400 mt-0.5 block">
                                {stats.completed_referrals}
                            </span>
                        </div>
                    </div>
                </div>

                {/* REFERRAL HISTORY (Compact & Clean) */}
                <div className="bg-white dark:bg-[#1e1e2d] rounded-2xl p-4 border border-gray-100 dark:border-gray-800 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] space-y-2">
                    <div className="flex items-center justify-between">
                        <span className="text-xs font-bold text-gray-900 dark:text-white">Recent Referrals</span>
                        <span className="text-[11px] text-gray-400">{referrals.length} total</span>
                    </div>

                    {referrals.length === 0 ? (
                        <p className="text-xs text-gray-400 text-center py-4">
                            No friends invited yet. Share your code above!
                        </p>
                    ) : (
                        <div className="divide-y divide-gray-100 dark:divide-gray-800">
                            {referrals.map((item) => (
                                <div key={item.id} className="py-2.5 flex items-center justify-between gap-2 text-xs">
                                    <div className="min-w-0">
                                        <div className="font-bold text-gray-900 dark:text-white truncate">
                                            {item.name}
                                        </div>
                                        <span className="text-[10px] text-gray-400">{item.date}</span>
                                    </div>
                                    <div className="text-right shrink-0">
                                        <div className="font-extrabold text-gray-900 dark:text-white">
                                            ₦{Number(item.reward_amount).toFixed(2)}
                                        </div>
                                        {item.status === 'completed' ? (
                                            <span className="inline-flex items-center gap-0.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                                                <CheckCircle className="w-2.5 h-2.5" />
                                                Paid
                                            </span>
                                        ) : (
                                            <span className="inline-flex items-center gap-0.5 text-[10px] font-bold text-amber-500 dark:text-amber-400">
                                                <Clock className="w-2.5 h-2.5" />
                                                Pending
                                            </span>
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>

            </div>
        </>
    );
}
