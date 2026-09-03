import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import type { BreadcrumbItem } from '@/types';
import { usePage, Link } from '@inertiajs/react';
import { useEffect } from 'react';
import { LayoutGrid, Wallet, History, User } from 'lucide-react';
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

    return (
        <AppLayoutTemplate breadcrumbs={breadcrumbs}>
            <div className="pb-16 md:pb-0 min-h-screen flex flex-col flex-1">
                {children}
            </div>

            {/* ─── STICKY BOTTOM NAVIGATION FOR MOBILE ─── */}
            <div className="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white dark:bg-[#1e1e2d] border-t border-slate-200/60 dark:border-slate-800 flex items-center justify-around py-2.5 shadow-lg shadow-black/5">
                <Link
                    href={dashboard().url}
                    className={`flex flex-col items-center gap-1 transition-colors ${
                        isDashboardActive ? 'text-primary' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200'
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
