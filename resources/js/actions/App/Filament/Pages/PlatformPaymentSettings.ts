import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \App\Filament\Pages\PlatformPaymentSettings::__invoke
 * @see app/Filament/Pages/PlatformPaymentSettings.php:7
 * @route '//admin.localhost/platform-payment-settings'
 */
const PlatformPaymentSettings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PlatformPaymentSettings.url(options),
    method: 'get',
})

PlatformPaymentSettings.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/platform-payment-settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\PlatformPaymentSettings::__invoke
 * @see app/Filament/Pages/PlatformPaymentSettings.php:7
 * @route '//admin.localhost/platform-payment-settings'
 */
PlatformPaymentSettings.url = (options?: RouteQueryOptions) => {
    return PlatformPaymentSettings.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\PlatformPaymentSettings::__invoke
 * @see app/Filament/Pages/PlatformPaymentSettings.php:7
 * @route '//admin.localhost/platform-payment-settings'
 */
PlatformPaymentSettings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PlatformPaymentSettings.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\PlatformPaymentSettings::__invoke
 * @see app/Filament/Pages/PlatformPaymentSettings.php:7
 * @route '//admin.localhost/platform-payment-settings'
 */
PlatformPaymentSettings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: PlatformPaymentSettings.url(options),
    method: 'head',
})
export default PlatformPaymentSettings