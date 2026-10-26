import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { dashboard } from '@/routes';
import {
    ChevronLeft, Headphones, ShieldCheck, CheckCircle2,
    IdCard, Clock, AlertCircle, UploadCloud, FileCheck,
    ArrowRight, XCircle, Eye, Download, RefreshCw,
    ArrowLeftRight, FileText
} from 'lucide-react';
import { ConfirmPaymentSheet, PaymentDetailItem } from '@/components/confirm-payment-sheet';
import { TransactionPinSheet } from '@/components/transaction-pin-sheet';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

export interface ServiceFieldConfig {
    label: string;
    placeholder?: string;
    type?: string;
    is_required: boolean;
}

export interface ServiceDocumentConfig {
    name: string;
    is_required: boolean;
    description?: string | null;
}

export interface ModificationServiceItem {
    id: number;
    name: string;
    slug: string;
    service_key: string;
    category: 'nin' | 'bvn';
    description: string;
    price: number;
    processing_time: string;
    fields: ServiceFieldConfig[];
    required_documents: ServiceDocumentConfig[];
    instructions: string;
}

export interface ModificationOrderItem {
    id: number;
    reference: string;
    service_name: string;
    identifier: string;
    amount_paid: number;
    status: 'pending' | 'processing' | 'completed' | 'rejected';
    old_data?: Record<string, string> | null;
    new_data?: Record<string, string> | null;
    submitted_fields?: Record<string, string> | null;
    rejection_reason?: string | null;
    admin_notes?: string | null;
    has_result_file?: boolean;
    download_url?: string | null;
    created_at: string;
    processed_at?: string | null;
}

interface ModificationPageProps {
    category: 'nin' | 'bvn';
    services: ModificationServiceItem[];
    recentRequests: ModificationOrderItem[];
    walletBalance: number;
    user: {
        id: number;
        name: string;
        phone?: string;
        email?: string;
        has_pin: boolean;
    };
    flash?: {
        success?: string;
        error?: string;
    };
    errors?: Record<string, string>;
}

export default function ModificationPage({
    category = 'nin',
    services = [],
    recentRequests = [],
    walletBalance = 0,
    user,
    flash,
    errors,
}: ModificationPageProps) {
    const isNin = category === 'nin';
    const otherRoute = isNin ? '/identity/bvn-modification' : '/identity/nin-modification';
    const otherLabel = isNin ? 'Switch to BVN Modification' : 'Switch to NIN Modification';

    const [activeTab, setActiveTab] = useState<'services' | 'orders'>('services');
    const [selectedService, setSelectedService] = useState<ModificationServiceItem | null>(null);

    // Form State
    const [identifier, setIdentifier] = useState('');
    const [fieldsData, setFieldsData] = useState<Record<string, string>>({});
    const [docFiles, setDocFiles] = useState<Record<string, File>>({});

    // Sheets & PIN State
    const [isConfirmOpen, setIsConfirmOpen] = useState(false);
    const [isPinSheetOpen, setIsPinSheetOpen] = useState(false);
    const [pinError, setPinError] = useState<string | null>(null);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [errorMessage, setErrorMessage] = useState<string | null>(null);
    const [successMessage, setSuccessMessage] = useState<string | null>(flash?.success || null);

    // Order detail modal state
    const [viewingOrder, setViewingOrder] = useState<ModificationOrderItem | null>(null);

    // Reset form when service changes
    const handleSelectService = (service: ModificationServiceItem) => {
        setSelectedService(service);
        setIdentifier('');
        setFieldsData({});
        setDocFiles({});
        setErrorMessage(null);
        setSuccessMessage(null);
    };

    const handleFieldChange = (label: string, value: string) => {
        setFieldsData((prev) => ({ ...prev, [label]: value }));
        setErrorMessage(null);
    };

    const handleFileChange = (docName: string, e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            if (file.size > 5 * 1024 * 1024) {
                setErrorMessage(`File for '${docName}' exceeds the 5MB limit.`);
                return;
            }
            setDocFiles((prev) => ({ ...prev, [docName]: file }));
            setErrorMessage(null);
        }
    };

    const handleRemoveFile = (docName: string) => {
        setDocFiles((prev) => {
            const updated = { ...prev };
            delete updated[docName];
            return updated;
        });
    };

    // Pre-validation before opening payment confirmation sheet
    const handlePreSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        setErrorMessage(null);

        if (!selectedService) return;

        const cleanId = identifier.replace(/\s+/g, '');
        if (!/^[0-9]{11}$/.test(cleanId)) {
            setErrorMessage(`Please enter a valid 11-digit ${isNin ? 'NIN' : 'BVN'} number.`);
            return;
        }

        // Validate required information fields
        const configuredFields = selectedService.fields || [];
        for (const field of configuredFields) {
            const val = (fieldsData[field.label] || '').trim();
            if (field.is_required && !val) {
                setErrorMessage(`Please provide: ${field.label}`);
                return;
            }
        }

        // Validate required documents
        const configuredDocs = selectedService.required_documents || [];
        for (const doc of configuredDocs) {
            if (doc.is_required && !docFiles[doc.name]) {
                setErrorMessage(`Please upload required document: ${doc.name}`);
                return;
            }
        }

        if (walletBalance < selectedService.price) {
            setErrorMessage(`Insufficient wallet balance (₦${walletBalance.toFixed(2)}). You need ₦${selectedService.price.toFixed(2)}.`);
            return;
        }

        setIsConfirmOpen(true);
    };

    // When customer confirms on ConfirmPaymentSheet
    const handleConfirmStep = () => {
        setIsConfirmOpen(false);

        if (user.has_pin) {
            setPinError(null);
            setIsPinSheetOpen(true);
        } else {
            executeSubmission(null);
        }
    };

    // When customer submits PIN on TransactionPinSheet
    const handlePinSubmit = (pin: string) => {
        executeSubmission(pin);
    };

    // Execute actual submission via Inertia
    const executeSubmission = (pin: string | null) => {
        if (!selectedService) return;

        setIsSubmitting(true);
        setErrorMessage(null);
        setPinError(null);

        const formData = new FormData();
        formData.append('service_id', String(selectedService.id));
        formData.append('identifier', identifier.trim());
        if (pin) {
            formData.append('transaction_pin', pin);
        }

        // Append configured fields
        Object.entries(fieldsData).forEach(([label, value]) => {
            if (value) {
                formData.append(`fields[${label}]`, value);
                formData.append(`new_data[${label}]`, value);
            }
        });

        // Append document files
        Object.entries(docFiles).forEach(([docName, file]) => {
            if (file) {
                formData.append(`documents[${docName}]`, file);
            }
        });

        router.post('/identity/modification', formData, {
            forceFormData: true,
            preserveScroll: true,
            onFinish: () => setIsSubmitting(false),
            onSuccess: () => {
                setIsPinSheetOpen(false);
                setSelectedService(null);
                setActiveTab('orders');
                setSuccessMessage(`Your ${selectedService.name} request has been submitted successfully!`);
            },
            onError: (errs: any) => {
                const msg = errs.message || errs.transaction_pin || Object.values(errs)[0] as string || 'Failed to submit modification request.';
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
    const paymentDetails: PaymentDetailItem[] = selectedService ? [
        {
            label: 'Service',
            value: <span className="font-semibold text-gray-900 dark:text-white">{selectedService.name}</span>,
        },
        {
            label: isNin ? 'NIN Number' : 'BVN Number',
            value: <span className="font-mono font-semibold tracking-wider">{identifier}</span>,
        },
        {
            label: 'Est. Processing Time',
            value: <span className="font-medium text-emerald-600 dark:text-emerald-400">{selectedService.processing_time}</span>,
        },
        {
            label: 'Attached Documents',
            value: <span className="font-semibold">{Object.keys(docFiles).length} Document(s)</span>,
        },
        {
            label: 'Total Fee',
            value: <span className="font-bold text-gray-900 dark:text-white">₦{Number(selectedService.price).toFixed(2)}</span>,
        },
    ] : [];

    const pageTitle = isNin
        ? 'NIN Modification - Update Details & Correct Records'
        : 'BVN Modification - Update Details & Correct Records';

    return (
        <>
            <Head title={pageTitle} />

            {/* MOBILE STICKY HEADER */}
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
                            {isNin ? 'NIN Modification' : 'BVN Modification'}
                        </h1>
                        <p className="text-xs text-slate-400 font-normal truncate mt-0.5">
                            {isNin ? 'Update NIMC Identity Records' : 'Update BVN Details'}
                        </p>
                    </div>
                </div>

                <div className="flex items-center gap-2 shrink-0 ml-2">
                    <Link
                        href={otherRoute}
                        className="text-[11px] font-bold text-primary bg-primary/10 hover:bg-primary/20 px-2.5 py-1 rounded-lg transition-colors flex items-center gap-1"
                        title={otherLabel}
                    >
                        <ArrowLeftRight className="w-3 h-3" />
                        <span>{isNin ? 'BVN' : 'NIN'}</span>
                    </Link>
                    <Link
                        href="/contact"
                        className="p-1.5 text-slate-600 dark:text-slate-300 hover:text-primary transition-colors cursor-pointer"
                        title="Customer Support"
                    >
                        <Headphones className="w-5 h-5 stroke-[1.8]" />
                    </Link>
                </div>
            </div>

            {/* MAIN CONTAINER */}
            <div className="max-w-3xl mx-auto px-4 py-4 md:py-6 w-full space-y-4">

                {/* DESKTOP HEADER */}
                <div className="hidden md:flex items-center justify-between pb-2 border-b border-gray-100 dark:border-gray-800">
                    <div className="flex items-center gap-3">
                        <Link
                            href={dashboard().url}
                            className="p-2 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-200 transition-colors"
                        >
                            <ChevronLeft className="w-5 h-5" />
                        </Link>
                        <div>
                            <div className="flex items-center gap-2">
                                <h1 className="text-xl font-black text-gray-900 dark:text-white tracking-tight">
                                    {isNin ? 'NIN Modification' : 'BVN Modification'}
                                </h1>
                                <span className={`px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${
                                    isNin ? 'bg-emerald-500/10 text-emerald-600' : 'bg-blue-500/10 text-blue-600'
                                }`}>
                                    {isNin ? 'NIMC Portal' : 'NIBSS / BVN'}
                                </span>
                            </div>
                            <p className="text-xs text-slate-400 mt-0.5">
                                {isNin
                                    ? 'Change of Name, Date of Birth, Phone Number & Identity Rectification'
                                    : 'Update Name, Date of Birth & Telephone Number on your BVN'}
                            </p>
                        </div>
                    </div>

                    <div className="flex items-center gap-3">
                        <Link
                            href={otherRoute}
                            className="text-xs font-bold text-primary hover:text-primary/80 bg-primary/10 hover:bg-primary/20 px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 shadow-2xs"
                        >
                            <ArrowLeftRight className="w-3.5 h-3.5" />
                            <span>{otherLabel}</span>
                        </Link>

                        <div className="px-3 py-1.5 rounded-xl bg-gray-50 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 text-xs flex items-center gap-2">
                            <span className="text-slate-400 font-medium">Wallet:</span>
                            <span className="font-bold text-gray-900 dark:text-white">₦{Number(walletBalance).toFixed(2)}</span>
                            <Link href="/wallet" className="text-primary font-bold hover:underline ml-1">+ Fund</Link>
                        </div>
                    </div>
                </div>

                {/* ERROR & SUCCESS NOTIFICATIONS */}
                {(errorMessage || errors?.message) && (
                    <div className="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 rounded-2xl p-4 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center justify-between gap-3 animate-in fade-in duration-200">
                        <div className="flex items-center gap-2">
                            <AlertCircle className="w-4 h-4 shrink-0 text-rose-500" />
                            <span>{errorMessage || errors?.message}</span>
                        </div>
                        <button onClick={() => setErrorMessage(null)} className="text-xs font-bold opacity-60 hover:opacity-100 cursor-pointer">✕</button>
                    </div>
                )}

                {(successMessage || flash?.success) && (
                    <div className="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl p-4 text-emerald-700 dark:text-emerald-300 text-xs font-semibold flex items-center justify-between gap-3 animate-in fade-in duration-200">
                        <div className="flex items-center gap-2">
                            <CheckCircle2 className="w-4 h-4 shrink-0 text-emerald-500" />
                            <span>{successMessage || flash?.success}</span>
                        </div>
                        <button onClick={() => setSuccessMessage(null)} className="text-xs font-bold opacity-60 hover:opacity-100 cursor-pointer">✕</button>
                    </div>
                )}

                {/* MAIN TABS: SERVICES vs MY ORDERS */}
                <div className="bg-white dark:bg-[#181826] p-1.5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-xs flex gap-1">
                    <button
                        type="button"
                        onClick={() => {
                            setActiveTab('services');
                            setSelectedService(null);
                        }}
                        className={`flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer ${
                            activeTab === 'services'
                                ? 'bg-primary text-white shadow-sm'
                                : 'text-slate-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'
                        }`}
                    >
                        {isNin ? <IdCard className="w-4 h-4" /> : <ShieldCheck className="w-4 h-4" />}
                        <span>Available {isNin ? 'NIN' : 'BVN'} Services</span>
                        <span className="px-1.5 py-0.2 rounded-full text-[10px] bg-white/20">
                            {services.length}
                        </span>
                    </button>

                    <button
                        type="button"
                        onClick={() => {
                            setActiveTab('orders');
                            setSelectedService(null);
                        }}
                        className={`flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer ${
                            activeTab === 'orders'
                                ? 'bg-primary text-white shadow-sm'
                                : 'text-slate-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'
                        }`}
                    >
                        <Clock className="w-4 h-4" />
                        <span>My Orders</span>
                        {recentRequests.length > 0 && (
                            <span className="px-1.5 py-0.2 rounded-full text-[10px] bg-white/20">
                                {recentRequests.length}
                            </span>
                        )}
                    </button>
                </div>

                {/* VIEW 1: MY ORDERS & STATUS TRACKING */}
                {activeTab === 'orders' && (
                    <div className="space-y-3">
                        {recentRequests.length === 0 ? (
                            <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-8 text-center space-y-3">
                                <Clock className="w-10 h-10 text-slate-300 dark:text-slate-600 mx-auto" />
                                <h3 className="font-bold text-sm text-gray-900 dark:text-white">
                                    No {isNin ? 'NIN' : 'BVN'} modification orders yet
                                </h3>
                                <p className="text-xs text-slate-400 max-w-sm mx-auto">
                                    When you submit a request, you can monitor its live review, processing progress, and download completed documents here.
                                </p>
                                <button
                                    onClick={() => setActiveTab('services')}
                                    className="px-4 py-2 rounded-xl bg-primary text-white text-xs font-bold shadow-sm hover:opacity-90 transition-all cursor-pointer"
                                >
                                    Browse Services
                                </button>
                            </div>
                        ) : (
                            recentRequests.map((order) => {
                                const statusConfig = {
                                    pending: { label: 'Pending Review', color: 'bg-amber-500/10 text-amber-600 border-amber-200 dark:border-amber-900/50', icon: Clock },
                                    processing: { label: 'In Progress', color: 'bg-blue-500/10 text-blue-600 border-blue-200 dark:border-blue-900/50', icon: RefreshCw },
                                    completed: { label: 'Completed', color: 'bg-emerald-500/10 text-emerald-600 border-emerald-200 dark:border-emerald-900/50', icon: CheckCircle2 },
                                    rejected: { label: 'Rejected', color: 'bg-rose-500/10 text-rose-600 border-rose-200 dark:border-rose-900/50', icon: XCircle },
                                }[order.status] || { label: order.status, color: 'bg-gray-100 text-gray-600', icon: Clock };

                                const StatusIcon = statusConfig.icon;

                                return (
                                    <div
                                        key={order.reference}
                                        className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs space-y-3 hover:border-primary/30 transition-all"
                                    >
                                        <div className="flex items-start justify-between gap-2">
                                            <div className="space-y-0.5">
                                                <div className="flex items-center gap-2">
                                                    <h3 className="font-extrabold text-sm text-gray-900 dark:text-white">
                                                        {order.service_name}
                                                    </h3>
                                                    <span className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border ${statusConfig.color}`}>
                                                        <StatusIcon className="w-3 h-3" />
                                                        <span>{statusConfig.label}</span>
                                                    </span>
                                                </div>
                                                <p className="text-xs text-slate-400 font-mono">
                                                    Ref: <span className="font-semibold text-slate-600 dark:text-slate-300">{order.reference}</span> · ID: <span className="font-bold">{order.identifier}</span>
                                                </p>
                                            </div>

                                            <div className="text-right">
                                                <div className="text-xs font-black text-gray-900 dark:text-white">
                                                    ₦{order.amount_paid.toFixed(2)}
                                                </div>
                                                <span className="text-[10px] text-slate-400">{order.created_at}</span>
                                            </div>
                                        </div>

                                        {/* Status messages */}
                                        {order.status === 'rejected' && order.rejection_reason && (
                                            <div className="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/40 text-xs text-rose-700 dark:text-rose-300 space-y-1">
                                                <div className="font-bold flex items-center gap-1.5">
                                                    <XCircle className="w-3.5 h-3.5 text-rose-500" />
                                                    <span>Rejection Reason:</span>
                                                </div>
                                                <p className="pl-5 text-[11px]">{order.rejection_reason}</p>
                                                <p className="pl-5 text-[10px] text-rose-500 font-medium italic">Amount has been refunded to your wallet.</p>
                                            </div>
                                        )}

                                        {order.status === 'processing' && (
                                            <div className="p-3 rounded-xl bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/40 text-xs text-blue-700 dark:text-blue-300 flex items-center gap-2">
                                                <RefreshCw className="w-3.5 h-3.5 text-blue-500 animate-spin" />
                                                <span>Your documents are currently being processed on national identity systems.</span>
                                            </div>
                                        )}

                                        {/* Actions */}
                                        <div className="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-800 text-xs">
                                            <button
                                                onClick={() => setViewingOrder(order)}
                                                className="font-bold text-slate-600 dark:text-slate-400 hover:text-primary transition-colors inline-flex items-center gap-1 cursor-pointer"
                                            >
                                                <Eye className="w-3.5 h-3.5" />
                                                <span>View Submitted Details</span>
                                            </button>

                                            {order.has_result_file && order.download_url && (
                                                <a
                                                    href={order.download_url}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    download
                                                    className="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs inline-flex items-center gap-1.5 transition-colors shadow-xs"
                                                >
                                                    <Download className="w-3.5 h-3.5" />
                                                    <span>Download Result Slip</span>
                                                </a>
                                            )}
                                        </div>
                                    </div>
                                );
                            })
                        )}
                    </div>
                )}

                {/* VIEW 2: SERVICE PICKER */}
                {activeTab === 'services' && !selectedService && (
                    <div className="space-y-3">
                        <div className="text-xs text-slate-400 px-1 flex items-center justify-between">
                            <span>Select the {isNin ? 'NIN' : 'BVN'} service you need:</span>
                            <span className="font-semibold text-slate-500">{services.length} service options</span>
                        </div>

                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            {services.map((service) => (
                                <div
                                    key={service.id}
                                    onClick={() => handleSelectService(service)}
                                    className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs hover:border-primary hover:shadow-md transition-all cursor-pointer flex flex-col justify-between space-y-4 group"
                                >
                                    <div className="space-y-2">
                                        <div className="flex items-start justify-between gap-2">
                                            <span className="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-primary/10 text-primary">
                                                {service.category.toUpperCase()}
                                            </span>
                                            <span className="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                                <Clock className="w-3 h-3" />
                                                <span>{service.processing_time}</span>
                                            </span>
                                        </div>

                                        <h3 className="font-extrabold text-sm text-gray-900 dark:text-white group-hover:text-primary transition-colors">
                                            {service.name}
                                        </h3>

                                        <p className="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                                            {service.description || 'Standard identity modification and database update.'}
                                        </p>
                                    </div>

                                    <div className="pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                                        <div>
                                            <span className="text-[10px] text-slate-400 uppercase tracking-wider block font-semibold">Service Fee</span>
                                            <span className="font-black text-base text-gray-900 dark:text-white">
                                                ₦{Number(service.price).toFixed(2)}
                                            </span>
                                        </div>

                                        <div className="w-8 h-8 rounded-xl bg-gray-50 dark:bg-gray-800 group-hover:bg-primary group-hover:text-white text-gray-600 dark:text-gray-300 flex items-center justify-center transition-colors">
                                            <ArrowRight className="w-4 h-4" />
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}

                {/* VIEW 3: SUBMISSION FORM FOR SELECTED SERVICE */}
                {activeTab === 'services' && selectedService && (
                    <div className="space-y-4">
                        {/* Service Header Card */}
                        <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center justify-between gap-3">
                            <div className="flex items-center gap-3">
                                <button
                                    type="button"
                                    onClick={() => setSelectedService(null)}
                                    className="p-1.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 cursor-pointer"
                                >
                                    <ChevronLeft className="w-4 h-4" />
                                </button>
                                <div>
                                    <div className="flex items-center gap-2">
                                        <span className="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-primary/10 text-primary">
                                            {selectedService.category.toUpperCase()}
                                        </span>
                                        <h2 className="font-black text-sm sm:text-base text-gray-900 dark:text-white">
                                            {selectedService.name}
                                        </h2>
                                    </div>
                                    <p className="text-xs text-slate-400 mt-0.5">
                                        ⏱ Est. Time: <strong className="text-gray-700 dark:text-gray-300">{selectedService.processing_time}</strong>
                                    </p>
                                </div>
                            </div>

                            <div className="text-right">
                                <span className="text-[10px] text-slate-400 uppercase tracking-wider block font-semibold">Total Price</span>
                                <span className="font-black text-base sm:text-lg text-primary">
                                    ₦{Number(selectedService.price).toFixed(2)}
                                </span>
                            </div>
                        </div>

                        {/* Submission Form */}
                        <form onSubmit={handlePreSubmit} className="space-y-4">
                            {/* Card 1: Target Identifier */}
                            <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs space-y-3">
                                <label className="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">
                                    1. Target {isNin ? 'NIN Number' : 'BVN Number'} <span className="text-rose-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    inputMode="numeric"
                                    maxLength={11}
                                    placeholder={isNin ? 'Enter 11-digit NIN' : 'Enter 11-digit BVN'}
                                    value={identifier}
                                    onChange={(e) => setIdentifier(e.target.value.replace(/[^0-9]/g, '').slice(0, 11))}
                                    className="w-full text-base sm:text-lg font-mono font-bold tracking-wider px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/60 text-gray-900 dark:text-white focus:outline-none focus:border-primary"
                                    required
                                />
                                <p className="text-[11px] text-slate-400">
                                    Please verify the 11-digit number matches the identity profile you wish to rectify.
                                </p>
                            </div>

                            {/* Card 2: Dynamic Custom Information Fields */}
                            {selectedService.fields && selectedService.fields.length > 0 && (
                                <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs space-y-3">
                                    <label className="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">
                                        2. Information Details ({selectedService.fields.length} Fields)
                                    </label>

                                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                        {selectedService.fields.map((field) => (
                                            <div key={field.label} className="space-y-1.5">
                                                <label className="text-xs font-bold text-gray-700 dark:text-gray-300 flex items-center gap-1">
                                                    <span>{field.label}</span>
                                                    {field.is_required ? (
                                                        <span className="text-rose-500 font-black">*</span>
                                                    ) : (
                                                        <span className="text-[10px] text-slate-400 font-normal">(Optional)</span>
                                                    )}
                                                </label>

                                                {field.type === 'textarea' ? (
                                                    <textarea
                                                        rows={2}
                                                        placeholder={field.placeholder || `Enter ${field.label}`}
                                                        value={fieldsData[field.label] || ''}
                                                        onChange={(e) => handleFieldChange(field.label, e.target.value)}
                                                        className="w-full text-xs px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/60 text-gray-900 dark:text-white focus:outline-none focus:border-primary"
                                                        required={field.is_required}
                                                    />
                                                ) : (
                                                    <input
                                                        type={field.type === 'date' ? 'date' : 'text'}
                                                        placeholder={field.placeholder || `Enter ${field.label}`}
                                                        value={fieldsData[field.label] || ''}
                                                        onChange={(e) => handleFieldChange(field.label, e.target.value)}
                                                        className="w-full text-xs px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/60 text-gray-900 dark:text-white focus:outline-none focus:border-primary"
                                                        required={field.is_required}
                                                    />
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {/* Card 3: Required & Optional Supporting Documents */}
                            {selectedService.required_documents && selectedService.required_documents.length > 0 && (
                                <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs space-y-4">
                                    <div className="flex items-center justify-between">
                                        <label className="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">
                                            3. Supporting Documents ({selectedService.required_documents.length})
                                        </label>
                                        <span className="text-[11px] text-slate-400">PDF, JPG, PNG (Max 5MB)</span>
                                    </div>

                                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        {selectedService.required_documents.map((doc) => {
                                            const uploadedFile = docFiles[doc.name];

                                            return (
                                                <div
                                                    key={doc.name}
                                                    className={`p-3 sm:p-4 rounded-xl border transition-all ${
                                                        uploadedFile
                                                            ? 'border-emerald-300 dark:border-emerald-800 bg-emerald-50/20 dark:bg-emerald-950/20'
                                                            : 'border-dashed border-gray-300 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40'
                                                    }`}
                                                >
                                                    <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                                        <div className="space-y-0.5">
                                                            <div className="flex items-center gap-2">
                                                                {uploadedFile ? (
                                                                    <CheckCircle2 className="w-4 h-4 text-emerald-500 shrink-0" />
                                                                ) : (
                                                                    <UploadCloud className="w-4 h-4 text-slate-400 shrink-0" />
                                                                )}
                                                                <span className="text-xs font-bold text-gray-900 dark:text-white">
                                                                    {doc.name}
                                                                </span>
                                                                {doc.is_required ? (
                                                                    <span className="px-1.5 py-0.2 rounded text-[9px] font-bold bg-rose-500/10 text-rose-500">
                                                                        Required
                                                                    </span>
                                                                ) : (
                                                                    <span className="px-1.5 py-0.2 rounded text-[9px] font-bold bg-gray-100 dark:bg-gray-800 text-gray-500">
                                                                        Optional
                                                                    </span>
                                                                )}
                                                            </div>
                                                            {uploadedFile ? (
                                                                <p className="text-[11px] text-emerald-700 dark:text-emerald-400 font-mono pl-6 truncate max-w-xs">
                                                                    {uploadedFile.name} ({(uploadedFile.size / 1024).toFixed(0)} KB)
                                                                </p>
                                                            ) : (
                                                                <p className="text-[10px] text-slate-400 pl-6">
                                                                    {doc.description || 'Upload clear photo or scanned PDF'}
                                                                </p>
                                                            )}
                                                        </div>

                                                        <div className="flex items-center gap-2 shrink-0">
                                                            {uploadedFile ? (
                                                                <button
                                                                    type="button"
                                                                    onClick={() => handleRemoveFile(doc.name)}
                                                                    className="px-2.5 py-1 text-xs text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/50 rounded-lg transition-colors cursor-pointer"
                                                                >
                                                                    Remove
                                                                </button>
                                                            ) : (
                                                                <label className="px-3 py-1.5 text-xs font-bold rounded-xl bg-primary text-white hover:opacity-90 transition-all cursor-pointer inline-flex items-center gap-1.5 shadow-2xs">
                                                                    <span>Choose File</span>
                                                                    <input
                                                                        type="file"
                                                                        accept="image/*,application/pdf"
                                                                        onChange={(e) => handleFileChange(doc.name, e)}
                                                                        className="hidden"
                                                                    />
                                                                </label>
                                                            )}
                                                        </div>
                                                    </div>
                                                </div>
                                            );
                                        })}
                                    </div>
                                </div>
                            )}

                            {/* Merchant Instructions / Guidelines */}
                            {selectedService.instructions && (
                                <div className="p-4 rounded-2xl bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 text-xs text-amber-800 dark:text-amber-300 space-y-1">
                                    <strong className="block font-bold">Important Instructions from Merchant:</strong>
                                    <p className="text-[11px] text-amber-900/80 dark:text-amber-200/80 leading-relaxed">
                                        {selectedService.instructions}
                                    </p>
                                </div>
                            )}

                            {/* Submit Button */}
                            <button
                                type="submit"
                                className="w-full py-3.5 px-4 rounded-2xl bg-primary text-white font-black text-sm shadow-md shadow-primary/25 hover:opacity-95 active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <FileCheck className="w-4 h-4" />
                                <span>Proceed & Pay ₦{Number(selectedService.price).toFixed(2)}</span>
                            </button>
                        </form>
                    </div>
                )}
            </div>

            {/* CONFIRM PAYMENT SHEET */}
            <ConfirmPaymentSheet
                isOpen={isConfirmOpen}
                onOpenChange={setIsConfirmOpen}
                title={`Confirm ${selectedService?.name || 'Payment'}`}
                amount={selectedService?.price || 0}
                details={paymentDetails}
                walletBalance={walletBalance}
                isSubmitting={isSubmitting}
                confirmButtonText="Confirm & Authorize"
                onConfirm={handleConfirmStep}
                fundWalletUrl="/wallet"
            />

            {/* TRANSACTION PIN SHEET */}
            {selectedService && (
                <TransactionPinSheet
                    isOpen={isPinSheetOpen}
                    onOpenChange={setIsPinSheetOpen}
                    title="Authorize Modification Payment"
                    description={`Enter your 4-digit Transaction PIN to submit ${selectedService.name}`}
                    summary={
                        <div className="flex items-center justify-between text-xs">
                            <span className="text-muted-foreground">{selectedService.name}</span>
                            <span className="font-bold text-foreground">₦{Number(selectedService.price).toLocaleString()} &rarr; {identifier}</span>
                        </div>
                    }
                    isSubmitting={isSubmitting}
                    error={pinError}
                    onClearError={() => setPinError(null)}
                    onSubmitPin={handlePinSubmit}
                />
            )}

            {/* VIEW ORDER DETAILS MODAL */}
            {viewingOrder && (
                <Dialog open={!!viewingOrder} onOpenChange={() => setViewingOrder(null)}>
                    <DialogContent className="max-w-md p-5 bg-white dark:bg-[#181826] rounded-2xl border border-gray-100 dark:border-gray-800">
                        <DialogHeader>
                            <DialogTitle className="text-base font-extrabold text-gray-900 dark:text-white">
                                Order #{viewingOrder.reference}
                            </DialogTitle>
                        </DialogHeader>

                        <div className="space-y-4 text-xs pt-2">
                            <div className="grid grid-cols-2 gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-900 text-slate-600 dark:text-slate-300">
                                <div>
                                    <span className="text-[10px] uppercase tracking-wider text-slate-400 block font-bold">Service</span>
                                    <strong className="text-gray-900 dark:text-white">{viewingOrder.service_name}</strong>
                                </div>
                                <div>
                                    <span className="text-[10px] uppercase tracking-wider text-slate-400 block font-bold">Target ID</span>
                                    <strong className="font-mono text-gray-900 dark:text-white">{viewingOrder.identifier}</strong>
                                </div>
                            </div>

                            {/* Submitted Information Fields */}
                            <div className="space-y-2">
                                <span className="font-bold uppercase tracking-wider text-slate-400 text-[10px] block">Submitted Details</span>
                                <div className="space-y-1.5">
                                    {Object.entries(viewingOrder.submitted_fields || viewingOrder.new_data || {}).map(([key, value]) => (
                                        <div key={key} className="p-2.5 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 flex items-center justify-between">
                                            <span className="text-[11px] font-medium text-slate-400">{key}:</span>
                                            <span className="text-xs font-bold text-gray-900 dark:text-white">{value}</span>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            {/* Merchant note if any */}
                            {viewingOrder.admin_notes && (
                                <div className="p-3 rounded-xl bg-blue-50 dark:bg-blue-950/20 text-blue-800 dark:text-blue-300">
                                    <span className="font-bold block text-[10px] uppercase tracking-wider">Merchant Update:</span>
                                    <p className="text-[11px] mt-0.5">{viewingOrder.admin_notes}</p>
                                </div>
                            )}

                            {/* Rejection reason */}
                            {viewingOrder.rejection_reason && (
                                <div className="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/20 text-rose-800 dark:text-rose-300">
                                    <span className="font-bold block text-[10px] uppercase tracking-wider">Rejection Reason:</span>
                                    <p className="text-[11px] mt-0.5">{viewingOrder.rejection_reason}</p>
                                </div>
                            )}

                            {/* Download Result button if completed */}
                            {viewingOrder.has_result_file && viewingOrder.download_url && (
                                <a
                                    href={viewingOrder.download_url}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    download
                                    className="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-xs transition-all"
                                >
                                    <Download className="w-4 h-4" />
                                    <span>Download Official Slip</span>
                                </a>
                            )}
                        </div>
                    </DialogContent>
                </Dialog>
            )}
        </>
    );
}
