import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Pages\Dashboard::__invoke
 * @see app/Filament/Merchant/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
const Dashboard = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboard.url(args, options),
    method: 'get',
})

Dashboard.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\Dashboard::__invoke
 * @see app/Filament/Merchant/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
Dashboard.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return Dashboard.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\Dashboard::__invoke
 * @see app/Filament/Merchant/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
Dashboard.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboard.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\Dashboard::__invoke
 * @see app/Filament/Merchant/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
Dashboard.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Dashboard.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\Dashboard::__invoke
 * @see app/Filament/Merchant/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
    const DashboardForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: Dashboard.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\Dashboard::__invoke
 * @see app/Filament/Merchant/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
        DashboardForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: Dashboard.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\Dashboard::__invoke
 * @see app/Filament/Merchant/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
        DashboardForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: Dashboard.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    Dashboard.form = DashboardForm
export default Dashboard