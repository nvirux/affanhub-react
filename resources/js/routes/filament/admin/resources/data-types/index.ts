import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\DataTypes\Pages\ListDataTypes::__invoke
 * @see app/Filament/Resources/DataTypes/Pages/ListDataTypes.php:7
 * @route '//admin.localhost/data-types'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/data-types',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\DataTypes\Pages\ListDataTypes::__invoke
 * @see app/Filament/Resources/DataTypes/Pages/ListDataTypes.php:7
 * @route '//admin.localhost/data-types'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\DataTypes\Pages\ListDataTypes::__invoke
 * @see app/Filament/Resources/DataTypes/Pages/ListDataTypes.php:7
 * @route '//admin.localhost/data-types'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\DataTypes\Pages\ListDataTypes::__invoke
 * @see app/Filament/Resources/DataTypes/Pages/ListDataTypes.php:7
 * @route '//admin.localhost/data-types'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\DataTypes\Pages\ListDataTypes::__invoke
 * @see app/Filament/Resources/DataTypes/Pages/ListDataTypes.php:7
 * @route '//admin.localhost/data-types'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\DataTypes\Pages\ListDataTypes::__invoke
 * @see app/Filament/Resources/DataTypes/Pages/ListDataTypes.php:7
 * @route '//admin.localhost/data-types'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\DataTypes\Pages\ListDataTypes::__invoke
 * @see app/Filament/Resources/DataTypes/Pages/ListDataTypes.php:7
 * @route '//admin.localhost/data-types'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
const dataTypes = {
    index: Object.assign(index, index),
}

export default dataTypes