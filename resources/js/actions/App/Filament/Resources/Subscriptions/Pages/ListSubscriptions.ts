import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Subscriptions\Pages\ListSubscriptions::__invoke
 * @see app/Filament/Resources/Subscriptions/Pages/ListSubscriptions.php:7
 * @route '//admin.localhost/subscriptions'
 */
const ListSubscriptions = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSubscriptions.url(options),
    method: 'get',
})

ListSubscriptions.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/subscriptions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Subscriptions\Pages\ListSubscriptions::__invoke
 * @see app/Filament/Resources/Subscriptions/Pages/ListSubscriptions.php:7
 * @route '//admin.localhost/subscriptions'
 */
ListSubscriptions.url = (options?: RouteQueryOptions) => {
    return ListSubscriptions.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Subscriptions\Pages\ListSubscriptions::__invoke
 * @see app/Filament/Resources/Subscriptions/Pages/ListSubscriptions.php:7
 * @route '//admin.localhost/subscriptions'
 */
ListSubscriptions.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSubscriptions.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Subscriptions\Pages\ListSubscriptions::__invoke
 * @see app/Filament/Resources/Subscriptions/Pages/ListSubscriptions.php:7
 * @route '//admin.localhost/subscriptions'
 */
ListSubscriptions.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListSubscriptions.url(options),
    method: 'head',
})
export default ListSubscriptions