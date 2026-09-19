import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\TenantAuthController::validateStep1
 * @see app/Http/Controllers/Auth/TenantAuthController.php:17
 * @route '/register/validate-step-1'
 */
export const validateStep1 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: validateStep1.url(options),
    method: 'post',
})

validateStep1.definition = {
    methods: ["post"],
    url: '/register/validate-step-1',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::validateStep1
 * @see app/Http/Controllers/Auth/TenantAuthController.php:17
 * @route '/register/validate-step-1'
 */
validateStep1.url = (options?: RouteQueryOptions) => {
    return validateStep1.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::validateStep1
 * @see app/Http/Controllers/Auth/TenantAuthController.php:17
 * @route '/register/validate-step-1'
 */
validateStep1.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: validateStep1.url(options),
    method: 'post',
})

/**
* @see \Laravel\Fortify\Http\Controllers\RegisteredUserController::store
 * @see vendor/laravel/fortify/src/Http/Controllers/RegisteredUserController.php:53
 * @route '/register'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/register',
} satisfies RouteDefinition<["post"]>

/**
* @see \Laravel\Fortify\Http\Controllers\RegisteredUserController::store
 * @see vendor/laravel/fortify/src/Http/Controllers/RegisteredUserController.php:53
 * @route '/register'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \Laravel\Fortify\Http\Controllers\RegisteredUserController::store
 * @see vendor/laravel/fortify/src/Http/Controllers/RegisteredUserController.php:53
 * @route '/register'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})
const register = {
    store: Object.assign(store, store),
}

export default register