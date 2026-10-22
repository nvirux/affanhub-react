import { useState } from 'react';
import { Sheet, SheetContent, SheetHeader, SheetTitle } from '@/components/ui/sheet';
import { Button } from '@/components/ui/button';
import { Fingerprint, ShieldCheck, Zap, X } from 'lucide-react';
import { saveBiometric } from '@/lib/biometrics';
import { toast } from 'sonner';

interface EnableBiometricSheetProps {
    isOpen: boolean;
    onClose: () => void;
    identifier: string;
    token: string;
    aliases?: (string | undefined | null)[];
}

export default function EnableBiometricSheet({
    isOpen,
    onClose,
    identifier,
    token,
    aliases = [],
}: EnableBiometricSheetProps) {
    const [isSaving, setIsSaving] = useState(false);

    const handleEnable = () => {
        setIsSaving(true);
        try {
            saveBiometric(identifier, token, aliases);
            try {
                const saved = localStorage.getItem('affanhub_last_login');
                if (saved) {
                    const parsed = JSON.parse(saved);
                    parsed.biometric_enabled = true;
                    localStorage.setItem('affanhub_last_login', JSON.stringify(parsed));
                }
            } catch {}
            toast.success('Fingerprint login enabled! You can now log in with one touch.');
            onClose();
        } catch (e) {
            console.error('Failed to enable biometric:', e);
            toast.error('Could not activate fingerprint login. Please try again.');
        } finally {
            setIsSaving(false);
        }
    };

    const handleDismiss = () => {
        try {
            sessionStorage.setItem(`bio_prompt_dismissed_${identifier}`, 'true');
        } catch {
            // Ignore storage errors
        }
        onClose();
    };

    return (
        <Sheet open={isOpen} onOpenChange={(open) => !open && handleDismiss()}>
            <SheetContent
                side="bottom"
                className="overflow-hidden rounded-t-3xl max-w-sm sm:max-w-md mx-auto p-5 sm:p-6 bg-white dark:bg-[#181826] border-t border-gray-100 dark:border-gray-800 shadow-2xl focus:outline-none"
            >
                {/* Drag Handle */}
                <div className="w-10 h-1 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mb-3" />

                <div className="flex flex-col items-center text-center space-y-3">
                    {/* Glowing Biometric Icon */}
                    <div className="mx-auto w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-1 ring-8 ring-primary/5 transition-transform hover:scale-105 duration-200">
                        <Fingerprint className="w-9 h-9 stroke-[1.8]" />
                    </div>

                    <SheetHeader className="p-0 text-center space-y-1">
                        <SheetTitle className="text-lg sm:text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                            Enable Fingerprint Login?
                        </SheetTitle>
                        <p className="text-xs text-muted-foreground max-w-xs leading-relaxed">
                            Log in faster and more securely next time with your fingerprint or biometric sensor.
                        </p>
                    </SheetHeader>

                    {/* Value Points */}
                    <div className="w-full bg-gray-50/80 dark:bg-zinc-900/60 border border-gray-100 dark:border-zinc-800 rounded-2xl p-3.5 space-y-2 text-left my-2">
                        <div className="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300">
                            <Zap className="w-4 h-4 text-amber-500 shrink-0" />
                            <span>1-tap instant sign in, no typing required</span>
                        </div>
                        <div className="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300">
                            <ShieldCheck className="w-4 h-4 text-emerald-500 shrink-0" />
                            <span>Hardware encrypted safely on your device</span>
                        </div>
                    </div>

                    {/* Action Buttons */}
                    <div className="w-full flex flex-col gap-2 pt-1">
                        <Button
                            type="button"
                            onClick={handleEnable}
                            disabled={isSaving}
                            className="w-full h-11 rounded-xl font-semibold flex items-center justify-center gap-2 shadow-md shadow-primary/20"
                        >
                            <Fingerprint className="w-4 h-4" />
                            <span>Enable Fingerprint</span>
                        </Button>

                        <button
                            type="button"
                            onClick={handleDismiss}
                            disabled={isSaving}
                            className="w-full py-2 text-xs font-medium text-muted-foreground hover:text-gray-900 dark:hover:text-white transition-colors cursor-pointer"
                        >
                            Maybe Later
                        </button>
                    </div>
                </div>
            </SheetContent>
        </Sheet>
    );
}
