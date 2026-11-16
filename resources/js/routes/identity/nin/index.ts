import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\NinVerificationController::verify
 * @see app/Http/Controllers/NinVerificationController.php:119
 * @route '/identity/nin/verify'
 */
export const verify = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: verify.url(options),
    method: 'post',
})

verify.definition = {
    methods: ["post"],
    url: '/identity/nin/verify',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\NinVerificationController::verify
 * @see app/Http/Controllers/NinVerificationController.php:119
 * @route '/identity/nin/verify'
 */
verify.url = (options?: RouteQueryOptions) => {
    return verify.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\NinVerificationController::verify
 * @see app/Http/Controllers/NinVerificationController.php:119
 * @route '/identity/nin/verify'
 */
verify.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: verify.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\NinVerificationController::verify
 * @see app/Http/Controllers/NinVerificationController.php:119
 * @route '/identity/nin/verify'
 */
    const verifyForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: verify.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\NinVerificationController::verify
 * @see app/Http/Controllers/NinVerificationController.php:119
 * @route '/identity/nin/verify'
 */
        verifyForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: verify.url(options),
            method: 'post',
        })
    
    verify.form = verifyForm
const nin = {
    verify: Object.assign(verify, verify),
}

export default nin