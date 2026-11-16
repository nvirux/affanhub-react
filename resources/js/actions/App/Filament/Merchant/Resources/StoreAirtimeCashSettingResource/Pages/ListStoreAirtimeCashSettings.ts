import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\StoreAirtimeCashSettingResource\Pages\ListStoreAirtimeCashSettings::__invoke
 * @see app/Filament/Merchant/Resources/StoreAirtimeCashSettingResource/Pages/ListStoreAirtimeCashSettings.php:7
 * @route '//merchant.localhost/{tenant}/airtime-cash-settings'
 */
const ListStoreAirtimeCashSettings = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListStoreAirtimeCashSettings.url(args, options),
    method: 'get',
})

ListStoreAirtimeCashSettings.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/airtime-cash-settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\StoreAirtimeCashSettingResource\Pages\ListStoreAirtimeCashSettings::__invoke
 * @see app/Filament/Merchant/Resources/StoreAirtimeCashSettingResource/Pages/ListStoreAirtimeCashSettings.php:7
 * @route '//merchant.localhost/{tenant}/airtime-cash-settings'
 */
ListStoreAirtimeCashSettings.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return ListStoreAirtimeCashSettings.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\StoreAirtimeCashSettingResource\Pages\ListStoreAirtimeCashSettings::__invoke
 * @see app/Filament/Merchant/Resources/StoreAirtimeCashSettingResource/Pages/ListStoreAirtimeCashSettings.php:7
 * @route '//merchant.localhost/{tenant}/airtime-cash-settings'
 */
ListStoreAirtimeCashSettings.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListStoreAirtimeCashSettings.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\StoreAirtimeCashSettingResource\Pages\ListStoreAirtimeCashSettings::__invoke
 * @see app/Filament/Merchant/Resources/StoreAirtimeCashSettingResource/Pages/ListStoreAirtimeCashSettings.php:7
 * @route '//merchant.localhost/{tenant}/airtime-cash-settings'
 */
ListStoreAirtimeCashSettings.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListStoreAirtimeCashSettings.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\StoreAirtimeCashSettingResource\Pages\ListStoreAirtimeCashSettings::__invoke
 * @see app/Filament/Merchant/Resources/StoreAirtimeCashSettingResource/Pages/ListStoreAirtimeCashSettings.php:7
 * @route '//merchant.localhost/{tenant}/airtime-cash-settings'
 */
    const ListStoreAirtimeCashSettingsForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListStoreAirtimeCashSettings.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\StoreAirtimeCashSettingResource\Pages\ListStoreAirtimeCashSettings::__invoke
 * @see app/Filament/Merchant/Resources/StoreAirtimeCashSettingResource/Pages/ListStoreAirtimeCashSettings.php:7
 * @route '//merchant.localhost/{tenant}/airtime-cash-settings'
 */
        ListStoreAirtimeCashSettingsForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListStoreAirtimeCashSettings.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\StoreAirtimeCashSettingResource\Pages\ListStoreAirtimeCashSettings::__invoke
 * @see app/Filament/Merchant/Resources/StoreAirtimeCashSettingResource/Pages/ListStoreAirtimeCashSettings.php:7
 * @route '//merchant.localhost/{tenant}/airtime-cash-settings'
 */
        ListStoreAirtimeCashSettingsForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListStoreAirtimeCashSettings.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListStoreAirtimeCashSettings.form = ListStoreAirtimeCashSettingsForm
export default ListStoreAirtimeCashSettings