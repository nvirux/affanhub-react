import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Faqs\Pages\ListFaqs::__invoke
 * @see app/Filament/Resources/Faqs/Pages/ListFaqs.php:7
 * @route '//admin.localhost/faqs'
 */
const ListFaqs = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListFaqs.url(options),
    method: 'get',
})

ListFaqs.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/faqs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Faqs\Pages\ListFaqs::__invoke
 * @see app/Filament/Resources/Faqs/Pages/ListFaqs.php:7
 * @route '//admin.localhost/faqs'
 */
ListFaqs.url = (options?: RouteQueryOptions) => {
    return ListFaqs.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Faqs\Pages\ListFaqs::__invoke
 * @see app/Filament/Resources/Faqs/Pages/ListFaqs.php:7
 * @route '//admin.localhost/faqs'
 */
ListFaqs.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListFaqs.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Faqs\Pages\ListFaqs::__invoke
 * @see app/Filament/Resources/Faqs/Pages/ListFaqs.php:7
 * @route '//admin.localhost/faqs'
 */
ListFaqs.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListFaqs.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Faqs\Pages\ListFaqs::__invoke
 * @see app/Filament/Resources/Faqs/Pages/ListFaqs.php:7
 * @route '//admin.localhost/faqs'
 */
    const ListFaqsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListFaqs.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Faqs\Pages\ListFaqs::__invoke
 * @see app/Filament/Resources/Faqs/Pages/ListFaqs.php:7
 * @route '//admin.localhost/faqs'
 */
        ListFaqsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListFaqs.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Faqs\Pages\ListFaqs::__invoke
 * @see app/Filament/Resources/Faqs/Pages/ListFaqs.php:7
 * @route '//admin.localhost/faqs'
 */
        ListFaqsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListFaqs.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListFaqs.form = ListFaqsForm
export default ListFaqs