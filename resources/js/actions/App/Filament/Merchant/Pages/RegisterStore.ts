import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Pages\RegisterStore::__invoke
 * @see app/Filament/Merchant/Pages/RegisterStore.php:7
 * @route '//merchant.localhost/new'
 */
const RegisterStore = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RegisterStore.url(options),
    method: 'get',
})

RegisterStore.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/new',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\RegisterStore::__invoke
 * @see app/Filament/Merchant/Pages/RegisterStore.php:7
 * @route '//merchant.localhost/new'
 */
RegisterStore.url = (options?: RouteQueryOptions) => {
    return RegisterStore.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\RegisterStore::__invoke
 * @see app/Filament/Merchant/Pages/RegisterStore.php:7
 * @route '//merchant.localhost/new'
 */
RegisterStore.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RegisterStore.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\RegisterStore::__invoke
 * @see app/Filament/Merchant/Pages/RegisterStore.php:7
 * @route '//merchant.localhost/new'
 */
RegisterStore.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RegisterStore.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\RegisterStore::__invoke
 * @see app/Filament/Merchant/Pages/RegisterStore.php:7
 * @route '//merchant.localhost/new'
 */
    const RegisterStoreForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RegisterStore.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\RegisterStore::__invoke
 * @see app/Filament/Merchant/Pages/RegisterStore.php:7
 * @route '//merchant.localhost/new'
 */
        RegisterStoreForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RegisterStore.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\RegisterStore::__invoke
 * @see app/Filament/Merchant/Pages/RegisterStore.php:7
 * @route '//merchant.localhost/new'
 */
        RegisterStoreForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RegisterStore.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RegisterStore.form = RegisterStoreForm
export default RegisterStore