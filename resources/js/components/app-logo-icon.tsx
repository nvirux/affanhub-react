import { usePage } from '@inertiajs/react';
import type { HTMLAttributes } from 'react';

export default function AppLogoIcon({ className = 'size-10', ...props }: HTMLAttributes<HTMLDivElement>) {
    const { store, name } = usePage<any>().props;
    const storeName = store?.name || name || 'AffanHub';
    const initial = storeName.charAt(0).toUpperCase() || 'A';

    if (store?.logo_url) {
        return (
            <div className={`flex items-center justify-center overflow-hidden rounded-xl bg-white p-1 shadow-sm border border-gray-100 dark:border-gray-800 ${className}`} {...props}>
                <img src={store.logo_url} alt={storeName} className="h-full w-full object-contain" />
            </div>
        );
    }

    // If tenant has no custom logo uploaded, display the sleek Affan icon
    return (
        <div className={`flex items-center justify-center overflow-hidden rounded-xl ${className}`} {...props}>
            <img src="/affan-icon.png" alt={storeName} className="h-full w-full object-contain" />
        </div>
    );

    // Central Platform (AffanHub)
    return (
        <div className={`flex items-center justify-center overflow-hidden rounded-xl ${className}`} {...props}>
            <img src="/affan-icon.png" alt="AffanHub" className="h-full w-full object-contain" />
        </div>
    );
}
