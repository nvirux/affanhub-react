import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
const ManageServices = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ManageServices.url(args, options),
    method: 'get',
})

ManageServices.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/manage-services',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
ManageServices.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return ManageServices.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
ManageServices.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ManageServices.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
ManageServices.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ManageServices.url(args, options),
    method: 'head',
})
export default ManageServices