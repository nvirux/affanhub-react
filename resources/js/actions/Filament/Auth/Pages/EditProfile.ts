import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \Filament\Auth\Pages\EditProfile::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/EditProfile.php:7
 * @route '//admin.localhost/profile'
 */
const EditProfile = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditProfile.url(options),
    method: 'get',
})

EditProfile.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Auth\Pages\EditProfile::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/EditProfile.php:7
 * @route '//admin.localhost/profile'
 */
EditProfile.url = (options?: RouteQueryOptions) => {
    return EditProfile.definition.url + queryParams(options)
}

/**
* @see \Filament\Auth\Pages\EditProfile::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/EditProfile.php:7
 * @route '//admin.localhost/profile'
 */
EditProfile.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditProfile.url(options),
    method: 'get',
})
/**
* @see \Filament\Auth\Pages\EditProfile::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/EditProfile.php:7
 * @route '//admin.localhost/profile'
 */
EditProfile.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditProfile.url(options),
    method: 'head',
})
export default EditProfile