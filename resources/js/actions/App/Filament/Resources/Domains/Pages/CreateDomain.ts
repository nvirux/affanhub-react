import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Domains\Pages\CreateDomain::__invoke
 * @see app/Filament/Resources/Domains/Pages/CreateDomain.php:7
 * @route '//admin.localhost/domains/create'
 */
const CreateDomain = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateDomain.url(options),
    method: 'get',
})

CreateDomain.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/domains/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Domains\Pages\CreateDomain::__invoke
 * @see app/Filament/Resources/Domains/Pages/CreateDomain.php:7
 * @route '//admin.localhost/domains/create'
 */
CreateDomain.url = (options?: RouteQueryOptions) => {
    return CreateDomain.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Domains\Pages\CreateDomain::__invoke
 * @see app/Filament/Resources/Domains/Pages/CreateDomain.php:7
 * @route '//admin.localhost/domains/create'
 */
CreateDomain.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateDomain.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Domains\Pages\CreateDomain::__invoke
 * @see app/Filament/Resources/Domains/Pages/CreateDomain.php:7
 * @route '//admin.localhost/domains/create'
 */
CreateDomain.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateDomain.url(options),
    method: 'head',
})
export default CreateDomain