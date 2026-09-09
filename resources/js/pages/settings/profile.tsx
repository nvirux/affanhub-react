import { Head, usePage, Link, router } from '@inertiajs/react';
import {
    User, Mail, Phone, Copy, Check,
    ShieldCheck, ChevronRight, Gift, Wallet, LogOut, ChevronLeft,
    Headphones, AlertCircle, HelpCircle
} from 'lucide-react';
import { useState } from 'react';
import { edit as editSecurity } from '@/routes/security';
import { dashboard, logout } from '@/routes';
import { send } from '@/routes/verification';
import type { Auth } from '@/types';

type PageProps = {
    auth: Auth;
    mustVerifyEmail?: boolean;
    status?: string;
};

export default function Profile({
    mustVerifyEmail = false,
    status,
}: PageProps) {
    const { auth } = usePage<PageProps>().props;
    const user = auth.user;

    const [copied, setCopied] = useState(false);

    const handleCopyReferral = () => {
        if (user.referral_code) {
            navigator.clipboard.writeText(user.referral_code);
            setCopied(true);
            setTimeout(() => setCopied(false), 2000);
        }
    };

    const getInitials = (name?: string) => {
        if (!name) return 'UN';
        return name
            .split(' ')
            .map((n) => n[0])
            .slice(0, 2)
            .join('')
            .toUpperCase();
    };

    return (
        <>
            <Head title="Profile - Account & Settings" />

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
                            Profile
                        </h1>
                        <p className="text-xs text-slate-400 dark:text-slate-400 font-normal truncate mt-0.5">
                            Account & Settings
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

            {/* ── MAIN CONTENT CONTAINER (matches Airtime, Data, and Earn pages) ── */}
            <div className="max-w-xl mx-auto px-4 py-4 md:py-6 w-full space-y-4">

                {/* 1. User Summary Card (Read-only personal details) */}
                <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-xs">
                    <div className="flex items-center gap-4">
                        <div className="w-14 h-14 rounded-full bg-primary text-white font-extrabold text-lg flex items-center justify-center shrink-0 shadow-xs">
                            {getInitials(user.name)}
                        </div>
                        <div className="min-w-0 flex-1">
                            <h2 className="text-base font-extrabold text-gray-900 dark:text-white truncate">
                                {user.name}
                            </h2>
                            <p className="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                {user.email}
                            </p>
                            {user.phone && (
                                <p className="text-xs font-mono font-medium text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1">
                                    <Phone className="w-3 h-3 text-primary" />
                                    <span>{user.phone}</span>
                                </p>
                            )}
                        </div>
                    </div>

                    {/* Referral Code Row */}
                    {user.referral_code && (
                        <div className="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                            <div>
                                <span className="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block">
                                    Referral Code
                                </span>
                                <span className="font-mono font-extrabold text-sm tracking-wider text-primary">
                                    {user.referral_code}
                                </span>
                            </div>
                            <button
                                type="button"
                                onClick={handleCopyReferral}
                                className="px-3 py-1.5 rounded-xl bg-primary/10 text-primary hover:bg-primary/20 text-xs font-bold flex items-center gap-1.5 transition-colors cursor-pointer"
                            >
                                {copied ? <Check className="w-3.5 h-3.5" /> : <Copy className="w-3.5 h-3.5" />}
                                <span>{copied ? 'Copied' : 'Copy'}</span>
                            </button>
                        </div>
                    )}
                </div>

                {/* 2. Menu Navigation List Card */}
                <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl shadow-xs overflow-hidden divide-y divide-gray-100 dark:divide-gray-800">
                    {/* Security & PIN Access Item */}
                    <Link
                        href={editSecurity()}
                        className="flex items-center justify-between p-4 hover:bg-gray-50/80 dark:hover:bg-zinc-800/40 transition-colors group cursor-pointer"
                    >
                        <div className="flex items-center gap-3.5 min-w-0">
                            <div className="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <ShieldCheck className="w-5 h-5 stroke-[1.8]" />
                            </div>
                            <div className="min-w-0">
                                <div className="flex items-center gap-2">
                                    <h3 className="text-sm font-bold text-gray-900 dark:text-white">
                                        Security & PIN Access
                                    </h3>
                                    <span className="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                        Active
                                    </span>
                                </div>
                                <p className="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                    4-Digit Login PIN, Transaction PIN, 2FA
                                </p>
                            </div>
                        </div>
                        <ChevronRight className="w-4 h-4 text-gray-400 group-hover:text-primary transition-colors shrink-0 ml-2" />
                    </Link>

                    {/* Customer Support & Contact Item */}
                    <Link
                        href="/contact"
                        className="flex items-center justify-between p-4 hover:bg-gray-50/80 dark:hover:bg-zinc-800/40 transition-colors group cursor-pointer"
                    >
                        <div className="flex items-center gap-3.5 min-w-0">
                            <div className="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                <HelpCircle className="w-5 h-5 stroke-[1.8]" />
                            </div>
                            <div className="min-w-0">
                                <h3 className="text-sm font-bold text-gray-900 dark:text-white">
                                    Customer Support & Live Chat
                                </h3>
                                <p className="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                    Contact us, WhatsApp & real-time chat
                                </p>
                            </div>
                        </div>
                        <ChevronRight className="w-4 h-4 text-gray-400 group-hover:text-primary transition-colors shrink-0 ml-2" />
                    </Link>

                    {/* Refer & Earn Bonus Item */}
                    <Link
                        href="/earn"
                        className="flex items-center justify-between p-4 hover:bg-gray-50/80 dark:hover:bg-zinc-800/40 transition-colors group cursor-pointer"
                    >
                        <div className="flex items-center gap-3.5 min-w-0">
                            <div className="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
                                <Gift className="w-5 h-5 stroke-[1.8]" />
                            </div>
                            <div className="min-w-0">
                                <h3 className="text-sm font-bold text-gray-900 dark:text-white">
                                    Refer & Earn
                                </h3>
                                <p className="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                    Invite friends and earn commission bonus
                                </p>
                            </div>
                        </div>
                        <ChevronRight className="w-4 h-4 text-gray-400 group-hover:text-primary transition-colors shrink-0 ml-2" />
                    </Link>

                    {/* Wallet & Accounts Item */}
                    <Link
                        href={dashboard().url}
                        className="flex items-center justify-between p-4 hover:bg-gray-50/80 dark:hover:bg-zinc-800/40 transition-colors group cursor-pointer"
                    >
                        <div className="flex items-center gap-3.5 min-w-0">
                            <div className="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-500/10 text-purple-500 flex items-center justify-center shrink-0">
                                <Wallet className="w-5 h-5 stroke-[1.8]" />
                            </div>
                            <div className="min-w-0">
                                <h3 className="text-sm font-bold text-gray-900 dark:text-white">
                                    Wallet & Accounts
                                </h3>
                                <p className="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                    View balance and dedicated virtual accounts
                                </p>
                            </div>
                        </div>
                        <ChevronRight className="w-4 h-4 text-gray-400 group-hover:text-primary transition-colors shrink-0 ml-2" />
                    </Link>
                </div>

                {/* 3. Email Verification Banner (If unverified) */}
                {mustVerifyEmail && user.email_verified_at === null && (
                    <div className="rounded-2xl border border-amber-200 dark:border-amber-500/20 bg-amber-50 dark:bg-amber-500/10 p-4 text-xs text-amber-800 dark:text-amber-300 flex items-start gap-3">
                        <AlertCircle className="w-4 h-4 shrink-0 text-amber-600 mt-0.5" />
                        <div className="space-y-1">
                            <p className="font-bold">Your email address is unverified.</p>
                            <p className="text-muted-foreground">
                                Verify your email to ensure password and PIN recovery options remain active.
                            </p>
                            <Link
                                href={send()}
                                as="button"
                                className="text-xs font-bold underline hover:text-amber-900 dark:hover:text-amber-100 cursor-pointer pt-0.5 block"
                            >
                                Resend verification email &rarr;
                            </Link>
                            {status === 'verification-link-sent' && (
                                <p className="text-xs text-emerald-600 dark:text-emerald-400 font-bold pt-1">
                                    A fresh verification link has been sent to your email!
                                </p>
                            )}
                        </div>
                    </div>
                )}

                {/* 4. Log Out Action */}
                <div className="pt-1">
                    <button
                        type="button"
                        onClick={() => router.post(logout().url)}
                        className="w-full py-3 px-4 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-100 dark:border-rose-500/20 text-rose-600 dark:text-rose-400 font-bold text-xs flex items-center justify-center gap-2 hover:bg-rose-100 dark:hover:bg-rose-500/20 transition-colors cursor-pointer"
                    >
                        <LogOut className="w-4 h-4" />
                        <span>Log Out</span>
                    </button>
                </div>
            </div>
        </>
    );
}
