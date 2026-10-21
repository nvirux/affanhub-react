import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import biometricToken20cd0c from './biometric-token'
/**
* @see \App\Http\Controllers\Auth\TenantAuthController::biometricToken
 * @see app/Http/Controllers/Auth/TenantAuthController.php:198
 * @route '/user/biometric-token'
 */
export const biometricToken = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: biometricToken.url(options),
    method: 'post',
})

biometricToken.definition = {
    methods: ["post"],
    url: '/user/biometric-token',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::biometricToken
 * @see app/Http/Controllers/Auth/TenantAuthController.php:198
 * @route '/user/biometric-token'
 */
biometricToken.url = (options?: RouteQueryOptions) => {
    return biometricToken.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::biometricToken
 * @see app/Http/Controllers/Auth/TenantAuthController.php:198
 * @route '/user/biometric-token'
 */
biometricToken.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: biometricToken.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Auth\TenantAuthController::biometricToken
 * @see app/Http/Controllers/Auth/TenantAuthController.php:198
 * @route '/user/biometric-token'
 */
    const biometricTokenForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: biometricToken.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Auth\TenantAuthController::biometricToken
 * @see app/Http/Controllers/Auth/TenantAuthController.php:198
 * @route '/user/biometric-token'
 */
        biometricTokenForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: biometricToken.url(options),
            method: 'post',
        })
    
    biometricToken.form = biometricTokenForm
/**
* @see \App\Http\Controllers\DashboardController::dashboard
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/user/dashboard'
 */
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/user/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DashboardController::dashboard
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/user/dashboard'
 */
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DashboardController::dashboard
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/user/dashboard'
 */
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DashboardController::dashboard
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/user/dashboard'
 */
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DashboardController::dashboard
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/user/dashboard'
 */
    const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: dashboard.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DashboardController::dashboard
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/user/dashboard'
 */
        dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DashboardController::dashboard
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/user/dashboard'
 */
        dashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    dashboard.form = dashboardForm
const user = {
    biometricToken: Object.assign(biometricToken, biometricToken20cd0c),
dashboard: Object.assign(dashboard, dashboard),
}

export default user