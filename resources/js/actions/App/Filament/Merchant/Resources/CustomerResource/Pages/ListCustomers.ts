import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\CustomerResource\Pages\ListCustomers::__invoke
 * @see app/Filament/Merchant/Resources/CustomerResource/Pages/ListCustomers.php:7
 * @route '//merchant.localhost/{tenant}/customers'
 */
const ListCustomers = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCustomers.url(args, options),
    method: 'get',
})

ListCustomers.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/customers',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\CustomerResource\Pages\ListCustomers::__invoke
 * @see app/Filament/Merchant/Resources/CustomerResource/Pages/ListCustomers.php:7
 * @route '//merchant.localhost/{tenant}/customers'
 */
ListCustomers.url = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: args.tenant,
                }

    return ListCustomers.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\CustomerResource\Pages\ListCustomers::__invoke
 * @see app/Filament/Merchant/Resources/CustomerResource/Pages/ListCustomers.php:7
 * @route '//merchant.localhost/{tenant}/customers'
 */
ListCustomers.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCustomers.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\CustomerResource\Pages\ListCustomers::__invoke
 * @see app/Filament/Merchant/Resources/CustomerResource/Pages/ListCustomers.php:7
 * @route '//merchant.localhost/{tenant}/customers'
 */
ListCustomers.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListCustomers.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\CustomerResource\Pages\ListCustomers::__invoke
 * @see app/Filament/Merchant/Resources/CustomerResource/Pages/ListCustomers.php:7
 * @route '//merchant.localhost/{tenant}/customers'
 */
    const ListCustomersForm = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListCustomers.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\CustomerResource\Pages\ListCustomers::__invoke
 * @see app/Filament/Merchant/Resources/CustomerResource/Pages/ListCustomers.php:7
 * @route '//merchant.localhost/{tenant}/customers'
 */
        ListCustomersForm.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListCustomers.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\CustomerResource\Pages\ListCustomers::__invoke
 * @see app/Filament/Merchant/Resources/CustomerResource/Pages/ListCustomers.php:7
 * @route '//merchant.localhost/{tenant}/customers'
 */
        ListCustomersForm.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListCustomers.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListCustomers.form = ListCustomersForm
export default ListCustomers