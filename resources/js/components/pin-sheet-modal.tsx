import React, { useState, useEffect } from 'react';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet';
import { Button } from '@/components/ui/button';
import { Lock, AlertCircle, ArrowLeft } from 'lucide-react';
import { Spinner } from '@/components/ui/spinner';
import PinPad from '@/components/pin-pad';

export interface PinSheetStep {
    id: string;
    title: string;
    subtitle: string;
    icon?: React.ReactNode;
}

interface PinSheetModalProps {
    isOpen: boolean;
    onOpenChange: (open: boolean) => void;
    title: string;
    steps: PinSheetStep[];
    onComplete: (values: Record<string, string>) => void;
    isSubmitting: boolean;
    serverError?: string | null;
    onClearError?: () => void;
    confirmStepId?: string;
    newPinStepId?: string;
}

export function PinSheetModal({
    isOpen,
    onOpenChange,
    title,
    steps,
    onComplete,
    isSubmitting,
    serverError,
    onClearError,
    confirmStepId = 'pin_confirmation',
    newPinStepId = 'pin',
}: PinSheetModalProps) {
    const [currentStepIndex, setCurrentStepIndex] = useState(0);
    const [stepValues, setStepValues] = useState<Record<string, string>>({});
    const [currentPin, setCurrentPin] = useState('');
    const [localError, setLocalError] = useState<string | null>(null);

    // Reset internal state whenever sheet opens or closes
    useEffect(() => {
        if (!isOpen) {
            setCurrentStepIndex(0);
            setStepValues({});
            setCurrentPin('');
            setLocalError(null);
            if (onClearError) onClearError();
        }
    }, [isOpen]);

    // Handle server errors
    useEffect(() => {
        if (serverError) {
            setLocalError(serverError);
            // Reset to first step on server error so user can re-authenticate
            setCurrentStepIndex(0);
            setStepValues({});
            setCurrentPin('');
        }
    }, [serverError]);

    const activeStep = steps[currentStepIndex] || steps[0];
    const totalSteps = steps.length;
    const activeError = localError || serverError;

    const handlePinChange = (val: string) => {
        setCurrentPin(val);
        if (localError) setLocalError(null);
        if (serverError && onClearError) onClearError();
    };

    const handlePinComplete = (completedVal: string) => {
        const nextValues = {
            ...stepValues,
            [activeStep.id]: completedVal,
        };
        setStepValues(nextValues);

        // Check if this was a confirmation step
        if (activeStep.id === confirmStepId) {
            const expectedPin = nextValues[newPinStepId];
            if (expectedPin && expectedPin !== completedVal) {
                setLocalError('PINs do not match. Please enter your new PIN again.');
                // Find newPinStepIndex to jump back
                const newPinIndex = steps.findIndex((s) => s.id === newPinStepId);
                setTimeout(() => {
                    setCurrentStepIndex(newPinIndex >= 0 ? newPinIndex : 0);
                    setCurrentPin('');
                    const resetValues = { ...nextValues };
                    delete resetValues[confirmStepId];
                    delete resetValues[newPinStepId];
                    setStepValues(resetValues);
                }, 600);
                return;
            }

            // All steps verified, invoke onComplete
            setCurrentPin('');
            onComplete(nextValues);
            return;
        }

        // Advance to next step if more steps exist
        if (currentStepIndex < totalSteps - 1) {
            setTimeout(() => {
                setCurrentStepIndex((prev) => prev + 1);
                setCurrentPin('');
                setLocalError(null);
            }, 100);
        } else {
            setCurrentPin('');
            onComplete(nextValues);
        }
    };

    const handleBackStep = () => {
        if (currentStepIndex > 0) {
            const prevIndex = currentStepIndex - 1;
            const prevStep = steps[prevIndex];
            const updatedValues = { ...stepValues };
            delete updatedValues[activeStep.id];
            delete updatedValues[prevStep.id];
            setStepValues(updatedValues);
            setCurrentStepIndex(prevIndex);
            setCurrentPin('');
            setLocalError(null);
            if (serverError && onClearError) onClearError();
        }
    };

    return (
        <Sheet
            open={isOpen}
            onOpenChange={(open) => {
                if (!isSubmitting) {
                    onOpenChange(open);
                }
            }}
        >
            <SheetContent
                side="bottom"
                className="rounded-t-3xl max-w-sm sm:max-w-md mx-auto p-4 sm:p-6 bg-white dark:bg-[#181826] border-t border-gray-100 dark:border-zinc-800 shadow-2xl focus:outline-none"
            >
                {/* Drag handle */}
                <div className="w-12 h-1.5 bg-gray-200 dark:bg-zinc-700 rounded-full mx-auto mb-3" />

                <div className="flex flex-col items-center text-center space-y-2">
                    <SheetHeader className="p-0 text-center space-y-1 w-full">
                        <div className="mx-auto w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-1 ring-4 ring-primary/5">
                            {activeStep.icon || <Lock className="w-6 h-6" />}
                        </div>

                        {/* Step indicator pills */}
                        {totalSteps > 1 && (
                            <div className="flex items-center justify-center gap-1.5 py-1">
                                {steps.map((s, idx) => (
                                    <div
                                        key={s.id}
                                        className={`h-1.5 rounded-full transition-all duration-300 ${
                                            idx === currentStepIndex
                                                ? 'w-6 bg-primary'
                                                : idx < currentStepIndex
                                                ? 'w-2 bg-emerald-500'
                                                : 'w-2 bg-gray-200 dark:bg-zinc-700'
                                        }`}
                                    />
                                ))}
                            </div>
                        )}

                        <SheetTitle className="text-lg font-bold text-gray-900 dark:text-white">
                            {activeStep.title}
                        </SheetTitle>
                        <p className="text-xs text-muted-foreground max-w-xs mx-auto">
                            {activeStep.subtitle}
                        </p>
                    </SheetHeader>

                    {activeError && (
                        <div className="w-full max-w-[280px] sm:max-w-[300px] my-1 rounded-xl border border-destructive/20 bg-destructive/10 p-2.5 text-xs text-destructive flex items-center justify-center gap-2 animate-shake">
                            <AlertCircle className="w-4 h-4 shrink-0" />
                            <span>{activeError}</span>
                        </div>
                    )}

                    {/* Numeric Keypad PIN Pad */}
                    <PinPad
                        value={currentPin}
                        onChange={handlePinChange}
                        onComplete={handlePinComplete}
                        disabled={isSubmitting}
                        error={Boolean(activeError)}
                    />

                    {/* Back / Navigation Button */}
                    {currentStepIndex > 0 && !isSubmitting && (
                        <Button
                            type="button"
                            variant="ghost"
                            size="sm"
                            onClick={handleBackStep}
                            className="mt-2 text-xs text-muted-foreground hover:text-foreground flex items-center gap-1.5 cursor-pointer"
                        >
                            <ArrowLeft className="w-3.5 h-3.5" />
                            <span>Back to previous step</span>
                        </Button>
                    )}

                    {isSubmitting && (
                        <div className="flex items-center gap-2 text-xs font-semibold text-primary pt-2">
                            <span className="animate-spin h-4 w-4 border-2 border-primary border-t-transparent rounded-full" />
                            <span>Saving your PIN...</span>
                        </div>
                    )}
                </div>
            </SheetContent>
        </Sheet>
    );
}
