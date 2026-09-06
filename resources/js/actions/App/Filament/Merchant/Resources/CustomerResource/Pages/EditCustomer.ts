import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\CustomerResource\Pages\EditCustomer::__invoke
 * @see app/Filament/Merchant/Resources/CustomerResource/Pages/EditCustomer.php:7
 * @route '//merchant.localhost/{tenant}/customers/{record}/edit'
 */
const EditCustomer = (args: { tenant: string | number, record: string | number } | [tenant: string | number, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditCustomer.url(args, options),
    method: 'get',
})

EditCustomer.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/customers/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\CustomerResource\Pages\EditCustomer::__invoke
 * @see app/Filament/Merchant/Resources/CustomerResource/Pages/EditCustomer.php:7
 * @route '//merchant.localhost/{tenant}/customers/{record}/edit'
 */
EditCustomer.url = (args: { tenant: string | number, record: string | number } | [tenant: string | number, record: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                    record: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: args.tenant,
                                record: args.record,
                }

    return EditCustomer.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\CustomerResource\Pages\EditCustomer::__invoke
 * @see app/Filament/Merchant/Resources/CustomerResource/Pages/EditCustomer.php:7
 * @route '//merchant.localhost/{tenant}/customers/{record}/edit'
 */
EditCustomer.get = (args: { tenant: string | number, record: string | number } | [tenant: string | number, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditCustomer.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\CustomerResource\Pages\EditCustomer::__invoke
 * @see app/Filament/Merchant/Resources/CustomerResource/Pages/EditCustomer.php:7
 * @route '//merchant.localhost/{tenant}/customers/{record}/edit'
 */
EditCustomer.head = (args: { tenant: string | number, record: string | number } | [tenant: string | number, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditCustomer.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\CustomerResource\Pages\EditCustomer::__invoke
 * @see app/Filament/Merchant/Resources/CustomerResource/Pages/EditCustomer.php:7
 * @route '//merchant.localhost/{tenant}/customers/{record}/edit'
 */
    const EditCustomerForm = (args: { tenant: string | number, record: string | number } | [tenant: string | number, record: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditCustomer.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\CustomerResource\Pages\EditCustomer::__invoke
 * @see app/Filament/Merchant/Resources/CustomerResource/Pages/EditCustomer.php:7
 * @route '//merchant.localhost/{tenant}/customers/{record}/edit'
 */
        EditCustomerForm.get = (args: { tenant: string | number, record: string | number } | [tenant: string | number, record: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditCustomer.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\CustomerResource\Pages\EditCustomer::__invoke
 * @see app/Filament/Merchant/Resources/CustomerResource/Pages/EditCustomer.php:7
 * @route '//merchant.localhost/{tenant}/customers/{record}/edit'
 */
        EditCustomerForm.head = (args: { tenant: string | number, record: string | number } | [tenant: string | number, record: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditCustomer.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditCustomer.form = EditCustomerForm
export default EditCustomer