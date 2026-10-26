import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::create
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/auth/complete-phone',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::create
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::create
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::create
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::create
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::create
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Auth\MerchantPhoneCompletionController::create
 * @see app/Http/Controllers/Auth/MerchantPhoneCompletionController.php:16
 * @route '/auth/complete-phone'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
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
const MerchantPhoneCompletionController = { create, store }

export default MerchantPhoneCompletionController