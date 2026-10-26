import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs::__invoke
 * @see app/Filament/Resources/ActivityLogs/Pages/ListActivityLogs.php:7
 * @route '//admin.localhost/activity-logs'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/activity-logs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs::__invoke
 * @see app/Filament/Resources/ActivityLogs/Pages/ListActivityLogs.php:7
 * @route '//admin.localhost/activity-logs'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs::__invoke
 * @see app/Filament/Resources/ActivityLogs/Pages/ListActivityLogs.php:7
 * @route '//admin.localhost/activity-logs'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs::__invoke
 * @see app/Filament/Resources/ActivityLogs/Pages/ListActivityLogs.php:7
 * @route '//admin.localhost/activity-logs'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs::__invoke
 * @see app/Filament/Resources/ActivityLogs/Pages/ListActivityLogs.php:7
 * @route '//admin.localhost/activity-logs'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs::__invoke
 * @see app/Filament/Resources/ActivityLogs/Pages/ListActivityLogs.php:7
 * @route '//admin.localhost/activity-logs'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs::__invoke
 * @see app/Filament/Resources/ActivityLogs/Pages/ListActivityLogs.php:7
 * @route '//admin.localhost/activity-logs'
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
const activityLogs = {
    index: Object.assign(index, index),
}

export default activityLogs