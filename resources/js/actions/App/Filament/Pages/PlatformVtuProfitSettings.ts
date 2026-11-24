import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Filament\Pages\PlatformVtuProfitSettings::__invoke
 * @see app/Filament/Pages/PlatformVtuProfitSettings.php:7
 * @route '//admin.localhost/platform-vtu-profit-settings'
 */
const PlatformVtuProfitSettings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PlatformVtuProfitSettings.url(options),
    method: 'get',
})

PlatformVtuProfitSettings.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/platform-vtu-profit-settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\PlatformVtuProfitSettings::__invoke
 * @see app/Filament/Pages/PlatformVtuProfitSettings.php:7
 * @route '//admin.localhost/platform-vtu-profit-settings'
 */
PlatformVtuProfitSettings.url = (options?: RouteQueryOptions) => {
    return PlatformVtuProfitSettings.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\PlatformVtuProfitSettings::__invoke
 * @see app/Filament/Pages/PlatformVtuProfitSettings.php:7
 * @route '//admin.localhost/platform-vtu-profit-settings'
 */
PlatformVtuProfitSettings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PlatformVtuProfitSettings.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\PlatformVtuProfitSettings::__invoke
 * @see app/Filament/Pages/PlatformVtuProfitSettings.php:7
 * @route '//admin.localhost/platform-vtu-profit-settings'
 */
PlatformVtuProfitSettings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: PlatformVtuProfitSettings.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\PlatformVtuProfitSettings::__invoke
 * @see app/Filament/Pages/PlatformVtuProfitSettings.php:7
 * @route '//admin.localhost/platform-vtu-profit-settings'
 */
    const PlatformVtuProfitSettingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: PlatformVtuProfitSettings.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\PlatformVtuProfitSettings::__invoke
 * @see app/Filament/Pages/PlatformVtuProfitSettings.php:7
 * @route '//admin.localhost/platform-vtu-profit-settings'
 */
        PlatformVtuProfitSettingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: PlatformVtuProfitSettings.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\PlatformVtuProfitSettings::__invoke
 * @see app/Filament/Pages/PlatformVtuProfitSettings.php:7
 * @route '//admin.localhost/platform-vtu-profit-settings'
 */
        PlatformVtuProfitSettingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: PlatformVtuProfitSettings.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    PlatformVtuProfitSettings.form = PlatformVtuProfitSettingsForm
export default PlatformVtuProfitSettings