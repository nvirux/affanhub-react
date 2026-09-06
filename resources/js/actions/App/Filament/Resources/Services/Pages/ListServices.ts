import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Services\Pages\ListServices::__invoke
 * @see app/Filament/Resources/Services/Pages/ListServices.php:7
 * @route '//admin.localhost/services'
 */
const ListServices = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListServices.url(options),
    method: 'get',
})

ListServices.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/services',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Services\Pages\ListServices::__invoke
 * @see app/Filament/Resources/Services/Pages/ListServices.php:7
 * @route '//admin.localhost/services'
 */
ListServices.url = (options?: RouteQueryOptions) => {
    return ListServices.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Services\Pages\ListServices::__invoke
 * @see app/Filament/Resources/Services/Pages/ListServices.php:7
 * @route '//admin.localhost/services'
 */
ListServices.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListServices.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Services\Pages\ListServices::__invoke
 * @see app/Filament/Resources/Services/Pages/ListServices.php:7
 * @route '//admin.localhost/services'
 */
ListServices.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListServices.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Services\Pages\ListServices::__invoke
 * @see app/Filament/Resources/Services/Pages/ListServices.php:7
 * @route '//admin.localhost/services'
 */
    const ListServicesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListServices.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Services\Pages\ListServices::__invoke
 * @see app/Filament/Resources/Services/Pages/ListServices.php:7
 * @route '//admin.localhost/services'
 */
        ListServicesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListServices.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Services\Pages\ListServices::__invoke
 * @see app/Filament/Resources/Services/Pages/ListServices.php:7
 * @route '//admin.localhost/services'
 */
        ListServicesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListServices.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListServices.form = ListServicesForm
export default ListServices