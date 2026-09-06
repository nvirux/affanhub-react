import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Stores\Pages\EditStore::__invoke
 * @see app/Filament/Resources/Stores/Pages/EditStore.php:7
 * @route '//admin.localhost/stores/{record}/edit'
 */
const EditStore = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditStore.url(args, options),
    method: 'get',
})

EditStore.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/stores/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Stores\Pages\EditStore::__invoke
 * @see app/Filament/Resources/Stores/Pages/EditStore.php:7
 * @route '//admin.localhost/stores/{record}/edit'
 */
EditStore.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { record: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    record: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        record: args.record,
                }

    return EditStore.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\Stores\Pages\EditStore::__invoke
 * @see app/Filament/Resources/Stores/Pages/EditStore.php:7
 * @route '//admin.localhost/stores/{record}/edit'
 */
EditStore.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditStore.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Stores\Pages\EditStore::__invoke
 * @see app/Filament/Resources/Stores/Pages/EditStore.php:7
 * @route '//admin.localhost/stores/{record}/edit'
 */
EditStore.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditStore.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Stores\Pages\EditStore::__invoke
 * @see app/Filament/Resources/Stores/Pages/EditStore.php:7
 * @route '//admin.localhost/stores/{record}/edit'
 */
    const EditStoreForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditStore.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Stores\Pages\EditStore::__invoke
 * @see app/Filament/Resources/Stores/Pages/EditStore.php:7
 * @route '//admin.localhost/stores/{record}/edit'
 */
        EditStoreForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditStore.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Stores\Pages\EditStore::__invoke
 * @see app/Filament/Resources/Stores/Pages/EditStore.php:7
 * @route '//admin.localhost/stores/{record}/edit'
 */
        EditStoreForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditStore.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditStore.form = EditStoreForm
export default EditStore