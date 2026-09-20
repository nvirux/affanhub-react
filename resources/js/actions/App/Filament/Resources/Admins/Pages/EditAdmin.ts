import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Admins\Pages\EditAdmin::__invoke
 * @see app/Filament/Resources/Admins/Pages/EditAdmin.php:7
 * @route '//admin.localhost/admins/{record}/edit'
 */
const EditAdmin = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditAdmin.url(args, options),
    method: 'get',
})

EditAdmin.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/admins/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Admins\Pages\EditAdmin::__invoke
 * @see app/Filament/Resources/Admins/Pages/EditAdmin.php:7
 * @route '//admin.localhost/admins/{record}/edit'
 */
EditAdmin.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditAdmin.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\Admins\Pages\EditAdmin::__invoke
 * @see app/Filament/Resources/Admins/Pages/EditAdmin.php:7
 * @route '//admin.localhost/admins/{record}/edit'
 */
EditAdmin.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditAdmin.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Admins\Pages\EditAdmin::__invoke
 * @see app/Filament/Resources/Admins/Pages/EditAdmin.php:7
 * @route '//admin.localhost/admins/{record}/edit'
 */
EditAdmin.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditAdmin.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Admins\Pages\EditAdmin::__invoke
 * @see app/Filament/Resources/Admins/Pages/EditAdmin.php:7
 * @route '//admin.localhost/admins/{record}/edit'
 */
    const EditAdminForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditAdmin.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Admins\Pages\EditAdmin::__invoke
 * @see app/Filament/Resources/Admins/Pages/EditAdmin.php:7
 * @route '//admin.localhost/admins/{record}/edit'
 */
        EditAdminForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditAdmin.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Admins\Pages\EditAdmin::__invoke
 * @see app/Filament/Resources/Admins/Pages/EditAdmin.php:7
 * @route '//admin.localhost/admins/{record}/edit'
 */
        EditAdminForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditAdmin.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditAdmin.form = EditAdminForm
export default EditAdmin