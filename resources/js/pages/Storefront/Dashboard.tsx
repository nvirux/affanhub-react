import { useState, useEffect } from 'react';
import { Head, usePage, router } from '@inertiajs/react';
import { dashboard, logout } from '@/routes';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { useAppearance } from '@/hooks/use-appearance';
import {
    Smartphone, Wifi, Tv, Zap, GraduationCap, RefreshCw, MessageSquare,
    Eye, EyeOff, Plus, BarChart3, CheckCircle, Wallet, Gift,
    ChevronDown, Bell, LogOut, Settings, Menu, FileText, Sun, Moon, Monitor, Headphones,
    X, Copy, Check, Building2, ShieldCheck, AlertCircle, CreditCard, Sparkles,
    UserCheck, Fingerprint, Search, Edit3, FileCheck, IdCard
} from 'lucide-react';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetDescription,
} from '@/components/ui/sheet';

export default function Dashboard() {
    const { auth, store, store_services, flash, errors } = usePage<any>().props;
    const user = auth?.user;
    const { appearance, updateAppearance } = useAppearance();
    const [themeMenuOpen, setThemeMenuOpen] = useState(false);
    const [phone, setPhone] = useState(user?.phone || '');
    const [name, setName] = useState(user?.name || '');

    const getIconComponent = (iconName: string, category: string) => {
        const cls = "w-5 h-5 md:w-7 md:h-7 stroke-[1.75]";
        switch(iconName) {
            case 'Smartphone': return <Smartphone className={cls} />;
            case 'Wifi': return <Wifi className={cls} />;
            case 'Tv': return <Tv className={cls} />;
            case 'Zap': return <Zap className={cls} />;
            case 'GraduationCap': return <GraduationCap className={cls} />;
            case 'RefreshCw': return <RefreshCw className={cls} />;
            case 'MessageSquare': return <MessageSquare className={cls} />;
            case 'IdCard': return <IdCard className={cls} />;
            case 'UserCheck': return <UserCheck className={cls} />;
            case 'Search': return <Search className={cls} />;
            case 'Edit3': return <Edit3 className={cls} />;
            case 'FileCheck': return <FileCheck className={cls} />;
            case 'ShieldCheck': return <ShieldCheck className={cls} />;
            default: return category === 'identity' ? <IdCard className={cls} /> : <Smartphone className={cls} />;
        }
    };

    const getColorClass = (key: string, category: string) => {
        return 'text-primary bg-primary/5 border-gray-100 dark:border-gray-700';
    };

    // Fund Wallet Modal States
    const [fundModalOpen, setFundModalOpen] = useState(false);
    const [kycType, setKycType] = useState<'bvn' | 'nin'>('nin');
    const [kycNumber, setKycNumber] = useState(user?.nin || '');
    const [isGenerating, setIsGenerating] = useState(false);
    const [copySuccess, setCopySuccess] = useState(false);

    useEffect(() => {
        if (kycType === 'nin' && user?.nin) {
            setKycNumber(user.nin);
        } else if (kycType === 'bvn' && user?.bvn) {
            setKycNumber(user.bvn);
        }
    }, [kycType, user?.nin, user?.bvn]);

    const handleCopyAccount = (text: string) => {
        navigator.clipboard.writeText(text);
        setCopySuccess(true);
        setTimeout(() => setCopySuccess(false), 2000);
    };

    const handleGenerateAccount = (e: React.FormEvent) => {
        e.preventDefault();
        if (kycNumber.length !== 11) {
            alert(`Please enter a valid 11-digit ${kycType.toUpperCase()}`);
            return;
        }
        setIsGenerating(true);
        router.post('/virtual-account/generate', {
            type: kycType,
            number: kycNumber,
            phone: phone || user?.phone,
            name: name || user?.name,
        }, {
            onFinish: () => setIsGenerating(false),
            onSuccess: () => {
                setKycNumber('');
            }
        });
    };

    const handleServiceClick = (key: string) => {
        if (key === 'data') {
            router.get('/vtu/data');
        }
    };

    // Dynamic merchant dashboard tagline fallback
    const rawTagline = store?.dashboard_subtitle || store?.tagline || 'Fast & Reliable Telecom Services';
    const merchantTagline = rawTagline.length > 32 ? `${rawTagline.slice(0, 32)}...` : rawTagline;

    // Balance visibility toggle state synced with localStorage (safe for SSR hydration)
    const [showBalance, setShowBalance] = useState(true);

    useEffect(() => {
        if (typeof window !== 'undefined') {
            const hidden = localStorage.getItem('hideBalance') === 'true';
            if (hidden) {
                setShowBalance(false);
            }
        }
    }, []);

    const toggleBalance = () => {
        setShowBalance(prev => {
            const next = !prev;
            localStorage.setItem('hideBalance', String(!next));
            return next;
        });
    };

    // User Dropdown State for Mobile view
    const [userMenuOpen, setUserMenuOpen] = useState(false);

    // Mock data representing recent transactions
    const transactions = []; // Start empty to match the screenshot "No transactions found yet."

    // Computed user initials
    const getInitials = (name?: string) => {
        if (!name) return 'UN';
        return name
            .split(' ')
            .map((n) => n[0])
            .join('')
            .slice(0, 2)
            .toUpperCase();
    };

    const handleLogout = () => {
        router.post(logout().url);
    };

    return (
        <>
            <Head title="Customer Dashboard" />
            
            {/* ────────────────────────────────────────────────────────
                DESKTOP VIEW (hidden md:flex)
                ──────────────────────────────────────────────────────── */}
            <div className="hidden md:flex h-full w-full flex-1 flex-col gap-6 font-sans p-6 max-w-7xl mx-auto">
                
                {/* Header & Wallet Section */}
                <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h1 className="text-[22px] font-bold text-gray-900 dark:text-white tracking-tight">Dashboard</h1>
                        <p className="text-[13px] text-gray-500 dark:text-gray-400 mt-0.5">Overview of your {store?.name || 'VTULab'} account</p>
                    </div>
                    
                    <div className="flex items-center gap-4 bg-white dark:bg-[#1e1e2d] px-4 py-2.5 rounded-[1.25rem] border border-gray-100 dark:border-gray-800 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                        <div className="flex flex-col pr-4 border-r border-gray-100 dark:border-gray-700">
                            <div className="flex items-center gap-1.5 cursor-pointer select-none" onClick={toggleBalance}>
                                <span className="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Wallet Balance</span>
                                {showBalance ? (
                                    <Eye className="w-3.5 h-3.5 text-gray-400 hover:text-gray-600 transition-colors" />
                                ) : (
                                    <EyeOff className="w-3.5 h-3.5 text-gray-400 hover:text-gray-600 transition-colors" />
                                )}
                            </div>
                            <span className="text-[22px] font-extrabold text-gray-900 dark:text-white mt-0.5 leading-none">
                                {showBalance ? (user?.wallet_balance !== undefined ? `₦${Number(user.wallet_balance).toLocaleString('en-NG', { minimumFractionDigits: 2 })}` : '₦0.00') : '••••••••'}
                            </span>
                        </div>
                        <div className="pl-1">
                            <button onClick={() => setFundModalOpen(true)} className="px-5 py-2.5 rounded-xl bg-primary text-white text-[13px] font-bold flex items-center gap-1.5 shadow-md shadow-primary/20 hover:opacity-90 active:scale-95 transition-all cursor-pointer">
                                <span>Fund Wallet</span>
                                <Plus className="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>

                {/* Real Stats Row */}
                <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
                    {/* Stat 1 */}
                    <div className="bg-white dark:bg-[#1e1e2d] p-4 md:p-5 rounded-[1.25rem] border border-gray-100 dark:border-gray-800 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] flex flex-col md:flex-row justify-between items-start md:items-center gap-2 md:gap-0">
                        <div>
                            <span className="text-[11px] md:text-[12px] font-semibold text-gray-500 dark:text-gray-400">Total Transactions</span>
                            <h3 className="text-[18px] md:text-[22px] font-bold text-gray-900 dark:text-white mt-1 md:mb-2">0</h3>
                            <div className="flex items-center gap-1 text-[10px] md:text-[11px] mt-1 md:mt-0">
                                <span className="text-gray-400">Lifetime total</span>
                            </div>
                        </div>
                        <div className="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary self-end md:self-auto hidden sm:flex">
                            <BarChart3 className="w-5 h-5 md:w-6 md:h-6" />
                        </div>
                    </div>
                    
                    {/* Stat 2 */}
                    <div className="bg-white dark:bg-[#1e1e2d] p-4 md:p-5 rounded-[1.25rem] border border-gray-100 dark:border-gray-800 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] flex flex-col md:flex-row justify-between items-start md:items-center gap-2 md:gap-0">
                        <div>
                            <span className="text-[11px] md:text-[12px] font-semibold text-gray-500 dark:text-gray-400 leading-tight">Successful Txns</span>
                            <h3 className="text-[18px] md:text-[22px] font-bold text-gray-900 dark:text-white mt-1 md:mb-2">0</h3>
                            <div className="flex items-center gap-1 text-[10px] md:text-[11px] mt-1 md:mt-0">
                                <span className="text-emerald-500 font-bold">100%</span>
                                <span className="text-gray-400 hidden md:inline ml-1">success rate</span>
                            </div>
                        </div>
                        <div className="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-500 self-end md:self-auto hidden sm:flex">
                            <CheckCircle className="w-5 h-5 md:w-6 md:h-6" />
                        </div>
                    </div>
                    
                    {/* Stat 3 */}
                    <div className="bg-white dark:bg-[#1e1e2d] p-4 md:p-5 rounded-[1.25rem] border border-gray-100 dark:border-gray-800 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] flex flex-col md:flex-row justify-between items-start md:items-center gap-2 md:gap-0">
                        <div>
                            <span className="text-[11px] md:text-[12px] font-semibold text-gray-500 dark:text-gray-400">Total Spent</span>
                            <h3 className="text-[18px] md:text-[22px] font-bold text-gray-900 dark:text-white mt-1 md:mb-2 leading-none">₦0.00</h3>
                            <div className="flex items-center gap-1 text-[10px] md:text-[11px] mt-1 md:mt-0">
                                <span className="text-gray-400 font-medium">Successful orders</span>
                            </div>
                        </div>
                        <div className="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-500 self-end md:self-auto hidden sm:flex">
                            <Wallet className="w-5 h-5 md:w-6 md:h-6" />
                        </div>
                    </div>
                    
                    {/* Stat 4 */}
                    <div className="bg-white dark:bg-[#1e1e2d] p-4 md:p-5 rounded-[1.25rem] border border-gray-100 dark:border-gray-800 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] flex flex-col md:flex-row justify-between items-start md:items-center gap-2 md:gap-0">
                        <div>
                            <span className="text-[11px] md:text-[12px] font-semibold text-gray-500 dark:text-gray-400">Cashback Saved</span>
                            <h3 className="text-[18px] md:text-[22px] font-bold text-gray-900 dark:text-white mt-1 md:mb-2 leading-none">₦0.00</h3>
                            <div className="flex items-center gap-1 text-[10px] md:text-[11px] mt-1 md:mt-0">
                                <span className="text-emerald-500 font-bold">User Discount</span>
                            </div>
                        </div>
                        <div className="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary self-end md:self-auto hidden sm:flex">
                            <Gift className="w-5 h-5 md:w-6 md:h-6" />
                        </div>
                    </div>
                </div>

                {/* Main Content Grid */}
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    
                    {/* Left Column: Services & API Banner */}
                    <div className="lg:col-span-7 flex flex-col gap-6">
                        {/* Quick Services (Single Customizable Card) */}
                        <div className="bg-white dark:bg-[#1e1e2d] rounded-[1.25rem] border border-gray-100 dark:border-gray-800 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-6">
                            <div className="flex justify-between items-center mb-5">
                                <h2 className="text-[16px] font-bold text-gray-900 dark:text-white">Quick Services</h2>
                                <button className="text-[13px] font-bold text-primary hover:opacity-80 cursor-pointer">View all</button>
                            </div>
                            
                            <div className="grid grid-cols-4 gap-2 md:gap-4">
                                {((store_services && store_services.length > 0) ? store_services.slice(0, 7) : [
                                    { key: 'airtime', name: 'Airtime', description: 'Top up airtime', icon: 'Smartphone', category: 'vtu' },
                                    { key: 'data', name: 'Data', description: 'Buy data plans', icon: 'Wifi', category: 'vtu' },
                                    { key: 'cable', name: 'Cable TV', description: 'Pay for TV', icon: 'Tv', category: 'vtu' },
                                    { key: 'electricity', name: 'Electricity', description: 'Buy electricity', icon: 'Zap', category: 'vtu' },
                                    { key: 'exam_pins', name: 'Exam PINs', description: 'Buy exam pins', icon: 'GraduationCap', category: 'vtu' },
                                    { key: 'airtime_cash', name: 'Airtime to Cash', description: 'Convert to cash', icon: 'RefreshCw', category: 'vtu' },
                                    { key: 'bulk_sms', name: 'Bulk SMS', description: 'Send messages', icon: 'MessageSquare', category: 'vtu' },
                                ]).map((svc: any) => (
                                    <ServiceItem 
                                        key={svc.key || svc.id}
                                        icon={getIconComponent(svc.icon, svc.category)} 
                                        title={svc.name} 
                                        subtitle={svc.description || 'Quick service'} 
                                        color={getColorClass(svc.key, svc.category)} 
                                        onClick={() => handleServiceClick(svc.key)}
                                    />
                                ))}
                                
                                <button className="group flex flex-col items-center p-2 md:p-4 rounded-[1rem] md:rounded-2xl bg-primary/5 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-100 dark:border-gray-700 transition-all duration-300 w-full text-center cursor-pointer">
                                    <div className="w-10 h-10 md:w-12 md:h-12 mb-2 md:mb-3 text-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <div className="grid grid-cols-2 gap-1 w-5 h-5 md:w-6 md:h-6 text-primary group-hover:text-gray-600 transition-colors">
                                            <div className="bg-current rounded-full"></div><div className="bg-current rounded-full"></div>
                                            <div className="bg-current rounded-full"></div><div className="bg-current rounded-full"></div>
                                        </div>
                                    </div>
                                    <span className="text-[9px] md:text-[13px] font-bold text-gray-900 dark:text-gray-100 text-center leading-tight">More</span>
                                    <span className="text-[8px] md:text-[10px] text-gray-400 mt-0.5 md:mt-1 text-center font-medium hidden sm:block">View all</span>
                                </button>
                            </div>
                        </div>

                        {/* API Promo Banner */}
                        <div className="relative overflow-hidden rounded-2xl bg-primary/5 dark:bg-[#1e1e2d] border border-primary/20 dark:border-gray-800 p-6 sm:p-8 flex items-center justify-between shadow-[0_2px_12px_-4px_rgba(98,54,255,0.06)] select-none">
                            <div className="relative z-10 max-w-md select-none">
                                <h2 className="text-[20px] sm:text-[22px] font-extrabold text-gray-900 dark:text-white mb-2 leading-snug tracking-tight select-none">
                                    Power your business<br />with {store?.name || 'VTULab'} APIs
                                </h2>
                                <p className="text-gray-500 dark:text-gray-400 text-[13px] mb-6 leading-relaxed max-w-xs font-normal select-none">
                                    Reliable, fast and developer-friendly APIs for all your automation needs.
                                </p>
                                <button className="text-[13px] font-bold py-2.5 px-5 rounded-xl bg-primary text-white shadow-md shadow-primary/25 hover:opacity-90 active:scale-95 transition-all">
                                    Explore API Docs
                                </button>
                            </div>

                            {/* 3D Animated API Illustration SVG */}
                            <div className="hidden sm:flex items-center justify-center shrink-0 w-60 sm:w-72 h-44 relative select-none pointer-events-none">
                                <svg viewBox="0 0 340 220" fill="none" xmlns="http://www.w3.org/2000/svg" className="w-full h-full filter drop-shadow-xl select-none">
                                    <defs>
                                        <linearGradient id="topFaceGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stopColor="#6236FF" />
                                            <stop offset="100%" stopColor="#5125E8" />
                                        </linearGradient>
                                        <linearGradient id="leftFaceGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" stopColor="#5125E8" />
                                            <stop offset="100%" stopColor="#3730a3" />
                                        </linearGradient>
                                        <linearGradient id="rightFaceGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stopColor="#4338ca" />
                                            <stop offset="100%" stopColor="#1e1b4b" />
                                        </linearGradient>
                                        <linearGradient id="bubbleGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stopColor="#6236FF" />
                                            <stop offset="100%" stopColor="#5125E8" />
                                        </linearGradient>
                                        <radialGradient id="ambientGlow" cx="50%" cy="50%" r="50%">
                                            <stop offset="0%" stopColor="#6236FF" stopOpacity="0.35" />
                                            <stop offset="100%" stopColor="#6236FF" stopOpacity="0" />
                                        </radialGradient>
                                        
                                        <style dangerouslySetInnerHTML={{__html: `
                                            @keyframes floatCentral { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-8px); } }
                                            @keyframes floatTag1 { 0%, 100% { transform: translateY(0px) scale(1); } 50% { transform: translateY(-10px) scale(1.04); } }
                                            @keyframes floatTag2 { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-6px); } }
                                            @keyframes gridPulse { 0%, 100% { opacity: 0.25; stroke-dashoffset: 24; } 50% { opacity: 0.8; stroke-dashoffset: 0; } }
                                            @keyframes portGlow { 0%, 100% { opacity: 0.4; filter: drop-shadow(0 0 1px #a78bfa); } 50% { opacity: 1; filter: drop-shadow(0 0 4px #c4b5fd); } }
                                            .anim-central { animation: floatCentral 4s ease-in-out infinite; }
                                            .anim-tag1 { animation: floatTag1 3.2s ease-in-out infinite; }
                                            .anim-tag2 { animation: floatTag2 4.5s ease-in-out infinite; }
                                            .anim-grid { stroke-dasharray: 6 6; animation: gridPulse 3s ease-in-out infinite; }
                                            .anim-port-1 { animation: portGlow 1.8s ease-in-out infinite; }
                                            .anim-port-2 { animation: portGlow 1.8s ease-in-out 0.9s infinite; }
                                            text, path, rect { user-select: none; -webkit-user-select: none; }
                                        `}} />
                                    </defs>

                                    <circle cx="170" cy="110" r="90" fill="url(#ambientGlow)" />
                                    <path d="M170 40 L285 100 L170 160 L55 100 Z" stroke="#6236FF" strokeWidth="1.5" className="anim-grid" fill="none" />
                                    <path d="M170 60 L255 105 L170 150 L85 105 Z" stroke="#818cf8" strokeWidth="1" opacity="0.3" fill="none" />

                                    <g className="anim-central">
                                        <ellipse cx="170" cy="152" rx="65" ry="18" fill="#1e1b4b" opacity="0.4" filter="blur(4px)" />
                                        <path d="M170 65 L235 98 L170 131 L105 98 Z" fill="url(#topFaceGrad)" stroke="#a78bfa" strokeWidth="0.75" />
                                        <path d="M105 98 L170 131 L170 156 L105 123 Z" fill="url(#leftFaceGrad)" />
                                        <path d="M170 131 L235 98 L235 123 L170 156 Z" fill="url(#rightFaceGrad)" />

                                        <g transform="rotate(-15 170 105) skewX(24)">
                                            <text x="170" y="108" fontFamily="system-ui, -apple-system, sans-serif" fontWeight="900" fontSize="24" fill="#1e1b4b" opacity="0.3" textAnchor="middle">API</text>
                                            <text x="169" y="107" fontFamily="system-ui, -apple-system, sans-serif" fontWeight="900" fontSize="24" fill="#ffffff" textAnchor="middle">API</text>
                                        </g>

                                        <rect x="118" y="108" width="8" height="4" rx="2" fill="#a78bfa" className="anim-port-1" transform="rotate(22 118 108)" />
                                        <rect x="132" y="115" width="8" height="4" rx="2" fill="#38bdf8" className="anim-port-2" transform="rotate(22 132 115)" />
                                        <rect x="146" y="122" width="8" height="4" rx="2" fill="#34d399" className="anim-port-1" transform="rotate(22 146 122)" />

                                        <rect x="194" y="122" width="8" height="4" rx="2" fill="#34d399" className="anim-port-2" transform="rotate(-22 194 122)" />
                                        <rect x="208" y="115" width="8" height="4" rx="2" fill="#a78bfa" className="anim-port-1" transform="rotate(-22 208 115)" />
                                        <rect x="222" y="108" width="8" height="4" rx="2" fill="#a78bfa" className="anim-port-2" transform="rotate(-22 222 108)" />
                                    </g>

                                    <g className="anim-tag1">
                                        <rect x="245" y="42" width="56" height="32" rx="10" fill="url(#bubbleGrad)" stroke="#c4b5fd" strokeWidth="1.5" />
                                        <text x="273" y="63" fontFamily="monospace" fontWeight="900" fontSize="14" fill="#ffffff" textAnchor="middle">&lt;/&gt;</text>
                                    </g>

                                    <g className="anim-tag2">
                                        <rect x="36" y="55" width="80" height="28" rx="8" fill="#1e1b4b" opacity="0.95" stroke="#6236FF" strokeWidth="1.5" />
                                        <path d="M51 61 L45 68 H50 L48 75 L56 67 H51 L53 61 Z" fill="#f59e0b" stroke="#f59e0b" strokeWidth="0.5" strokeLinejoin="round" />
                                        <text x="78" y="73" fontFamily="system-ui, sans-serif" fontWeight="800" fontSize="11" fill="#ffffff" textAnchor="middle">FAST</text>
                                    </g>

                                    <g className="anim-tag1">
                                        <path d="M70 140 L105 156 L70 172 L35 156 Z" fill="#ffffff" opacity="0.95" />
                                        <path d="M35 156 L70 172 L70 180 L35 164 Z" fill="#cbd5e1" />
                                        <path d="M70 172 L105 156 L105 164 L70 180 Z" fill="#94a3b8" />
                                        <path d="M62 153 C62 149 66 147 70 149 C74 146 79 149 78 154 C81 155 80 159 76 159 L62 159 C59 159 58 155 62 153 Z" fill="#6236FF" />
                                    </g>

                                    <g className="anim-tag2">
                                        <rect x="230" y="145" width="85" height="26" rx="8" fill="#064e3b" stroke="#10b981" strokeWidth="1.5" />
                                        <path d="M241 158 L244 161 L250 153" stroke="#34d399" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" />
                                        <text x="278" y="162" fontFamily="system-ui, sans-serif" fontWeight="800" fontSize="10" fill="#34d399" textAnchor="middle">99.9% UP</text>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {/* Right Column: Real Recent Transactions */}
                    <div className="lg:col-span-5 bg-white dark:bg-[#1e1e2d] rounded-[1.25rem] border border-gray-100 dark:border-gray-800 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-6 flex flex-col h-full">
                        <div className="flex justify-between items-center mb-6">
                            <h2 className="text-[16px] font-bold text-gray-900 dark:text-white">Recent Transactions</h2>
                            <button className="text-[13px] font-bold text-primary hover:opacity-85">View all</button>
                        </div>
                        
                        <div className="flex flex-col gap-4 flex-1">
                            {transactions.length === 0 ? (
                                <div className="flex flex-col items-center justify-center py-10 text-gray-400">
                                    <FileText className="w-10 h-10 mb-2 opacity-40" />
                                    <p className="text-xs font-semibold">No transactions found yet.</p>
                                </div>
                            ) : (
                                <div className="divide-y divide-slate-100 dark:divide-slate-800">
                                    {/* Map transactions here if loaded */}
                                </div>
                            )}
                        </div>
                    </div>

                </div>
            </div>

            {/* ────────────────────────────────────────────────────────
                MOBILE VIEW (block md:hidden)
                ──────────────────────────────────────────────────────── */}
            <div className="block md:hidden font-sans bg-[#f8f7fc] dark:bg-zinc-900 min-h-screen pb-24 w-full overflow-x-clip relative">
                
                {/* Sticky Top Header Navigation */}
                <div className="sticky top-0 z-30 bg-primary dark:bg-primary/95 backdrop-blur-md text-white pt-3 pb-3 px-4 shadow-sm">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center gap-2.5">
                            <div className="w-8 h-8 rounded-full bg-white/20 border border-white/40 text-white font-bold text-[12px] flex items-center justify-center overflow-hidden shrink-0">
                                {getInitials(user?.name)}
                            </div>
                            <div className="flex flex-col text-left leading-none">
                                <span className="font-bold text-[13.5px] text-white tracking-tight">Hello, {user?.name?.split(' ')[0] || 'User'} 👋</span>
                                <span className="text-[9px] text-white/85 mt-1 font-normal line-clamp-1 max-w-[150px] leading-tight" title={rawTagline}>
                                    {merchantTagline}
                                </span>
                            </div>
                        </div>

                        <div className="flex items-center gap-1">
                            {/* Theme switcher Popover */}
                            <div className="relative">
                                <button 
                                    onClick={() => setThemeMenuOpen(!themeMenuOpen)} 
                                    className="text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors flex items-center justify-center focus:outline-none cursor-pointer" 
                                    title={`Theme: ${appearance}`}
                                >
                                    {appearance === 'light' ? (
                                        <Sun className="w-5 h-5" />
                                    ) : appearance === 'dark' ? (
                                        <Moon className="w-5 h-5" />
                                    ) : (
                                        <Monitor className="w-5 h-5" />
                                    )}
                                </button>

                                {themeMenuOpen && (
                                    <>
                                        {/* Click-away backdrop */}
                                        <div className="fixed inset-0 z-40" onClick={() => setThemeMenuOpen(false)} />
                                        
                                        <div className="absolute right-0 mt-2 w-32 rounded-xl shadow-xl bg-white dark:bg-[#1c1c24] border border-gray-100 dark:border-gray-800 py-1.5 z-50 text-slate-700 dark:text-slate-200">
                                            <button 
                                                onClick={() => { updateAppearance('light'); setThemeMenuOpen(false); }}
                                                className={`flex items-center gap-2 w-full text-left px-3 py-1.5 text-xs hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors ${appearance === 'light' ? 'text-primary font-bold bg-primary/5 dark:bg-primary/10' : ''}`}
                                            >
                                                <Sun className="w-3.5 h-3.5" /> Light
                                            </button>
                                            <button 
                                                onClick={() => { updateAppearance('dark'); setThemeMenuOpen(false); }}
                                                className={`flex items-center gap-2 w-full text-left px-3 py-1.5 text-xs hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors ${appearance === 'dark' ? 'text-primary font-bold bg-primary/5 dark:bg-primary/10' : ''}`}
                                            >
                                                <Moon className="w-3.5 h-3.5" /> Dark
                                            </button>
                                            <button 
                                                onClick={() => { updateAppearance('system'); setThemeMenuOpen(false); }}
                                                className={`flex items-center gap-2 w-full text-left px-3 py-1.5 text-xs hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors ${appearance === 'system' ? 'text-primary font-bold bg-primary/5 dark:bg-primary/10' : ''}`}
                                            >
                                                <Monitor className="w-3.5 h-3.5" /> System
                                            </button>
                                        </div>
                                    </>
                                )}
                            </div>

                            {/* Support link */}
                            <a href="#" className="text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors" title="Customer Support">
                                <Headphones className="w-5 h-5" />
                            </a>

                            {/* Notifications */}
                            <div className="relative cursor-pointer p-1.5 text-white hover:bg-white/10 rounded-lg transition-colors">
                                <Bell className="w-5 h-5 stroke-[1.8]" />
                                <span className="absolute top-1 right-1 flex h-2.5 w-2.5 items-center justify-center rounded-full bg-rose-500 text-[8px] font-bold text-white shadow-sm ring-1 ring-primary">3</span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Hero Bottom Accent Curve */}
                <div className="bg-primary dark:bg-primary/90 h-6 rounded-b-xl shadow-xs"></div>

                {/* Floating Wallet Card */}
                <div className="px-4 -mt-5 relative z-10 mb-5">
                    <div className="bg-white dark:bg-[#1e1e2d] rounded-xl p-4 sm:p-5 shadow-[0_10px_25px_-5px_rgba(98,54,255,0.12),0_4px_10px_-2px_rgba(0,0,0,0.04)] border border-gray-100/80 dark:border-gray-800 flex justify-between items-center">
                        <div className="text-left">
                            <div className="flex items-center gap-1.5 text-gray-500 dark:text-gray-400 text-[12px] font-medium cursor-pointer select-none" onClick={toggleBalance}>
                                <span>Wallet Balance</span>
                                {showBalance ? (
                                    <Eye className="w-4 h-4 text-gray-400 hover:text-gray-600" />
                                ) : (
                                    <EyeOff className="w-4 h-4 text-gray-400 hover:text-gray-600" />
                                )}
                            </div>
                            <div className="text-[22px] font-extrabold text-gray-900 dark:text-white mt-1 tracking-tight leading-none">
                                {showBalance ? (user?.wallet_balance !== undefined ? `₦${Number(user.wallet_balance).toLocaleString('en-NG', { minimumFractionDigits: 2 })}` : '₦0.00') : '••••••••'}
                            </div>
                        </div>
                        <div>
                            <button onClick={() => setFundModalOpen(true)} className="px-3.5 py-2 rounded-xl bg-primary text-white text-[11.5px] font-bold flex items-center gap-1 shadow-sm shadow-primary/20 hover:opacity-90 active:scale-95 transition-all cursor-pointer">
                                <span>Fund Wallet</span>
                                <Plus className="w-3.5 h-3.5" />
                            </button>
                        </div>
                    </div>
                </div>

                {/* Quick Actions Section (Single Customizable Grid) */}
                <div className="px-4 mb-6">
                    <div className="bg-white dark:bg-[#1e1e2d] rounded-xl p-4 shadow-[0_2px_12px_-3px_rgba(0,0,0,0.04)] border border-gray-100 dark:border-gray-800">
                        <div className="flex justify-between items-center mb-4 px-1">
                            <h2 className="text-[15px] font-bold text-gray-900 dark:text-white">Quick Actions</h2>
                            <button className="text-[12px] font-bold text-primary hover:underline">See all</button>
                        </div>

                        <div className="grid grid-cols-4 gap-y-5 gap-x-2 text-center">
                            {((store_services && store_services.length > 0) ? store_services.slice(0, 7) : [
                                { key: 'airtime', name: 'Airtime', icon: 'Smartphone', category: 'vtu' },
                                { key: 'data', name: 'Data', icon: 'Wifi', category: 'vtu' },
                                { key: 'cable', name: 'Cable TV', icon: 'Tv', category: 'vtu' },
                                { key: 'electricity', name: 'Electricity', icon: 'Zap', category: 'vtu' },
                                { key: 'education', name: 'Education', icon: 'GraduationCap', category: 'vtu' },
                                { key: 'airtime_cash', name: 'Airtime to Cash', icon: 'RefreshCw', category: 'vtu' },
                                { key: 'bulk_sms', name: 'Bulk SMS', icon: 'MessageSquare', category: 'vtu' },
                            ]).map((svc: any) => {
                                const displayName = svc.key === 'airtime' ? 'Airtime'
                                    : svc.key === 'data' ? 'Data'
                                    : svc.key === 'electricity' ? 'Electricity'
                                    : (svc.key === 'education' || svc.key === 'exam_pins') ? 'Education'
                                    : svc.key === 'nin_verification' ? 'NIN Verify'
                                    : svc.key === 'bvn_verification' ? 'BVN Verify'
                                    : svc.name;
                                return (
                                    <button key={svc.key || svc.id} onClick={() => handleServiceClick(svc.key)} className="flex flex-col items-center group w-full cursor-pointer">
                                        <div className={`w-12 h-12 rounded-2xl flex items-center justify-center mb-1.5 group-active:scale-95 transition-transform ${getColorClass(svc.key, svc.category).split(' ')[0]} ${getColorClass(svc.key, svc.category).split(' ')[1]}`}>
                                            {getIconComponent(svc.icon, svc.category)}
                                        </div>
                                        <span className="text-[11px] font-bold text-gray-800 dark:text-gray-200 leading-tight px-0.5">{displayName}</span>
                                    </button>
                                );
                            })}

                            {/* More Services Link (8th Slot) */}
                            <button className="flex flex-col items-center group w-full cursor-pointer">
                                <div className="w-12 h-12 rounded-2xl bg-primary/5 dark:bg-primary/10 text-primary flex items-center justify-center mb-1.5 group-active:scale-95 transition-transform">
                                    <div className="grid grid-cols-2 gap-0.5 w-4 h-4 text-primary">
                                        <div className="bg-current rounded-full"></div><div className="bg-current rounded-full"></div>
                                        <div className="bg-current rounded-full"></div><div className="bg-current rounded-full"></div>
                                    </div>
                                </div>
                                <span className="text-[11px] font-bold text-gray-800 dark:text-gray-200 leading-tight">More</span>
                            </button>
                        </div>
                    </div>
                </div>

                {/* Real Recent Transactions Section (Mobile) */}
                <div className="px-4 mb-8">
                    <div className="bg-white dark:bg-[#1e1e2d] rounded-xl p-4 shadow-[0_2px_12px_-3px_rgba(0,0,0,0.04)] border border-gray-100 dark:border-gray-800">
                        <div className="flex justify-between items-center mb-4 px-1">
                            <h2 className="text-[15px] font-bold text-gray-900 dark:text-white">Recent Transactions</h2>
                            <button className="text-[12px] font-bold text-primary hover:underline">See all</button>
                        </div>

                        <div className="flex flex-col gap-3">
                            {transactions.length === 0 ? (
                                <div className="flex flex-col items-center justify-center py-8 text-gray-400">
                                    <FileText className="w-9 h-9 mb-1.5 opacity-40" />
                                    <p className="text-xs font-semibold">No transactions found yet.</p>
                                </div>
                            ) : (
                                <div className="flex flex-col gap-3">
                                    {/* Map transactions here if loaded */}
                                </div>
                            )}
                        </div>
                    </div>
                </div>

            </div>

            {/* ── FUND WALLET SHEET ── */}
            <Sheet open={fundModalOpen} onOpenChange={setFundModalOpen}>
                <SheetContent side="bottom" className="rounded-t-2xl sm:rounded-t-3xl max-w-sm sm:max-w-md mx-auto p-4 sm:p-5 bg-white dark:bg-[#1e1e2d] border-t border-gray-100 dark:border-gray-800 shadow-xl">
                    <SheetHeader className="p-0 mb-3 text-left">
                        <div className="flex items-center gap-2.5">
                            <div className="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                <Wallet className="w-4.5 h-4.5" />
                            </div>
                            <div>
                                <SheetTitle className="text-base font-bold text-gray-900 dark:text-white leading-tight">Fund Wallet</SheetTitle>
                                <SheetDescription className="text-[11px] text-gray-500 dark:text-gray-400">Add funds to your account instantly</SheetDescription>
                            </div>
                        </div>
                    </SheetHeader>

                    {/* STATE 1: VIRTUAL ACCOUNT EXISTS */}
                    {user?.virtual_account ? (
                        <div className="space-y-4 pt-1">
                            {/* Premium Virtual Bank Card */}
                            <div className="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 p-4 sm:p-5 text-white shadow-xl shadow-indigo-500/20">
                                <div className="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-xl pointer-events-none" />
                                
                                <div className="flex items-center justify-between mb-4">
                                    <div className="flex items-center gap-2">
                                        <div className="w-8 h-8 rounded-lg bg-white/15 backdrop-blur-md flex items-center justify-center border border-white/20">
                                            <Building2 className="w-4 h-4 text-white" />
                                        </div>
                                        <div>
                                            <p className="text-[10px] font-medium text-indigo-200 uppercase tracking-wider">Bank Partner</p>
                                            <p className="text-xs font-black tracking-wide">{user.virtual_account.bank_name}</p>
                                        </div>
                                    </div>
                                    <span className="px-2.5 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-[10px] font-bold tracking-wide flex items-center gap-1">
                                        <span className="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse" />
                                        24/7 Instant
                                    </span>
                                </div>

                                <div className="mb-4">
                                    <p className="text-[10px] font-medium text-indigo-200 uppercase tracking-wider mb-1">Dedicated Account Number</p>
                                    <div className="flex items-center justify-between bg-black/20 backdrop-blur-sm rounded-xl px-3.5 py-2.5 border border-white/10">
                                        <span className="text-xl font-black tracking-widest font-mono text-white">
                                            {user.virtual_account.account_number}
                                        </span>
                                        <button
                                            onClick={() => handleCopyAccount(user.virtual_account.account_number)}
                                            className="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white text-indigo-900 text-xs font-bold shadow-md hover:bg-indigo-50 active:scale-95 transition-all cursor-pointer"
                                        >
                                            {copySuccess ? (
                                                <>
                                                    <Check className="w-3.5 h-3.5 text-emerald-600" />
                                                    <span className="text-emerald-600">Copied</span>
                                                </>
                                            ) : (
                                                <>
                                                    <Copy className="w-3.5 h-3.5 text-indigo-700" />
                                                    <span>Copy</span>
                                                </>
                                            )}
                                        </button>
                                    </div>
                                </div>

                                <div className="flex items-center justify-between text-[11px] pt-2 border-t border-white/10">
                                    <span className="text-indigo-200 font-medium">Account Name</span>
                                    <span className="font-bold text-white truncate max-w-[200px]">
                                        {user.virtual_account.account_name}
                                    </span>
                                </div>
                            </div>

                            <div className="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-xl p-3 flex items-start gap-2.5 text-amber-800 dark:text-amber-300 text-[11px]">
                                <ShieldCheck className="w-4 h-4 flex-shrink-0 mt-0.5" />
                                <div>
                                    <p className="font-bold">Automated Funding Notice</p>
                                    <p className="opacity-90 leading-relaxed">Transfers sent to this dedicated bank account are processed via PayMint and automatically credited to your wallet in seconds.</p>
                                </div>
                            </div>
                        </div>
                    ) : (
                        /* STATE 2: NO ACCOUNT YET (KYC PROMPT) */
                        <div className="space-y-3 pt-0.5">
                            <div className="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-xl p-2.5 sm:p-3 flex items-center gap-2.5 text-amber-800 dark:text-amber-300">
                                <AlertCircle className="w-4 h-4 flex-shrink-0" />
                                <div className="text-[11px] leading-tight">
                                    <p className="font-bold mb-0.5">Identity Verification Required</p>
                                    <p className="opacity-90">Per CBN regulations, please enter your NIN or BVN below to generate your instant bank account.</p>
                                </div>
                            </div>

                            {(flash?.error || errors?.number || errors?.phone) && (
                                <div className="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 rounded-xl p-2.5 text-rose-700 dark:text-rose-300 text-xs font-semibold">
                                    {flash?.error || errors?.number || errors?.phone || 'Failed to generate account.'}
                                </div>
                            )}

                            <form onSubmit={handleGenerateAccount} className="space-y-3">
                                {/* Type Switcher */}
                                <div className="grid grid-cols-2 gap-1.5 bg-gray-100 dark:bg-gray-800 p-1 rounded-lg">
                                    <button
                                        type="button"
                                        onClick={() => setKycType('nin')}
                                        className={`py-1.5 text-[11px] font-bold rounded-md transition-all ${
                                            kycType === 'nin'
                                                ? 'bg-white dark:bg-[#1e1e2d] text-primary shadow-xs'
                                                : 'text-gray-500 dark:text-gray-400 hover:text-gray-900'
                                        }`}
                                    >
                                        NIN (National ID)
                                    </button>
                                    <button
                                        type="button"
                                        onClick={() => setKycType('bvn')}
                                        className={`py-1.5 text-[11px] font-bold rounded-lg transition-all ${
                                            kycType === 'bvn'
                                                ? 'bg-white dark:bg-[#1e1e2d] text-primary shadow-xs'
                                                : 'text-gray-500 dark:text-gray-400 hover:text-gray-900'
                                        }`}
                                    >
                                        BVN (Bank Verif. No)
                                    </button>
                                </div>

                                <div>
                                    <label className="text-[11px] font-bold text-gray-700 dark:text-gray-300 mb-1 block">
                                        Account Holder Full Name
                                    </label>
                                    <input
                                        type="text"
                                        value={name}
                                        onChange={(e) => setName(e.target.value)}
                                        placeholder="Full Name"
                                        className="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-primary"
                                    />
                                </div>

                                <div>
                                    <label className="text-[11px] font-bold text-gray-700 dark:text-gray-300 mb-1 block">
                                        Enter 11-Digit {kycType.toUpperCase()} Number
                                    </label>
                                    <input
                                        type="text"
                                        maxLength={11}
                                        value={kycNumber}
                                        onChange={(e) => setKycNumber(e.target.value.replace(/\D/g, ''))}
                                        placeholder={`e.g. 22123456789`}
                                        className="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-primary"
                                    />
                                </div>

                                <button
                                    type="submit"
                                    disabled={isGenerating || kycNumber.length !== 11}
                                    className="w-full py-2.5 rounded-lg bg-primary text-white text-xs font-bold flex items-center justify-center gap-1.5 shadow-sm shadow-primary/20 hover:opacity-90 active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer mb-1"
                                >
                                    {isGenerating ? (
                                        <span>Generating Account...</span>
                                    ) : (
                                        <>
                                            <Sparkles className="w-3.5 h-3.5" />
                                            <span>Generate Instant Account</span>
                                        </>
                                    )}
                                </button>
                            </form>
                        </div>
                    )}
                </SheetContent>
            </Sheet>
        </>
    );
}

interface ServiceItemProps {
    icon: React.ReactNode;
    title: string;
    subtitle: string;
    color: string;
    onClick?: () => void;
}

function ServiceItem({ icon, title, subtitle, color, onClick }: ServiceItemProps) {
    return (
        <button onClick={onClick} className="group flex flex-col items-center p-1.5 sm:p-2 md:p-4 rounded-xl md:rounded-2xl bg-primary/5 dark:bg-gray-800/50 hover:bg-primary/10 dark:hover:bg-primary/20 border border-gray-100 dark:border-gray-700 transition-all duration-300 text-left w-full cursor-pointer overflow-hidden">
            <div className={`w-9 h-9 md:w-12 md:h-12 mb-1 md:mb-3 flex items-center justify-center rounded-xl group-hover:scale-105 transition-transform shrink-0 ${color.split(' ')[0]}`}>
                {icon}
            </div>
            <span className="text-[10px] md:text-[13px] font-bold text-gray-900 dark:text-gray-100 text-center leading-tight line-clamp-2 w-full px-0.5">{title}</span>
            <span className="text-[8px] md:text-[10px] text-gray-400 mt-0.5 md:mt-1 text-center font-medium hidden sm:block truncate w-full">{subtitle}</span>
        </button>
    );
}

interface MobileActionItemProps {
    icon: React.ReactNode;
    title: string;
}

function MobileActionItem({ icon, title }: MobileActionItemProps) {
    return (
        <button className="flex flex-col items-center group w-full cursor-pointer">
            <div className="w-12 h-12 rounded-2xl bg-primary/5 dark:bg-primary/10 text-primary flex items-center justify-center mb-1.5 group-active:scale-95 transition-transform">
                {icon}
            </div>
            <span className="text-[11px] font-bold text-gray-800 dark:text-gray-200 leading-tight">{title}</span>
        </button>
    );
}

Dashboard.layout = {
    breadcrumbs: [
        {
            title: 'Dashboard',
            href: dashboard(),
        },
    ],
};
