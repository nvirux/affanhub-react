import React from 'react';
import { Link } from '@inertiajs/react';
import { dashboard } from '@/routes';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet';
import { Zap, AlertCircle, RefreshCw } from 'lucide-react';

export interface PaymentDetailItem {
    label: string;
    value: React.ReactNode;
    subtitle?: React.ReactNode;
}

interface ConfirmPaymentSheetProps {
    isOpen: boolean;
    onOpenChange: (open: boolean) => void;
    title?: string;
    amount: number;
    subAmountText?: React.ReactNode;
    details: PaymentDetailItem[];
    walletBalance: number;
    isSubmitting?: boolean;
    confirmButtonText?: string;
    onConfirm: () => void;
    fundWalletUrl?: string;
}

export function ConfirmPaymentSheet({
    isOpen,
    onOpenChange,
    title = 'Confirm to Pay',
    amount,
    subAmountText,
    details,
    walletBalance,
    isSubmitting = false,
    confirmButtonText = 'Confirm & Pay',
    onConfirm,
    fundWalletUrl,
}: ConfirmPaymentSheetProps) {
    const isInsufficient = walletBalance < amount;

    return (
        <Sheet open={isOpen} onOpenChange={onOpenChange}>
            <SheetContent
                side="bottom"
                className="rounded-t-2xl sm:rounded-t-3xl max-w-sm sm:max-w-md mx-auto p-4 sm:p-5 bg-white dark:bg-[#181826] border-t border-gray-100 dark:border-gray-800 shadow-2xl focus:outline-none"
            >
                {/* Compact Handle */}
                <div className="w-10 h-1 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mb-2" />

                <div className="space-y-3">
                    {/* Top Header: Title & Prominent Amount */}
                    <SheetHeader className="p-0 text-center mb-1">
                        <SheetTitle className="text-xs sm:text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            {title}
                        </SheetTitle>
                        <div className="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight mt-0.5">
                            ₦{Number(amount).toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                        </div>
                        {subAmountText && (
                            <div className="mt-0.5">
                                {subAmountText}
                            </div>
                        )}
                    </SheetHeader>

                    {/* Details Breakdown */}
                    <div className="bg-gray-50 dark:bg-gray-900/80 border border-gray-100 dark:border-gray-800 rounded-xl p-3 space-y-2 text-xs">
                        {details.map((item, idx) => (
                            <div
                                key={item.label}
                                className={`flex items-start justify-between ${
                                    idx < details.length - 1
                                        ? 'pb-1.5 border-b border-gray-200/50 dark:border-gray-800/80'
                                        : 'pt-0.5'
                                }`}
                            >
                                <span className="text-gray-500 dark:text-gray-400 font-medium text-[11px] pt-0.5">
                                    {item.label}
                                </span>
                                <div className="text-right">
                                    {item.value}
                                    {item.subtitle && (
                                        <span className="text-[11px] font-semibold text-slate-400 dark:text-slate-500 block mt-0.5">
                                            {item.subtitle}
                                        </span>
                                    )}
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* Wallet Balance Card */}
                    <div className="bg-gray-50 dark:bg-gray-900/80 border border-gray-100 dark:border-gray-800 rounded-xl px-3.5 py-2.5 flex items-center justify-between">
                        <div>
                            <span className="text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold block">
                                Wallet Balance
                            </span>
                            <span className={`text-sm font-black ${isInsufficient ? 'text-rose-500' : 'text-gray-900 dark:text-white'}`}>
                                ₦{Number(walletBalance).toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                            </span>
                        </div>

                        {isInsufficient && (
                            <Link
                                href={fundWalletUrl || dashboard().url}
                                className="text-xs font-bold text-primary hover:underline flex items-center gap-1 bg-primary/10 hover:bg-primary/20 px-2.5 py-1 rounded-lg transition-colors cursor-pointer"
                            >
                                <span>+ Add Money</span>
                            </Link>
                        )}
                    </div>

                    {/* Action Button */}
                    <div className="pt-1">
                        <button
                            type="button"
                            onClick={onConfirm}
                            disabled={isSubmitting || isInsufficient}
                            className="w-full py-3 rounded-xl bg-primary text-white text-xs sm:text-sm font-extrabold flex items-center justify-center gap-2 shadow-md shadow-primary/20 hover:opacity-95 active:scale-[0.99] transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                        >
                            {isSubmitting ? (
                                <>
                                    <RefreshCw className="w-3.5 h-3.5 animate-spin" />
                                    <span>Processing...</span>
                                </>
                            ) : isInsufficient ? (
                                <>
                                    <AlertCircle className="w-3.5 h-3.5" />
                                    <span>Insufficient Wallet Balance</span>
                                </>
                            ) : (
                                <>
                                    <Zap className="w-3.5 h-3.5 fill-white" />
                                    <span>{confirmButtonText}</span>
                                </>
                            )}
                        </button>
                    </div>
                </div>
            </SheetContent>
        </Sheet>
    );
}
