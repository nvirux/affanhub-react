import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\VirtualAccounts\Pages\ListVirtualAccounts::__invoke
 * @see app/Filament/Resources/VirtualAccounts/Pages/ListVirtualAccounts.php:7
 * @route '//admin.localhost/virtual-accounts'
 */
const ListVirtualAccounts = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListVirtualAccounts.url(options),
    method: 'get',
})

ListVirtualAccounts.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/virtual-accounts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\VirtualAccounts\Pages\ListVirtualAccounts::__invoke
 * @see app/Filament/Resources/VirtualAccounts/Pages/ListVirtualAccounts.php:7
 * @route '//admin.localhost/virtual-accounts'
 */
ListVirtualAccounts.url = (options?: RouteQueryOptions) => {
    return ListVirtualAccounts.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\VirtualAccounts\Pages\ListVirtualAccounts::__invoke
 * @see app/Filament/Resources/VirtualAccounts/Pages/ListVirtualAccounts.php:7
 * @route '//admin.localhost/virtual-accounts'
 */
ListVirtualAccounts.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListVirtualAccounts.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\VirtualAccounts\Pages\ListVirtualAccounts::__invoke
 * @see app/Filament/Resources/VirtualAccounts/Pages/ListVirtualAccounts.php:7
 * @route '//admin.localhost/virtual-accounts'
 */
ListVirtualAccounts.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListVirtualAccounts.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\VirtualAccounts\Pages\ListVirtualAccounts::__invoke
 * @see app/Filament/Resources/VirtualAccounts/Pages/ListVirtualAccounts.php:7
 * @route '//admin.localhost/virtual-accounts'
 */
    const ListVirtualAccountsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListVirtualAccounts.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\VirtualAccounts\Pages\ListVirtualAccounts::__invoke
 * @see app/Filament/Resources/VirtualAccounts/Pages/ListVirtualAccounts.php:7
 * @route '//admin.localhost/virtual-accounts'
 */
        ListVirtualAccountsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListVirtualAccounts.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\VirtualAccounts\Pages\ListVirtualAccounts::__invoke
 * @see app/Filament/Resources/VirtualAccounts/Pages/ListVirtualAccounts.php:7
 * @route '//admin.localhost/virtual-accounts'
 */
        ListVirtualAccountsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListVirtualAccounts.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListVirtualAccounts.form = ListVirtualAccountsForm
export default ListVirtualAccounts