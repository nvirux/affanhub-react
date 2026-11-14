import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\ModificationRequestResource\Pages\ListModificationRequests::__invoke
 * @see app/Filament/Merchant/Resources/ModificationRequestResource/Pages/ListModificationRequests.php:7
 * @route '//merchant.localhost/{tenant}/modification-requests'
 */
const ListModificationRequests = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListModificationRequests.url(args, options),
    method: 'get',
})

ListModificationRequests.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/modification-requests',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\ModificationRequestResource\Pages\ListModificationRequests::__invoke
 * @see app/Filament/Merchant/Resources/ModificationRequestResource/Pages/ListModificationRequests.php:7
 * @route '//merchant.localhost/{tenant}/modification-requests'
 */
ListModificationRequests.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return ListModificationRequests.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\ModificationRequestResource\Pages\ListModificationRequests::__invoke
 * @see app/Filament/Merchant/Resources/ModificationRequestResource/Pages/ListModificationRequests.php:7
 * @route '//merchant.localhost/{tenant}/modification-requests'
 */
ListModificationRequests.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListModificationRequests.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\ModificationRequestResource\Pages\ListModificationRequests::__invoke
 * @see app/Filament/Merchant/Resources/ModificationRequestResource/Pages/ListModificationRequests.php:7
 * @route '//merchant.localhost/{tenant}/modification-requests'
 */
ListModificationRequests.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListModificationRequests.url(args, options),
    method: 'head',
})
export default ListModificationRequests