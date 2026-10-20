import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\MobileAppDownloadController::download
 * @see app/Http/Controllers/MobileAppDownloadController.php:17
 * @route '/app/download'
 */
export const download = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(options),
    method: 'get',
})

download.definition = {
    methods: ["get","head"],
    url: '/app/download',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MobileAppDownloadController::download
 * @see app/Http/Controllers/MobileAppDownloadController.php:17
 * @route '/app/download'
 */
download.url = (options?: RouteQueryOptions) => {
    return download.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\MobileAppDownloadController::download
 * @see app/Http/Controllers/MobileAppDownloadController.php:17
 * @route '/app/download'
 */
download.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\MobileAppDownloadController::download
 * @see app/Http/Controllers/MobileAppDownloadController.php:17
 * @route '/app/download'
 */
download.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\MobileAppDownloadController::download
 * @see app/Http/Controllers/MobileAppDownloadController.php:17
 * @route '/app/download'
 */
    const downloadForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: download.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\MobileAppDownloadController::download
 * @see app/Http/Controllers/MobileAppDownloadController.php:17
 * @route '/app/download'
 */
        downloadForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: download.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\MobileAppDownloadController::download
 * @see app/Http/Controllers/MobileAppDownloadController.php:17
 * @route '/app/download'
 */
        downloadForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: download.url({
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