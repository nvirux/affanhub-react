import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\WalletTransactions\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Resources/WalletTransactions/Pages/ListWalletTransactions.php:7
 * @route '//admin.localhost/wallet-transactions'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/wallet-transactions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\WalletTransactions\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Resources/WalletTransactions/Pages/ListWalletTransactions.php:7
 * @route '//admin.localhost/wallet-transactions'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\WalletTransactions\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Resources/WalletTransactions/Pages/ListWalletTransactions.php:7
 * @route '//admin.localhost/wallet-transactions'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\WalletTransactions\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Resources/WalletTransactions/Pages/ListWalletTransactions.php:7
 * @route '//admin.localhost/wallet-transactions'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})
const walletTransactions = {
    index: Object.assign(index, index),
}

export default walletTransactions