import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\BvnVerificationController::index
 * @see app/Http/Controllers/BvnVerificationController.php:24
 * @route '/identity/bvn'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/identity/bvn',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\BvnVerificationController::index
 * @see app/Http/Controllers/BvnVerificationController.php:24
 * @route '/identity/bvn'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\BvnVerificationController::index
 * @see app/Http/Controllers/BvnVerificationController.php:24
 * @route '/identity/bvn'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\BvnVerificationController::index
 * @see app/Http/Controllers/BvnVerificationController.php:24
 * @route '/identity/bvn'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\BvnVerificationController::index
 * @see app/Http/Controllers/BvnVerificationController.php:24
 * @route '/identity/bvn'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\BvnVerificationController::index
 * @see app/Http/Controllers/BvnVerificationController.php:24
 * @route '/identity/bvn'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\BvnVerificationController::index
 * @see app/Http/Controllers/BvnVerificationController.php:24
 * @route '/identity/bvn'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
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

    /**
* @see \App\Http\Controllers\BvnVerificationController::verify
 * @see app/Http/Controllers/BvnVerificationController.php:119
 * @route '/identity/bvn/verify'
 */
    const verifyForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: verify.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\BvnVerificationController::verify
 * @see app/Http/Controllers/BvnVerificationController.php:119
 * @route '/identity/bvn/verify'
 */
        verifyForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: verify.url(options),
            method: 'post',
        })
    
    verify.form = verifyForm
const BvnVerificationController = { index, verify }

export default BvnVerificationController