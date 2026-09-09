import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\AirtimeController::index
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/vtu/airtime',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AirtimeController::index
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AirtimeController::index
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AirtimeController::index
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AirtimeController::index
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AirtimeController::index
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AirtimeController::index
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
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
* @see \App\Http\Controllers\AirtimeController::purchase
 * @see app/Http/Controllers/AirtimeController.php:82
 * @route '/vtu/airtime/purchase'
 */
export const purchase = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: purchase.url(options),
    method: 'post',
})

purchase.definition = {
    methods: ["post"],
    url: '/vtu/airtime/purchase',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AirtimeController::purchase
 * @see app/Http/Controllers/AirtimeController.php:82
 * @route '/vtu/airtime/purchase'
 */
purchase.url = (options?: RouteQueryOptions) => {
    return purchase.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AirtimeController::purchase
 * @see app/Http/Controllers/AirtimeController.php:82
 * @route '/vtu/airtime/purchase'
 */
purchase.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: purchase.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AirtimeController::purchase
 * @see app/Http/Controllers/AirtimeController.php:82
 * @route '/vtu/airtime/purchase'
 */
    const purchaseForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: purchase.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AirtimeController::purchase
 * @see app/Http/Controllers/AirtimeController.php:82
 * @route '/vtu/airtime/purchase'
 */
        purchaseForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: purchase.url(options),
            method: 'post',
        })
    
    purchase.form = purchaseForm
const AirtimeController = { index, purchase }

export default AirtimeController