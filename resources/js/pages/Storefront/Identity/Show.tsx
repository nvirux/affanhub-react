import { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import {
    CheckCircle2, Copy, Check, ArrowLeft, Printer,
    FileText, ExternalLink, Download, RefreshCw, Eye, EyeOff,
    ShieldCheck, ChevronLeft, Headphones, UserCheck, Building2
} from 'lucide-react';

interface VerificationShowProps {
    verification: {
        id: number;
        reference: string;
        provider_reference?: string | null;
        service_type: 'nin' | 'bvn';
        service_name: string;
        search_type: string;
        search_value: string;
        recipient_name?: string | null;
        tracking_id?: string | null;
        photo?: string | null;
        status: string;
        fee_charged: number;
        slip_name: string;
        data: Record<string, any>;
        preview_url: string;
        download_url: string;
        new_search_url: string;
        created_at: string;
    };
    store_support: {
        name: string;
        whatsapp?: string | null;
    };
}

/**
 * Format base64 photo safely as a Data URI so the browser never treats raw base64 as a relative URL.
 */
function formatPhotoBase64(rawPhoto?: string | null): string | null {
    if (!rawPhoto) return null;
    const trimmed = String(rawPhoto).trim();
    if (!trimmed || trimmed === 'null' || trimmed === 'undefined') return null;

    // Already a complete data URI
    if (trimmed.startsWith('data:image/')) {
        return trimmed;
    }

    // Clean any accidental surrounding quotes
    const clean = trimmed.replace(/^["']|["']$/g, '');

    // In case it is an absolute http/https URL
    if (clean.startsWith('http://') || clean.startsWith('https://')) {
        return clean;
    }

    // It's raw base64 (e.g. /9j/... for JPEG, iVBORw... for PNG)
    const mime = clean.startsWith('iVBORw') ? 'image/png' : 'image/jpeg';
    return `data:${mime};base64,${clean}`;
}

export default function IdentityVerificationShow({
    verification,
    store_support,
}: VerificationShowProps) {
    const [copiedRef, setCopiedRef] = useState(false);
    const [copiedId, setCopiedId] = useState(false);
    const [showFullId, setShowFullId] = useState(false);

    const isBvn = verification.service_type === 'bvn';
    const cData = verification.data || {};
    const photoSrc = formatPhotoBase64(verification.photo || cData.photo || cData.image);

    const primaryNumber = isBvn
        ? (cData.bvn || verification.search_value)
        : (cData.nin || verification.search_value);

    const handleCopyRef = () => {
        navigator.clipboard.writeText(verification.reference);
        setCopiedRef(true);
        setTimeout(() => setCopiedRef(false), 2000);
    };

    const handleCopyId = () => {
        navigator.clipboard.writeText(primaryNumber);
        setCopiedId(true);
        setTimeout(() => setCopiedId(false), 2000);
    };

    const handlePrint = () => {
        window.print();
    };

    const supportUrl = store_support.whatsapp
        ? `https://wa.me/${store_support.whatsapp.replace(/\D/g, '')}?text=${encodeURIComponent(
              `Hello ${store_support.name}, I need assistance with verification ${verification.reference} (${verification.service_name})`
          )}`
        : '/contact';

    return (
        <>
            <Head title={`Verified Slip: ${verification.recipient_name || verification.reference}`} />

            {/* MOBILE ONLY TOP BAR (Hidden on Desktop) */}
            <div className="bg-white dark:bg-[#181826] border-b border-slate-100 dark:border-slate-800/80 px-4 py-3 sticky top-0 z-20 flex md:hidden items-center justify-between print:hidden">
                <div className="flex items-center gap-3 min-w-0">
                    <Link
                        href={verification.new_search_url}
                        className="p-1 -ml-1 text-slate-500 hover:text-slate-800 dark:hover:text-white transition-colors cursor-pointer"
                        title="Back"
                    >
                        <ChevronLeft className="w-6 h-6 stroke-[2.2]" />
                    </Link>
                    <div className="min-w-0">
                        <h1 className="font-extrabold text-base text-gray-900 dark:text-white tracking-tight leading-tight truncate">
                            {verification.service_name}
                        </h1>
                        <p className="text-xs text-slate-400 dark:text-slate-400 font-normal truncate mt-0.5">
                            Order {verification.reference}
                        </p>
                    </div>
                </div>

                <div className="flex items-center gap-2 shrink-0 ml-2">
                    <button
                        type="button"
                        onClick={handlePrint}
                        className="p-1.5 text-slate-600 dark:text-slate-300 hover:text-primary transition-colors cursor-pointer"
                        title="Print Page"
                    >
                        <Printer className="w-5 h-5 stroke-[1.8]" />
                    </button>
                    <a
                        href={supportUrl}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="p-1.5 text-slate-600 dark:text-slate-300 hover:text-emerald-500 transition-colors cursor-pointer"
                        title="Customer Support"
                    >
                        <Headphones className="w-5 h-5 stroke-[1.8]" />
                    </a>
                </div>
            </div>

            {/* MAIN CONTENT CONTAINER */}
            <div className="min-h-screen bg-slate-50/60 dark:bg-[#0f0f18] py-4 sm:py-8 px-3 sm:px-6 lg:px-8">
                <div className="max-w-xl mx-auto space-y-4 sm:space-y-6">



                    {/* 1. STATUS SUCCESS CARD */}
                    <div className="bg-white dark:bg-[#181826] border border-emerald-500/30 dark:border-emerald-500/30 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-xs relative overflow-hidden">
                        <div className="absolute top-0 right-0 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none" />

                        <div className="flex items-center gap-3.5 mb-4">
                            <div className="w-12 h-12 rounded-2xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 shadow-sm">
                                <CheckCircle2 className="w-6 h-6 stroke-[2.4]" />
                            </div>
                            <div>
                                <div className="flex items-center gap-2">
                                    <h2 className="text-base sm:text-lg font-black text-gray-900 dark:text-white tracking-tight">
                                        Verification Successful
                                    </h2>
                                    <span className="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500 text-white">
                                        Verified
                                    </span>
                                </div>
                                <p className="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Official slip generated · {verification.created_at}
                                </p>
                            </div>
                        </div>

                        {/* Order Reference Strip */}
                        <div className="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-slate-800 text-xs">
                            <span className="font-semibold text-slate-400">Order Reference</span>
                            <div className="flex items-center gap-2">
                                <span className="font-mono font-bold text-gray-800 dark:text-gray-200">
                                    {verification.reference}
                                </span>
                                <button
                                    type="button"
                                    onClick={handleCopyRef}
                                    className="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-0.5 cursor-pointer"
                                    title="Copy Reference"
                                >
                                    {copiedRef ? <Check className="w-3.5 h-3.5 text-emerald-500" /> : <Copy className="w-3.5 h-3.5" />}
                                </button>
                            </div>
                        </div>
                    </div>

                    {/* 2. VERIFIED CITIZEN BIO-DATA PROFILE CARD */}
                    <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-xs space-y-3.5 sm:space-y-4">
                        <div className="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800">
                            <div className="flex items-center gap-2.5">
                                <UserCheck className="w-5 h-5 text-primary" />
                                <h3 className="font-extrabold text-sm sm:text-base text-gray-900 dark:text-white">
                                    Verified Bio-Data Profile
                                </h3>
                            </div>
                            <span className="text-xs font-semibold text-slate-400">
                                {isBvn ? 'BVN Record' : 'NIMC Record'}
                            </span>
                        </div>

                        {/* Citizen Portrait Photo & Name Header (Flex on Mobile & Desktop) */}
                        {photoSrc ? (
                            <div className="flex items-center gap-3 p-3 rounded-2xl bg-slate-50/80 dark:bg-gray-900/50 border border-slate-100 dark:border-slate-800/80">
                                <div className="relative shrink-0 p-1 rounded-xl border-2 border-primary/20 bg-white dark:bg-gray-800 shadow-xs">
                                    <img
                                        src={photoSrc}
                                        alt="Citizen Portrait"
                                        className="w-16 h-20 sm:w-20 sm:h-24 object-cover rounded-lg shadow-xs"
                                    />
                                    <div className="absolute -bottom-1.5 -right-1.5 bg-emerald-500 text-white p-0.5 rounded-full shadow-xs">
                                        <ShieldCheck className="w-3.5 h-3.5 stroke-[2.5]" />
                                    </div>
                                </div>
                                <div className="min-w-0 flex-1 space-y-1">
                                    <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                        Full Legal Name
                                    </span>
                                    <div className="font-black text-xs sm:text-sm text-gray-900 dark:text-white uppercase tracking-tight leading-snug">
                                        {cData.full_name || `${cData.firstname || ''} ${cData.middlename || ''} ${cData.surname || ''}`.trim() || verification.recipient_name || 'N/A'}
                                    </div>
                                    <div className="pt-0.5">
                                        <span className="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-primary/10 text-primary">
                                            <ShieldCheck className="w-3 h-3" />
                                            Verified Identity
                                        </span>
                                    </div>
                                </div>
                            </div>
                        ) : null}

                        {/* Bio-Data Grid (Flex / 2 Columns on Mobile & Desktop) */}
                        <div className="grid grid-cols-2 gap-2 sm:gap-3 text-xs">
                            {!photoSrc && (
                                <div className="col-span-2 space-y-1 p-2.5 rounded-xl bg-slate-50/70 dark:bg-gray-900/40">
                                    <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                        Full Legal Name
                                    </span>
                                    <div className="font-black text-xs sm:text-sm text-gray-900 dark:text-white uppercase tracking-tight">
                                        {cData.full_name || `${cData.firstname || ''} ${cData.middlename || ''} ${cData.surname || ''}`.trim() || verification.recipient_name || 'N/A'}
                                    </div>
                                </div>
                            )}

                            <div className="col-span-2 sm:col-span-1 space-y-1 p-2.5 rounded-xl bg-slate-50/70 dark:bg-gray-900/40">
                                <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    {isBvn ? 'Bank Verification No. (BVN)' : 'National Identification No. (NIN)'}
                                </span>
                                <div className="flex items-center gap-2 font-mono font-bold text-xs sm:text-sm text-primary">
                                    <span>
                                        {showFullId
                                            ? primaryNumber
                                            : `${primaryNumber.slice(0, 3)}••••••${primaryNumber.slice(-2)}`}
                                    </span>
                                    <button
                                        type="button"
                                        onClick={() => setShowFullId(!showFullId)}
                                        className="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-0.5 cursor-pointer"
                                        title={showFullId ? 'Hide' : 'Show Full'}
                                    >
                                        {showFullId ? <EyeOff className="w-3.5 h-3.5" /> : <Eye className="w-3.5 h-3.5" />}
                                    </button>
                                    <button
                                        type="button"
                                        onClick={handleCopyId}
                                        className="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-0.5 cursor-pointer"
                                        title="Copy"
                                    >
                                        {copiedId ? <Check className="w-3.5 h-3.5 text-emerald-500" /> : <Copy className="w-3.5 h-3.5" />}
                                    </button>
                                </div>
                            </div>

                            {(cData.dob || cData.gender) && (
                                <div className="space-y-1 p-2.5 rounded-xl bg-slate-50/70 dark:bg-gray-900/40">
                                    <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                        Date of Birth & Gender
                                    </span>
                                    <div className="font-semibold text-gray-800 dark:text-gray-200 truncate">
                                        {cData.dob || 'N/A'} · {cData.gender || 'N/A'}
                                    </div>
                                </div>
                            )}

                            {cData.tracking_id && (
                                <div className="space-y-1 p-2.5 rounded-xl bg-slate-50/70 dark:bg-gray-900/40">
                                    <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                        Tracking ID
                                    </span>
                                    <div className="font-mono font-semibold text-gray-800 dark:text-gray-200 truncate">
                                        {cData.tracking_id}
                                    </div>
                                </div>
                            )}

                            {cData.phone && (
                                <div className="space-y-1 p-2.5 rounded-xl bg-slate-50/70 dark:bg-gray-900/40">
                                    <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                        Linked Phone Number
                                    </span>
                                    <div className="font-mono font-semibold text-gray-800 dark:text-gray-200 truncate">
                                        {cData.phone}
                                    </div>
                                </div>
                            )}

                            {(cData.lga || cData.state_of_origin) && (
                                <div className="space-y-1 p-2.5 rounded-xl bg-slate-50/70 dark:bg-gray-900/40">
                                    <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                        State & LGA
                                    </span>
                                    <div className="font-semibold text-gray-800 dark:text-gray-200 truncate">
                                        {cData.lga ? `${cData.lga}, ` : ''}{cData.state_of_origin || ''}
                                    </div>
                                </div>
                            )}

                            {isBvn && cData.enrollment_bank && (
                                <div className="col-span-2 sm:col-span-1 space-y-1 p-2.5 rounded-xl bg-slate-50/70 dark:bg-gray-900/40">
                                    <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                        Enrollment Bank & Branch
                                    </span>
                                    <div className="font-semibold text-gray-800 dark:text-gray-200">
                                        {cData.enrollment_bank} {cData.branch ? `(${cData.branch})` : ''}
                                    </div>
                                </div>
                            )}

                            {cData.address && (
                                <div className="col-span-2 space-y-1 p-2.5 rounded-xl bg-slate-50/70 dark:bg-gray-900/40">
                                    <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                        Residential Address
                                    </span>
                                    <div className="font-medium text-gray-800 dark:text-gray-200 leading-relaxed">
                                        {cData.address}
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* 3. PAYMENT & AUDIT SUMMARY */}
                    <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 text-xs text-slate-600 dark:text-slate-300 space-y-2">
                        <div className="flex items-center justify-between pb-2 border-b border-gray-100 dark:border-gray-800">
                            <span className="font-semibold text-slate-400">Document Type</span>
                            <span className="font-bold text-gray-900 dark:text-white">
                                {verification.slip_name}
                            </span>
                        </div>
                        <div className="flex items-center justify-between pb-2 border-b border-gray-100 dark:border-gray-800">
                            <span className="font-semibold text-slate-400">Amount Charged</span>
                            <span className="font-bold text-gray-900 dark:text-white">
                                ₦{verification.fee_charged.toFixed(2)}
                            </span>
                        </div>
                        <div className="flex items-center justify-between">
                            <span className="font-semibold text-slate-400">Verification Status</span>
                            <span className="font-bold text-emerald-600 dark:text-emerald-400 uppercase text-[11px]">
                                {verification.status}
                            </span>
                        </div>
                    </div>

                    {/* 4. BOTTOM ACTIONS (PREVIEW, DIRECT DOWNLOAD & VERIFY ANOTHER) */}
                    <div className="space-y-2.5 pt-2 print:hidden">
                        {/* Preview and Direct Download flex side-by-side on mobile too */}
                        <div className="grid grid-cols-2 gap-2.5">
                            <a
                                href={verification.preview_url}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-3 rounded-xl bg-white dark:bg-[#181826] border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 text-xs sm:text-sm font-extrabold hover:bg-gray-50 dark:hover:bg-gray-800/80 hover:border-primary/50 transition-all shadow-xs active:scale-98 cursor-pointer text-center"
                            >
                                <ExternalLink className="w-4 h-4 text-primary shrink-0" />
                                <span className="truncate">Preview Slip</span>
                            </a>

                            <a
                                href={verification.download_url}
                                className="flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-3 rounded-xl bg-primary hover:bg-primary/90 text-primary-foreground text-xs sm:text-sm font-extrabold transition-all shadow-sm shadow-primary/25 active:scale-98 cursor-pointer text-center"
                            >
                                <Download className="w-4 h-4 stroke-[2.2] shrink-0" />
                                <span className="truncate">Direct Download</span>
                            </a>
                        </div>

                        {/* Verify Another button below */}
                        <Link
                            href={verification.new_search_url}
                            className="w-full flex items-center justify-center gap-2 px-4 py-3.5 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs sm:text-sm font-black hover:opacity-90 transition-all shadow-xs cursor-pointer"
                        >
                            <RefreshCw className="w-4 h-4" />
                            <span>Verify Another {isBvn ? 'BVN' : 'NIN'}</span>
                        </Link>
                    </div>

                </div>
            </div>
        </>
    );
}

IdentityVerificationShow.layout = {
    breadcrumbs: [
        {
            title: 'Identity Verification',
            href: '/identity/nin',
        },
        {
            title: 'Verified Record',
            href: '#',
        },
    ],
};
