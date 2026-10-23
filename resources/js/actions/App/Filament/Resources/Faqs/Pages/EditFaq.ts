import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Faqs\Pages\EditFaq::__invoke
 * @see app/Filament/Resources/Faqs/Pages/EditFaq.php:7
 * @route '//admin.localhost/faqs/{record}/edit'
 */
const EditFaq = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditFaq.url(args, options),
    method: 'get',
})

EditFaq.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/faqs/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Faqs\Pages\EditFaq::__invoke
 * @see app/Filament/Resources/Faqs/Pages/EditFaq.php:7
 * @route '//admin.localhost/faqs/{record}/edit'
 */
EditFaq.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditFaq.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\Faqs\Pages\EditFaq::__invoke
 * @see app/Filament/Resources/Faqs/Pages/EditFaq.php:7
 * @route '//admin.localhost/faqs/{record}/edit'
 */
EditFaq.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditFaq.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Faqs\Pages\EditFaq::__invoke
 * @see app/Filament/Resources/Faqs/Pages/EditFaq.php:7
 * @route '//admin.localhost/faqs/{record}/edit'
 */
EditFaq.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditFaq.url(args, options),
    method: 'head',
})
export default EditFaq