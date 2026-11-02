@php
    $siteKey = config('services.turnstile.site_key');
    $statePath = $getStatePath() ?? 'turnstile_token';
@endphp

@if(filled($siteKey))
    <div
        wire:ignore
        x-data="{
            sitekey: @js($siteKey),
            widgetId: null,
            init() {
                if (!this.sitekey) return;

                const renderTurnstile = () => {
                    if (window.turnstile && this.$refs.turnstileContainer && !this.widgetId) {
                        try {
                            this.widgetId = window.turnstile.render(this.$refs.turnstileContainer, {
                                sitekey: this.sitekey,
                                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                                action: 'merchant_auth',
                                callback: (token) => {
                                    $wire.set(@js($statePath), token);
                                },
                                'expired-callback': () => {
                                    $wire.set(@js($statePath), null);
                                },
                                'error-callback': () => {
                                    $wire.set(@js($statePath), null);
                                }
                            });
                        } catch (e) {
                            console.error('Turnstile render error:', e);
                        }
                    }
                };

                if (window.turnstile) {
                    renderTurnstile();
                } else {
                    const existing = document.getElementById('cf-turnstile-script');
                    if (!existing) {
                        const script = document.createElement('script');
                        script.id = 'cf-turnstile-script';
                        script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit';
                        script.async = true;
                        script.defer = true;
                        script.onload = () => setTimeout(renderTurnstile, 50);
                        document.head.appendChild(script);
                    } else {
                        existing.addEventListener('load', () => setTimeout(renderTurnstile, 50));
                    }
                }
            }
        }"
        class="flex justify-center my-2"
    >
        <div x-ref="turnstileContainer"></div>
    </div>
@endif
