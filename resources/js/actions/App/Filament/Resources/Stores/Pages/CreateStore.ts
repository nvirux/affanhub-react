import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Stores\Pages\CreateStore::__invoke
 * @see app/Filament/Resources/Stores/Pages/CreateStore.php:7
 * @route '//admin.localhost/stores/create'
 */
const CreateStore = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateStore.url(options),
    method: 'get',
})

CreateStore.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/stores/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Stores\Pages\CreateStore::__invoke
 * @see app/Filament/Resources/Stores/Pages/CreateStore.php:7
 * @route '//admin.localhost/stores/create'
 */
CreateStore.url = (options?: RouteQueryOptions) => {
    return CreateStore.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Stores\Pages\CreateStore::__invoke
 * @see app/Filament/Resources/Stores/Pages/CreateStore.php:7
 * @route '//admin.localhost/stores/create'
 */
CreateStore.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateStore.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Stores\Pages\CreateStore::__invoke
 * @see app/Filament/Resources/Stores/Pages/CreateStore.php:7
 * @route '//admin.localhost/stores/create'
 */
CreateStore.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateStore.url(options),
    method: 'head',
})
export default CreateStore