import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
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
* @see \App\Http\Controllers\Auth\TenantAuthController::checkIdentifier
 * @see app/Http/Controllers/Auth/TenantAuthController.php:68
 * @route '/login/check-identifier'
 */
    const checkIdentifierForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkIdentifier.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Auth\TenantAuthController::checkIdentifier
 * @see app/Http/Controllers/Auth/TenantAuthController.php:68
 * @route '/login/check-identifier'
 */
        checkIdentifierForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkIdentifier.url(options),
            method: 'post',
        })
    
    checkIdentifier.form = checkIdentifierForm
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

    /**
* @see \App\Http\Controllers\Auth\TenantAuthController::validateRegisterStep1
 * @see app/Http/Controllers/Auth/TenantAuthController.php:17
 * @route '/register/validate-step-1'
 */
    const validateRegisterStep1Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: validateRegisterStep1.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Auth\TenantAuthController::validateRegisterStep1
 * @see app/Http/Controllers/Auth/TenantAuthController.php:17
 * @route '/register/validate-step-1'
 */
        validateRegisterStep1Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: validateRegisterStep1.url(options),
            method: 'post',
        })
    
    validateRegisterStep1.form = validateRegisterStep1Form
const TenantAuthController = { checkIdentifier, validateRegisterStep1 }

export default TenantAuthController