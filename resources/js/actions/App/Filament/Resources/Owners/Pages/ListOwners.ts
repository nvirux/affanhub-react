import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Owners\Pages\ListOwners::__invoke
 * @see app/Filament/Resources/Owners/Pages/ListOwners.php:7
 * @route '//admin.localhost/owners'
 */
const ListOwners = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListOwners.url(options),
    method: 'get',
})

ListOwners.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/owners',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Owners\Pages\ListOwners::__invoke
 * @see app/Filament/Resources/Owners/Pages/ListOwners.php:7
 * @route '//admin.localhost/owners'
 */
ListOwners.url = (options?: RouteQueryOptions) => {
    return ListOwners.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Owners\Pages\ListOwners::__invoke
 * @see app/Filament/Resources/Owners/Pages/ListOwners.php:7
 * @route '//admin.localhost/owners'
 */
ListOwners.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListOwners.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Owners\Pages\ListOwners::__invoke
 * @see app/Filament/Resources/Owners/Pages/ListOwners.php:7
 * @route '//admin.localhost/owners'
 */
ListOwners.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListOwners.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Owners\Pages\ListOwners::__invoke
 * @see app/Filament/Resources/Owners/Pages/ListOwners.php:7
 * @route '//admin.localhost/owners'
 */
    const ListOwnersForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListOwners.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Owners\Pages\ListOwners::__invoke
 * @see app/Filament/Resources/Owners/Pages/ListOwners.php:7
 * @route '//admin.localhost/owners'
 */
        ListOwnersForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListOwners.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Owners\Pages\ListOwners::__invoke
 * @see app/Filament/Resources/Owners/Pages/ListOwners.php:7
 * @route '//admin.localhost/owners'
 */
        ListOwnersForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListOwners.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListOwners.form = ListOwnersForm
export default ListOwners