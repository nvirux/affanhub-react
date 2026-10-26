import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Pages\MobileAppManager::__invoke
 * @see app/Filament/Merchant/Pages/MobileAppManager.php:7
 * @route '//merchant.localhost/{tenant}/mobile-app-manager'
 */
const MobileAppManager = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MobileAppManager.url(args, options),
    method: 'get',
})

MobileAppManager.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/mobile-app-manager',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\MobileAppManager::__invoke
 * @see app/Filament/Merchant/Pages/MobileAppManager.php:7
 * @route '//merchant.localhost/{tenant}/mobile-app-manager'
 */
MobileAppManager.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return MobileAppManager.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\MobileAppManager::__invoke
 * @see app/Filament/Merchant/Pages/MobileAppManager.php:7
 * @route '//merchant.localhost/{tenant}/mobile-app-manager'
 */
MobileAppManager.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MobileAppManager.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\MobileAppManager::__invoke
 * @see app/Filament/Merchant/Pages/MobileAppManager.php:7
 * @route '//merchant.localhost/{tenant}/mobile-app-manager'
 */
MobileAppManager.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: MobileAppManager.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\MobileAppManager::__invoke
 * @see app/Filament/Merchant/Pages/MobileAppManager.php:7
 * @route '//merchant.localhost/{tenant}/mobile-app-manager'
 */
    const MobileAppManagerForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: MobileAppManager.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\MobileAppManager::__invoke
 * @see app/Filament/Merchant/Pages/MobileAppManager.php:7
 * @route '//merchant.localhost/{tenant}/mobile-app-manager'
 */
        MobileAppManagerForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: MobileAppManager.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\MobileAppManager::__invoke
 * @see app/Filament/Merchant/Pages/MobileAppManager.php:7
 * @route '//merchant.localhost/{tenant}/mobile-app-manager'
 */
        MobileAppManagerForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: MobileAppManager.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    MobileAppManager.form = MobileAppManagerForm
export default MobileAppManager