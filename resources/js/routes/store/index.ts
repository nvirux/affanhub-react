import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
import mobileApp from './mobile-app'
/**
 * @see routes/tenant.php:120
 * @route '/admin/{any?}'
 */
export const admin = (args?: { any?: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: admin.url(args, options),
    method: 'get',
})

admin.definition = {
    methods: ["get","head"],
    url: '/admin/{any?}',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/tenant.php:120
 * @route '/admin/{any?}'
 */
admin.url = (args?: { any?: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { any: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    any: args[0],
                }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
            "any",
        ])

    const parsedArgs = {
                        any: args?.any,
                }

    return admin.definition.url
            .replace('{any?}', parsedArgs.any?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
 * @see routes/tenant.php:120
 * @route '/admin/{any?}'
 */
admin.get = (args?: { any?: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: admin.url(args, options),
    method: 'get',
})
/**
 * @see routes/tenant.php:120
 * @route '/admin/{any?}'
 */
admin.head = (args?: { any?: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: admin.url(args, options),
    method: 'head',
})
const store = {
    mobileApp: Object.assign(mobileApp, mobileApp),
admin: Object.assign(admin, admin),
}

export default store