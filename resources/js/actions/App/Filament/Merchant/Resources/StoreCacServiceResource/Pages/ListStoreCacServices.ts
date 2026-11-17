import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\StoreCacServiceResource\Pages\ListStoreCacServices::__invoke
 * @see app/Filament/Merchant/Resources/StoreCacServiceResource/Pages/ListStoreCacServices.php:7
 * @route '//merchant.localhost/{tenant}/cac-services'
 */
const ListStoreCacServices = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListStoreCacServices.url(args, options),
    method: 'get',
})

ListStoreCacServices.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/cac-services',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\StoreCacServiceResource\Pages\ListStoreCacServices::__invoke
 * @see app/Filament/Merchant/Resources/StoreCacServiceResource/Pages/ListStoreCacServices.php:7
 * @route '//merchant.localhost/{tenant}/cac-services'
 */
ListStoreCacServices.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return ListStoreCacServices.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\StoreCacServiceResource\Pages\ListStoreCacServices::__invoke
 * @see app/Filament/Merchant/Resources/StoreCacServiceResource/Pages/ListStoreCacServices.php:7
 * @route '//merchant.localhost/{tenant}/cac-services'
 */
ListStoreCacServices.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListStoreCacServices.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\StoreCacServiceResource\Pages\ListStoreCacServices::__invoke
 * @see app/Filament/Merchant/Resources/StoreCacServiceResource/Pages/ListStoreCacServices.php:7
 * @route '//merchant.localhost/{tenant}/cac-services'
 */
ListStoreCacServices.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListStoreCacServices.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\StoreCacServiceResource\Pages\ListStoreCacServices::__invoke
 * @see app/Filament/Merchant/Resources/StoreCacServiceResource/Pages/ListStoreCacServices.php:7
 * @route '//merchant.localhost/{tenant}/cac-services'
 */
    const ListStoreCacServicesForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListStoreCacServices.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\StoreCacServiceResource\Pages\ListStoreCacServices::__invoke
 * @see app/Filament/Merchant/Resources/StoreCacServiceResource/Pages/ListStoreCacServices.php:7
 * @route '//merchant.localhost/{tenant}/cac-services'
 */
        ListStoreCacServicesForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListStoreCacServices.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\StoreCacServiceResource\Pages\ListStoreCacServices::__invoke
 * @see app/Filament/Merchant/Resources/StoreCacServiceResource/Pages/ListStoreCacServices.php:7
 * @route '//merchant.localhost/{tenant}/cac-services'
 */
        ListStoreCacServicesForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListStoreCacServices.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListStoreCacServices.form = ListStoreCacServicesForm
export default ListStoreCacServices