import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Domains\Pages\ListDomains::__invoke
 * @see app/Filament/Resources/Domains/Pages/ListDomains.php:7
 * @route '//admin.localhost/domains'
 */
const ListDomains = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListDomains.url(options),
    method: 'get',
})

ListDomains.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/domains',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Domains\Pages\ListDomains::__invoke
 * @see app/Filament/Resources/Domains/Pages/ListDomains.php:7
 * @route '//admin.localhost/domains'
 */
ListDomains.url = (options?: RouteQueryOptions) => {
    return ListDomains.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Domains\Pages\ListDomains::__invoke
 * @see app/Filament/Resources/Domains/Pages/ListDomains.php:7
 * @route '//admin.localhost/domains'
 */
ListDomains.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListDomains.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Domains\Pages\ListDomains::__invoke
 * @see app/Filament/Resources/Domains/Pages/ListDomains.php:7
 * @route '//admin.localhost/domains'
 */
ListDomains.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListDomains.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Domains\Pages\ListDomains::__invoke
 * @see app/Filament/Resources/Domains/Pages/ListDomains.php:7
 * @route '//admin.localhost/domains'
 */
    const ListDomainsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListDomains.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Domains\Pages\ListDomains::__invoke
 * @see app/Filament/Resources/Domains/Pages/ListDomains.php:7
 * @route '//admin.localhost/domains'
 */
        ListDomainsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListDomains.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Domains\Pages\ListDomains::__invoke
 * @see app/Filament/Resources/Domains/Pages/ListDomains.php:7
 * @route '//admin.localhost/domains'
 */
        ListDomainsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListDomains.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListDomains.form = ListDomainsForm
export default ListDomains