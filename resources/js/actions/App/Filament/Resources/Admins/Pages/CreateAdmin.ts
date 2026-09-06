import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Admins\Pages\CreateAdmin::__invoke
 * @see app/Filament/Resources/Admins/Pages/CreateAdmin.php:7
 * @route '//admin.localhost/admins/create'
 */
const CreateAdmin = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateAdmin.url(options),
    method: 'get',
})

CreateAdmin.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/admins/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Admins\Pages\CreateAdmin::__invoke
 * @see app/Filament/Resources/Admins/Pages/CreateAdmin.php:7
 * @route '//admin.localhost/admins/create'
 */
CreateAdmin.url = (options?: RouteQueryOptions) => {
    return CreateAdmin.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Admins\Pages\CreateAdmin::__invoke
 * @see app/Filament/Resources/Admins/Pages/CreateAdmin.php:7
 * @route '//admin.localhost/admins/create'
 */
CreateAdmin.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateAdmin.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Admins\Pages\CreateAdmin::__invoke
 * @see app/Filament/Resources/Admins/Pages/CreateAdmin.php:7
 * @route '//admin.localhost/admins/create'
 */
CreateAdmin.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateAdmin.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Admins\Pages\CreateAdmin::__invoke
 * @see app/Filament/Resources/Admins/Pages/CreateAdmin.php:7
 * @route '//admin.localhost/admins/create'
 */
    const CreateAdminForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateAdmin.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Admins\Pages\CreateAdmin::__invoke
 * @see app/Filament/Resources/Admins/Pages/CreateAdmin.php:7
 * @route '//admin.localhost/admins/create'
 */
        CreateAdminForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateAdmin.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Admins\Pages\CreateAdmin::__invoke
 * @see app/Filament/Resources/Admins/Pages/CreateAdmin.php:7
 * @route '//admin.localhost/admins/create'
 */
        CreateAdminForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateAdmin.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateAdmin.form = CreateAdminForm
export default CreateAdmin