import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Merchant\CloudflareDomainConnectController::connect
 * @see app/Http/Controllers/Merchant/CloudflareDomainConnectController.php:23
 * @route '/merchant/cloudflare/connect/{domain}'
 */
export const connect = (args: { domain: number | { id: number } } | [domain: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: connect.url(args, options),
    method: 'get',
})

connect.definition = {
    methods: ["get","head"],
    url: '/merchant/cloudflare/connect/{domain}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Merchant\CloudflareDomainConnectController::connect
 * @see app/Http/Controllers/Merchant/CloudflareDomainConnectController.php:23
 * @route '/merchant/cloudflare/connect/{domain}'
 */
connect.url = (args: { domain: number | { id: number } } | [domain: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { domain: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { domain: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    domain: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        domain: typeof args.domain === 'object'
                ? args.domain.id
                : args.domain,
                }

    return connect.definition.url
            .replace('{domain}', parsedArgs.domain.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Merchant\CloudflareDomainConnectController::connect
 * @see app/Http/Controllers/Merchant/CloudflareDomainConnectController.php:23
 * @route '/merchant/cloudflare/connect/{domain}'
 */
connect.get = (args: { domain: number | { id: number } } | [domain: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: connect.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Merchant\CloudflareDomainConnectController::connect
 * @see app/Http/Controllers/Merchant/CloudflareDomainConnectController.php:23
 * @route '/merchant/cloudflare/connect/{domain}'
 */
connect.head = (args: { domain: number | { id: number } } | [domain: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: connect.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Merchant\CloudflareDomainConnectController::connect
 * @see app/Http/Controllers/Merchant/CloudflareDomainConnectController.php:23
 * @route '/merchant/cloudflare/connect/{domain}'
 */
    const connectForm = (args: { domain: number | { id: number } } | [domain: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: connect.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Merchant\CloudflareDomainConnectController::connect
 * @see app/Http/Controllers/Merchant/CloudflareDomainConnectController.php:23
 * @route '/merchant/cloudflare/connect/{domain}'
 */
        connectForm.get = (args: { domain: number | { id: number } } | [domain: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: connect.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Merchant\CloudflareDomainConnectController::connect
 * @see app/Http/Controllers/Merchant/CloudflareDomainConnectController.php:23
 * @route '/merchant/cloudflare/connect/{domain}'
 */
        connectForm.head = (args: { domain: number | { id: number } } | [domain: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: connect.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    connect.form = connectForm
/**
* @see \App\Http\Controllers\Merchant\CloudflareDomainConnectController::callback
 * @see app/Http/Controllers/Merchant/CloudflareDomainConnectController.php:70
 * @route '/merchant/cloudflare/callback'
 */
export const callback = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: callback.url(options),
    method: 'get',
})

callback.definition = {
    methods: ["get","head"],
    url: '/merchant/cloudflare/callback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Merchant\CloudflareDomainConnectController::callback
 * @see app/Http/Controllers/Merchant/CloudflareDomainConnectController.php:70
 * @route '/merchant/cloudflare/callback'
 */
callback.url = (options?: RouteQueryOptions) => {
    return callback.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Merchant\CloudflareDomainConnectController::callback
 * @see app/Http/Controllers/Merchant/CloudflareDomainConnectController.php:70
 * @route '/merchant/cloudflare/callback'
 */
callback.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: callback.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Merchant\CloudflareDomainConnectController::callback
 * @see app/Http/Controllers/Merchant/CloudflareDomainConnectController.php:70
 * @route '/merchant/cloudflare/callback'
 */
callback.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: callback.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Merchant\CloudflareDomainConnectController::callback
 * @see app/Http/Controllers/Merchant/CloudflareDomainConnectController.php:70
 * @route '/merchant/cloudflare/callback'
 */
    const callbackForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: callback.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Merchant\CloudflareDomainConnectController::callback
 * @see app/Http/Controllers/Merchant/CloudflareDomainConnectController.php:70
 * @route '/merchant/cloudflare/callback'
 */
        callbackForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: callback.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Merchant\CloudflareDomainConnectController::callback
 * @see app/Http/Controllers/Merchant/CloudflareDomainConnectController.php:70
 * @route '/merchant/cloudflare/callback'
 */
        callbackForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: callback.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    callback.form = callbackForm
const CloudflareDomainConnectController = { connect, callback }

export default CloudflareDomainConnectController