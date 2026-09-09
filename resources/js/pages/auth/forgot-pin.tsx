import { Head, useForm, Link } from '@inertiajs/react';
import { ShieldAlert, ArrowLeft, ArrowRight, User } from 'lucide-react';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import InputError from '@/components/input-error';
import { login } from '@/routes';

type Props = {
    status?: string;
    debugResetUrl?: string;
    initialIdentifier?: string;
};

export default function ForgotPin({ status, debugResetUrl, initialIdentifier = '' }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        identifier: initialIdentifier,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/forgot-pin');
    };

    return (
        <>
            <Head title="Forgot PIN" />

            <div className="flex flex-col gap-6">
                <div className="flex flex-col gap-2 text-center">
                    <div className="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-primary mb-1">
                        <ShieldAlert className="h-5 w-5" />
                    </div>
                    <h1 className="text-xl font-bold tracking-tight">Forgot Your Login PIN?</h1>
                    <p className="text-xs text-muted-foreground max-w-xs mx-auto">
                        Enter your registered phone number or email address, and we'll send you a secure link to reset your 4-digit PIN.
                    </p>
                </div>

                {status && (
                    <div className="rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-3.5 text-center text-xs font-medium text-emerald-600 dark:text-emerald-400">
                        {status}
                    </div>
                )}

                {debugResetUrl && (
                    <div className="rounded-xl border border-blue-500/20 bg-blue-500/10 p-3 text-center text-xs text-blue-600 dark:text-blue-400 space-y-1">
                        <p className="font-semibold text-[11px] uppercase tracking-wider">Dev Reset Link</p>
                        <a
                            href={debugResetUrl}
                            className="underline break-all font-mono text-[11px] block hover:text-blue-800 dark:hover:text-blue-200"
                        >
                            Click here to proceed to Reset PIN &rarr;
                        </a>
                    </div>
                )}

                <form onSubmit={handleSubmit} className="flex flex-col gap-6">
                    <div className="grid gap-2">
                        <Label htmlFor="identifier">Phone number or Email</Label>
                        <div className="relative">
                            <Input
                                id="identifier"
                                type="text"
                                name="identifier"
                                value={data.identifier}
                                onChange={(e) => setData('identifier', e.target.value)}
                                required
                                autoFocus
                                placeholder="e.g. 08012345678 or name@email.com"
                                disabled={processing}
                                className="pl-9"
                            />
                            <User className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                        </div>
                        <InputError message={errors.identifier} />
                    </div>

                    <Button
                        type="submit"
                        className="w-full flex items-center justify-center gap-2"
                        disabled={processing || !data.identifier.trim()}
                    >
                        {processing ? (
                            <>
                                <Spinner />
                                Sending reset link...
                            </>
                        ) : (
                            <>
                                Send Reset Link
                                <ArrowRight className="w-4 h-4" />
                            </>
                        )}
                    </Button>

                    <div className="text-center text-sm text-muted-foreground">
                        Remember your PIN?{' '}
                        <TextLink href={login()}>
                            Back to log in
                        </TextLink>
                    </div>
                </form>
            </div>
        </>
    );
}
