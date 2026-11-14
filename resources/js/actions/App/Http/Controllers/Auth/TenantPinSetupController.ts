import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::show
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/setup-pin',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::show
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::show
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::show
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::store
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:32
 * @route '/setup-pin'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/setup-pin',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::store
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:32
 * @route '/setup-pin'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::store
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:32
 * @route '/setup-pin'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})
const TenantPinSetupController = { show, store }

export default TenantPinSetupController