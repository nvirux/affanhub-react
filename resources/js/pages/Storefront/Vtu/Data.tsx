import { useState, useEffect } from 'react';
import { Head, usePage, router, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import {
    Wifi, ChevronLeft, Check, Sparkles, AlertCircle, CheckCircle,
    Smartphone, Wallet, RefreshCw, Zap, Bell, Headphones, MoreVertical,
    ChevronDown, UserCheck, User, Users
} from 'lucide-react';
import { ConfirmPaymentSheet } from '@/components/confirm-payment-sheet';
import { TransactionPinSheet } from '@/components/transaction-pin-sheet';
import { PhoneNetworkCard, NETWORK_ICONS, NETWORKS_LIST } from '@/components/phone-network-card';

export default function DataPage() {
    const { auth, store, flash, errors } = usePage<any>().props;
    const user = auth?.user;

    const [networks, setNetworks] = useState<any[]>([]);
    const [dataTypes, setDataTypes] = useState<any[]>([]);
    const [dataPlans, setDataPlans] = useState<any[]>([]);
    const [selectedNetwork, setSelectedNetwork] = useState<string>('mtn');
    const [selectedValidityTab, setSelectedValidityTab] = useState<string>('daily');
    const [selectedPlanId, setSelectedPlanId] = useState<number | null>(null);

    const [phone, setPhone] = useState<string>('');
    const [phoneError, setPhoneError] = useState<string | null>(null);
    const [isLoadingPlans, setIsLoadingPlans] = useState<boolean>(true);
    const [isSubmitting, setIsSubmitting] = useState<boolean>(false);
    const [isConfirmOpen, setIsConfirmOpen] = useState<boolean>(false);
    const [isPinSheetOpen, setIsPinSheetOpen] = useState<boolean>(false);
    const [pinError, setPinError] = useState<string | null>(null);

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

    const handleSelectPlan = (plan: any) => {
        setSelectedPlanId(plan.id);
        if (!phone || phone.trim() === '') {
            setPhoneError('Please enter recipient phone number');
            document.getElementById('phone-input')?.focus();
            return;
        }
        if (phone.length !== 11) {
            setPhoneError('Phone number must be 11 digits');
            document.getElementById('phone-input')?.focus();
            return;
        }
        setPhoneError(null);
        setIsConfirmOpen(true);
    };

    const handleOpenPinSheet = (e?: React.FormEvent) => {
        if (e) e.preventDefault();
        if (!selectedPlanId || !phone || phone.length !== 11) return;

        setIsConfirmOpen(false);
        setPinError(null);
        setIsPinSheetOpen(true);
    };

    const handlePinSubmit = (pin: string) => {
        setIsSubmitting(true);
        setPinError(null);

        router.post(
            '/vtu/data/purchase',
            {
                network: selectedNetwork,
                data_plan_id: selectedPlanId,
                phone: phone,
                transaction_pin: pin,
            },
            {
                preserveScroll: true,
                onFinish: () => setIsSubmitting(false),
                onSuccess: () => {
                    setSelectedPlanId(null);
                    setIsPinSheetOpen(false);
                    setPhoneError(null);
                },
                onError: (errs: any) => {
                    const msg = errs.message || errs.transaction_pin || errs.phone || 'Data purchase failed.';
                    if (msg.toLowerCase().includes('pin')) {
                        setPinError(msg);
                    } else {
                        setIsPinSheetOpen(false);
                    }
                },
            }
        );
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
                    <Link
                        href="/contact"
                        className="p-1.5 text-slate-600 dark:text-slate-300 hover:text-primary transition-colors cursor-pointer"
                        title="Customer Support"
                    >
                        <Headphones className="w-5 h-5 stroke-[1.8]" />
                    </Link>
                    <button type="button" className="p-1.5 text-slate-600 dark:text-slate-300 hover:text-primary transition-colors cursor-pointer" title="Options">
                        <MoreVertical className="w-5 h-5 stroke-[1.8]" />
                    </button>
                </div>
            </div>

            {/* MAIN CONTENT CONTAINER */}
            <div className="max-w-2xl mx-auto px-4 py-4 md:py-6 w-full space-y-4">

                {/* Alert Banners */}
                {(flash?.error || errors?.data_plan_id || errors?.phone) && (
                    <div className="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 rounded-2xl p-4 text-rose-700 dark:text-rose-300 text-xs font-semibold flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div className="flex items-center gap-2">
                            <AlertCircle className="w-4 h-4 shrink-0 text-rose-500" />
                            <span>{flash?.error || errors?.data_plan_id || errors?.phone || 'Failed to process transaction.'}</span>
                        </div>
                        <Link
                            href="/contact"
                            className="inline-flex items-center gap-1 text-[11px] font-bold text-rose-700 dark:text-rose-300 bg-rose-100 dark:bg-rose-500/20 px-3 py-1.5 rounded-lg hover:bg-rose-200 transition-colors w-fit shrink-0"
                        >
                            <Headphones className="w-3.5 h-3.5" />
                            <span>Contact Support</span>
                        </Link>
                    </div>
                )}
                {flash?.success && (
                    <div className="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl p-4 text-emerald-700 dark:text-emerald-300 text-xs font-semibold flex items-center gap-2">
                        <CheckCircle className="w-4 h-4 shrink-0" />
                        <span>{flash.success}</span>
                    </div>
                )}

                {isLoadingPlans ? (
                    <div className="py-16 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 flex flex-col items-center gap-2 bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-xs">
                        <RefreshCw className="w-6 h-6 animate-spin text-primary" />
                        <span>Loading data packages...</span>
                    </div>
                ) : (
                    <div className="space-y-4">

                        {/* 1. Network & Phone Selector Card (Component) */}
                        <PhoneNetworkCard
                            phone={phone}
                            setPhone={setPhone}
                            selectedNetwork={selectedNetwork}
                            setSelectedNetwork={setSelectedNetwork}
                            phoneError={phoneError}
                            setPhoneError={setPhoneError}
                            userPhone={user?.phone}
                            onNetworkChange={() => setSelectedPlanId(null)}
                        />

                        {/* 2. Data Category Switcher & Package Cards (Standalone) */}
                        <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs space-y-4">
                            
                            {/* Segmented Control Pill Switcher (Dynamically rendered, hiding empty tabs) */}
                            {availableTabs.length > 0 && (
                                <div className="bg-slate-100/90 dark:bg-gray-900 p-1 rounded-xl flex gap-1 overflow-x-auto no-scrollbar">
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
                                    {activePlans.map((plan) => (
                                        <button
                                            key={plan.id}
                                            type="button"
                                            onClick={() => handleSelectPlan(plan)}
                                            className="rounded-xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-primary/40 text-left transition-all cursor-pointer overflow-hidden flex flex-col justify-between relative"
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
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>
                )}
            </div>

            {/* ── CONFIRM TO PAY BOTTOM SHEET MODAL (REUSABLE COMPONENT) ── */}
            {selectedPlan && (
                <ConfirmPaymentSheet
                    isOpen={isConfirmOpen}
                    onOpenChange={setIsConfirmOpen}
                    title="Confirm to Pay"
                    amount={Number(selectedPlan.price)}
                    isSubmitting={isSubmitting}
                    confirmButtonText="Confirm & Recharge Data Bundle"
                    onConfirm={handleOpenPinSheet}
                    details={[
                        {
                            label: 'Network',
                            value: (
                                <div className="flex items-center gap-1.5 font-bold text-gray-900 dark:text-white">
                                    {NETWORK_ICONS[selectedPlan.network_slug || selectedNetwork] && (
                                        <img
                                            src={NETWORK_ICONS[selectedPlan.network_slug || selectedNetwork]}
                                            alt={selectedPlan.network_name || selectedNetwork}
                                            className="w-3.5 h-3.5 rounded-full object-contain bg-white"
                                        />
                                    )}
                                    <span className="uppercase text-xs">{selectedPlan.network_name || selectedNetwork}</span>
                                </div>
                            ),
                        },
                        {
                            label: 'Plan',
                            value: (
                                <div className="flex items-center justify-end gap-1.5 flex-wrap">
                                    <span className="font-extrabold text-gray-900 dark:text-white text-xs">
                                        {selectedPlan.name}
                                    </span>
                                    <span className="text-[9px] font-bold text-primary bg-primary/10 px-1.5 py-0.5 rounded uppercase">
                                        {selectedPlan.data_type_name || 'DATA'}
                                    </span>
                                </div>
                            ),
                            subtitle: selectedPlan.validity || '30 days',
                        },
                        {
                            label: 'Recipient',
                            value: (
                                <div className="flex items-center gap-1 font-black text-gray-900 dark:text-white font-mono text-xs">
                                    <Smartphone className="w-3 h-3 text-primary" />
                                    <span>{phone.replace(/(\d{3})(\d{4})(\d{4})/, '$1 $2 $3')}</span>
                                </div>
                            ),
                        },
                    ]}
                />
            )}

            {/* Transaction PIN Verification Sheet */}
            {selectedPlan && (
                <TransactionPinSheet
                    isOpen={isPinSheetOpen}
                    onOpenChange={setIsPinSheetOpen}
                    title="Authorize Data Purchase"
                    description="Enter your 4-digit Transaction PIN to complete purchase"
                    summary={
                        <div className="flex items-center justify-between text-xs">
                            <span className="text-muted-foreground">{selectedPlan.network_name || selectedNetwork.toUpperCase()} {selectedPlan.name}</span>
                            <span className="font-bold text-foreground">₦{Number(selectedPlan.price).toLocaleString()} &rarr; {phone}</span>
                        </div>
                    }
                    isSubmitting={isSubmitting}
                    error={pinError}
                    onClearError={() => setPinError(null)}
                    onSubmitPin={handlePinSubmit}
                />
            )}
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

