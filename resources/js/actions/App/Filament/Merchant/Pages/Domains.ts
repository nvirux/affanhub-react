import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Pages\Domains::__invoke
 * @see app/Filament/Merchant/Pages/Domains.php:7
 * @route '//merchant.localhost/{tenant}/domains'
 */
const Domains = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Domains.url(args, options),
    method: 'get',
})

Domains.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/domains',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\Domains::__invoke
 * @see app/Filament/Merchant/Pages/Domains.php:7
 * @route '//merchant.localhost/{tenant}/domains'
 */
Domains.url = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: args.tenant,
                }

    return Domains.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\Domains::__invoke
 * @see app/Filament/Merchant/Pages/Domains.php:7
 * @route '//merchant.localhost/{tenant}/domains'
 */
Domains.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Domains.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\Domains::__invoke
 * @see app/Filament/Merchant/Pages/Domains.php:7
 * @route '//merchant.localhost/{tenant}/domains'
 */
Domains.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Domains.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\Domains::__invoke
 * @see app/Filament/Merchant/Pages/Domains.php:7
 * @route '//merchant.localhost/{tenant}/domains'
 */
    const DomainsForm = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: Domains.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\Domains::__invoke
 * @see app/Filament/Merchant/Pages/Domains.php:7
 * @route '//merchant.localhost/{tenant}/domains'
 */
        DomainsForm.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: Domains.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\Domains::__invoke
 * @see app/Filament/Merchant/Pages/Domains.php:7
 * @route '//merchant.localhost/{tenant}/domains'
 */
        DomainsForm.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: Domains.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    Domains.form = DomainsForm
export default Domains