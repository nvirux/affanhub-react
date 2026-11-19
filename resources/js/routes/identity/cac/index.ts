import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/identity/cac',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac'
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
const cac = {
    index: Object.assign(index, index),
}

export default cac