import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ServicesController::index
 * @see app/Http/Controllers/ServicesController.php:18
 * @route '/services'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/services',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::index
 * @see app/Http/Controllers/ServicesController.php:18
 * @route '/services'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::index
 * @see app/Http/Controllers/ServicesController.php:18
 * @route '/services'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::index
 * @see app/Http/Controllers/ServicesController.php:18
 * @route '/services'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})
const ServicesController = { index }

export default ServicesController