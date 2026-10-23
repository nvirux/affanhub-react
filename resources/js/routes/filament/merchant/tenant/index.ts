import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Pages\RegisterStore::__invoke
 * @see app/Filament/Merchant/Pages/RegisterStore.php:7
 * @route '//merchant.localhost/new'
 */
export const registration = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: registration.url(options),
    method: 'get',
})

registration.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/new',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\RegisterStore::__invoke
 * @see app/Filament/Merchant/Pages/RegisterStore.php:7
 * @route '//merchant.localhost/new'
 */
registration.url = (options?: RouteQueryOptions) => {
    return registration.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\RegisterStore::__invoke
 * @see app/Filament/Merchant/Pages/RegisterStore.php:7
 * @route '//merchant.localhost/new'
 */
registration.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: registration.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\RegisterStore::__invoke
 * @see app/Filament/Merchant/Pages/RegisterStore.php:7
 * @route '//merchant.localhost/new'
 */
registration.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: registration.url(options),
    method: 'head',
})