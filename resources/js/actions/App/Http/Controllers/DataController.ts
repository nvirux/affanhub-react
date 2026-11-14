import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\DataController::index
 * @see app/Http/Controllers/DataController.php:22
 * @route '/vtu/data'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/vtu/data',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DataController::index
 * @see app/Http/Controllers/DataController.php:22
 * @route '/vtu/data'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DataController::index
 * @see app/Http/Controllers/DataController.php:22
 * @route '/vtu/data'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DataController::index
 * @see app/Http/Controllers/DataController.php:22
 * @route '/vtu/data'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DataController::getPlans
 * @see app/Http/Controllers/DataController.php:30
 * @route '/vtu/data/plans'
 */
export const getPlans = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getPlans.url(options),
    method: 'get',
})

getPlans.definition = {
    methods: ["get","head"],
    url: '/vtu/data/plans',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DataController::getPlans
 * @see app/Http/Controllers/DataController.php:30
 * @route '/vtu/data/plans'
 */
getPlans.url = (options?: RouteQueryOptions) => {
    return getPlans.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DataController::getPlans
 * @see app/Http/Controllers/DataController.php:30
 * @route '/vtu/data/plans'
 */
getPlans.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getPlans.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DataController::getPlans
 * @see app/Http/Controllers/DataController.php:30
 * @route '/vtu/data/plans'
 */
getPlans.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getPlans.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DataController::purchase
 * @see app/Http/Controllers/DataController.php:76
 * @route '/vtu/data/purchase'
 */
export const purchase = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: purchase.url(options),
    method: 'post',
})

purchase.definition = {
    methods: ["post"],
    url: '/vtu/data/purchase',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\DataController::purchase
 * @see app/Http/Controllers/DataController.php:76
 * @route '/vtu/data/purchase'
 */
purchase.url = (options?: RouteQueryOptions) => {
    return purchase.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DataController::purchase
 * @see app/Http/Controllers/DataController.php:76
 * @route '/vtu/data/purchase'
 */
purchase.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: purchase.url(options),
    method: 'post',
})
const DataController = { index, getPlans, purchase }

export default DataController