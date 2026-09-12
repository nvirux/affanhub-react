import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//merchant.localhost/login'
 */
export const login = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: login.url(options),
    method: 'get',
})

login.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/login',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//merchant.localhost/login'
 */
login.url = (options?: RouteQueryOptions) => {
    return login.definition.url + queryParams(options)
}

/**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//merchant.localhost/login'
 */
login.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: login.url(options),
    method: 'get',
})
/**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//merchant.localhost/login'
 */
login.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: login.url(options),
    method: 'head',
})

    /**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//merchant.localhost/login'
 */
    const loginForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: login.url(options),
        method: 'get',
    })

            /**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//merchant.localhost/login'
 */
        loginForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: login.url(options),
            method: 'get',
        })
            /**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//merchant.localhost/login'
 */
        loginForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: login.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    login.form = loginForm
/**
* @see \App\Filament\Merchant\Pages\Auth\Register::__invoke
 * @see app/Filament/Merchant/Pages/Auth/Register.php:7
 * @route '//merchant.localhost/register'
 */
export const register = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

register.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\Auth\Register::__invoke
 * @see app/Filament/Merchant/Pages/Auth/Register.php:7
 * @route '//merchant.localhost/register'
 */
register.url = (options?: RouteQueryOptions) => {
    return register.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\Auth\Register::__invoke
 * @see app/Filament/Merchant/Pages/Auth/Register.php:7
 * @route '//merchant.localhost/register'
 */
register.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\Auth\Register::__invoke
 * @see app/Filament/Merchant/Pages/Auth/Register.php:7
 * @route '//merchant.localhost/register'
 */
register.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: register.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\Auth\Register::__invoke
 * @see app/Filament/Merchant/Pages/Auth/Register.php:7
 * @route '//merchant.localhost/register'
 */
    const registerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: register.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\Auth\Register::__invoke
 * @see app/Filament/Merchant/Pages/Auth/Register.php:7
 * @route '//merchant.localhost/register'
 */
        registerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: register.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\Auth\Register::__invoke
 * @see app/Filament/Merchant/Pages/Auth/Register.php:7
 * @route '//merchant.localhost/register'
 */
        registerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: register.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    register.form = registerForm
/**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//merchant.localhost/logout'
 */
export const logout = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

logout.definition = {
    methods: ["post"],
    url: '//merchant.localhost/logout',
} satisfies RouteDefinition<["post"]>

/**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//merchant.localhost/logout'
 */
logout.url = (options?: RouteQueryOptions) => {
    return logout.definition.url + queryParams(options)
}

/**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//merchant.localhost/logout'
 */
logout.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

    /**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//merchant.localhost/logout'
 */
    const logoutForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: logout.url(options),
        method: 'post',
    })

            /**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//merchant.localhost/logout'
 */
        logoutForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: logout.url(options),
            method: 'post',
        })
    
    logout.form = logoutForm
/**
* @see \Filament\Auth\Pages\EditProfile::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/EditProfile.php:7
 * @route '//merchant.localhost/profile'
 */
export const profile = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: profile.url(options),
    method: 'get',
})

profile.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Auth\Pages\EditProfile::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/EditProfile.php:7
 * @route '//merchant.localhost/profile'
 */
profile.url = (options?: RouteQueryOptions) => {
    return profile.definition.url + queryParams(options)
}

/**
* @see \Filament\Auth\Pages\EditProfile::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/EditProfile.php:7
 * @route '//merchant.localhost/profile'
 */
profile.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: profile.url(options),
    method: 'get',
})
/**
* @see \Filament\Auth\Pages\EditProfile::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/EditProfile.php:7
 * @route '//merchant.localhost/profile'
 */
profile.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: profile.url(options),
    method: 'head',
})

    /**
* @see \Filament\Auth\Pages\EditProfile::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/EditProfile.php:7
 * @route '//merchant.localhost/profile'
 */
    const profileForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: profile.url(options),
        method: 'get',
    })

            /**
* @see \Filament\Auth\Pages\EditProfile::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/EditProfile.php:7
 * @route '//merchant.localhost/profile'
 */
        profileForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: profile.url(options),
            method: 'get',
        })
            /**
* @see \Filament\Auth\Pages\EditProfile::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/EditProfile.php:7
 * @route '//merchant.localhost/profile'
 */
        profileForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: profile.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    profile.form = profileForm
const auth = {
    login: Object.assign(login, login),
register: Object.assign(register, register),
logout: Object.assign(logout, logout),
profile: Object.assign(profile, profile),
}

export default auth