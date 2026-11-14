import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../wayfinder'
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
const mobileApp = {
    download: Object.assign(download, download),
}

export default mobileApp