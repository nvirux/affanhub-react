import { useState, useEffect } from 'react';
import { Head, Link, usePage, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { cn } from '@/lib/utils';
import {
    ChevronLeft,
    Wallet as WalletIcon,
    PlusCircle,
    Copy,
    Check,
    Eye,
    EyeOff,
    Building2,
    ShieldCheck,
    CreditCard,
    ArrowUpRight,
    ArrowDownLeft,
    Headphones,
    Sparkles,
    AlertCircle,
    CheckCircle2,
    Clock,
    X,
    QrCode,
    RefreshCw,
} from 'lucide-react';

interface VirtualAccountData {
    id: number;
    bank_name: string;
    account_number: string;
    account_name: string;
    status: string;
    provider?: string;
}

interface WalletData {
    balance: number;
    currency: string;
    status: string;
}

interface TransactionItem {
    id: number | string;
    reference: string;
    type: 'deposit' | 'debit' | string;
    title: string;
    description: string;
    amount: number;
    status: 'successful' | 'pending' | 'failed' | string;
    created_at: string;
    date: string;
}

interface WalletPageProps {
    wallet: WalletData;
    virtual_account?: VirtualAccountData | null;
    recent_transactions?: TransactionItem[];
}

export default function WalletPage({
    wallet = { balance: 0, currency: 'NGN', status: 'active' },
    virtual_account = null,
    recent_transactions = [],
}: WalletPageProps) {
    const { auth, store } = usePage<any>().props;
    const user = auth?.user;

    // Balance visibility state
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
        setShowBalance((prev) => {
            const next = !prev;
            localStorage.setItem('hideBalance', String(!next));
            return next;
        });
    };

    // Copy virtual account state
    const [copiedAccount, setCopiedAccount] = useState(false);
    const handleCopyAccount = (text: string) => {
        if (!text) return;
        navigator.clipboard.writeText(text);
        setCopiedAccount(true);
        setTimeout(() => setCopiedAccount(false), 2000);
    };

    // Add Money Modal & Confirm to Pay State
    const [isAddMoneyOpen, setIsAddMoneyOpen] = useState(false);
    const [depositAmount, setDepositAmount] = useState<string>('2000');
    const [selectedMethod, setSelectedMethod] = useState<'transfer' | 'card'>('transfer');
    const [isConfirmModalOpen, setIsConfirmModalOpen] = useState(false);
    const [isProcessingPay, setIsProcessingPay] = useState(false);

    // Virtual Account KYC Generator Modal
    const [isKycModalOpen, setIsKycModalOpen] = useState(false);
    const [kycType, setKycType] = useState<'nin' | 'bvn'>('nin');
    const [kycNumber, setKycNumber] = useState('');
    const [isGeneratingKyc, setIsGeneratingKyc] = useState(false);

    const quickAmounts = ['1000', '2000', '5000', '10000', '20000'];

    const handleQuickAmountSelect = (val: string) => {
        setDepositAmount(val);
    };

    const handleOpenConfirm = () => {
        const num = parseFloat(depositAmount);
        if (isNaN(num) || num < 100) {
            alert('Minimum deposit amount is ₦100');
            return;
        }
        setIsAddMoneyOpen(false);
        setIsConfirmModalOpen(true);
    };

    const handleSimulatePayment = () => {
        setIsProcessingPay(true);
        setTimeout(() => {
            setIsProcessingPay(false);
            setIsConfirmModalOpen(false);
            alert('Payment simulation initiated. Funds will reflect in your wallet upon confirmation.');
        }, 1500);
    };

    const handleGenerateAccount = (e: React.FormEvent) => {
        e.preventDefault();
        if (kycNumber.length !== 11) {
            alert(`Please enter a valid 11-digit ${kycType.toUpperCase()}`);
            return;
        }
        setIsGeneratingKyc(true);
        router.post(
            '/virtual-account/generate',
            {
                type: kycType,
                number: kycNumber,
                phone: user?.phone,
                name: user?.name,
            },
            {
                onFinish: () => setIsGeneratingKyc(false),
                onSuccess: () => {
                    setIsKycModalOpen(false);
                    setKycNumber('');
                },
            }
        );
    };

    const activeVirtualAccount = virtual_account || user?.virtual_account;
    const currentBalance = wallet?.balance ?? user?.wallet_balance ?? 0;

    // Fallback sample transactions if none exist yet for design display
    const displayTransactions: TransactionItem[] = recent_transactions.length > 0 ? recent_transactions : [];

    return (
        <AppLayout
            breadcrumbs={[
                { title: 'Dashboard', href: dashboard().url },
                { title: 'Wallet & Funding', href: '/wallet' },
            ]}
        >
            <Head title="Wallet & Funding" />

            {/* ── MOBILE STICKY HEADER ── */}
            <div className="sticky top-0 z-30 bg-white/95 dark:bg-[#181826]/95 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 py-3 px-4 flex md:hidden items-center justify-between shadow-2xs">
                <div className="flex items-center gap-3 min-w-0">
                    <Link
                        href={dashboard().url}
                        className="p-1 text-gray-700 dark:text-gray-200 hover:text-primary transition-colors cursor-pointer shrink-0"
                    >
                        <ChevronLeft className="w-6 h-6 stroke-[2.2]" />
                    </Link>
                    <div className="min-w-0">
                        <h1 className="font-extrabold text-base text-gray-900 dark:text-white tracking-tight leading-tight">
                            Wallet & Funding
                        </h1>
                        <p className="text-xs text-slate-400 dark:text-slate-400 font-normal truncate mt-0.5">
                            Manage balance & automated deposits
                        </p>
                    </div>
                </div>

                <div className="flex items-center gap-2 shrink-0 ml-2">
                    <Link
                        href="/contact"
                        className="p-1.5 text-slate-600 dark:text-slate-300 hover:text-primary transition-colors cursor-pointer"
                        title="Customer Support"
                    >
                        <Headphones className="w-5 h-5" />
                    </Link>
                </div>
            </div>

            {/* ── MAIN CONTENT CONTAINER ── */}
            <div className="max-w-4xl mx-auto px-4 py-5 md:py-8 space-y-6">
                {/* ── HERO WALLET BALANCE CARD ── */}
                <div className="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 dark:from-zinc-900 dark:via-zinc-950 dark:to-slate-900 p-6 md:p-8 text-white shadow-xl border border-slate-700/40">
                    {/* Decorative Background Circles */}
                    <div className="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-primary/20 blur-2xl pointer-events-none" />
                    <div className="absolute -bottom-12 -left-12 w-48 h-48 rounded-full bg-indigo-500/10 blur-2xl pointer-events-none" />

                    <div className="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div className="space-y-3">
                            <div className="flex items-center gap-2 text-slate-300 text-xs font-semibold uppercase tracking-wider">
                                <WalletIcon className="w-4 h-4 text-primary" />
                                <span>Available Wallet Balance</span>
                                <button
                                    onClick={toggleBalance}
                                    className="p-1 text-slate-400 hover:text-white transition-colors cursor-pointer ml-1"
                                    title={showBalance ? 'Hide Balance' : 'Show Balance'}
                                >
                                    {showBalance ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                                </button>
                            </div>

                            <div className="flex items-baseline gap-1">
                                <span className="text-3xl md:text-4xl font-extrabold tracking-tight text-white">
                                    {showBalance
                                        ? `₦${Number(currentBalance).toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
                                        : '••••••••'}
                                </span>
                            </div>

                            <div className="flex items-center gap-2 pt-1">
                                <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                    <span className="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse" />
                                    Active Account
                                </span>
                                <span className="text-xs text-slate-400 font-medium">
                                    Instant 24/7 Top-up
                                </span>
                            </div>
                        </div>

                        {/* Quick Action Buttons */}
                        <div className="flex flex-wrap items-center gap-3">
                            <button
                                onClick={() => setIsAddMoneyOpen(true)}
                                className="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-primary hover:bg-primary/90 text-primary-foreground font-bold text-sm shadow-md transition-all active:scale-98 cursor-pointer"
                            >
                                <PlusCircle className="w-4 h-4" />
                                <span>Add Money</span>
                            </button>

                            {activeVirtualAccount ? (
                                <button
                                    onClick={() => handleCopyAccount(activeVirtualAccount.account_number)}
                                    className="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-white/10 hover:bg-white/15 text-white font-semibold text-sm border border-white/15 backdrop-blur-xs transition-all active:scale-98 cursor-pointer"
                                >
                                    {copiedAccount ? <Check className="w-4 h-4 text-emerald-400" /> : <Copy className="w-4 h-4" />}
                                    <span>{copiedAccount ? 'Copied' : 'Copy Account'}</span>
                                </button>
                            ) : (
                                <button
                                    onClick={() => setIsKycModalOpen(true)}
                                    className="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-white/10 hover:bg-white/15 text-white font-semibold text-sm border border-white/15 backdrop-blur-xs transition-all active:scale-98 cursor-pointer"
                                >
                                    <Building2 className="w-4 h-4" />
                                    <span>Get Bank Account</span>
                                </button>
                            )}
                        </div>
                    </div>
                </div>

                {/* ── DEDICATED VIRTUAL BANK ACCOUNT SECTION ── */}
                <div className="bg-white dark:bg-[#181826] rounded-2xl p-5 md:p-6 border border-slate-100 dark:border-slate-800/80 shadow-xs space-y-4">
                    <div className="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div className="flex items-center gap-2.5">
                            <div className="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                <Building2 className="w-5 h-5" />
                            </div>
                            <div>
                                <h2 className="font-bold text-slate-900 dark:text-white text-base">
                                    Dedicated Virtual Account
                                </h2>
                                <p className="text-xs text-slate-500 dark:text-slate-400">
                                    Automated bank transfer & wallet funding
                                </p>
                            </div>
                        </div>

                        <span className="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                            <Sparkles className="w-3.5 h-3.5" />
                            Instant Deposit
                        </span>
                    </div>

                    {activeVirtualAccount ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                            {/* Bank Details Box */}
                            <div className="p-4 rounded-xl bg-slate-50 dark:bg-zinc-900/60 border border-slate-200/70 dark:border-zinc-800 space-y-3">
                                <div className="flex items-center justify-between">
                                    <span className="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">
                                        Bank Name
                                    </span>
                                    <span className="text-sm font-bold text-slate-900 dark:text-white">
                                        {activeVirtualAccount.bank_name || 'Automated Bank'}
                                    </span>
                                </div>

                                <div className="flex items-center justify-between pt-1">
                                    <div className="space-y-0.5">
                                        <span className="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">
                                            Account Number
                                        </span>
                                        <div className="text-xl md:text-2xl font-mono font-extrabold text-slate-900 dark:text-white tracking-wider">
                                            {activeVirtualAccount.account_number}
                                        </div>
                                    </div>
                                    <button
                                        onClick={() => handleCopyAccount(activeVirtualAccount.account_number)}
                                        className={cn(
                                            'px-3 py-2 rounded-lg font-bold text-xs flex items-center gap-1.5 transition-all cursor-pointer shadow-xs',
                                            copiedAccount
                                                ? 'bg-emerald-600 text-white'
                                                : 'bg-primary text-primary-foreground hover:bg-primary/90'
                                        )}
                                    >
                                        {copiedAccount ? <Check className="w-3.5 h-3.5" /> : <Copy className="w-3.5 h-3.5" />}
                                        <span>{copiedAccount ? 'Copied' : 'Copy'}</span>
                                    </button>
                                </div>

                                <div className="flex items-center justify-between pt-2 border-t border-slate-200/60 dark:border-zinc-800/80">
                                    <span className="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">
                                        Account Name
                                    </span>
                                    <span className="text-xs font-bold text-slate-800 dark:text-slate-200 truncate max-w-[200px]">
                                        {activeVirtualAccount.account_name || user?.name || 'Account Holder'}
                                    </span>
                                </div>
                            </div>

                            {/* How It Works Note */}
                            <div className="p-4 rounded-xl bg-amber-500/5 dark:bg-amber-500/10 border border-amber-500/20 flex flex-col justify-between space-y-3">
                                <div className="space-y-1.5">
                                    <div className="flex items-center gap-2 text-amber-700 dark:text-amber-400 text-xs font-extrabold uppercase tracking-wider">
                                        <ShieldCheck className="w-4 h-4" />
                                        <span>How Automated Deposit Works</span>
                                    </div>
                                    <p className="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                        Transfer any amount to your unique account number above from your banking app, USSD, or POS. Your wallet will be credited <strong>automatically in seconds</strong>.
                                    </p>
                                </div>

                                <div className="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                                    <Clock className="w-3.5 h-3.5 text-primary shrink-0" />
                                    <span>24/7 Automated processing • Zero manual confirmation needed</span>
                                </div>
                            </div>
                        </div>
                    ) : (
                        <div className="p-6 rounded-xl bg-slate-50 dark:bg-zinc-900/60 border border-dashed border-slate-300 dark:border-zinc-800 text-center space-y-3">
                            <div className="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center mx-auto">
                                <Building2 className="w-6 h-6" />
                            </div>
                            <div className="max-w-md mx-auto space-y-1">
                                <h3 className="font-bold text-slate-900 dark:text-white text-sm md:text-base">
                                    No Virtual Account Generated Yet
                                </h3>
                                <p className="text-xs text-slate-500 dark:text-slate-400">
                                    Generate your dedicated bank account to enjoy automated wallet top-ups via direct bank transfer.
                                </p>
                            </div>
                            <button
                                onClick={() => setIsKycModalOpen(true)}
                                className="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-primary-foreground font-bold text-xs shadow-sm hover:bg-primary/90 transition-all cursor-pointer"
                            >
                                <PlusCircle className="w-4 h-4" />
                                <span>Generate Dedicated Account</span>
                            </button>
                        </div>
                    )}
                </div>

                {/* ── RECENT WALLET TRANSACTIONS CARD ── */}
                <div className="bg-white dark:bg-[#181826] rounded-2xl p-5 md:p-6 border border-slate-100 dark:border-slate-800/80 shadow-xs space-y-4">
                    <div className="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div className="flex items-center gap-2.5">
                            <div className="w-9 h-9 rounded-xl bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-slate-300 flex items-center justify-center">
                                <Clock className="w-5 h-5" />
                            </div>
                            <div>
                                <h2 className="font-bold text-slate-900 dark:text-white text-base">
                                    Recent Wallet Transactions
                                </h2>
                                <p className="text-xs text-slate-500 dark:text-slate-400">
                                    History of deposits and wallet activities
                                </p>
                            </div>
                        </div>

                        <Link
                            href={dashboard().url}
                            className="text-xs font-bold text-primary hover:underline cursor-pointer"
                        >
                            View All
                        </Link>
                    </div>

                    {displayTransactions.length > 0 ? (
                        <div className="divide-y divide-slate-100 dark:divide-slate-800">
                            {displayTransactions.map((tx) => {
                                const isDeposit = tx.type === 'deposit';
                                return (
                                    <div
                                        key={tx.id}
                                        className="py-3.5 flex items-center justify-between gap-3 hover:bg-slate-50/50 dark:hover:bg-zinc-900/30 px-2 rounded-xl transition-colors"
                                    >
                                        <div className="flex items-center gap-3 min-w-0">
                                            <div
                                                className={cn(
                                                    'w-10 h-10 rounded-xl flex items-center justify-center shrink-0',
                                                    isDeposit
                                                        ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                                        : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-slate-300'
                                                )}
                                            >
                                                {isDeposit ? (
                                                    <ArrowDownLeft className="w-5 h-5" />
                                                ) : (
                                                    <ArrowUpRight className="w-5 h-5" />
                                                )}
                                            </div>

                                            <div className="min-w-0 space-y-0.5">
                                                <div className="font-bold text-slate-900 dark:text-white text-sm truncate">
                                                    {tx.title}
                                                </div>
                                                <div className="text-xs text-slate-400 truncate flex items-center gap-1.5">
                                                    <span>{tx.reference}</span>
                                                    <span>•</span>
                                                    <span>{tx.created_at}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div className="text-right shrink-0 space-y-1">
                                            <div
                                                className={cn(
                                                    'font-extrabold text-sm',
                                                    isDeposit
                                                        ? 'text-emerald-600 dark:text-emerald-400'
                                                        : 'text-slate-900 dark:text-white'
                                                )}
                                            >
                                                {isDeposit ? '+' : '-'}₦
                                                {Number(tx.amount).toLocaleString('en-NG', {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2,
                                                })}
                                            </div>
                                            <span
                                                className={cn(
                                                    'inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-extrabold uppercase',
                                                    tx.status === 'successful'
                                                        ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                                        : tx.status === 'pending'
                                                          ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400'
                                                          : 'bg-rose-500/10 text-rose-600 dark:text-rose-400'
                                                )}
                                            >
                                                {tx.status}
                                            </span>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    ) : (
                        <div className="py-10 text-center space-y-2">
                            <div className="w-12 h-12 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-400 flex items-center justify-center mx-auto">
                                <Clock className="w-6 h-6" />
                            </div>
                            <h3 className="text-sm font-bold text-slate-800 dark:text-slate-200">
                                No Wallet Transactions Yet
                            </h3>
                            <p className="text-xs text-slate-400 max-w-sm mx-auto">
                                When you fund your wallet or purchase telecom services, your activity history will appear here.
                            </p>
                        </div>
                    )}
                </div>
            </div>

            {/* ── ADD MONEY / FUND WALLET MODAL ── */}
            {isAddMoneyOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 animate-in fade-in duration-150">
                    <div className="bg-white dark:bg-[#181826] rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 dark:border-slate-800 space-y-5 relative">
                        <button
                            onClick={() => setIsAddMoneyOpen(false)}
                            className="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg cursor-pointer"
                        >
                            <X className="w-5 h-5" />
                        </button>

                        <div className="space-y-1">
                            <h3 className="font-extrabold text-lg text-slate-900 dark:text-white flex items-center gap-2">
                                <PlusCircle className="w-5 h-5 text-primary" />
                                <span>Fund Wallet</span>
                            </h3>
                            <p className="text-xs text-slate-500 dark:text-slate-400">
                                Enter the amount you wish to deposit into your wallet.
                            </p>
                        </div>

                        {/* Amount Input */}
                        <div className="space-y-2">
                            <label className="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                Amount (₦)
                            </label>
                            <div className="relative">
                                <span className="absolute left-3.5 top-1/2 -translate-y-1/2 font-extrabold text-slate-400">
                                    ₦
                                </span>
                                <input
                                    type="number"
                                    min="100"
                                    value={depositAmount}
                                    onChange={(e) => setDepositAmount(e.target.value)}
                                    placeholder="e.g. 2000"
                                    className="w-full pl-8 pr-4 py-3 rounded-xl bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-900 dark:text-white font-extrabold text-lg focus:outline-none focus:ring-2 focus:ring-primary/40"
                                />
                            </div>

                            {/* Quick Amount Chips */}
                            <div className="flex flex-wrap gap-2 pt-1">
                                {quickAmounts.map((amt) => (
                                    <button
                                        key={amt}
                                        type="button"
                                        onClick={() => handleQuickAmountSelect(amt)}
                                        className={cn(
                                            'px-2.5 py-1 rounded-lg text-xs font-bold transition-all cursor-pointer',
                                            depositAmount === amt
                                                ? 'bg-primary text-primary-foreground shadow-xs'
                                                : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-zinc-700'
                                        )}
                                    >
                                        ₦{Number(amt).toLocaleString()}
                                    </button>
                                ))}
                            </div>
                        </div>

                        {/* Payment Method Selector */}
                        <div className="space-y-2">
                            <label className="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                Choose Payment Method
                            </label>
                            <div className="grid grid-cols-2 gap-2.5">
                                <button
                                    type="button"
                                    onClick={() => setSelectedMethod('transfer')}
                                    className={cn(
                                        'p-3 rounded-xl border text-left flex flex-col gap-1 transition-all cursor-pointer',
                                        selectedMethod === 'transfer'
                                            ? 'border-primary bg-primary/5 dark:bg-primary/10 ring-1 ring-primary'
                                            : 'border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50'
                                    )}
                                >
                                    <div className="flex items-center justify-between">
                                        <Building2 className="w-4 h-4 text-primary" />
                                        <span className="text-[10px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-600">
                                            Instant
                                        </span>
                                    </div>
                                    <span className="font-bold text-xs text-slate-900 dark:text-white">
                                        Bank Transfer
                                    </span>
                                    <span className="text-[10px] text-slate-400">
                                        Dedicated virtual account
                                    </span>
                                </button>

                                <button
                                    type="button"
                                    onClick={() => setSelectedMethod('card')}
                                    className={cn(
                                        'p-3 rounded-xl border text-left flex flex-col gap-1 transition-all cursor-pointer',
                                        selectedMethod === 'card'
                                            ? 'border-primary bg-primary/5 dark:bg-primary/10 ring-1 ring-primary'
                                            : 'border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50'
                                    )}
                                >
                                    <div className="flex items-center justify-between">
                                        <CreditCard className="w-4 h-4 text-primary" />
                                        <span className="text-[10px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-600">
                                            Online
                                        </span>
                                    </div>
                                    <span className="font-bold text-xs text-slate-900 dark:text-white">
                                        Card / USSD
                                    </span>
                                    <span className="text-[10px] text-slate-400">
                                        Instant gateway checkout
                                    </span>
                                </button>
                            </div>
                        </div>

                        {/* Modal Action */}
                        <div className="pt-2 flex gap-3">
                            <button
                                type="button"
                                onClick={() => setIsAddMoneyOpen(false)}
                                className="flex-1 py-2.5 rounded-xl border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-slate-300 font-bold text-xs hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
                            >
                                Cancel
                            </button>
                            <button
                                type="button"
                                onClick={handleOpenConfirm}
                                className="flex-1 py-2.5 rounded-xl bg-primary text-primary-foreground font-bold text-xs hover:bg-primary/90 shadow-md transition-all cursor-pointer"
                            >
                                Continue
                            </button>
                        </div>
                    </div>
                </div>
            )}

            {/* ── CONFIRM TO PAY MODAL / SHEET ── */}
            {isConfirmModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 animate-in fade-in duration-150">
                    <div className="bg-white dark:bg-[#181826] rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 dark:border-slate-800 space-y-5 relative">
                        <button
                            onClick={() => setIsConfirmModalOpen(false)}
                            className="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg cursor-pointer"
                        >
                            <X className="w-5 h-5" />
                        </button>

                        <div className="text-center space-y-1">
                            <div className="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center mx-auto">
                                <ShieldCheck className="w-6 h-6" />
                            </div>
                            <h3 className="font-extrabold text-lg text-slate-900 dark:text-white">
                                Confirm Deposit
                            </h3>
                            <p className="text-xs text-slate-500 dark:text-slate-400">
                                Review your wallet top-up breakdown before proceeding
                            </p>
                        </div>

                        {/* Breakdown Summary */}
                        <div className="p-4 rounded-xl bg-slate-50 dark:bg-zinc-900/80 border border-slate-200/70 dark:border-zinc-800 space-y-2.5 text-xs">
                            <div className="flex justify-between items-center text-slate-600 dark:text-slate-400">
                                <span>Deposit Amount:</span>
                                <span className="font-bold text-slate-900 dark:text-white">
                                    ₦{Number(depositAmount || 0).toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                                </span>
                            </div>

                            <div className="flex justify-between items-center text-slate-600 dark:text-slate-400">
                                <span>Payment Method:</span>
                                <span className="font-bold text-slate-900 dark:text-white capitalize">
                                    {selectedMethod === 'transfer' ? 'Dedicated Bank Transfer' : 'Debit Card / USSD'}
                                </span>
                            </div>

                            <div className="flex justify-between items-center text-slate-600 dark:text-slate-400">
                                <span>Processing Fee:</span>
                                <span className="font-bold text-emerald-600 dark:text-emerald-400">
                                    ₦0.00 (Free)
                                </span>
                            </div>

                            <div className="pt-2 border-t border-slate-200 dark:border-zinc-700 flex justify-between items-center text-sm font-extrabold">
                                <span className="text-slate-900 dark:text-white">Total Payable:</span>
                                <span className="text-primary text-base">
                                    ₦{Number(depositAmount || 0).toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                                </span>
                            </div>
                        </div>

                        {selectedMethod === 'transfer' && activeVirtualAccount ? (
                            <div className="p-3.5 rounded-xl bg-primary/5 dark:bg-primary/10 border border-primary/20 space-y-2">
                                <div className="flex items-center justify-between">
                                    <span className="text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Transfer directly to:
                                    </span>
                                    <span className="text-xs font-extrabold text-primary">
                                        {activeVirtualAccount.bank_name}
                                    </span>
                                </div>
                                <div className="flex items-center justify-between p-2 rounded-lg bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 font-mono font-bold text-sm">
                                    <span>{activeVirtualAccount.account_number}</span>
                                    <button
                                        onClick={() => handleCopyAccount(activeVirtualAccount.account_number)}
                                        className="text-xs font-sans text-primary hover:underline cursor-pointer"
                                    >
                                        {copiedAccount ? 'Copied!' : 'Copy'}
                                    </button>
                                </div>
                            </div>
                        ) : null}

                        {/* Modal Actions */}
                        <div className="pt-2 flex gap-3">
                            <button
                                type="button"
                                onClick={() => {
                                    setIsConfirmModalOpen(false);
                                    setIsAddMoneyOpen(true);
                                }}
                                className="flex-1 py-2.5 rounded-xl border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-slate-300 font-bold text-xs hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
                            >
                                Back
                            </button>

                            <button
                                type="button"
                                disabled={isProcessingPay}
                                onClick={handleSimulatePayment}
                                className="flex-1 py-2.5 rounded-xl bg-primary text-primary-foreground font-bold text-xs hover:bg-primary/90 shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                            >
                                {isProcessingPay && <RefreshCw className="w-3.5 h-3.5 animate-spin" />}
                                <span>{isProcessingPay ? 'Processing...' : 'Confirm to Pay'}</span>
                            </button>
                        </div>
                    </div>
                </div>
            )}

            {/* ── VIRTUAL ACCOUNT GENERATOR KYC MODAL ── */}
            {isKycModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 animate-in fade-in duration-150">
                    <div className="bg-white dark:bg-[#181826] rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 dark:border-slate-800 space-y-5 relative">
                        <button
                            onClick={() => setIsKycModalOpen(false)}
                            className="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg cursor-pointer"
                        >
                            <X className="w-5 h-5" />
                        </button>

                        <div className="space-y-1">
                            <h3 className="font-extrabold text-lg text-slate-900 dark:text-white flex items-center gap-2">
                                <Building2 className="w-5 h-5 text-primary" />
                                <span>Generate Dedicated Account</span>
                            </h3>
                            <p className="text-xs text-slate-500 dark:text-slate-400">
                                As required by CBN/NDIC regulations, provide your 11-digit NIN or BVN to generate your instant bank account.
                            </p>
                        </div>

                        <form onSubmit={handleGenerateAccount} className="space-y-4">
                            {/* KYC Type Toggle */}
                            <div className="grid grid-cols-2 gap-2 bg-slate-100 dark:bg-zinc-800 p-1 rounded-xl">
                                <button
                                    type="button"
                                    onClick={() => setKycType('nin')}
                                    className={cn(
                                        'py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer',
                                        kycType === 'nin'
                                            ? 'bg-white dark:bg-zinc-900 text-slate-900 dark:text-white shadow-xs'
                                            : 'text-slate-500 hover:text-slate-900 dark:hover:text-white'
                                    )}
                                >
                                    National ID (NIN)
                                </button>
                                <button
                                    type="button"
                                    onClick={() => setKycType('bvn')}
                                    className={cn(
                                        'py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer',
                                        kycType === 'bvn'
                                            ? 'bg-white dark:bg-zinc-900 text-slate-900 dark:text-white shadow-xs'
                                            : 'text-slate-500 hover:text-slate-900 dark:hover:text-white'
                                    )}
                                >
                                    Bank Verification (BVN)
                                </button>
                            </div>

                            <div className="space-y-1.5">
                                <label className="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    {kycType.toUpperCase()} Number (11 Digits)
                                </label>
                                <input
                                    type="text"
                                    maxLength={11}
                                    value={kycNumber}
                                    onChange={(e) => setKycNumber(e.target.value.replace(/\D/g, ''))}
                                    placeholder={`Enter 11-digit ${kycType.toUpperCase()}`}
                                    className="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-900 dark:text-white font-mono font-bold text-sm focus:outline-none focus:ring-2 focus:ring-primary/40"
                                    required
                                />
                            </div>

                            <div className="p-3 rounded-xl bg-blue-500/10 border border-blue-500/20 text-[11px] text-blue-700 dark:text-blue-300 flex items-start gap-2">
                                <ShieldCheck className="w-4 h-4 shrink-0 mt-0.5" />
                                <span>
                                    Your identity verification is securely encrypted and used solely for dedicated virtual account creation.
                                </span>
                            </div>

                            <div className="pt-2 flex gap-3">
                                <button
                                    type="button"
                                    onClick={() => setIsKycModalOpen(false)}
                                    className="flex-1 py-2.5 rounded-xl border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-slate-300 font-bold text-xs hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={isGeneratingKyc || kycNumber.length !== 11}
                                    className="flex-1 py-2.5 rounded-xl bg-primary text-primary-foreground font-bold text-xs hover:bg-primary/90 shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                                >
                                    {isGeneratingKyc && <RefreshCw className="w-3.5 h-3.5 animate-spin" />}
                                    <span>{isGeneratingKyc ? 'Generating...' : 'Generate Account'}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
