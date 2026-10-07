@props(['domain', 'fallbackCname' => 'sites.affanhub.com', 'isCloudflare' => false])

<div class="space-y-4 text-sm">
    @if($isCloudflare)
        <div class="p-4 bg-orange-50 dark:bg-orange-950/40 border border-orange-200 dark:border-orange-800 rounded-xl space-y-3">
            <div class="flex items-center gap-2 font-bold text-orange-900 dark:text-orange-200">
                <svg class="w-5 h-5 text-orange-600 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM19 18H6c-2.21 0-4-1.79-4-4 0-2.05 1.53-3.76 3.56-3.97l1.07-.11.5-.95C8.08 7.14 9.94 6 12 6c2.62 0 4.88 1.86 5.39 4.43l.3 1.5 1.53.11c1.56.1 2.78 1.41 2.78 2.96 0 1.65-1.35 3-3 3z"/>
                </svg>
                <span>⚡ Cloudflare DNS Detected</span>
            </div>
            <p class="text-xs text-orange-800 dark:text-orange-300 leading-relaxed">
                We detected that <strong>{{ $domain->domain }}</strong> is hosted on Cloudflare. You can connect your Cloudflare account in 1-Click to automatically set up routing and SSL:
            </p>
            <div>
                <a href="{{ route('merchant.cloudflare.connect', ['domain' => $domain->id]) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition shadow-sm">
                    <span>⚡ Continue with Cloudflare</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    @endif

    <div class="space-y-1">
        <h4 class="font-semibold text-gray-900 dark:text-white">Manual DNS Configuration</h4>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Log in to your DNS provider (Cloudflare, Namecheap, GoDaddy, etc.) and add the following CNAME record:
        </p>
    </div>

    <div class="overflow-hidden border border-gray-200 dark:border-gray-800 rounded-xl bg-gray-50 dark:bg-gray-900/50">
        <table class="w-full text-left text-xs">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 uppercase font-semibold">
                <tr>
                    <th class="px-3 py-2.5">Type</th>
                    <th class="px-3 py-2.5">Host / Name</th>
                    <th class="px-3 py-2.5">Points To / Target</th>
                    <th class="px-3 py-2.5">Proxy Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800 text-gray-900 dark:text-gray-100">
                <tr>
                    <td class="px-3 py-2.5 font-bold text-amber-600">CNAME</td>
                    <td class="px-3 py-2.5">
                        <code class="px-1.5 py-0.5 rounded bg-gray-200 dark:bg-gray-800 text-xs font-mono font-bold">@</code>
                        <span class="text-gray-400 text-[10px] ml-1">(or root)</span>
                    </td>
                    <td class="px-3 py-2.5">
                        <code class="px-1.5 py-0.5 rounded bg-amber-100 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 font-mono font-bold text-xs">{{ $fallbackCname }}</code>
                    </td>
                    <td class="px-3 py-2.5 text-xs text-gray-600 dark:text-gray-400">
                        Proxied or DNS Only
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($domain->verification_error)
        <div class="p-2.5 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 rounded-lg text-xs text-red-700 dark:text-red-300">
            ⚠️ <strong>Last Attempt:</strong> {{ $domain->last_verification_message ?? $domain->verification_error }}
        </div>
    @endif

    <div class="text-[11px] text-gray-500 dark:text-gray-400">
        💡 <em>Free SSL certificates are automatically provisioned and managed by Cloudflare for SaaS as soon as DNS traffic reaches the platform.</em>
    </div>
</div>
