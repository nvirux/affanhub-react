import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Pages\StoreLayoutSettings::__invoke
 * @see app/Filament/Merchant/Pages/StoreLayoutSettings.php:7
 * @route '//merchant.localhost/{tenant}/service-layout'
 */
const StoreLayoutSettings = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: StoreLayoutSettings.url(args, options),
    method: 'get',
})

StoreLayoutSettings.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/service-layout',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\StoreLayoutSettings::__invoke
 * @see app/Filament/Merchant/Pages/StoreLayoutSettings.php:7
 * @route '//merchant.localhost/{tenant}/service-layout'
 */
StoreLayoutSettings.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return StoreLayoutSettings.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\StoreLayoutSettings::__invoke
 * @see app/Filament/Merchant/Pages/StoreLayoutSettings.php:7
 * @route '//merchant.localhost/{tenant}/service-layout'
 */
StoreLayoutSettings.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: StoreLayoutSettings.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\StoreLayoutSettings::__invoke
 * @see app/Filament/Merchant/Pages/StoreLayoutSettings.php:7
 * @route '//merchant.localhost/{tenant}/service-layout'
 */
StoreLayoutSettings.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: StoreLayoutSettings.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\StoreLayoutSettings::__invoke
 * @see app/Filament/Merchant/Pages/StoreLayoutSettings.php:7
 * @route '//merchant.localhost/{tenant}/service-layout'
 */
    const StoreLayoutSettingsForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: StoreLayoutSettings.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\StoreLayoutSettings::__invoke
 * @see app/Filament/Merchant/Pages/StoreLayoutSettings.php:7
 * @route '//merchant.localhost/{tenant}/service-layout'
 */
        StoreLayoutSettingsForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: StoreLayoutSettings.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\StoreLayoutSettings::__invoke
 * @see app/Filament/Merchant/Pages/StoreLayoutSettings.php:7
 * @route '//merchant.localhost/{tenant}/service-layout'
 */
        StoreLayoutSettingsForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: StoreLayoutSettings.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    StoreLayoutSettings.form = StoreLayoutSettingsForm
export default StoreLayoutSettings