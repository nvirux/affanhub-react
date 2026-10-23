import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\TenantAuthController::checkIdentifier
 * @see app/Http/Controllers/Auth/TenantAuthController.php:72
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
 * @see app/Http/Controllers/Auth/TenantAuthController.php:72
 * @route '/login/check-identifier'
 */
checkIdentifier.url = (options?: RouteQueryOptions) => {
    return checkIdentifier.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::checkIdentifier
 * @see app/Http/Controllers/Auth/TenantAuthController.php:72
 * @route '/login/check-identifier'
 */
checkIdentifier.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkIdentifier.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::biometricLogin
 * @see app/Http/Controllers/Auth/TenantAuthController.php:137
 * @route '/login/biometric'
 */
export const biometricLogin = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: biometricLogin.url(options),
    method: 'post',
})

biometricLogin.definition = {
    methods: ["post"],
    url: '/login/biometric',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::biometricLogin
 * @see app/Http/Controllers/Auth/TenantAuthController.php:137
 * @route '/login/biometric'
 */
biometricLogin.url = (options?: RouteQueryOptions) => {
    return biometricLogin.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::biometricLogin
 * @see app/Http/Controllers/Auth/TenantAuthController.php:137
 * @route '/login/biometric'
 */
biometricLogin.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: biometricLogin.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::validateRegisterStep1
 * @see app/Http/Controllers/Auth/TenantAuthController.php:21
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
 * @see app/Http/Controllers/Auth/TenantAuthController.php:21
 * @route '/register/validate-step-1'
 */
validateRegisterStep1.url = (options?: RouteQueryOptions) => {
    return validateRegisterStep1.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::validateRegisterStep1
 * @see app/Http/Controllers/Auth/TenantAuthController.php:21
 * @route '/register/validate-step-1'
 */
validateRegisterStep1.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: validateRegisterStep1.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::generateBiometricToken
 * @see app/Http/Controllers/Auth/TenantAuthController.php:226
 * @route '/user/biometric-token'
 */
export const generateBiometricToken = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: generateBiometricToken.url(options),
    method: 'post',
})

generateBiometricToken.definition = {
    methods: ["post"],
    url: '/user/biometric-token',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::generateBiometricToken
 * @see app/Http/Controllers/Auth/TenantAuthController.php:226
 * @route '/user/biometric-token'
 */
generateBiometricToken.url = (options?: RouteQueryOptions) => {
    return generateBiometricToken.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::generateBiometricToken
 * @see app/Http/Controllers/Auth/TenantAuthController.php:226
 * @route '/user/biometric-token'
 */
generateBiometricToken.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: generateBiometricToken.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::revokeBiometricToken
 * @see app/Http/Controllers/Auth/TenantAuthController.php:245
 * @route '/user/biometric-token'
 */
export const revokeBiometricToken = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: revokeBiometricToken.url(options),
    method: 'delete',
})

revokeBiometricToken.definition = {
    methods: ["delete"],
    url: '/user/biometric-token',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::revokeBiometricToken
 * @see app/Http/Controllers/Auth/TenantAuthController.php:245
 * @route '/user/biometric-token'
 */
revokeBiometricToken.url = (options?: RouteQueryOptions) => {
    return revokeBiometricToken.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TenantAuthController::revokeBiometricToken
 * @see app/Http/Controllers/Auth/TenantAuthController.php:245
 * @route '/user/biometric-token'
 */
revokeBiometricToken.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: revokeBiometricToken.url(options),
    method: 'delete',
})
const TenantAuthController = { checkIdentifier, biometricLogin, validateRegisterStep1, generateBiometricToken, revokeBiometricToken }

export default TenantAuthController