import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Plans\Pages\ListPlans::__invoke
 * @see app/Filament/Resources/Plans/Pages/ListPlans.php:7
 * @route '//admin.localhost/plans'
 */
const ListPlans = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListPlans.url(options),
    method: 'get',
})

ListPlans.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/plans',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Plans\Pages\ListPlans::__invoke
 * @see app/Filament/Resources/Plans/Pages/ListPlans.php:7
 * @route '//admin.localhost/plans'
 */
ListPlans.url = (options?: RouteQueryOptions) => {
    return ListPlans.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Plans\Pages\ListPlans::__invoke
 * @see app/Filament/Resources/Plans/Pages/ListPlans.php:7
 * @route '//admin.localhost/plans'
 */
ListPlans.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListPlans.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Plans\Pages\ListPlans::__invoke
 * @see app/Filament/Resources/Plans/Pages/ListPlans.php:7
 * @route '//admin.localhost/plans'
 */
ListPlans.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListPlans.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Plans\Pages\ListPlans::__invoke
 * @see app/Filament/Resources/Plans/Pages/ListPlans.php:7
 * @route '//admin.localhost/plans'
 */
    const ListPlansForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListPlans.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Plans\Pages\ListPlans::__invoke
 * @see app/Filament/Resources/Plans/Pages/ListPlans.php:7
 * @route '//admin.localhost/plans'
 */
        ListPlansForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListPlans.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Plans\Pages\ListPlans::__invoke
 * @see app/Filament/Resources/Plans/Pages/ListPlans.php:7
 * @route '//admin.localhost/plans'
 */
        ListPlansForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListPlans.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListPlans.form = ListPlansForm
export default ListPlans