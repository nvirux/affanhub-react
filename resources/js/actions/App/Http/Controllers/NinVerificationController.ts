import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\NinVerificationController::index
 * @see app/Http/Controllers/NinVerificationController.php:18
 * @route '/identity/nin'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/identity/nin',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\NinVerificationController::index
 * @see app/Http/Controllers/NinVerificationController.php:18
 * @route '/identity/nin'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\NinVerificationController::index
 * @see app/Http/Controllers/NinVerificationController.php:18
 * @route '/identity/nin'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\NinVerificationController::index
 * @see app/Http/Controllers/NinVerificationController.php:18
 * @route '/identity/nin'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\NinVerificationController::index
 * @see app/Http/Controllers/NinVerificationController.php:18
 * @route '/identity/nin'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\NinVerificationController::index
 * @see app/Http/Controllers/NinVerificationController.php:18
 * @route '/identity/nin'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\NinVerificationController::index
 * @see app/Http/Controllers/NinVerificationController.php:18
 * @route '/identity/nin'
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
* @see \App\Http\Controllers\NinVerificationController::verify
 * @see app/Http/Controllers/NinVerificationController.php:118
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
 * @see app/Http/Controllers/NinVerificationController.php:118
 * @route '/identity/nin/verify'
 */
verify.url = (options?: RouteQueryOptions) => {
    return verify.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\NinVerificationController::verify
 * @see app/Http/Controllers/NinVerificationController.php:118
 * @route '/identity/nin/verify'
 */
verify.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: verify.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\NinVerificationController::verify
 * @see app/Http/Controllers/NinVerificationController.php:118
 * @route '/identity/nin/verify'
 */
    const verifyForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: verify.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\NinVerificationController::verify
 * @see app/Http/Controllers/NinVerificationController.php:118
 * @route '/identity/nin/verify'
 */
        verifyForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: verify.url(options),
            method: 'post',
        })
    
    verify.form = verifyForm
const NinVerificationController = { index, verify }

export default NinVerificationController