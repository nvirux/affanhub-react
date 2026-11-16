import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\ListStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/ListStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff'
 */
const ListStaff = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListStaff.url(args, options),
    method: 'get',
})

ListStaff.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/staff',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\ListStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/ListStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff'
 */
ListStaff.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'public_id' in args) {
            args = { tenant: args.public_id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: typeof args.tenant === 'object'
                ? args.tenant.public_id
                : args.tenant,
                }

    return ListStaff.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\ListStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/ListStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff'
 */
ListStaff.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListStaff.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\ListStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/ListStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff'
 */
ListStaff.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListStaff.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\ListStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/ListStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff'
 */
    const ListStaffForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListStaff.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\ListStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/ListStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff'
 */
        ListStaffForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListStaff.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\ListStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/ListStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff'
 */
        ListStaffForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListStaff.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListStaff.form = ListStaffForm
export default ListStaff