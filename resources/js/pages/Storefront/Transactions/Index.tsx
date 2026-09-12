import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { dashboard } from '@/routes';
import { cn } from '@/lib/utils';
import {
    Search, Filter, ChevronLeft, ArrowRight, CheckCircle2,
    Clock, AlertCircle, Smartphone, Wifi, Receipt, RefreshCw
} from 'lucide-react';

interface TransactionItem {
    id: number;
    reference: string;
    service_type: string;
    recipient: string;
    amount: number;
    status: 'successful' | 'pending' | 'failed';
    created_at: string;
    date: string;
}

interface TransactionsIndexProps {
    transactions: {
        data: TransactionItem[];
        links: any[];
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

    return (
        <>
            <Head title="Transaction History" />

            {/* Mobile Header */}
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
                            Orders & Purchases
                        </h1>
                        <p className="text-xs text-slate-400 font-normal truncate mt-0.5">
                            Telecom service history & receipts
                        </p>
                    </div>
                </div>
            </div>

            {/* Main Content */}
            <div className="max-w-4xl mx-auto px-4 py-5 md:py-8 space-y-6">
                
                {/* Header Title on Desktop */}
                <div className="hidden md:flex justify-between items-center">
                    <div>
                        <h1 className="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                            Transaction History
                        </h1>
                        <p className="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            Track and view receipts for all your airtime, data, and bill payments.
                        </p>
                    </div>
                    <Link
                        href={dashboard().url}
                        className="text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-primary px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#181826] transition-colors"
                    >
                        Back to Dashboard
                    </Link>
                </div>

                {/* Filter & Search Bar */}
                <div className="bg-white dark:bg-[#181826] rounded-2xl border border-slate-100 dark:border-slate-800 p-4 shadow-xs space-y-3">
                    <div className="flex flex-col sm:flex-row gap-3">
                        {/* Search Input */}
                        <form onSubmit={handleSearch} className="relative flex-1">
                            <Search className="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                            <input
                                type="text"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Search by phone number or reference..."
                                className="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 dark:bg-zinc-900/60 border border-slate-200 dark:border-zinc-800 text-xs text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/40"
                            />
                        </form>

                        {/* Category Tabs */}
                        <div className="flex gap-1.5 overflow-x-auto no-scrollbar">
                            {[
                                { slug: 'all', label: 'All Services' },
                                { slug: 'airtime', label: 'Airtime' },
                                { slug: 'data', label: 'Data Plans' },
                            ].map((tab) => (
                                <button
                                    key={tab.slug}
                                    type="button"
                                    onClick={() => handleTypeChange(tab.slug)}
                                    className={cn(
                                        'px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors cursor-pointer',
                                        activeType === tab.slug
                                            ? 'bg-primary text-primary-foreground shadow-xs'
                                            : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200'
                                    )}
                                >
                                    {tab.label}
                                </button>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Transactions List */}
                <div className="bg-white dark:bg-[#181826] rounded-2xl border border-slate-100 dark:border-slate-800 p-4 sm:p-6 shadow-xs">
                    {transactions.data.length > 0 ? (
                        <div className="divide-y divide-slate-100 dark:divide-slate-800">
                            {transactions.data.map((tx) => {
                                const isAirtime = tx.service_type === 'airtime';
                                return (
                                    <Link
                                        key={tx.id}
                                        href={`/transactions/${tx.reference}`}
                                        className="py-3.5 flex items-center justify-between gap-3 hover:bg-slate-50/70 dark:hover:bg-zinc-900/40 px-2.5 rounded-xl transition-colors group"
                                    >
                                        <div className="flex items-center gap-3 min-w-0">
                                            <div
                                                className={cn(
                                                    'w-10 h-10 rounded-xl flex items-center justify-center shrink-0',
                                                    isAirtime
                                                        ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400'
                                                        : 'bg-blue-500/10 text-blue-600 dark:text-blue-400'
                                                )}
                                            >
                                                {isAirtime ? (
                                                    <Smartphone className="w-5 h-5" />
                                                ) : (
                                                    <Wifi className="w-5 h-5" />
                                                )}
                                            </div>

                                            <div className="min-w-0 space-y-0.5">
                                                <div className="font-bold text-slate-900 dark:text-white text-sm truncate flex items-center gap-2">
                                                    <span className="uppercase">{tx.service_type}</span>
                                                    <span className="font-mono text-slate-500 text-xs font-normal">
                                                        · {tx.recipient}
                                                    </span>
                                                </div>
                                                <div className="text-xs text-slate-400 truncate flex items-center gap-1.5">
                                                    <span className="font-mono">{tx.reference}</span>
                                                    <span>•</span>
                                                    <span>{tx.created_at}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div className="flex items-center gap-3 shrink-0">
                                            <div className="text-right space-y-1">
                                                <div className="font-extrabold text-sm text-slate-900 dark:text-white">
                                                    ₦{Number(tx.amount).toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                                                </div>
                                                <span
                                                    className={cn(
                                                        'inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-extrabold uppercase',
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

                                            <ArrowRight className="w-4 h-4 text-slate-300 group-hover:text-primary group-hover:translate-x-0.5 transition-all" />
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
                </div>
            </div>
        </>
    );
}
