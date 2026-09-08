import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\PlanAirtimeDiscounts\Pages\ListPlanAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/PlanAirtimeDiscounts/Pages/ListPlanAirtimeDiscounts.php:7
 * @route '//admin.localhost/plan-airtime-discounts'
 */
const ListPlanAirtimeDiscounts = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListPlanAirtimeDiscounts.url(options),
    method: 'get',
})

ListPlanAirtimeDiscounts.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/plan-airtime-discounts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\PlanAirtimeDiscounts\Pages\ListPlanAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/PlanAirtimeDiscounts/Pages/ListPlanAirtimeDiscounts.php:7
 * @route '//admin.localhost/plan-airtime-discounts'
 */
ListPlanAirtimeDiscounts.url = (options?: RouteQueryOptions) => {
    return ListPlanAirtimeDiscounts.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\PlanAirtimeDiscounts\Pages\ListPlanAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/PlanAirtimeDiscounts/Pages/ListPlanAirtimeDiscounts.php:7
 * @route '//admin.localhost/plan-airtime-discounts'
 */
ListPlanAirtimeDiscounts.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListPlanAirtimeDiscounts.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\PlanAirtimeDiscounts\Pages\ListPlanAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/PlanAirtimeDiscounts/Pages/ListPlanAirtimeDiscounts.php:7
 * @route '//admin.localhost/plan-airtime-discounts'
 */
ListPlanAirtimeDiscounts.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListPlanAirtimeDiscounts.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\PlanAirtimeDiscounts\Pages\ListPlanAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/PlanAirtimeDiscounts/Pages/ListPlanAirtimeDiscounts.php:7
 * @route '//admin.localhost/plan-airtime-discounts'
 */
    const ListPlanAirtimeDiscountsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListPlanAirtimeDiscounts.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\PlanAirtimeDiscounts\Pages\ListPlanAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/PlanAirtimeDiscounts/Pages/ListPlanAirtimeDiscounts.php:7
 * @route '//admin.localhost/plan-airtime-discounts'
 */
        ListPlanAirtimeDiscountsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListPlanAirtimeDiscounts.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\PlanAirtimeDiscounts\Pages\ListPlanAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/PlanAirtimeDiscounts/Pages/ListPlanAirtimeDiscounts.php:7
 * @route '//admin.localhost/plan-airtime-discounts'
 */
        ListPlanAirtimeDiscountsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListPlanAirtimeDiscounts.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListPlanAirtimeDiscounts.form = ListPlanAirtimeDiscountsForm
export default ListPlanAirtimeDiscounts