import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\VirtualAccounts\Pages\ListVirtualAccounts::__invoke
 * @see app/Filament/Resources/VirtualAccounts/Pages/ListVirtualAccounts.php:7
 * @route '//admin.localhost/virtual-accounts'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/virtual-accounts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\VirtualAccounts\Pages\ListVirtualAccounts::__invoke
 * @see app/Filament/Resources/VirtualAccounts/Pages/ListVirtualAccounts.php:7
 * @route '//admin.localhost/virtual-accounts'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\VirtualAccounts\Pages\ListVirtualAccounts::__invoke
 * @see app/Filament/Resources/VirtualAccounts/Pages/ListVirtualAccounts.php:7
 * @route '//admin.localhost/virtual-accounts'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\VirtualAccounts\Pages\ListVirtualAccounts::__invoke
 * @see app/Filament/Resources/VirtualAccounts/Pages/ListVirtualAccounts.php:7
 * @route '//admin.localhost/virtual-accounts'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\VirtualAccounts\Pages\ListVirtualAccounts::__invoke
 * @see app/Filament/Resources/VirtualAccounts/Pages/ListVirtualAccounts.php:7
 * @route '//admin.localhost/virtual-accounts'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\VirtualAccounts\Pages\ListVirtualAccounts::__invoke
 * @see app/Filament/Resources/VirtualAccounts/Pages/ListVirtualAccounts.php:7
 * @route '//admin.localhost/virtual-accounts'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\VirtualAccounts\Pages\ListVirtualAccounts::__invoke
 * @see app/Filament/Resources/VirtualAccounts/Pages/ListVirtualAccounts.php:7
 * @route '//admin.localhost/virtual-accounts'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
const virtualAccounts = {
    index: Object.assign(index, index),
}

export default virtualAccounts