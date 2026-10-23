import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Pages\PushNotifications::__invoke
 * @see app/Filament/Merchant/Pages/PushNotifications.php:7
 * @route '//merchant.localhost/{tenant}/push-notifications'
 */
const PushNotifications = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PushNotifications.url(args, options),
    method: 'get',
})

PushNotifications.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/push-notifications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\PushNotifications::__invoke
 * @see app/Filament/Merchant/Pages/PushNotifications.php:7
 * @route '//merchant.localhost/{tenant}/push-notifications'
 */
PushNotifications.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return PushNotifications.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\PushNotifications::__invoke
 * @see app/Filament/Merchant/Pages/PushNotifications.php:7
 * @route '//merchant.localhost/{tenant}/push-notifications'
 */
PushNotifications.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PushNotifications.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\PushNotifications::__invoke
 * @see app/Filament/Merchant/Pages/PushNotifications.php:7
 * @route '//merchant.localhost/{tenant}/push-notifications'
 */
PushNotifications.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: PushNotifications.url(args, options),
    method: 'head',
})
export default PushNotifications