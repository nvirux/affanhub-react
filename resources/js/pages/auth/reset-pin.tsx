import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import { KeyRound, AlertCircle, ArrowLeft } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import PinPad from '@/components/pin-pad';

type Props = {
    token: string;
    email: string;
};

export default function ResetPin({ token, email }: Props) {
    const [stage, setStage] = useState<'enter' | 'confirm'>('enter');
    const [pin, setPin] = useState('');
    const [pinConfirmation, setPinConfirmation] = useState('');
    const [error, setError] = useState<string | null>(null);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const handleEnterComplete = (val: string) => {
        setPin(val);
        setError(null);
        setTimeout(() => {
            setStage('confirm');
        }, 120);
    };

    const handleConfirmComplete = (confirmVal: string) => {
        setPinConfirmation(confirmVal);

        if (pin !== confirmVal) {
            setError('PINs do not match. Please try setting your PIN again.');
            setTimeout(() => {
                setPin('');
                setPinConfirmation('');
                setStage('enter');
            }, 600);
            return;
        }

        setIsSubmitting(true);
        setError(null);

        router.post(
            '/reset-pin',
            {
                token,
                email,
                pin,
                pin_confirmation: confirmVal,
            },
            {
                onError: (errs) => {
                    const message =
                        errs.pin ||
                        errs.pin_confirmation ||
                        errs.token ||
                        errs.email ||
                        Object.values(errs)[0];
                    setError(
                        typeof message === 'string'
                            ? message
                            : 'Failed to reset PIN. The reset link may have expired.'
                    );
                    setPin('');
                    setPinConfirmation('');
                    setStage('enter');
                    setIsSubmitting(false);
                },
                onFinish: () => {
                    setIsSubmitting(false);
                },
            }
        );
    };

    const handleBackToEnter = () => {
        setStage('enter');
        setPin('');
        setPinConfirmation('');
        setError(null);
    };

    return (
        <>
            <Head title="Reset Login PIN" />

            <div className="flex flex-col items-center gap-4 text-center">
                <div className="space-y-1">
                    <div className="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-primary mb-1">
                        <KeyRound className="h-5 w-5" />
                    </div>
                    <h2 className="text-xl font-bold tracking-tight">
                        {stage === 'enter' ? 'Create New 4-Digit PIN' : 'Confirm Your New PIN'}
                    </h2>
                    <p className="text-xs text-muted-foreground max-w-xs">
                        {stage === 'enter'
                            ? `Setting a new Login PIN for ${email}`
                            : 'Re-enter your new 4-digit PIN to confirm'}
                    </p>
                </div>

                {error && (
                    <div className="w-full max-w-[280px] rounded-xl border border-destructive/20 bg-destructive/10 p-2.5 text-xs text-destructive flex items-center justify-center gap-2 animate-shake">
                        <AlertCircle className="w-4 h-4 shrink-0" />
                        <span>{error}</span>
                    </div>
                )}

                {/* In-Page Custom Numeric Keypad PIN View */}
                {stage === 'enter' ? (
                    <PinPad
                        value={pin}
                        onChange={(val) => {
                            setPin(val);
                            if (error) setError(null);
                        }}
                        onComplete={handleEnterComplete}
                        disabled={isSubmitting}
                        error={Boolean(error)}
                    />
                ) : (
                    <div className="flex flex-col items-center">
                        <PinPad
                            value={pinConfirmation}
                            onChange={(val) => {
                                setPinConfirmation(val);
                                if (error) setError(null);
                            }}
                            onComplete={handleConfirmComplete}
                            disabled={isSubmitting}
                            error={Boolean(error)}
                        />

                        <Button
                            type="button"
                            variant="ghost"
                            size="sm"
                            onClick={handleBackToEnter}
                            disabled={isSubmitting}
                            className="mt-3 text-xs text-muted-foreground hover:text-foreground flex items-center gap-1.5"
                        >
                            <ArrowLeft className="w-3.5 h-3.5" />
                            Change first PIN
                        </Button>
                    </div>
                )}

                {isSubmitting && (
                    <div className="flex items-center gap-2 text-xs font-semibold text-primary mt-2">
                        <Spinner />
                        <span>Updating your PIN...</span>
                    </div>
                )}
            </div>
        </>
    );
}
