import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
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

    /**
* @see \App\Filament\Resources\DataTypes\Pages\ListDataTypes::__invoke
 * @see app/Filament/Resources/DataTypes/Pages/ListDataTypes.php:7
 * @route '//admin.localhost/data-types'
 */
    const ListDataTypesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListDataTypes.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\DataTypes\Pages\ListDataTypes::__invoke
 * @see app/Filament/Resources/DataTypes/Pages/ListDataTypes.php:7
 * @route '//admin.localhost/data-types'
 */
        ListDataTypesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListDataTypes.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\DataTypes\Pages\ListDataTypes::__invoke
 * @see app/Filament/Resources/DataTypes/Pages/ListDataTypes.php:7
 * @route '//admin.localhost/data-types'
 */
        ListDataTypesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListDataTypes.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListDataTypes.form = ListDataTypesForm
export default ListDataTypes