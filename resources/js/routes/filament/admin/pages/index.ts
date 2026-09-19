import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
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
const pages = {
    platformPaymentSettings: Object.assign(platformPaymentSettings, platformPaymentSettings),
dashboard: Object.assign(dashboard, dashboard),
}

export default pages