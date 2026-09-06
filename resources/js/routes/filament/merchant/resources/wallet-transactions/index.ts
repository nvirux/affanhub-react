import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\WalletTransactionResource\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Merchant/Resources/WalletTransactionResource/Pages/ListWalletTransactions.php:7
 * @route '//merchant.localhost/{tenant}/wallet-transactions'
 */
export const index = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/wallet-transactions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\WalletTransactionResource\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Merchant/Resources/WalletTransactionResource/Pages/ListWalletTransactions.php:7
 * @route '//merchant.localhost/{tenant}/wallet-transactions'
 */
index.url = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: args.tenant,
                }

    return index.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\WalletTransactionResource\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Merchant/Resources/WalletTransactionResource/Pages/ListWalletTransactions.php:7
 * @route '//merchant.localhost/{tenant}/wallet-transactions'
 */
index.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\WalletTransactionResource\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Merchant/Resources/WalletTransactionResource/Pages/ListWalletTransactions.php:7
 * @route '//merchant.localhost/{tenant}/wallet-transactions'
 */
index.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\WalletTransactionResource\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Merchant/Resources/WalletTransactionResource/Pages/ListWalletTransactions.php:7
 * @route '//merchant.localhost/{tenant}/wallet-transactions'
 */
    const indexForm = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\WalletTransactionResource\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Merchant/Resources/WalletTransactionResource/Pages/ListWalletTransactions.php:7
 * @route '//merchant.localhost/{tenant}/wallet-transactions'
 */
        indexForm.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\WalletTransactionResource\Pages\ListWalletTransactions::__invoke
 * @see app/Filament/Merchant/Resources/WalletTransactionResource/Pages/ListWalletTransactions.php:7
 * @route '//merchant.localhost/{tenant}/wallet-transactions'
 */
        indexForm.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
const walletTransactions = {
    index: Object.assign(index, index),
}

export default walletTransactions