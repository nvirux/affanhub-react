import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\StoreDataPlanResource\Pages\ListStoreDataPlans::__invoke
 * @see app/Filament/Merchant/Resources/StoreDataPlanResource/Pages/ListStoreDataPlans.php:7
 * @route '//merchant.localhost/{tenant}/store-data-plans'
 */
export const index = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/store-data-plans',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\StoreDataPlanResource\Pages\ListStoreDataPlans::__invoke
 * @see app/Filament/Merchant/Resources/StoreDataPlanResource/Pages/ListStoreDataPlans.php:7
 * @route '//merchant.localhost/{tenant}/store-data-plans'
 */
index.url = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return index.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\StoreDataPlanResource\Pages\ListStoreDataPlans::__invoke
 * @see app/Filament/Merchant/Resources/StoreDataPlanResource/Pages/ListStoreDataPlans.php:7
 * @route '//merchant.localhost/{tenant}/store-data-plans'
 */
index.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\StoreDataPlanResource\Pages\ListStoreDataPlans::__invoke
 * @see app/Filament/Merchant/Resources/StoreDataPlanResource/Pages/ListStoreDataPlans.php:7
 * @route '//merchant.localhost/{tenant}/store-data-plans'
 */
index.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\StoreDataPlanResource\Pages\ListStoreDataPlans::__invoke
 * @see app/Filament/Merchant/Resources/StoreDataPlanResource/Pages/ListStoreDataPlans.php:7
 * @route '//merchant.localhost/{tenant}/store-data-plans'
 */
    const indexForm = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\StoreDataPlanResource\Pages\ListStoreDataPlans::__invoke
 * @see app/Filament/Merchant/Resources/StoreDataPlanResource/Pages/ListStoreDataPlans.php:7
 * @route '//merchant.localhost/{tenant}/store-data-plans'
 */
        indexForm.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\StoreDataPlanResource\Pages\ListStoreDataPlans::__invoke
 * @see app/Filament/Merchant/Resources/StoreDataPlanResource/Pages/ListStoreDataPlans.php:7
 * @route '//merchant.localhost/{tenant}/store-data-plans'
 */
        indexForm.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
const storeDataPlans = {
    index: Object.assign(index, index),
}

export default storeDataPlans