import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\ListStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/ListStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff'
 */
export const index = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/staff',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\ListStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/ListStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff'
 */
index.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return index.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\ListStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/ListStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff'
 */
index.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\ListStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/ListStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff'
 */
index.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\ListStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/ListStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff'
 */
    const indexForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\ListStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/ListStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff'
 */
        indexForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\ListStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/ListStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff'
 */
        indexForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\CreateStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/CreateStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/create'
 */
export const create = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/staff/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\CreateStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/CreateStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/create'
 */
create.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return create.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\CreateStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/CreateStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/create'
 */
create.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\CreateStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/CreateStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/create'
 */
create.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\CreateStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/CreateStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/create'
 */
    const createForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\CreateStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/CreateStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/create'
 */
        createForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\CreateStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/CreateStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/create'
 */
        createForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\EditStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/EditStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/{record}/edit'
 */
export const edit = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/staff/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\EditStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/EditStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/{record}/edit'
 */
edit.url = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                    record: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: typeof args.tenant === 'object'
                ? args.tenant.public_id
                : args.tenant,
                                record: args.record,
                }

    return edit.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\EditStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/EditStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/{record}/edit'
 */
edit.get = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\EditStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/EditStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/{record}/edit'
 */
edit.head = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\EditStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/EditStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/{record}/edit'
 */
    const editForm = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\EditStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/EditStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/{record}/edit'
 */
        editForm.get = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Resources\StaffResource\Pages\EditStaff::__invoke
 * @see app/Filament/Merchant/Resources/StaffResource/Pages/EditStaff.php:7
 * @route '//merchant.localhost/{tenant}/staff/{record}/edit'
 */
        editForm.head = (args: { tenant: string | number | { public_id: string | number }, record: string | number } | [tenant: string | number | { public_id: string | number }, record: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
const staff = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
edit: Object.assign(edit, edit),
}

export default staff