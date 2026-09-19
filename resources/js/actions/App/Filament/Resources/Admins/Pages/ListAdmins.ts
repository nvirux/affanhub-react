import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Admins\Pages\ListAdmins::__invoke
 * @see app/Filament/Resources/Admins/Pages/ListAdmins.php:7
 * @route '//admin.localhost/admins'
 */
const ListAdmins = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListAdmins.url(options),
    method: 'get',
})

ListAdmins.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/admins',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Admins\Pages\ListAdmins::__invoke
 * @see app/Filament/Resources/Admins/Pages/ListAdmins.php:7
 * @route '//admin.localhost/admins'
 */
ListAdmins.url = (options?: RouteQueryOptions) => {
    return ListAdmins.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Admins\Pages\ListAdmins::__invoke
 * @see app/Filament/Resources/Admins/Pages/ListAdmins.php:7
 * @route '//admin.localhost/admins'
 */
ListAdmins.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListAdmins.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Admins\Pages\ListAdmins::__invoke
 * @see app/Filament/Resources/Admins/Pages/ListAdmins.php:7
 * @route '//admin.localhost/admins'
 */
ListAdmins.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListAdmins.url(options),
    method: 'head',
})
export default ListAdmins