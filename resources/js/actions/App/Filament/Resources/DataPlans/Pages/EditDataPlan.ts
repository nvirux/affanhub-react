import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\DataPlans\Pages\EditDataPlan::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/EditDataPlan.php:7
 * @route '//admin.localhost/data-plans/{record}/edit'
 */
const EditDataPlan = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditDataPlan.url(args, options),
    method: 'get',
})

EditDataPlan.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/data-plans/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\DataPlans\Pages\EditDataPlan::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/EditDataPlan.php:7
 * @route '//admin.localhost/data-plans/{record}/edit'
 */
EditDataPlan.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditDataPlan.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\DataPlans\Pages\EditDataPlan::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/EditDataPlan.php:7
 * @route '//admin.localhost/data-plans/{record}/edit'
 */
EditDataPlan.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditDataPlan.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\DataPlans\Pages\EditDataPlan::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/EditDataPlan.php:7
 * @route '//admin.localhost/data-plans/{record}/edit'
 */
EditDataPlan.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditDataPlan.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\DataPlans\Pages\EditDataPlan::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/EditDataPlan.php:7
 * @route '//admin.localhost/data-plans/{record}/edit'
 */
    const EditDataPlanForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditDataPlan.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\DataPlans\Pages\EditDataPlan::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/EditDataPlan.php:7
 * @route '//admin.localhost/data-plans/{record}/edit'
 */
        EditDataPlanForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditDataPlan.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\DataPlans\Pages\EditDataPlan::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/EditDataPlan.php:7
 * @route '//admin.localhost/data-plans/{record}/edit'
 */
        EditDataPlanForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditDataPlan.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditDataPlan.form = EditDataPlanForm
export default EditDataPlan