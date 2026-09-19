import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Transactions\Pages\ListTransactions::__invoke
 * @see app/Filament/Resources/Transactions/Pages/ListTransactions.php:7
 * @route '//admin.localhost/transactions'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/transactions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Transactions\Pages\ListTransactions::__invoke
 * @see app/Filament/Resources/Transactions/Pages/ListTransactions.php:7
 * @route '//admin.localhost/transactions'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Transactions\Pages\ListTransactions::__invoke
 * @see app/Filament/Resources/Transactions/Pages/ListTransactions.php:7
 * @route '//admin.localhost/transactions'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Transactions\Pages\ListTransactions::__invoke
 * @see app/Filament/Resources/Transactions/Pages/ListTransactions.php:7
 * @route '//admin.localhost/transactions'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})
const transactions = {
    index: Object.assign(index, index),
}

export default transactions