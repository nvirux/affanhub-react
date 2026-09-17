import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\DashboardController::index
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/dashboard'
 */
const index42a740574ecbfbac32f8cc353fc32db9 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index42a740574ecbfbac32f8cc353fc32db9.url(options),
    method: 'get',
})

index42a740574ecbfbac32f8cc353fc32db9.definition = {
    methods: ["get","head"],
    url: '/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DashboardController::index
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/dashboard'
 */
index42a740574ecbfbac32f8cc353fc32db9.url = (options?: RouteQueryOptions) => {
    return index42a740574ecbfbac32f8cc353fc32db9.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DashboardController::index
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/dashboard'
 */
index42a740574ecbfbac32f8cc353fc32db9.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index42a740574ecbfbac32f8cc353fc32db9.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DashboardController::index
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/dashboard'
 */
index42a740574ecbfbac32f8cc353fc32db9.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index42a740574ecbfbac32f8cc353fc32db9.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DashboardController::index
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/dashboard'
 */
    const index42a740574ecbfbac32f8cc353fc32db9Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index42a740574ecbfbac32f8cc353fc32db9.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DashboardController::index
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/dashboard'
 */
        index42a740574ecbfbac32f8cc353fc32db9Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index42a740574ecbfbac32f8cc353fc32db9.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DashboardController::index
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/dashboard'
 */
        index42a740574ecbfbac32f8cc353fc32db9Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index42a740574ecbfbac32f8cc353fc32db9.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index42a740574ecbfbac32f8cc353fc32db9.form = index42a740574ecbfbac32f8cc353fc32db9Form
    /**
* @see \App\Http\Controllers\DashboardController::index
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/user/dashboard'
 */
const indexf0a0cf55a6141632b5a60218bb29d6ef = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexf0a0cf55a6141632b5a60218bb29d6ef.url(options),
    method: 'get',
})

indexf0a0cf55a6141632b5a60218bb29d6ef.definition = {
    methods: ["get","head"],
    url: '/user/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DashboardController::index
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/user/dashboard'
 */
indexf0a0cf55a6141632b5a60218bb29d6ef.url = (options?: RouteQueryOptions) => {
    return indexf0a0cf55a6141632b5a60218bb29d6ef.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DashboardController::index
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/user/dashboard'
 */
indexf0a0cf55a6141632b5a60218bb29d6ef.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexf0a0cf55a6141632b5a60218bb29d6ef.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DashboardController::index
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/user/dashboard'
 */
indexf0a0cf55a6141632b5a60218bb29d6ef.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexf0a0cf55a6141632b5a60218bb29d6ef.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DashboardController::index
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/user/dashboard'
 */
    const indexf0a0cf55a6141632b5a60218bb29d6efForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: indexf0a0cf55a6141632b5a60218bb29d6ef.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DashboardController::index
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/user/dashboard'
 */
        indexf0a0cf55a6141632b5a60218bb29d6efForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexf0a0cf55a6141632b5a60218bb29d6ef.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DashboardController::index
 * @see app/Http/Controllers/DashboardController.php:20
 * @route '/user/dashboard'
 */
        indexf0a0cf55a6141632b5a60218bb29d6efForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexf0a0cf55a6141632b5a60218bb29d6ef.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    indexf0a0cf55a6141632b5a60218bb29d6ef.form = indexf0a0cf55a6141632b5a60218bb29d6efForm

/**
* Multiple routes resolve to \App\Http\Controllers\DashboardController::index, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `index['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const index = {
    '/dashboard': index42a740574ecbfbac32f8cc353fc32db9,
    '/user/dashboard': indexf0a0cf55a6141632b5a60218bb29d6ef,
}

const DashboardController = { index }

export default DashboardController