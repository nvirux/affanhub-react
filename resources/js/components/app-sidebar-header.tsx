import { Breadcrumbs } from '@/components/breadcrumbs';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItem as BreadcrumbItemType } from '@/types';
import { usePage } from '@inertiajs/react';
import { Search, Bell, ChevronDown } from 'lucide-react';

export function AppSidebarHeader({
    breadcrumbs = [],
}: {
    breadcrumbs?: BreadcrumbItemType[];
}) {
    const { auth } = usePage<any>().props;
    const user = auth?.user;

    const getInitials = (name?: string) => {
        if (!name) return 'UN';
        return name
            .split(' ')
            .map((n) => n[0])
            .join('')
            .slice(0, 2)
            .toUpperCase();
    };

    return (
        <header className="sticky top-0 z-40 hidden md:flex h-16 shrink-0 items-center justify-between gap-4 border-b border-sidebar-border/50 bg-white/80 dark:bg-slate-900/80 backdrop-blur px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
            {/* Left side: Sidebar trigger & breadcrumbs */}
            <div className="flex items-center gap-2">
                <SidebarTrigger className="-ml-1" />
                <Breadcrumbs breadcrumbs={breadcrumbs} />
            </div>

            {/* Middle: Search input (desktop only) */}
            <div className="relative flex-1 max-w-md hidden md:block">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                <input 
                    type="text" 
                    placeholder="Search for services, transactions, or networks..." 
                    className="w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-xs rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-slate-800 dark:text-slate-200"
                />
            </div>

            {/* Right side: Notifications & User Avatar (desktop only) */}
            <div className="hidden md:flex items-center gap-4">
                {/* Notifications */}
                <button className="relative p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                    <Bell className="w-5 h-5" />
                    <span className="absolute top-1 right-1 w-3.5 h-3.5 bg-primary text-white text-[8px] font-bold rounded-full flex items-center justify-center">
                        3
                    </span>
                </button>

                {/* Divider */}
                <div className="h-6 w-px bg-slate-200 dark:bg-slate-800" />

                {/* User Info */}
                <div className="flex items-center gap-3">
                    <div className="text-right hidden sm:block">
                        <span className="text-xs font-bold text-slate-900 dark:text-white block">{user?.name || 'Umar Net Viruz'}</span>
                    </div>
                    <div className="w-8 h-8 rounded-full bg-primary/10 text-primary border border-primary/20 flex items-center justify-center font-bold text-xs uppercase overflow-hidden">
                        {getInitials(user?.name)}
                    </div>
                </div>
            </div>
        </header>
    );
}
