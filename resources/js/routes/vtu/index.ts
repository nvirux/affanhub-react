import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import dataC044be from './data'
/**
* @see \App\Http\Controllers\DataController::data
 * @see app/Http/Controllers/DataController.php:20
 * @route '/vtu/data'
 */
export const data = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: data.url(options),
    method: 'get',
})

data.definition = {
    methods: ["get","head"],
    url: '/vtu/data',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DataController::data
 * @see app/Http/Controllers/DataController.php:20
 * @route '/vtu/data'
 */
data.url = (options?: RouteQueryOptions) => {
    return data.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DataController::data
 * @see app/Http/Controllers/DataController.php:20
 * @route '/vtu/data'
 */
data.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: data.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DataController::data
 * @see app/Http/Controllers/DataController.php:20
 * @route '/vtu/data'
 */
data.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: data.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DataController::data
 * @see app/Http/Controllers/DataController.php:20
 * @route '/vtu/data'
 */
    const dataForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: data.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DataController::data
 * @see app/Http/Controllers/DataController.php:20
 * @route '/vtu/data'
 */
        dataForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: data.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DataController::data
 * @see app/Http/Controllers/DataController.php:20
 * @route '/vtu/data'
 */
        dataForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: data.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    data.form = dataForm
const vtu = {
    data: Object.assign(data, dataC044be),
}

export default vtu