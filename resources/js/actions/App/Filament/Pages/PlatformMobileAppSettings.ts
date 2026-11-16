import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Filament\Pages\PlatformMobileAppSettings::__invoke
 * @see app/Filament/Pages/PlatformMobileAppSettings.php:7
 * @route '//admin.localhost/platform-mobile-app-settings'
 */
const PlatformMobileAppSettings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PlatformMobileAppSettings.url(options),
    method: 'get',
})

PlatformMobileAppSettings.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/platform-mobile-app-settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\PlatformMobileAppSettings::__invoke
 * @see app/Filament/Pages/PlatformMobileAppSettings.php:7
 * @route '//admin.localhost/platform-mobile-app-settings'
 */
PlatformMobileAppSettings.url = (options?: RouteQueryOptions) => {
    return PlatformMobileAppSettings.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\PlatformMobileAppSettings::__invoke
 * @see app/Filament/Pages/PlatformMobileAppSettings.php:7
 * @route '//admin.localhost/platform-mobile-app-settings'
 */
PlatformMobileAppSettings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PlatformMobileAppSettings.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\PlatformMobileAppSettings::__invoke
 * @see app/Filament/Pages/PlatformMobileAppSettings.php:7
 * @route '//admin.localhost/platform-mobile-app-settings'
 */
PlatformMobileAppSettings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: PlatformMobileAppSettings.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\PlatformMobileAppSettings::__invoke
 * @see app/Filament/Pages/PlatformMobileAppSettings.php:7
 * @route '//admin.localhost/platform-mobile-app-settings'
 */
    const PlatformMobileAppSettingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: PlatformMobileAppSettings.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\PlatformMobileAppSettings::__invoke
 * @see app/Filament/Pages/PlatformMobileAppSettings.php:7
 * @route '//admin.localhost/platform-mobile-app-settings'
 */
        PlatformMobileAppSettingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: PlatformMobileAppSettings.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\PlatformMobileAppSettings::__invoke
 * @see app/Filament/Pages/PlatformMobileAppSettings.php:7
 * @route '//admin.localhost/platform-mobile-app-settings'
 */
        PlatformMobileAppSettingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: PlatformMobileAppSettings.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    PlatformMobileAppSettings.form = PlatformMobileAppSettingsForm
export default PlatformMobileAppSettings