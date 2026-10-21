/**
 * Biometric / Fingerprint Bridge Utilities for Android WebView.
 * Communicates with native Android via `window.AffanBridge`.
 */

declare global {
    interface Window {
        AffanBridge?: {
            canAuthenticateBiometric?: () => boolean;
            hasSavedBiometricToken?: () => boolean;
            hasSavedTransactionPin?: () => boolean;
            saveBiometricToken?: (token: string) => void;
            saveTransactionPin?: (pin: string) => void;
            clearBiometricToken?: () => void;
            promptBiometric?: (title: string, subtitle?: string) => void;
            promptTransactionBiometric?: (title: string, subtitle?: string) => void;
        };
        isAffanApp?: boolean;
        onBiometricSuccess?: (token?: string) => void;
        onBiometricError?: (message?: string) => void;
        onTransactionBiometricSuccess?: (pin?: string) => void;
        onTransactionBiometricError?: (message?: string) => void;
    }
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
 * Checks whether the user has previously saved a biometric login credential on this device.
 */
export function hasSavedBiometricToken(): boolean {
    if (typeof window === 'undefined') return false;
    try {
        return Boolean(window.AffanBridge?.hasSavedBiometricToken?.());
    } catch {
        return false;
    }
}

/**
 * Checks whether the user has saved their transaction PIN for quick biometric authorization.
 */
export function hasSavedTransactionPin(): boolean {
    if (typeof window === 'undefined') return false;
    try {
        return Boolean(window.AffanBridge?.hasSavedTransactionPin?.());
    } catch {
        return false;
    }
}

/**
 * Launches the native Android BiometricPrompt for Login.
 */
export function requestBiometricLogin(options: {
    title?: string;
    subtitle?: string;
    onSuccess: (token?: string) => void;
    onError?: (error: string) => void;
}): void {
    if (typeof window === 'undefined' || !window.AffanBridge?.promptBiometric) {
        options.onError?.('Biometric authentication is not supported on this device.');
        return;
    }

    window.onBiometricSuccess = (token?: string) => {
        options.onSuccess(token);
    };

    window.onBiometricError = (error?: string) => {
        options.onError?.(error || 'Fingerprint verification failed or was cancelled.');
    };

    window.AffanBridge.promptBiometric(
        options.title || 'Biometric Login',
        options.subtitle || 'Scan your fingerprint to log in'
    );
}

/**
 * Launches the native Android BiometricPrompt for Transaction Authorization.
 */
export function requestBiometricTransaction(options: {
    title?: string;
    subtitle?: string;
    onSuccess: (pin?: string) => void;
    onError?: (error: string) => void;
}): void {
    if (typeof window === 'undefined') {
        options.onError?.('Biometric authentication is not available.');
        return;
    }

    // If native has promptTransactionBiometric, use it; otherwise fallback to promptBiometric
    const promptFn = window.AffanBridge?.promptTransactionBiometric || window.AffanBridge?.promptBiometric;

    if (!promptFn) {
        options.onError?.('Biometric prompt is not available on this device.');
        return;
    }

    window.onTransactionBiometricSuccess = (pin?: string) => {
        options.onSuccess(pin);
    };

    window.onTransactionBiometricError = (error?: string) => {
        options.onError?.(error || 'Transaction authorization cancelled.');
    };

    // Also register onBiometricSuccess fallback if Android uses a single callback
    window.onBiometricSuccess = (tokenOrPin?: string) => {
        options.onSuccess(tokenOrPin);
    };

    window.onBiometricError = (error?: string) => {
        options.onError?.(error || 'Transaction authorization cancelled.');
    };

    promptFn.call(
        window.AffanBridge,
        options.title || 'Authorize Transaction',
        options.subtitle || 'Scan your fingerprint to approve payment'
    );
}

/**
 * Saves a biometric login token in the device's secure Keystore.
 */
export function saveBiometricToken(token: string): void {
    try {
        window.AffanBridge?.saveBiometricToken?.(token);
    } catch (e) {
        console.warn('Failed saving biometric token:', e);
    }
}

/**
 * Saves the user's transaction PIN securely in Keystore for fast biometric approval.
 */
export function saveTransactionPin(pin: string): void {
    try {
        window.AffanBridge?.saveTransactionPin?.(pin);
    } catch (e) {
        console.warn('Failed saving transaction PIN in biometric vault:', e);
    }
}

/**
 * Clears stored biometric credentials on logout or account switch.
 */
export function clearBiometricCredentials(): void {
    try {
        window.AffanBridge?.clearBiometricToken?.();
    } catch (e) {
        console.warn('Failed clearing biometric credentials:', e);
    }
}
