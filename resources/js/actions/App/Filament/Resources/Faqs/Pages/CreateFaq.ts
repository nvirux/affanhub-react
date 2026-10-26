import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Faqs\Pages\CreateFaq::__invoke
 * @see app/Filament/Resources/Faqs/Pages/CreateFaq.php:7
 * @route '//admin.localhost/faqs/create'
 */
const CreateFaq = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateFaq.url(options),
    method: 'get',
})

CreateFaq.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/faqs/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Faqs\Pages\CreateFaq::__invoke
 * @see app/Filament/Resources/Faqs/Pages/CreateFaq.php:7
 * @route '//admin.localhost/faqs/create'
 */
CreateFaq.url = (options?: RouteQueryOptions) => {
    return CreateFaq.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Faqs\Pages\CreateFaq::__invoke
 * @see app/Filament/Resources/Faqs/Pages/CreateFaq.php:7
 * @route '//admin.localhost/faqs/create'
 */
CreateFaq.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateFaq.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Faqs\Pages\CreateFaq::__invoke
 * @see app/Filament/Resources/Faqs/Pages/CreateFaq.php:7
 * @route '//admin.localhost/faqs/create'
 */
CreateFaq.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateFaq.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Faqs\Pages\CreateFaq::__invoke
 * @see app/Filament/Resources/Faqs/Pages/CreateFaq.php:7
 * @route '//admin.localhost/faqs/create'
 */
    const CreateFaqForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateFaq.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Faqs\Pages\CreateFaq::__invoke
 * @see app/Filament/Resources/Faqs/Pages/CreateFaq.php:7
 * @route '//admin.localhost/faqs/create'
 */
        CreateFaqForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateFaq.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Faqs\Pages\CreateFaq::__invoke
 * @see app/Filament/Resources/Faqs/Pages/CreateFaq.php:7
 * @route '//admin.localhost/faqs/create'
 */
        CreateFaqForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateFaq.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateFaq.form = CreateFaqForm
export default CreateFaq