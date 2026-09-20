import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Features\Pages\CreateFeature::__invoke
 * @see app/Filament/Resources/Features/Pages/CreateFeature.php:7
 * @route '//admin.localhost/features/create'
 */
const CreateFeature = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateFeature.url(options),
    method: 'get',
})

CreateFeature.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/features/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Features\Pages\CreateFeature::__invoke
 * @see app/Filament/Resources/Features/Pages/CreateFeature.php:7
 * @route '//admin.localhost/features/create'
 */
CreateFeature.url = (options?: RouteQueryOptions) => {
    return CreateFeature.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Features\Pages\CreateFeature::__invoke
 * @see app/Filament/Resources/Features/Pages/CreateFeature.php:7
 * @route '//admin.localhost/features/create'
 */
CreateFeature.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateFeature.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Features\Pages\CreateFeature::__invoke
 * @see app/Filament/Resources/Features/Pages/CreateFeature.php:7
 * @route '//admin.localhost/features/create'
 */
CreateFeature.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateFeature.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Features\Pages\CreateFeature::__invoke
 * @see app/Filament/Resources/Features/Pages/CreateFeature.php:7
 * @route '//admin.localhost/features/create'
 */
    const CreateFeatureForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateFeature.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Features\Pages\CreateFeature::__invoke
 * @see app/Filament/Resources/Features/Pages/CreateFeature.php:7
 * @route '//admin.localhost/features/create'
 */
        CreateFeatureForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateFeature.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Features\Pages\CreateFeature::__invoke
 * @see app/Filament/Resources/Features/Pages/CreateFeature.php:7
 * @route '//admin.localhost/features/create'
 */
        CreateFeatureForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateFeature.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateFeature.form = CreateFeatureForm
export default CreateFeature