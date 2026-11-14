import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\AirtimeCashController::index
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/vtu/airtime-to-cash',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AirtimeCashController::index
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AirtimeCashController::index
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AirtimeCashController::index
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\AirtimeCashController::submit
 * @see app/Http/Controllers/AirtimeCashController.php:109
 * @route '/vtu/airtime-to-cash/submit'
 */
export const submit = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: submit.url(options),
    method: 'post',
})

submit.definition = {
    methods: ["post"],
    url: '/vtu/airtime-to-cash/submit',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AirtimeCashController::submit
 * @see app/Http/Controllers/AirtimeCashController.php:109
 * @route '/vtu/airtime-to-cash/submit'
 */
submit.url = (options?: RouteQueryOptions) => {
    return submit.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AirtimeCashController::submit
 * @see app/Http/Controllers/AirtimeCashController.php:109
 * @route '/vtu/airtime-to-cash/submit'
 */
submit.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: submit.url(options),
    method: 'post',
})
const AirtimeCashController = { index, submit }

export default AirtimeCashController