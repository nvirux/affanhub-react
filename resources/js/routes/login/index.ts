import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\TenantAuthController::checkIdentifier
 * @see app/Http/Controllers/Auth/TenantAuthController.php:16
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
 * @see app/Http/Controllers/Auth/TenantAuthController.php:16
 * @route '/login/check-identifier'
 */
checkIdentifier.url = (options?: RouteQueryOptions) => {
    return checkIdentifier.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::checkIdentifier
 * @see app/Http/Controllers/Auth/TenantAuthController.php:16
 * @route '/login/check-identifier'
 */
checkIdentifier.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkIdentifier.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Auth\TenantAuthController::checkIdentifier
 * @see app/Http/Controllers/Auth/TenantAuthController.php:16
 * @route '/login/check-identifier'
 */
    const checkIdentifierForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkIdentifier.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Auth\TenantAuthController::checkIdentifier
 * @see app/Http/Controllers/Auth/TenantAuthController.php:16
 * @route '/login/check-identifier'
 */
        checkIdentifierForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkIdentifier.url(options),
            method: 'post',
        })
    
    checkIdentifier.form = checkIdentifierForm
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

    /**
* @see \Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::store
 * @see vendor/laravel/fortify/src/Http/Controllers/AuthenticatedSessionController.php:58
 * @route '/login'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::store
 * @see vendor/laravel/fortify/src/Http/Controllers/AuthenticatedSessionController.php:58
 * @route '/login'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
const login = {
    store: Object.assign(store, store),
}

export default login