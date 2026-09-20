import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
const ListStoreMobileApps = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListStoreMobileApps.url(options),
    method: 'get',
})

ListStoreMobileApps.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/store-mobile-apps',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
ListStoreMobileApps.url = (options?: RouteQueryOptions) => {
    return ListStoreMobileApps.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
ListStoreMobileApps.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListStoreMobileApps.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
ListStoreMobileApps.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListStoreMobileApps.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
    const ListStoreMobileAppsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListStoreMobileApps.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
        ListStoreMobileAppsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListStoreMobileApps.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
        ListStoreMobileAppsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListStoreMobileApps.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListStoreMobileApps.form = ListStoreMobileAppsForm
export default ListStoreMobileApps