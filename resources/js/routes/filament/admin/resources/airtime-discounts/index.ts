import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
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
/**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\EditAirtimeDiscount::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/EditAirtimeDiscount.php:7
 * @route '//admin.localhost/airtime-discounts/{record}/edit'
 */
export const edit = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/airtime-discounts/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\EditAirtimeDiscount::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/EditAirtimeDiscount.php:7
 * @route '//admin.localhost/airtime-discounts/{record}/edit'
 */
edit.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\EditAirtimeDiscount::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/EditAirtimeDiscount.php:7
 * @route '//admin.localhost/airtime-discounts/{record}/edit'
 */
edit.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\EditAirtimeDiscount::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/EditAirtimeDiscount.php:7
 * @route '//admin.localhost/airtime-discounts/{record}/edit'
 */
edit.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\EditAirtimeDiscount::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/EditAirtimeDiscount.php:7
 * @route '//admin.localhost/airtime-discounts/{record}/edit'
 */
    const editForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\EditAirtimeDiscount::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/EditAirtimeDiscount.php:7
 * @route '//admin.localhost/airtime-discounts/{record}/edit'
 */
        editForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\AirtimeDiscounts\Pages\EditAirtimeDiscount::__invoke
 * @see app/Filament/Resources/AirtimeDiscounts/Pages/EditAirtimeDiscount.php:7
 * @route '//admin.localhost/airtime-discounts/{record}/edit'
 */
        editForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
const airtimeDiscounts = {
    index: Object.assign(index, index),
edit: Object.assign(edit, edit),
}

export default airtimeDiscounts