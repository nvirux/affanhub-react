import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import type { BreadcrumbItem } from '@/types';
import { usePage, Link } from '@inertiajs/react';
import { useEffect } from 'react';
import { LayoutGrid, Wallet, Gift, History, User } from 'lucide-react';
import { dashboard } from '@/routes';

export default function AppLayout({
    breadcrumbs = [],
    children,
}: {
    breadcrumbs?: BreadcrumbItem[];
    children: React.ReactNode;
}) {
    const { store } = usePage<any>().props;
    const url = usePage().url;

    const isDashboardActive = url.startsWith('/dashboard');
    const isEarnActive = url.startsWith('/earn');

    return (
        <AppLayoutTemplate breadcrumbs={breadcrumbs}>
            <div className="pb-16 md:pb-0 min-h-screen flex flex-col flex-1 bg-[#f8f7fc] dark:bg-zinc-900">
                {children}
            </div>

            {/* ─── STICKY BOTTOM NAVIGATION FOR MOBILE ─── */}
            <div className="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white dark:bg-[#1e1e2d] border-t border-slate-200/60 dark:border-slate-800 flex items-center justify-around py-2 shadow-lg shadow-black/5">
                <Link
                    href={dashboard().url}
                    className={`flex flex-col items-center gap-1 transition-colors ${
                        isDashboardActive ? 'text-primary font-bold' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200'
                    }`}
                >
                    <LayoutGrid className="w-5 h-5 stroke-[1.8]" />
                    <span className="text-[10px] font-bold">Dashboard</span>
                </Link>
                <Link
                    href="#"
                    className="flex flex-col items-center gap-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                >
                    <Wallet className="w-5 h-5 stroke-[1.8]" />
                    <span className="text-[10px] font-bold">Wallet</span>
                </Link>

                {/* EARN TAB (Featured Center Action) */}
                <Link
                    href="/earn"
                    className="relative -top-2.5 flex flex-col items-center group focus:outline-none"
                >
                    <div className="relative">
                        <div
                            className={`w-11 h-11 rounded-full flex items-center justify-center transition-all duration-200 ring-4 ring-white dark:ring-[#1e1e2d] ${
                                isEarnActive
                                    ? 'bg-primary text-white shadow-lg shadow-primary/40 scale-105'
                                    : 'bg-primary text-white shadow-md shadow-primary/25 group-hover:scale-105 active:scale-95'
                            }`}
                        >
                            <Gift className="w-5 h-5 stroke-[2]" />
                        </div>
                        <span className="absolute -top-1 -right-1 px-1 py-0.5 rounded-full bg-emerald-500 text-[8px] font-black text-white leading-none shadow-xs ring-1.5 ring-white dark:ring-[#1e1e2d]">
                            BONUS
                        </span>
                    </div>
                    <span
                        className={`text-[10px] mt-0.5 tracking-tight ${
                            isEarnActive
                                ? 'font-extrabold text-primary'
                                : 'font-bold text-gray-700 dark:text-gray-200 group-hover:text-primary transition-colors'
                        }`}
                    >
                        Earn
                    </span>
                </Link>

                <Link
                    href="#"
                    className="flex flex-col items-center gap-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                >
                    <History className="w-5 h-5 stroke-[1.8]" />
                    <span className="text-[10px] font-bold">History</span>
                </Link>
                <Link
                    href="#"
                    className="flex flex-col items-center gap-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                >
                    <User className="w-5 h-5 stroke-[1.8]" />
                    <span className="text-[10px] font-bold">Profile</span>
                </Link>
            </div>

        </AppLayoutTemplate>
    );
}
