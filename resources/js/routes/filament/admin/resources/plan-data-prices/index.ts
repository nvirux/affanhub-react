import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\PlanDataPrices\Pages\ListPlanDataPrices::__invoke
 * @see app/Filament/Resources/PlanDataPrices/Pages/ListPlanDataPrices.php:7
 * @route '//admin.localhost/plan-data-prices'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/plan-data-prices',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\PlanDataPrices\Pages\ListPlanDataPrices::__invoke
 * @see app/Filament/Resources/PlanDataPrices/Pages/ListPlanDataPrices.php:7
 * @route '//admin.localhost/plan-data-prices'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\PlanDataPrices\Pages\ListPlanDataPrices::__invoke
 * @see app/Filament/Resources/PlanDataPrices/Pages/ListPlanDataPrices.php:7
 * @route '//admin.localhost/plan-data-prices'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\PlanDataPrices\Pages\ListPlanDataPrices::__invoke
 * @see app/Filament/Resources/PlanDataPrices/Pages/ListPlanDataPrices.php:7
 * @route '//admin.localhost/plan-data-prices'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\PlanDataPrices\Pages\ListPlanDataPrices::__invoke
 * @see app/Filament/Resources/PlanDataPrices/Pages/ListPlanDataPrices.php:7
 * @route '//admin.localhost/plan-data-prices'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\PlanDataPrices\Pages\ListPlanDataPrices::__invoke
 * @see app/Filament/Resources/PlanDataPrices/Pages/ListPlanDataPrices.php:7
 * @route '//admin.localhost/plan-data-prices'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\PlanDataPrices\Pages\ListPlanDataPrices::__invoke
 * @see app/Filament/Resources/PlanDataPrices/Pages/ListPlanDataPrices.php:7
 * @route '//admin.localhost/plan-data-prices'
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
const planDataPrices = {
    index: Object.assign(index, index),
}

export default planDataPrices