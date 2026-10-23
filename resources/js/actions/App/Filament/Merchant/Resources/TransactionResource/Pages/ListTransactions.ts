import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\TransactionResource\Pages\ListTransactions::__invoke
 * @see app/Filament/Merchant/Resources/TransactionResource/Pages/ListTransactions.php:7
 * @route '//merchant.localhost/{tenant}/transactions'
 */
const ListTransactions = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListTransactions.url(args, options),
    method: 'get',
})

ListTransactions.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/transactions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\TransactionResource\Pages\ListTransactions::__invoke
 * @see app/Filament/Merchant/Resources/TransactionResource/Pages/ListTransactions.php:7
 * @route '//merchant.localhost/{tenant}/transactions'
 */
ListTransactions.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return ListTransactions.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\TransactionResource\Pages\ListTransactions::__invoke
 * @see app/Filament/Merchant/Resources/TransactionResource/Pages/ListTransactions.php:7
 * @route '//merchant.localhost/{tenant}/transactions'
 */
ListTransactions.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListTransactions.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\TransactionResource\Pages\ListTransactions::__invoke
 * @see app/Filament/Merchant/Resources/TransactionResource/Pages/ListTransactions.php:7
 * @route '//merchant.localhost/{tenant}/transactions'
 */
ListTransactions.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListTransactions.url(args, options),
    method: 'head',
})
export default ListTransactions