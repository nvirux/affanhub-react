import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
import setup90f0be from './setup'
/**
* @see \App\Http\Controllers\Auth\TransactionPinController::setup
 * @see app/Http/Controllers/Auth/TransactionPinController.php:16
 * @route '/setup-transaction-pin'
 */
export const setup = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: setup.url(options),
    method: 'get',
})

setup.definition = {
    methods: ["get","head"],
    url: '/setup-transaction-pin',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\TransactionPinController::setup
 * @see app/Http/Controllers/Auth/TransactionPinController.php:16
 * @route '/setup-transaction-pin'
 */
setup.url = (options?: RouteQueryOptions) => {
    return setup.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TransactionPinController::setup
 * @see app/Http/Controllers/Auth/TransactionPinController.php:16
 * @route '/setup-transaction-pin'
 */
setup.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: setup.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Auth\TransactionPinController::setup
 * @see app/Http/Controllers/Auth/TransactionPinController.php:16
 * @route '/setup-transaction-pin'
 */
setup.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: setup.url(options),
    method: 'head',
})
const transactionPin = {
    setup: Object.assign(setup, setup90f0be),
}

export default transactionPin