import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Domains\Pages\ListDomains::__invoke
 * @see app/Filament/Resources/Domains/Pages/ListDomains.php:7
 * @route '//admin.localhost/domains'
 */
const ListDomains = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListDomains.url(options),
    method: 'get',
})

ListDomains.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/domains',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Domains\Pages\ListDomains::__invoke
 * @see app/Filament/Resources/Domains/Pages/ListDomains.php:7
 * @route '//admin.localhost/domains'
 */
ListDomains.url = (options?: RouteQueryOptions) => {
    return ListDomains.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Domains\Pages\ListDomains::__invoke
 * @see app/Filament/Resources/Domains/Pages/ListDomains.php:7
 * @route '//admin.localhost/domains'
 */
ListDomains.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListDomains.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Domains\Pages\ListDomains::__invoke
 * @see app/Filament/Resources/Domains/Pages/ListDomains.php:7
 * @route '//admin.localhost/domains'
 */
ListDomains.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListDomains.url(options),
    method: 'head',
})
export default ListDomains