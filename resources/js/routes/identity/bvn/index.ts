import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\BvnVerificationController::verify
 * @see app/Http/Controllers/BvnVerificationController.php:119
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
 * @see app/Http/Controllers/BvnVerificationController.php:119
 * @route '/identity/bvn/verify'
 */
verify.url = (options?: RouteQueryOptions) => {
    return verify.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\BvnVerificationController::verify
 * @see app/Http/Controllers/BvnVerificationController.php:119
 * @route '/identity/bvn/verify'
 */
verify.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: verify.url(options),
    method: 'post',
})
const bvn = {
    verify: Object.assign(verify, verify),
}

export default bvn