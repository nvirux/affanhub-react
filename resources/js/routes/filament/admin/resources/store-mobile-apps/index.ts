import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/store-mobile-apps',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})
const storeMobileApps = {
    index: Object.assign(index, index),
}

export default storeMobileApps