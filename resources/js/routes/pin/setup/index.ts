import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
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

    /**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::store
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:32
 * @route '/setup-pin'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::store
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:32
 * @route '/setup-pin'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
const setup = {
    store: Object.assign(store, store),
}

export default setup