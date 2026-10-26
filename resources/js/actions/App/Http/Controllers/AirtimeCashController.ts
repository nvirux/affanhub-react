import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\AirtimeCashController::index
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/vtu/airtime-to-cash',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AirtimeCashController::index
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AirtimeCashController::index
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AirtimeCashController::index
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AirtimeCashController::index
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AirtimeCashController::index
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AirtimeCashController::index
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
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
* @see \App\Http\Controllers\AirtimeCashController::submit
 * @see app/Http/Controllers/AirtimeCashController.php:109
 * @route '/vtu/airtime-to-cash/submit'
 */
export const submit = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: submit.url(options),
    method: 'post',
})

submit.definition = {
    methods: ["post"],
    url: '/vtu/airtime-to-cash/submit',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AirtimeCashController::submit
 * @see app/Http/Controllers/AirtimeCashController.php:109
 * @route '/vtu/airtime-to-cash/submit'
 */
submit.url = (options?: RouteQueryOptions) => {
    return submit.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AirtimeCashController::submit
 * @see app/Http/Controllers/AirtimeCashController.php:109
 * @route '/vtu/airtime-to-cash/submit'
 */
submit.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: submit.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AirtimeCashController::submit
 * @see app/Http/Controllers/AirtimeCashController.php:109
 * @route '/vtu/airtime-to-cash/submit'
 */
    const submitForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: submit.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AirtimeCashController::submit
 * @see app/Http/Controllers/AirtimeCashController.php:109
 * @route '/vtu/airtime-to-cash/submit'
 */
        submitForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: submit.url(options),
            method: 'post',
        })
    
    submit.form = submitForm
const AirtimeCashController = { index, submit }

export default AirtimeCashController