import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\StoreCacServiceResource\Pages\EditStoreCacService::__invoke
 * @see app/Filament/Merchant/Resources/StoreCacServiceResource/Pages/EditStoreCacService.php:7
 * @route '//merchant.localhost/{tenant}/cac-services/{record}/edit'
 */
const EditStoreCacService = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditStoreCacService.url(args, options),
    method: 'get',
})

EditStoreCacService.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/cac-services/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\StoreCacServiceResource\Pages\EditStoreCacService::__invoke
 * @see app/Filament/Merchant/Resources/StoreCacServiceResource/Pages/EditStoreCacService.php:7
 * @route '//merchant.localhost/{tenant}/cac-services/{record}/edit'
 */
EditStoreCacService.url = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                    record: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: typeof args.tenant === 'object'
                ? args.tenant.public_id
                : args.tenant,
                                record: args.record,
                }

    return EditStoreCacService.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\StoreCacServiceResource\Pages\EditStoreCacService::__invoke
 * @see app/Filament/Merchant/Resources/StoreCacServiceResource/Pages/EditStoreCacService.php:7
 * @route '//merchant.localhost/{tenant}/cac-services/{record}/edit'
 */
EditStoreCacService.get = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditStoreCacService.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\StoreCacServiceResource\Pages\EditStoreCacService::__invoke
 * @see app/Filament/Merchant/Resources/StoreCacServiceResource/Pages/EditStoreCacService.php:7
 * @route '//merchant.localhost/{tenant}/cac-services/{record}/edit'
 */
EditStoreCacService.head = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditStoreCacService.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\StoreCacServiceResource\Pages\EditStoreCacService::__invoke
 * @see app/Filament/Merchant/Resources/StoreCacServiceResource/Pages/EditStoreCacService.php:7
 * @route '//merchant.localhost/{tenant}/cac-services/{record}/edit'
 */
    const EditStoreCacServiceForm = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditStoreCacService.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\StoreCacServiceResource\Pages\EditStoreCacService::__invoke
 * @see app/Filament/Merchant/Resources/StoreCacServiceResource/Pages/EditStoreCacService.php:7
 * @route '//merchant.localhost/{tenant}/cac-services/{record}/edit'
 */
        EditStoreCacServiceForm.get = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditStoreCacService.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\StoreCacServiceResource\Pages\EditStoreCacService::__invoke
 * @see app/Filament/Merchant/Resources/StoreCacServiceResource/Pages/EditStoreCacService.php:7
 * @route '//merchant.localhost/{tenant}/cac-services/{record}/edit'
 */
        EditStoreCacServiceForm.head = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditStoreCacService.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditStoreCacService.form = EditStoreCacServiceForm
export default EditStoreCacService