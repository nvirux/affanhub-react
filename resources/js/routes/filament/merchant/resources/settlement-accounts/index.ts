import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\SettlementAccountResource\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Merchant/Resources/SettlementAccountResource/Pages/ListSettlementAccounts.php:7
 * @route '//merchant.localhost/{tenant}/settlement-accounts'
 */
export const index = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/settlement-accounts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\SettlementAccountResource\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Merchant/Resources/SettlementAccountResource/Pages/ListSettlementAccounts.php:7
 * @route '//merchant.localhost/{tenant}/settlement-accounts'
 */
index.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return index.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\SettlementAccountResource\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Merchant/Resources/SettlementAccountResource/Pages/ListSettlementAccounts.php:7
 * @route '//merchant.localhost/{tenant}/settlement-accounts'
 */
index.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\SettlementAccountResource\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Merchant/Resources/SettlementAccountResource/Pages/ListSettlementAccounts.php:7
 * @route '//merchant.localhost/{tenant}/settlement-accounts'
 */
index.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\SettlementAccountResource\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Merchant/Resources/SettlementAccountResource/Pages/ListSettlementAccounts.php:7
 * @route '//merchant.localhost/{tenant}/settlement-accounts'
 */
    const indexForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\SettlementAccountResource\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Merchant/Resources/SettlementAccountResource/Pages/ListSettlementAccounts.php:7
 * @route '//merchant.localhost/{tenant}/settlement-accounts'
 */
        indexForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\SettlementAccountResource\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Merchant/Resources/SettlementAccountResource/Pages/ListSettlementAccounts.php:7
 * @route '//merchant.localhost/{tenant}/settlement-accounts'
 */
        indexForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
const settlementAccounts = {
    index: Object.assign(index, index),
}

export default settlementAccounts