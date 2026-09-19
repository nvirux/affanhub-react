import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::paymint
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:21
 * @route '/webhooks/paymint'
 */
export const paymint = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: paymint.url(options),
    method: 'post',
})

paymint.definition = {
    methods: ["post"],
    url: '/webhooks/paymint',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::paymint
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:21
 * @route '/webhooks/paymint'
 */
paymint.url = (options?: RouteQueryOptions) => {
    return paymint.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Webhook\PayMintWebhookController::paymint
 * @see app/Http/Controllers/Webhook/PayMintWebhookController.php:21
 * @route '/webhooks/paymint'
 */
paymint.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: paymint.url(options),
    method: 'post',
})
const webhooks = {
    paymint: Object.assign(paymint, paymint),
}

export default webhooks