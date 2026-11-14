import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::create
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/auth/complete-phone',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::create
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::create
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::create
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::store
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:41
 * @route '/auth/complete-phone'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/auth/complete-phone',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::store
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:41
 * @route '/auth/complete-phone'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::store
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:41
 * @route '/auth/complete-phone'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})
const MerchantPhoneCompletionController = { create, store }

export default MerchantPhoneCompletionController