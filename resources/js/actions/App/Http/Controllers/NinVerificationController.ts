import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\NinVerificationController::index
 * @see app/Http/Controllers/NinVerificationController.php:24
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
 * @see app/Http/Controllers/NinVerificationController.php:24
 * @route '/identity/nin'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\NinVerificationController::index
 * @see app/Http/Controllers/NinVerificationController.php:24
 * @route '/identity/nin'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\NinVerificationController::index
 * @see app/Http/Controllers/NinVerificationController.php:24
 * @route '/identity/nin'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\NinVerificationController::index
 * @see app/Http/Controllers/NinVerificationController.php:24
 * @route '/identity/nin'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\NinVerificationController::index
 * @see app/Http/Controllers/NinVerificationController.php:24
 * @route '/identity/nin'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\NinVerificationController::index
 * @see app/Http/Controllers/NinVerificationController.php:24
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
/**
* @see \App\Http\Controllers\NinVerificationController::show
 * @see app/Http/Controllers/NinVerificationController.php:416
 * @route '/identity/verifications/{reference}'
 */
export const show = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/identity/verifications/{reference}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\NinVerificationController::show
 * @see app/Http/Controllers/NinVerificationController.php:416
 * @route '/identity/verifications/{reference}'
 */
show.url = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { reference: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    reference: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        reference: args.reference,
                }

    return show.definition.url
            .replace('{reference}', parsedArgs.reference.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\NinVerificationController::show
 * @see app/Http/Controllers/NinVerificationController.php:416
 * @route '/identity/verifications/{reference}'
 */
show.get = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\NinVerificationController::show
 * @see app/Http/Controllers/NinVerificationController.php:416
 * @route '/identity/verifications/{reference}'
 */
show.head = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\NinVerificationController::show
 * @see app/Http/Controllers/NinVerificationController.php:416
 * @route '/identity/verifications/{reference}'
 */
    const showForm = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\NinVerificationController::show
 * @see app/Http/Controllers/NinVerificationController.php:416
 * @route '/identity/verifications/{reference}'
 */
        showForm.get = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\NinVerificationController::show
 * @see app/Http/Controllers/NinVerificationController.php:416
 * @route '/identity/verifications/{reference}'
 */
        showForm.head = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\NinVerificationController::downloadSlip
 * @see app/Http/Controllers/NinVerificationController.php:469
 * @route '/identity/verifications/{reference}/download-slip'
 */
export const downloadSlip = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadSlip.url(args, options),
    method: 'get',
})

downloadSlip.definition = {
    methods: ["get","head"],
    url: '/identity/verifications/{reference}/download-slip',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\NinVerificationController::downloadSlip
 * @see app/Http/Controllers/NinVerificationController.php:469
 * @route '/identity/verifications/{reference}/download-slip'
 */
downloadSlip.url = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { reference: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    reference: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        reference: args.reference,
                }

    return downloadSlip.definition.url
            .replace('{reference}', parsedArgs.reference.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\NinVerificationController::downloadSlip
 * @see app/Http/Controllers/NinVerificationController.php:469
 * @route '/identity/verifications/{reference}/download-slip'
 */
downloadSlip.get = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadSlip.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\NinVerificationController::downloadSlip
 * @see app/Http/Controllers/NinVerificationController.php:469
 * @route '/identity/verifications/{reference}/download-slip'
 */
downloadSlip.head = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: downloadSlip.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\NinVerificationController::downloadSlip
 * @see app/Http/Controllers/NinVerificationController.php:469
 * @route '/identity/verifications/{reference}/download-slip'
 */
    const downloadSlipForm = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: downloadSlip.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\NinVerificationController::downloadSlip
 * @see app/Http/Controllers/NinVerificationController.php:469
 * @route '/identity/verifications/{reference}/download-slip'
 */
        downloadSlipForm.get = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: downloadSlip.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\NinVerificationController::downloadSlip
 * @see app/Http/Controllers/NinVerificationController.php:469
 * @route '/identity/verifications/{reference}/download-slip'
 */
        downloadSlipForm.head = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: downloadSlip.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    downloadSlip.form = downloadSlipForm
const NinVerificationController = { index, verify, show, downloadSlip }

export default NinVerificationController