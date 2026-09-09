import { Head, router, Link } from '@inertiajs/react';
import { useState } from 'react';
import { KeyRound, ShieldCheck, ChevronLeft, Headphones } from 'lucide-react';
import type { Props as ManageTwoFactorProps } from '@/components/manage-two-factor';
import ManageTwoFactor from '@/components/manage-two-factor';
import { Button } from '@/components/ui/button';
import { PinSheetModal, type PinSheetStep } from '@/components/pin-sheet-modal';
import { edit as editProfile } from '@/routes/profile';

type Props = {
    hasPassword: boolean;
    hasLoginPin: boolean;
    hasTransactionPin: boolean;
} & ManageTwoFactorProps;

export default function Security(props: Props) {
    const [loginPinSheetOpen, setLoginPinSheetOpen] = useState(false);
    const [transactionPinSheetOpen, setTransactionPinSheetOpen] = useState(false);

    const [isSubmittingLoginPin, setIsSubmittingLoginPin] = useState(false);
    const [loginPinError, setLoginPinError] = useState<string | null>(null);

    const [isSubmittingTransPin, setIsSubmittingTransPin] = useState(false);
    const [transPinError, setTransPinError] = useState<string | null>(null);

    // ─── Login PIN Steps ───
    const loginPinSteps: PinSheetStep[] = props.hasLoginPin
        ? [
              {
                  id: 'current_pin',
                  title: 'Current Login PIN',
                  subtitle: 'Enter your current 4-digit security PIN',
                  icon: <KeyRound className="w-6 h-6 text-primary" />,
              },
              {
                  id: 'pin',
                  title: 'New Login PIN',
                  subtitle: 'Enter your new 4-digit security PIN',
                  icon: <KeyRound className="w-6 h-6 text-primary" />,
              },
              {
                  id: 'pin_confirmation',
                  title: 'Confirm New Login PIN',
                  subtitle: 'Re-enter your new 4-digit PIN to confirm',
                  icon: <KeyRound className="w-6 h-6 text-primary" />,
              },
          ]
        : [
              {
                  id: 'pin',
                  title: 'Create 4-Digit Login PIN',
                  subtitle: 'Enter a 4-digit security PIN for your account',
                  icon: <KeyRound className="w-6 h-6 text-primary" />,
              },
              {
                  id: 'pin_confirmation',
                  title: 'Confirm Login PIN',
                  subtitle: 'Re-enter your 4-digit PIN to confirm',
                  icon: <KeyRound className="w-6 h-6 text-primary" />,
              },
          ];

    const handleSubmitLoginPin = (values: Record<string, string>) => {
        setIsSubmittingLoginPin(true);
        setLoginPinError(null);

        router.put('/settings/login-pin', values, {
            preserveScroll: true,
            onSuccess: () => {
                setIsSubmittingLoginPin(false);
                setLoginPinSheetOpen(false);
            },
            onError: (errors) => {
                const message =
                    errors.current_pin ||
                    errors.pin ||
                    errors.pin_confirmation ||
                    Object.values(errors)[0];
                setLoginPinError(
                    typeof message === 'string'
                        ? message
                        : 'Failed to update Login PIN. Please try again.'
                );
                setIsSubmittingLoginPin(false);
            },
        });
    };

    // ─── Transaction PIN Steps ───
    const transactionPinSteps: PinSheetStep[] = props.hasTransactionPin
        ? [
              {
                  id: 'current_pin',
                  title: 'Current Transaction PIN',
                  subtitle: 'Enter your current 4-digit Transaction PIN',
                  icon: <ShieldCheck className="w-6 h-6 text-primary" />,
              },
              {
                  id: 'pin',
                  title: 'New Transaction PIN',
                  subtitle: 'Enter your new 4-digit Transaction PIN',
                  icon: <ShieldCheck className="w-6 h-6 text-primary" />,
              },
              {
                  id: 'pin_confirmation',
                  title: 'Confirm New Transaction PIN',
                  subtitle: 'Re-enter your new 4-digit Transaction PIN',
                  icon: <ShieldCheck className="w-6 h-6 text-primary" />,
              },
          ]
        : [
              {
                  id: 'login_pin',
                  title: 'Verify Login PIN',
                  subtitle: 'Enter your 4-digit Login PIN to continue',
                  icon: <KeyRound className="w-6 h-6 text-primary" />,
              },
              {
                  id: 'pin',
                  title: 'Create Transaction PIN',
                  subtitle: 'Enter a 4-digit PIN to authorize payments',
                  icon: <ShieldCheck className="w-6 h-6 text-primary" />,
              },
              {
                  id: 'pin_confirmation',
                  title: 'Confirm Transaction PIN',
                  subtitle: 'Re-enter your 4-digit Transaction PIN',
                  icon: <ShieldCheck className="w-6 h-6 text-primary" />,
              },
          ];

    const handleSubmitTransactionPin = (values: Record<string, string>) => {
        setIsSubmittingTransPin(true);
        setTransPinError(null);

        router.put('/settings/transaction-pin', values, {
            preserveScroll: true,
            onSuccess: () => {
                setIsSubmittingTransPin(false);
                setTransactionPinSheetOpen(false);
            },
            onError: (errors) => {
                const message =
                    errors.current_pin ||
                    errors.login_pin ||
                    errors.pin ||
                    errors.pin_confirmation ||
                    Object.values(errors)[0];
                setTransPinError(
                    typeof message === 'string'
                        ? message
                        : 'Failed to update Transaction PIN. Please try again.'
                );
                setIsSubmittingTransPin(false);
            },
        });
    };

    return (
        <>
            <Head title="Security - PINs & Authentication" />

            {/* ── MOBILE VIEW STICKY HEADER (flex md:hidden) ── */}
            <div className="sticky top-0 z-30 bg-white/95 dark:bg-[#181826]/95 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 py-3 px-4 flex md:hidden items-center justify-between shadow-2xs">
                <div className="flex items-center gap-3 min-w-0">
                    <Link
                        href={editProfile().url}
                        className="p-1 text-gray-700 dark:text-gray-200 hover:text-primary transition-colors cursor-pointer shrink-0"
                    >
                        <ChevronLeft className="w-6 h-6 stroke-[2.2]" />
                    </Link>
                    <div className="min-w-0">
                        <h1 className="font-extrabold text-base text-gray-900 dark:text-white tracking-tight leading-tight">
                            Security
                        </h1>
                        <p className="text-xs text-slate-400 dark:text-slate-400 font-normal truncate mt-0.5">
                            PINs & Two-Factor Authentication
                        </p>
                    </div>
                </div>

                <div className="flex items-center gap-2 shrink-0 ml-2">
                    <Link
                        href="/contact"
                        className="p-1.5 text-slate-600 dark:text-slate-300 hover:text-primary transition-colors cursor-pointer"
                        title="Customer Support"
                    >
                        <Headphones className="w-5 h-5 stroke-[1.8]" />
                    </Link>
                </div>
            </div>

            {/* ── MAIN CONTENT CONTAINER (matches Airtime, Data, and Earn pages) ── */}
            <div className="max-w-xl mx-auto px-4 py-4 md:py-6 w-full space-y-4">

                {/* 1. 4-Digit Login PIN Card */}
                <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-xs flex items-center justify-between gap-4">
                    <div className="flex items-center gap-3.5 min-w-0">
                        <div className="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                            <KeyRound className="w-5 h-5 stroke-[1.8]" />
                        </div>
                        <div className="min-w-0">
                            <div className="flex items-center gap-2">
                                <h3 className="text-sm font-bold text-gray-900 dark:text-white">
                                    4-Digit Login PIN
                                </h3>
                                <span
                                    className={`text-[10px] font-bold px-2 py-0.5 rounded-full ${
                                        props.hasLoginPin
                                            ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                            : 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400'
                                    }`}
                                >
                                    {props.hasLoginPin ? 'Active' : 'Not Set'}
                                </span>
                            </div>
                            <p className="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                                Used to sign in with on-screen numeric keypad
                            </p>
                        </div>
                    </div>

                    <button
                        type="button"
                        onClick={() => {
                            setLoginPinError(null);
                            setLoginPinSheetOpen(true);
                        }}
                        className="px-4 py-2 rounded-xl bg-primary text-white text-xs font-bold hover:opacity-90 active:scale-95 transition-all shrink-0 cursor-pointer shadow-xs"
                    >
                        {props.hasLoginPin ? 'Change' : 'Set PIN'}
                    </button>
                </div>

                {/* 2. 4-Digit Transaction PIN Card */}
                <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-xs flex items-center justify-between gap-4">
                    <div className="flex items-center gap-3.5 min-w-0">
                        <div className="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0">
                            <ShieldCheck className="w-5 h-5 stroke-[1.8]" />
                        </div>
                        <div className="min-w-0">
                            <div className="flex items-center gap-2">
                                <h3 className="text-sm font-bold text-gray-900 dark:text-white">
                                    Transaction PIN
                                </h3>
                                <span
                                    className={`text-[10px] font-bold px-2 py-0.5 rounded-full ${
                                        props.hasTransactionPin
                                            ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                            : 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400'
                                    }`}
                                >
                                    {props.hasTransactionPin ? 'Active' : 'Not Set'}
                                </span>
                            </div>
                            <p className="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                                Required for Airtime, Data & bills authorization
                            </p>
                        </div>
                    </div>

                    <button
                        type="button"
                        onClick={() => {
                            setTransPinError(null);
                            setTransactionPinSheetOpen(true);
                        }}
                        className="px-4 py-2 rounded-xl bg-primary text-white text-xs font-bold hover:opacity-90 active:scale-95 transition-all shrink-0 cursor-pointer shadow-xs"
                    >
                        {props.hasTransactionPin ? 'Change' : 'Set PIN'}
                    </button>
                </div>

                {/* 3. Two-Factor Authentication Card */}
                <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-xs">
                    <ManageTwoFactor {...props} />
                </div>
            </div>

            {/* ─── Bottom Sheet Modal for Login PIN ─── */}
            <PinSheetModal
                isOpen={loginPinSheetOpen}
                onOpenChange={setLoginPinSheetOpen}
                title={props.hasLoginPin ? 'Change Login PIN' : 'Set Login PIN'}
                steps={loginPinSteps}
                onComplete={handleSubmitLoginPin}
                isSubmitting={isSubmittingLoginPin}
                serverError={loginPinError}
                onClearError={() => setLoginPinError(null)}
            />

            {/* ─── Bottom Sheet Modal for Transaction PIN ─── */}
            <PinSheetModal
                isOpen={transactionPinSheetOpen}
                onOpenChange={setTransactionPinSheetOpen}
                title={
                    props.hasTransactionPin
                        ? 'Change Transaction PIN'
                        : 'Set Transaction PIN'
                }
                steps={transactionPinSteps}
                onComplete={handleSubmitTransactionPin}
                isSubmitting={isSubmittingTransPin}
                serverError={transPinError}
                onClearError={() => setTransPinError(null)}
            />
        </>
    );
}
