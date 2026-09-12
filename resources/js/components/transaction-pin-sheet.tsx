import React, { useState, useEffect } from 'react';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet';
import { Lock, AlertCircle, Loader2 } from 'lucide-react';
import PinPad from '@/components/pin-pad';

interface TransactionPinSheetProps {
    isOpen: boolean;
    onOpenChange: (open: boolean) => void;
    title?: string;
    description?: string;
    summary?: React.ReactNode;
    isSubmitting?: boolean;
    error?: string | null;
    onClearError?: () => void;
    onSubmitPin: (pin: string) => void;
}

export function TransactionPinSheet({
    isOpen,
    onOpenChange,
    title = 'Authorize Payment',
    description = 'Enter your 4-digit Transaction PIN to complete this purchase',
    summary,
    isSubmitting = false,
    error = null,
    onClearError,
    onSubmitPin,
}: TransactionPinSheetProps) {
    const [pin, setPin] = useState('');

    useEffect(() => {
        if (error) {
            setPin('');
        }
    }, [error]);

    const handlePinComplete = (val: string) => {
        onSubmitPin(val);
    };

    const handleChange = (val: string) => {
        setPin(val);
        if (error && onClearError) {
            onClearError();
        }
    };

    return (
        <Sheet
            open={isOpen}
            onOpenChange={(open) => {
                if (!isSubmitting) {
                    if (!open) {
                        setPin('');
                        if (onClearError) onClearError();
                    }
                    onOpenChange(open);
                }
            }}
        >
            <SheetContent
                side="bottom"
                className="overflow-hidden rounded-t-2xl sm:rounded-t-3xl max-w-sm sm:max-w-md mx-auto p-4 sm:p-5 bg-white dark:bg-[#181826] border-t border-gray-100 dark:border-gray-800 shadow-2xl focus:outline-none"
            >
                {/* Full Processing Loading Overlay */}
                {isSubmitting && (
                    <div className="absolute inset-0 bg-white/95 dark:bg-[#181826]/95 backdrop-blur-sm z-50 rounded-t-2xl sm:rounded-t-3xl flex flex-col items-center justify-center p-6 space-y-4 animate-in fade-in duration-200">
                        <div className="w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center shadow-lg ring-8 ring-primary/5">
                            <Loader2 className="w-8 h-8 animate-spin stroke-[2.5]" />
                        </div>
                        <div className="text-center space-y-1.5 max-w-xs">
                            <h4 className="text-base font-bold text-gray-900 dark:text-white tracking-tight">
                                Processing Payment...
                            </h4>
                            <p className="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                                Authorizing order and dispatching to telecom network. Please do not close or refresh.
                            </p>
                        </div>
                    </div>
                )}

                {/* Drag handle */}
                <div className="w-10 h-1 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mb-2" />

                <div className="flex flex-col items-center text-center space-y-2">
                    <SheetHeader className="p-0 text-center space-y-1">
                        <div className="mx-auto w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center mb-1 ring-4 ring-primary/5">
                            <Lock className="w-5 h-5" />
                        </div>
                        <SheetTitle className="text-base sm:text-lg font-bold text-gray-900 dark:text-white">
                            {title}
                        </SheetTitle>
                        <p className="text-xs text-muted-foreground max-w-xs">
                            {description}
                        </p>
                    </SheetHeader>

                    {summary && (
                        <div className="w-full bg-gray-50 dark:bg-gray-900/80 border border-gray-100 dark:border-gray-800 rounded-xl px-3 py-2 text-xs">
                            {summary}
                        </div>
                    )}

                    {error && (
                        <div className="w-full max-w-[280px] sm:max-w-[300px] my-1 rounded-xl border border-destructive/20 bg-destructive/10 p-2 text-xs text-destructive flex items-center justify-center gap-1.5 animate-shake">
                            <AlertCircle className="w-4 h-4 shrink-0" />
                            <span>{error}</span>
                        </div>
                    )}

                    <PinPad
                        value={pin}
                        onChange={handleChange}
                        onComplete={handlePinComplete}
                        disabled={isSubmitting}
                        error={Boolean(error)}
                    />

                    {isSubmitting && (
                        <div className="flex items-center gap-2 text-xs font-semibold text-primary pt-2">
                            <span className="animate-spin h-3.5 w-3.5 border-2 border-primary border-t-transparent rounded-full" />
                            <span>Authorizing payment...</span>
                        </div>
                    )}
                </div>
            </SheetContent>
        </Sheet>
    );
}
