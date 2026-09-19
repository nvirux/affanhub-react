import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\BillingCheckoutCallbackController::handle
 * @see app/Http/Controllers/BillingCheckoutCallbackController.php:21
 * @route '/billing/callback/{tenant}'
 */
export const handle = (args: { tenant: string | { public_id: string } } | [tenant: string | { public_id: string } ] | string | { public_id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: handle.url(args, options),
    method: 'get',
})

handle.definition = {
    methods: ["get","head"],
    url: '/billing/callback/{tenant}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\BillingCheckoutCallbackController::handle
 * @see app/Http/Controllers/BillingCheckoutCallbackController.php:21
 * @route '/billing/callback/{tenant}'
 */
handle.url = (args: { tenant: string | { public_id: string } } | [tenant: string | { public_id: string } ] | string | { public_id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'public_id' in args) {
            args = { tenant: args.public_id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: typeof args.tenant === 'object'
                ? args.tenant.public_id
                : args.tenant,
                }

    return handle.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\BillingCheckoutCallbackController::handle
 * @see app/Http/Controllers/BillingCheckoutCallbackController.php:21
 * @route '/billing/callback/{tenant}'
 */
handle.get = (args: { tenant: string | { public_id: string } } | [tenant: string | { public_id: string } ] | string | { public_id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: handle.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\BillingCheckoutCallbackController::handle
 * @see app/Http/Controllers/BillingCheckoutCallbackController.php:21
 * @route '/billing/callback/{tenant}'
 */
handle.head = (args: { tenant: string | { public_id: string } } | [tenant: string | { public_id: string } ] | string | { public_id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: handle.url(args, options),
    method: 'head',
})
const BillingCheckoutCallbackController = { handle }

export default BillingCheckoutCallbackController