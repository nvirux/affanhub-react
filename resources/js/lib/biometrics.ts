/**
 * Biometric / Fingerprint Bridge Utilities for Android WebView.
 * Communicates with native Android via `window.AffanBridge`.
 */

declare global {
    interface Window {
        AffanBridge?: {
            canAuthenticateBiometric?: () => boolean;
            hasBiometricFor?: (identifier: string) => boolean;
            saveBiometric?: (identifier: string, pin: string) => void;
            clearBiometricFor?: (identifier: string) => void;
            promptBiometricFor?: (identifier: string, title: string) => void;

            // Transaction methods
            hasTransactionPin?: () => boolean;
            saveTransactionPin?: (pin: string) => void;
            clearTransactionPin?: () => void;
            promptTransactionBiometric?: (title: string, subtitle: string) => void;

            // Legacy fallbacks
            hasSavedBiometricToken?: () => boolean;
            saveBiometricToken?: (token: string) => void;
            clearBiometricToken?: () => void;
            promptBiometric?: (title: string, subtitle?: string) => void;
        };
        isAffanApp?: boolean;
        onBiometricSuccess?: (tokenOrPin?: string) => void;
        onBiometricError?: (message?: string) => void;
        onTransactionBiometricSuccess?: (pin?: string) => void;
        onTransactionBiometricError?: (message?: string) => void;
    }
}

function getIdentifierVariants(identifier?: string, aliases: (string | undefined | null)[] = []): string[] {
    const set = new Set<string>();
    const add = (raw?: string | null) => {
        if (!raw) return;
        const clean = raw.trim().toLowerCase();
        if (!clean) return;
        set.add(clean);
        const digits = clean.replace(/[^0-9]/g, '');
        if (digits.length >= 7) {
            set.add(digits);
            if (digits.startsWith('234') && digits.length >= 13) {
                set.add('0' + digits.substring(3));
                set.add('+' + digits);
            } else if (digits.startsWith('0') && digits.length === 11) {
                set.add('234' + digits.substring(1));
                set.add('+234' + digits.substring(1));
            }
        }
    };
    add(identifier);
    aliases.forEach(add);
    return Array.from(set);
}

/**
 * Checks whether the current device supports hardware biometric authentication
 * and is running inside the AffanHub Android App.
 */
export function isBiometricSupported(): boolean {
    if (typeof window === 'undefined') return false;
    try {
        return Boolean(window.AffanBridge?.canAuthenticateBiometric?.());
    } catch {
        return false;
    }
}

/**
 * Checks whether a biometric PIN or token was previously saved for this specific user or device.
 * ONLY if this returns true should the fingerprint button appear on the Login PIN keypad.
 */
export function hasBiometricFor(identifier?: string, aliases: (string | undefined | null)[] = []): boolean {
    if (typeof window === 'undefined') return false;
    try {
        const variants = getIdentifierVariants(identifier, aliases);

        // 1. Check native bridge hasBiometricFor for all variants
        if (window.AffanBridge?.hasBiometricFor) {
            for (const v of variants) {
                if (window.AffanBridge.hasBiometricFor(v)) {
                    return true;
                }
            }
        }

        // 2. Check native bridge hasSavedBiometricToken
        if (window.AffanBridge?.hasSavedBiometricToken?.()) {
            return true;
        }

        // 3. Fallback to localStorage check
        for (const v of variants) {
            try {
                if (localStorage.getItem(`bio_enabled_${v}`) === 'true') {
                    return true;
                }
            } catch {}
        }

        try {
            if (localStorage.getItem('affan_bio_enabled') === 'true') {
                return true;
            }
        } catch {}

        return false;
    } catch {
        return false;
    }
}

/**
 * Saves the user's PIN/token securely in the hardware keystore bound to this identifier and aliases.
 */
export function saveBiometric(identifier: string, token: string, aliases: (string | undefined | null)[] = []): void {
    if (typeof window === 'undefined' || !identifier || !token) return;
    try {
        const variants = getIdentifierVariants(identifier, aliases);

        for (const v of variants) {
            if (window.AffanBridge?.saveBiometric) {
                window.AffanBridge.saveBiometric(v, token);
            }
            try {
                localStorage.setItem(`bio_enabled_${v}`, 'true');
            } catch {}
        }

        // Always save device-level fallback token as well
        window.AffanBridge?.saveBiometricToken?.(token);
        try {
            localStorage.setItem('affan_bio_enabled', 'true');
        } catch {}
    } catch (e) {
        console.warn('Failed saving biometric PIN:', e);
    }
}

/**
 * Clears the saved biometric PIN for a specific identifier and aliases.
 */
export function clearBiometricFor(identifier: string, aliases: (string | undefined | null)[] = []): void {
    if (typeof window === 'undefined' || !identifier) return;
    try {
        const variants = getIdentifierVariants(identifier, aliases);
        for (const v of variants) {
            window.AffanBridge?.clearBiometricFor?.(v);
            try {
                localStorage.removeItem(`bio_enabled_${v}`);
            } catch {}
        }
        window.AffanBridge?.clearBiometricToken?.();
        try {
            localStorage.removeItem('affan_bio_enabled');
        } catch {}
    } catch (e) {
        console.warn('Failed clearing biometric PIN for identifier:', e);
    }
}

/**
 * Launches native biometric prompt for Login.
 * On fingerprint match, Android returns the saved token/PIN for this identifier.
 */
export function promptBiometricFor(options: {
    identifier: string;
    aliases?: (string | undefined | null)[];
    title?: string;
    onSuccess: (pin: string) => void;
    onError?: (error: string) => void;
}): void {
    if (typeof window === 'undefined') {
        options.onError?.('Biometric authentication is not supported.');
        return;
    }

    window.onBiometricSuccess = (pin?: string) => {
        if (pin && pin.length > 0) {
            options.onSuccess(pin);
        } else {
            options.onError?.('No credentials stored. Please enter your PIN.');
        }
    };

    window.onBiometricError = (error?: string) => {
        options.onError?.(error || 'Fingerprint verification failed.');
    };

    if (window.AffanBridge?.promptBiometricFor) {
        window.AffanBridge.promptBiometricFor(
            options.identifier,
            options.title || 'Biometric Login'
        );
    } else if (window.AffanBridge?.promptBiometric) {
        window.AffanBridge.promptBiometric(
            options.title || 'Biometric Login',
            'Scan your fingerprint to log in'
        );
    } else {
        options.onError?.('Biometric prompt is unavailable on this device.');
    }
}

/**
 * Checks whether a transaction PIN was saved on this phone.
 * ONLY if this returns true should the fingerprint button appear on the Payment PIN keypad.
 */
export function hasTransactionPin(): boolean {
    if (typeof window === 'undefined') return false;
    try {
        return Boolean(window.AffanBridge?.hasTransactionPin?.());
    } catch {
        return false;
    }
}

/**
 * Saves the user's transaction PIN securely in Keystore for fast biometric approval.
 */
export function saveTransactionPin(pin: string): void {
    if (typeof window === 'undefined' || !pin) return;
    try {
        window.AffanBridge?.saveTransactionPin?.(pin);
    } catch (e) {
        console.warn('Failed saving transaction PIN in biometric vault:', e);
    }
}

/**
 * Clears stored transaction PIN (called if PIN was changed or reset).
 */
export function clearTransactionPin(): void {
    if (typeof window === 'undefined') return;
    try {
        window.AffanBridge?.clearTransactionPin?.();
    } catch (e) {
        console.warn('Failed clearing transaction PIN:', e);
    }
}

/**
 * Launches native biometric prompt for Transaction Approval.
 * On fingerprint match, Android returns the saved transaction PIN.
 */
export function promptTransactionBiometric(options: {
    title?: string;
    subtitle?: string;
    onSuccess: (pin: string) => void;
    onError?: (error: string) => void;
}): void {
    if (typeof window === 'undefined') {
        options.onError?.('Biometric authentication is not available.');
        return;
    }

    const promptFn = window.AffanBridge?.promptTransactionBiometric || window.AffanBridge?.promptBiometric;

    if (!promptFn) {
        options.onError?.('Biometric prompt is not available on this device.');
        return;
    }

    window.onTransactionBiometricSuccess = (pin?: string) => {
        if (pin && pin.length > 0) {
            options.onSuccess(pin);
        } else {
            options.onError?.('Please enter your 4-digit PIN once to activate fingerprint payments.');
        }
    };

    window.onTransactionBiometricError = (error?: string) => {
        options.onError?.(error || 'Transaction authorization cancelled.');
    };

    window.onBiometricSuccess = (pin?: string) => {
        if (pin && pin.length > 0) {
            options.onSuccess(pin);
        } else {
            options.onError?.('Please enter your 4-digit PIN once to activate fingerprint payments.');
        }
    };

    window.onBiometricError = (error?: string) => {
        options.onError?.(error || 'Transaction authorization cancelled.');
    };

    promptFn.call(
        window.AffanBridge,
        options.title || 'Authorize Payment',
        options.subtitle || 'Scan your fingerprint to approve payment'
    );
}
