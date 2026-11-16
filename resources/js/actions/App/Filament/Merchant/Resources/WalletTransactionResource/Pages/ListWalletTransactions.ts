import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\WalletTransactionResource\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Merchant/Resources/WalletTransactionResource/Pages/ListWalletTransactions.php:7
 * @route '//merchant.localhost/{tenant}/wallet-transactions'
 */
const ListWalletTransactions = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListWalletTransactions.url(args, options),
    method: 'get',
})

ListWalletTransactions.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/wallet-transactions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\WalletTransactionResource\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Merchant/Resources/WalletTransactionResource/Pages/ListWalletTransactions.php:7
 * @route '//merchant.localhost/{tenant}/wallet-transactions'
 */
ListWalletTransactions.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return ListWalletTransactions.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\WalletTransactionResource\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Merchant/Resources/WalletTransactionResource/Pages/ListWalletTransactions.php:7
 * @route '//merchant.localhost/{tenant}/wallet-transactions'
 */
ListWalletTransactions.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListWalletTransactions.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\WalletTransactionResource\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Merchant/Resources/WalletTransactionResource/Pages/ListWalletTransactions.php:7
 * @route '//merchant.localhost/{tenant}/wallet-transactions'
 */
ListWalletTransactions.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListWalletTransactions.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\WalletTransactionResource\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Merchant/Resources/WalletTransactionResource/Pages/ListWalletTransactions.php:7
 * @route '//merchant.localhost/{tenant}/wallet-transactions'
 */
    const ListWalletTransactionsForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListWalletTransactions.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\WalletTransactionResource\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Merchant/Resources/WalletTransactionResource/Pages/ListWalletTransactions.php:7
 * @route '//merchant.localhost/{tenant}/wallet-transactions'
 */
        ListWalletTransactionsForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListWalletTransactions.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\WalletTransactionResource\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Merchant/Resources/WalletTransactionResource/Pages/ListWalletTransactions.php:7
 * @route '//merchant.localhost/{tenant}/wallet-transactions'
 */
        ListWalletTransactionsForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListWalletTransactions.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListWalletTransactions.form = ListWalletTransactionsForm
export default ListWalletTransactions