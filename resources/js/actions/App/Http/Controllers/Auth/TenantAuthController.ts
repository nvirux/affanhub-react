import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
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
const TenantAuthController = { checkIdentifier }

export default TenantAuthController