import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\WithdrawalResource\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Merchant/Resources/WithdrawalResource/Pages/ListWithdrawals.php:7
 * @route '//merchant.localhost/{tenant}/withdrawals'
 */
const ListWithdrawals = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListWithdrawals.url(args, options),
    method: 'get',
})

ListWithdrawals.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/withdrawals',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\WithdrawalResource\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Merchant/Resources/WithdrawalResource/Pages/ListWithdrawals.php:7
 * @route '//merchant.localhost/{tenant}/withdrawals'
 */
ListWithdrawals.url = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ListWithdrawals.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\WithdrawalResource\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Merchant/Resources/WithdrawalResource/Pages/ListWithdrawals.php:7
 * @route '//merchant.localhost/{tenant}/withdrawals'
 */
ListWithdrawals.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListWithdrawals.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\WithdrawalResource\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Merchant/Resources/WithdrawalResource/Pages/ListWithdrawals.php:7
 * @route '//merchant.localhost/{tenant}/withdrawals'
 */
ListWithdrawals.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListWithdrawals.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\WithdrawalResource\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Merchant/Resources/WithdrawalResource/Pages/ListWithdrawals.php:7
 * @route '//merchant.localhost/{tenant}/withdrawals'
 */
    const ListWithdrawalsForm = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListWithdrawals.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\WithdrawalResource\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Merchant/Resources/WithdrawalResource/Pages/ListWithdrawals.php:7
 * @route '//merchant.localhost/{tenant}/withdrawals'
 */
        ListWithdrawalsForm.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListWithdrawals.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\WithdrawalResource\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Merchant/Resources/WithdrawalResource/Pages/ListWithdrawals.php:7
 * @route '//merchant.localhost/{tenant}/withdrawals'
 */
        ListWithdrawalsForm.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListWithdrawals.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListWithdrawals.form = ListWithdrawalsForm
export default ListWithdrawals