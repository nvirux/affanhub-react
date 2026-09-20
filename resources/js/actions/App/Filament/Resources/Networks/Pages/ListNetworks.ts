import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Networks\Pages\ListNetworks::__invoke
 * @see app/Filament/Resources/Networks/Pages/ListNetworks.php:7
 * @route '//admin.localhost/networks'
 */
const ListNetworks = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListNetworks.url(options),
    method: 'get',
})

ListNetworks.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/networks',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Networks\Pages\ListNetworks::__invoke
 * @see app/Filament/Resources/Networks/Pages/ListNetworks.php:7
 * @route '//admin.localhost/networks'
 */
ListNetworks.url = (options?: RouteQueryOptions) => {
    return ListNetworks.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Networks\Pages\ListNetworks::__invoke
 * @see app/Filament/Resources/Networks/Pages/ListNetworks.php:7
 * @route '//admin.localhost/networks'
 */
ListNetworks.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListNetworks.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Networks\Pages\ListNetworks::__invoke
 * @see app/Filament/Resources/Networks/Pages/ListNetworks.php:7
 * @route '//admin.localhost/networks'
 */
ListNetworks.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListNetworks.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Networks\Pages\ListNetworks::__invoke
 * @see app/Filament/Resources/Networks/Pages/ListNetworks.php:7
 * @route '//admin.localhost/networks'
 */
    const ListNetworksForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListNetworks.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Networks\Pages\ListNetworks::__invoke
 * @see app/Filament/Resources/Networks/Pages/ListNetworks.php:7
 * @route '//admin.localhost/networks'
 */
        ListNetworksForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListNetworks.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Networks\Pages\ListNetworks::__invoke
 * @see app/Filament/Resources/Networks/Pages/ListNetworks.php:7
 * @route '//admin.localhost/networks'
 */
        ListNetworksForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListNetworks.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListNetworks.form = ListNetworksForm
export default ListNetworks