<x-filament-panels::page.simple>
    <div class="mt-4 flex flex-col gap-y-6">
        <form wire:submit.prevent="verifyCode" class="space-y-6">
            <div>
                <label for="otp-code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 text-center mb-3">
                    Enter your 6-digit verification code
                </label>
                <div class="flex justify-center">
                    <input
                        id="otp-code"
                        type="text"
                        wire:model="code"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        autocomplete="one-time-code"
                        autofocus
                        placeholder="••••••"
                        class="w-64 text-center text-3xl font-mono tracking-[0.4em] font-bold rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all outline-none"
                    />
                </div>
                @error('code')
                    <p class="text-center text-sm text-red-600 dark:text-red-400 mt-2 font-medium">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full py-3 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white font-semibold text-base shadow-sm hover:shadow transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove wire:target="verifyCode">
                        Verify Account
                    </span>
                    <span wire:loading wire:target="verifyCode" class="inline-flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Verifying...
                    </span>
                </button>
            </div>
        </form>

        <div class="flex flex-col items-center gap-3 pt-4 text-center text-sm text-gray-600 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800">
            <div>
                Didn't receive the code?
                <button
                    type="button"
                    wire:click="resendCode"
                    wire:loading.attr="disabled"
                    class="text-amber-600 dark:text-amber-400 font-semibold hover:underline inline-flex items-center gap-1 cursor-pointer disabled:opacity-50 ml-1"
                >
                    <span wire:loading.remove wire:target="resendCode">Resend Code</span>
                    <span wire:loading wire:target="resendCode">Sending...</span>
                </button>
            </div>

            <div>
                Wrong email address?
                <button
                    type="button"
                    wire:click="logout"
                    class="text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 underline cursor-pointer text-xs ml-1"
                >
                    Sign out
                </button>
            </div>
        </div>
    </div>
</x-filament-panels::page.simple>
