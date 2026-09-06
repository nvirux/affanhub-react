import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\PlanDataPrices\Pages\ListPlanDataPrices::__invoke
 * @see app/Filament/Resources/PlanDataPrices/Pages/ListPlanDataPrices.php:7
 * @route '//admin.localhost/plan-data-prices'
 */
const ListPlanDataPrices = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListPlanDataPrices.url(options),
    method: 'get',
})

ListPlanDataPrices.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/plan-data-prices',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\PlanDataPrices\Pages\ListPlanDataPrices::__invoke
 * @see app/Filament/Resources/PlanDataPrices/Pages/ListPlanDataPrices.php:7
 * @route '//admin.localhost/plan-data-prices'
 */
ListPlanDataPrices.url = (options?: RouteQueryOptions) => {
    return ListPlanDataPrices.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\PlanDataPrices\Pages\ListPlanDataPrices::__invoke
 * @see app/Filament/Resources/PlanDataPrices/Pages/ListPlanDataPrices.php:7
 * @route '//admin.localhost/plan-data-prices'
 */
ListPlanDataPrices.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListPlanDataPrices.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\PlanDataPrices\Pages\ListPlanDataPrices::__invoke
 * @see app/Filament/Resources/PlanDataPrices/Pages/ListPlanDataPrices.php:7
 * @route '//admin.localhost/plan-data-prices'
 */
ListPlanDataPrices.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListPlanDataPrices.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\PlanDataPrices\Pages\ListPlanDataPrices::__invoke
 * @see app/Filament/Resources/PlanDataPrices/Pages/ListPlanDataPrices.php:7
 * @route '//admin.localhost/plan-data-prices'
 */
    const ListPlanDataPricesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListPlanDataPrices.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\PlanDataPrices\Pages\ListPlanDataPrices::__invoke
 * @see app/Filament/Resources/PlanDataPrices/Pages/ListPlanDataPrices.php:7
 * @route '//admin.localhost/plan-data-prices'
 */
        ListPlanDataPricesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListPlanDataPrices.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\PlanDataPrices\Pages\ListPlanDataPrices::__invoke
 * @see app/Filament/Resources/PlanDataPrices/Pages/ListPlanDataPrices.php:7
 * @route '//admin.localhost/plan-data-prices'
 */
        ListPlanDataPricesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListPlanDataPrices.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListPlanDataPrices.form = ListPlanDataPricesForm
export default ListPlanDataPrices