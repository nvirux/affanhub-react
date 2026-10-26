import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\DataPlans\Pages\ListDataPlans::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/ListDataPlans.php:7
 * @route '//admin.localhost/data-plans'
 */
const ListDataPlans = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListDataPlans.url(options),
    method: 'get',
})

ListDataPlans.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/data-plans',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\DataPlans\Pages\ListDataPlans::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/ListDataPlans.php:7
 * @route '//admin.localhost/data-plans'
 */
ListDataPlans.url = (options?: RouteQueryOptions) => {
    return ListDataPlans.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\DataPlans\Pages\ListDataPlans::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/ListDataPlans.php:7
 * @route '//admin.localhost/data-plans'
 */
ListDataPlans.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListDataPlans.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\DataPlans\Pages\ListDataPlans::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/ListDataPlans.php:7
 * @route '//admin.localhost/data-plans'
 */
ListDataPlans.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListDataPlans.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\DataPlans\Pages\ListDataPlans::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/ListDataPlans.php:7
 * @route '//admin.localhost/data-plans'
 */
    const ListDataPlansForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListDataPlans.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\DataPlans\Pages\ListDataPlans::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/ListDataPlans.php:7
 * @route '//admin.localhost/data-plans'
 */
        ListDataPlansForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListDataPlans.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\DataPlans\Pages\ListDataPlans::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/ListDataPlans.php:7
 * @route '//admin.localhost/data-plans'
 */
        ListDataPlansForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListDataPlans.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListDataPlans.form = ListDataPlansForm
export default ListDataPlans