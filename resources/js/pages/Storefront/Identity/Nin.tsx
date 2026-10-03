import { useState } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import { dashboard } from '@/routes';
import {
    ChevronLeft, Headphones, ShieldCheck, CheckCircle2,
    FileText, IdCard, Smartphone, Copy, Check, Eye, EyeOff,
    Download, Printer, RefreshCw, AlertCircle,
    BadgeCheck, Lock, Layers, ZoomIn, X
} from 'lucide-react';
import { ConfirmPaymentSheet, PaymentDetailItem } from '@/components/confirm-payment-sheet';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

// Slip Sample Images from IDCore
import ninInfoImg from '@/assets/slips/nin-information.png';
import ninRegularImg from '@/assets/slips/nin-regular.png';
import ninStandardImg from '@/assets/slips/nin-standard.png';
import ninPremiumImg from '@/assets/slips/nin-premium.png';

const SLIP_SAMPLE_IMAGES: Record<string, string> = {
    information: ninInfoImg,
    regular: ninRegularImg,
    standard: ninStandardImg,
    premium: ninPremiumImg,
};

export interface SlipTemplate {
    id: string;
    name: string;
    badge: string;
    price: number;
    description: string;
    features: string[];
    is_popular?: boolean;
    color?: string;
}

export interface RecentVerification {
    id: number;
    reference: string;
    recipient: string;
    amount_paid: number;
    status: string;
    slip_download_url?: string | null;
    created_at: string;
}

interface NinPageProps {
    is_available?: boolean;
    slips: SlipTemplate[];
    wallet_balance: number;
    recent_verifications: RecentVerification[];
    store_support?: {
        name: string;
        whatsapp?: string | null;
    };
}

export default function NinVerificationPage({
    is_available = true,
    slips = [],
    wallet_balance = 0,
    recent_verifications = [],
    store_support,
}: NinPageProps) {
    const { auth } = usePage<any>().props;

    // Search Mode: 'nin' vs 'phone'
    const [searchMode, setSearchMode] = useState<'nin' | 'phone'>('nin');
    const [searchValue, setSearchValue] = useState('');
    const [selectedSlipId, setSelectedSlipId] = useState<string>(
        slips.find((s) => s.is_popular)?.id || slips[0]?.id || 'standard'
    );

    // Sheets & Verification States
    const [isConfirmOpen, setIsConfirmOpen] = useState(false);
    const [isVerifying, setIsVerifying] = useState(false);
    const [errorMessage, setErrorMessage] = useState<string | null>(null);

    // Sample Image Lightbox Dialog State
    const [sampleModalImg, setSampleModalImg] = useState<{ title: string; src: string } | null>(null);
    const [previewStageSlipId, setPreviewStageSlipId] = useState<string | null>(null);

    // Verified Result State
    const [verifiedData, setVerifiedData] = useState<any | null>(null);
    const [showFullNin, setShowFullNin] = useState(false);
    const [copiedRef, setCopiedRef] = useState(false);

    // Selected slip details
    const selectedSlip = slips.find((s) => s.id === selectedSlipId) || slips[0] || {
        id: 'standard',
        name: 'Standard',
        price: 150,
        badge: 'Official A4',
    };

    const isInputValid = /^[0-9]{11}$/.test(searchValue.replace(/\s+/g, ''));
    const isInsufficientBalance = wallet_balance < selectedSlip.price;

    const handleSearchValueChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const val = e.target.value.replace(/[^0-9]/g, '').slice(0, 11);
        setSearchValue(val);
        setErrorMessage(null);
    };

    const handlePaste = async () => {
        try {
            const text = await navigator.clipboard.readText();
            const cleaned = text.replace(/[^0-9]/g, '').slice(0, 11);
            if (cleaned) {
                setSearchValue(cleaned);
                setErrorMessage(null);
            }
        } catch {
            // Ignore clipboard permission errors
        }
    };

    const handleInitiateVerification = () => {
        if (!isInputValid) {
            setErrorMessage(
                searchMode === 'phone'
                    ? 'Please enter a valid 11-digit phone number.'
                    : 'Please enter a valid 11-digit National Identity Number (NIN).'
            );
            return;
        }

        if (isInsufficientBalance) {
            setErrorMessage(`Insufficient wallet balance (₦${wallet_balance.toLocaleString('en-NG', { minimumFractionDigits: 2 })}). Please fund your wallet.`);
            return;
        }

        setErrorMessage(null);
        setIsConfirmOpen(true);
    };

    const handleConfirmVerification = async () => {
        setIsVerifying(true);
        setErrorMessage(null);

        try {
            const response = await fetch('/identity/nin/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
                },
                body: JSON.stringify({
                    search_type: searchMode,
                    search_value: searchValue,
                    slip_type: selectedSlip.id,
                }),
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                const msg = data.message || 'Verification failed. Please check the details and try again.';
                setErrorMessage(msg);
                setIsConfirmOpen(false);
                setIsVerifying(false);
                return;
            }

            setIsConfirmOpen(false);
            if (data.redirect_url) {
                window.location.href = data.redirect_url;
                return;
            }
            setVerifiedData(data.data);
            setIsVerifying(false);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } catch (err: any) {
            setIsVerifying(false);
            setIsConfirmOpen(false);
            setErrorMessage('Network connection error. Please try again.');
        }
    };

    const handleResetSearch = () => {
        setVerifiedData(null);
        setSearchValue('');
        setErrorMessage(null);
    };

    const handleCopyText = (text: string) => {
        navigator.clipboard.writeText(text);
        setCopiedRef(true);
        setTimeout(() => setCopiedRef(false), 2000);
    };

    // Payment Sheet Confirmation Details
    const paymentDetails: PaymentDetailItem[] = [
        {
            label: 'Service',
            value: <span className="font-semibold text-gray-900 dark:text-white">NIN Verification</span>,
        },
        {
            label: 'Search Mode',
            value: searchMode === 'phone' ? 'Phone Lookup' : '11-Digit NIN',
        },
        {
            label: searchMode === 'phone' ? 'Phone Number' : 'NIN Number',
            value: <span className="font-mono font-semibold tracking-wider">{searchValue}</span>,
        },
        {
            label: 'Slip Format',
            value: <span className="font-medium text-primary">{selectedSlip.name.endsWith('Slip') ? selectedSlip.name : `${selectedSlip.name} Slip`}</span>,
        },
        {
            label: 'Processing Fee',
            value: <span className="font-bold text-gray-900 dark:text-white">₦{Number(selectedSlip.price).toFixed(2)}</span>,
        },
    ];

    return (
        <>
            <Head title="NIN Verification & Slip Search" />

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
                            NIN Verification
                        </h1>
                        <p className="text-xs text-slate-400 dark:text-slate-400 font-normal truncate mt-0.5">
                            Verify Identity & Print Slips
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
                </div>
            </div>

            {/* MAIN CONTENT CONTAINER */}
            <div className="max-w-2xl mx-auto px-4 py-4 md:py-6 w-full space-y-4">

                {/* ERROR NOTIFICATION BANNER */}
                {errorMessage && (
                    <div className="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 rounded-2xl p-4 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center justify-between gap-3 animate-in fade-in duration-200">
                        <div className="flex items-center gap-2">
                            <AlertCircle className="w-4 h-4 shrink-0 text-rose-500" />
                            <span>{errorMessage}</span>
                        </div>
                        <button
                            onClick={() => setErrorMessage(null)}
                            className="text-xs font-bold opacity-60 hover:opacity-100 cursor-pointer"
                        >
                            ✕
                        </button>
                    </div>
                )}

                {/* SERVICE UNAVAILABLE CARD */}
                {!is_available ? (
                    <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-8 sm:p-10 text-center shadow-xs flex flex-col items-center justify-center space-y-4">
                        <div className="w-16 h-16 rounded-2xl bg-amber-500/10 dark:bg-amber-400/10 flex items-center justify-center text-amber-500">
                            <AlertCircle className="w-8 h-8 stroke-[1.75]" />
                        </div>
                        <div className="space-y-1.5 max-w-md">
                            <h2 className="text-lg font-bold text-gray-900 dark:text-white">
                                Service Currently Unavailable
                            </h2>
                            <p className="text-sm text-gray-500 dark:text-gray-400">
                                This service is currently unavailable. Please contact support to get access or check back later.
                            </p>
                        </div>
                        <div className="flex flex-col sm:flex-row items-center gap-3 pt-2 w-full sm:w-auto">
                            <Link
                                href="/contact"
                                className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-all shadow-xs"
                            >
                                <Headphones className="w-4 h-4" />
                                Contact Support
                            </Link>
                            <Link
                                href={dashboard().url}
                                className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-700 transition-all"
                            >
                                Back to Dashboard
                            </Link>
                        </div>
                    </div>
                ) : (
                    <>
                        {/* ACTIVE VIEW: VERIFIED RESULT CARD OR SEARCH FORM */}
                        {verifiedData ? (
                    /* VERIFIED RECORD DISPLAY & SLIP PREVIEW */
                    <div className="space-y-4 animate-in zoom-in-95 duration-200">
                        {/* Status Card Header */}
                        <div className="bg-white dark:bg-[#181826] border border-emerald-500/30 dark:border-emerald-500/30 rounded-2xl p-4 sm:p-5 shadow-xs relative overflow-hidden">
                            <div className="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none" />

                            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 sm:pb-4 border-b border-gray-100 dark:border-gray-800">
                                <div className="flex items-center gap-3">
                                    <div className="w-11 h-11 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0">
                                        <BadgeCheck className="w-6 h-6 stroke-[2.2]" />
                                    </div>
                                    <div>
                                        <div className="flex items-center gap-2">
                                            <h2 className="text-sm sm:text-base font-extrabold text-gray-900 dark:text-white">
                                                Identity Record Verified
                                            </h2>
                                            <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white">
                                                Verified
                                            </span>
                                        </div>
                                        <p className="text-xs text-slate-400 mt-0.5">
                                            Ref: <span className="font-mono font-medium">{verifiedData.reference}</span>
                                        </p>
                                    </div>
                                </div>

                                <button
                                    onClick={handleResetSearch}
                                    className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 dark:border-gray-700 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors w-fit cursor-pointer"
                                >
                                    <RefreshCw className="w-3.5 h-3.5" />
                                    <span>New Search</span>
                                </button>
                            </div>

                            {/* Bio-Data Summary Grid */}
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 pt-3 sm:pt-4 text-xs">
                                <div className="space-y-1">
                                    <span className="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                        Full Legal Name
                                    </span>
                                    <div className="font-extrabold text-sm text-gray-900 dark:text-white uppercase tracking-tight">
                                        {verifiedData.surname} {verifiedData.firstname} {verifiedData.middlename}
                                    </div>
                                </div>

                                <div className="space-y-1">
                                    <span className="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                        National Identification No. (NIN)
                                    </span>
                                    <div className="flex items-center gap-2 font-mono font-bold text-sm text-primary">
                                        <span>
                                            {showFullNin
                                                ? verifiedData.nin
                                                : `${verifiedData.nin.slice(0, 3)}••••••${verifiedData.nin.slice(-2)}`}
                                        </span>
                                        <button
                                            type="button"
                                            onClick={() => setShowFullNin(!showFullNin)}
                                            className="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-0.5 cursor-pointer"
                                            title={showFullNin ? 'Hide NIN' : 'Show Full NIN'}
                                        >
                                            {showFullNin ? <EyeOff className="w-3.5 h-3.5" /> : <Eye className="w-3.5 h-3.5" />}
                                        </button>
                                        <button
                                            type="button"
                                            onClick={() => handleCopyText(verifiedData.nin)}
                                            className="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-0.5 cursor-pointer"
                                            title="Copy NIN"
                                        >
                                            {copiedRef ? <Check className="w-3.5 h-3.5 text-emerald-500" /> : <Copy className="w-3.5 h-3.5" />}
                                        </button>
                                    </div>
                                </div>

                                <div className="space-y-1">
                                    <span className="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                        Tracking ID
                                    </span>
                                    <div className="font-mono font-medium text-gray-800 dark:text-gray-200">
                                        {verifiedData.tracking_id}
                                    </div>
                                </div>

                                <div className="space-y-1">
                                    <span className="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                        Date of Birth & Gender
                                    </span>
                                    <div className="font-medium text-gray-800 dark:text-gray-200">
                                        {verifiedData.dob} · {verifiedData.gender}
                                    </div>
                                </div>

                                <div className="space-y-1">
                                    <span className="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                        Linked Phone Number
                                    </span>
                                    <div className="font-mono font-medium text-gray-800 dark:text-gray-200">
                                        {verifiedData.phone}
                                    </div>
                                </div>

                                <div className="space-y-1">
                                    <span className="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                        Origin (State & LGA)
                                    </span>
                                    <div className="font-medium text-gray-800 dark:text-gray-200">
                                        {verifiedData.lga}, {verifiedData.state_of_origin}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* SLIP FORMAT VISUAL PREVIEW CARD */}
                        <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs space-y-4">
                            <div className="flex items-center justify-between">
                                <div className="flex items-center gap-2">
                                    <FileText className="w-4 h-4 text-primary" />
                                    <h3 className="font-extrabold text-sm text-gray-900 dark:text-white">
                                        Generated Slip Preview
                                    </h3>
                                </div>
                                <span className="px-2 py-0.5 rounded text-[11px] font-bold bg-primary/10 text-primary">
                                    {verifiedData.slip_name}
                                </span>
                            </div>

                            {/* Official Template Preview Image */}
                            <div className="relative rounded-2xl border border-gray-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-900/90 p-3 sm:p-4 flex flex-col items-center justify-center overflow-hidden">
                                {SLIP_SAMPLE_IMAGES[selectedSlip.id] ? (
                                    <div className="relative group max-h-72 w-full flex items-center justify-center">
                                        <img
                                            src={SLIP_SAMPLE_IMAGES[selectedSlip.id]}
                                            alt={verifiedData.slip_name}
                                            className="max-h-64 sm:max-h-72 max-w-full object-contain rounded-xl shadow-md transition-transform group-hover:scale-102"
                                        />
                                        <button
                                            type="button"
                                            onClick={() => setSampleModalImg({ title: verifiedData.slip_name, src: SLIP_SAMPLE_IMAGES[selectedSlip.id] })}
                                            className="absolute top-2 right-2 p-1.5 rounded-lg bg-black/60 text-white hover:bg-black/80 transition-colors shadow-xs cursor-pointer flex items-center gap-1 text-[11px]"
                                            title="Enlarge Sample Preview"
                                        >
                                            <ZoomIn className="w-3.5 h-3.5" />
                                            <span className="hidden sm:inline">Enlarge</span>
                                        </button>
                                    </div>
                                ) : (
                                    <div className="w-full py-8 text-center text-xs text-slate-400">
                                        Ready for PDF generation
                                    </div>
                                )}

                                <div className="w-full mt-3 pt-2.5 border-t border-gray-200 dark:border-gray-800 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                                    <span>Tracking ID: <span className="font-mono font-medium">{verifiedData.tracking_id}</span></span>
                                    <span className="text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1">
                                        <ShieldCheck className="w-3.5 h-3.5" /> Digital Verification Ready
                                    </span>
                                </div>
                            </div>

                            {/* Download & Print Actions */}
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3 pt-1">
                                <button
                                    type="button"
                                    onClick={() => {
                                        if (verifiedData.slip_download_url) {
                                            window.open(verifiedData.slip_download_url, '_blank');
                                        } else {
                                            window.print();
                                        }
                                    }}
                                    className="w-full py-3 px-4 rounded-xl bg-primary text-white font-extrabold text-xs shadow-md shadow-primary/20 hover:opacity-90 active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer"
                                >
                                    <Download className="w-4 h-4" />
                                    <span>Download Official PDF Slip</span>
                                </button>

                                <button
                                    type="button"
                                    onClick={() => window.print()}
                                    className="w-full py-3 px-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#181826] font-extrabold text-xs text-gray-800 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer"
                                >
                                    <Printer className="w-4 h-4" />
                                    <span>Print Verification Record</span>
                                </button>
                            </div>
                        </div>
                    </div>
                ) : (
                    /* SEARCH & SLIP SELECTION VIEW */
                    <div className="space-y-4">
                        {/* 1. SEGMENTED SEARCH MODE SWITCHER */}
                        <div className="bg-white dark:bg-[#181826] p-1.5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-xs flex">
                            <button
                                type="button"
                                onClick={() => {
                                    setSearchMode('nin');
                                    setSearchValue('');
                                    setErrorMessage(null);
                                }}
                                className={`flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer ${
                                    searchMode === 'nin'
                                        ? 'bg-primary text-white shadow-sm'
                                        : 'text-slate-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'
                                }`}
                            >
                                <IdCard className="w-4 h-4" />
                                <span>By 11-Digit NIN</span>
                            </button>

                            <button
                                type="button"
                                onClick={() => {
                                    setSearchMode('phone');
                                    setSearchValue('');
                                    setErrorMessage(null);
                                }}
                                className={`flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer ${
                                    searchMode === 'phone'
                                        ? 'bg-primary text-white shadow-sm'
                                        : 'text-slate-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'
                                }`}
                            >
                                <Smartphone className="w-4 h-4" />
                                <span>By Phone Number</span>
                            </button>
                        </div>

                        {/* 2. SEARCH INPUT CARD */}
                        <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs space-y-3">
                            <div className="flex items-center justify-between">
                                <label className="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                                    {searchMode === 'phone' ? (
                                        <>
                                            <Smartphone className="w-3.5 h-3.5 text-primary" />
                                            <span>Registered Phone Number</span>
                                        </>
                                    ) : (
                                        <>
                                            <IdCard className="w-3.5 h-3.5 text-primary" />
                                            <span>11-Digit National Identity Number</span>
                                        </>
                                    )}
                                </label>

                                <span
                                    className={`text-[11px] font-mono font-bold px-2 py-0.5 rounded-full ${
                                        isInputValid
                                            ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                            : 'bg-gray-100 dark:bg-gray-800 text-slate-400'
                                    }`}
                                >
                                    {searchValue.length} / 11
                                </span>
                            </div>

                            {/* Main Input Box with Actions */}
                            <div className="relative flex items-center">
                                <input
                                    type="tel"
                                    inputMode="numeric"
                                    pattern="[0-9]*"
                                    maxLength={11}
                                    value={searchValue}
                                    onChange={handleSearchValueChange}
                                    placeholder={
                                        searchMode === 'phone'
                                            ? 'e.g. 08012345678'
                                            : 'e.g. 12345678901'
                                    }
                                    className="w-full pl-4 pr-24 py-3.5 bg-gray-50/80 dark:bg-gray-900/80 border border-gray-200 dark:border-gray-700 rounded-xl text-base sm:text-lg font-mono font-bold tracking-wider text-gray-900 dark:text-white placeholder:text-slate-400 placeholder:font-normal focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"
                                />

                                <div className="absolute right-2.5 flex items-center gap-1">
                                    {isInputValid ? (
                                        <div className="p-1 text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg">
                                            <CheckCircle2 className="w-5 h-5 stroke-[2.2]" />
                                        </div>
                                    ) : (
                                        <button
                                            type="button"
                                            onClick={handlePaste}
                                            className="px-2.5 py-1 text-[11px] font-bold text-primary hover:bg-primary/10 rounded-lg transition-colors cursor-pointer"
                                        >
                                            Paste
                                        </button>
                                    )}
                                </div>
                            </div>

                            <p className="text-[11px] text-slate-400 flex items-center gap-1">
                                <Lock className="w-3 h-3 text-slate-400" />
                                {searchMode === 'phone'
                                    ? 'Enter the 11-digit phone number linked to the customer\'s identity record.'
                                    : 'Enter the 11-digit NIN printed on the customer\'s slip or national identity card.'}
                            </p>
                        </div>

                        {/* 3. SLIP SELECTION (4 SLIPS: INFORMATION, REGULAR, STANDARD, PREMIUM IN A 2-COLUMN GRID ON MOBILE) */}
                        <div className="space-y-2.5">
                            <div className="flex items-center justify-between">
                                <div className="flex items-center gap-2">
                                    <Layers className="w-4 h-4 text-primary" />
                                    <h2 className="font-extrabold text-sm text-gray-900 dark:text-white">
                                        Select Slip Type
                                    </h2>
                                </div>
                                <span className="text-xs text-slate-400">Charged per slip</span>
                            </div>

                            {/* 2-Column Grid on Mobile (2 per row), 4-Column on Desktop */}
                            {slips.length === 0 ? (
                                <div className="rounded-2xl border border-dashed border-gray-200 dark:border-gray-800 p-8 text-center bg-gray-50/50 dark:bg-gray-900/40">
                                    <div className="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto mb-3">
                                        <AlertCircle className="w-6 h-6" />
                                    </div>
                                    <h3 className="font-extrabold text-sm text-gray-900 dark:text-white">
                                        No Verification Slips Available
                                    </h3>
                                    <p className="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                        There are currently no verification slips configured or active for this store. Please check back later or contact store support.
                                    </p>
                                </div>
                            ) : (
                                <div className="grid grid-cols-2 md:grid-cols-4 gap-2.5 sm:gap-3">
                                    {slips.map((slip) => {
                                    const isSelected = selectedSlipId === slip.id;
                                    const sampleSrc = SLIP_SAMPLE_IMAGES[slip.id] || `/images/slips/nin-${slip.id}.png`;

                                    return (
                                        <div
                                            key={slip.id}
                                            onClick={() => {
                                                setSelectedSlipId(slip.id);
                                                setPreviewStageSlipId(null);
                                            }}
                                            className={`relative rounded-2xl p-2.5 sm:p-3.5 transition-all duration-200 cursor-pointer border flex flex-col justify-between group ${
                                                isSelected
                                                    ? 'bg-primary/5 dark:bg-primary/10 border-primary ring-2 ring-primary/20 shadow-md'
                                                    : 'bg-white dark:bg-[#181826] border-gray-100 dark:border-gray-800 hover:border-gray-300 dark:hover:border-gray-700 shadow-xs'
                                            }`}
                                        >
                                            {/* Top Row: Badge & Radio Check */}
                                            <div className="flex items-center justify-between mb-2">
                                                <div className="flex items-center gap-1 min-w-0">
                                                    <span className="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-800 text-slate-600 dark:text-slate-300 truncate">
                                                        {slip.badge}
                                                    </span>
                                                    {slip.is_popular && (
                                                        <span className="px-1.5 py-0.5 text-[8px] font-black uppercase tracking-wider bg-amber-500 text-white rounded-full">
                                                            ★
                                                        </span>
                                                    )}
                                                </div>

                                                <div
                                                    className={`w-4 h-4 rounded-full border flex items-center justify-center shrink-0 ${
                                                        isSelected
                                                            ? 'border-primary bg-primary text-white'
                                                            : 'border-gray-300 dark:border-gray-600'
                                                    }`}
                                                >
                                                    {isSelected && <Check className="w-2.5 h-2.5 stroke-[3]" />}
                                                </div>
                                            </div>

                                            {/* REAL SAMPLE PREVIEW THUMBNAIL */}
                                            <div
                                                className="relative w-full h-24 sm:h-28 rounded-xl bg-slate-50 dark:bg-gray-900/80 border border-gray-100 dark:border-gray-800/80 p-1 flex items-center justify-center overflow-hidden mb-2.5 cursor-pointer"
                                                onClick={(e) => {
                                                    e.stopPropagation();
                                                    if (!isSelected) {
                                                        // 1st click on unselected: only select the slip
                                                        setSelectedSlipId(slip.id);
                                                        setPreviewStageSlipId(null);
                                                    } else if (previewStageSlipId !== slip.id) {
                                                        // 2nd click on selected: show preview prompt in center
                                                        setPreviewStageSlipId(slip.id);
                                                    } else {
                                                        // 3rd click: open the sample modal
                                                        setSampleModalImg({
                                                            title: `${slip.name.endsWith('Slip') ? slip.name : `${slip.name} Slip`} Sample`,
                                                            src: sampleSrc,
                                                        });
                                                        setPreviewStageSlipId(null);
                                                    }
                                                }}
                                            >
                                                <img
                                                    src={sampleSrc}
                                                    alt={`${slip.name} Sample`}
                                                    className="max-h-full max-w-full object-contain rounded-md shadow-2xs group-hover:scale-105 transition-transform duration-200"
                                                    loading="lazy"
                                                />
                                                {isSelected && previewStageSlipId === slip.id && (
                                                    <div className="absolute inset-0 bg-black/50 backdrop-blur-[1px] transition-all rounded-xl flex items-center justify-center p-2 animate-in fade-in zoom-in-95 duration-150">
                                                        <span className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-primary text-primary-foreground text-[11px] font-black shadow-lg">
                                                            <ZoomIn className="w-3.5 h-3.5" />
                                                            <span>Preview</span>
                                                        </span>
                                                    </div>
                                                )}
                                            </div>

                                            {/* Title & Description */}
                                            <div>
                                                <h3 className="font-extrabold text-xs sm:text-sm text-gray-900 dark:text-white leading-tight">
                                                    {slip.name}
                                                </h3>
                                                <p className="text-[10px] text-slate-400 mt-0.5 line-clamp-1 leading-tight">
                                                    {slip.description}
                                                </p>
                                            </div>

                                            {/* Price Footer */}
                                            <div className="mt-2.5 pt-2 border-t border-gray-100 dark:border-gray-800/80 flex items-baseline justify-between">
                                                <span className="text-[9px] text-slate-400 font-medium">Price</span>
                                                <span className="text-xs sm:text-sm font-black text-gray-900 dark:text-white font-mono">
                                                    ₦{Number(slip.price).toFixed(2)}
                                                </span>
                                            </div>
                                        </div>
                                    );
                                })}
                                </div>
                            )}
                        </div>

                        {/* 4. SUMMARY & ACTION BUTTON */}
                        <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs space-y-3.5">
                            <div className="flex items-center justify-between text-xs pb-2.5 border-b border-gray-100 dark:border-gray-800">
                                <span className="text-slate-500">Selected Slip</span>
                                <span className="font-extrabold text-gray-900 dark:text-white">
                                    {slips.length > 0 ? selectedSlip.name : 'None available'}
                                </span>
                            </div>

                            <div className="flex items-center justify-between text-xs pb-2.5 border-b border-gray-100 dark:border-gray-800">
                                <span className="text-slate-500">Service Fee</span>
                                <span className="font-mono font-black text-base text-primary">
                                    ₦{slips.length > 0 ? Number(selectedSlip.price).toFixed(2) : '0.00'}
                                </span>
                            </div>

                            <div className="flex items-center justify-between text-xs">
                                <span className="text-slate-500">Balance After Transaction</span>
                                <span className="font-mono font-bold text-gray-700 dark:text-gray-300">
                                    ₦{Math.max(0, wallet_balance - (slips.length > 0 ? selectedSlip.price : 0)).toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                                </span>
                            </div>

                            {/* Submit Button */}
                            <button
                                type="button"
                                disabled={!isInputValid || isVerifying || isInsufficientBalance || slips.length === 0}
                                onClick={handleInitiateVerification}
                                className={`w-full py-3.5 sm:py-4 px-6 rounded-xl font-extrabold text-sm tracking-wide shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer ${
                                    isInputValid && !isInsufficientBalance && slips.length > 0
                                        ? 'bg-primary text-white shadow-primary/25 hover:opacity-95 active:scale-98'
                                        : 'bg-gray-200 dark:bg-gray-800 text-gray-400 cursor-not-allowed shadow-none'
                                }`}
                            >
                                {slips.length === 0 ? (
                                    <>
                                        <AlertCircle className="w-4 h-4" />
                                        <span>No Slips Available</span>
                                    </>
                                ) : isVerifying ? (
                                    <>
                                        <RefreshCw className="w-4 h-4 animate-spin" />
                                        <span>Querying Database...</span>
                                    </>
                                ) : isInsufficientBalance ? (
                                    <>
                                        <AlertCircle className="w-4 h-4" />
                                        <span>Insufficient Balance (Fund Wallet)</span>
                                    </>
                                ) : (
                                    <>
                                        <ShieldCheck className="w-4 h-4" />
                                        <span>Verify & Generate Slip · ₦{Number(selectedSlip.price).toFixed(2)}</span>
                                    </>
                                )}
                            </button>
                        </div>
                    </div>
                )}

                {/* 5. RECENT VERIFICATIONS FOR USER */}
                {recent_verifications && recent_verifications.length > 0 && (
                    <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs space-y-3">
                        <div className="flex items-center justify-between">
                            <h3 className="font-extrabold text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Recent Searches & Slips
                            </h3>
                            <Link href="/transactions" className="text-xs font-bold text-primary hover:opacity-85">
                                View all
                            </Link>
                        </div>

                        <div className="divide-y divide-gray-100 dark:divide-gray-800">
                            {recent_verifications.map((item) => (
                                <div key={item.id} className="py-2.5 flex items-center justify-between text-xs">
                                    <div className="flex items-center gap-2.5">
                                        <div className="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0">
                                            <IdCard className="w-4 h-4" />
                                        </div>
                                        <div>
                                            <div className="font-mono font-bold text-gray-900 dark:text-white">
                                                {item.recipient || item.reference}
                                            </div>
                                            <div className="text-[10px] text-slate-400">
                                                {item.created_at}
                                            </div>
                                        </div>
                                    </div>

                                    <div className="flex items-center gap-2">
                                        <div className="text-right">
                                            <div className="font-mono font-bold text-gray-900 dark:text-white">
                                                ₦{Number(item.amount_paid).toFixed(2)}
                                            </div>
                                            <span className="text-[10px] font-bold text-emerald-500 uppercase">
                                                {item.status}
                                            </span>
                                        </div>
                                        {item.slip_download_url && (
                                            <a
                                                href={item.slip_download_url}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="p-1.5 rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition-colors"
                                                title="Download Slip"
                                            >
                                                <Download className="w-3.5 h-3.5" />
                                            </a>
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}
                    </>
                )}

                {/* DATA PRIVACY & COMPLIANCE FOOTER */}
                <div className="text-center pt-2 pb-6 space-y-1">
                    <p className="text-[11px] text-slate-400 flex items-center justify-center gap-1 font-medium">
                        <ShieldCheck className="w-3.5 h-3.5 text-emerald-500" />
                        Official Identity Verification & Slip Generation
                    </p>
                    <p className="text-[10px] text-slate-400/80">
                        Protected by end-to-end data encryption and national data privacy standards.
                    </p>
                </div>
            </div>

            {/* CONFIRMATION PAYMENT SHEET */}
            <ConfirmPaymentSheet
                isOpen={isConfirmOpen}
                onOpenChange={setIsConfirmOpen}
                title="Confirm NIN Verification"
                amount={selectedSlip.price}
                details={paymentDetails}
                walletBalance={wallet_balance}
                confirmButtonText="Confirm & Verify"
                isSubmitting={isVerifying}
                onConfirm={handleConfirmVerification}
                fundWalletUrl="/wallet"
            />

            {/* HIGH-RES SAMPLE SLIP LIGHTBOX MODAL */}
            <Dialog open={!!sampleModalImg} onOpenChange={(open) => !open && setSampleModalImg(null)}>
                <DialogContent className="max-w-md sm:max-w-lg p-4 sm:p-6 bg-white dark:bg-[#181826] rounded-2xl sm:rounded-3xl border border-gray-100 dark:border-gray-800">
                    <DialogHeader className="pb-2">
                        <DialogTitle className="text-sm sm:text-base font-extrabold text-gray-900 dark:text-white">
                            {sampleModalImg?.title || 'Slip Sample Preview'}
                        </DialogTitle>
                    </DialogHeader>

                    {sampleModalImg && (
                        <div className="relative w-full max-h-[70vh] flex items-center justify-center overflow-auto rounded-xl bg-slate-50 dark:bg-gray-900/90 p-2 border border-gray-100 dark:border-gray-800">
                            <img
                                src={sampleModalImg.src}
                                alt={sampleModalImg.title}
                                className="max-h-[65vh] max-w-full object-contain rounded-lg shadow-md"
                            />
                        </div>
                    )}
                </DialogContent>
            </Dialog>
        </>
    );
}

NinVerificationPage.layout = {
    breadcrumbs: [
        {
            title: 'NIN Verification',
            href: '/identity/nin',
        },
    ],
};
