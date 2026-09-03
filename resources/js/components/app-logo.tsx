import { usePage } from '@inertiajs/react';

import AppLogoIcon from '@/components/app-logo-icon';

export default function AppLogo() {
    const { store, name } = usePage<any>().props;
    const displayName = store?.name || name || 'AffanHub';
    const displayInitial = displayName.charAt(0).toUpperCase();

    return (
        <>
            <div className="flex aspect-square size-8 items-center justify-center rounded bg-sidebar-primary text-sidebar-primary-foreground overflow-hidden">
                {store?.logo_url ? (
                    <img src={store.logo_url} alt={displayName} className="size-full object-contain bg-white" />
                ) : (
                    <span className="font-bold text-sm text-white">{displayInitial}</span>
                )}
            </div>
            <div className="ml-1 grid flex-1 text-left text-sm">
                <span className="mb-0.5 truncate leading-tight font-semibold">
                    {displayName}
                </span>
            </div>
        </>
    );
}
