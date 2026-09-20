import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Features\Pages\ListFeatures::__invoke
 * @see app/Filament/Resources/Features/Pages/ListFeatures.php:7
 * @route '//admin.localhost/features'
 */
const ListFeatures = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListFeatures.url(options),
    method: 'get',
})

ListFeatures.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/features',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Features\Pages\ListFeatures::__invoke
 * @see app/Filament/Resources/Features/Pages/ListFeatures.php:7
 * @route '//admin.localhost/features'
 */
ListFeatures.url = (options?: RouteQueryOptions) => {
    return ListFeatures.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Features\Pages\ListFeatures::__invoke
 * @see app/Filament/Resources/Features/Pages/ListFeatures.php:7
 * @route '//admin.localhost/features'
 */
ListFeatures.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListFeatures.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Features\Pages\ListFeatures::__invoke
 * @see app/Filament/Resources/Features/Pages/ListFeatures.php:7
 * @route '//admin.localhost/features'
 */
ListFeatures.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListFeatures.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Features\Pages\ListFeatures::__invoke
 * @see app/Filament/Resources/Features/Pages/ListFeatures.php:7
 * @route '//admin.localhost/features'
 */
    const ListFeaturesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListFeatures.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Features\Pages\ListFeatures::__invoke
 * @see app/Filament/Resources/Features/Pages/ListFeatures.php:7
 * @route '//admin.localhost/features'
 */
        ListFeaturesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListFeatures.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Features\Pages\ListFeatures::__invoke
 * @see app/Filament/Resources/Features/Pages/ListFeatures.php:7
 * @route '//admin.localhost/features'
 */
        ListFeaturesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListFeatures.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListFeatures.form = ListFeaturesForm
export default ListFeatures