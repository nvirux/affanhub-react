import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Plans\Pages\CreatePlan::__invoke
 * @see app/Filament/Resources/Plans/Pages/CreatePlan.php:7
 * @route '//admin.localhost/plans/create'
 */
const CreatePlan = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreatePlan.url(options),
    method: 'get',
})

CreatePlan.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/plans/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Plans\Pages\CreatePlan::__invoke
 * @see app/Filament/Resources/Plans/Pages/CreatePlan.php:7
 * @route '//admin.localhost/plans/create'
 */
CreatePlan.url = (options?: RouteQueryOptions) => {
    return CreatePlan.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Plans\Pages\CreatePlan::__invoke
 * @see app/Filament/Resources/Plans/Pages/CreatePlan.php:7
 * @route '//admin.localhost/plans/create'
 */
CreatePlan.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreatePlan.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Plans\Pages\CreatePlan::__invoke
 * @see app/Filament/Resources/Plans/Pages/CreatePlan.php:7
 * @route '//admin.localhost/plans/create'
 */
CreatePlan.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreatePlan.url(options),
    method: 'head',
})
export default CreatePlan