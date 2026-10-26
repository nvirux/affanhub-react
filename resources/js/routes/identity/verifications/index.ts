import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
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
const verifications = {
    show: Object.assign(show, show),
downloadSlip: Object.assign(downloadSlip, downloadSlip),
}

export default verifications