import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\DataTypes\Pages\ListDataTypes::__invoke
 * @see app/Filament/Resources/DataTypes/Pages/ListDataTypes.php:7
 * @route '//admin.localhost/data-types'
 */
const ListDataTypes = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListDataTypes.url(options),
    method: 'get',
})

ListDataTypes.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/data-types',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\DataTypes\Pages\ListDataTypes::__invoke
 * @see app/Filament/Resources/DataTypes/Pages/ListDataTypes.php:7
 * @route '//admin.localhost/data-types'
 */
ListDataTypes.url = (options?: RouteQueryOptions) => {
    return ListDataTypes.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\DataTypes\Pages\ListDataTypes::__invoke
 * @see app/Filament/Resources/DataTypes/Pages/ListDataTypes.php:7
 * @route '//admin.localhost/data-types'
 */
ListDataTypes.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListDataTypes.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\DataTypes\Pages\ListDataTypes::__invoke
 * @see app/Filament/Resources/DataTypes/Pages/ListDataTypes.php:7
 * @route '//admin.localhost/data-types'
 */
ListDataTypes.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListDataTypes.url(options),
    method: 'head',
})
export default ListDataTypes