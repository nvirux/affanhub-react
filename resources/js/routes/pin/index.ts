import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import setup90f0be from './setup'
/**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::setup
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
export const setup = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: setup.url(options),
    method: 'get',
})

setup.definition = {
    methods: ["get","head"],
    url: '/setup-pin',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::setup
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
setup.url = (options?: RouteQueryOptions) => {
    return setup.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::setup
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
setup.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: setup.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::setup
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
setup.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: setup.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::setup
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
    const setupForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: setup.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::setup
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
        setupForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: setup.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::setup
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
        setupForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: setup.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    setup.form = setupForm
const pin = {
    setup: Object.assign(setup, setup90f0be),
}

export default pin