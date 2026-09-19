import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../wayfinder'
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
const airtime = {
    purchase: Object.assign(purchase, purchase),
}

export default airtime