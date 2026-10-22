import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import type { BreadcrumbItem } from '@/types';
import { usePage, Link } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { Home, Wallet, Gift, History, User } from 'lucide-react';
import { dashboard } from '@/routes';
import { edit as editProfile } from '@/routes/profile';

import { isBiometricSupported, hasBiometricFor, saveBiometric } from '@/lib/biometrics';
import EnableBiometricSheet from '@/components/enable-biometric-sheet';

export default function AppLayout({
    breadcrumbs = [],
    children,
}: {
    breadcrumbs?: BreadcrumbItem[];
    children: React.ReactNode;
}) {
    const { store, flash, auth } = usePage<any>().props;
    const url = usePage().url;

    const [showBiometricPrompt, setShowBiometricPrompt] = useState(false);
    const [bioPromptData, setBioPromptData] = useState<{ identifier: string; token: string; aliases: string[] } | null>(null);

    useEffect(() => {
        const token = flash?.biometric_token;
        const identifier = flash?.biometric_identifier;

        if (token && identifier && isBiometricSupported()) {
            const aliases = Array.from(
                new Set(
                    [
                        auth?.user?.email,
                        auth?.user?.phone,
                        flash?.biometric_email,
                        flash?.biometric_phone,
                    ].filter(Boolean) as string[]
                )
            );

            const hasBio = hasBiometricFor(identifier, aliases);
            if (hasBio) {
                // Silently refresh token in secure vault for existing user
                saveBiometric(identifier, token, aliases);
            } else {
                const dismissed = sessionStorage.getItem(`bio_prompt_dismissed_${identifier}`);
                if (!dismissed) {
                    setBioPromptData({ identifier, token, aliases });
                    const timer = setTimeout(() => {
                        setShowBiometricPrompt(true);
                    }, 600);
                    return () => clearTimeout(timer);
                }
            }
        }
    }, [flash?.biometric_token, flash?.biometric_identifier, auth?.user]);

    const isDashboardActive = url.startsWith('/dashboard');
    const isWalletActive = url.startsWith('/wallet');
    const isEarnActive = url.startsWith('/earn');
    const isHistoryActive = url.startsWith('/transactions');
    const isProfileActive = url.startsWith('/settings');

    return (
        <AppLayoutTemplate breadcrumbs={breadcrumbs}>
            <div className="pb-20 md:pb-0 min-h-screen flex flex-col flex-1 bg-[#f8f7fc] dark:bg-zinc-900 w-full min-w-0">
                {children}
            </div>

            {bioPromptData && (
                <EnableBiometricSheet
                    isOpen={showBiometricPrompt}
                    onClose={() => setShowBiometricPrompt(false)}
                    identifier={bioPromptData.identifier}
                    token={bioPromptData.token}
                    aliases={bioPromptData.aliases}
                />
            )}

            {/* ─── STICKY BOTTOM NAVIGATION FOR MOBILE ─── */}
            <div className="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white dark:bg-[#1e1e2d] border-t border-slate-200/60 dark:border-slate-800 grid grid-cols-5 h-16 items-center px-1 shadow-lg shadow-black/5">
                <Link
                    href={dashboard().url}
                    className={`flex flex-col items-center justify-center gap-1 transition-colors ${
                        isDashboardActive ? 'text-primary font-bold' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200'
                    }`}
                >
                    <Home className="w-5 h-5 stroke-[1.8]" />
                    <span className="text-[10px] font-bold">Home</span>
                </Link>
                <Link
                    href="/wallet"
                    className={`flex flex-col items-center justify-center gap-1 transition-colors ${
                        isWalletActive ? 'text-primary font-bold' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200'
                    }`}
                >
                    <Wallet className="w-5 h-5 stroke-[1.8]" />
                    <span className="text-[10px] font-bold">Wallet</span>
                </Link>

                {/* EARN TAB (Featured Center Action - Mathematically Centered) */}
                <Link
                    href="/earn"
                    className="flex flex-col items-center justify-center group focus:outline-none relative"
                >
                    <div className="relative -mt-5">
                        <div
                            className={`w-12 h-12 rounded-full flex items-center justify-center transition-all duration-200 ring-4 ring-white dark:ring-[#1e1e2d] ${
                                isEarnActive
                                    ? 'bg-primary text-white shadow-lg shadow-primary/40 scale-105'
                                    : 'bg-primary text-white shadow-md shadow-primary/25 group-hover:scale-105 active:scale-95'
                            }`}
                        >
                            <Gift className="w-5 h-5 stroke-[2]" />
                        </div>
                        <span className="absolute -top-1.5 left-1/2 -translate-x-1/2 px-1.5 py-0.5 rounded-full bg-emerald-500 text-[8px] font-black text-white leading-none shadow-xs ring-1.5 ring-white dark:ring-[#1e1e2d] whitespace-nowrap">
                            BONUS
                        </span>
                    </div>
                    <span
                        className={`text-[10px] mt-1 tracking-tight ${
                            isEarnActive
                                ? 'font-extrabold text-primary'
                                : 'font-bold text-gray-700 dark:text-gray-200 group-hover:text-primary transition-colors'
                        }`}
                    >
                        Earn
                    </span>
                </Link>

                <Link
                    href="/transactions"
                    className={`flex flex-col items-center justify-center gap-1 transition-colors ${
                        isHistoryActive ? 'text-primary font-bold' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200'
                    }`}
                >
                    <History className="w-5 h-5 stroke-[1.8]" />
                    <span className="text-[10px] font-bold">History</span>
                </Link>
                <Link
                    href={editProfile().url}
                    className={`flex flex-col items-center justify-center gap-1 transition-colors ${
                        isProfileActive ? 'text-primary font-bold' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200'
                    }`}
                >
                    <User className="w-5 h-5 stroke-[1.8]" />
                    <span className="text-[10px] font-bold">Profile</span>
                </Link>
            </div>

        </AppLayoutTemplate>
    );
}
