import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Slips\Pages\ListSlips::__invoke
 * @see app/Filament/Resources/Slips/Pages/ListSlips.php:7
 * @route '//admin.localhost/slips'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/slips',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Slips\Pages\ListSlips::__invoke
 * @see app/Filament/Resources/Slips/Pages/ListSlips.php:7
 * @route '//admin.localhost/slips'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Slips\Pages\ListSlips::__invoke
 * @see app/Filament/Resources/Slips/Pages/ListSlips.php:7
 * @route '//admin.localhost/slips'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Slips\Pages\ListSlips::__invoke
 * @see app/Filament/Resources/Slips/Pages/ListSlips.php:7
 * @route '//admin.localhost/slips'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\Slips\Pages\CreateSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/CreateSlip.php:7
 * @route '//admin.localhost/slips/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/slips/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Slips\Pages\CreateSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/CreateSlip.php:7
 * @route '//admin.localhost/slips/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Slips\Pages\CreateSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/CreateSlip.php:7
 * @route '//admin.localhost/slips/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Slips\Pages\CreateSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/CreateSlip.php:7
 * @route '//admin.localhost/slips/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\Slips\Pages\EditSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/EditSlip.php:7
 * @route '//admin.localhost/slips/{record}/edit'
 */
export const edit = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/slips/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Slips\Pages\EditSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/EditSlip.php:7
 * @route '//admin.localhost/slips/{record}/edit'
 */
edit.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { record: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    record: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        record: args.record,
                }

    return edit.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\Slips\Pages\EditSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/EditSlip.php:7
 * @route '//admin.localhost/slips/{record}/edit'
 */
edit.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Slips\Pages\EditSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/EditSlip.php:7
 * @route '//admin.localhost/slips/{record}/edit'
 */
edit.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})
const slips = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
edit: Object.assign(edit, edit),
}

export default slips