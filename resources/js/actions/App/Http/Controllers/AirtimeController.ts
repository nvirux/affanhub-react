import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\AirtimeController::index
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/vtu/airtime',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AirtimeController::index
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AirtimeController::index
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AirtimeController::index
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\AirtimeController::purchase
 * @see app/Http/Controllers/AirtimeController.php:84
 * @route '/vtu/airtime/purchase'
 */
export const purchase = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: purchase.url(options),
    method: 'post',
})

purchase.definition = {
    methods: ["post"],
    url: '/vtu/airtime/purchase',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AirtimeController::purchase
 * @see app/Http/Controllers/AirtimeController.php:84
 * @route '/vtu/airtime/purchase'
 */
purchase.url = (options?: RouteQueryOptions) => {
    return purchase.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AirtimeController::purchase
 * @see app/Http/Controllers/AirtimeController.php:84
 * @route '/vtu/airtime/purchase'
 */
purchase.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: purchase.url(options),
    method: 'post',
})
const AirtimeController = { index, purchase }

export default AirtimeController