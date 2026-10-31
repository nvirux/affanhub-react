import { createInertiaApp, router } from '@inertiajs/react';
import { Toaster } from '@/components/ui/sonner';
import { TooltipProvider } from '@/components/ui/tooltip';
import { initializeTheme } from '@/hooks/use-appearance';
import AppLayout from '@/layouts/app-layout';
import AuthLayout from '@/layouts/auth-layout';

const defaultAppName = import.meta.env.VITE_APP_NAME || 'AffanHub';

// Meta Pixel SPA PageView & Flash Conversion Event tracking
let isInitialVisit = true;
router.on('navigate', (event) => {
    // Prevent duplicate PageView event on the initial full HTML page load
    if (isInitialVisit) {
        isInitialVisit = false;
    } else if (typeof window !== 'undefined' && typeof (window as any).fbq === 'function') {
        (window as any).fbq('track', 'PageView');
    }

    // Check for flashed pixel conversion events (e.g., CompleteRegistration, Subscribe)
    const pageProps = event?.detail?.page?.props as any;
    const pixelEvent = pageProps?.flash?.pixel_event;
    if (pixelEvent && typeof window !== 'undefined' && typeof (window as any).fbq === 'function') {
        (window as any).fbq('track', pixelEvent.event, pixelEvent.params || {});
    }
});

createInertiaApp({
    title: (title) => {
        const appName =
            (typeof document !== 'undefined'
                ? document.querySelector('meta[name="app-name"]')?.getAttribute('content')
                : null) || defaultAppName;
        return title ? `${title} - ${appName}` : appName;
    },
    layout: (name) => {
        switch (true) {
            case name.startsWith('Marketing/'):
            case name.startsWith('Storefront/Themes/'):
            case ['Storefront/PrivacyPolicy', 'Storefront/Contact'].includes(name):
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return AppLayout;
            default:
                return AppLayout;
        }
    },
    strictMode: true,
    withApp(app) {
        return (
            <TooltipProvider delayDuration={0}>
                {app}
                <Toaster />
            </TooltipProvider>
        );
    },
    progress: false,
});

// This will set light / dark mode on load...
initializeTheme();
