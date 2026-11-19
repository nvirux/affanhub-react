import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { dashboard } from '@/routes';
import { cn } from '@/lib/utils';
import {
    Search, Filter, ChevronLeft, ArrowRight, CheckCircle2,
    Clock, AlertCircle, Smartphone, Wifi, Receipt, RefreshCw,
    ShieldCheck, Zap, Tv, ChevronDown, Briefcase, GraduationCap
} from 'lucide-react';
import cacIcon from '@/assets/icons/cac.png';
import nimcIcon from '@/assets/icons/nimc.png';
import nibssIcon from '@/assets/icons/nibss.png';
import verificationIcon from '@/assets/icons/verification.png';
import mtnIcon from '@/assets/icons/mtn.png';
import airtelIcon from '@/assets/icons/airtel.png';
import gloIcon from '@/assets/icons/glo.png';
import nineMobileIcon from '@/assets/icons/9mobile.png';

const NETWORK_ICONS: Record<string, string> = {
    mtn: mtnIcon,
    airtel: airtelIcon,
    glo: gloIcon,
    '9mobile': nineMobileIcon,
};

const detectNetworkFromPhone = (phone?: string): 'mtn' | 'airtel' | 'glo' | '9mobile' | null => {
    if (!phone) return null;
    const digits = phone.replace(/\D/g, '');
    const normalized = digits.startsWith('234') && digits.length >= 13 ? '0' + digits.slice(3) : digits;

    const mtnPrefixes = ['0803', '0806', '0703', '0706', '0813', '0816', '0810', '0814', '0903', '0906', '0913', '0916', '0704', '07025', '07026'];
    const airtelPrefixes = ['0802', '0808', '0812', '0701', '0708', '0902', '0907', '0901', '0912', '0904'];
    const gloPrefixes = ['0805', '0807', '0705', '0815', '0811', '0905', '0915'];
    const nineMobilePrefixes = ['0809', '0817', '0818', '0909', '0908'];

    if (mtnPrefixes.some(p => normalized.startsWith(p))) return 'mtn';
    if (airtelPrefixes.some(p => normalized.startsWith(p))) return 'airtel';
    if (gloPrefixes.some(p => normalized.startsWith(p))) return 'glo';
    if (nineMobilePrefixes.some(p => normalized.startsWith(p))) return '9mobile';
    return null;
};

interface TransactionItem {
    id: number;
    reference: string;
    service_type: string;
    recipient: string;
    amount: number;
    discount?: number;
    amount_paid?: number;
    status: 'successful' | 'pending' | 'failed';
    created_at: string;
    date: string;
    api_response?: any;
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

    const getTransactionMeta = (tx: any) => {
        const rawServiceType = (tx.service_type || '').toLowerCase();
        const api = tx.api_response || {};

        let network: 'mtn' | 'airtel' | 'glo' | '9mobile' | null = null;
        const netRaw = (api.network || '').toString().toLowerCase();
        if (netRaw.includes('mtn')) network = 'mtn';
        else if (netRaw.includes('airtel')) network = 'airtel';
        else if (netRaw.includes('glo')) network = 'glo';
        else if (netRaw.includes('9mobile') || netRaw.includes('etisalat')) network = '9mobile';
        else {
            network = detectNetworkFromPhone(tx.recipient || api.phone || api.sender_phone);
        }

        if (rawServiceType === 'airtime') {
            const netLabel = network ? network.toUpperCase() : 'Airtime';
            return {
                type: 'telco' as const,
                network,
                title: network ? `${netLabel} Airtime` : 'Airtime Topup',
                subtitle: `${tx.recipient || tx.reference} • ${tx.created_at}`,
            };
        }

        if (rawServiceType === 'data') {
            const netLabel = network ? network.toUpperCase() : 'Data';
            const planName = api.plan_name || api.data_plan || api.size || api.plan || '';
            const titlePlan = planName ? `${netLabel} ${planName}` : `${netLabel} Data`;
            return {
                type: 'telco' as const,
                network,
                title: titlePlan,
                subtitle: `${tx.recipient || tx.reference} • ${tx.created_at}`,
            };
        }

        if (rawServiceType.includes('cac')) {
            const packageTitle = api.package_title || 'CAC Registration';
            const businessName = api.business_name || tx.recipient;
            return {
                type: 'cac' as const,
                network: null,
                title: packageTitle,
                subtitle: businessName ? `${businessName} • ${tx.created_at}` : tx.created_at,
            };
        }

        if (rawServiceType.includes('modification')) {
            const isBvn = rawServiceType.includes('bvn');
            const serviceTitle = api.service_title ? `${isBvn ? 'BVN' : 'NIN'} Mod (${api.service_title})` : `${isBvn ? 'BVN' : 'NIN'} Modification`;
            const idNum = api.identifier || tx.recipient;
            return {
                type: 'identity_mod' as const,
                network: null,
                title: serviceTitle,
                subtitle: idNum ? `${idNum} • ${tx.created_at}` : tx.created_at,
            };
        }

        if (rawServiceType.includes('verification')) {
            const isBvn = rawServiceType.includes('bvn');
            const slipName = api.slip_name ? `${isBvn ? 'BVN' : 'NIN'} (${api.slip_name})` : `${isBvn ? 'BVN' : 'NIN'} Verification`;
            return {
                type: 'identity_verify' as const,
                network: null,
                title: slipName,
                subtitle: `${tx.recipient || tx.reference} • ${tx.created_at}`,
            };
        }

        if (rawServiceType.includes('airtime_to_cash') || rawServiceType.includes('airtime_cash')) {
            const netLabel = (api.network || network || 'Airtime').toString().toUpperCase();
            return {
                type: 'airtime_cash' as const,
                network,
                title: `Airtime to Cash - ${netLabel}`,
                subtitle: `${tx.recipient || tx.reference} • ${tx.created_at}`,
            };
        }

        if (rawServiceType.includes('electricity') || rawServiceType.includes('power')) {
            const disco = api.disco || api.provider || 'Electricity';
            return {
                type: 'electricity' as const,
                network: null,
                title: disco,
                subtitle: `${tx.recipient || tx.reference} • ${tx.created_at}`,
            };
        }

        if (rawServiceType.includes('cable') || rawServiceType.includes('tv')) {
            const provider = api.provider || api.cable_name || 'Cable TV';
            return {
                type: 'cable' as const,
                network: null,
                title: provider,
                subtitle: `${tx.recipient || tx.reference} • ${tx.created_at}`,
            };
        }

        if (rawServiceType.includes('education') || rawServiceType.includes('exam')) {
            const exam = api.exam_type || api.service || 'Exam PIN';
            return {
                type: 'education' as const,
                network: null,
                title: exam,
                subtitle: `${tx.recipient || tx.reference} • ${tx.created_at}`,
            };
        }

        return {
            type: 'default' as const,
            network: null,
            title: (tx.service_type || 'Transaction').replace(/_/g, ' ').replace(/\b\w/g, (l: string) => l.toUpperCase()),
            subtitle: `${tx.recipient || tx.reference} • ${tx.created_at}`,
        };
    };

    const renderTxIcon = (meta: ReturnType<typeof getTransactionMeta>) => {
        if (meta.type === 'telco' && meta.network && NETWORK_ICONS[meta.network]) {
            const bgMap: Record<string, string> = {
                mtn: 'bg-[#FFCC00]',
                airtel: 'bg-[#E60000]',
                glo: 'bg-[#00843D]',
                '9mobile': 'bg-[#00573D]',
            };
            return (
                <div className={cn("w-10 h-10 sm:w-11 sm:h-11 rounded-full flex items-center justify-center p-2 shrink-0 shadow-xs", bgMap[meta.network] || 'bg-slate-100')}>
                    <img src={NETWORK_ICONS[meta.network]} alt={meta.network} className="w-full h-full object-contain" />
                </div>
            );
        }

        if (meta.type === 'cac') {
            return (
                <div className="w-10 h-10 sm:w-11 sm:h-11 rounded-full overflow-hidden flex items-center justify-center shrink-0 border border-emerald-500/30 shadow-xs bg-emerald-50 dark:bg-emerald-950/40">
                    <img src={cacIcon} alt="CAC" className="w-full h-full object-cover scale-125 select-none" />
                </div>
            );
        }

        if (meta.type === 'identity_mod' || meta.type === 'identity_verify') {
            const isBvn = meta.title.toLowerCase().includes('bvn');
            return (
                <div className="w-10 h-10 sm:w-11 sm:h-11 rounded-full overflow-hidden flex items-center justify-center shrink-0 border border-blue-500/30 shadow-xs bg-blue-50 dark:bg-blue-950/40">
                    <img src={isBvn ? nibssIcon : nimcIcon} alt={isBvn ? "BVN" : "NIN"} className="w-full h-full object-cover scale-130 select-none" />
                </div>
            );
        }

        if (meta.type === 'airtime_cash') {
            return (
                <div className="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 border border-amber-500/20">
                    <RefreshCw className="w-5 h-5 stroke-[2]" />
                </div>
            );
        }

        if (meta.type === 'electricity') {
            return (
                <div className="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0 border border-amber-500/20">
                    <Zap className="w-5 h-5 stroke-[2]" />
                </div>
            );
        }

        if (meta.type === 'cable') {
            return (
                <div className="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 border border-purple-500/20">
                    <Tv className="w-5 h-5 stroke-[2]" />
                </div>
            );
        }

        if (meta.type === 'education') {
            return (
                <div className="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-500/20">
                    <GraduationCap className="w-5 h-5 stroke-[2]" />
                </div>
            );
        }

        return (
            <div className="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-slate-500/10 text-slate-600 dark:text-slate-400 flex items-center justify-center shrink-0 border border-slate-500/20">
                <Receipt className="w-5 h-5 stroke-[2]" />
            </div>
        );
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
                                const meta = getTransactionMeta(tx);
                                return (
                                    <Link
                                        key={tx.id}
                                        href={`/transactions/${tx.reference}`}
                                        className="w-full py-3 px-2 sm:px-3 flex items-center justify-between gap-2.5 sm:gap-4 hover:bg-slate-50/70 dark:hover:bg-zinc-900/40 rounded-xl transition-colors group min-w-0"
                                    >
                                        {/* Left Side: Service Icon & Info */}
                                        <div className="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1">
                                            {renderTxIcon(meta)}

                                            <div className="min-w-0 flex-1">
                                                <div className="flex items-center gap-1.5 min-w-0">
                                                    <span className="font-bold text-slate-900 dark:text-white text-xs sm:text-sm truncate group-hover:text-primary transition-colors">
                                                        {meta.title}
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
                                                ₦{Number(tx.amount).toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                                            </div>
                                            {Number(tx.discount) > 0 && (
                                                <span className="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold leading-tight mt-0.5">
                                                    Saved ₦{Number(tx.discount).toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                                                </span>
                                            )}
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
