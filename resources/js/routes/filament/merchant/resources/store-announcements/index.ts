import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\StoreAnnouncementResource\Pages\ListStoreAnnouncements::__invoke
 * @see app/Filament/Merchant/Resources/StoreAnnouncementResource/Pages/ListStoreAnnouncements.php:7
 * @route '//merchant.localhost/{tenant}/store-announcements'
 */
export const index = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/store-announcements',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\StoreAnnouncementResource\Pages\ListStoreAnnouncements::__invoke
 * @see app/Filament/Merchant/Resources/StoreAnnouncementResource/Pages/ListStoreAnnouncements.php:7
 * @route '//merchant.localhost/{tenant}/store-announcements'
 */
index.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return index.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\StoreAnnouncementResource\Pages\ListStoreAnnouncements::__invoke
 * @see app/Filament/Merchant/Resources/StoreAnnouncementResource/Pages/ListStoreAnnouncements.php:7
 * @route '//merchant.localhost/{tenant}/store-announcements'
 */
index.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\StoreAnnouncementResource\Pages\ListStoreAnnouncements::__invoke
 * @see app/Filament/Merchant/Resources/StoreAnnouncementResource/Pages/ListStoreAnnouncements.php:7
 * @route '//merchant.localhost/{tenant}/store-announcements'
 */
index.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})
const storeAnnouncements = {
    index: Object.assign(index, index),
}

export default storeAnnouncements