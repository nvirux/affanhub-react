import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../wayfinder'
/**
 * @see routes/web.php:62
 * @route '//{central_domain}/privacy-policy'
 */
export const privacyPolicy = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: privacyPolicy.url(args, options),
    method: 'get',
})

privacyPolicy.definition = {
    methods: ["get","head"],
    url: '//{central_domain}/privacy-policy',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web.php:62
 * @route '//{central_domain}/privacy-policy'
 */
privacyPolicy.url = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { central_domain: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    central_domain: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        central_domain: args.central_domain,
                }

    return privacyPolicy.definition.url
            .replace('{central_domain}', parsedArgs.central_domain.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
 * @see routes/web.php:62
 * @route '//{central_domain}/privacy-policy'
 */
privacyPolicy.get = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: privacyPolicy.url(args, options),
    method: 'get',
})
/**
 * @see routes/web.php:62
 * @route '//{central_domain}/privacy-policy'
 */
privacyPolicy.head = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: privacyPolicy.url(args, options),
    method: 'head',
})
const central = {
    privacyPolicy: Object.assign(privacyPolicy, privacyPolicy),
}

export default central