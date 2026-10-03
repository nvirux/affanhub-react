import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Slips\Pages\ListSlips::__invoke
 * @see app/Filament/Resources/Slips/Pages/ListSlips.php:7
 * @route '//admin.localhost/slips'
 */
const ListSlips = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSlips.url(options),
    method: 'get',
})

ListSlips.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/slips',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Slips\Pages\ListSlips::__invoke
 * @see app/Filament/Resources/Slips/Pages/ListSlips.php:7
 * @route '//admin.localhost/slips'
 */
ListSlips.url = (options?: RouteQueryOptions) => {
    return ListSlips.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Slips\Pages\ListSlips::__invoke
 * @see app/Filament/Resources/Slips/Pages/ListSlips.php:7
 * @route '//admin.localhost/slips'
 */
ListSlips.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSlips.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Slips\Pages\ListSlips::__invoke
 * @see app/Filament/Resources/Slips/Pages/ListSlips.php:7
 * @route '//admin.localhost/slips'
 */
ListSlips.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListSlips.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Slips\Pages\ListSlips::__invoke
 * @see app/Filament/Resources/Slips/Pages/ListSlips.php:7
 * @route '//admin.localhost/slips'
 */
    const ListSlipsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListSlips.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Slips\Pages\ListSlips::__invoke
 * @see app/Filament/Resources/Slips/Pages/ListSlips.php:7
 * @route '//admin.localhost/slips'
 */
        ListSlipsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListSlips.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Slips\Pages\ListSlips::__invoke
 * @see app/Filament/Resources/Slips/Pages/ListSlips.php:7
 * @route '//admin.localhost/slips'
 */
        ListSlipsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListSlips.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListSlips.form = ListSlipsForm
export default ListSlips