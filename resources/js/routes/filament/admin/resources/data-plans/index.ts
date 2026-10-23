import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\DataPlans\Pages\ListDataPlans::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/ListDataPlans.php:7
 * @route '//admin.localhost/data-plans'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/data-plans',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\DataPlans\Pages\ListDataPlans::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/ListDataPlans.php:7
 * @route '//admin.localhost/data-plans'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\DataPlans\Pages\ListDataPlans::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/ListDataPlans.php:7
 * @route '//admin.localhost/data-plans'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\DataPlans\Pages\ListDataPlans::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/ListDataPlans.php:7
 * @route '//admin.localhost/data-plans'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\DataPlans\Pages\EditDataPlan::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/EditDataPlan.php:7
 * @route '//admin.localhost/data-plans/{record}/edit'
 */
export const edit = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/data-plans/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\DataPlans\Pages\EditDataPlan::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/EditDataPlan.php:7
 * @route '//admin.localhost/data-plans/{record}/edit'
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
* @see \App\Filament\Resources\DataPlans\Pages\EditDataPlan::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/EditDataPlan.php:7
 * @route '//admin.localhost/data-plans/{record}/edit'
 */
edit.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\DataPlans\Pages\EditDataPlan::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/EditDataPlan.php:7
 * @route '//admin.localhost/data-plans/{record}/edit'
 */
edit.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})
const dataPlans = {
    index: Object.assign(index, index),
edit: Object.assign(edit, edit),
}

export default dataPlans