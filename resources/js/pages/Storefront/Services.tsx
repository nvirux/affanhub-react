import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { dashboard } from '@/routes';
import {
    ChevronLeft, Search, Headphones, Smartphone, Wifi, Tv, Zap,
    GraduationCap, RefreshCw, MessageSquare, IdCard, UserCheck,
    Edit3, FileCheck, ShieldCheck, Briefcase
} from 'lucide-react';
import cacIcon from '@/assets/icons/cac.png';
import nimcIcon from '@/assets/icons/nimc.png';
import nibssIcon from '@/assets/icons/nibss.png';
import verificationIcon from '@/assets/icons/verification.png';

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

    const getIconComponent = (iconName: string, category: string) => {
        const cls = "w-6 h-6 sm:w-7 sm:h-7 stroke-[1.8]";

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
            case 'Briefcase': return <Briefcase className={cls} />;
            default:
                return <Smartphone className={cls} />;
        }
    };

    const renderServiceBadge = (svc: ServiceItemData, category: string) => {
        const iconName = svc.icon;
        const key = svc.key || '';

        // CAC Emblem - fill circle edge-to-edge
        if (iconName === 'cac' || key.includes('cac')) {
            return (
                <div className="w-12 h-12 sm:w-14 sm:h-14 rounded-full overflow-hidden flex items-center justify-center mb-1.5 group-active:scale-95 group-hover:scale-105 transition-transform border border-emerald-500/30 shadow-xs bg-emerald-50 dark:bg-emerald-950/40 shrink-0">
                    <img src={cacIcon} alt="CAC" className="w-full h-full object-cover scale-125 select-none" />
                </div>
            );
        }

        // BVN / NIBSS Emblem - fill circle edge-to-edge
        if (iconName === 'nibss' || (category === 'identity' && key.includes('bvn'))) {
            return (
                <div className="w-12 h-12 sm:w-14 sm:h-14 rounded-full overflow-hidden flex items-center justify-center mb-1.5 group-active:scale-95 group-hover:scale-105 transition-transform border border-purple-500/30 shadow-xs bg-purple-50 dark:bg-purple-950/40 shrink-0">
                    <img src={nibssIcon} alt="BVN" className="w-full h-full object-cover scale-130 select-none" />
                </div>
            );
        }

        // Verification Shield Emblem - fill circle edge-to-edge
        if (iconName === 'verification') {
            return (
                <div className="w-12 h-12 sm:w-14 sm:h-14 rounded-full overflow-hidden flex items-center justify-center mb-1.5 group-active:scale-95 group-hover:scale-105 transition-transform border border-amber-500/30 shadow-xs bg-amber-50 dark:bg-amber-950/40 shrink-0">
                    <img src={verificationIcon} alt="Verify" className="w-full h-full object-cover scale-120 select-none" />
                </div>
            );
        }

        // NIN / NIMC Emblem - fill circle edge-to-edge
        if (iconName === 'nimc' || category === 'identity') {
            return (
                <div className="w-12 h-12 sm:w-14 sm:h-14 rounded-full overflow-hidden flex items-center justify-center mb-1.5 group-active:scale-95 group-hover:scale-105 transition-transform border border-blue-500/30 shadow-xs bg-blue-50 dark:bg-blue-950/40 shrink-0">
                    <img src={nimcIcon} alt="NIN" className="w-full h-full object-cover scale-130 select-none" />
                </div>
            );
        }

        // VTU Services - prominent icon container
        return (
            <div className="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center mb-1.5 group-active:scale-95 group-hover:scale-105 transition-transform text-primary bg-primary/5 dark:bg-primary/10 border border-primary/10 dark:border-primary/20 shrink-0">
                {getIconComponent(svc.icon, category)}
            </div>
        );
    };

    const handleServiceClick = (key: string) => {
        if (key === 'data') {
            router.get('/vtu/data');
        } else if (key === 'airtime') {
            router.get('/vtu/airtime');
        } else if (key === 'airtime_cash' || key === 'airtime-to-cash' || key === 'airtime_to_cash') {
            router.get('/vtu/airtime-to-cash');
        } else if (key === 'cable') {
            router.get('/vtu/cable');
        } else if (key === 'electricity') {
            router.get('/vtu/electricity');
        } else if (key === 'education' || key === 'exam_pins') {
            router.get('/vtu/education');
        } else if (key === 'bulk_sms') {
            router.get('/vtu/bulk-sms');
        } else if (key === 'nin_verification' || key === 'nin') {
            router.get('/identity/nin');
        } else if (key === 'bvn_verification' || key === 'bvn') {
            router.get('/identity/bvn');
        } else if (key === 'nin_modification' || key === 'nin-modification' || key === 'modification') {
            router.get('/identity/nin-modification');
        } else if (key === 'bvn_modification' || key === 'bvn-modification') {
            router.get('/identity/bvn-modification');
        } else if (key === 'cac_registration' || key === 'cac' || key === 'cac_services') {
            router.get('/cac');
        } else {
            router.get(`/identity/${key.replace('_verification', '')}`);
        }
    };

    const getDisplayName = (svc: ServiceItemData) => {
        switch (svc.key) {
            case 'airtime': return 'Airtime';
            case 'data': return 'Data';
            case 'cable': return 'Cable TV';
            case 'electricity': return 'Electricity';
            case 'education':
            case 'exam_pins': return 'Exam PINs';
            case 'airtime_cash': return 'Airtime to Cash';
            case 'bulk_sms': return 'Bulk SMS';
            case 'nin_verification': return 'NIN Verify';
            case 'bvn_verification': return 'BVN Verify';
            case 'nin_modification': return 'NIN Mod';
            case 'bvn_modification': return 'BVN Mod';
            case 'cac_registration':
            case 'cac_services': return 'CAC Services';
            default: return svc.name;
        }
    };

    // Default VTU Services fallback list
    const defaultVtuList: ServiceItemData[] = [
        { id: 1, key: 'airtime', name: 'Airtime', icon: 'Smartphone', category: 'vtu', sort_order: 1, is_available: true },
        { id: 2, key: 'data', name: 'Data', icon: 'Wifi', category: 'vtu', sort_order: 2, is_available: true },
        { id: 3, key: 'cable', name: 'Cable TV', icon: 'Tv', category: 'vtu', sort_order: 3, is_available: true },
        { id: 4, key: 'electricity', name: 'Electricity', icon: 'Zap', category: 'vtu', sort_order: 4, is_available: true },
        { id: 5, key: 'education', name: 'Exam PINs', icon: 'GraduationCap', category: 'vtu', sort_order: 5, is_available: true },
        { id: 6, key: 'airtime_cash', name: 'Airtime to Cash', icon: 'RefreshCw', category: 'vtu', sort_order: 6, is_available: true },
        { id: 7, key: 'bulk_sms', name: 'Bulk SMS', icon: 'MessageSquare', category: 'vtu', sort_order: 7, is_available: true },
    ];

    // Default Identity Services fallback list
    const defaultIdentityList: ServiceItemData[] = [
        { id: 8, key: 'nin_verification', name: 'NIN Verify', icon: 'IdCard', category: 'identity', sort_order: 8, is_available: true },
        { id: 9, key: 'bvn_verification', name: 'BVN Verify', icon: 'UserCheck', category: 'identity', sort_order: 9, is_available: true },
        { id: 10, key: 'nin_modification', name: 'NIN Mod', icon: 'Edit3', category: 'identity', sort_order: 10, is_available: true },
        { id: 11, key: 'bvn_modification', name: 'BVN Mod', icon: 'UserCheck', category: 'identity', sort_order: 11, is_available: true },
        { id: 12, key: 'cac_services', name: 'CAC Services', icon: 'Briefcase', category: 'identity', sort_order: 12, is_available: true },
    ];

    const allVtu = services.filter((s) => s.category === 'vtu');
    const allIdentity = services.filter((s) => s.category === 'identity');

    const vtuList = allVtu.length > 0 ? allVtu : defaultVtuList;
    const identityList = allIdentity.length > 0 ? allIdentity : defaultIdentityList;

    const query = searchQuery.trim().toLowerCase();

    const filterList = (list: ServiceItemData[]) => {
        if (!query) return list;
        return list.filter((s) =>
            s.name.toLowerCase().includes(query) ||
            s.key.toLowerCase().includes(query) ||
            getDisplayName(s).toLowerCase().includes(query)
        );
    };

    const vtuFiltered = filterList(vtuList);
    const identityFiltered = filterList(identityList);

    return (
        <>
            <Head title="Services" />

            {/* TOP HEADER */}
            <div className="sticky top-0 z-30 bg-white/95 dark:bg-[#181826]/95 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 py-3.5 px-4 flex items-center justify-between shadow-2xs">
                <div className="flex items-center gap-3 min-w-0">
                    <Link
                        href={dashboard().url}
                        className="p-1.5 rounded-xl text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer shrink-0"
                        title="Back to Dashboard"
                    >
                        <ChevronLeft className="w-5 h-5 stroke-[2.2]" />
                    </Link>
                    <div className="min-w-0">
                        <h1 className="font-extrabold text-base text-gray-900 dark:text-white tracking-tight leading-tight">
                            Services
                        </h1>
                        <p className="text-xs text-slate-400 dark:text-slate-400 font-normal truncate mt-0.5">
                            VTU & Identity Services
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
            <div className="max-w-2xl mx-auto px-4 py-6 w-full space-y-5">

                {/* SEARCH INPUT */}
                <div className="relative">
                    <Search className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <input
                        type="text"
                        value={searchQuery}
                        onChange={(e) => setSearchQuery(e.target.value)}
                        placeholder="Search services..."
                        className="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-[#1e1e2d] border border-gray-200 dark:border-gray-800 rounded-xl text-xs sm:text-sm text-gray-900 dark:text-white placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-xs"
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

                {/* CARD 1: VTU */}
                <div className="bg-white dark:bg-[#1e1e2d] rounded-2xl p-5 shadow-[0_2px_12px_-3px_rgba(0,0,0,0.04)] border border-gray-100 dark:border-gray-800">
                    <div className="flex justify-between items-center mb-4 px-1">
                        <h2 className="text-[15px] sm:text-[16px] font-bold text-gray-900 dark:text-white">VTU</h2>
                    </div>

                    {vtuFiltered.length === 0 ? (
                        <div className="py-6 text-center text-xs text-gray-400 italic">
                            No VTU services found
                        </div>
                    ) : (
                        <div className="grid grid-cols-4 gap-y-5 sm:gap-y-6 gap-x-2 text-center">
                            {vtuFiltered.map((svc) => (
                                <button
                                    key={svc.key || svc.id}
                                    onClick={() => handleServiceClick(svc.key)}
                                    className="flex flex-col items-center group w-full cursor-pointer"
                                >
                                    {renderServiceBadge(svc, 'vtu')}
                                    <span className="text-[11px] sm:text-[12px] font-bold text-gray-800 dark:text-gray-200 leading-tight px-0.5">
                                        {getDisplayName(svc)}
                                    </span>
                                </button>
                            ))}
                        </div>
                    )}
                </div>

                {/* CARD 2: IDENTITY */}
                <div className="bg-white dark:bg-[#1e1e2d] rounded-2xl p-5 shadow-[0_2px_12px_-3px_rgba(0,0,0,0.04)] border border-gray-100 dark:border-gray-800">
                    <div className="flex justify-between items-center mb-4 px-1">
                        <h2 className="text-[15px] sm:text-[16px] font-bold text-gray-900 dark:text-white">Identity</h2>
                    </div>

                    {identityFiltered.length === 0 ? (
                        <div className="py-6 text-center text-xs text-gray-400 italic">
                            No Identity services found
                        </div>
                    ) : (
                        <div className="grid grid-cols-4 gap-y-5 sm:gap-y-6 gap-x-2 text-center">
                            {identityFiltered.map((svc) => (
                                <button
                                    key={svc.key || svc.id}
                                    onClick={() => handleServiceClick(svc.key)}
                                    className="flex flex-col items-center group w-full cursor-pointer"
                                >
                                    {renderServiceBadge(svc, 'identity')}
                                    <span className="text-[11px] sm:text-[12px] font-bold text-gray-800 dark:text-gray-200 leading-tight px-0.5">
                                        {getDisplayName(svc)}
                                    </span>
                                </button>
                            ))}
                        </div>
                    )}
                </div>

            </div>
        </>
    );
}
