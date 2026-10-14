import { useState, useRef } from 'react';
import { Head, usePage, Link, router } from '@inertiajs/react';
import { dashboard } from '@/routes';
import {
    ChevronLeft, ArrowRightLeft, Copy, Check, CheckCircle,
    AlertCircle, Building, Wallet, Smartphone, Clock,
    ShieldCheck, Sparkles, Info, RefreshCw, Upload, X,
    Image as ImageIcon, CheckCircle2
} from 'lucide-react';
import { NETWORK_ICONS } from '@/components/phone-network-card';

export interface AirtimeCashNetwork {
    id: number;
    name: string;
    slug: string;
    rate: number;
    min_amount: number;
    max_amount: number;
    receiving_phone: string | null;
    ussd_instructions: string;
    is_enabled: boolean;
}

export interface AirtimeCashRequestItem {
    id: number;
    reference: string;
    network: string;
    network_slug: string;
    airtime_amount: number;
    cash_amount: number;
    exchange_rate: number;
    payout_method: 'wallet' | 'bank';
    status: 'pending' | 'approved' | 'rejected';
    rejection_reason?: string | null;
    proof_image_url?: string | null;
    created_at: string;
}

interface AirtimeToCashPageProps {
    networks: AirtimeCashNetwork[];
    recent_requests: AirtimeCashRequestItem[];
    wallet_balance: number;
    user: {
        name?: string;
        phone?: string;
    };
    is_accessible?: boolean;
}

export default function AirtimeToCashPage({
    networks = [],
    recent_requests = [],
    wallet_balance = 0,
    user,
    is_accessible = true,
}: AirtimeToCashPageProps) {
    const { flash, errors } = usePage<any>().props;

    // Default to first enabled network, or first network
    const initialNetwork = networks.find((n) => n.is_enabled) || networks[0];
    const [selectedNetworkId, setSelectedNetworkId] = useState<number>(initialNetwork?.id || 1);
    const selectedNetwork = networks.find((n) => n.id === selectedNetworkId) || initialNetwork;

    const minAmount = selectedNetwork?.min_amount || 500;
    const maxAmount = selectedNetwork?.max_amount || 50000;

    const [airtimeAmount, setAirtimeAmount] = useState<string>('1000');
    const [senderPhone, setSenderPhone] = useState<string>(user?.phone || '');
    const [payoutMethod, setPayoutMethod] = useState<'wallet' | 'bank'>('wallet');
    const [bankName, setBankName] = useState<string>('');
    const [accountNumber, setAccountNumber] = useState<string>('');
    const [accountName, setAccountName] = useState<string>('');

    // Proof file upload state
    const [proofFile, setProofFile] = useState<File | null>(null);
    const [proofPreview, setProofPreview] = useState<string | null>(null);
    const fileInputRef = useRef<HTMLInputElement>(null);

    const [copiedPhone, setCopiedPhone] = useState<boolean>(false);
    const [isSubmitting, setIsSubmitting] = useState<boolean>(false);
    const [localError, setLocalError] = useState<string | null>(null);
    const [showSuccessToast, setShowSuccessToast] = useState<boolean>(false);
    const [justSubmittedRef, setJustSubmittedRef] = useState<string | null>(null);

    const parsedAmount = parseFloat(airtimeAmount) || 0;
    const rate = selectedNetwork ? selectedNetwork.rate : 80;
    const isAmountValid = parsedAmount >= minAmount && parsedAmount <= maxAmount;
    const cashAmount = isAmountValid ? (parsedAmount * rate) / 100 : 0;

    const handleCopyPhone = () => {
        if (!selectedNetwork?.receiving_phone) return;
        navigator.clipboard.writeText(selectedNetwork.receiving_phone);
        setCopiedPhone(true);
        setTimeout(() => setCopiedPhone(false), 2000);
    };

    const handleSelectNetwork = (netId: number) => {
        const targetNet = networks.find((n) => n.id === netId);
        if (!targetNet || !targetNet.is_enabled || !targetNet.receiving_phone) return;

        setSelectedNetworkId(netId);
        setLocalError(null);

        const currentAmt = parseInt(airtimeAmount, 10);
        if (!isNaN(currentAmt)) {
            if (currentAmt > targetNet.max_amount) {
                setAirtimeAmount(targetNet.max_amount.toString());
            } else if (currentAmt < targetNet.min_amount) {
                setAirtimeAmount(targetNet.min_amount.toString());
            }
        }
    };

    // Amount change handler with strict clamping
    const handleAmountChange = (val: string) => {
        const cleaned = val.replace(/\D/g, '');
        if (!cleaned) {
            setAirtimeAmount('');
            setLocalError(`Please enter an amount between ₦${minAmount.toLocaleString()} and ₦${maxAmount.toLocaleString()}.`);
            return;
        }

        const num = parseInt(cleaned, 10);

        // Strict ceiling: user cannot type more than max
        if (num > maxAmount) {
            setAirtimeAmount(maxAmount.toString());
            setLocalError(`Maximum conversion amount is ₦${maxAmount.toLocaleString()}.`);
            return;
        }

        setAirtimeAmount(cleaned);

        if (num < minAmount) {
            setLocalError(`Minimum conversion amount is ₦${minAmount.toLocaleString()}.`);
        } else {
            setLocalError(null);
        }
    };

    // Amount blur handler: clamp to minimum if left lower than min
    const handleAmountBlur = () => {
        if (!airtimeAmount) {
            setAirtimeAmount(minAmount.toString());
            setLocalError(null);
            return;
        }

        const num = parseInt(airtimeAmount, 10);
        if (isNaN(num) || num < minAmount) {
            setAirtimeAmount(minAmount.toString());
            setLocalError(null);
        } else if (num > maxAmount) {
            setAirtimeAmount(maxAmount.toString());
            setLocalError(null);
        }
    };

    // Proof file change handler
    const handleProofChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            setLocalError('Please upload an image file (PNG, JPG, or WEBP).');
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            setLocalError('Proof image size must be less than 5MB.');
            return;
        }

        setProofFile(file);
        setProofPreview(URL.createObjectURL(file));
        setLocalError(null);
    };

    const handleRemoveProof = () => {
        if (proofPreview) {
            URL.revokeObjectURL(proofPreview);
        }
        setProofFile(null);
        setProofPreview(null);
        if (fileInputRef.current) {
            fileInputRef.current.value = '';
        }
    };

    // Preset amounts within range
    const presetAmounts = Array.from(
        new Set([minAmount, 1000, 2000, 5000, 10000, maxAmount])
    )
        .filter((amt) => amt >= minAmount && amt <= maxAmount)
        .sort((a, b) => a - b)
        .slice(0, 5);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        setLocalError(null);

        if (!selectedNetwork || !selectedNetwork.is_enabled || !selectedNetwork.receiving_phone) {
            setLocalError('Conversion is currently unavailable for this network.');
            return;
        }

        if (parsedAmount < minAmount) {
            setLocalError(`Minimum conversion amount is ₦${minAmount.toLocaleString()}.`);
            return;
        }

        if (parsedAmount > maxAmount) {
            setLocalError(`Maximum conversion amount is ₦${maxAmount.toLocaleString()}.`);
            return;
        }

        const cleanPhone = senderPhone.replace(/\D/g, '');
        if (cleanPhone.length < 10 || cleanPhone.length > 11) {
            setLocalError('Please enter a valid 11-digit phone number you will send airtime from.');
            return;
        }

        if (payoutMethod === 'bank') {
            if (!bankName.trim() || !accountNumber.trim() || !accountName.trim()) {
                setLocalError('Please fill in your bank name, 10-digit account number, and account name.');
                return;
            }
            if (accountNumber.trim().length !== 10) {
                setLocalError('Bank account number must be exactly 10 digits.');
                return;
            }
        }

        setIsSubmitting(true);

        router.post(
            '/vtu/airtime-to-cash/submit',
            {
                network_id: selectedNetwork.id,
                airtime_amount: parsedAmount,
                sender_phone: cleanPhone,
                payout_method: payoutMethod,
                bank_name: payoutMethod === 'bank' ? bankName : null,
                account_number: payoutMethod === 'bank' ? accountNumber : null,
                account_name: payoutMethod === 'bank' ? accountName : null,
                proof_image: proofFile,
            },
            {
                forceFormData: true,
                preserveScroll: true,
                onSuccess: () => {
                    // Reset form fields
                    setAirtimeAmount(minAmount.toString());
                    setBankName('');
                    setAccountNumber('');
                    setAccountName('');
                    handleRemoveProof();
                    setLocalError(null);

                    // Show success toast
                    setShowSuccessToast(true);
                    setJustSubmittedRef('latest');

                    // Scroll down to recent requests section so customer sees it went through
                    setTimeout(() => {
                        const el = document.getElementById('recent-requests');
                        if (el) {
                            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    }, 200);
                },
                onError: (errs) => {
                    setLocalError(
                        errs.network_id ||
                        errs.airtime_amount ||
                        errs.sender_phone ||
                        errs.payout_method ||
                        errs.proof_image ||
                        'Failed to submit request.'
                    );
                },
                onFinish: () => setIsSubmitting(false),
            }
        );
    };

    return (
        <>
            <Head title="Airtime to Cash - Convert Airtime to Instant Money" />

            {/* Top Navigation Bar */}
            <div className="sticky top-0 z-30 bg-white/95 dark:bg-[#181826]/95 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 py-3.5 px-4 flex items-center justify-between shadow-2xs">
                <div className="flex items-center gap-3 min-w-0">
                    <Link
                        href={dashboard().url}
                        className="p-1.5 rounded-xl text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer shrink-0"
                        title="Back to Dashboard"
                    >
                        <ChevronLeft className="w-6 h-6 stroke-[2.2]" />
                    </Link>
                    <div className="min-w-0">
                        <div className="flex items-center gap-2">
                            <h1 className="font-extrabold text-base text-gray-900 dark:text-white tracking-tight leading-tight">
                                Airtime to Cash
                            </h1>
                            <span className="hidden sm:inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                <Sparkles className="w-2.5 h-2.5" /> High Rates
                            </span>
                        </div>
                        <p className="text-xs text-slate-500 dark:text-slate-400 font-normal truncate mt-0.5">
                            Convert excess airtime to instant bank cash or store wallet balance
                        </p>
                    </div>
                </div>

                <div className="flex items-center gap-2">
                    <div className="hidden sm:flex flex-col items-end text-right">
                        <span className="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-semibold">Store Balance</span>
                        <span className="text-xs font-bold text-gray-900 dark:text-white">₦{wallet_balance.toLocaleString('en-NG', { minimumFractionDigits: 2 })}</span>
                    </div>
                </div>
            </div>

            {/* Main Content Area */}
            <div className="max-w-2xl mx-auto px-4 py-6 w-full space-y-6">

                {/* Success Flash Banner */}
                {flash?.success && (
                    <div className="rounded-2xl p-4 text-xs font-semibold flex items-start gap-3 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-800 dark:text-emerald-300">
                        <CheckCircle className="w-5 h-5 shrink-0 text-emerald-600 dark:text-emerald-400 mt-0.5" />
                        <div>
                            <p className="font-bold text-sm">Request Submitted!</p>
                            <p className="mt-0.5">{flash.success}</p>
                        </div>
                    </div>
                )}

                {/* Service Availability Notice */}
                {!is_accessible && (
                    <div className="rounded-2xl p-4 sm:p-5 text-xs bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 space-y-1.5 shadow-xs">
                        <div className="flex items-center gap-2 font-bold text-sm text-slate-800 dark:text-slate-200">
                            <AlertCircle className="w-4 h-4 shrink-0 text-amber-500" />
                            <span>Service Currently Unavailable</span>
                        </div>
                        <p className="text-slate-500 dark:text-slate-400 leading-relaxed">
                            Airtime to Cash conversions are currently closed on this store. Please check back later or contact customer support for further assistance.
                        </p>
                    </div>
                )}

                {/* Error Banner */}
                {(localError || errors?.airtime_amount || errors?.sender_phone) && (
                    <div className="rounded-2xl p-4 text-xs font-semibold flex items-center gap-2 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-700 dark:text-rose-300">
                        <AlertCircle className="w-4 h-4 shrink-0 text-rose-500" />
                        <span>{localError || errors?.airtime_amount || errors?.sender_phone}</span>
                    </div>
                )}

                {/* Step 1: Select Network */}
                <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs space-y-3">
                    <div className="flex items-center justify-between">
                        <label className="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                            <span className="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[11px] font-black">1</span>
                            Select Telecom Network
                        </label>
                        {selectedNetwork && (
                            <span className="text-xs font-extrabold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full border border-emerald-500/20">
                                {selectedNetwork.rate}% Payout Rate
                            </span>
                        )}
                    </div>

                    <div className="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                        {networks.map((net) => {
                            const isSelected = selectedNetwork?.id === net.id;
                            const isAvailable = net.is_enabled && Boolean(net.receiving_phone);

                            return (
                                <button
                                    key={net.id}
                                    type="button"
                                    onClick={() => handleSelectNetwork(net.id)}
                                    disabled={!isAvailable}
                                    className={`relative p-3 rounded-xl border flex flex-col items-center justify-center gap-2 transition-all cursor-pointer ${
                                        isSelected
                                            ? 'border-primary bg-primary/5 ring-2 ring-primary/20 shadow-xs'
                                            : isAvailable
                                                ? 'border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 hover:border-gray-300 dark:hover:border-gray-700'
                                                : 'border-dashed border-gray-200 dark:border-gray-800 opacity-50 cursor-not-allowed bg-gray-50 dark:bg-gray-900'
                                    }`}
                                >
                                    {NETWORK_ICONS[net.slug] ? (
                                        <img
                                            src={NETWORK_ICONS[net.slug]}
                                            alt={net.name}
                                            className="w-8 h-8 rounded-full object-contain shadow-xs bg-white"
                                        />
                                    ) : (
                                        <div className="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center font-black text-primary text-xs">
                                            {net.name.slice(0, 2).toUpperCase()}
                                        </div>
                                    )}

                                    <div className="text-center">
                                        <span className="block font-bold text-xs text-gray-900 dark:text-white uppercase">
                                            {net.name}
                                        </span>
                                        <span className="block text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">
                                            {isAvailable ? `${net.rate}% Rate` : 'Unavailable'}
                                        </span>
                                    </div>
                                </button>
                            );
                        })}
                    </div>
                </div>

                {/* Step 2: Airtime Amount & Live Calculator */}
                <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs space-y-4">
                    <label className="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <span className="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[11px] font-black">2</span>
                        Enter Airtime Amount
                    </label>

                    {/* Quick Presets (Strictly within [minAmount, maxAmount]) */}
                    <div className="grid grid-cols-3 sm:grid-cols-5 gap-2">
                        {presetAmounts.map((amt) => (
                            <button
                                key={amt}
                                type="button"
                                onClick={() => handleAmountChange(amt.toString())}
                                className={`py-2 px-1 text-center rounded-xl text-xs font-extrabold border transition-all cursor-pointer ${
                                    parsedAmount === amt
                                        ? 'bg-primary text-white border-primary shadow-xs'
                                        : 'bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-800 text-gray-700 dark:text-gray-200 hover:border-gray-300'
                                }`}
                            >
                                ₦{amt.toLocaleString()}
                            </button>
                        ))}
                    </div>

                    {/* Custom Input with Clamping & Range Guards */}
                    <div className="space-y-1.5">
                        <div className="relative">
                            <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <span className="text-base font-black text-gray-400 dark:text-gray-500">₦</span>
                            </div>
                            <input
                                type="text"
                                inputMode="numeric"
                                value={airtimeAmount}
                                onChange={(e) => handleAmountChange(e.target.value)}
                                onBlur={handleAmountBlur}
                                placeholder={`Enter amount between ${minAmount.toLocaleString()} and ${maxAmount.toLocaleString()}`}
                                className={`w-full pl-9 pr-4 py-3 rounded-xl border bg-gray-50/70 dark:bg-gray-900/70 text-gray-900 dark:text-white font-black text-lg focus:outline-none focus:ring-2 transition-all ${
                                    parsedAmount > 0 && !isAmountValid
                                        ? 'border-rose-400 focus:ring-rose-400'
                                        : 'border-gray-200 dark:border-gray-700 focus:ring-primary focus:border-transparent'
                                }`}
                            />
                        </div>

                        <div className="flex items-center justify-between text-[11px] text-gray-500 dark:text-gray-400 px-1">
                            <span className={parsedAmount > 0 && parsedAmount < minAmount ? 'text-rose-500 font-bold' : ''}>
                                Min: ₦{minAmount.toLocaleString()}
                            </span>
                            <span className={parsedAmount > maxAmount ? 'text-rose-500 font-bold' : ''}>
                                Max: ₦{maxAmount.toLocaleString()}
                            </span>
                        </div>

                        {parsedAmount > 0 && !isAmountValid && (
                            <p className="text-[11px] font-bold text-rose-500 flex items-center gap-1 px-1">
                                <AlertCircle className="w-3.5 h-3.5 shrink-0" />
                                {parsedAmount < minAmount
                                    ? `Amount is below minimum allowed (₦${minAmount.toLocaleString()})`
                                    : `Amount exceeds maximum allowed limit (₦${maxAmount.toLocaleString()})`}
                            </p>
                        )}
                    </div>

                    {/* LIVE CALCULATION SUMMARY CARD */}
                    <div className={`border rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 transition-colors ${
                        isAmountValid
                            ? 'bg-gradient-to-br from-emerald-500/10 via-emerald-500/5 to-transparent border-emerald-500/20'
                            : 'bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-800'
                    }`}>
                        <div className="space-y-0.5">
                            <span className="text-[11px] font-semibold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider">
                                Cash Payout (You Will Receive)
                            </span>
                            <div className="flex items-baseline gap-2">
                                <span className={`text-2xl sm:text-3xl font-black ${
                                    isAmountValid ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500'
                                }`}>
                                    ₦{cashAmount.toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                                </span>
                                <span className="text-xs font-bold text-gray-500 dark:text-gray-400">
                                    at {rate}% rate
                                </span>
                            </div>
                        </div>

                        <div className="text-left sm:text-right text-xs text-gray-600 dark:text-gray-400 border-t sm:border-t-0 pt-2 sm:pt-0 border-emerald-500/10">
                            <p>
                                Airtime Sent: <span className="font-bold text-gray-900 dark:text-white">
                                    ₦{isAmountValid ? parsedAmount.toLocaleString() : '0'}
                                </span>
                            </p>
                            <p>
                                Service Fee: <span className="font-bold text-gray-900 dark:text-white">
                                    ₦{isAmountValid ? (parsedAmount - cashAmount).toLocaleString('en-NG', { minimumFractionDigits: 2 }) : '0.00'} ({100 - rate}%)
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {/* Step 3: Merchant Transfer Number & Instructions */}
                {selectedNetwork?.receiving_phone && (
                    <div className="bg-amber-500/5 border border-amber-500/20 rounded-2xl p-4 sm:p-5 shadow-xs space-y-3">
                        <label className="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400 flex items-center gap-1.5">
                            <span className="w-5 h-5 rounded-full bg-amber-500/20 text-amber-700 dark:text-amber-400 flex items-center justify-center text-[11px] font-black">3</span>
                            Transfer Airtime to Merchant SIM
                        </label>

                        <div className="bg-white dark:bg-[#181826] border border-amber-500/30 rounded-xl p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div className="flex items-center gap-3">
                                <div className="w-10 h-10 rounded-full bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                                    <Smartphone className="w-5 h-5" />
                                </div>
                                <div>
                                    <span className="text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase">
                                        Send Airtime To ({selectedNetwork.name})
                                    </span>
                                    <p className="text-lg font-mono font-black text-gray-900 dark:text-white tracking-wider">
                                        {selectedNetwork.receiving_phone}
                                    </p>
                                </div>
                            </div>

                            <button
                                type="button"
                                onClick={handleCopyPhone}
                                className="px-3.5 py-2 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 bg-amber-500/10 text-amber-700 dark:text-amber-400 hover:bg-amber-500/20 transition-all cursor-pointer shrink-0"
                            >
                                {copiedPhone ? (
                                    <>
                                        <Check className="w-4 h-4 text-emerald-600" />
                                        <span className="text-emerald-600">Copied!</span>
                                    </>
                                ) : (
                                    <>
                                        <Copy className="w-4 h-4" />
                                        <span>Copy Number</span>
                                    </>
                                )}
                            </button>
                        </div>

                        {/* USSD Transfer Instructions */}
                        <div className="bg-white dark:bg-gray-900/60 rounded-xl p-3 border border-gray-100 dark:border-gray-800 text-xs text-gray-600 dark:text-gray-300 space-y-1">
                            <div className="flex items-center gap-1.5 font-bold text-gray-900 dark:text-white">
                                <Info className="w-3.5 h-3.5 text-primary" />
                                <span>Transfer Instruction:</span>
                            </div>
                            <p className="font-mono text-[11px] bg-gray-50 dark:bg-gray-800 p-2 rounded-lg text-primary font-semibold break-all">
                                {selectedNetwork.ussd_instructions}
                            </p>
                            <p className="text-[11px] text-gray-500 dark:text-gray-400">
                                💡 <strong className="text-gray-700 dark:text-gray-300">Important:</strong> Please perform the transfer using the code above first, then fill in your phone number below and submit.
                            </p>
                        </div>
                    </div>
                )}

                {/* Step 4: Verification & Payout Destination */}
                <form onSubmit={handleSubmit} className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs space-y-5">
                    <label className="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <span className="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[11px] font-black">4</span>
                        Your Sender Details & Payout
                    </label>

                    {/* Sender Phone */}
                    <div className="space-y-1.5">
                        <label className="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Your Sending Phone Number (SIM You Transferred From)
                        </label>
                        <div className="relative">
                            <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <Smartphone className="w-4 h-4 text-gray-400" />
                            </div>
                            <input
                                type="tel"
                                value={senderPhone}
                                onChange={(e) => setSenderPhone(e.target.value)}
                                placeholder="08012345678"
                                required
                                className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/70 text-gray-900 dark:text-white font-mono font-bold text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            />
                        </div>
                        <p className="text-[11px] text-gray-500 dark:text-gray-400">
                            We match this number with the incoming airtime transfer SMS on the merchant SIM.
                        </p>
                    </div>

                    {/* Payout Choice */}
                    <div className="space-y-2">
                        <label className="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Where should we send your cash?
                        </label>
                        <div className="grid grid-cols-2 gap-3">
                            <button
                                type="button"
                                onClick={() => setPayoutMethod('wallet')}
                                className={`p-3 rounded-xl border flex items-center gap-3 transition-all cursor-pointer text-left ${
                                    payoutMethod === 'wallet'
                                        ? 'border-primary bg-primary/5 ring-2 ring-primary/20'
                                        : 'border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50'
                                }`}
                            >
                                <div className={`w-8 h-8 rounded-lg flex items-center justify-center ${
                                    payoutMethod === 'wallet' ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-400'
                                }`}>
                                    <Wallet className="w-4 h-4" />
                                </div>
                                <div>
                                    <p className="font-extrabold text-xs text-gray-900 dark:text-white">Store Wallet</p>
                                    <p className="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold">Instant Credit</p>
                                </div>
                            </button>

                            <button
                                type="button"
                                onClick={() => setPayoutMethod('bank')}
                                className={`p-3 rounded-xl border flex items-center gap-3 transition-all cursor-pointer text-left ${
                                    payoutMethod === 'bank'
                                        ? 'border-primary bg-primary/5 ring-2 ring-primary/20'
                                        : 'border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50'
                                }`}
                            >
                                <div className={`w-8 h-8 rounded-lg flex items-center justify-center ${
                                    payoutMethod === 'bank' ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-400'
                                }`}>
                                    <Building className="w-4 h-4" />
                                </div>
                                <div>
                                    <p className="font-extrabold text-xs text-gray-900 dark:text-white">Bank Account</p>
                                    <p className="text-[10px] text-gray-500 dark:text-gray-400">Direct Wire</p>
                                </div>
                            </button>
                        </div>
                    </div>

                    {/* Bank Fields (Conditional) */}
                    {payoutMethod === 'bank' && (
                        <div className="bg-gray-50 dark:bg-gray-900/60 rounded-xl p-3.5 border border-gray-200 dark:border-gray-800 space-y-3">
                            <div className="space-y-1">
                                <label className="text-[11px] font-bold text-gray-700 dark:text-gray-300">Bank Name</label>
                                <input
                                    type="text"
                                    value={bankName}
                                    onChange={(e) => setBankName(e.target.value)}
                                    placeholder="e.g. OPay, Moniepoint, Kuda, GTBank, Zenith"
                                    required={payoutMethod === 'bank'}
                                    className="w-full px-3 py-2 text-xs rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
                                />
                            </div>

                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div className="space-y-1">
                                    <label className="text-[11px] font-bold text-gray-700 dark:text-gray-300">Account Number (10 Digits)</label>
                                    <input
                                        type="text"
                                        maxLength={10}
                                        value={accountNumber}
                                        onChange={(e) => setAccountNumber(e.target.value.replace(/\D/g, ''))}
                                        placeholder="0123456789"
                                        required={payoutMethod === 'bank'}
                                        className="w-full px-3 py-2 text-xs font-mono font-bold rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
                                    />
                                </div>

                                <div className="space-y-1">
                                    <label className="text-[11px] font-bold text-gray-700 dark:text-gray-300">Account Name</label>
                                    <input
                                        type="text"
                                        value={accountName}
                                        onChange={(e) => setAccountName(e.target.value)}
                                        placeholder="Full Name on Account"
                                        required={payoutMethod === 'bank'}
                                        className="w-full px-3 py-2 text-xs rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
                                    />
                                </div>
                            </div>
                        </div>
                    )}

                    {/* Proof of Transfer (Optional Screenshot Upload) */}
                    <div className="space-y-2 pt-1 border-t border-gray-100 dark:border-gray-800">
                        <div className="flex items-center justify-between">
                            <label className="text-xs font-bold text-gray-700 dark:text-gray-300 flex items-center gap-1.5">
                                <Upload className="w-3.5 h-3.5 text-primary" />
                                <span>Upload Transfer Proof / Debit SMS</span>
                                <span className="text-[10px] font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded">Optional</span>
                            </label>
                        </div>

                        {proofPreview ? (
                            <div className="relative rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-3 flex items-center justify-between">
                                <div className="flex items-center gap-3 min-w-0">
                                    <img
                                        src={proofPreview}
                                        alt="Proof Preview"
                                        className="w-12 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-700 shrink-0"
                                    />
                                    <div className="min-w-0">
                                        <p className="text-xs font-bold text-gray-900 dark:text-white truncate">
                                            {proofFile?.name}
                                        </p>
                                        <p className="text-[11px] text-gray-500 dark:text-gray-400">
                                            {(proofFile ? proofFile.size / 1024 : 0).toFixed(1)} KB • Image attached
                                        </p>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    onClick={handleRemoveProof}
                                    className="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors cursor-pointer"
                                    title="Remove Screenshot"
                                >
                                    <X className="w-4 h-4" />
                                </button>
                            </div>
                        ) : (
                            <div
                                onClick={() => fileInputRef.current?.click()}
                                className="border-2 border-dashed border-gray-200 dark:border-gray-700 hover:border-primary/50 dark:hover:border-primary/50 rounded-xl p-4 text-center cursor-pointer transition-colors bg-gray-50/50 dark:bg-gray-900/30 group"
                            >
                                <input
                                    ref={fileInputRef}
                                    type="file"
                                    accept="image/png,image/jpeg,image/jpg,image/webp"
                                    onChange={handleProofChange}
                                    className="hidden"
                                />
                                <div className="flex flex-col items-center justify-center gap-1.5">
                                    <div className="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center group-hover:scale-105 transition-transform">
                                        <ImageIcon className="w-4 h-4" />
                                    </div>
                                    <p className="text-xs font-bold text-gray-800 dark:text-gray-200">
                                        Click to attach transfer screenshot or USSD receipt
                                    </p>
                                    <p className="text-[10px] text-gray-400 dark:text-gray-500">
                                        PNG, JPG, or WEBP up to 5MB (speeds up review)
                                    </p>
                                </div>
                            </div>
                        )}
                    </div>

                    {/* Submit Button */}
                    <button
                        type="submit"
                        disabled={isSubmitting || !selectedNetwork?.is_enabled || !isAmountValid || !is_accessible}
                        className="w-full py-3.5 px-4 rounded-xl bg-primary text-white font-extrabold text-sm shadow-md hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {isSubmitting ? (
                            <>
                                <RefreshCw className="w-4 h-4 animate-spin" />
                                <span>Submitting Conversion Request...</span>
                            </>
                        ) : !is_accessible ? (
                            <>
                                <AlertCircle className="w-4 h-4" />
                                <span>Service Currently Unavailable</span>
                            </>
                        ) : (
                            <>
                                <ArrowRightLeft className="w-4 h-4" />
                                <span>I Have Transferred Airtime — Submit Request</span>
                            </>
                        )}
                    </button>

                    <p className="text-center text-[11px] text-gray-500 dark:text-gray-400 flex items-center justify-center gap-1">
                        <ShieldCheck className="w-3.5 h-3.5 text-emerald-600" />
                        Safe & Verified. Transfers are reviewed and processed promptly.
                    </p>
                </form>

                {/* Conversion History Section */}
                <div id="recent-requests" className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs space-y-3 scroll-mt-20">
                    <div className="flex items-center justify-between">
                        <h2 className="font-extrabold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                            <Clock className="w-4 h-4 text-primary" />
                            Recent Conversion Requests
                        </h2>
                        <span className="text-xs text-gray-500 dark:text-gray-400">
                            {recent_requests.length} records
                        </span>
                    </div>

                    {recent_requests.length === 0 ? (
                        <div className="text-center py-8 text-gray-500 dark:text-gray-400">
                            <ArrowRightLeft className="w-8 h-8 mx-auto mb-2 opacity-30" />
                            <p className="text-xs font-semibold">No conversion requests yet.</p>
                            <p className="text-[11px] mt-0.5">Your past airtime-to-cash submissions will appear here.</p>
                        </div>
                    ) : (
                        <div className="space-y-2.5">
                            {recent_requests.map((req, index) => {
                                const isNew = justSubmittedRef === 'latest' && index === 0;

                                return (
                                    <div
                                        key={req.id}
                                        className={`p-3.5 rounded-xl border transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-3 ${
                                            isNew
                                                ? 'border-emerald-500/50 bg-emerald-50/40 dark:bg-emerald-500/10 ring-2 ring-emerald-500/20'
                                                : 'border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50'
                                        }`}
                                    >
                                        <div className="space-y-1">
                                            <div className="flex items-center gap-2 flex-wrap">
                                                <span className="font-mono text-xs font-extrabold text-gray-900 dark:text-white">
                                                    {req.reference}
                                                </span>
                                                <span className="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase">
                                                    • {req.network}
                                                </span>
                                                <span className="text-[10px] text-gray-400 dark:text-gray-500">
                                                    ({req.created_at})
                                                </span>
                                                {isNew && (
                                                    <span className="text-[10px] font-extrabold px-1.5 py-0.5 rounded-full bg-emerald-600 text-white animate-pulse">
                                                        Just Submitted
                                                    </span>
                                                )}
                                                {req.proof_image_url && (
                                                    <a
                                                        href={req.proof_image_url}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="text-[10px] font-semibold text-primary hover:underline inline-flex items-center gap-0.5"
                                                    >
                                                        📸 Proof Attached
                                                    </a>
                                                )}
                                            </div>
                                            <div className="flex items-center gap-2 text-xs">
                                                <span className="text-gray-600 dark:text-gray-400">
                                                    Airtime: <strong className="text-gray-900 dark:text-white">₦{req.airtime_amount.toLocaleString()}</strong>
                                                </span>
                                                <span>&rarr;</span>
                                                <span className="text-emerald-600 dark:text-emerald-400 font-bold">
                                                    Cash: ₦{req.cash_amount.toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                                                </span>
                                                <span className="text-[10px] uppercase font-semibold text-gray-500">
                                                    ({req.payout_method})
                                                </span>
                                            </div>
                                            {req.rejection_reason && (
                                                <p className="text-[11px] text-rose-600 dark:text-rose-400 font-semibold">
                                                    Reason: {req.rejection_reason}
                                                </p>
                                            )}
                                        </div>

                                        <div className="shrink-0">
                                            {req.status === 'pending' && (
                                                <span className="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                                    <Clock className="w-3 h-3" /> Pending Review
                                                </span>
                                            )}
                                            {req.status === 'approved' && (
                                                <span className="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                                    <CheckCircle className="w-3 h-3" /> Paid & Completed
                                                </span>
                                            )}
                                            {req.status === 'rejected' && (
                                                <span className="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                                    <AlertCircle className="w-3 h-3" /> Rejected
                                                </span>
                                            )}
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    )}
                </div>

                {/* Floating Success Toast */}
                {showSuccessToast && (
                    <div className="fixed bottom-6 right-6 z-50 max-w-sm w-[calc(100vw-3rem)] bg-emerald-700 text-white rounded-2xl p-4 shadow-2xl flex items-start gap-3 border border-emerald-500/30">
                        <CheckCircle2 className="w-5 h-5 text-emerald-200 shrink-0 mt-0.5" />
                        <div className="flex-1 min-w-0">
                            <p className="text-xs font-black">Conversion Request Queued!</p>
                            <p className="text-[11px] text-emerald-100 mt-0.5 leading-snug">
                                Your airtime conversion order was sent. We are verifying your transfer now.
                            </p>
                        </div>
                        <button
                            type="button"
                            onClick={() => setShowSuccessToast(false)}
                            className="text-emerald-200 hover:text-white p-1 rounded-lg cursor-pointer"
                        >
                            <X className="w-4 h-4" />
                        </button>
                    </div>
                )}

            </div>
        </>
    );
}

AirtimeToCashPage.layout = {
    breadcrumbs: [
        {
            title: 'Airtime to Cash',
            href: '/vtu/airtime-to-cash',
        },
    ],
};
