import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\EarnController::index
 * @see app/Http/Controllers/EarnController.php:18
 * @route '/earn'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/earn',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EarnController::index
 * @see app/Http/Controllers/EarnController.php:18
 * @route '/earn'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\EarnController::index
 * @see app/Http/Controllers/EarnController.php:18
 * @route '/earn'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\EarnController::index
 * @see app/Http/Controllers/EarnController.php:18
 * @route '/earn'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\EarnController::index
 * @see app/Http/Controllers/EarnController.php:18
 * @route '/earn'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\EarnController::index
 * @see app/Http/Controllers/EarnController.php:18
 * @route '/earn'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\EarnController::index
 * @see app/Http/Controllers/EarnController.php:18
 * @route '/earn'
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
const EarnController = { index }

export default EarnController