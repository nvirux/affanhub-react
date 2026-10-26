import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Services\Pages\ListServices::__invoke
 * @see app/Filament/Resources/Services/Pages/ListServices.php:7
 * @route '//admin.localhost/services'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/services',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Services\Pages\ListServices::__invoke
 * @see app/Filament/Resources/Services/Pages/ListServices.php:7
 * @route '//admin.localhost/services'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Services\Pages\ListServices::__invoke
 * @see app/Filament/Resources/Services/Pages/ListServices.php:7
 * @route '//admin.localhost/services'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Services\Pages\ListServices::__invoke
 * @see app/Filament/Resources/Services/Pages/ListServices.php:7
 * @route '//admin.localhost/services'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Services\Pages\ListServices::__invoke
 * @see app/Filament/Resources/Services/Pages/ListServices.php:7
 * @route '//admin.localhost/services'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Services\Pages\ListServices::__invoke
 * @see app/Filament/Resources/Services/Pages/ListServices.php:7
 * @route '//admin.localhost/services'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Services\Pages\ListServices::__invoke
 * @see app/Filament/Resources/Services/Pages/ListServices.php:7
 * @route '//admin.localhost/services'
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
const services = {
    index: Object.assign(index, index),
}

export default services