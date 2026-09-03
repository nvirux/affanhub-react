import { useState, useEffect } from 'react';
import { Head, usePage, router, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import {
    Wifi, ChevronLeft, Check, Sparkles, AlertCircle, CheckCircle,
    Smartphone, Wallet, RefreshCw, Zap, Bell, Headphones, MoreVertical,
    ChevronDown, UserCheck, User, Users
} from 'lucide-react';

export default function DataPage() {
    const { auth, store, flash, errors } = usePage<any>().props;
    const user = auth?.user;
    const mainWallet = user?.wallets?.find((w: any) => w.type === 'main') || user?.wallet;

    const [networks, setNetworks] = useState<any[]>([]);
    const [dataTypes, setDataTypes] = useState<any[]>([]);
    const [dataPlans, setDataPlans] = useState<any[]>([]);
    const [selectedNetwork, setSelectedNetwork] = useState<string>('mtn');
    const [selectedValidityTab, setSelectedValidityTab] = useState<string>('daily');
    const [selectedPlanId, setSelectedPlanId] = useState<number | null>(null);

    const [phone, setPhone] = useState<string>('');
    const [networkDropdownOpen, setNetworkDropdownOpen] = useState<boolean>(false);
    const [isLoadingPlans, setIsLoadingPlans] = useState<boolean>(true);
    const [isSubmitting, setIsSubmitting] = useState<boolean>(false);

    // Auto-detect phone network prefix
    useEffect(() => {
        if (phone.length >= 4) {
            const prefix = phone.substring(0, 4);
            const mtnPrefixes = ['0803', '0806', '0703', '0706', '0813', '0816', '0810', '0814', '0903', '0906', '0913', '0916'];
            const airtelPrefixes = ['0802', '0808', '0708', '0812', '0701', '0902', '0901', '0904', '0907', '0912'];
            const gloPrefixes = ['0805', '0807', '0705', '0815', '0811', '0905', '0915'];
            const etisalatPrefixes = ['0809', '0817', '0818', '0909', '0908'];

            if (mtnPrefixes.includes(prefix)) setSelectedNetwork('mtn');
            else if (airtelPrefixes.includes(prefix)) setSelectedNetwork('airtel');
            else if (gloPrefixes.includes(prefix)) setSelectedNetwork('glo');
            else if (etisalatPrefixes.includes(prefix)) setSelectedNetwork('9mobile');
        }
    }, [phone]);

    // Fetch data plans from API endpoint
    useEffect(() => {
        setIsLoadingPlans(true);
        fetch('/vtu/data/plans')
            .then((res) => res.json())
            .then((data) => {
                if (data.success || data.networks) {
                    setNetworks(data.networks || []);
                    setDataTypes(data.data_types || []);
                    setDataPlans(data.plans || []);
                }
            })
            .catch((err) => console.error('Failed to load data plans:', err))
            .finally(() => setIsLoadingPlans(false));
    }, []);

    // Filtered selected plan
    const selectedPlan = dataPlans.find((p) => p.id === selectedPlanId);

    const handlePurchase = (e: React.FormEvent) => {
        e.preventDefault();
        if (!selectedPlanId || !phone || phone.length !== 11) return;

        setIsSubmitting(true);
        router.post('/vtu/data/purchase', {
            network: selectedNetwork,
            data_plan_id: selectedPlanId,
            phone: phone,
        }, {
            onFinish: () => setIsSubmitting(false),
            onSuccess: () => {
                setSelectedPlanId(null);
            }
        });
    };

    // Helper to get group slug for plan
    const getPlanGroup = (p: any) => {
        if (p.validity_group) return p.validity_group.toLowerCase();
        const val = (p.validity || '').toLowerCase();
        if (val.includes('year') || val.includes('365')) return 'yearly';
        if (val.includes('month') || val.includes('30 day') || val.includes('60 day')) return 'monthly';
        if (val.includes('week') || val.includes('7 day') || val.includes('14 day')) return 'weekly';
        return 'daily';
    };

    // Filter network plans for selected network
    const networkPlans = dataPlans.filter((p) => p.network_slug === selectedNetwork);

    // Calculate dynamically available tabs for selected network
    const hasHotPlans = networkPlans.some((p) => p.is_best_offer);
    const availableTabSlugs = new Set<string>();

    if (hasHotPlans) {
        availableTabSlugs.add('hot');
    }

    networkPlans.forEach((p) => {
        availableTabSlugs.add(getPlanGroup(p));
    });

    const ALL_TABS = [
        { slug: 'hot', label: 'HOT 🔥' },
        { slug: 'daily', label: 'Daily' },
        { slug: 'weekly', label: 'Weekly' },
        { slug: 'monthly', label: 'Monthly' },
        { slug: 'yearly', label: 'Yearly' },
    ];

    const availableTabs = ALL_TABS.filter((tab) => availableTabSlugs.has(tab.slug));

    // Auto-adjust active tab if selected tab is not available for current network
    useEffect(() => {
        if (availableTabs.length > 0 && !availableTabs.some((t) => t.slug === selectedValidityTab)) {
            setSelectedValidityTab(availableTabs[0].slug);
            setSelectedPlanId(null);
        }
    }, [selectedNetwork, availableTabs.length]);

    // Active filtered plans for grid
    const activePlans = networkPlans.filter((p) => {
        if (selectedValidityTab === 'hot') {
            return p.is_best_offer;
        }
        return getPlanGroup(p) === selectedValidityTab;
    });

    return (
        <>
            <Head title="Data - Cheap SME, Corporate & Direct Data plans" />

            {/* ────────────────────────────────────────────────────────
                MOBILE VIEW STICKY HEADER (flex md:hidden)
                ──────────────────────────────────────────────────────── */}
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
                            Data
                        </h1>
                        <p className="text-xs text-slate-400 dark:text-slate-400 font-normal truncate mt-0.5">
                            Cheap SME, Corporate & Direct Data plans...
                        </p>
                    </div>
                </div>

                <div className="flex items-center gap-2 shrink-0 ml-2">
                    <button type="button" className="p-1.5 text-slate-600 dark:text-slate-300 hover:text-primary transition-colors cursor-pointer" title="Customer Support">
                        <Headphones className="w-5 h-5 stroke-[1.8]" />
                    </button>
                    <button type="button" className="p-1.5 text-slate-600 dark:text-slate-300 hover:text-primary transition-colors cursor-pointer" title="Options">
                        <MoreVertical className="w-5 h-5 stroke-[1.8]" />
                    </button>
                </div>
            </div>

            {/* MAIN CONTENT CONTAINER */}
            <div className="max-w-2xl mx-auto px-4 py-4 md:py-6 w-full space-y-4">

                {/* Alert Banners */}
                {(flash?.error || errors?.data_plan_id || errors?.phone) && (
                    <div className="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 rounded-2xl p-4 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-2">
                        <AlertCircle className="w-4 h-4 shrink-0" />
                        <span>{flash?.error || errors?.data_plan_id || errors?.phone || 'Failed to process transaction.'}</span>
                    </div>
                )}
                {flash?.success && (
                    <div className="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl p-4 text-emerald-700 dark:text-emerald-300 text-xs font-semibold flex items-center gap-2">
                        <CheckCircle className="w-4 h-4 shrink-0" />
                        <span>{flash.success}</span>
                    </div>
                )}

                {isLoadingPlans ? (
                    <div className="py-16 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 flex flex-col items-center gap-2 bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-3xl p-6 shadow-xs">
                        <RefreshCw className="w-6 h-6 animate-spin text-primary" />
                        <span>Loading data packages...</span>
                    </div>
                ) : (
                    <form onSubmit={handlePurchase} className="space-y-4">

                        {/* 1. TOP CARD: Network & Phone Selector */}
                        <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-3xl p-4 sm:p-5 shadow-xs">
                            <div className="relative flex items-center bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700/80 rounded-2xl px-3 py-2 sm:py-2.5 focus-within:ring-2 focus-within:ring-primary focus-within:border-primary transition-all">
                                
                                {/* Network Selector Dropdown Trigger */}
                                <div className="relative shrink-0">
                                    <button
                                        type="button"
                                        onClick={() => setNetworkDropdownOpen(!networkDropdownOpen)}
                                        className="flex items-center gap-2 pr-3 border-r border-gray-200 dark:border-gray-700 cursor-pointer"
                                    >
                                        <span className={`w-8 h-8 rounded-full flex items-center justify-center text-[11px] font-black text-white shadow-2xs ${
                                            selectedNetwork === 'mtn' ? 'bg-amber-400 text-black' :
                                            selectedNetwork === 'airtel' ? 'bg-red-600' :
                                            selectedNetwork === 'glo' ? 'bg-emerald-600' : 'bg-green-700'
                                        }`}>
                                            {selectedNetwork.toUpperCase()}
                                        </span>
                                        <ChevronDown className="w-4 h-4 text-gray-400" />
                                    </button>

                                    {/* Network Popup Dropdown */}
                                    {networkDropdownOpen && (
                                        <>
                                            <div className="fixed inset-0 z-40" onClick={() => setNetworkDropdownOpen(false)} />
                                            <div className="absolute left-0 top-full mt-2 w-48 bg-white dark:bg-[#1c1c28] border border-gray-100 dark:border-gray-800 rounded-2xl shadow-xl z-50 p-2 space-y-1">
                                                {[
                                                    { slug: 'mtn', name: 'MTN', color: 'bg-amber-400 text-black' },
                                                    { slug: 'airtel', name: 'Airtel', color: 'bg-red-600 text-white' },
                                                    { slug: 'glo', name: 'Glo', color: 'bg-emerald-600 text-white' },
                                                    { slug: '9mobile', name: '9mobile', color: 'bg-green-700 text-white' },
                                                ].map((net) => (
                                                    <button
                                                        key={net.slug}
                                                        type="button"
                                                        onClick={() => {
                                                            setSelectedNetwork(net.slug);
                                                            setSelectedPlanId(null);
                                                            setNetworkDropdownOpen(false);
                                                        }}
                                                        className={`flex items-center gap-3 w-full px-3 py-2 rounded-xl text-xs font-bold transition-colors cursor-pointer ${
                                                            selectedNetwork === net.slug
                                                                ? 'bg-primary/10 text-primary'
                                                                : 'hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-200'
                                                        }`}
                                                    >
                                                        <span className={`w-6 h-6 rounded-full flex items-center justify-center text-[9px] font-black ${net.color}`}>
                                                            {net.name[0]}
                                                        </span>
                                                        <span>{net.name}</span>
                                                    </button>
                                                ))}
                                            </div>
                                        </>
                                    )}
                                </div>

                                {/* Phone Input */}
                                <input
                                    type="text"
                                    maxLength={11}
                                    value={phone}
                                    onChange={(e) => setPhone(e.target.value.replace(/\D/g, ''))}
                                    placeholder="090 2529 3759"
                                    className="w-full bg-transparent px-3 py-1 text-base font-bold text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none tracking-wide"
                                />

                                {/* Beneficiary Round Icon */}
                                <button
                                    type="button"
                                    onClick={() => setPhone(user?.phone || '09025293759')}
                                    className="w-9 h-9 rounded-full bg-primary/10 text-primary hover:bg-primary/20 transition-colors flex items-center justify-center shrink-0 cursor-pointer"
                                    title="Auto-fill phone number"
                                >
                                    <User className="w-5 h-5" />
                                </button>
                            </div>
                        </div>

                        {/* 2. BOTTOM CARD: Data Category Switcher & Package Cards */}
                        <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-3xl p-4 sm:p-5 shadow-xs space-y-4">
                            
                            {/* Segmented Control Pill Switcher (Dynamically rendered, hiding empty tabs) */}
                            {availableTabs.length > 0 && (
                                <div className="bg-slate-100/90 dark:bg-gray-900 p-1 rounded-2xl flex gap-1 overflow-x-auto no-scrollbar">
                                    {availableTabs.map((tab) => {
                                        const isActive = selectedValidityTab === tab.slug;
                                        return (
                                            <button
                                                key={tab.slug}
                                                type="button"
                                                onClick={() => {
                                                    setSelectedValidityTab(tab.slug);
                                                    setSelectedPlanId(null);
                                                }}
                                                className={`flex-1 py-2 px-3 text-xs font-black rounded-xl transition-all capitalize cursor-pointer whitespace-nowrap ${
                                                    isActive
                                                        ? 'bg-primary text-white shadow-sm'
                                                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white'
                                                }`}
                                            >
                                                {tab.label}
                                            </button>
                                        );
                                    })}
                                </div>
                            )}

                            {/* Data Package 2-Column Grid Cards */}
                            {activePlans.length === 0 ? (
                                <div className="py-8 text-center text-xs font-semibold text-gray-400 border border-dashed border-gray-200 dark:border-gray-800 rounded-2xl">
                                    No active data packages found for this selection.
                                </div>
                            ) : (
                                <div className="grid grid-cols-2 gap-3 sm:gap-4">
                                    {activePlans.map((plan) => {
                                        const isSelected = selectedPlanId === plan.id;
                                        return (
                                            <button
                                                key={plan.id}
                                                type="button"
                                                onClick={() => setSelectedPlanId(plan.id)}
                                                className={`rounded-2xl border text-left transition-all cursor-pointer overflow-hidden flex flex-col justify-between relative ${
                                                    isSelected
                                                        ? 'border-2 border-primary ring-2 ring-primary/20 shadow-md bg-white dark:bg-gray-900'
                                                        : 'border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-primary/40'
                                                }`}
                                            >
                                                {/* Top Half: Size, Type Badge & Subtitle */}
                                                <div className="p-3.5 sm:p-4">
                                                    <div className="flex items-center gap-1.5 flex-wrap">
                                                        <span className="text-base sm:text-lg font-black text-gray-900 dark:text-white leading-tight">
                                                            {plan.name}
                                                        </span>
                                                        <span className="text-[10px] font-extrabold text-primary bg-primary/10 px-1.5 py-0.5 rounded-md uppercase tracking-tight">
                                                            [{plan.data_type_name || 'DATA'}]
                                                        </span>
                                                        {plan.is_best_offer && (
                                                            <span className="text-[9px] font-black text-white bg-rose-500 px-1.5 py-0.5 rounded-md flex items-center gap-0.5 shadow-2xs">
                                                                🔥 Best Offer
                                                            </span>
                                                        )}
                                                    </div>
                                                    <p className="text-[11px] font-medium text-slate-400 dark:text-slate-400 mt-1">
                                                        Standard plan
                                                    </p>
                                                </div>

                                                {/* Bottom Half (Light Blue / Primary Soft Tint background) */}
                                                <div className="px-3.5 py-2.5 sm:px-4 sm:py-2.5 bg-primary/5 dark:bg-primary/10 border-t border-gray-100 dark:border-gray-800/60 flex items-center justify-between">
                                                    <div>
                                                        <span className="text-[9px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider block">PRICE</span>
                                                        <span className="text-xs sm:text-sm font-black text-primary">₦{Number(plan.price).toLocaleString()}</span>
                                                    </div>
                                                    <div className="text-right">
                                                        <span className="text-[9px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider block">VALIDITY</span>
                                                        <span className="text-xs sm:text-sm font-bold text-primary">{plan.validity || '24 hours'}</span>
                                                    </div>
                                                </div>
                                            </button>
                                        );
                                    })}
                                </div>
                            )}

                            {/* Purchase Submit Button */}
                            {selectedPlan && (
                                <div className="space-y-3 pt-2">
                                    <div className="bg-gray-50 dark:bg-gray-900/60 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 flex items-center justify-between text-xs">
                                        <div>
                                            <span className="text-gray-400 block font-semibold">Total Amount</span>
                                            <span className="text-base font-black text-gray-900 dark:text-white">
                                                ₦{Number(selectedPlan.price).toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                                            </span>
                                        </div>
                                        <div className="text-right">
                                            <span className="text-gray-400 block font-semibold">Selected Plan</span>
                                            <span className="font-bold text-primary">{selectedPlan.network_name} {selectedPlan.name}</span>
                                        </div>
                                    </div>

                                    <button
                                        type="submit"
                                        disabled={isSubmitting || !selectedPlanId || phone.length !== 11}
                                        className="w-full py-4 rounded-2xl bg-primary text-white text-sm font-extrabold flex items-center justify-center gap-2 shadow-lg shadow-primary/25 hover:opacity-95 active:scale-[0.99] transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                                    >
                                        {isSubmitting ? (
                                            <span>Processing Transaction...</span>
                                        ) : (
                                            <>
                                                <Zap className="w-4 h-4" />
                                                <span>Confirm & Recharge Data Bundle</span>
                                            </>
                                        )}
                                    </button>
                                </div>
                            )}
                        </div>
                    </form>
                )}
            </div>
        </>
    );
}

DataPage.layout = {
    breadcrumbs: [
        {
            title: 'Data',
            href: '/vtu/data',
        },
    ],
};
