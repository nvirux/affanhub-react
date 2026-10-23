import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Stores\Pages\ListStores::__invoke
 * @see app/Filament/Resources/Stores/Pages/ListStores.php:7
 * @route '//admin.localhost/stores'
 */
const ListStores = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListStores.url(options),
    method: 'get',
})

ListStores.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/stores',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Stores\Pages\ListStores::__invoke
 * @see app/Filament/Resources/Stores/Pages/ListStores.php:7
 * @route '//admin.localhost/stores'
 */
ListStores.url = (options?: RouteQueryOptions) => {
    return ListStores.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Stores\Pages\ListStores::__invoke
 * @see app/Filament/Resources/Stores/Pages/ListStores.php:7
 * @route '//admin.localhost/stores'
 */
ListStores.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListStores.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Stores\Pages\ListStores::__invoke
 * @see app/Filament/Resources/Stores/Pages/ListStores.php:7
 * @route '//admin.localhost/stores'
 */
ListStores.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListStores.url(options),
    method: 'head',
})
export default ListStores