import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Owners\Pages\CreateOwner::__invoke
 * @see app/Filament/Resources/Owners/Pages/CreateOwner.php:7
 * @route '//admin.localhost/owners/create'
 */
const CreateOwner = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateOwner.url(options),
    method: 'get',
})

CreateOwner.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/owners/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Owners\Pages\CreateOwner::__invoke
 * @see app/Filament/Resources/Owners/Pages/CreateOwner.php:7
 * @route '//admin.localhost/owners/create'
 */
CreateOwner.url = (options?: RouteQueryOptions) => {
    return CreateOwner.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Owners\Pages\CreateOwner::__invoke
 * @see app/Filament/Resources/Owners/Pages/CreateOwner.php:7
 * @route '//admin.localhost/owners/create'
 */
CreateOwner.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateOwner.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Owners\Pages\CreateOwner::__invoke
 * @see app/Filament/Resources/Owners/Pages/CreateOwner.php:7
 * @route '//admin.localhost/owners/create'
 */
CreateOwner.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateOwner.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Owners\Pages\CreateOwner::__invoke
 * @see app/Filament/Resources/Owners/Pages/CreateOwner.php:7
 * @route '//admin.localhost/owners/create'
 */
    const CreateOwnerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateOwner.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Owners\Pages\CreateOwner::__invoke
 * @see app/Filament/Resources/Owners/Pages/CreateOwner.php:7
 * @route '//admin.localhost/owners/create'
 */
        CreateOwnerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateOwner.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Owners\Pages\CreateOwner::__invoke
 * @see app/Filament/Resources/Owners/Pages/CreateOwner.php:7
 * @route '//admin.localhost/owners/create'
 */
        CreateOwnerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateOwner.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateOwner.form = CreateOwnerForm
export default CreateOwner