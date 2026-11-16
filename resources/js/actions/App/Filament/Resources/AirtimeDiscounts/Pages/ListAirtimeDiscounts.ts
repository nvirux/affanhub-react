import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\ListAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/ListAirtimeDiscounts.php:7
 * @route '//admin.localhost/airtime-discounts'
 */
const ListAirtimeDiscounts = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListAirtimeDiscounts.url(options),
    method: 'get',
})

ListAirtimeDiscounts.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/airtime-discounts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\ListAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/ListAirtimeDiscounts.php:7
 * @route '//admin.localhost/airtime-discounts'
 */
ListAirtimeDiscounts.url = (options?: RouteQueryOptions) => {
    return ListAirtimeDiscounts.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\ListAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/ListAirtimeDiscounts.php:7
 * @route '//admin.localhost/airtime-discounts'
 */
ListAirtimeDiscounts.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListAirtimeDiscounts.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\ListAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/ListAirtimeDiscounts.php:7
 * @route '//admin.localhost/airtime-discounts'
 */
ListAirtimeDiscounts.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListAirtimeDiscounts.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\ListAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/ListAirtimeDiscounts.php:7
 * @route '//admin.localhost/airtime-discounts'
 */
    const ListAirtimeDiscountsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListAirtimeDiscounts.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\ListAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/ListAirtimeDiscounts.php:7
 * @route '//admin.localhost/airtime-discounts'
 */
        ListAirtimeDiscountsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListAirtimeDiscounts.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\ListAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/ListAirtimeDiscounts.php:7
 * @route '//admin.localhost/airtime-discounts'
 */
        ListAirtimeDiscountsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListAirtimeDiscounts.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListAirtimeDiscounts.form = ListAirtimeDiscountsForm
export default ListAirtimeDiscounts