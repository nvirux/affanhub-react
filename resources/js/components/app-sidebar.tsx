import { Link } from '@inertiajs/react';
import {
    LayoutDashboard,
    Gift,
    Smartphone,
    Wifi,
    Tv,
    Zap,
    GraduationCap,
    RefreshCw,
    MessageSquare,
    Fingerprint,
    IdCard,
    FileEdit,
    FileCheck,
    BadgeCheck,
    Code2,
    Terminal,
    Headphones,
    ShieldCheck,
    User,
    Wallet,
    type LucideIcon,
} from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavUser } from '@/components/nav-user';
import { cn } from '@/lib/utils';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/hooks/use-current-url';
import { dashboard } from '@/routes';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';

import type { InertiaLinkProps } from '@inertiajs/react';

interface SidebarNavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon: LucideIcon;
    badge?: string;
    disabled?: boolean;
}

interface NavGroup {
    label: string;
    items: SidebarNavItem[];
}

const navGroups: NavGroup[] = [
    {
        label: 'Platform',
        items: [
            {
                title: 'Dashboard',
                href: dashboard(),
                icon: LayoutDashboard,
            },
            {
                title: 'Wallet & Funding',
                href: '/wallet',
                icon: Wallet,
            },
            {
                title: 'Earn & Refer',
                href: '/earn',
                icon: Gift,
            },
        ],
    },
    {
        label: 'VTU Services',
        items: [
            {
                title: 'Airtime',
                href: '/vtu/airtime',
                icon: Smartphone,
            },
            {
                title: 'Data Bundles',
                href: '/vtu/data',
                icon: Wifi,
            },
            {
                title: 'Cable TV',
                href: '#',
                icon: Tv,
                badge: 'Soon',
                disabled: true,
            },
            {
                title: 'Electricity',
                href: '#',
                icon: Zap,
                badge: 'Soon',
                disabled: true,
            },
            {
                title: 'Exam PINs',
                href: '#',
                icon: GraduationCap,
                badge: 'Soon',
                disabled: true,
            },
            {
                title: 'Airtime to Cash',
                href: '#',
                icon: RefreshCw,
                badge: 'Soon',
                disabled: true,
            },
            {
                title: 'Bulk SMS',
                href: '#',
                icon: MessageSquare,
                badge: 'Soon',
                disabled: true,
            },
        ],
    },
    {
        label: 'Identity Services',
        items: [
            {
                title: 'NIN Verification',
                href: '#',
                icon: Fingerprint,
                badge: 'Soon',
                disabled: true,
            },
            {
                title: 'BVN Verification',
                href: '#',
                icon: IdCard,
                badge: 'Soon',
                disabled: true,
            },
            {
                title: 'NIN Modification',
                href: '#',
                icon: FileEdit,
                badge: 'Soon',
                disabled: true,
            },
            {
                title: 'BVN Modification',
                href: '#',
                icon: BadgeCheck,
                badge: 'Soon',
                disabled: true,
            },
            {
                title: 'IPE Clearance',
                href: '#',
                icon: FileCheck,
                badge: 'Soon',
                disabled: true,
            },
        ],
    },
    {
        label: 'Developer / API',
        items: [
            {
                title: 'API Documentation',
                href: '#',
                icon: Code2,
                badge: 'Soon',
                disabled: true,
            },
            {
                title: 'Keys & Webhooks',
                href: '#',
                icon: Terminal,
                badge: 'Soon',
                disabled: true,
            },
        ],
    },
    {
        label: 'Support & Settings',
        items: [
            {
                title: 'Help & Live Chat',
                href: '/contact',
                icon: Headphones,
            },
            {
                title: 'Security & PIN',
                href: editSecurity(),
                icon: ShieldCheck,
            },
            {
                title: 'My Profile',
                href: editProfile(),
                icon: User,
            },
        ],
    },
];

export function AppSidebar() {
    const { isCurrentUrl } = useCurrentUrl();

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                {navGroups.map((group) => (
                    <SidebarGroup key={group.label} className="px-2 py-1.5">
                        <SidebarGroupLabel className="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            {group.label}
                        </SidebarGroupLabel>
                        <SidebarMenu>
                            {group.items.map((item) => (
                                <SidebarMenuItem key={item.title}>
                                    {item.disabled ? (
                                        <SidebarMenuButton
                                            className="cursor-not-allowed opacity-60 hover:bg-transparent"
                                            tooltip={{ children: `${item.title} (Coming Soon)` }}
                                        >
                                            {item.icon && <item.icon className="w-4 h-4 text-slate-400" />}
                                            <span className="text-slate-600 dark:text-slate-400">{item.title}</span>
                                            {item.badge && (
                                                <span className="ml-auto text-[9px] font-extrabold uppercase tracking-wider px-1.5 py-0.5 rounded bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 group-data-[collapsible=icon]:hidden">
                                                    {item.badge}
                                                </span>
                                            )}
                                        </SidebarMenuButton>
                                    ) : (
                                        <SidebarMenuButton
                                            asChild
                                            isActive={isCurrentUrl(item.href)}
                                            tooltip={{ children: item.title }}
                                        >
                                            <Link href={item.href} prefetch>
                                                {item.icon && <item.icon className="w-4 h-4" />}
                                                <span>{item.title}</span>
                                                {item.badge && (
                                                    <span
                                                        className={cn(
                                                            'ml-auto text-[9px] font-extrabold uppercase tracking-wider px-1.5 py-0.5 rounded border group-data-[collapsible=icon]:hidden',
                                                            isCurrentUrl(item.href)
                                                                ? 'bg-primary-foreground/20 text-primary-foreground border-primary-foreground/30'
                                                                : 'bg-primary/10 text-primary border-primary/20'
                                                        )}
                                                    >
                                                        {item.badge}
                                                    </span>
                                                )}
                                            </Link>
                                        </SidebarMenuButton>
                                    )}
                                </SidebarMenuItem>
                            ))}
                        </SidebarMenu>
                    </SidebarGroup>
                ))}
            </SidebarContent>

            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}

