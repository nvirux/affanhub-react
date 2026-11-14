import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\DataController::plans
 * @see app/Http/Controllers/DataController.php:30
 * @route '/vtu/data/plans'
 */
export const plans = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: plans.url(options),
    method: 'get',
})

plans.definition = {
    methods: ["get","head"],
    url: '/vtu/data/plans',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DataController::plans
 * @see app/Http/Controllers/DataController.php:30
 * @route '/vtu/data/plans'
 */
plans.url = (options?: RouteQueryOptions) => {
    return plans.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DataController::plans
 * @see app/Http/Controllers/DataController.php:30
 * @route '/vtu/data/plans'
 */
plans.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: plans.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DataController::plans
 * @see app/Http/Controllers/DataController.php:30
 * @route '/vtu/data/plans'
 */
plans.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: plans.url(options),
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
const data = {
    plans: Object.assign(plans, plans),
purchase: Object.assign(purchase, purchase),
}

export default data