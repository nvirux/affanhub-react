import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\DataController::plans
 * @see app/Http/Controllers/DataController.php:28
 * @route '/vtu/data/plans'
 */
export const plans = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: plans.url(options),
    method: 'get',
})

plans.definition = {
    methods: ["get","head"],
    url: '/vtu/data/plans',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DataController::plans
 * @see app/Http/Controllers/DataController.php:28
 * @route '/vtu/data/plans'
 */
plans.url = (options?: RouteQueryOptions) => {
    return plans.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DataController::plans
 * @see app/Http/Controllers/DataController.php:28
 * @route '/vtu/data/plans'
 */
plans.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: plans.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DataController::plans
 * @see app/Http/Controllers/DataController.php:28
 * @route '/vtu/data/plans'
 */
plans.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: plans.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DataController::plans
 * @see app/Http/Controllers/DataController.php:28
 * @route '/vtu/data/plans'
 */
    const plansForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: plans.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DataController::plans
 * @see app/Http/Controllers/DataController.php:28
 * @route '/vtu/data/plans'
 */
        plansForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: plans.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DataController::plans
 * @see app/Http/Controllers/DataController.php:28
 * @route '/vtu/data/plans'
 */
        plansForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: plans.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    plans.form = plansForm
/**
* @see \App\Http\Controllers\DataController::purchase
 * @see app/Http/Controllers/DataController.php:74
 * @route '/vtu/data/purchase'
 */
export const purchase = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: purchase.url(options),
    method: 'post',
})

purchase.definition = {
    methods: ["post"],
    url: '/vtu/data/purchase',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\DataController::purchase
 * @see app/Http/Controllers/DataController.php:74
 * @route '/vtu/data/purchase'
 */
purchase.url = (options?: RouteQueryOptions) => {
    return purchase.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DataController::purchase
 * @see app/Http/Controllers/DataController.php:74
 * @route '/vtu/data/purchase'
 */
purchase.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: purchase.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\DataController::purchase
 * @see app/Http/Controllers/DataController.php:74
 * @route '/vtu/data/purchase'
 */
    const purchaseForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: purchase.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\DataController::purchase
 * @see app/Http/Controllers/DataController.php:74
 * @route '/vtu/data/purchase'
 */
        purchaseForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: purchase.url(options),
            method: 'post',
        })
    
    purchase.form = purchaseForm
const data = {
    plans: Object.assign(plans, plans),
purchase: Object.assign(purchase, purchase),
}

export default data