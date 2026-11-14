import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\SettlementAccounts\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Resources/SettlementAccounts/Pages/ListSettlementAccounts.php:7
 * @route '//admin.localhost/settlement-accounts'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/settlement-accounts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\SettlementAccounts\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Resources/SettlementAccounts/Pages/ListSettlementAccounts.php:7
 * @route '//admin.localhost/settlement-accounts'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\SettlementAccounts\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Resources/SettlementAccounts/Pages/ListSettlementAccounts.php:7
 * @route '//admin.localhost/settlement-accounts'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\SettlementAccounts\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Resources/SettlementAccounts/Pages/ListSettlementAccounts.php:7
 * @route '//admin.localhost/settlement-accounts'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})
const settlementAccounts = {
    index: Object.assign(index, index),
}

export default settlementAccounts