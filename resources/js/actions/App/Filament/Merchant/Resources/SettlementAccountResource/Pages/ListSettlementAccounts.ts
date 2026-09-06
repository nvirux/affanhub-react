import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\SettlementAccountResource\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Merchant/Resources/SettlementAccountResource/Pages/ListSettlementAccounts.php:7
 * @route '//merchant.localhost/{tenant}/settlement-accounts'
 */
const ListSettlementAccounts = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSettlementAccounts.url(args, options),
    method: 'get',
})

ListSettlementAccounts.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/settlement-accounts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\SettlementAccountResource\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Merchant/Resources/SettlementAccountResource/Pages/ListSettlementAccounts.php:7
 * @route '//merchant.localhost/{tenant}/settlement-accounts'
 */
ListSettlementAccounts.url = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ListSettlementAccounts.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\SettlementAccountResource\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Merchant/Resources/SettlementAccountResource/Pages/ListSettlementAccounts.php:7
 * @route '//merchant.localhost/{tenant}/settlement-accounts'
 */
ListSettlementAccounts.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSettlementAccounts.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\SettlementAccountResource\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Merchant/Resources/SettlementAccountResource/Pages/ListSettlementAccounts.php:7
 * @route '//merchant.localhost/{tenant}/settlement-accounts'
 */
ListSettlementAccounts.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListSettlementAccounts.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\SettlementAccountResource\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Merchant/Resources/SettlementAccountResource/Pages/ListSettlementAccounts.php:7
 * @route '//merchant.localhost/{tenant}/settlement-accounts'
 */
    const ListSettlementAccountsForm = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListSettlementAccounts.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\SettlementAccountResource\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Merchant/Resources/SettlementAccountResource/Pages/ListSettlementAccounts.php:7
 * @route '//merchant.localhost/{tenant}/settlement-accounts'
 */
        ListSettlementAccountsForm.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListSettlementAccounts.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\SettlementAccountResource\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Merchant/Resources/SettlementAccountResource/Pages/ListSettlementAccounts.php:7
 * @route '//merchant.localhost/{tenant}/settlement-accounts'
 */
        ListSettlementAccountsForm.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListSettlementAccounts.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListSettlementAccounts.form = ListSettlementAccountsForm
export default ListSettlementAccounts