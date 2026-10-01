import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\BvnVerificationController::verify
 * @see app/Http/Controllers/BvnVerificationController.php:107
 * @route '/identity/bvn/verify'
 */
export const verify = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: verify.url(options),
    method: 'post',
})

verify.definition = {
    methods: ["post"],
    url: '/identity/bvn/verify',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\BvnVerificationController::verify
 * @see app/Http/Controllers/BvnVerificationController.php:107
 * @route '/identity/bvn/verify'
 */
verify.url = (options?: RouteQueryOptions) => {
    return verify.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\BvnVerificationController::verify
 * @see app/Http/Controllers/BvnVerificationController.php:107
 * @route '/identity/bvn/verify'
 */
verify.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: verify.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\BvnVerificationController::verify
 * @see app/Http/Controllers/BvnVerificationController.php:107
 * @route '/identity/bvn/verify'
 */
    const verifyForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: verify.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\BvnVerificationController::verify
 * @see app/Http/Controllers/BvnVerificationController.php:107
 * @route '/identity/bvn/verify'
 */
        verifyForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: verify.url(options),
            method: 'post',
        })
    
    verify.form = verifyForm
const bvn = {
    verify: Object.assign(verify, verify),
}

export default bvn