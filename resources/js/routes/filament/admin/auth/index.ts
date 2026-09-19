import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//admin.localhost/login'
 */
export const login = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: login.url(options),
    method: 'get',
})

login.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/login',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//admin.localhost/login'
 */
login.url = (options?: RouteQueryOptions) => {
    return login.definition.url + queryParams(options)
}

/**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//admin.localhost/login'
 */
login.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: login.url(options),
    method: 'get',
})
/**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//admin.localhost/login'
 */
login.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: login.url(options),
    method: 'head',
})

/**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//admin.localhost/logout'
 */
export const logout = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

logout.definition = {
    methods: ["post"],
    url: '//admin.localhost/logout',
} satisfies RouteDefinition<["post"]>

/**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//admin.localhost/logout'
 */
logout.url = (options?: RouteQueryOptions) => {
    return logout.definition.url + queryParams(options)
}

/**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//admin.localhost/logout'
 */
logout.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

/**
* @see \Filament\Auth\Pages\EditProfile::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/EditProfile.php:7
 * @route '//admin.localhost/profile'
 */
export const profile = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: profile.url(options),
    method: 'get',
})

profile.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Auth\Pages\EditProfile::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/EditProfile.php:7
 * @route '//admin.localhost/profile'
 */
profile.url = (options?: RouteQueryOptions) => {
    return profile.definition.url + queryParams(options)
}

/**
* @see \Filament\Auth\Pages\EditProfile::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/EditProfile.php:7
 * @route '//admin.localhost/profile'
 */
profile.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: profile.url(options),
    method: 'get',
})
/**
* @see \Filament\Auth\Pages\EditProfile::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/EditProfile.php:7
 * @route '//admin.localhost/profile'
 */
profile.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: profile.url(options),
    method: 'head',
})
const auth = {
    login: Object.assign(login, login),
logout: Object.assign(logout, logout),
profile: Object.assign(profile, profile),
}

export default auth