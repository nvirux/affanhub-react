import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\TenantAuthController::revoke
 * @see app/Http/Controllers/Auth/TenantAuthController.php:245
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
 * @see app/Http/Controllers/Auth/TenantAuthController.php:245
 * @route '/user/biometric-token'
 */
revoke.url = (options?: RouteQueryOptions) => {
    return revoke.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::revoke
 * @see app/Http/Controllers/Auth/TenantAuthController.php:245
 * @route '/user/biometric-token'
 */
revoke.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: revoke.url(options),
    method: 'delete',
})
const biometricToken = {
    revoke: Object.assign(revoke, revoke),
}

export default biometricToken