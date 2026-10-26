import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Filament\Pages\PlatformMobileAppSettings::__invoke
 * @see app/Filament/Pages/PlatformMobileAppSettings.php:7
 * @route '//admin.localhost/platform-mobile-app-settings'
 */
export const platformMobileAppSettings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: platformMobileAppSettings.url(options),
    method: 'get',
})

platformMobileAppSettings.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/platform-mobile-app-settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\PlatformMobileAppSettings::__invoke
 * @see app/Filament/Pages/PlatformMobileAppSettings.php:7
 * @route '//admin.localhost/platform-mobile-app-settings'
 */
platformMobileAppSettings.url = (options?: RouteQueryOptions) => {
    return platformMobileAppSettings.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\PlatformMobileAppSettings::__invoke
 * @see app/Filament/Pages/PlatformMobileAppSettings.php:7
 * @route '//admin.localhost/platform-mobile-app-settings'
 */
platformMobileAppSettings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: platformMobileAppSettings.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\PlatformMobileAppSettings::__invoke
 * @see app/Filament/Pages/PlatformMobileAppSettings.php:7
 * @route '//admin.localhost/platform-mobile-app-settings'
 */
platformMobileAppSettings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: platformMobileAppSettings.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\PlatformMobileAppSettings::__invoke
 * @see app/Filament/Pages/PlatformMobileAppSettings.php:7
 * @route '//admin.localhost/platform-mobile-app-settings'
 */
    const platformMobileAppSettingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: platformMobileAppSettings.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\PlatformMobileAppSettings::__invoke
 * @see app/Filament/Pages/PlatformMobileAppSettings.php:7
 * @route '//admin.localhost/platform-mobile-app-settings'
 */
        platformMobileAppSettingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: platformMobileAppSettings.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\PlatformMobileAppSettings::__invoke
 * @see app/Filament/Pages/PlatformMobileAppSettings.php:7
 * @route '//admin.localhost/platform-mobile-app-settings'
 */
        platformMobileAppSettingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: platformMobileAppSettings.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    platformMobileAppSettings.form = platformMobileAppSettingsForm
/**
* @see \App\Filament\Pages\PlatformPaymentSettings::__invoke
 * @see app/Filament/Pages/PlatformPaymentSettings.php:7
 * @route '//admin.localhost/platform-payment-settings'
 */
export const platformPaymentSettings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: platformPaymentSettings.url(options),
    method: 'get',
})

platformPaymentSettings.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/platform-payment-settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\PlatformPaymentSettings::__invoke
 * @see app/Filament/Pages/PlatformPaymentSettings.php:7
 * @route '//admin.localhost/platform-payment-settings'
 */
platformPaymentSettings.url = (options?: RouteQueryOptions) => {
    return platformPaymentSettings.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\PlatformPaymentSettings::__invoke
 * @see app/Filament/Pages/PlatformPaymentSettings.php:7
 * @route '//admin.localhost/platform-payment-settings'
 */
platformPaymentSettings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: platformPaymentSettings.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\PlatformPaymentSettings::__invoke
 * @see app/Filament/Pages/PlatformPaymentSettings.php:7
 * @route '//admin.localhost/platform-payment-settings'
 */
platformPaymentSettings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: platformPaymentSettings.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\PlatformPaymentSettings::__invoke
 * @see app/Filament/Pages/PlatformPaymentSettings.php:7
 * @route '//admin.localhost/platform-payment-settings'
 */
    const platformPaymentSettingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: platformPaymentSettings.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\PlatformPaymentSettings::__invoke
 * @see app/Filament/Pages/PlatformPaymentSettings.php:7
 * @route '//admin.localhost/platform-payment-settings'
 */
        platformPaymentSettingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: platformPaymentSettings.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\PlatformPaymentSettings::__invoke
 * @see app/Filament/Pages/PlatformPaymentSettings.php:7
 * @route '//admin.localhost/platform-payment-settings'
 */
        platformPaymentSettingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: platformPaymentSettings.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    platformPaymentSettings.form = platformPaymentSettingsForm
/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//admin.localhost'
 */
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '//admin.localhost',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//admin.localhost'
 */
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//admin.localhost'
 */
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})
/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//admin.localhost'
 */
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

    /**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//admin.localhost'
 */
    const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: dashboard.url(options),
        method: 'get',
    })

            /**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//admin.localhost'
 */
        dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url(options),
            method: 'get',
        })
            /**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//admin.localhost'
 */
        dashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    dashboard.form = dashboardForm
const pages = {
    platformMobileAppSettings: Object.assign(platformMobileAppSettings, platformMobileAppSettings),
platformPaymentSettings: Object.assign(platformPaymentSettings, platformPaymentSettings),
dashboard: Object.assign(dashboard, dashboard),
}

export default pages