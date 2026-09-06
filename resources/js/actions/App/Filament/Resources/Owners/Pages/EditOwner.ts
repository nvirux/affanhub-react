import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Owners\Pages\EditOwner::__invoke
 * @see app/Filament/Resources/Owners/Pages/EditOwner.php:7
 * @route '//admin.localhost/owners/{record}/edit'
 */
const EditOwner = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditOwner.url(args, options),
    method: 'get',
})

EditOwner.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/owners/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Owners\Pages\EditOwner::__invoke
 * @see app/Filament/Resources/Owners/Pages/EditOwner.php:7
 * @route '//admin.localhost/owners/{record}/edit'
 */
EditOwner.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditOwner.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\Owners\Pages\EditOwner::__invoke
 * @see app/Filament/Resources/Owners/Pages/EditOwner.php:7
 * @route '//admin.localhost/owners/{record}/edit'
 */
EditOwner.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditOwner.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Owners\Pages\EditOwner::__invoke
 * @see app/Filament/Resources/Owners/Pages/EditOwner.php:7
 * @route '//admin.localhost/owners/{record}/edit'
 */
EditOwner.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditOwner.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Owners\Pages\EditOwner::__invoke
 * @see app/Filament/Resources/Owners/Pages/EditOwner.php:7
 * @route '//admin.localhost/owners/{record}/edit'
 */
    const EditOwnerForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditOwner.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Owners\Pages\EditOwner::__invoke
 * @see app/Filament/Resources/Owners/Pages/EditOwner.php:7
 * @route '//admin.localhost/owners/{record}/edit'
 */
        EditOwnerForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditOwner.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Owners\Pages\EditOwner::__invoke
 * @see app/Filament/Resources/Owners/Pages/EditOwner.php:7
 * @route '//admin.localhost/owners/{record}/edit'
 */
        EditOwnerForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditOwner.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditOwner.form = EditOwnerForm
export default EditOwner