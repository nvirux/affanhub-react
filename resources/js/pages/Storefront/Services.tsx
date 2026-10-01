import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { dashboard } from '@/routes';
import {
    ChevronLeft, Search, Headphones, Smartphone, Wifi, Tv, Zap,
    GraduationCap, RefreshCw, MessageSquare, IdCard, UserCheck,
    Edit3, FileCheck, ShieldCheck, ArrowRight, Lock, CheckCircle2,
    Layers, Sparkles
} from 'lucide-react';
import { cn } from '@/lib/utils';

export interface ServiceItemData {
    id: number;
    name: string;
    key: string;
    category: 'vtu' | 'identity' | string;
    icon: string;
    description?: string;
    sort_order: number;
    is_available: boolean;
}

interface ServicesPageProps {
    services: ServiceItemData[];
}

export default function ServicesPage({ services = [] }: ServicesPageProps) {
    const [searchQuery, setSearchQuery] = useState('');
    const [selectedCategory, setSelectedCategory] = useState<'all' | 'vtu' | 'identity'>('all');

    const getIconComponent = (iconName: string, category: string) => {
        const cls = "w-6 h-6 stroke-[1.8]";
        switch (iconName) {
            case 'Smartphone': return <Smartphone className={cls} />;
            case 'Wifi': return <Wifi className={cls} />;
            case 'Tv': return <Tv className={cls} />;
            case 'Zap': return <Zap className={cls} />;
            case 'GraduationCap': return <GraduationCap className={cls} />;
            case 'RefreshCw': return <RefreshCw className={cls} />;
            case 'MessageSquare': return <MessageSquare className={cls} />;
            case 'IdCard': return <IdCard className={cls} />;
            case 'UserCheck': return <UserCheck className={cls} />;
            case 'Edit3': return <Edit3 className={cls} />;
            case 'FileCheck': return <FileCheck className={cls} />;
            case 'ShieldCheck': return <ShieldCheck className={cls} />;
            default: return category === 'identity' ? <IdCard className={cls} /> : <Smartphone className={cls} />;
        }
    };

    const handleServiceClick = (service: ServiceItemData) => {
        if (service.key === 'data') {
            router.get('/vtu/data');
        } else if (service.key === 'airtime') {
            router.get('/vtu/airtime');
        } else if (service.key === 'nin_verification' || service.key === 'nin') {
            router.get('/identity/nin');
        } else if (service.key === 'bvn_verification' || service.key === 'bvn') {
            router.get('/identity/bvn');
        } else if (service.key === 'cable') {
            router.get('/vtu/cable');
        } else if (service.key === 'electricity') {
            router.get('/vtu/electricity');
        } else {
            // For other services, route to identity if category is identity, or fallback
            if (service.category === 'identity') {
                router.get(`/identity/${service.key.replace('_verification', '')}`);
            } else {
                router.get(`/vtu/${service.key}`);
            }
        }
    };

    const filteredServices = services.filter((svc) => {
        const matchesCategory = selectedCategory === 'all' || svc.category === selectedCategory;
        const matchesQuery = searchQuery.trim() === '' ||
            svc.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
            (svc.description && svc.description.toLowerCase().includes(searchQuery.toLowerCase()));
        return matchesCategory && matchesQuery;
    });

    const vtuCount = services.filter((s) => s.category === 'vtu').length;
    const identityCount = services.filter((s) => s.category === 'identity').length;

    return (
        <>
            <Head title="All Services" />

            {/* MOBILE VIEW STICKY HEADER (hidden on desktop) */}
            <div className="sticky top-0 z-30 bg-white/95 dark:bg-[#181826]/95 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 py-3.5 px-4 flex md:hidden items-center justify-between shadow-2xs">
                <div className="flex items-center gap-3 min-w-0">
                    <Link
                        href={dashboard().url}
                        className="p-1.5 rounded-xl text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer shrink-0"
                        title="Back to Dashboard"
                    >
                        <ChevronLeft className="w-5 h-5 stroke-[2.2]" />
                    </Link>
                    <div className="min-w-0">
                        <h1 className="font-extrabold text-base text-gray-900 dark:text-white tracking-tight leading-tight flex items-center gap-2">
                            All Services
                            <span className="text-[10px] font-bold px-2 py-0.5 rounded-full bg-primary/10 text-primary">
                                {services.length}
                            </span>
                        </h1>
                        <p className="text-xs text-slate-400 dark:text-slate-400 font-normal truncate mt-0.5">
                            Select a service to get started
                        </p>
                    </div>
                </div>

                <div className="flex items-center gap-2 shrink-0">
                    <Link
                        href="/contact"
                        className="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer"
                        title="Customer Support"
                    >
                        <Headphones className="w-5 h-5 stroke-[1.8]" />
                    </Link>
                </div>
            </div>

            {/* MAIN CONTENT AREA */}
            <div className="max-w-4xl mx-auto px-4 py-6 w-full space-y-5">

                {/* SEARCH & FILTER CONTROLS */}
                <div className="space-y-3">
                    {/* Search Input */}
                    <div className="relative">
                        <Search className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                        <input
                            type="text"
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            placeholder="Search services (e.g. Data, NIN, Airtime, BVN)..."
                            className="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-[#181826] border border-gray-200 dark:border-gray-800 rounded-xl text-xs sm:text-sm text-gray-900 dark:text-white placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-xs"
                        />
                        {searchQuery && (
                            <button
                                onClick={() => setSearchQuery('')}
                                className="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 font-bold"
                            >
                                ✕
                            </button>
                        )}
                    </div>

                    {/* Category Tabs */}
                    <div className="flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar">
                        <button
                            onClick={() => setSelectedCategory('all')}
                            className={cn(
                                "px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all shrink-0 cursor-pointer",
                                selectedCategory === 'all'
                                    ? "bg-primary text-white shadow-xs"
                                    : "bg-gray-100 dark:bg-gray-800/80 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700"
                            )}
                        >
                            All ({services.length})
                        </button>
                        <button
                            onClick={() => setSelectedCategory('vtu')}
                            className={cn(
                                "px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all shrink-0 cursor-pointer flex items-center gap-1.5",
                                selectedCategory === 'vtu'
                                    ? "bg-primary text-white shadow-xs"
                                    : "bg-gray-100 dark:bg-gray-800/80 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700"
                            )}
                        >
                            <Smartphone className="w-3.5 h-3.5" />
                            VTU Utilities ({vtuCount})
                        </button>
                        <button
                            onClick={() => setSelectedCategory('identity')}
                            className={cn(
                                "px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all shrink-0 cursor-pointer flex items-center gap-1.5",
                                selectedCategory === 'identity'
                                    ? "bg-primary text-white shadow-xs"
                                    : "bg-gray-100 dark:bg-gray-800/80 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700"
                            )}
                        >
                            <ShieldCheck className="w-3.5 h-3.5" />
                            Identity Services ({identityCount})
                        </button>
                    </div>
                </div>

                {/* SERVICES GRID */}
                {filteredServices.length === 0 ? (
                    <div className="bg-white dark:bg-[#181826] border border-gray-100 dark:border-gray-800 rounded-2xl p-10 text-center space-y-3">
                        <div className="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-800 text-gray-400 flex items-center justify-center mx-auto">
                            <Layers className="w-6 h-6" />
                        </div>
                        <h3 className="font-bold text-sm text-gray-900 dark:text-white">No services found</h3>
                        <p className="text-xs text-gray-500 dark:text-gray-400 max-w-xs mx-auto">
                            No service matched "{searchQuery}". Try searching with a different term.
                        </p>
                    </div>
                ) : (
                    <div className="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2.5 sm:gap-3.5">
                        {filteredServices.map((service) => {
                            const isId = service.category === 'identity';
                            return (
                                <div
                                    key={service.id || service.key}
                                    onClick={() => handleServiceClick(service)}
                                    className={cn(
                                        "group relative bg-white dark:bg-[#181826] border rounded-2xl p-3 sm:p-4 transition-all duration-200 cursor-pointer flex flex-col justify-between shadow-xs hover:shadow-md hover:-translate-y-0.5 select-none",
                                        service.is_available
                                            ? "border-gray-100 dark:border-gray-800 hover:border-primary/40 dark:hover:border-primary/40"
                                            : "border-gray-100/60 dark:border-gray-800/60 opacity-80"
                                    )}
                                >
                                    <div className="space-y-2 sm:space-y-3">
                                        <div className="flex items-center justify-between">
                                            <div className={cn(
                                                "w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center transition-transform group-hover:scale-105 shrink-0",
                                                isId
                                                    ? "bg-emerald-500/10 text-emerald-600 dark:text-emerald-400"
                                                    : "bg-primary/10 text-primary"
                                            )}>
                                                {getIconComponent(service.icon, service.category)}
                                            </div>

                                            {service.is_available ? (
                                                <span className="inline-flex items-center gap-1 text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                                    <span className="w-1.5 h-1.5 rounded-full bg-emerald-500" />
                                                    <span className="hidden xs:inline">Available</span>
                                                </span>
                                            ) : (
                                                <span className="inline-flex items-center gap-1 text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400">
                                                    <Lock className="w-2.5 h-2.5" />
                                                    <span className="hidden xs:inline">Unavailable</span>
                                                </span>
                                            )}
                                        </div>

                                        <div>
                                            <h3 className="font-extrabold text-xs sm:text-sm text-gray-900 dark:text-white group-hover:text-primary transition-colors flex items-center justify-between gap-1 leading-snug">
                                                <span className="truncate">{service.name}</span>
                                                <ArrowRight className="w-3.5 h-3.5 text-gray-300 dark:text-gray-600 group-hover:text-primary group-hover:translate-x-0.5 transition-all shrink-0 hidden sm:block" />
                                            </h3>
                                            <p className="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2 leading-snug sm:leading-relaxed">
                                                {service.description || (isId ? 'Official verification & slip service' : 'Instant automated telecom service')}
                                            </p>
                                        </div>
                                    </div>

                                    <div className="pt-2 sm:pt-3 mt-2 sm:mt-3 border-t border-gray-100 dark:border-gray-800/60 flex items-center justify-between text-[10px] sm:text-[11px] font-medium text-slate-400">
                                        <span className="capitalize truncate">{service.category === 'vtu' ? 'VTU' : 'Identity'}</span>
                                        <span className="text-primary font-bold group-hover:underline">Open →</span>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                )}

                {/* HELPFUL SUPPORT CALLOUT */}
                <div className="bg-primary/5 dark:bg-primary/10 border border-primary/15 dark:border-primary/20 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div className="space-y-1">
                        <div className="flex items-center gap-2">
                            <Sparkles className="w-4 h-4 text-primary" />
                            <h4 className="font-bold text-xs sm:text-sm text-gray-900 dark:text-white">
                                Need custom service access or API integration?
                            </h4>
                        </div>
                        <p className="text-xs text-slate-500 dark:text-slate-400">
                            Our team is available 24/7 to activate new services or connect your portal to automated APIs.
                        </p>
                    </div>

                    <Link
                        href="/contact"
                        className="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-white text-xs font-bold hover:bg-primary/90 transition-all shrink-0 shadow-xs"
                    >
                        <Headphones className="w-3.5 h-3.5" />
                        Contact Support
                    </Link>
                </div>
            </div>
        </>
    );
}
