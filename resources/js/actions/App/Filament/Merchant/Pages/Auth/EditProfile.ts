import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Pages\Auth\EditProfile::__invoke
 * @see app/Filament/Merchant/Pages/Auth/EditProfile.php:7
 * @route '//merchant.localhost/profile'
 */
const EditProfile = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditProfile.url(options),
    method: 'get',
})

EditProfile.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\Auth\EditProfile::__invoke
 * @see app/Filament/Merchant/Pages/Auth/EditProfile.php:7
 * @route '//merchant.localhost/profile'
 */
EditProfile.url = (options?: RouteQueryOptions) => {
    return EditProfile.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\Auth\EditProfile::__invoke
 * @see app/Filament/Merchant/Pages/Auth/EditProfile.php:7
 * @route '//merchant.localhost/profile'
 */
EditProfile.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditProfile.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\Auth\EditProfile::__invoke
 * @see app/Filament/Merchant/Pages/Auth/EditProfile.php:7
 * @route '//merchant.localhost/profile'
 */
EditProfile.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditProfile.url(options),
    method: 'head',
})
export default EditProfile