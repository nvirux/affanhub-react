import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../../wayfinder'
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
export default CreateFeature