import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\DataController::index
 * @see app/Http/Controllers/DataController.php:20
 * @route '/vtu/data'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/vtu/data',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DataController::index
 * @see app/Http/Controllers/DataController.php:20
 * @route '/vtu/data'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DataController::index
 * @see app/Http/Controllers/DataController.php:20
 * @route '/vtu/data'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DataController::index
 * @see app/Http/Controllers/DataController.php:20
 * @route '/vtu/data'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DataController::index
 * @see app/Http/Controllers/DataController.php:20
 * @route '/vtu/data'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DataController::index
 * @see app/Http/Controllers/DataController.php:20
 * @route '/vtu/data'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DataController::index
 * @see app/Http/Controllers/DataController.php:20
 * @route '/vtu/data'
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
* @see \App\Http\Controllers\DataController::getPlans
 * @see app/Http/Controllers/DataController.php:28
 * @route '/vtu/data/plans'
 */
export const getPlans = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getPlans.url(options),
    method: 'get',
})

getPlans.definition = {
    methods: ["get","head"],
    url: '/vtu/data/plans',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DataController::getPlans
 * @see app/Http/Controllers/DataController.php:28
 * @route '/vtu/data/plans'
 */
getPlans.url = (options?: RouteQueryOptions) => {
    return getPlans.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DataController::getPlans
 * @see app/Http/Controllers/DataController.php:28
 * @route '/vtu/data/plans'
 */
getPlans.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getPlans.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DataController::getPlans
 * @see app/Http/Controllers/DataController.php:28
 * @route '/vtu/data/plans'
 */
getPlans.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getPlans.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DataController::getPlans
 * @see app/Http/Controllers/DataController.php:28
 * @route '/vtu/data/plans'
 */
    const getPlansForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getPlans.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DataController::getPlans
 * @see app/Http/Controllers/DataController.php:28
 * @route '/vtu/data/plans'
 */
        getPlansForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getPlans.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DataController::getPlans
 * @see app/Http/Controllers/DataController.php:28
 * @route '/vtu/data/plans'
 */
        getPlansForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getPlans.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getPlans.form = getPlansForm
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
const DataController = { index, getPlans, purchase }

export default DataController