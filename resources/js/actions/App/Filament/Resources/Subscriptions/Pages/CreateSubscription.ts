import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Subscriptions\Pages\CreateSubscription::__invoke
 * @see app/Filament/Resources/Subscriptions/Pages/CreateSubscription.php:7
 * @route '//admin.localhost/subscriptions/create'
 */
const CreateSubscription = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateSubscription.url(options),
    method: 'get',
})

CreateSubscription.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/subscriptions/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Subscriptions\Pages\CreateSubscription::__invoke
 * @see app/Filament/Resources/Subscriptions/Pages/CreateSubscription.php:7
 * @route '//admin.localhost/subscriptions/create'
 */
CreateSubscription.url = (options?: RouteQueryOptions) => {
    return CreateSubscription.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Subscriptions\Pages\CreateSubscription::__invoke
 * @see app/Filament/Resources/Subscriptions/Pages/CreateSubscription.php:7
 * @route '//admin.localhost/subscriptions/create'
 */
CreateSubscription.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateSubscription.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Subscriptions\Pages\CreateSubscription::__invoke
 * @see app/Filament/Resources/Subscriptions/Pages/CreateSubscription.php:7
 * @route '//admin.localhost/subscriptions/create'
 */
CreateSubscription.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateSubscription.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Subscriptions\Pages\CreateSubscription::__invoke
 * @see app/Filament/Resources/Subscriptions/Pages/CreateSubscription.php:7
 * @route '//admin.localhost/subscriptions/create'
 */
    const CreateSubscriptionForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateSubscription.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Subscriptions\Pages\CreateSubscription::__invoke
 * @see app/Filament/Resources/Subscriptions/Pages/CreateSubscription.php:7
 * @route '//admin.localhost/subscriptions/create'
 */
        CreateSubscriptionForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateSubscription.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Subscriptions\Pages\CreateSubscription::__invoke
 * @see app/Filament/Resources/Subscriptions/Pages/CreateSubscription.php:7
 * @route '//admin.localhost/subscriptions/create'
 */
        CreateSubscriptionForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateSubscription.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateSubscription.form = CreateSubscriptionForm
export default CreateSubscription