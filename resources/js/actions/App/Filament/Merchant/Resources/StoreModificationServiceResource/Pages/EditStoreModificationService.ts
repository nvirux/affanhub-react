import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\EditStoreModificationService::__invoke
 * @see app/Filament/Merchant/Resources/StoreModificationServiceResource/Pages/EditStoreModificationService.php:7
 * @route '//merchant.localhost/{tenant}/store-modification-services/{record}/edit'
 */
const EditStoreModificationService = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditStoreModificationService.url(args, options),
    method: 'get',
})

EditStoreModificationService.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/store-modification-services/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\EditStoreModificationService::__invoke
 * @see app/Filament/Merchant/Resources/StoreModificationServiceResource/Pages/EditStoreModificationService.php:7
 * @route '//merchant.localhost/{tenant}/store-modification-services/{record}/edit'
 */
EditStoreModificationService.url = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions) => {
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

    return EditStoreModificationService.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\EditStoreModificationService::__invoke
 * @see app/Filament/Merchant/Resources/StoreModificationServiceResource/Pages/EditStoreModificationService.php:7
 * @route '//merchant.localhost/{tenant}/store-modification-services/{record}/edit'
 */
EditStoreModificationService.get = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditStoreModificationService.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\EditStoreModificationService::__invoke
 * @see app/Filament/Merchant/Resources/StoreModificationServiceResource/Pages/EditStoreModificationService.php:7
 * @route '//merchant.localhost/{tenant}/store-modification-services/{record}/edit'
 */
EditStoreModificationService.head = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditStoreModificationService.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\EditStoreModificationService::__invoke
 * @see app/Filament/Merchant/Resources/StoreModificationServiceResource/Pages/EditStoreModificationService.php:7
 * @route '//merchant.localhost/{tenant}/store-modification-services/{record}/edit'
 */
    const EditStoreModificationServiceForm = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditStoreModificationService.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\EditStoreModificationService::__invoke
 * @see app/Filament/Merchant/Resources/StoreModificationServiceResource/Pages/EditStoreModificationService.php:7
 * @route '//merchant.localhost/{tenant}/store-modification-services/{record}/edit'
 */
        EditStoreModificationServiceForm.get = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditStoreModificationService.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\EditStoreModificationService::__invoke
 * @see app/Filament/Merchant/Resources/StoreModificationServiceResource/Pages/EditStoreModificationService.php:7
 * @route '//merchant.localhost/{tenant}/store-modification-services/{record}/edit'
 */
        EditStoreModificationServiceForm.head = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditStoreModificationService.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditStoreModificationService.form = EditStoreModificationServiceForm
export default EditStoreModificationService