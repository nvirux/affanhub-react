import { Head, router } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { Gift, CheckCircle2, ArrowRight, ArrowLeft, AlertCircle, User, Mail, Phone } from 'lucide-react';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import PinPad from '@/components/pin-pad';
import { login } from '@/routes';

type Props = {
    passwordRules?: string;
    errors?: Record<string, string>;
};

export default function Register({ errors: serverErrors = {} }: Props) {
    const [step, setStep] = useState<1 | 2>(1);
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [phone, setPhone] = useState('');
    const [referralCode, setReferralCode] = useState('');
    const [hasRefParam, setHasRefParam] = useState(false);

    // Step 1 Validation
    const [clientErrors, setClientErrors] = useState<Record<string, string>>({});

    // Step 2 PIN state
    const [pinStage, setPinStage] = useState<'enter' | 'confirm'>('enter');
    const [pin, setPin] = useState('');
    const [pinConfirmation, setPinConfirmation] = useState('');
    const [pinError, setPinError] = useState<string | null>(null);
    const [isSubmitting, setIsSubmitting] = useState(false);

    useEffect(() => {
        if (typeof window !== 'undefined') {
            const params = new URLSearchParams(window.location.search);
            const ref = params.get('ref') || params.get('referral_code') || '';
            if (ref) {
                setReferralCode(ref.toUpperCase());
                setHasRefParam(true);
            }

            const idParam = params.get('identifier') || '';
            if (idParam) {
                if (idParam.includes('@')) {
                    setEmail(idParam);
                } else {
                    setPhone(idParam);
                }
            }
        }
    }, []);

    const validateStep1 = () => {
        const errs: Record<string, string> = {};
        if (!name.trim()) {
            errs.name = 'Please enter your full name.';
        }
        if (!email.trim()) {
            errs.email = 'Please enter your email address.';
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim())) {
            errs.email = 'Please enter a valid email address.';
        }
        if (!phone.trim()) {
            errs.phone = 'Please enter your phone number.';
        } else if (phone.replace(/[^0-9]/g, '').length < 7) {
            errs.phone = 'Please enter a valid phone number.';
        }

        setClientErrors(errs);
        return Object.keys(errs).length === 0;
    };

    const handleContinueToPin = (e: React.FormEvent) => {
        e.preventDefault();
        if (validateStep1()) {
            setStep(2);
            setPinStage('enter');
            setPin('');
            setPinConfirmation('');
            setPinError(null);
        }
    };

    const handleEnterComplete = (val: string) => {
        setPin(val);
        setPinError(null);
        setTimeout(() => {
            setPinStage('confirm');
        }, 120);
    };

    const handleConfirmComplete = (confirmVal: string) => {
        setPinConfirmation(confirmVal);

        if (pin !== confirmVal) {
            setPinError('PINs do not match. Please enter a 4-digit PIN again.');
            setTimeout(() => {
                setPin('');
                setPinConfirmation('');
                setPinStage('enter');
            }, 600);
            return;
        }

        setIsSubmitting(true);
        setPinError(null);

        router.post(
            '/register',
            {
                name: name.trim(),
                email: email.trim(),
                phone: phone.trim(),
                referral_code: referralCode.trim() || undefined,
                pin,
                pin_confirmation: confirmVal,
            },
            {
                onError: (errs) => {
                    setIsSubmitting(false);
                    // If errors are on name, email, or phone, seamlessly return user to Step 1
                    if (errs.email || errs.phone || errs.name) {
                        setStep(1);
                        setClientErrors(errs);
                    } else if (errs.pin || errs.pin_confirmation) {
                        setPinError(errs.pin || errs.pin_confirmation || 'Invalid PIN.');
                        setPin('');
                        setPinConfirmation('');
                        setPinStage('enter');
                    } else {
                        setPinError(Object.values(errs)[0] || 'Registration failed. Please try again.');
                    }
                },
                onFinish: () => {
                    setIsSubmitting(false);
                },
            }
        );
    };

    return (
        <>
            <Head title="Register" />

            {step === 1 ? (
                /* Step 1: Account Information */
                <div className="flex flex-col gap-6 w-full">
                    <div className="space-y-1 text-center">
                        <h1 className="text-xl font-bold tracking-tight">Create your account</h1>
                        <p className="text-xs text-muted-foreground">
                            Enter your details below to get started
                        </p>
                    </div>

                    <form onSubmit={handleContinueToPin} className="flex flex-col gap-5">
                        <div className="grid gap-4">
                            <div className="grid gap-1.5">
                                <Label htmlFor="name">Full Name</Label>
                                <div className="relative">
                                    <Input
                                        id="name"
                                        type="text"
                                        required
                                        autoFocus
                                        tabIndex={1}
                                        autoComplete="name"
                                        value={name}
                                        onChange={(e) => {
                                            setName(e.target.value);
                                            if (clientErrors.name) setClientErrors((prev) => ({ ...prev, name: '' }));
                                        }}
                                        placeholder="e.g. John Doe"
                                        className="pl-9"
                                    />
                                    <User className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                </div>
                                {(clientErrors.name || serverErrors.name) && (
                                    <p className="text-xs text-destructive">{clientErrors.name || serverErrors.name}</p>
                                )}
                            </div>

                            <div className="grid gap-1.5">
                                <Label htmlFor="email">Email address</Label>
                                <div className="relative">
                                    <Input
                                        id="email"
                                        type="email"
                                        required
                                        tabIndex={2}
                                        autoComplete="email"
                                        value={email}
                                        onChange={(e) => {
                                            setEmail(e.target.value);
                                            if (clientErrors.email) setClientErrors((prev) => ({ ...prev, email: '' }));
                                        }}
                                        placeholder="name@example.com"
                                        className="pl-9"
                                    />
                                    <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                </div>
                                {(clientErrors.email || serverErrors.email) && (
                                    <p className="text-xs text-destructive">{clientErrors.email || serverErrors.email}</p>
                                )}
                            </div>

                            <div className="grid gap-1.5">
                                <Label htmlFor="phone">Phone number</Label>
                                <div className="relative">
                                    <Input
                                        id="phone"
                                        type="tel"
                                        required
                                        tabIndex={3}
                                        autoComplete="tel"
                                        value={phone}
                                        onChange={(e) => {
                                            setPhone(e.target.value);
                                            if (clientErrors.phone) setClientErrors((prev) => ({ ...prev, phone: '' }));
                                        }}
                                        placeholder="08012345678"
                                        className="pl-9"
                                    />
                                    <Phone className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                </div>
                                {(clientErrors.phone || serverErrors.phone) && (
                                    <p className="text-xs text-destructive">{clientErrors.phone || serverErrors.phone}</p>
                                )}
                            </div>

                            <div className="grid gap-1.5">
                                <div className="flex items-center justify-between">
                                    <Label htmlFor="referral_code" className="flex items-center gap-1.5 text-xs text-muted-foreground font-semibold">
                                        <Gift className="w-3.5 h-3.5 text-amber-500" />
                                        Referral Code (Optional)
                                    </Label>
                                    {hasRefParam && (
                                        <span className="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-full border border-emerald-200/60 dark:border-emerald-800/50">
                                            <CheckCircle2 className="w-3 h-3 text-emerald-500" />
                                            Referred by friend
                                        </span>
                                    )}
                                </div>
                                <Input
                                    id="referral_code"
                                    type="text"
                                    tabIndex={4}
                                    autoComplete="off"
                                    value={referralCode}
                                    onChange={(e) => setReferralCode(e.target.value.toUpperCase())}
                                    placeholder="e.g. AF8X9KZ"
                                    className="font-mono uppercase tracking-wider text-xs"
                                />
                            </div>

                            <Button
                                type="submit"
                                className="w-full flex items-center justify-center gap-2 mt-2 cursor-pointer"
                                tabIndex={5}
                            >
                                Continue
                                <ArrowRight className="w-4 h-4" />
                            </Button>
                        </div>

                        <div className="text-center text-sm text-muted-foreground">
                            Already have an account?{' '}
                            <TextLink href={login()} tabIndex={6}>
                                Log in
                            </TextLink>
                        </div>
                    </form>
                </div>
            ) : (
                /* Step 2: 4-Digit Login PIN Setup */
                <div className="flex flex-col items-center text-center w-full">
                    <div className="space-y-1 mb-2">
                        <div className="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-primary/10 text-primary text-[11px] font-semibold mb-1">
                            Step 2 of 2
                        </div>
                        <h2 className="text-lg font-bold tracking-tight">
                            {pinStage === 'enter' ? 'Create 4-Digit Login PIN' : 'Confirm Your 4-Digit PIN'}
                        </h2>
                        <p className="text-xs text-muted-foreground max-w-xs">
                            {pinStage === 'enter'
                                ? 'Tap the keypad below to set your 4-digit security PIN'
                                : 'Re-enter your 4-digit PIN to confirm'}
                        </p>
                    </div>

                    {pinError && (
                        <div className="w-full max-w-[280px] sm:max-w-[300px] my-2 rounded-xl border border-destructive/20 bg-destructive/10 p-2.5 text-xs text-destructive flex items-center justify-center gap-2 animate-shake">
                            <AlertCircle className="w-4 h-4 shrink-0" />
                            <span>{pinError}</span>
                        </div>
                    )}

                    {/* On-Screen Keypad */}
                    {pinStage === 'enter' ? (
                        <PinPad
                            value={pin}
                            onChange={(val) => {
                                setPin(val);
                                if (pinError) setPinError(null);
                            }}
                            onComplete={handleEnterComplete}
                            disabled={isSubmitting}
                            error={Boolean(pinError)}
                        />
                    ) : (
                        <div className="flex flex-col items-center w-full">
                            <PinPad
                                value={pinConfirmation}
                                onChange={(val) => {
                                    setPinConfirmation(val);
                                    if (pinError) setPinError(null);
                                }}
                                onComplete={handleConfirmComplete}
                                disabled={isSubmitting}
                                error={Boolean(pinError)}
                            />

                            <button
                                type="button"
                                onClick={() => {
                                    setPinStage('enter');
                                    setPin('');
                                    setPinConfirmation('');
                                    setPinError(null);
                                }}
                                disabled={isSubmitting}
                                className="mt-3 text-xs text-muted-foreground hover:text-primary transition-colors cursor-pointer"
                            >
                                Change first PIN
                            </button>
                        </div>
                    )}

                    <Button
                        type="button"
                        variant="ghost"
                        size="sm"
                        onClick={() => {
                            setStep(1);
                            setPin('');
                            setPinConfirmation('');
                            setPinError(null);
                        }}
                        disabled={isSubmitting}
                        className="mt-4 text-xs text-muted-foreground hover:text-foreground flex items-center gap-1.5 cursor-pointer"
                    >
                        <ArrowLeft className="w-3.5 h-3.5" />
                        Back to personal details
                    </Button>

                    {isSubmitting && (
                        <div className="flex items-center gap-2 text-xs font-semibold text-primary mt-3">
                            <Spinner />
                            <span>Creating your account...</span>
                        </div>
                    )}
                </div>
            )}
        </>
    );
}
