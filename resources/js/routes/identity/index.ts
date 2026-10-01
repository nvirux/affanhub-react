import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import ninBd9d09 from './nin'
import bvnC51c8d from './bvn'
/**
* @see \App\Http\Controllers\NinVerificationController::nin
 * @see app/Http/Controllers/NinVerificationController.php:18
 * @route '/identity/nin'
 */
export const nin = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: nin.url(options),
    method: 'get',
})

nin.definition = {
    methods: ["get","head"],
    url: '/identity/nin',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\NinVerificationController::nin
 * @see app/Http/Controllers/NinVerificationController.php:18
 * @route '/identity/nin'
 */
nin.url = (options?: RouteQueryOptions) => {
    return nin.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\NinVerificationController::nin
 * @see app/Http/Controllers/NinVerificationController.php:18
 * @route '/identity/nin'
 */
nin.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: nin.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\NinVerificationController::nin
 * @see app/Http/Controllers/NinVerificationController.php:18
 * @route '/identity/nin'
 */
nin.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: nin.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\NinVerificationController::nin
 * @see app/Http/Controllers/NinVerificationController.php:18
 * @route '/identity/nin'
 */
    const ninForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: nin.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\NinVerificationController::nin
 * @see app/Http/Controllers/NinVerificationController.php:18
 * @route '/identity/nin'
 */
        ninForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: nin.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\NinVerificationController::nin
 * @see app/Http/Controllers/NinVerificationController.php:18
 * @route '/identity/nin'
 */
        ninForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: nin.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    nin.form = ninForm
/**
* @see \App\Http\Controllers\BvnVerificationController::bvn
 * @see app/Http/Controllers/BvnVerificationController.php:18
 * @route '/identity/bvn'
 */
export const bvn = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bvn.url(options),
    method: 'get',
})

bvn.definition = {
    methods: ["get","head"],
    url: '/identity/bvn',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\BvnVerificationController::bvn
 * @see app/Http/Controllers/BvnVerificationController.php:18
 * @route '/identity/bvn'
 */
bvn.url = (options?: RouteQueryOptions) => {
    return bvn.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\BvnVerificationController::bvn
 * @see app/Http/Controllers/BvnVerificationController.php:18
 * @route '/identity/bvn'
 */
bvn.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bvn.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\BvnVerificationController::bvn
 * @see app/Http/Controllers/BvnVerificationController.php:18
 * @route '/identity/bvn'
 */
bvn.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: bvn.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\BvnVerificationController::bvn
 * @see app/Http/Controllers/BvnVerificationController.php:18
 * @route '/identity/bvn'
 */
    const bvnForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: bvn.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\BvnVerificationController::bvn
 * @see app/Http/Controllers/BvnVerificationController.php:18
 * @route '/identity/bvn'
 */
        bvnForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bvn.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\BvnVerificationController::bvn
 * @see app/Http/Controllers/BvnVerificationController.php:18
 * @route '/identity/bvn'
 */
        bvnForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bvn.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    bvn.form = bvnForm
const identity = {
    nin: Object.assign(nin, ninBd9d09),
bvn: Object.assign(bvn, bvnC51c8d),
}

export default identity