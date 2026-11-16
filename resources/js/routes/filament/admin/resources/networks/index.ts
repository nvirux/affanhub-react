import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Networks\Pages\ListNetworks::__invoke
 * @see app/Filament/Resources/Networks/Pages/ListNetworks.php:7
 * @route '//admin.localhost/networks'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/networks',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Networks\Pages\ListNetworks::__invoke
 * @see app/Filament/Resources/Networks/Pages/ListNetworks.php:7
 * @route '//admin.localhost/networks'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Networks\Pages\ListNetworks::__invoke
 * @see app/Filament/Resources/Networks/Pages/ListNetworks.php:7
 * @route '//admin.localhost/networks'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Networks\Pages\ListNetworks::__invoke
 * @see app/Filament/Resources/Networks/Pages/ListNetworks.php:7
 * @route '//admin.localhost/networks'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Networks\Pages\ListNetworks::__invoke
 * @see app/Filament/Resources/Networks/Pages/ListNetworks.php:7
 * @route '//admin.localhost/networks'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Networks\Pages\ListNetworks::__invoke
 * @see app/Filament/Resources/Networks/Pages/ListNetworks.php:7
 * @route '//admin.localhost/networks'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Networks\Pages\ListNetworks::__invoke
 * @see app/Filament/Resources/Networks/Pages/ListNetworks.php:7
 * @route '//admin.localhost/networks'
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
const networks = {
    index: Object.assign(index, index),
}

export default networks