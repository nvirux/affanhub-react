import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { dashboard } from '@/routes';
import {
    ChevronLeft, ShieldCheck, CheckCircle2, Clock, AlertCircle,
    UploadCloud, FileCheck, ArrowRight, XCircle, Eye, Download,
    Briefcase, Building2, Search, FileText, Check, AlertTriangle
} from 'lucide-react';
import { ConfirmPaymentSheet, PaymentDetailItem } from '@/components/confirm-payment-sheet';
import { TransactionPinSheet } from '@/components/transaction-pin-sheet';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

export interface CacFieldConfig {
    label: string;
    placeholder?: string;
    type?: string;
    is_required: boolean;
}

export interface CacDocumentConfig {
    name: string;
    is_required: boolean;
    description?: string | null;
}

export interface CacPackageItem {
    id: number;
    name: string;
    slug: string;
    description?: string;
    price: number;
    processing_time: string;
    fields: CacFieldConfig[];
    required_documents: CacDocumentConfig[];
    instructions?: string;
}

export interface CacOrderItem {
    id: number;
    reference: string;
    service_name: string;
    business_name: string | null;
    amount_paid: number;
    status: 'pending' | 'processing' | 'completed' | 'rejected' | string;
    rejection_reason?: string | null;
    admin_notes?: string | null;
    has_result_file: boolean;
    submitted_fields?: Record<string, string> | null;
    uploaded_documents?: Array<{ key: string; original_name?: string; file_path: string; size?: number }> | null;
    created_at: string;
}

interface CacPageProps {
    packages: CacPackageItem[];
    recentOrders: CacOrderItem[];
    walletBalance: number;
    flash?: {
        success?: string;
        error?: string;
    };
    errors?: Record<string, string>;
}

export default function CacIndexPage({
    packages = [],
    recentOrders = [],
    walletBalance = 0,
    flash,
    errors = {},
}: CacPageProps) {
    const [activeTab, setActiveTab] = useState<'packages' | 'orders'>('packages');
    const [selectedPackage, setSelectedPackage] = useState<CacPackageItem | null>(null);

    // Dynamic Form States
    const [fieldsData, setFieldsData] = useState<Record<string, string>>({});
    const [docFiles, setDocFiles] = useState<Record<string, File>>({});

    // Payment Sheets State
    const [isConfirmOpen, setIsConfirmOpen] = useState(false);
    const [isPinSheetOpen, setIsPinSheetOpen] = useState(false);
    const [pinError, setPinError] = useState<string | null>(null);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [errorMessage, setErrorMessage] = useState<string | null>(null);
    const [successMessage, setSuccessMessage] = useState<string | null>(flash?.success || null);

    // Modal state for viewing order details
    const [viewingOrder, setViewingOrder] = useState<CacOrderItem | null>(null);

    const handleSelectPackage = (pkg: CacPackageItem) => {
        setSelectedPackage(pkg);
        setFieldsData({});
        setDocFiles({});
        setErrorMessage(null);
        setSuccessMessage(null);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const handleFieldChange = (label: string, value: string) => {
        setFieldsData((prev) => ({ ...prev, [label]: value }));
        setErrorMessage(null);
    };

    const handleFileChange = (docName: string, e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            if (file.size > 5 * 1024 * 1024) {
                setErrorMessage(`Document '${docName}' exceeds the 5MB file limit.`);
                return;
            }
            setDocFiles((prev) => ({ ...prev, [docName]: file }));
            setErrorMessage(null);
        }
    };

    // Client-side Validation Before Opening Confirm Sheet
    const handleInitiatePayment = (e: React.FormEvent) => {
        e.preventDefault();
        setErrorMessage(null);

        if (!selectedPackage) return;

        // Check required fields
        for (const f of selectedPackage.fields) {
            if (f.is_required) {
                const val = fieldsData[f.label];
                if (!val || val.trim() === '') {
                    setErrorMessage(`Please fill in '${f.label}' before proceeding.`);
                    return;
                }
            }
        }

        // Check required documents
        for (const doc of selectedPackage.required_documents) {
            if (doc.is_required && !docFiles[doc.name]) {
                setErrorMessage(`Please upload the required document: '${doc.name}'.`);
                return;
            }
        }

        // Check wallet balance
        if (walletBalance < selectedPackage.price) {
            setErrorMessage(`Insufficient wallet balance (₦${walletBalance.toLocaleString()}). This package costs ₦${selectedPackage.price.toLocaleString()}. Please top up your wallet.`);
            return;
        }

        setIsConfirmOpen(true);
    };

    const handleConfirmSuccess = () => {
        setIsConfirmOpen(false);
        setPinError(null);
        setIsPinSheetOpen(true);
    };

    // Submit with Transaction PIN
    const handlePinComplete = (pin: string) => {
        if (!selectedPackage) return;

        setIsSubmitting(true);
        setPinError(null);

        const formData = new FormData();
        formData.append('service_id', String(selectedPackage.id));
        formData.append('transaction_pin', pin);

        // Append dynamic questions
        Object.entries(fieldsData).forEach(([label, value]) => {
            if (value) {
                formData.append(`fields[${label}]`, value);
            }
        });

        // Append uploaded files
        Object.entries(docFiles).forEach(([docName, file]) => {
            if (file) {
                formData.append(`documents[${docName}]`, file);
            }
        });

        router.post('/cac', formData, {
            forceFormData: true,
            preserveScroll: true,
            onFinish: () => setIsSubmitting(false),
            onSuccess: () => {
                setIsPinSheetOpen(false);
                setSelectedPackage(null);
                setActiveTab('orders');
                setSuccessMessage(`Your CAC registration application has been submitted successfully!`);
            },
            onError: (errs: any) => {
                const msg = errs.message || errs.transaction_pin || Object.values(errs)[0] as string || 'Failed to submit CAC application.';
                if (msg.toLowerCase().includes('pin')) {
                    setPinError(msg);
                } else {
                    setIsPinSheetOpen(false);
                    setErrorMessage(msg);
                }
            },
        });
    };

    // Prepare confirmation breakdown items
    const paymentDetails: PaymentDetailItem[] = selectedPackage ? [
        {
            label: 'Package',
            value: <span className="font-semibold text-gray-900 dark:text-white">{selectedPackage.name}</span>,
        },
        {
            label: 'Proposed Name',
            value: <span className="font-semibold text-sky-600 dark:text-sky-400">{Object.values(fieldsData)[0] || 'N/A'}</span>,
        },
        {
            label: 'Est. Turnaround',
            value: <span className="text-gray-600 dark:text-gray-300">{selectedPackage.processing_time}</span>,
        },
        {
            label: 'Statutory & Filing Fee',
            value: <span className="font-bold text-gray-900 dark:text-white">₦{selectedPackage.price.toLocaleString()}</span>,
        },
    ] : [];

    const getStatusBadge = (status: string) => {
        switch (status) {
            case 'completed':
                return (
                    <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                        <CheckCircle2 className="w-3.5 h-3.5" />
                        Approved
                    </span>
                );
            case 'processing':
                return (
                    <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-300 border border-sky-200 dark:border-sky-800">
                        <Clock className="w-3.5 h-3.5 animate-spin" />
                        Processing at CAC
                    </span>
                );
            case 'rejected':
                return (
                    <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                        <XCircle className="w-3.5 h-3.5" />
                        Rejected
                    </span>
                );
            default:
                return (
                    <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                        <Clock className="w-3.5 h-3.5" />
                        Pending Review
                    </span>
                );
        }
    };

    return (
        <div className="min-h-screen bg-slate-50 dark:bg-[#0c0d14] text-slate-900 dark:text-slate-100 pb-20">
            <Head title="CAC Business Registration" />

            {/* Top Navigation Bar */}
            <header className="sticky top-0 z-30 bg-white/90 dark:bg-[#12131f]/90 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800/80 py-3.5 px-4 flex items-center justify-between shadow-2xs">
                <div className="flex items-center gap-3 min-w-0">
                    <Link
                        href={selectedPackage ? '#' : dashboard().url}
                        onClick={(e) => {
                            if (selectedPackage) {
                                e.preventDefault();
                                setSelectedPackage(null);
                            }
                        }}
                        className="p-1.5 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors shrink-0"
                        title={selectedPackage ? 'Back to Packages' : 'Back to Dashboard'}
                    >
                        <ChevronLeft className="w-5 h-5 stroke-[2.2]" />
                    </Link>
                    <div className="min-w-0">
                        <h1 className="font-extrabold text-base text-slate-900 dark:text-white tracking-tight leading-tight flex items-center gap-1.5">
                            <Building2 className="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" />
                            <span>CAC Business Registration</span>
                        </h1>
                        <p className="text-xs text-slate-500 dark:text-slate-400 font-normal truncate mt-0.5">
                            Corporate Affairs Commission registration & certificates
                        </p>
                    </div>
                </div>

                <div className="flex items-center gap-2">
                    <div className="text-right hidden sm:block">
                        <div className="text-[10px] text-slate-400 uppercase font-semibold">Wallet Balance</div>
                        <div className="text-xs font-extrabold text-emerald-600 dark:text-emerald-400">
                            ₦{walletBalance.toLocaleString()}
                        </div>
                    </div>
                </div>
            </header>

            <main className="max-w-4xl mx-auto px-4 py-5 space-y-6">
                {/* Success / Error Banners */}
                {successMessage && (
                    <div className="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 flex items-start gap-3">
                        <CheckCircle2 className="w-5 h-5 shrink-0 text-emerald-600 dark:text-emerald-400 mt-0.5" />
                        <div className="text-sm font-medium flex-1">{successMessage}</div>
                        <button onClick={() => setSuccessMessage(null)} className="text-xs font-bold text-emerald-600 hover:underline">Dismiss</button>
                    </div>
                )}

                {errorMessage && (
                    <div className="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 flex items-start gap-3">
                        <AlertCircle className="w-5 h-5 shrink-0 text-rose-600 dark:text-rose-400 mt-0.5" />
                        <div className="text-sm font-medium flex-1">{errorMessage}</div>
                        <button onClick={() => setErrorMessage(null)} className="text-xs font-bold text-rose-600 hover:underline">Dismiss</button>
                    </div>
                )}

                {/* Sub-header Tabs */}
                {!selectedPackage && (
                    <div className="flex items-center gap-2 p-1 bg-slate-200/70 dark:bg-slate-800/70 rounded-xl">
                        <button
                            type="button"
                            onClick={() => setActiveTab('packages')}
                            className={`flex-1 py-2 px-3 text-xs sm:text-sm font-bold rounded-lg transition-all ${
                                activeTab === 'packages'
                                    ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-xs'
                                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                            }`}
                        >
                            Available Packages ({packages.length})
                        </button>
                        <button
                            type="button"
                            onClick={() => setActiveTab('orders')}
                            className={`flex-1 py-2 px-3 text-xs sm:text-sm font-bold rounded-lg transition-all ${
                                activeTab === 'orders'
                                    ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-xs'
                                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                            }`}
                        >
                            My Applications ({recentOrders.length})
                        </button>
                    </div>
                )}

                {/* TAB 1: CAC PACKAGES LIST */}
                {activeTab === 'packages' && !selectedPackage && (
                    <div className="space-y-4">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {packages.map((pkg) => (
                                <div
                                    key={pkg.id}
                                    className="bg-white dark:bg-[#151624] border border-slate-200 dark:border-slate-800 rounded-2xl p-5 hover:border-emerald-500/50 dark:hover:border-emerald-500/50 transition-all flex flex-col justify-between shadow-xs group"
                                >
                                    <div className="space-y-3">
                                        <div className="flex items-start justify-between gap-3">
                                            <div className="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 group-hover:scale-105 transition-transform">
                                                <Briefcase className="w-5 h-5 stroke-[2]" />
                                            </div>
                                            <div className="text-right">
                                                <div className="text-xs font-semibold text-slate-400">Total Fee</div>
                                                <div className="text-lg font-black text-slate-900 dark:text-white tracking-tight">
                                                    ₦{pkg.price.toLocaleString()}
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h3 className="font-extrabold text-slate-900 dark:text-white text-base">
                                                {pkg.name}
                                            </h3>
                                            <p className="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mt-1 leading-relaxed">
                                                {pkg.description || 'Official Corporate Affairs Commission filing and certified documentation.'}
                                            </p>
                                        </div>

                                        <div className="flex items-center gap-2 pt-1 flex-wrap">
                                            <span className="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                                                <Clock className="w-3 h-3 text-slate-400" />
                                                {pkg.processing_time}
                                            </span>
                                            <span className="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                                                <FileText className="w-3 h-3 text-slate-400" />
                                                {pkg.fields.length} Questions
                                            </span>
                                            {pkg.required_documents.length > 0 && (
                                                <span className="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                                                    <UploadCloud className="w-3 h-3 text-slate-400" />
                                                    {pkg.required_documents.length} Uploads
                                                </span>
                                            )}
                                        </div>
                                    </div>

                                    <div className="pt-5 mt-4 border-t border-slate-100 dark:border-slate-800/80">
                                        <button
                                            type="button"
                                            onClick={() => handleSelectPackage(pkg)}
                                            className="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-colors shadow-xs"
                                        >
                                            <span>Apply & Register</span>
                                            <ArrowRight className="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                            ))}
                        </div>

                        {packages.length === 0 && (
                            <div className="text-center py-12 bg-white dark:bg-[#151624] rounded-2xl border border-slate-200 dark:border-slate-800 p-6">
                                <AlertCircle className="w-10 h-10 text-slate-400 mx-auto mb-2" />
                                <h3 className="font-bold text-slate-700 dark:text-slate-200">No CAC Packages Configured</h3>
                                <p className="text-xs text-slate-400 max-w-sm mx-auto mt-1">
                                    The merchant has not enabled any CAC registration packages yet. Please check back shortly.
                                </p>
                            </div>
                        )}
                    </div>
                )}

                {/* SELECTED PACKAGE: APPLICATION FORM */}
                {selectedPackage && (
                    <div className="space-y-6">
                        {/* Package summary card */}
                        <div className="bg-white dark:bg-[#151624] border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs">
                            <div className="flex items-center justify-between gap-4 flex-wrap pb-4 border-b border-slate-100 dark:border-slate-800">
                                <div>
                                    <span className="text-[10px] uppercase font-bold text-emerald-600 dark:text-emerald-400 tracking-wider">
                                        Selected Package
                                    </span>
                                    <h2 className="text-lg font-black text-slate-900 dark:text-white">
                                        {selectedPackage.name}
                                    </h2>
                                </div>
                                <div className="text-right">
                                    <span className="text-xs font-semibold text-slate-400">Total Price</span>
                                    <div className="text-xl font-black text-emerald-600 dark:text-emerald-400">
                                        ₦{selectedPackage.price.toLocaleString()}
                                    </div>
                                </div>
                            </div>

                            <div className="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400 mt-3 flex-wrap">
                                <span className="inline-flex items-center gap-1.5 font-medium">
                                    <Clock className="w-3.5 h-3.5 text-slate-400" />
                                    Turnaround: {selectedPackage.processing_time}
                                </span>
                                <span className="inline-flex items-center gap-1.5 font-medium">
                                    <ShieldCheck className="w-3.5 h-3.5 text-emerald-500" />
                                    Statutory CAC Filing Included
                                </span>
                            </div>

                            {selectedPackage.instructions && (
                                <div className="mt-4 p-3.5 rounded-xl bg-amber-50/80 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 text-amber-900 dark:text-amber-200 text-xs leading-relaxed flex items-start gap-2.5">
                                    <AlertTriangle className="w-4 h-4 text-amber-600 shrink-0 mt-0.5" />
                                    <div>
                                        <div className="font-bold mb-0.5">Application Guidelines:</div>
                                        {selectedPackage.instructions}
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Form Submission Container */}
                        <form onSubmit={handleInitiatePayment} className="space-y-6">
                            {/* SECTION 1: QUESTION FIELDS */}
                            {selectedPackage.fields.length > 0 && (
                                <div className="bg-white dark:bg-[#151624] border border-slate-200 dark:border-slate-800 rounded-2xl p-5 space-y-4 shadow-xs">
                                    <div className="flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                                        <FileText className="w-4 h-4 text-emerald-600" />
                                        <h3 className="font-bold text-sm text-slate-900 dark:text-white uppercase tracking-wider text-[11px]">
                                            1. Information & Proposed Details
                                        </h3>
                                    </div>

                                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        {selectedPackage.fields.map((f, idx) => {
                                            const isTextarea = f.type === 'textarea';
                                            return (
                                                <div key={idx} className={isTextarea ? 'sm:col-span-2' : ''}>
                                                    <label className="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                                        {f.label}{' '}
                                                        {f.is_required ? (
                                                            <span className="text-rose-500">*</span>
                                                        ) : (
                                                            <span className="text-slate-400 font-normal text-[11px]">(Optional)</span>
                                                        )}
                                                    </label>

                                                    {isTextarea ? (
                                                        <textarea
                                                            rows={3}
                                                            required={f.is_required}
                                                            placeholder={f.placeholder || `Enter ${f.label.toLowerCase()}`}
                                                            value={fieldsData[f.label] || ''}
                                                            onChange={(e) => handleFieldChange(f.label, e.target.value)}
                                                            className="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-slate-900 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all"
                                                        />
                                                    ) : (
                                                        <input
                                                            type={f.type === 'date' ? 'date' : f.type === 'number' ? 'number' : 'text'}
                                                            required={f.is_required}
                                                            placeholder={f.placeholder || `Enter ${f.label.toLowerCase()}`}
                                                            value={fieldsData[f.label] || ''}
                                                            onChange={(e) => handleFieldChange(f.label, e.target.value)}
                                                            className="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-slate-900 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all"
                                                        />
                                                    )}
                                                </div>
                                            );
                                        })}
                                    </div>
                                </div>
                            )}

                            {/* SECTION 2: REQUIRED DOCUMENT UPLOADS */}
                            {selectedPackage.required_documents.length > 0 && (
                                <div className="bg-white dark:bg-[#151624] border border-slate-200 dark:border-slate-800 rounded-2xl p-5 space-y-4 shadow-xs">
                                    <div className="flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                                        <UploadCloud className="w-4 h-4 text-emerald-600" />
                                        <h3 className="font-bold text-sm text-slate-900 dark:text-white uppercase tracking-wider text-[11px]">
                                            2. Required Document Evidence & Signatures
                                        </h3>
                                    </div>

                                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        {selectedPackage.required_documents.map((doc, idx) => {
                                            const selectedFile = docFiles[doc.name];
                                            return (
                                                <div
                                                    key={idx}
                                                    className="p-4 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30 flex flex-col justify-between"
                                                >
                                                    <div>
                                                        <div className="flex items-center justify-between gap-2">
                                                            <span className="text-xs font-bold text-slate-900 dark:text-white">
                                                                {doc.name}{' '}
                                                                {doc.is_required ? (
                                                                    <span className="text-rose-500">*</span>
                                                                ) : (
                                                                    <span className="text-slate-400 font-normal text-[11px]">(Optional)</span>
                                                                )}
                                                            </span>
                                                            {selectedFile && (
                                                                <span className="text-[10px] font-bold text-emerald-600 flex items-center gap-1">
                                                                    <Check className="w-3 h-3" /> Ready
                                                                </span>
                                                            )}
                                                        </div>

                                                        {doc.description && (
                                                            <p className="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                                                                {doc.description}
                                                            </p>
                                                        )}
                                                    </div>

                                                    <div className="mt-3">
                                                        <label className="cursor-pointer inline-flex items-center gap-2 py-2 px-3 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors w-full justify-center">
                                                            <UploadCloud className="w-4 h-4 text-emerald-500" />
                                                            <span className="truncate max-w-[200px]">
                                                                {selectedFile ? selectedFile.name : 'Choose File (PDF/Image)'}
                                                            </span>
                                                            <input
                                                                type="file"
                                                                accept=".pdf,image/png,image/jpeg,image/jpg"
                                                                required={doc.is_required && !selectedFile}
                                                                onChange={(e) => handleFileChange(doc.name, e)}
                                                                className="hidden"
                                                            />
                                                        </label>
                                                    </div>
                                                </div>
                                            );
                                        })}
                                    </div>
                                </div>
                            )}

                            {/* SECTION 3: SUMMARY & WALLET BALANCE CHECK */}
                            <div className="bg-white dark:bg-[#151624] border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs space-y-4">
                                <div className="flex items-center justify-between text-xs sm:text-sm">
                                    <span className="text-slate-500 dark:text-slate-400">Your Current Wallet Balance</span>
                                    <span className="font-extrabold text-slate-900 dark:text-white">₦{walletBalance.toLocaleString()}</span>
                                </div>

                                <div className="flex items-center justify-between text-xs sm:text-sm pt-2 border-t border-slate-100 dark:border-slate-800">
                                    <span className="font-bold text-slate-900 dark:text-white">Amount to Pay</span>
                                    <span className="text-base font-black text-emerald-600 dark:text-emerald-400">
                                        ₦{selectedPackage.price.toLocaleString()}
                                    </span>
                                </div>

                                {walletBalance < selectedPackage.price && (
                                    <div className="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200 text-xs flex items-center justify-between gap-3">
                                        <span>Insufficient balance to submit this application.</span>
                                        <Link href="/wallet" className="font-bold underline text-amber-900 dark:text-amber-100 shrink-0">
                                            Fund Wallet
                                        </Link>
                                    </div>
                                )}

                                <div className="flex items-center gap-3 pt-2">
                                    <button
                                        type="button"
                                        onClick={() => setSelectedPackage(null)}
                                        className="py-3 px-4 rounded-xl border border-slate-200 dark:border-slate-700 text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                                    >
                                        Cancel
                                    </button>

                                    <button
                                        type="submit"
                                        disabled={walletBalance < selectedPackage.price}
                                        className="flex-1 py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white font-extrabold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all shadow-xs"
                                    >
                                        <span>Confirm Application & Pay</span>
                                        <ArrowRight className="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                )}

                {/* TAB 2: MY ORDERS LIST */}
                {activeTab === 'orders' && !selectedPackage && (
                    <div className="space-y-3">
                        {recentOrders.map((order) => (
                            <div
                                key={order.id}
                                className="bg-white dark:bg-[#151624] border border-slate-200 dark:border-slate-800 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs"
                            >
                                <div className="space-y-1.5 min-w-0">
                                    <div className="flex items-center gap-2 flex-wrap">
                                        <span className="font-mono text-xs font-bold text-slate-500 dark:text-slate-400">
                                            {order.reference}
                                        </span>
                                        {getStatusBadge(order.status)}
                                    </div>

                                    <h4 className="font-extrabold text-slate-900 dark:text-white text-sm sm:text-base truncate">
                                        {order.service_name}
                                    </h4>

                                    {order.business_name && (
                                        <div className="text-xs text-sky-600 dark:text-sky-400 font-semibold truncate">
                                            Proposed Name: {order.business_name}
                                        </div>
                                    )}

                                    <div className="text-[11px] text-slate-400">
                                        Submitted on {order.created_at} · ₦{order.amount_paid.toLocaleString()}
                                    </div>

                                    {order.rejection_reason && (
                                        <div className="text-xs text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 p-2 rounded-lg mt-1 border border-rose-200 dark:border-rose-800">
                                            <strong>Rejection Reason:</strong> {order.rejection_reason}
                                        </div>
                                    )}
                                </div>

                                <div className="flex items-center gap-2 shrink-0">
                                    <button
                                        type="button"
                                        onClick={() => setViewingOrder(order)}
                                        className="py-2 px-3 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center gap-1.5"
                                    >
                                        <Eye className="w-3.5 h-3.5" />
                                        <span>View Details</span>
                                    </button>

                                    {order.has_result_file && (
                                        <a
                                            href={`/cac/${order.reference}/download-certificate`}
                                            className="py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors flex items-center gap-1.5 shadow-xs"
                                        >
                                            <Download className="w-3.5 h-3.5" />
                                            <span>Download Certificate</span>
                                        </a>
                                    )}
                                </div>
                            </div>
                        ))}

                        {recentOrders.length === 0 && (
                            <div className="text-center py-12 bg-white dark:bg-[#151624] rounded-2xl border border-slate-200 dark:border-slate-800 p-6">
                                <FileCheck className="w-10 h-10 text-slate-400 mx-auto mb-2" />
                                <h3 className="font-bold text-slate-700 dark:text-slate-200">No CAC Applications Found</h3>
                                <p className="text-xs text-slate-400 max-w-sm mx-auto mt-1">
                                    You haven't submitted any CAC registration orders yet. Select a package to register.
                                </p>
                            </div>
                        )}
                    </div>
                )}
            </main>

            {/* ORDER DETAILS DIALOG */}
            <Dialog open={!!viewingOrder} onOpenChange={(open) => !open && setViewingOrder(null)}>
                <DialogContent className="max-w-lg max-h-[85vh] overflow-y-auto">
                    <DialogHeader>
                        <DialogTitle className="text-base font-extrabold flex items-center gap-2">
                            <span>Order #{viewingOrder?.reference}</span>
                        </DialogTitle>
                    </DialogHeader>

                    {viewingOrder && (
                        <div className="space-y-4 pt-2 text-xs">
                            <div className="p-3 bg-slate-50 dark:bg-slate-900 rounded-xl space-y-1">
                                <div className="flex justify-between">
                                    <span className="text-slate-400">Package:</span>
                                    <span className="font-bold text-slate-900 dark:text-white">{viewingOrder.service_name}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-slate-400">Status:</span>
                                    <div>{getStatusBadge(viewingOrder.status)}</div>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-slate-400">Fee Paid:</span>
                                    <span className="font-bold text-emerald-600">₦{viewingOrder.amount_paid.toLocaleString()}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-slate-400">Submitted:</span>
                                    <span>{viewingOrder.created_at}</span>
                                </div>
                            </div>

                            {/* Submitted Answers */}
                            {viewingOrder.submitted_fields && Object.keys(viewingOrder.submitted_fields).length > 0 && (
                                <div className="space-y-2">
                                    <h4 className="font-bold text-slate-900 dark:text-white text-[11px] uppercase tracking-wider">
                                        Submitted Details:
                                    </h4>
                                    <div className="p-3 bg-slate-50 dark:bg-slate-900 rounded-xl space-y-1.5">
                                        {Object.entries(viewingOrder.submitted_fields).map(([k, v]) => (
                                            <div key={k} className="flex flex-col border-b border-slate-200/50 dark:border-slate-800 pb-1 last:border-0 last:pb-0">
                                                <span className="text-[10px] text-slate-400 font-semibold">{k}</span>
                                                <span className="font-medium text-slate-900 dark:text-white break-words">{v}</span>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {/* Uploaded Documents */}
                            {viewingOrder.uploaded_documents && viewingOrder.uploaded_documents.length > 0 && (
                                <div className="space-y-2">
                                    <h4 className="font-bold text-slate-900 dark:text-white text-[11px] uppercase tracking-wider">
                                        Uploaded Files:
                                    </h4>
                                    <div className="space-y-1.5">
                                        {viewingOrder.uploaded_documents.map((doc, idx) => (
                                            <div key={idx} className="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 flex items-center justify-between">
                                                <span className="font-semibold truncate max-w-[200px]">{doc.key}</span>
                                                <a
                                                    href={`/storage/${doc.file_path}`}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="text-sky-600 font-bold hover:underline"
                                                >
                                                    View File &rarr;
                                                </a>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {/* Certificate download */}
                            {viewingOrder.has_result_file && (
                                <a
                                    href={`/cac/${viewingOrder.reference}/download-certificate`}
                                    className="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-center block transition-colors"
                                >
                                    Download Issued CAC Certificate / Slip
                                </a>
                            )}
                        </div>
                    )}
                </DialogContent>
            </Dialog>

            {/* Confirm Payment Sheet */}
            <ConfirmPaymentSheet
                isOpen={isConfirmOpen}
                onClose={() => setIsConfirmOpen(false)}
                onConfirm={handleConfirmSuccess}
                title="Authorize CAC Registration"
                subtitle="Review your application particulars and filing fee"
                totalAmount={selectedPackage?.price || 0}
                details={paymentDetails}
                walletBalance={walletBalance}
            />

            {/* Transaction PIN Sheet */}
            <TransactionPinSheet
                isOpen={isPinSheetOpen}
                onClose={() => {
                    if (!isSubmitting) {
                        setIsPinSheetOpen(false);
                        setPinError(null);
                    }
                }}
                onComplete={handlePinComplete}
                title="Confirm with Transaction PIN"
                description={`Enter your 4-digit PIN to pay ₦${(selectedPackage?.price || 0).toLocaleString()} for ${selectedPackage?.name || 'CAC Registration'}.`}
                isLoading={isSubmitting}
                error={pinError}
            />
        </div>
    );
}
