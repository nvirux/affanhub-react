import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::handle
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:21
 * @route '/webhooks/paymint'
 */
export const handle = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: handle.url(options),
    method: 'post',
})

handle.definition = {
    methods: ["post"],
    url: '/webhooks/paymint',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::handle
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:21
 * @route '/webhooks/paymint'
 */
handle.url = (options?: RouteQueryOptions) => {
    return handle.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::handle
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:21
 * @route '/webhooks/paymint'
 */
handle.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: handle.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::handle
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:21
 * @route '/webhooks/paymint'
 */
    const handleForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: handle.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::handle
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:21
 * @route '/webhooks/paymint'
 */
        handleForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: handle.url(options),
            method: 'post',
        })
    
    handle.form = handleForm
const PayMintWebhookController = { handle }

export default PayMintWebhookController