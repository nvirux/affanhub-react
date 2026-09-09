import { Head, router } from '@inertiajs/react';
import { useState, useRef, useEffect } from 'react';
import { ArrowRight, User, AlertCircle, KeyRound } from 'lucide-react';
import PasswordInput from '@/components/password-input';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import PinPad from '@/components/pin-pad';
import { register } from '@/routes';
import { request } from '@/routes/password';

type Props = {
    status?: string;
    canResetPassword: boolean;
};

const STORAGE_KEY = 'affanhub_last_login';

function getXsrfToken(): string {
    if (typeof document === 'undefined') return '';
    const match = document.cookie.match(new RegExp('(^|;\\s*)(?:XSRF-TOKEN|X-XSRF-TOKEN)=([^;]*)'));
    return match ? decodeURIComponent(match[2]) : '';
}

export default function Login({ status, canResetPassword }: Props) {
    const [step, setStep] = useState<1 | 2>(1);
    const [identifier, setIdentifier] = useState('');
    const [identifierError, setIdentifierError] = useState<string | null>(null);
    const [isChecking, setIsChecking] = useState(false);

    // Step 2 state
    const [userPinEnabled, setUserPinEnabled] = useState(false);
    const [hasPassword, setHasPassword] = useState(false);
    const [maskedIdentifier, setMaskedIdentifier] = useState('');
    const [userName, setUserName] = useState('');
    const [pin, setPin] = useState('');
    const [password, setPassword] = useState('');
    const [usePasswordFallback, setUsePasswordFallback] = useState(false);
    const [loginError, setLoginError] = useState<string | null>(null);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const identifierInputRef = useRef<HTMLInputElement>(null);

    const saveRememberedAccount = (data: {
        identifier: string;
        masked_identifier: string;
        name: string;
        login_pin_enabled: boolean;
        has_password?: boolean;
    }) => {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
        } catch {
            // Ignore storage errors
        }
    };

    // Load saved account display hint from localStorage & refresh from server
    useEffect(() => {
        try {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved) {
                const parsed = JSON.parse(saved);
                if (parsed && parsed.identifier) {
                    setIdentifier(parsed.identifier);
                    setMaskedIdentifier(parsed.masked_identifier || parsed.identifier);
                    setUserName(parsed.name || '');
                    setUserPinEnabled(Boolean(parsed.login_pin_enabled));
                    setHasPassword(Boolean(parsed.has_password));
                    setStep(2);

                    // Re-verify current PIN status & password status from server
                    fetch('/login/check-identifier', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': getXsrfToken(),
                        },
                        body: JSON.stringify({ identifier: parsed.identifier }),
                    })
                        .then((res) => res.json())
                        .then((data) => {
                            if (data && data.status === 'found') {
                                const isPin = Boolean(data.login_pin_enabled);
                                const hasPwd = Boolean(data.has_password);
                                setUserPinEnabled(isPin);
                                setHasPassword(hasPwd);
                                saveRememberedAccount({
                                    identifier: parsed.identifier,
                                    masked_identifier: data.masked_identifier || parsed.identifier,
                                    name: data.name || parsed.name || '',
                                    login_pin_enabled: isPin,
                                    has_password: hasPwd,
                                });
                            }
                        })
                        .catch(() => {});
                }
            }
        } catch {
            // Ignore storage errors
        }
    }, []);

    const handleSwitchAccount = () => {
        try {
            localStorage.removeItem(STORAGE_KEY);
        } catch {
            // Ignore storage errors
        }
        setStep(1);
        setIdentifier('');
        setMaskedIdentifier('');
        setUserName('');
        setPin('');
        setPassword('');
        setUsePasswordFallback(false);
        setLoginError(null);
        setIdentifierError(null);
        setTimeout(() => {
            identifierInputRef.current?.focus();
        }, 50);
    };

    const handleCheckIdentifier = async (e: React.FormEvent) => {
        e.preventDefault();
        const cleanId = identifier.trim();
        if (!cleanId) {
            setIdentifierError('Please enter your phone number or email address.');
            identifierInputRef.current?.focus();
            return;
        }

        setIsChecking(true);
        setIdentifierError(null);

        try {
            const response = await fetch('/login/check-identifier', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-XSRF-TOKEN': getXsrfToken(),
                },
                body: JSON.stringify({ identifier: cleanId }),
            });

            const data = await response.json();

            if (!response.ok) {
                setIdentifierError(data.message || 'No account found with this phone number or email.');
                setIsChecking(false);
                return;
            }

            const isPin = Boolean(data.login_pin_enabled);
            const hasPwd = Boolean(data.has_password);
            const masked = data.masked_identifier || cleanId;
            const name = data.name || '';

            setUserPinEnabled(isPin);
            setHasPassword(hasPwd);
            setMaskedIdentifier(masked);
            setUserName(name);
            setUsePasswordFallback(false);
            setStep(2);
            setLoginError(null);

            // Save UX display hint
            saveRememberedAccount({
                identifier: cleanId,
                masked_identifier: masked,
                name,
                login_pin_enabled: isPin,
                has_password: hasPwd,
            });
        } catch {
            setIdentifierError('Unable to connect. Please check your network connection.');
        } finally {
            setIsChecking(false);
        }
    };

    const submitLogin = (overridePin?: string) => {
        if (isSubmitting) return;

        const isPinMode = userPinEnabled && !usePasswordFallback;
        const currentPin = overridePin ?? pin;

        if (isPinMode && currentPin.length !== 4) {
            setLoginError('Please enter your complete 4-digit PIN.');
            return;
        }

        if (!isPinMode && !password) {
            setLoginError('Please enter your password.');
            return;
        }

        setIsSubmitting(true);
        setLoginError(null);

        router.post(
            '/login',
            {
                email: identifier,
                phone: identifier,
                pin: isPinMode ? currentPin : '',
                password: isPinMode ? currentPin : password,
                remember: true,
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    // Update display hint on successful login
                    saveRememberedAccount({
                        identifier,
                        masked_identifier: maskedIdentifier || identifier,
                        name: userName,
                        login_pin_enabled: userPinEnabled,
                        has_password: hasPassword,
                    });
                },
                onError: (errs) => {
                    const message =
                        errs.email ||
                        errs.phone ||
                        errs.password ||
                        errs.pin ||
                        Object.values(errs)[0];
                    setLoginError(
                        typeof message === 'string'
                            ? message
                            : 'Invalid credentials. Please try again.'
                    );
                    if (isPinMode) {
                        setPin('');
                    }
                    setIsSubmitting(false);
                },
                onFinish: () => {
                    setIsSubmitting(false);
                },
            }
        );
    };

    return (
        <>
            <Head title="Log in" />

            {status && (
                <div className="mb-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 p-3 text-center text-sm font-medium text-emerald-600 dark:text-emerald-400">
                    {status}
                </div>
            )}

            {step === 1 ? (
                /* Step 1: Identifier Entry */
                <div className="flex flex-col gap-6">
                    <form onSubmit={handleCheckIdentifier} className="flex flex-col gap-6">
                        <div className="grid gap-6">
                            <div className="grid gap-2">
                                <Label htmlFor="identifier">Phone number or Email</Label>
                                <div className="relative">
                                    <Input
                                        ref={identifierInputRef}
                                        id="identifier"
                                        type="text"
                                        name="identifier"
                                        value={identifier}
                                        onChange={(e) => {
                                            setIdentifier(e.target.value);
                                            if (identifierError) setIdentifierError(null);
                                        }}
                                        required
                                        autoFocus
                                        tabIndex={1}
                                        autoComplete="username"
                                        placeholder="e.g. 08012345678 or name@email.com"
                                        disabled={isChecking}
                                        className="pl-9"
                                    />
                                    <User className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                </div>
                                <p className="text-[11px] text-muted-foreground">
                                    Enter the phone number or email registered with your account.
                                </p>
                                {identifierError && (
                                    <div className="mt-1 rounded-lg border border-destructive/20 bg-destructive/10 p-3 text-xs text-destructive flex items-start gap-2">
                                        <AlertCircle className="w-4 h-4 shrink-0 mt-0.5" />
                                        <div className="flex flex-col gap-1">
                                            <span>{identifierError}</span>
                                            <TextLink
                                                href={`${register()}?identifier=${encodeURIComponent(identifier)}`}
                                                className="text-xs font-semibold underline hover:text-destructive/80"
                                            >
                                                Don't have an account? Sign up &rarr;
                                            </TextLink>
                                        </div>
                                    </div>
                                )}
                            </div>

                            <Button
                                type="submit"
                                className="w-full flex items-center justify-center gap-2"
                                tabIndex={2}
                                disabled={isChecking}
                            >
                                {isChecking ? (
                                    <>
                                        <Spinner />
                                        Checking...
                                    </>
                                ) : (
                                    <>
                                        Continue
                                        <ArrowRight className="w-4 h-4" />
                                    </>
                                )}
                            </Button>
                        </div>

                        <div className="text-center text-sm text-muted-foreground">
                            Don't have an account?{' '}
                            <TextLink href={register()} tabIndex={3}>
                                Sign up
                            </TextLink>
                        </div>
                    </form>
                </div>
            ) : (
                /* Step 2: Credential Entry (Clean, Balanced & Organized) */
                <div className="flex flex-col items-center w-full">
                    <h2 className="text-lg font-bold tracking-tight text-center">
                        {userName ? `Welcome back, ${userName}` : 'Welcome back'}
                    </h2>

                    <div className="flex items-center justify-center gap-2 text-xs text-muted-foreground mt-0.5 mb-2">
                        <span className="font-mono">{maskedIdentifier}</span>
                        <span>•</span>
                        <button
                            type="button"
                            onClick={handleSwitchAccount}
                            className="text-primary font-medium hover:underline cursor-pointer"
                        >
                            Switch account
                        </button>
                    </div>

                    {loginError && (
                        <div className="w-full max-w-[280px] sm:max-w-[300px] my-2 rounded-xl border border-destructive/20 bg-destructive/10 p-2.5 text-xs text-destructive flex items-center justify-center gap-2 animate-shake">
                            <AlertCircle className="w-4 h-4 shrink-0" />
                            <span>{loginError}</span>
                        </div>
                    )}

                    {userPinEnabled && !usePasswordFallback ? (
                        /* In-Page Custom Numeric Keypad PIN View */
                        <div className="flex flex-col items-center text-center w-full">
                            <PinPad
                                value={pin}
                                onChange={(val) => {
                                    setPin(val);
                                    if (loginError) setLoginError(null);
                                }}
                                onComplete={(val) => {
                                    submitLogin(val);
                                }}
                                disabled={isSubmitting}
                                error={Boolean(loginError)}
                            />

                            <div className={`flex items-center ${hasPassword ? 'justify-between' : 'justify-center'} w-full max-w-[280px] sm:max-w-[300px] text-xs pt-4`}>
                                <TextLink
                                    href={`/forgot-pin?identifier=${encodeURIComponent(identifier)}`}
                                    className="text-xs text-muted-foreground hover:text-primary transition-colors"
                                >
                                    Forgot PIN?
                                </TextLink>

                                {hasPassword && (
                                    <button
                                        type="button"
                                        onClick={() => {
                                            setUsePasswordFallback(true);
                                            setLoginError(null);
                                        }}
                                        className="text-xs text-muted-foreground hover:text-primary transition-colors cursor-pointer"
                                    >
                                        Use password
                                    </button>
                                )}
                            </div>

                            {isSubmitting && (
                                <div className="flex items-center gap-2 text-xs font-semibold text-primary mt-3">
                                    <Spinner />
                                    <span>Logging in...</span>
                                </div>
                            )}
                        </div>
                    ) : (
                        /* Password View */
                        <form
                            onSubmit={(e) => {
                                e.preventDefault();
                                submitLogin();
                            }}
                            className="w-full max-w-[320px] grid gap-4 mt-2"
                        >
                            <div className="grid gap-2">
                                <div className="flex items-center justify-between">
                                    <Label htmlFor="password">Password</Label>
                                    {canResetPassword && (
                                        <TextLink href={request()} className="text-xs">
                                            Forgot your password?
                                        </TextLink>
                                    )}
                                </div>
                                <PasswordInput
                                    id="password"
                                    name="password"
                                    value={password}
                                    onChange={(e) => {
                                        setPassword(e.target.value);
                                        if (loginError) setLoginError(null);
                                    }}
                                    required
                                    autoFocus
                                    autoComplete="current-password"
                                    placeholder="Enter your password"
                                    disabled={isSubmitting}
                                />
                            </div>

                            {userPinEnabled && (
                                <div className="flex items-center justify-end text-xs">
                                    <button
                                        type="button"
                                        onClick={() => {
                                            setUsePasswordFallback(false);
                                            setLoginError(null);
                                        }}
                                        className="text-xs text-primary hover:underline flex items-center gap-1 cursor-pointer"
                                    >
                                        <KeyRound className="w-3 h-3" />
                                        Use 4-digit PIN instead
                                    </button>
                                </div>
                            )}

                            <Button
                                type="submit"
                                className="w-full mt-2"
                                disabled={isSubmitting || !password}
                            >
                                {isSubmitting && <Spinner />}
                                Log in
                            </Button>
                        </form>
                    )}
                </div>
            )}
        </>
    );
}
