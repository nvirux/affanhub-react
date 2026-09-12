import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Pages\Auth\Register::__invoke
 * @see app/Filament/Merchant/Pages/Auth/Register.php:7
 * @route '//merchant.localhost/register'
 */
const Register = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Register.url(options),
    method: 'get',
})

Register.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\Auth\Register::__invoke
 * @see app/Filament/Merchant/Pages/Auth/Register.php:7
 * @route '//merchant.localhost/register'
 */
Register.url = (options?: RouteQueryOptions) => {
    return Register.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\Auth\Register::__invoke
 * @see app/Filament/Merchant/Pages/Auth/Register.php:7
 * @route '//merchant.localhost/register'
 */
Register.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Register.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\Auth\Register::__invoke
 * @see app/Filament/Merchant/Pages/Auth/Register.php:7
 * @route '//merchant.localhost/register'
 */
Register.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Register.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\Auth\Register::__invoke
 * @see app/Filament/Merchant/Pages/Auth/Register.php:7
 * @route '//merchant.localhost/register'
 */
    const RegisterForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: Register.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\Auth\Register::__invoke
 * @see app/Filament/Merchant/Pages/Auth/Register.php:7
 * @route '//merchant.localhost/register'
 */
        RegisterForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: Register.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\Auth\Register::__invoke
 * @see app/Filament/Merchant/Pages/Auth/Register.php:7
 * @route '//merchant.localhost/register'
 */
        RegisterForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: Register.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    Register.form = RegisterForm
export default Register