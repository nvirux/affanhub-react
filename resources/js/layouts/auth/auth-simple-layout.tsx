import AppLogoIcon from '@/components/app-logo-icon';
import type { AuthLayoutProps } from '@/types';

export default function AuthSimpleLayout({
    children,
    title,
    description,
}: AuthLayoutProps) {
    return (
        <div className="flex min-h-svh flex-col items-center justify-center bg-background p-4 sm:p-6 md:p-10">
            <div className="w-full max-w-sm">
                <div className="flex flex-col gap-5 sm:gap-6">
                    <div className="flex flex-col items-center gap-2.5">
                        <div className="flex size-12 items-center justify-center select-none pointer-events-none">
                            <AppLogoIcon className="size-12" />
                        </div>

                        {(title || description) && (
                            <div className="space-y-1 text-center">
                                {title && <h1 className="text-xl font-semibold tracking-tight">{title}</h1>}
                                {description && (
                                    <p className="text-center text-xs text-muted-foreground">
                                        {description}
                                    </p>
                                )}
                            </div>
                        )}
                    </div>
                    {children}
                </div>
            </div>
        </div>
    );
}
