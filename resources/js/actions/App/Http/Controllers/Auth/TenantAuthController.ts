import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
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
* @see \App\Http\Controllers\Auth\TenantAuthController::validateRegisterStep1
 * @see app/Http/Controllers/Auth/TenantAuthController.php:17
 * @route '/register/validate-step-1'
 */
export const validateRegisterStep1 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: validateRegisterStep1.url(options),
    method: 'post',
})

validateRegisterStep1.definition = {
    methods: ["post"],
    url: '/register/validate-step-1',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::validateRegisterStep1
 * @see app/Http/Controllers/Auth/TenantAuthController.php:17
 * @route '/register/validate-step-1'
 */
validateRegisterStep1.url = (options?: RouteQueryOptions) => {
    return validateRegisterStep1.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::validateRegisterStep1
 * @see app/Http/Controllers/Auth/TenantAuthController.php:17
 * @route '/register/validate-step-1'
 */
validateRegisterStep1.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: validateRegisterStep1.url(options),
    method: 'post',
})
const TenantAuthController = { checkIdentifier, validateRegisterStep1 }

export default TenantAuthController