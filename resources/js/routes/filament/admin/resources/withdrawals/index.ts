import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Withdrawals\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Resources/Withdrawals/Pages/ListWithdrawals.php:7
 * @route '//admin.localhost/withdrawals'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/withdrawals',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Withdrawals\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Resources/Withdrawals/Pages/ListWithdrawals.php:7
 * @route '//admin.localhost/withdrawals'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Withdrawals\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Resources/Withdrawals/Pages/ListWithdrawals.php:7
 * @route '//admin.localhost/withdrawals'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Withdrawals\Pages\ListWithdrawals::__invoke
 * @see app/Filament/Resources/Withdrawals/Pages/ListWithdrawals.php:7
 * @route '//admin.localhost/withdrawals'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})
const withdrawals = {
    index: Object.assign(index, index),
}

export default withdrawals