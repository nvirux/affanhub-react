import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\PlanAirtimeDiscounts\Pages\ListPlanAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/PlanAirtimeDiscounts/Pages/ListPlanAirtimeDiscounts.php:7
 * @route '//admin.localhost/plan-airtime-discounts'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/plan-airtime-discounts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\PlanAirtimeDiscounts\Pages\ListPlanAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/PlanAirtimeDiscounts/Pages/ListPlanAirtimeDiscounts.php:7
 * @route '//admin.localhost/plan-airtime-discounts'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\PlanAirtimeDiscounts\Pages\ListPlanAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/PlanAirtimeDiscounts/Pages/ListPlanAirtimeDiscounts.php:7
 * @route '//admin.localhost/plan-airtime-discounts'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\PlanAirtimeDiscounts\Pages\ListPlanAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/PlanAirtimeDiscounts/Pages/ListPlanAirtimeDiscounts.php:7
 * @route '//admin.localhost/plan-airtime-discounts'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\PlanAirtimeDiscounts\Pages\ListPlanAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/PlanAirtimeDiscounts/Pages/ListPlanAirtimeDiscounts.php:7
 * @route '//admin.localhost/plan-airtime-discounts'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\PlanAirtimeDiscounts\Pages\ListPlanAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/PlanAirtimeDiscounts/Pages/ListPlanAirtimeDiscounts.php:7
 * @route '//admin.localhost/plan-airtime-discounts'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\PlanAirtimeDiscounts\Pages\ListPlanAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/PlanAirtimeDiscounts/Pages/ListPlanAirtimeDiscounts.php:7
 * @route '//admin.localhost/plan-airtime-discounts'
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
const planAirtimeDiscounts = {
    index: Object.assign(index, index),
}

export default planAirtimeDiscounts