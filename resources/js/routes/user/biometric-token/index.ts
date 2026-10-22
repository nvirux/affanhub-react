import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\TenantAuthController::revoke
 * @see app/Http/Controllers/Auth/TenantAuthController.php:220
 * @route '/user/biometric-token'
 */
export const revoke = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: revoke.url(options),
    method: 'delete',
})

revoke.definition = {
    methods: ["delete"],
    url: '/user/biometric-token',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::revoke
 * @see app/Http/Controllers/Auth/TenantAuthController.php:220
 * @route '/user/biometric-token'
 */
revoke.url = (options?: RouteQueryOptions) => {
    return revoke.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::revoke
 * @see app/Http/Controllers/Auth/TenantAuthController.php:220
 * @route '/user/biometric-token'
 */
revoke.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: revoke.url(options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Auth\TenantAuthController::revoke
 * @see app/Http/Controllers/Auth/TenantAuthController.php:220
 * @route '/user/biometric-token'
 */
    const revokeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: revoke.url({
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Auth\TenantAuthController::revoke
 * @see app/Http/Controllers/Auth/TenantAuthController.php:220
 * @route '/user/biometric-token'
 */
        revokeForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: revoke.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    revoke.form = revokeForm
const biometricToken = {
    revoke: Object.assign(revoke, revoke),
}

export default biometricToken