import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\ListStoreModificationServices::__invoke
 * @see app/Filament/Merchant/Resources/StoreModificationServiceResource/Pages/ListStoreModificationServices.php:7
 * @route '//merchant.localhost/{tenant}/modification-services'
 */
export const index = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/modification-services',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\ListStoreModificationServices::__invoke
 * @see app/Filament/Merchant/Resources/StoreModificationServiceResource/Pages/ListStoreModificationServices.php:7
 * @route '//merchant.localhost/{tenant}/modification-services'
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
* @see \App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\ListStoreModificationServices::__invoke
 * @see app/Filament/Merchant/Resources/StoreModificationServiceResource/Pages/ListStoreModificationServices.php:7
 * @route '//merchant.localhost/{tenant}/modification-services'
 */
index.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\ListStoreModificationServices::__invoke
 * @see app/Filament/Merchant/Resources/StoreModificationServiceResource/Pages/ListStoreModificationServices.php:7
 * @route '//merchant.localhost/{tenant}/modification-services'
 */
index.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\EditStoreModificationService::__invoke
 * @see app/Filament/Merchant/Resources/StoreModificationServiceResource/Pages/EditStoreModificationService.php:7
 * @route '//merchant.localhost/{tenant}/modification-services/{record}/edit'
 */
export const edit = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/modification-services/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\EditStoreModificationService::__invoke
 * @see app/Filament/Merchant/Resources/StoreModificationServiceResource/Pages/EditStoreModificationService.php:7
 * @route '//merchant.localhost/{tenant}/modification-services/{record}/edit'
 */
edit.url = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\EditStoreModificationService::__invoke
 * @see app/Filament/Merchant/Resources/StoreModificationServiceResource/Pages/EditStoreModificationService.php:7
 * @route '//merchant.localhost/{tenant}/modification-services/{record}/edit'
 */
edit.get = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\EditStoreModificationService::__invoke
 * @see app/Filament/Merchant/Resources/StoreModificationServiceResource/Pages/EditStoreModificationService.php:7
 * @route '//merchant.localhost/{tenant}/modification-services/{record}/edit'
 */
edit.head = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})
const modificationServices = {
    index: Object.assign(index, index),
edit: Object.assign(edit, edit),
}

export default modificationServices