import { createInertiaApp } from '@inertiajs/react';
import { Toaster } from '@/components/ui/sonner';
import { TooltipProvider } from '@/components/ui/tooltip';
import { initializeTheme } from '@/hooks/use-appearance';
import AppLayout from '@/layouts/app-layout';
import AuthLayout from '@/layouts/auth-layout';

const defaultAppName = import.meta.env.VITE_APP_NAME || 'AffanHub';

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
            case ['Marketing/Home', 'Storefront/Home'].includes(name):
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
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on load...
initializeTheme();
