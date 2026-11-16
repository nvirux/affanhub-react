import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
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

    /**
* @see \App\Filament\Resources\SettlementAccounts\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Resources/SettlementAccounts/Pages/ListSettlementAccounts.php:7
 * @route '//admin.localhost/settlement-accounts'
 */
    const ListSettlementAccountsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListSettlementAccounts.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\SettlementAccounts\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Resources/SettlementAccounts/Pages/ListSettlementAccounts.php:7
 * @route '//admin.localhost/settlement-accounts'
 */
        ListSettlementAccountsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListSettlementAccounts.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\SettlementAccounts\Pages\ListSettlementAccounts::__invoke
 * @see app/Filament/Resources/SettlementAccounts/Pages/ListSettlementAccounts.php:7
 * @route '//admin.localhost/settlement-accounts'
 */
        ListSettlementAccountsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListSettlementAccounts.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListSettlementAccounts.form = ListSettlementAccountsForm
export default ListSettlementAccounts