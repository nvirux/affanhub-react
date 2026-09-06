import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//admin.localhost/login'
 */
const Login20451467d993977f462db772b7e7e575 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Login20451467d993977f462db772b7e7e575.url(options),
    method: 'get',
})

Login20451467d993977f462db772b7e7e575.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/login',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//admin.localhost/login'
 */
Login20451467d993977f462db772b7e7e575.url = (options?: RouteQueryOptions) => {
    return Login20451467d993977f462db772b7e7e575.definition.url + queryParams(options)
}

/**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//admin.localhost/login'
 */
Login20451467d993977f462db772b7e7e575.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Login20451467d993977f462db772b7e7e575.url(options),
    method: 'get',
})
/**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//admin.localhost/login'
 */
Login20451467d993977f462db772b7e7e575.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Login20451467d993977f462db772b7e7e575.url(options),
    method: 'head',
})

    /**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//admin.localhost/login'
 */
    const Login20451467d993977f462db772b7e7e575Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: Login20451467d993977f462db772b7e7e575.url(options),
        method: 'get',
    })

            /**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//admin.localhost/login'
 */
        Login20451467d993977f462db772b7e7e575Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: Login20451467d993977f462db772b7e7e575.url(options),
            method: 'get',
        })
            /**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//admin.localhost/login'
 */
        Login20451467d993977f462db772b7e7e575Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: Login20451467d993977f462db772b7e7e575.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    Login20451467d993977f462db772b7e7e575.form = Login20451467d993977f462db772b7e7e575Form
    /**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//merchant.localhost/login'
 */
const Loginf4c93f7e1cbb664a7957d1816a8e62cb = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Loginf4c93f7e1cbb664a7957d1816a8e62cb.url(options),
    method: 'get',
})

Loginf4c93f7e1cbb664a7957d1816a8e62cb.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/login',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//merchant.localhost/login'
 */
Loginf4c93f7e1cbb664a7957d1816a8e62cb.url = (options?: RouteQueryOptions) => {
    return Loginf4c93f7e1cbb664a7957d1816a8e62cb.definition.url + queryParams(options)
}

/**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//merchant.localhost/login'
 */
Loginf4c93f7e1cbb664a7957d1816a8e62cb.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Loginf4c93f7e1cbb664a7957d1816a8e62cb.url(options),
    method: 'get',
})
/**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//merchant.localhost/login'
 */
Loginf4c93f7e1cbb664a7957d1816a8e62cb.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Loginf4c93f7e1cbb664a7957d1816a8e62cb.url(options),
    method: 'head',
})

    /**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//merchant.localhost/login'
 */
    const Loginf4c93f7e1cbb664a7957d1816a8e62cbForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: Loginf4c93f7e1cbb664a7957d1816a8e62cb.url(options),
        method: 'get',
    })

            /**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//merchant.localhost/login'
 */
        Loginf4c93f7e1cbb664a7957d1816a8e62cbForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: Loginf4c93f7e1cbb664a7957d1816a8e62cb.url(options),
            method: 'get',
        })
            /**
* @see \Filament\Auth\Pages\Login::__invoke
 * @see vendor/filament/filament/src/Auth/Pages/Login.php:7
 * @route '//merchant.localhost/login'
 */
        Loginf4c93f7e1cbb664a7957d1816a8e62cbForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: Loginf4c93f7e1cbb664a7957d1816a8e62cb.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    Loginf4c93f7e1cbb664a7957d1816a8e62cb.form = Loginf4c93f7e1cbb664a7957d1816a8e62cbForm

/**
* Multiple routes resolve to \Filament\Auth\Pages\Login::Login, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `Login['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
const Login = {
    '//admin.localhost/login': Login20451467d993977f462db772b7e7e575,
    '//merchant.localhost/login': Loginf4c93f7e1cbb664a7957d1816a8e62cb,
}

export default Login