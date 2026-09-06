import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
const ManageServices = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
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
ManageServices.url = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ManageServices.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
ManageServices.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ManageServices.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
ManageServices.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ManageServices.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
    const ManageServicesForm = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ManageServices.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
        ManageServicesForm.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ManageServices.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
        ManageServicesForm.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ManageServices.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ManageServices.form = ManageServicesForm
export default ManageServices