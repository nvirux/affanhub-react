import { useState, useEffect } from 'react';
import { ChevronDown, User, AlertCircle, Smartphone } from 'lucide-react';
import { PatternFormat } from 'react-number-format';
import mtnIcon from '@/assets/icons/mtn.png';
import airtelIcon from '@/assets/icons/airtel.png';
import gloIcon from '@/assets/icons/glo.png';
import nineMobileIcon from '@/assets/icons/9mobile.png';

export const NETWORK_ICONS: Record<string, string> = {
    mtn: mtnIcon,
    airtel: airtelIcon,
    glo: gloIcon,
    '9mobile': nineMobileIcon,
};

export const NETWORKS_LIST = [
    { slug: 'mtn', name: 'MTN' },
    { slug: 'airtel', name: 'Airtel' },
    { slug: 'glo', name: 'Glo' },
    { slug: '9mobile', name: '9mobile' },
];

export interface NetworkItem {
    id?: number;
    name: string;
    slug: string;
    is_enabled?: boolean;
}

interface PhoneNetworkCardProps {
    phone: string;
    setPhone: (phone: string) => void;
    selectedNetwork: string;
    setSelectedNetwork: (network: string) => void;
    phoneError?: string | null;
    setPhoneError?: (error: string | null) => void;
    userPhone?: string;
    autoDetect?: boolean;
    onNetworkChange?: (network: string) => void;
    className?: string;
    networks?: NetworkItem[];
}

export function PhoneNetworkCard({
    phone,
    setPhone,
    selectedNetwork,
    setSelectedNetwork,
    phoneError,
    setPhoneError,
    userPhone,
    autoDetect = true,
    onNetworkChange,
    className = '',
    networks,
}: PhoneNetworkCardProps) {
    const [networkDropdownOpen, setNetworkDropdownOpen] = useState(false);

    const isNetworkEnabled = (slug: string): boolean => {
        if (!networks || networks.length === 0) return true;
        const net = networks.find((n) => n.slug.toLowerCase() === slug.toLowerCase());
        return net ? Boolean(net.is_enabled ?? true) : true;
    };

    // Auto-detect phone network prefix
    useEffect(() => {
        if (autoDetect && phone.length >= 4) {
            const prefix = phone.substring(0, 4);
            const mtnPrefixes = ['0803', '0806', '0703', '0706', '0813', '0816', '0810', '0814', '0903', '0906', '0913', '0916'];
            const airtelPrefixes = ['0802', '0808', '0708', '0812', '0701', '0902', '0901', '0904', '0907', '0912'];
            const gloPrefixes = ['0805', '0807', '0705', '0815', '0811', '0905', '0915'];
            const etisalatPrefixes = ['0809', '0817', '0818', '0909', '0908'];

            let detected: string | null = null;
            if (mtnPrefixes.includes(prefix)) detected = 'mtn';
            else if (airtelPrefixes.includes(prefix)) detected = 'airtel';
            else if (gloPrefixes.includes(prefix)) detected = 'glo';
            else if (etisalatPrefixes.includes(prefix)) detected = '9mobile';

            if (detected && detected !== selectedNetwork) {
                if (isNetworkEnabled(detected)) {
                    setSelectedNetwork(detected);
                    onNetworkChange?.(detected);
                    if (phoneError && phoneError.toLowerCase().includes('unavailable')) {
                        setPhoneError?.(null);
                    }
                } else {
                    const netName = NETWORKS_LIST.find((n) => n.slug === detected)?.name || detected.toUpperCase();
                    setPhoneError?.(`${netName} is currently unavailable on this store.`);
                }
            }
        }
    }, [phone, autoDetect, selectedNetwork, setSelectedNetwork, onNetworkChange, networks]);

    const isCurrentActive = isNetworkEnabled(selectedNetwork);

    return (
        <div className={`space-y-1.5 ${className}`}>
            <div className={`relative flex items-center bg-white dark:bg-[#181826] border rounded-2xl px-3.5 py-2.5 sm:py-3 shadow-xs transition-all ${
                phoneError
                    ? 'border-rose-400 dark:border-rose-500 ring-2 ring-rose-400/20'
                    : 'border-gray-100 dark:border-gray-800 hover:border-gray-200 dark:hover:border-gray-700'
            }`}>
                
                {/* Network Selector Dropdown Trigger */}
                <div className="relative shrink-0">
                    <button
                        type="button"
                        onClick={() => setNetworkDropdownOpen(!networkDropdownOpen)}
                        className="flex items-center gap-2 pr-3 border-r border-gray-200 dark:border-gray-700 cursor-pointer"
                    >
                        <div className="relative">
                            {NETWORK_ICONS[selectedNetwork] ? (
                                <img
                                    src={NETWORK_ICONS[selectedNetwork]}
                                    alt={selectedNetwork}
                                    className={`w-8 h-8 rounded-full object-contain p-0.5 bg-white shadow-2xs border border-gray-100 dark:border-gray-700 shrink-0 ${
                                        !isCurrentActive ? 'grayscale opacity-60' : ''
                                    }`}
                                />
                            ) : (
                                <span className="w-8 h-8 rounded-full flex items-center justify-center text-[11px] font-black bg-primary text-white shadow-2xs shrink-0">
                                    {selectedNetwork.toUpperCase()}
                                </span>
                            )}
                            {!isCurrentActive && (
                                <span
                                    className="absolute -top-1 -right-1 w-3 h-3 bg-rose-500 border-2 border-white dark:border-[#181826] rounded-full"
                                    title="Network unavailable on store"
                                />
                            )}
                        </div>
                        <ChevronDown className="w-4 h-4 text-gray-400" />
                    </button>

                    {/* Network Popup Dropdown */}
                    {networkDropdownOpen && (
                        <>
                            <div className="fixed inset-0 z-40" onClick={() => setNetworkDropdownOpen(false)} />
                            <div className="absolute left-0 top-full mt-2 w-52 bg-white dark:bg-[#1c1c28] border border-gray-100 dark:border-gray-800 rounded-2xl shadow-xl z-50 p-2 space-y-1">
                                {NETWORKS_LIST.map((net) => {
                                    const isEnabled = isNetworkEnabled(net.slug);

                                    return (
                                        <button
                                            key={net.slug}
                                            type="button"
                                            disabled={!isEnabled}
                                            onClick={() => {
                                                if (!isEnabled) return;
                                                setSelectedNetwork(net.slug);
                                                onNetworkChange?.(net.slug);
                                                setNetworkDropdownOpen(false);
                                            }}
                                            className={`flex items-center justify-between w-full px-3 py-2.5 rounded-xl text-xs font-bold transition-colors ${
                                                !isEnabled
                                                    ? 'opacity-40 grayscale cursor-not-allowed bg-gray-50/60 dark:bg-gray-800/40 text-gray-400 dark:text-gray-500'
                                                    : selectedNetwork === net.slug
                                                        ? 'bg-primary/10 text-primary cursor-pointer'
                                                        : 'hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-200 cursor-pointer'
                                            }`}
                                            title={!isEnabled ? `${net.name} is currently disabled on this store` : undefined}
                                        >
                                            <div className="flex items-center gap-3 min-w-0">
                                                {NETWORK_ICONS[net.slug] ? (
                                                    <img
                                                        src={NETWORK_ICONS[net.slug]}
                                                        alt={net.name}
                                                        className={`w-6 h-6 rounded-full object-contain p-0.5 bg-white shadow-xs border border-gray-100 dark:border-gray-700 shrink-0 ${
                                                            !isEnabled ? 'grayscale' : ''
                                                        }`}
                                                    />
                                                ) : (
                                                    <span className="w-6 h-6 rounded-full flex items-center justify-center text-[9px] font-black bg-primary text-white shrink-0">
                                                        {net.name[0]}
                                                    </span>
                                                )}
                                                <span className="truncate">{net.name}</span>
                                            </div>
                                            {!isEnabled && (
                                                <span className="text-[9px] font-semibold text-rose-500 dark:text-rose-400 bg-rose-50 dark:bg-rose-500/10 px-1.5 py-0.5 rounded shrink-0">
                                                    Disabled
                                                </span>
                                            )}
                                        </button>
                                    );
                                })}
                            </div>
                        </>
                    )}
                </div>

                {/* Phone Input with PatternFormat */}
                <PatternFormat
                    id="phone-input"
                    format="### #### ####"
                    type="tel"
                    value={phone}
                    onValueChange={(values) => {
                        let clean = values.value;
                        if (clean.startsWith('234') && clean.length > 10) {
                            clean = '0' + clean.slice(3);
                        }
                        clean = clean.slice(0, 11);
                        setPhone(clean);
                        if (clean.length === 11 && setPhoneError) {
                            setPhoneError(null);
                        }
                    }}
                    placeholder="090 2529 3759"
                    className="w-full bg-transparent px-3 py-1 text-base font-bold text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none tracking-wide font-mono"
                />

                {/* Beneficiary Round Icon */}
                <button
                    type="button"
                    onClick={() => {
                        let val = (userPhone || '09025293759').replace(/\D/g, '');
                        if (val.startsWith('234') && val.length > 10) {
                            val = '0' + val.slice(3);
                        }
                        val = val.slice(0, 11);
                        setPhone(val);
                        if (val.length === 11 && setPhoneError) {
                            setPhoneError(null);
                        }
                    }}
                    className="w-9 h-9 rounded-full bg-primary/10 text-primary hover:bg-primary/20 transition-colors flex items-center justify-center shrink-0 cursor-pointer"
                    title="Auto-fill phone number"
                >
                    <User className="w-5 h-5" />
                </button>
            </div>

            {/* Validation Error Message */}
            {phoneError && (
                <p className="text-xs font-bold text-rose-500 dark:text-rose-400 flex items-center gap-1.5 px-2 pt-0.5">
                    <AlertCircle className="w-3.5 h-3.5 shrink-0" />
                    <span>{phoneError}</span>
                </p>
            )}
        </div>
    );
}
