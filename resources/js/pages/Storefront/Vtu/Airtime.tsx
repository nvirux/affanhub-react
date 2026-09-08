import { useState } from 'react';
import { Head, usePage, Link, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import {
    ChevronLeft, Headphones, MoreVertical, Smartphone,
    Zap, AlertCircle, CheckCircle
} from 'lucide-react';
import { PhoneNetworkCard, NETWORK_ICONS } from '@/components/phone-network-card';
import { ConfirmPaymentSheet } from '@/components/confirm-payment-sheet';

const AIRTIME_PRESETS = [
    { amount: 100, label: '₦100' },
    { amount: 200, label: '₦200' },
    { amount: 500, label: '₦500' },
    { amount: 1000, label: '₦1,000' },
    { amount: 2000, label: '₦2,000' },
    { amount: 5000, label: '₦5,000' },
];

export interface NetworkConfig {
    id: number;
    name: string;
    slug: string;
    discount: number;
    min_amount: number;
    max_amount: number;
    is_enabled?: boolean;
}

interface AirtimePageProps {
    networks?: NetworkConfig[];
    wallet_balance?: number;
}

export default function AirtimePage({ networks = [], wallet_balance }: AirtimePageProps) {
    const { auth, flash, errors } = usePage<any>().props;
    const user = auth?.user;
    const mainWallet = user?.wallets?.find((w: any) => w.type === 'main') || user?.wallet;
    const userWalletBalance = wallet_balance !== undefined ? wallet_balance : Number(mainWallet?.balance || 0);

    const [selectedNetwork, setSelectedNetwork] = useState<string>('mtn');
    const [phone, setPhone] = useState<string>('');
    const [phoneError, setPhoneError] = useState<string | null>(null);

    const [customAmount, setCustomAmount] = useState<string>('500');
    const [amountError, setAmountError] = useState<string | null>(null);

    const [isConfirmOpen, setIsConfirmOpen] = useState<boolean>(false);
    const [isSubmitting, setIsSubmitting] = useState<boolean>(false);
    const [statusMessage, setStatusMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    // Resolve current network discount and min/max limits
    const currentNetworkObj: NetworkConfig = networks.find(
        (n) => n.slug.toLowerCase() === selectedNetwork.toLowerCase()
    ) || {
        id: 1,
        name: selectedNetwork.toUpperCase(),
        slug: selectedNetwork.toLowerCase(),
        discount: 1.5,
        min_amount: 50,
        max_amount: 50000,
    };

    const discountPercent = Number(currentNetworkObj.discount ?? 1.5);
    const minAmount = Number(currentNetworkObj.min_amount ?? 50);
    const maxAmount = Number(currentNetworkObj.max_amount ?? 50000);

    // Calculate payable amount after discount
    const currentAmount = Number(customAmount) || 0;
    const discountAmount = (currentAmount * discountPercent) / 100;
    const payableAmount = Math.max(0, currentAmount - discountAmount);

    const validatePhone = (): boolean => {
        if (!phone || phone.trim() === '') {
            setPhoneError('Please enter recipient phone number');
            document.getElementById('phone-input')?.focus();
            return false;
        }
        if (phone.length !== 11) {
            setPhoneError('Phone number must be 11 digits');
            document.getElementById('phone-input')?.focus();
            return false;
        }
        setPhoneError(null);
        return true;
    };

    // Tapping a preset amount validates phone and immediately opens the Confirm modal
    const handleSelectPreset = (val: number) => {
        setCustomAmount(String(val));
        setAmountError(null);
        setStatusMessage(null);

        if (!validatePhone()) {
            return;
        }

        setIsConfirmOpen(true);
    };

    const handleCustomAmountChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const raw = e.target.value.replace(/\D/g, '');
        setCustomAmount(raw);
        setStatusMessage(null);
        const num = Number(raw);

        if (num > 0 && num < minAmount) {
            setAmountError(`Minimum recharge amount is ₦${minAmount.toLocaleString()}`);
        } else if (num > maxAmount) {
            setAmountError(`Maximum recharge amount is ₦${maxAmount.toLocaleString()}`);
        } else {
            setAmountError(null);
        }
    };

    // Small submit button on the custom amount input
    const handleCustomSubmit = () => {
        setStatusMessage(null);
        if (!validatePhone()) {
            return;
        }
        if (!currentAmount || currentAmount < minAmount) {
            setAmountError(`Minimum recharge amount is ₦${minAmount.toLocaleString()}`);
            return;
        }
        if (currentAmount > maxAmount) {
            setAmountError(`Maximum recharge amount is ₦${maxAmount.toLocaleString()}`);
            return;
        }

        setAmountError(null);
        setIsConfirmOpen(true);
    };

    const handleConfirmRecharge = () => {
        if (!validatePhone()) {
            return;
        }
        if (!currentAmount || currentAmount < minAmount || currentAmount > maxAmount) {
            return;
        }

        setIsSubmitting(true);
        setStatusMessage(null);

        router.post(
            '/vtu/airtime/purchase',
            {
                network_id: currentNetworkObj.id,
                amount: currentAmount,
                phone: phone,
            },
            {
                preserveScroll: true,
                onFinish: () => {
                    setIsSubmitting(false);
                },
                onSuccess: () => {
                    setIsConfirmOpen(false);
                    setPhoneError(null);
                    setStatusMessage({
                        type: 'success',
                        text: `₦${currentAmount.toLocaleString()} Airtime recharge for ${phone} was successful!`,
                    });
                },
                onError: (pageErrors: any) => {
                    setIsConfirmOpen(false);
                    const msg = pageErrors.message || pageErrors.amount || pageErrors.phone || 'Airtime recharge failed.';
                    setStatusMessage({
                        type: 'error',
                        text: msg,
                    });
                },
            }
        );
    };

    return (
        <>
            <Head title="Airtime - Instant Airtime Recharge" />

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
                            Airtime
                        </h1>
                        <p className="text-xs text-slate-400 dark:text-slate-400 font-normal truncate mt-0.5">
                            Instant top-up for all networks...
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

                {/* Status Messages */}
                {statusMessage && (
                    <div
                        className={`rounded-2xl p-4 text-xs font-semibold flex items-center gap-2 ${
                            statusMessage.type === 'success'
                                ? 'bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-300'
                                : 'bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-700 dark:text-rose-300'
                        }`}
                    >
                        {statusMessage.type === 'success' ? (
                            <CheckCircle className="w-4 h-4 shrink-0" />
                        ) : (
                            <AlertCircle className="w-4 h-4 shrink-0" />
                        )}
                        <span>{statusMessage.text}</span>
                    </div>
                )}

                {/* Server Flash & Error Banners */}
                {(flash?.error || errors?.phone || errors?.amount) && !statusMessage && (
                    <div className="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 rounded-2xl p-4 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-2">
                        <AlertCircle className="w-4 h-4 shrink-0" />
                        <span>{flash?.error || errors?.phone || errors?.amount || 'Failed to process transaction.'}</span>
                    </div>
                )}
                {flash?.success && !statusMessage && (
                    <div className="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl p-4 text-emerald-700 dark:text-emerald-300 text-xs font-semibold flex items-center gap-2">
                        <CheckCircle className="w-4 h-4 shrink-0" />
                        <span>{flash.success}</span>
                    </div>
                )}

                <div className="space-y-4">

                    {/* 1. Phone & Network Selector Card (Reusable Component) */}
                    <PhoneNetworkCard
                        phone={phone}
                        setPhone={setPhone}
                        selectedNetwork={selectedNetwork}
                        setSelectedNetwork={setSelectedNetwork}
                        phoneError={phoneError}
                        setPhoneError={setPhoneError}
                        userPhone={user?.phone}
                        onNetworkChange={() => setStatusMessage(null)}
                    />

                    {/* 2. Amount Selection Card (Standalone) */}
                    <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs space-y-4">
                        
                        <div className="flex items-center justify-between">
                            <h2 className="text-xs sm:text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider">
                                Select Amount
                            </h2>
                            {discountPercent > 0 && (
                                <span className="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 rounded-full">
                                    {discountPercent}% Discount
                                </span>
                            )}
                        </div>

                        {/* Quick Presets 3x2 Grid (Tapping immediately validates phone & opens Confirm to Pay modal) */}
                        <div className="grid grid-cols-3 gap-2.5 sm:gap-3">
                            {AIRTIME_PRESETS.map((item) => {
                                const discounted = item.amount - (item.amount * discountPercent) / 100;
                                return (
                                    <button
                                        key={item.amount}
                                        type="button"
                                        onClick={() => handleSelectPreset(item.amount)}
                                        className="p-3 rounded-xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-primary/40 text-gray-900 dark:text-white text-center transition-all cursor-pointer flex flex-col items-center justify-center"
                                    >
                                        <span className="text-base sm:text-lg font-black leading-tight">
                                            {item.label}
                                        </span>
                                        <span className="text-[10px] font-bold text-slate-400 dark:text-slate-500 mt-0.5">
                                            Pay ₦{discounted.toLocaleString()}
                                        </span>
                                    </button>
                                );
                            })}
                        </div>

                        {/* Custom Amount Input Field with Small Action Button */}
                        <div className="space-y-1.5 pt-1">
                            <label htmlFor="custom-amount" className="text-xs font-bold text-slate-500 dark:text-slate-400">
                                Or Enter Custom Amount (₦{minAmount.toLocaleString()} - ₦{maxAmount.toLocaleString()})
                            </label>
                            <div className={`relative flex items-center bg-gray-50/70 dark:bg-gray-900/70 border rounded-xl pl-3.5 pr-1.5 py-1.5 transition-all ${
                                amountError
                                    ? 'border-rose-400 dark:border-rose-500 ring-2 ring-rose-400/20'
                                    : 'border-gray-200 dark:border-gray-700 focus-within:border-primary'
                            }`}>
                                <span className="text-base font-black text-gray-500 dark:text-gray-400 mr-2">₦</span>
                                <input
                                    id="custom-amount"
                                    type="text"
                                    inputMode="numeric"
                                    value={customAmount}
                                    onChange={handleCustomAmountChange}
                                    onKeyDown={(e) => {
                                        if (e.key === 'Enter') {
                                            handleCustomSubmit();
                                        }
                                    }}
                                    placeholder="500"
                                    className="w-full bg-transparent text-base font-bold text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none tracking-wide"
                                />

                                {/* Small Action Button close to the custom input */}
                                <button
                                    type="button"
                                    onClick={handleCustomSubmit}
                                    disabled={!currentAmount || currentAmount < minAmount || currentAmount > maxAmount}
                                    className="shrink-0 px-3.5 py-2 rounded-lg bg-primary hover:bg-primary/90 text-white text-xs font-extrabold flex items-center gap-1 shadow-xs transition-all disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer"
                                >
                                    <Zap className="w-3.5 h-3.5 fill-white" />
                                    <span>Pay</span>
                                </button>
                            </div>

                            {amountError && (
                                <p className="text-xs font-bold text-rose-500 dark:text-rose-400 flex items-center gap-1 px-1">
                                    <AlertCircle className="w-3.5 h-3.5 shrink-0" />
                                    <span>{amountError}</span>
                                </p>
                            )}
                        </div>
                    </div>

                </div>
            </div>

            {/* ── CONFIRM TO PAY BOTTOM SHEET MODAL (REUSABLE COMPONENT) ── */}
            <ConfirmPaymentSheet
                isOpen={isConfirmOpen}
                onOpenChange={setIsConfirmOpen}
                title="Confirm to Pay"
                amount={payableAmount}
                subAmountText={
                    discountAmount > 0 ? (
                        <p className="text-[11px] font-bold text-emerald-500 mt-0.5">
                            Includes {discountPercent}% discount (Save ₦{discountAmount.toLocaleString('en-NG', { minimumFractionDigits: 2 })})
                        </p>
                    ) : undefined
                }
                walletBalance={Number(userWalletBalance)}
                isSubmitting={isSubmitting}
                confirmButtonText="Confirm & Recharge Airtime"
                onConfirm={handleConfirmRecharge}
                details={[
                    {
                        label: 'Network',
                        value: (
                            <div className="flex items-center gap-1.5 font-bold text-gray-900 dark:text-white">
                                {NETWORK_ICONS[selectedNetwork] && (
                                    <img
                                        src={NETWORK_ICONS[selectedNetwork]}
                                        alt={selectedNetwork}
                                        className="w-3.5 h-3.5 rounded-full object-contain bg-white"
                                    />
                                )}
                                <span className="uppercase text-xs">{selectedNetwork}</span>
                            </div>
                        ),
                    },
                    {
                        label: 'Product',
                        value: (
                            <span className="font-extrabold text-gray-900 dark:text-white text-xs">
                                ₦{currentAmount.toLocaleString()} VTU Airtime
                            </span>
                        ),
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
        </>
    );
}

AirtimePage.layout = {
    breadcrumbs: [
        {
            title: 'Airtime',
            href: '/vtu/airtime',
        },
    ],
};
