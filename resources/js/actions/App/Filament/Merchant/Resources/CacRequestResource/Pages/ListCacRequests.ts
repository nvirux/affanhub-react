import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\CacRequestResource\Pages\ListCacRequests::__invoke
 * @see app/Filament/Merchant/Resources/CacRequestResource/Pages/ListCacRequests.php:7
 * @route '//merchant.localhost/{tenant}/cac-requests'
 */
const ListCacRequests = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCacRequests.url(args, options),
    method: 'get',
})

ListCacRequests.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/cac-requests',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\CacRequestResource\Pages\ListCacRequests::__invoke
 * @see app/Filament/Merchant/Resources/CacRequestResource/Pages/ListCacRequests.php:7
 * @route '//merchant.localhost/{tenant}/cac-requests'
 */
ListCacRequests.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return ListCacRequests.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\CacRequestResource\Pages\ListCacRequests::__invoke
 * @see app/Filament/Merchant/Resources/CacRequestResource/Pages/ListCacRequests.php:7
 * @route '//merchant.localhost/{tenant}/cac-requests'
 */
ListCacRequests.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCacRequests.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\CacRequestResource\Pages\ListCacRequests::__invoke
 * @see app/Filament/Merchant/Resources/CacRequestResource/Pages/ListCacRequests.php:7
 * @route '//merchant.localhost/{tenant}/cac-requests'
 */
ListCacRequests.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListCacRequests.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\CacRequestResource\Pages\ListCacRequests::__invoke
 * @see app/Filament/Merchant/Resources/CacRequestResource/Pages/ListCacRequests.php:7
 * @route '//merchant.localhost/{tenant}/cac-requests'
 */
    const ListCacRequestsForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListCacRequests.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\CacRequestResource\Pages\ListCacRequests::__invoke
 * @see app/Filament/Merchant/Resources/CacRequestResource/Pages/ListCacRequests.php:7
 * @route '//merchant.localhost/{tenant}/cac-requests'
 */
        ListCacRequestsForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListCacRequests.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\CacRequestResource\Pages\ListCacRequests::__invoke
 * @see app/Filament/Merchant/Resources/CacRequestResource/Pages/ListCacRequests.php:7
 * @route '//merchant.localhost/{tenant}/cac-requests'
 */
        ListCacRequestsForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListCacRequests.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListCacRequests.form = ListCacRequestsForm
export default ListCacRequests