import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\MobileAppDownloadController::download
 * @see app/Http/Controllers/MobileAppDownloadController.php:18
 * @route '/apps/download/{store}'
 */
export const download = (args: { store: string | { public_id: string } } | [store: string | { public_id: string } ] | string | { public_id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

download.definition = {
    methods: ["get","head"],
    url: '/apps/download/{store}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MobileAppDownloadController::download
 * @see app/Http/Controllers/MobileAppDownloadController.php:18
 * @route '/apps/download/{store}'
 */
download.url = (args: { store: string | { public_id: string } } | [store: string | { public_id: string } ] | string | { public_id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { store: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'public_id' in args) {
            args = { store: args.public_id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    store: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        store: typeof args.store === 'object'
                ? args.store.public_id
                : args.store,
                }

    return download.definition.url
            .replace('{store}', parsedArgs.store.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\MobileAppDownloadController::download
 * @see app/Http/Controllers/MobileAppDownloadController.php:18
 * @route '/apps/download/{store}'
 */
download.get = (args: { store: string | { public_id: string } } | [store: string | { public_id: string } ] | string | { public_id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\MobileAppDownloadController::download
 * @see app/Http/Controllers/MobileAppDownloadController.php:18
 * @route '/apps/download/{store}'
 */
download.head = (args: { store: string | { public_id: string } } | [store: string | { public_id: string } ] | string | { public_id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\MobileAppDownloadController::download
 * @see app/Http/Controllers/MobileAppDownloadController.php:18
 * @route '/apps/download/{store}'
 */
    const downloadForm = (args: { store: string | { public_id: string } } | [store: string | { public_id: string } ] | string | { public_id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: download.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\MobileAppDownloadController::download
 * @see app/Http/Controllers/MobileAppDownloadController.php:18
 * @route '/apps/download/{store}'
 */
        downloadForm.get = (args: { store: string | { public_id: string } } | [store: string | { public_id: string } ] | string | { public_id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: download.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\MobileAppDownloadController::download
 * @see app/Http/Controllers/MobileAppDownloadController.php:18
 * @route '/apps/download/{store}'
 */
        downloadForm.head = (args: { store: string | { public_id: string } } | [store: string | { public_id: string } ] | string | { public_id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: download.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    download.form = downloadForm
const MobileAppDownloadController = { download }

export default MobileAppDownloadController