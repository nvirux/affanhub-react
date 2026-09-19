import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\TenantAuthController::checkIdentifier
 * @see app/Http/Controllers/Auth/TenantAuthController.php:68
 * @route '/login/check-identifier'
 */
export const checkIdentifier = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkIdentifier.url(options),
    method: 'post',
})

checkIdentifier.definition = {
    methods: ["post"],
    url: '/login/check-identifier',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::checkIdentifier
 * @see app/Http/Controllers/Auth/TenantAuthController.php:68
 * @route '/login/check-identifier'
 */
checkIdentifier.url = (options?: RouteQueryOptions) => {
    return checkIdentifier.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::checkIdentifier
 * @see app/Http/Controllers/Auth/TenantAuthController.php:68
 * @route '/login/check-identifier'
 */
checkIdentifier.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkIdentifier.url(options),
    method: 'post',
})

/**
* @see \Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::store
 * @see vendor/laravel/fortify/src/Http/Controllers/AuthenticatedSessionController.php:58
 * @route '/login'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/login',
} satisfies RouteDefinition<["post"]>

/**
* @see \Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::store
 * @see vendor/laravel/fortify/src/Http/Controllers/AuthenticatedSessionController.php:58
 * @route '/login'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::store
 * @see vendor/laravel/fortify/src/Http/Controllers/AuthenticatedSessionController.php:58
 * @route '/login'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})
const login = {
    store: Object.assign(store, store),
}

export default login