import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\WalletTransactions\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Resources/WalletTransactions/Pages/ListWalletTransactions.php:7
 * @route '//admin.localhost/wallet-transactions'
 */
const ListWalletTransactions = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListWalletTransactions.url(options),
    method: 'get',
})

ListWalletTransactions.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/wallet-transactions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\WalletTransactions\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Resources/WalletTransactions/Pages/ListWalletTransactions.php:7
 * @route '//admin.localhost/wallet-transactions'
 */
ListWalletTransactions.url = (options?: RouteQueryOptions) => {
    return ListWalletTransactions.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\WalletTransactions\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Resources/WalletTransactions/Pages/ListWalletTransactions.php:7
 * @route '//admin.localhost/wallet-transactions'
 */
ListWalletTransactions.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListWalletTransactions.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\WalletTransactions\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Resources/WalletTransactions/Pages/ListWalletTransactions.php:7
 * @route '//admin.localhost/wallet-transactions'
 */
ListWalletTransactions.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListWalletTransactions.url(options),
    method: 'head',
})
export default ListWalletTransactions