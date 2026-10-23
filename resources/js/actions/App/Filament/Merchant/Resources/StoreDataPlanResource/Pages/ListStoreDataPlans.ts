import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\StoreDataPlanResource\Pages\ListStoreDataPlans::__invoke
 * @see app/Filament/Merchant/Resources/StoreDataPlanResource/Pages/ListStoreDataPlans.php:7
 * @route '//merchant.localhost/{tenant}/store-data-plans'
 */
const ListStoreDataPlans = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListStoreDataPlans.url(args, options),
    method: 'get',
})

ListStoreDataPlans.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/store-data-plans',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\StoreDataPlanResource\Pages\ListStoreDataPlans::__invoke
 * @see app/Filament/Merchant/Resources/StoreDataPlanResource/Pages/ListStoreDataPlans.php:7
 * @route '//merchant.localhost/{tenant}/store-data-plans'
 */
ListStoreDataPlans.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return ListStoreDataPlans.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\StoreDataPlanResource\Pages\ListStoreDataPlans::__invoke
 * @see app/Filament/Merchant/Resources/StoreDataPlanResource/Pages/ListStoreDataPlans.php:7
 * @route '//merchant.localhost/{tenant}/store-data-plans'
 */
ListStoreDataPlans.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListStoreDataPlans.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\StoreDataPlanResource\Pages\ListStoreDataPlans::__invoke
 * @see app/Filament/Merchant/Resources/StoreDataPlanResource/Pages/ListStoreDataPlans.php:7
 * @route '//merchant.localhost/{tenant}/store-data-plans'
 */
ListStoreDataPlans.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListStoreDataPlans.url(args, options),
    method: 'head',
})
export default ListStoreDataPlans