import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\CreateStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/CreateStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/create'
 */
const CreateStaff = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateStaff.url(args, options),
    method: 'get',
})

CreateStaff.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/staff/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\CreateStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/CreateStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/create'
 */
CreateStaff.url = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return CreateStaff.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\CreateStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/CreateStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/create'
 */
CreateStaff.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateStaff.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\CreateStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/CreateStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/create'
 */
CreateStaff.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateStaff.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\CreateStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/CreateStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/create'
 */
    const CreateStaffForm = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateStaff.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\CreateStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/CreateStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/create'
 */
        CreateStaffForm.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateStaff.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\CreateStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/CreateStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/create'
 */
        CreateStaffForm.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateStaff.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateStaff.form = CreateStaffForm
export default CreateStaff