import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Pages\WholesaleRates::__invoke
 * @see app/Filament/Merchant/Pages/WholesaleRates.php:7
 * @route '//merchant.localhost/{tenant}/wholesale-rates'
 */
const WholesaleRates = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: WholesaleRates.url(args, options),
    method: 'get',
})

WholesaleRates.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/wholesale-rates',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\WholesaleRates::__invoke
 * @see app/Filament/Merchant/Pages/WholesaleRates.php:7
 * @route '//merchant.localhost/{tenant}/wholesale-rates'
 */
WholesaleRates.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return WholesaleRates.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\WholesaleRates::__invoke
 * @see app/Filament/Merchant/Pages/WholesaleRates.php:7
 * @route '//merchant.localhost/{tenant}/wholesale-rates'
 */
WholesaleRates.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: WholesaleRates.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\WholesaleRates::__invoke
 * @see app/Filament/Merchant/Pages/WholesaleRates.php:7
 * @route '//merchant.localhost/{tenant}/wholesale-rates'
 */
WholesaleRates.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: WholesaleRates.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\WholesaleRates::__invoke
 * @see app/Filament/Merchant/Pages/WholesaleRates.php:7
 * @route '//merchant.localhost/{tenant}/wholesale-rates'
 */
    const WholesaleRatesForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: WholesaleRates.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\WholesaleRates::__invoke
 * @see app/Filament/Merchant/Pages/WholesaleRates.php:7
 * @route '//merchant.localhost/{tenant}/wholesale-rates'
 */
        WholesaleRatesForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: WholesaleRates.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\WholesaleRates::__invoke
 * @see app/Filament/Merchant/Pages/WholesaleRates.php:7
 * @route '//merchant.localhost/{tenant}/wholesale-rates'
 */
        WholesaleRatesForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: WholesaleRates.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    WholesaleRates.form = WholesaleRatesForm
export default WholesaleRates