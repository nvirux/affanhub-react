import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\TransactionPinController::create
 * @see app/Http/Controllers/Auth/TransactionPinController.php:16
 * @route '/setup-transaction-pin'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/setup-transaction-pin',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\TransactionPinController::create
 * @see app/Http/Controllers/Auth/TransactionPinController.php:16
 * @route '/setup-transaction-pin'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TransactionPinController::create
 * @see app/Http/Controllers/Auth/TransactionPinController.php:16
 * @route '/setup-transaction-pin'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Auth\TransactionPinController::create
 * @see app/Http/Controllers/Auth/TransactionPinController.php:16
 * @route '/setup-transaction-pin'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\TransactionPinController::store
 * @see app/Http/Controllers/Auth/TransactionPinController.php:30
 * @route '/setup-transaction-pin'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/setup-transaction-pin',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\TransactionPinController::store
 * @see app/Http/Controllers/Auth/TransactionPinController.php:30
 * @route '/setup-transaction-pin'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TransactionPinController::store
 * @see app/Http/Controllers/Auth/TransactionPinController.php:30
 * @route '/setup-transaction-pin'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})
const TransactionPinController = { create, store }

export default TransactionPinController