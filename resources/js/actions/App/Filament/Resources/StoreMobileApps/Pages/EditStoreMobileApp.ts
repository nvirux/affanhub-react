import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\EditStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/EditStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/{record}/edit'
 */
const EditStoreMobileApp = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditStoreMobileApp.url(args, options),
    method: 'get',
})

EditStoreMobileApp.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/store-mobile-apps/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\EditStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/EditStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/{record}/edit'
 */
EditStoreMobileApp.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditStoreMobileApp.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\EditStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/EditStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/{record}/edit'
 */
EditStoreMobileApp.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditStoreMobileApp.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\EditStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/EditStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/{record}/edit'
 */
EditStoreMobileApp.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditStoreMobileApp.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\EditStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/EditStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/{record}/edit'
 */
    const EditStoreMobileAppForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditStoreMobileApp.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\EditStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/EditStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/{record}/edit'
 */
        EditStoreMobileAppForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditStoreMobileApp.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\EditStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/EditStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/{record}/edit'
 */
        EditStoreMobileAppForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditStoreMobileApp.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditStoreMobileApp.form = EditStoreMobileAppForm
export default EditStoreMobileApp