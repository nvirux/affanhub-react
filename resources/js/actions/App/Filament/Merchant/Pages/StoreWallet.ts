import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Pages\StoreWallet::__invoke
 * @see app/Filament/Merchant/Pages/StoreWallet.php:7
 * @route '//merchant.localhost/{tenant}/store-wallet'
 */
const StoreWallet = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: StoreWallet.url(args, options),
    method: 'get',
})

StoreWallet.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/store-wallet',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\StoreWallet::__invoke
 * @see app/Filament/Merchant/Pages/StoreWallet.php:7
 * @route '//merchant.localhost/{tenant}/store-wallet'
 */
StoreWallet.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'public_id' in args) {
            args = { tenant: args.public_id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: typeof args.tenant === 'object'
                ? args.tenant.public_id
                : args.tenant,
                }

    return StoreWallet.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\StoreWallet::__invoke
 * @see app/Filament/Merchant/Pages/StoreWallet.php:7
 * @route '//merchant.localhost/{tenant}/store-wallet'
 */
StoreWallet.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: StoreWallet.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\StoreWallet::__invoke
 * @see app/Filament/Merchant/Pages/StoreWallet.php:7
 * @route '//merchant.localhost/{tenant}/store-wallet'
 */
StoreWallet.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: StoreWallet.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\StoreWallet::__invoke
 * @see app/Filament/Merchant/Pages/StoreWallet.php:7
 * @route '//merchant.localhost/{tenant}/store-wallet'
 */
    const StoreWalletForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: StoreWallet.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\StoreWallet::__invoke
 * @see app/Filament/Merchant/Pages/StoreWallet.php:7
 * @route '//merchant.localhost/{tenant}/store-wallet'
 */
        StoreWalletForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: StoreWallet.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\StoreWallet::__invoke
 * @see app/Filament/Merchant/Pages/StoreWallet.php:7
 * @route '//merchant.localhost/{tenant}/store-wallet'
 */
        StoreWalletForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: StoreWallet.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    StoreWallet.form = StoreWalletForm
export default StoreWallet