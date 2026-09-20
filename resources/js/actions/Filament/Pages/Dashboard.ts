import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//admin.localhost'
 */
const Dashboard290de1a60ece11a70fd722ba71387a3e = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboard290de1a60ece11a70fd722ba71387a3e.url(options),
    method: 'get',
})

Dashboard290de1a60ece11a70fd722ba71387a3e.definition = {
    methods: ["get","head"],
    url: '//admin.localhost',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//admin.localhost'
 */
Dashboard290de1a60ece11a70fd722ba71387a3e.url = (options?: RouteQueryOptions) => {
    return Dashboard290de1a60ece11a70fd722ba71387a3e.definition.url + queryParams(options)
}

/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//admin.localhost'
 */
Dashboard290de1a60ece11a70fd722ba71387a3e.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboard290de1a60ece11a70fd722ba71387a3e.url(options),
    method: 'get',
})
/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//admin.localhost'
 */
Dashboard290de1a60ece11a70fd722ba71387a3e.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Dashboard290de1a60ece11a70fd722ba71387a3e.url(options),
    method: 'head',
})

    /**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//admin.localhost'
 */
    const Dashboard290de1a60ece11a70fd722ba71387a3eForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: Dashboard290de1a60ece11a70fd722ba71387a3e.url(options),
        method: 'get',
    })

            /**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//admin.localhost'
 */
        Dashboard290de1a60ece11a70fd722ba71387a3eForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: Dashboard290de1a60ece11a70fd722ba71387a3e.url(options),
            method: 'get',
        })
            /**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//admin.localhost'
 */
        Dashboard290de1a60ece11a70fd722ba71387a3eForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: Dashboard290de1a60ece11a70fd722ba71387a3e.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    Dashboard290de1a60ece11a70fd722ba71387a3e.form = Dashboard290de1a60ece11a70fd722ba71387a3eForm
    /**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
const Dashboardd1ad3750184e9a9225ff9cf5815168d4 = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboardd1ad3750184e9a9225ff9cf5815168d4.url(args, options),
    method: 'get',
})

Dashboardd1ad3750184e9a9225ff9cf5815168d4.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
Dashboardd1ad3750184e9a9225ff9cf5815168d4.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return Dashboardd1ad3750184e9a9225ff9cf5815168d4.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
Dashboardd1ad3750184e9a9225ff9cf5815168d4.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboardd1ad3750184e9a9225ff9cf5815168d4.url(args, options),
    method: 'get',
})
/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
Dashboardd1ad3750184e9a9225ff9cf5815168d4.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Dashboardd1ad3750184e9a9225ff9cf5815168d4.url(args, options),
    method: 'head',
})

    /**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
    const Dashboardd1ad3750184e9a9225ff9cf5815168d4Form = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: Dashboardd1ad3750184e9a9225ff9cf5815168d4.url(args, options),
        method: 'get',
    })

            /**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
        Dashboardd1ad3750184e9a9225ff9cf5815168d4Form.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: Dashboardd1ad3750184e9a9225ff9cf5815168d4.url(args, options),
            method: 'get',
        })
            /**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
        Dashboardd1ad3750184e9a9225ff9cf5815168d4Form.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: Dashboardd1ad3750184e9a9225ff9cf5815168d4.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    Dashboardd1ad3750184e9a9225ff9cf5815168d4.form = Dashboardd1ad3750184e9a9225ff9cf5815168d4Form

/**
* Multiple routes resolve to \Filament\Pages\Dashboard::Dashboard, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `Dashboard['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
const Dashboard = {
    '//admin.localhost': Dashboard290de1a60ece11a70fd722ba71387a3e,
    '//merchant.localhost/{tenant}': Dashboardd1ad3750184e9a9225ff9cf5815168d4,
}

export default Dashboard