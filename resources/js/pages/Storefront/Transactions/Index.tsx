import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { dashboard } from '@/routes';
import { cn } from '@/lib/utils';
import {
    Search, Filter, ChevronLeft, ArrowRight, CheckCircle2,
    Clock, AlertCircle, Smartphone, Wifi, Receipt, RefreshCw,
    ShieldCheck, Zap, Tv, ChevronDown
} from 'lucide-react';

interface TransactionItem {
    id: number;
    reference: string;
    service_type: string;
    recipient: string;
    amount: number;
    amount_paid?: number;
    status: 'successful' | 'pending' | 'failed';
    created_at: string;
    date: string;
}

interface TransactionsIndexProps {
    transactions: {
        data: TransactionItem[];
        links: Array<{ url: string | null; label: string; active: boolean }>;
        total: number;
        current_page: number;
        last_page: number;
    };
    filters: {
        type: string;
        status: string;
        search: string;
    };
}

export default function TransactionsIndex({
    transactions,
    filters,
}: TransactionsIndexProps) {
    const [search, setSearch] = useState(filters.search || '');
    const [activeType, setActiveType] = useState(filters.type || 'all');
    const [activeStatus, setActiveStatus] = useState(filters.status || 'all');

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(
            '/transactions',
            {
                type: activeType !== 'all' ? activeType : undefined,
                status: activeStatus !== 'all' ? activeStatus : undefined,
                search: search || undefined,
            },
            { preserveState: true, replace: true }
        );
    };

    const handleTypeChange = (type: string) => {
        setActiveType(type);
        router.get(
            '/transactions',
            {
                type: type !== 'all' ? type : undefined,
                status: activeStatus !== 'all' ? activeStatus : undefined,
                search: search || undefined,
            },
            { preserveState: true, replace: true }
        );
    };

    const getServiceMeta = (serviceType: string) => {
        switch (serviceType) {
            case 'nin_verification':
                return {
                    label: 'NIN Slip',
                    icon: ShieldCheck,
                    colorClass: 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
                };
            case 'bvn_verification':
                return {
                    label: 'BVN Slip',
                    icon: ShieldCheck,
                    colorClass: 'bg-teal-500/10 text-teal-600 dark:text-teal-400',
                };
            case 'airtime':
                return {
                    label: 'Airtime',
                    icon: Smartphone,
                    colorClass: 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
                };
            case 'data':
                return {
                    label: 'Data Plan',
                    icon: Wifi,
                    colorClass: 'bg-blue-500/10 text-blue-600 dark:text-blue-400',
                };
            case 'cable':
                return {
                    label: 'Cable TV',
                    icon: Tv,
                    colorClass: 'bg-purple-500/10 text-purple-600 dark:text-purple-400',
                };
            case 'electricity':
                return {
                    label: 'Electricity',
                    icon: Zap,
                    colorClass: 'bg-yellow-500/10 text-yellow-600 dark:text-yellow-400',
                };
            default:
                return {
                    label: (serviceType || 'Transaction').replace(/_/g, ' '),
                    icon: Receipt,
                    colorClass: 'bg-slate-500/10 text-slate-600 dark:text-slate-400',
                };
        }
    };

    return (
        <>
            <Head title="Transaction History" />

            {/* Mobile Header */}
            <div className="sticky top-0 z-30 bg-white/95 dark:bg-[#181826]/95 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 py-3 px-4 flex md:hidden items-center justify-between shadow-2xs w-full min-w-0">
                <div className="flex items-center gap-3 min-w-0">
                    <Link
                        href={dashboard().url}
                        className="p-1 text-gray-700 dark:text-gray-200 hover:text-primary transition-colors cursor-pointer shrink-0"
                    >
                        <ChevronLeft className="w-6 h-6 stroke-[2.2]" />
                    </Link>
                    <div className="min-w-0">
                        <h1 className="font-extrabold text-base text-gray-900 dark:text-white tracking-tight leading-tight">
                            Orders & Purchases
                        </h1>
                        <p className="text-xs text-slate-400 font-normal truncate mt-0.5">
                            Telecom service history & receipts
                        </p>
                    </div>
                </div>
            </div>

            {/* Main Content */}
            <div className="w-full max-w-4xl mx-auto px-3 sm:px-4 py-4 md:py-8 space-y-4 sm:space-y-6 min-w-0">

                {/* Search & Filter Bar */}
                <div className="w-full min-w-0 bg-white dark:bg-[#181826] rounded-2xl border border-slate-100 dark:border-slate-800 p-3 sm:p-4 shadow-xs">
                    <div className="flex items-center gap-2.5 w-full min-w-0">
                        {/* Search Input */}
                        <form onSubmit={handleSearch} className="relative flex-1 min-w-0">
                            <Search className="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                            <input
                                type="text"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Search by phone number or reference..."
                                className="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 dark:bg-zinc-900/60 border border-slate-200 dark:border-zinc-800 text-xs text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/40"
                            />
                        </form>

                        {/* Service Filter Dropdown */}
                        <div className="relative shrink-0">
                            <select
                                value={activeType}
                                onChange={(e) => handleTypeChange(e.target.value)}
                                className="appearance-none bg-slate-50 dark:bg-zinc-900/60 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl pl-8 pr-7 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40 cursor-pointer shadow-xs"
                            >
                                <option value="all">All Services</option>
                                <option value="identity">Identity Slips</option>
                                <option value="airtime">Airtime</option>
                                <option value="data">Data Plans</option>
                                <option value="cable">Cable TV</option>
                                <option value="electricity">Electricity</option>
                            </select>
                            <Filter className="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" />
                            <ChevronDown className="w-3 h-3 text-slate-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" />
                        </div>
                    </div>
                </div>

                {/* Transactions List */}
                <div className="w-full min-w-0 bg-white dark:bg-[#181826] rounded-2xl border border-slate-100 dark:border-slate-800 p-2 sm:p-4 md:p-6 shadow-xs overflow-hidden">
                    {transactions.data.length > 0 ? (
                        <div className="divide-y divide-slate-100 dark:divide-slate-800/80 w-full min-w-0">
                            {transactions.data.map((tx) => {
                                const meta = getServiceMeta(tx.service_type);
                                const Icon = meta.icon;
                                return (
                                    <Link
                                        key={tx.id}
                                        href={`/transactions/${tx.reference}`}
                                        className="w-full py-3 px-2 sm:px-3 flex items-center justify-between gap-2.5 sm:gap-4 hover:bg-slate-50/70 dark:hover:bg-zinc-900/40 rounded-xl transition-colors group min-w-0"
                                    >
                                        {/* Left Side: Service Icon & Info */}
                                        <div className="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1">
                                            <div
                                                className={cn(
                                                    'w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center shrink-0',
                                                    meta.colorClass
                                                )}
                                            >
                                                <Icon className="w-4 h-4 sm:w-5 sm:h-5 stroke-[2]" />
                                            </div>

                                            <div className="min-w-0 flex-1">
                                                <div className="flex items-center gap-1.5 min-w-0">
                                                    <span className="font-bold text-slate-900 dark:text-white text-xs sm:text-sm truncate">
                                                        {meta.label}
                                                    </span>
                                                    {tx.recipient && (
                                                        <span className="font-mono text-slate-500 dark:text-slate-400 text-[11px] sm:text-xs font-normal truncate max-w-[90px] sm:max-w-none shrink-0">
                                                            · {tx.recipient}
                                                        </span>
                                                    )}
                                                </div>
                                                <div className="text-[10px] sm:text-xs text-slate-400 flex items-center gap-1 min-w-0 mt-0.5">
                                                    <span className="font-mono truncate max-w-[75px] sm:max-w-none shrink-0">{tx.reference}</span>
                                                    <span className="shrink-0">•</span>
                                                    <span className="shrink-0 truncate">{tx.created_at}</span>
                                                </div>
                                            </div>
                                        </div>

                                        {/* Right Side: Amount & Status Badge */}
                                        <div className="flex flex-col items-end shrink-0 pl-2 text-right">
                                            <div className="font-extrabold text-xs sm:text-sm text-slate-900 dark:text-white font-mono leading-tight">
                                                ₦{Number(tx.amount_paid || tx.amount).toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                                            </div>
                                            <span
                                                className={cn(
                                                    'inline-flex items-center px-1.5 py-0.5 rounded text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wide mt-1',
                                                    tx.status === 'successful'
                                                        ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                                        : tx.status === 'pending'
                                                          ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400'
                                                          : 'bg-rose-500/10 text-rose-600 dark:text-rose-400'
                                                )}
                                            >
                                                {tx.status}
                                            </span>
                                        </div>
                                    </Link>
                                );
                            })}
                        </div>
                    ) : (
                        <div className="py-12 text-center space-y-3">
                            <div className="w-12 h-12 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-400 flex items-center justify-center mx-auto">
                                <Receipt className="w-6 h-6" />
                            </div>
                            <h3 className="text-sm font-bold text-slate-800 dark:text-slate-200">
                                No Transactions Found
                            </h3>
                            <p className="text-xs text-slate-400 max-w-sm mx-auto">
                                You haven't made any purchases matching your filter yet.
                            </p>
                        </div>
                    )}

                    {/* Pagination */}
                    {transactions.links && transactions.links.length > 3 && (
                        <div className="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2 overflow-x-auto no-scrollbar">
                            <div className="text-xs text-slate-400 shrink-0">
                                Page {transactions.current_page} of {transactions.last_page}
                            </div>
                            <div className="flex items-center gap-1 shrink-0">
                                {transactions.links.map((link, idx) => {
                                    if (!link.url) {
                                        return (
                                            <span
                                                key={idx}
                                                dangerouslySetInnerHTML={{ __html: link.label }}
                                                className="px-2.5 py-1 text-xs text-slate-300 dark:text-slate-600 pointer-events-none"
                                            />
                                        );
                                    }
                                    return (
                                        <Link
                                            key={idx}
                                            href={link.url}
                                            preserveState
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                            className={cn(
                                                'px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors',
                                                link.active
                                                    ? 'bg-primary text-white font-bold'
                                                    : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-zinc-800'
                                            )}
                                        />
                                    );
                                })}
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </>
    );
}
