import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\CreateStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/CreateStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/create'
 */
const CreateStoreMobileApp = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateStoreMobileApp.url(options),
    method: 'get',
})

CreateStoreMobileApp.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/store-mobile-apps/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\CreateStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/CreateStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/create'
 */
CreateStoreMobileApp.url = (options?: RouteQueryOptions) => {
    return CreateStoreMobileApp.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\CreateStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/CreateStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/create'
 */
CreateStoreMobileApp.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateStoreMobileApp.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\CreateStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/CreateStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/create'
 */
CreateStoreMobileApp.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateStoreMobileApp.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\CreateStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/CreateStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/create'
 */
    const CreateStoreMobileAppForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateStoreMobileApp.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\CreateStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/CreateStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/create'
 */
        CreateStoreMobileAppForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateStoreMobileApp.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\CreateStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/CreateStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/create'
 */
        CreateStoreMobileAppForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateStoreMobileApp.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateStoreMobileApp.form = CreateStoreMobileAppForm
export default CreateStoreMobileApp