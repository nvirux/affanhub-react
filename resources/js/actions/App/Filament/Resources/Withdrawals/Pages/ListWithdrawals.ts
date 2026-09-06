import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Withdrawals\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Resources/Withdrawals/Pages/ListWithdrawals.php:7
 * @route '//admin.localhost/withdrawals'
 */
const ListWithdrawals = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListWithdrawals.url(options),
    method: 'get',
})

ListWithdrawals.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/withdrawals',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Withdrawals\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Resources/Withdrawals/Pages/ListWithdrawals.php:7
 * @route '//admin.localhost/withdrawals'
 */
ListWithdrawals.url = (options?: RouteQueryOptions) => {
    return ListWithdrawals.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Withdrawals\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Resources/Withdrawals/Pages/ListWithdrawals.php:7
 * @route '//admin.localhost/withdrawals'
 */
ListWithdrawals.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListWithdrawals.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Withdrawals\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Resources/Withdrawals/Pages/ListWithdrawals.php:7
 * @route '//admin.localhost/withdrawals'
 */
ListWithdrawals.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListWithdrawals.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Withdrawals\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Resources/Withdrawals/Pages/ListWithdrawals.php:7
 * @route '//admin.localhost/withdrawals'
 */
    const ListWithdrawalsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListWithdrawals.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Withdrawals\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Resources/Withdrawals/Pages/ListWithdrawals.php:7
 * @route '//admin.localhost/withdrawals'
 */
        ListWithdrawalsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListWithdrawals.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Withdrawals\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Resources/Withdrawals/Pages/ListWithdrawals.php:7
 * @route '//admin.localhost/withdrawals'
 */
        ListWithdrawalsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListWithdrawals.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListWithdrawals.form = ListWithdrawalsForm
export default ListWithdrawals