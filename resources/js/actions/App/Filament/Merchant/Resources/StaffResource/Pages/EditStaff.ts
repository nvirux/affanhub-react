import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\EditStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/EditStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/{record}/edit'
 */
const EditStaff = (args: { tenant: string | number, record: string | number } | [tenant: string | number, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditStaff.url(args, options),
    method: 'get',
})

EditStaff.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/staff/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\EditStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/EditStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/{record}/edit'
 */
EditStaff.url = (args: { tenant: string | number, record: string | number } | [tenant: string | number, record: string | number ], options?: RouteQueryOptions) => {
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

    return EditStaff.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\EditStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/EditStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/{record}/edit'
 */
EditStaff.get = (args: { tenant: string | number, record: string | number } | [tenant: string | number, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditStaff.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\EditStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/EditStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/{record}/edit'
 */
EditStaff.head = (args: { tenant: string | number, record: string | number } | [tenant: string | number, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditStaff.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\EditStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/EditStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/{record}/edit'
 */
    const EditStaffForm = (args: { tenant: string | number, record: string | number } | [tenant: string | number, record: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditStaff.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\EditStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/EditStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/{record}/edit'
 */
        EditStaffForm.get = (args: { tenant: string | number, record: string | number } | [tenant: string | number, record: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditStaff.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\EditStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/EditStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/{record}/edit'
 */
        EditStaffForm.head = (args: { tenant: string | number, record: string | number } | [tenant: string | number, record: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditStaff.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditStaff.form = EditStaffForm
export default EditStaff