import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::handle
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:19
 * @route '/webhooks/paymint'
 */
const handle4db542e154b257599d72fa166c3b9904 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: handle4db542e154b257599d72fa166c3b9904.url(options),
    method: 'post',
})

handle4db542e154b257599d72fa166c3b9904.definition = {
    methods: ["post"],
    url: '/webhooks/paymint',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::handle
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:19
 * @route '/webhooks/paymint'
 */
handle4db542e154b257599d72fa166c3b9904.url = (options?: RouteQueryOptions) => {
    return handle4db542e154b257599d72fa166c3b9904.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::handle
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:19
 * @route '/webhooks/paymint'
 */
handle4db542e154b257599d72fa166c3b9904.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: handle4db542e154b257599d72fa166c3b9904.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::handle
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:19
 * @route '/webhooks/paymint'
 */
    const handle4db542e154b257599d72fa166c3b9904Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: handle4db542e154b257599d72fa166c3b9904.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::handle
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:19
 * @route '/webhooks/paymint'
 */
        handle4db542e154b257599d72fa166c3b9904Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: handle4db542e154b257599d72fa166c3b9904.url(options),
            method: 'post',
        })
    
    handle4db542e154b257599d72fa166c3b9904.form = handle4db542e154b257599d72fa166c3b9904Form
    /**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::handle
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:19
 * @route '/api/webhooks/paymint'
 */
const handleca17042d4c8e9ecda0e6d2ede1ac85f1 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: handleca17042d4c8e9ecda0e6d2ede1ac85f1.url(options),
    method: 'post',
})

handleca17042d4c8e9ecda0e6d2ede1ac85f1.definition = {
    methods: ["post"],
    url: '/api/webhooks/paymint',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::handle
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:19
 * @route '/api/webhooks/paymint'
 */
handleca17042d4c8e9ecda0e6d2ede1ac85f1.url = (options?: RouteQueryOptions) => {
    return handleca17042d4c8e9ecda0e6d2ede1ac85f1.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::handle
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:19
 * @route '/api/webhooks/paymint'
 */
handleca17042d4c8e9ecda0e6d2ede1ac85f1.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: handleca17042d4c8e9ecda0e6d2ede1ac85f1.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::handle
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:19
 * @route '/api/webhooks/paymint'
 */
    const handleca17042d4c8e9ecda0e6d2ede1ac85f1Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: handleca17042d4c8e9ecda0e6d2ede1ac85f1.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::handle
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:19
 * @route '/api/webhooks/paymint'
 */
        handleca17042d4c8e9ecda0e6d2ede1ac85f1Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: handleca17042d4c8e9ecda0e6d2ede1ac85f1.url(options),
            method: 'post',
        })
    
    handleca17042d4c8e9ecda0e6d2ede1ac85f1.form = handleca17042d4c8e9ecda0e6d2ede1ac85f1Form

/**
* Multiple routes resolve to \App\Http\Controllers\Webhook\PayMintWebhookController::handle, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `handle['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const handle = {
    '/webhooks/paymint': handle4db542e154b257599d72fa166c3b9904,
    '/api/webhooks/paymint': handleca17042d4c8e9ecda0e6d2ede1ac85f1,
}

const PayMintWebhookController = { handle }

export default PayMintWebhookController