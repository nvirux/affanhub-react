import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Slips\Pages\ListSlips::__invoke
 * @see app/Filament/Resources/Slips/Pages/ListSlips.php:7
 * @route '//admin.localhost/slips'
 */
const ListSlips = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSlips.url(options),
    method: 'get',
})

ListSlips.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/slips',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Slips\Pages\ListSlips::__invoke
 * @see app/Filament/Resources/Slips/Pages/ListSlips.php:7
 * @route '//admin.localhost/slips'
 */
ListSlips.url = (options?: RouteQueryOptions) => {
    return ListSlips.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Slips\Pages\ListSlips::__invoke
 * @see app/Filament/Resources/Slips/Pages/ListSlips.php:7
 * @route '//admin.localhost/slips'
 */
ListSlips.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSlips.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Slips\Pages\ListSlips::__invoke
 * @see app/Filament/Resources/Slips/Pages/ListSlips.php:7
 * @route '//admin.localhost/slips'
 */
ListSlips.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListSlips.url(options),
    method: 'head',
})
export default ListSlips