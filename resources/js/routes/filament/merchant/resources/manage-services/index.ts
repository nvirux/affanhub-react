import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\StoreServiceResource\Pages\ListStoreServices::__invoke
 * @see app/Filament/Merchant/Resources/StoreServiceResource/Pages/ListStoreServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
export const index = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/manage-services',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\StoreServiceResource\Pages\ListStoreServices::__invoke
 * @see app/Filament/Merchant/Resources/StoreServiceResource/Pages/ListStoreServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
index.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return index.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\StoreServiceResource\Pages\ListStoreServices::__invoke
 * @see app/Filament/Merchant/Resources/StoreServiceResource/Pages/ListStoreServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
index.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\StoreServiceResource\Pages\ListStoreServices::__invoke
 * @see app/Filament/Merchant/Resources/StoreServiceResource/Pages/ListStoreServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
index.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})
const manageServices = {
    index: Object.assign(index, index),
}

export default manageServices