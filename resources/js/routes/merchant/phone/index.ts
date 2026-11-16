import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::complete
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
export const complete = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: complete.url(options),
    method: 'get',
})

complete.definition = {
    methods: ["get","head"],
    url: '/auth/complete-phone',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::complete
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
complete.url = (options?: RouteQueryOptions) => {
    return complete.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::complete
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
complete.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: complete.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::complete
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
complete.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: complete.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::complete
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
    const completeForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: complete.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::complete
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
        completeForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: complete.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::complete
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
        completeForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: complete.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    complete.form = completeForm
/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::store
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:41
 * @route '/auth/complete-phone'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/auth/complete-phone',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::store
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:41
 * @route '/auth/complete-phone'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::store
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:41
 * @route '/auth/complete-phone'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::store
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:41
 * @route '/auth/complete-phone'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::store
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:41
 * @route '/auth/complete-phone'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
const phone = {
    complete: Object.assign(complete, complete),
store: Object.assign(store, store),
}

export default phone