import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\SettlementAccounts\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Resources/SettlementAccounts/Pages/ListSettlementAccounts.php:7
 * @route '//admin.localhost/settlement-accounts'
 */
const ListSettlementAccounts = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSettlementAccounts.url(options),
    method: 'get',
})

ListSettlementAccounts.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/settlement-accounts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\SettlementAccounts\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Resources/SettlementAccounts/Pages/ListSettlementAccounts.php:7
 * @route '//admin.localhost/settlement-accounts'
 */
ListSettlementAccounts.url = (options?: RouteQueryOptions) => {
    return ListSettlementAccounts.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\SettlementAccounts\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Resources/SettlementAccounts/Pages/ListSettlementAccounts.php:7
 * @route '//admin.localhost/settlement-accounts'
 */
ListSettlementAccounts.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSettlementAccounts.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\SettlementAccounts\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Resources/SettlementAccounts/Pages/ListSettlementAccounts.php:7
 * @route '//admin.localhost/settlement-accounts'
 */
ListSettlementAccounts.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListSettlementAccounts.url(options),
    method: 'head',
})
export default ListSettlementAccounts