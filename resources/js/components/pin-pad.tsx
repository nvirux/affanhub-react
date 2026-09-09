import React, { useEffect, useCallback } from 'react';
import { Delete } from 'lucide-react';
import { cn } from '@/lib/utils';

type PinPadProps = {
    value: string;
    onChange: (value: string) => void;
    onComplete?: (value: string) => void;
    maxLength?: number;
    disabled?: boolean;
    error?: boolean;
    className?: string;
};

export default function PinPad({
    value,
    onChange,
    onComplete,
    maxLength = 4,
    disabled = false,
    error = false,
    className,
}: PinPadProps) {
    const handleDigit = useCallback(
        (digit: string) => {
            if (disabled) return;
            if (value.length < maxLength) {
                const nextVal = value + digit;
                onChange(nextVal);
                if (nextVal.length === maxLength && onComplete) {
                    onComplete(nextVal);
                }
            }
        },
        [disabled, value, maxLength, onChange, onComplete]
    );

    const handleBackspace = useCallback(() => {
        if (disabled) return;
        if (value.length > 0) {
            onChange(value.slice(0, -1));
        }
    }, [disabled, value, onChange]);

    // Support physical desktop keyboard as well
    useEffect(() => {
        if (disabled) return;

        const handleKeyDown = (e: KeyboardEvent) => {
            // Ignore if active element is an input or textarea
            const target = e.target as HTMLElement;
            if (target && (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA')) {
                return;
            }

            if (/^[0-9]$/.test(e.key)) {
                e.preventDefault();
                handleDigit(e.key);
            } else if (e.key === 'Backspace') {
                e.preventDefault();
                handleBackspace();
            }
        };

        window.addEventListener('keydown', handleKeyDown);
        return () => window.removeEventListener('keydown', handleKeyDown);
    }, [disabled, handleDigit, handleBackspace]);

    const keys = [
        ['1', '2', '3'],
        ['4', '5', '6'],
        ['7', '8', '9'],
        ['', '0', 'backspace'],
    ];

    return (
        <div className={cn('flex flex-col items-center select-none w-full', className)}>
            {/* Visual PIN Dots */}
            <div
                className={cn(
                    'flex items-center justify-center gap-4.5 sm:gap-5 my-3.5 sm:my-4 transition-transform duration-200',
                    error && 'animate-shake'
                )}
            >
                {Array.from({ length: maxLength }).map((_, idx) => {
                    const filled = idx < value.length;
                    return (
                        <div
                            key={idx}
                            className={cn(
                                'size-3.5 sm:size-4 rounded-full border-2 transition-all duration-200',
                                filled
                                    ? 'bg-primary border-primary scale-110 shadow-sm shadow-primary/30'
                                    : 'border-muted-foreground/30 bg-muted/20'
                            )}
                        />
                    );
                })}
            </div>

            {/* Custom On-Screen Keypad with Balanced Circular Keys & Generous Spacing */}
            <div className="grid grid-cols-3 gap-x-5 sm:gap-x-7 gap-y-3 sm:gap-y-4 w-full max-w-[280px] sm:max-w-[300px] mx-auto mt-1">
                {keys.flat().map((key, index) => {
                    if (key === '') {
                        return <div key={index} className="size-16 sm:size-[66px] mx-auto" />;
                    }

                    if (key === 'backspace') {
                        return (
                            <button
                                key={index}
                                type="button"
                                onClick={handleBackspace}
                                disabled={disabled || value.length === 0}
                                aria-label="Delete"
                                className={cn(
                                    'size-16 sm:size-[66px] mx-auto rounded-full flex items-center justify-center',
                                    'text-muted-foreground transition-all duration-150 cursor-pointer',
                                    'hover:bg-muted/70 hover:text-foreground active:scale-90 active:bg-muted',
                                    'disabled:opacity-20 disabled:pointer-events-none'
                                )}
                            >
                                <Delete className="size-6" />
                            </button>
                        );
                    }

                    return (
                        <button
                            key={index}
                            type="button"
                            onClick={() => handleDigit(key)}
                            disabled={disabled || value.length >= maxLength}
                            className={cn(
                                'size-16 sm:size-[66px] mx-auto rounded-full flex items-center justify-center cursor-pointer',
                                'text-2xl sm:text-[28px] font-semibold tracking-tight',
                                'bg-muted/40 hover:bg-muted/70 active:bg-primary/15 text-foreground',
                                'border border-border/40 shadow-2xs active:scale-90 transition-all duration-150',
                                'disabled:opacity-50 disabled:cursor-not-allowed'
                            )}
                        >
                            {key}
                        </button>
                    );
                })}
            </div>
        </div>
    );
}
