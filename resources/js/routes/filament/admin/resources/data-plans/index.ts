import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
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
* @see \App\Filament\Resources\DataPlans\Pages\ListDataPlans::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/ListDataPlans.php:7
 * @route '//admin.localhost/data-plans'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\DataPlans\Pages\ListDataPlans::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/ListDataPlans.php:7
 * @route '//admin.localhost/data-plans'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\DataPlans\Pages\ListDataPlans::__invoke
 * @see app/Filament/Resources/DataPlans/Pages/ListDataPlans.php:7
 * @route '//admin.localhost/data-plans'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
const dataPlans = {
    index: Object.assign(index, index),
}

export default dataPlans