import { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import { dashboard } from '@/routes';
import { cn } from '@/lib/utils';
import {
    CheckCircle2, Clock, AlertCircle, Copy, Check, ArrowLeft,
    Printer, RefreshCw, MessageSquare, ShieldCheck, Share2, ChevronLeft, Headphones
} from 'lucide-react';

interface TransactionShowProps {
    transaction: {
        id: number;
        reference: string;
        service_type: string;
        recipient: string;
        amount: number;
        discount?: number;
        amount_paid?: number;
        status: 'successful' | 'pending' | 'failed';
        created_at: string;
        api_response?: any;
    };
    wallet_balance: number;
    store_support: {
        name: string;
        whatsapp?: string | null;
    };
}

export default function TransactionShow({
    transaction,
    wallet_balance = 0,
    store_support,
}: TransactionShowProps) {
    const [copied, setCopied] = useState(false);

    const isSuccess = transaction.status === 'successful';
    const isPending = transaction.status === 'pending';
    const isFailed = transaction.status === 'failed';

    const handleCopy = () => {
        navigator.clipboard.writeText(transaction.reference);
        setCopied(true);
        setTimeout(() => setCopied(false), 2000);
    };

    const handlePrint = () => {
        window.print();
    };

    const supportUrl = store_support.whatsapp
        ? `https://wa.me/${store_support.whatsapp.replace(/\D/g, '')}?text=${encodeURIComponent(
              `Hello ${store_support.name}, I need assistance with transaction ${transaction.reference} (${transaction.service_type.toUpperCase()} to ${transaction.recipient})`
          )}`
        : '/contact';

    const serviceRoute = transaction.service_type === 'data' ? '/vtu/data' : '/vtu/airtime';

    return (
        <>
            <Head title={`Receipt: ${transaction.reference}`} />

            {/* MOBILE ONLY TOP BAR (Hidden on Desktop) */}
            <div className="bg-white dark:bg-[#181826] border-b border-slate-100 dark:border-slate-800/80 px-4 py-3 sticky top-0 z-20 flex md:hidden items-center justify-between print:hidden">
                <div className="flex items-center gap-3 min-w-0">
                    <Link
                        href="/transactions"
                        className="p-1 -ml-1 text-slate-500 hover:text-slate-800 dark:hover:text-white transition-colors cursor-pointer"
                        title="Back to Transactions"
                    >
                        <ChevronLeft className="w-6 h-6 stroke-[2.2]" />
                    </Link>
                    <div className="min-w-0">
                        <h1 className="font-extrabold text-base text-gray-900 dark:text-white tracking-tight leading-tight">
                            Transaction Receipt
                        </h1>
                        <p className="text-xs text-slate-400 dark:text-slate-400 font-normal truncate mt-0.5">
                            Order {transaction.reference}
                        </p>
                    </div>
                </div>

                <div className="flex items-center gap-2 shrink-0 ml-2">
                    <button
                        type="button"
                        onClick={handlePrint}
                        className="p-1.5 text-slate-600 dark:text-slate-300 hover:text-primary transition-colors cursor-pointer"
                        title="Print Receipt"
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
            <div className="max-w-md mx-auto px-4 py-6 md:py-8 w-full flex flex-col items-center justify-center">

                {/* Desktop Compact Back & Print Bar (Hidden on Mobile & Print) */}
                <div className="w-full mb-3 hidden md:flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 print:hidden">
                    <Link
                        href="/transactions"
                        className="inline-flex items-center gap-1 font-bold hover:text-primary transition-colors"
                    >
                        <ChevronLeft className="w-4 h-4" />
                        <span>All Transactions</span>
                    </Link>
                    <button
                        type="button"
                        onClick={handlePrint}
                        className="inline-flex items-center gap-1.5 font-bold hover:text-primary transition-colors cursor-pointer"
                    >
                        <Printer className="w-3.5 h-3.5" />
                        <span>Print</span>
                    </button>
                </div>

                {/* Main Receipt Card */}
                <div className="w-full bg-white dark:bg-[#181826] rounded-3xl border border-slate-100 dark:border-slate-800 shadow-xl overflow-hidden print:border-none print:shadow-none">
                    
                    {/* Status Top Banner */}
                    <div
                        className={cn(
                            'p-6 text-center border-b',
                            isSuccess
                                ? 'bg-emerald-50/60 dark:bg-emerald-500/10 border-emerald-100 dark:border-emerald-500/20'
                                : isPending
                                  ? 'bg-amber-50/60 dark:bg-amber-500/10 border-amber-100 dark:border-amber-500/20'
                                  : 'bg-rose-50/60 dark:bg-rose-500/10 border-rose-100 dark:border-rose-500/20'
                        )}
                    >
                        <div
                            className={cn(
                                'w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-md',
                                isSuccess
                                    ? 'bg-emerald-500 text-white shadow-emerald-500/25'
                                    : isPending
                                      ? 'bg-amber-500 text-white shadow-amber-500/25'
                                      : 'bg-rose-500 text-white shadow-rose-500/25'
                            )}
                        >
                            {isSuccess ? (
                                <CheckCircle2 className="w-9 h-9 stroke-[2.2]" />
                            ) : isPending ? (
                                <Clock className="w-9 h-9 stroke-[2.2] animate-spin" />
                            ) : (
                                <AlertCircle className="w-9 h-9 stroke-[2.2]" />
                            )}
                        </div>

                        <span
                            className={cn(
                                'inline-block px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wide mb-2',
                                isSuccess
                                    ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300'
                                    : isPending
                                      ? 'bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300'
                                      : 'bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-300'
                            )}
                        >
                            {isSuccess ? 'Transaction Successful' : isPending ? 'Processing' : 'Failed'}
                        </span>

                        {/* Face Amount as Hero Display */}
                        <h2 className="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                            ₦{Number(transaction.amount).toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                        </h2>
                        <p className="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            {isSuccess
                                ? 'Your recharge has been dispatched and confirmed.'
                                : isPending
                                  ? 'Your order is currently processing with the network.'
                                  : 'Unable to deliver order. Any debited amount has been auto-refunded.'}
                        </p>
                    </div>

                    {/* Receipt Details Table */}
                    <div className="p-6 space-y-4 text-xs">
                        <div className="flex justify-between py-2 border-b border-slate-100 dark:border-slate-800">
                            <span className="text-slate-400 dark:text-slate-500 font-medium">Service</span>
                            <span className="font-extrabold text-slate-900 dark:text-white uppercase tracking-wider">
                                {transaction.service_type}
                            </span>
                        </div>

                        <div className="flex justify-between py-2 border-b border-slate-100 dark:border-slate-800">
                            <span className="text-slate-400 dark:text-slate-500 font-medium">Recipient Number</span>
                            <span className="font-bold text-slate-900 dark:text-white font-mono text-sm">
                                {transaction.recipient}
                            </span>
                        </div>

                        <div className="flex justify-between py-2 border-b border-slate-100 dark:border-slate-800">
                            <span className="text-slate-400 dark:text-slate-500 font-medium">Recharge Value</span>
                            <span className="font-bold text-slate-900 dark:text-white">
                                ₦{Number(transaction.amount).toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                            </span>
                        </div>

                        {Number(transaction.discount) > 0 && (
                            <div className="flex justify-between py-2 border-b border-slate-100 dark:border-slate-800 text-emerald-600 dark:text-emerald-400">
                                <span className="font-medium">Discount Savings</span>
                                <span className="font-bold">
                                    -₦{Number(transaction.discount).toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                                </span>
                            </div>
                        )}

                        <div className="flex justify-between py-2 border-b border-slate-100 dark:border-slate-800">
                            <span className="text-slate-400 dark:text-slate-500 font-medium">Total Paid</span>
                            <span className="font-extrabold text-slate-900 dark:text-white">
                                ₦{Number(transaction.amount_paid || transaction.amount).toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                            </span>
                        </div>

                        <div className="flex justify-between py-2 border-b border-slate-100 dark:border-slate-800">
                            <span className="text-slate-400 dark:text-slate-500 font-medium">Payment Method</span>
                            <span className="font-bold text-slate-900 dark:text-white">
                                Main Wallet
                            </span>
                        </div>

                        <div className="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-800">
                            <span className="text-slate-400 dark:text-slate-500 font-medium">Reference</span>
                            <button
                                type="button"
                                onClick={handleCopy}
                                className="inline-flex items-center gap-1.5 font-mono text-primary font-bold hover:underline cursor-pointer"
                                title="Copy Reference"
                            >
                                <span>{transaction.reference}</span>
                                {copied ? <Check className="w-3.5 h-3.5 text-emerald-500" /> : <Copy className="w-3.5 h-3.5" />}
                            </button>
                        </div>

                        <div className="flex justify-between py-2 border-b border-slate-100 dark:border-slate-800">
                            <span className="text-slate-400 dark:text-slate-500 font-medium">Date & Time</span>
                            <span className="font-bold text-slate-900 dark:text-white">
                                {transaction.created_at}
                            </span>
                        </div>

                        <div className="flex justify-between py-2">
                            <span className="text-slate-400 dark:text-slate-500 font-medium">Remaining Balance</span>
                            <span className="font-extrabold text-emerald-600 dark:text-emerald-400 font-mono">
                                ₦{Number(wallet_balance).toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                            </span>
                        </div>
                    </div>

                    {/* Action Buttons (Hidden on Print) */}
                    <div className="p-6 pt-0 space-y-2.5 print:hidden">
                        <div className="flex gap-2">
                            <button
                                type="button"
                                onClick={handlePrint}
                                className="flex-1 py-3 px-4 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 font-bold text-xs hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors inline-flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <Printer className="w-4 h-4" />
                                <span>Print Receipt</span>
                            </button>

                            <Link
                                href={serviceRoute}
                                className="flex-1 py-3 px-4 rounded-xl bg-primary text-primary-foreground font-bold text-xs hover:bg-primary/90 shadow-md transition-all inline-flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <RefreshCw className="w-4 h-4" />
                                <span>Buy Again</span>
                            </Link>
                        </div>

                        <a
                            href={supportUrl}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="w-full py-2.5 px-4 rounded-xl bg-slate-100 dark:bg-zinc-800/80 text-slate-700 dark:text-slate-300 font-semibold text-xs hover:bg-slate-200 dark:hover:bg-zinc-800 transition-colors inline-flex items-center justify-center gap-2"
                        >
                            <MessageSquare className="w-4 h-4 text-emerald-500" />
                            <span>Need Help with this Order? Chat Support</span>
                        </a>
                    </div>
                </div>
            </div>
        </>
    );
}
