import { useState, useEffect } from 'react';
import { ChevronDown, BookUser, AlertCircle, Smartphone } from 'lucide-react';
import { PatternFormat } from 'react-number-format';
import mtnIcon from '@/assets/icons/mtn.png';
import airtelIcon from '@/assets/icons/airtel.png';
import gloIcon from '@/assets/icons/glo.png';
import nineMobileIcon from '@/assets/icons/9mobile.png';

/**
 * Universal Nigerian Phone Number Normalizer
 * Handles +234, 234, leading 0, spaces, dashes, brackets, and international formats.
 * e.g., '+234 803 123 4567' -> '08031234567'
 */
export const normalizeNigerianPhone = (raw: string): string => {
    if (!raw) return '';
    // Strip non-digits
    let digits = raw.replace(/\D/g, '');

    // Convert 234... to 0...
    if (digits.startsWith('234') && digits.length >= 12) {
        digits = '0' + digits.slice(3);
    } else if (digits.startsWith('234') && digits.length === 11) {
        // e.g. 23480312345 (missing leading 0)
        digits = '0' + digits.slice(3);
    }

    // 10 digits without leading 0 (e.g. 8031234567) -> add 0
    if (digits.length === 10 && (digits.startsWith('7') || digits.startsWith('8') || digits.startsWith('9'))) {
        digits = '0' + digits;
    }

    return digits.slice(0, 11);
};

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

    // Contact picker listener (Android Native Bridge & Custom Events)
    useEffect(() => {
        const handleContactReceived = (event: any) => {
            const rawPhone = event.detail?.phone || event;
            if (rawPhone && typeof rawPhone === 'string') {
                const normalized = normalizeNigerianPhone(rawPhone);
                if (normalized) {
                    setPhone(normalized);
                    if (normalized.length === 11 && setPhoneError) {
                        setPhoneError(null);
                    }
                }
            }
        };

        if (typeof window !== 'undefined') {
            (window as any).onAffanContactPicked = (phoneStr: string) => {
                handleContactReceived({ detail: { phone: phoneStr } });
            };
            window.addEventListener('affan:contact-picked', handleContactReceived);
        }

        return () => {
            if (typeof window !== 'undefined') {
                window.removeEventListener('affan:contact-picked', handleContactReceived);
                delete (window as any).onAffanContactPicked;
            }
        };
    }, [setPhone, setPhoneError]);

    // Handle Contact Picker: Native Android Bridge -> Browser Contact Picker -> Logged-in User Phone
    const handlePickContact = async () => {
        // 1. Android Native App Bridge
        if (typeof window !== 'undefined' && (window as any).AffanBridge && typeof (window as any).AffanBridge.pickContact === 'function') {
            try {
                (window as any).AffanBridge.pickContact();
                return;
            } catch (err) {
                console.warn('AffanBridge.pickContact error:', err);
            }
        }

        // 2. Mobile Browser Contact Picker API (Chrome Android, Edge, etc.)
        if (typeof navigator !== 'undefined' && 'contacts' in navigator && 'ContactsManager' in window) {
            try {
                const props = ['tel'];
                const contacts = await (navigator as any).contacts.select(props, { multiple: false });
                if (contacts && contacts.length > 0 && contacts[0].tel && contacts[0].tel.length > 0) {
                    const selected = contacts[0].tel[0];
                    const normalized = normalizeNigerianPhone(selected);
                    setPhone(normalized);
                    if (normalized.length === 11 && setPhoneError) {
                        setPhoneError(null);
                    }
                    return;
                }
            } catch (e) {
                console.log('Mobile browser contact picker cancelled/error:', e);
            }
        }

        // 3. Fallback: Quick auto-fill logged-in user phone
        if (userPhone) {
            const normalized = normalizeNigerianPhone(userPhone);
            setPhone(normalized);
            if (normalized.length === 11 && setPhoneError) {
                setPhoneError(null);
            }
        }
    };

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

                    {/* Network Dropdown Menu */}
                    {networkDropdownOpen && (
                        <>
                            <div
                                className="fixed inset-0 z-40"
                                onClick={() => setNetworkDropdownOpen(false)}
                            />
                            <div className="absolute top-full left-0 mt-1.5 w-44 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-xl z-50 p-1 space-y-0.5 animate-in fade-in zoom-in-95 duration-100">
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
                                                if (phoneError && phoneError.toLowerCase().includes('unavailable')) {
                                                    setPhoneError?.(null);
                                                }
                                            }}
                                            className={`w-full flex items-center justify-between gap-2 px-2.5 py-2 rounded-lg text-left transition-colors ${
                                                selectedNetwork === net.slug
                                                    ? 'bg-primary/10 text-primary font-bold'
                                                    : isEnabled
                                                    ? 'hover:bg-gray-50 dark:hover:bg-gray-700/50 text-gray-700 dark:text-gray-300 font-medium cursor-pointer'
                                                    : 'opacity-40 cursor-not-allowed text-gray-400 dark:text-gray-500'
                                            }`}
                                        >
                                            <div className="flex items-center gap-2">
                                                <img
                                                    src={NETWORK_ICONS[net.slug]}
                                                    alt={net.name}
                                                    className="w-5 h-5 rounded-full object-cover shrink-0"
                                                />
                                                <span className="text-xs font-bold">{net.name}</span>
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

                {/* Phone Input with PatternFormat & Paste Normalizer */}
                <PatternFormat
                    id="phone-input"
                    format="### #### ####"
                    type="tel"
                    value={phone}
                    onPaste={(e: React.ClipboardEvent<HTMLInputElement>) => {
                        e.preventDefault();
                        const text = e.clipboardData.getData('text');
                        const clean = normalizeNigerianPhone(text);
                        setPhone(clean);
                        if (clean.length === 11 && setPhoneError) {
                            setPhoneError(null);
                        }
                    }}
                    onValueChange={(values) => {
                        let clean = normalizeNigerianPhone(values.value);
                        setPhone(clean);
                        if (clean.length === 11 && setPhoneError) {
                            setPhoneError(null);
                        }
                    }}
                    placeholder="090 2529 3759"
                    className="w-full bg-transparent px-3 py-1 text-base font-bold text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none tracking-wide font-mono"
                />

                {/* Contact Picker Button */}
                <button
                    type="button"
                    onClick={handlePickContact}
                    className="w-9 h-9 rounded-full bg-primary/10 text-primary hover:bg-primary/20 transition-all flex items-center justify-center shrink-0 cursor-pointer active:scale-95"
                    title="Pick contact from phone address book"
                >
                    <BookUser className="w-4.5 h-4.5" />
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
