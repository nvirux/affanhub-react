import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\EditAirtimeDiscount::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/EditAirtimeDiscount.php:7
 * @route '//admin.localhost/airtime-discounts/{record}/edit'
 */
const EditAirtimeDiscount = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditAirtimeDiscount.url(args, options),
    method: 'get',
})

EditAirtimeDiscount.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/airtime-discounts/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\EditAirtimeDiscount::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/EditAirtimeDiscount.php:7
 * @route '//admin.localhost/airtime-discounts/{record}/edit'
 */
EditAirtimeDiscount.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { record: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    record: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        record: args.record,
                }

    return EditAirtimeDiscount.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\EditAirtimeDiscount::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/EditAirtimeDiscount.php:7
 * @route '//admin.localhost/airtime-discounts/{record}/edit'
 */
EditAirtimeDiscount.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditAirtimeDiscount.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\EditAirtimeDiscount::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/EditAirtimeDiscount.php:7
 * @route '//admin.localhost/airtime-discounts/{record}/edit'
 */
EditAirtimeDiscount.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditAirtimeDiscount.url(args, options),
    method: 'head',
})
export default EditAirtimeDiscount