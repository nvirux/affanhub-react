import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\ListAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/ListAirtimeDiscounts.php:7
 * @route '//admin.localhost/airtime-discounts'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/airtime-discounts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\ListAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/ListAirtimeDiscounts.php:7
 * @route '//admin.localhost/airtime-discounts'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\ListAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/ListAirtimeDiscounts.php:7
 * @route '//admin.localhost/airtime-discounts'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\ListAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/ListAirtimeDiscounts.php:7
 * @route '//admin.localhost/airtime-discounts'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\ListAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/ListAirtimeDiscounts.php:7
 * @route '//admin.localhost/airtime-discounts'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\ListAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/ListAirtimeDiscounts.php:7
 * @route '//admin.localhost/airtime-discounts'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\ListAirtimeDiscounts::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/ListAirtimeDiscounts.php:7
 * @route '//admin.localhost/airtime-discounts'
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
const airtimeDiscounts = {
    index: Object.assign(index, index),
}

export default airtimeDiscounts