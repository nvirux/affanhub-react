import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \MobileAppDownloadController::download
 * @see [unknown]:0
 * @route '/apps/download/{store}'
 */
export const download = (args: { store: string | number | { public_id: string | number } } | [store: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

download.definition = {
    methods: ["get","head"],
    url: '/apps/download/{store}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \MobileAppDownloadController::download
 * @see [unknown]:0
 * @route '/apps/download/{store}'
 */
download.url = (args: { store: string | number | { public_id: string | number } } | [store: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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
* @see \MobileAppDownloadController::download
 * @see [unknown]:0
 * @route '/apps/download/{store}'
 */
download.get = (args: { store: string | number | { public_id: string | number } } | [store: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})
/**
* @see \MobileAppDownloadController::download
 * @see [unknown]:0
 * @route '/apps/download/{store}'
 */
download.head = (args: { store: string | number | { public_id: string | number } } | [store: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download.url(args, options),
    method: 'head',
})

    /**
* @see \MobileAppDownloadController::download
 * @see [unknown]:0
 * @route '/apps/download/{store}'
 */
    const downloadForm = (args: { store: string | number | { public_id: string | number } } | [store: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: download.url(args, options),
        method: 'get',
    })

            /**
* @see \MobileAppDownloadController::download
 * @see [unknown]:0
 * @route '/apps/download/{store}'
 */
        downloadForm.get = (args: { store: string | number | { public_id: string | number } } | [store: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: download.url(args, options),
            method: 'get',
        })
            /**
* @see \MobileAppDownloadController::download
 * @see [unknown]:0
 * @route '/apps/download/{store}'
 */
        downloadForm.head = (args: { store: string | number | { public_id: string | number } } | [store: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: download.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    download.form = downloadForm
const mobileApp = {
    download: Object.assign(download, download),
}

export default mobileApp