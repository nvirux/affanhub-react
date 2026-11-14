import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \Filament\Http\Controllers\RedirectToTenantController::__invoke
 * @see vendor/filament/filament/src/Http/Controllers/RedirectToTenantController.php:11
 * @route '//merchant.localhost'
 */
const RedirectToTenantController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectToTenantController.url(options),
    method: 'get',
})

RedirectToTenantController.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Http\Controllers\RedirectToTenantController::__invoke
 * @see vendor/filament/filament/src/Http/Controllers/RedirectToTenantController.php:11
 * @route '//merchant.localhost'
 */
RedirectToTenantController.url = (options?: RouteQueryOptions) => {
    return RedirectToTenantController.definition.url + queryParams(options)
}

/**
* @see \Filament\Http\Controllers\RedirectToTenantController::__invoke
 * @see vendor/filament/filament/src/Http/Controllers/RedirectToTenantController.php:11
 * @route '//merchant.localhost'
 */
RedirectToTenantController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectToTenantController.url(options),
    method: 'get',
})
/**
* @see \Filament\Http\Controllers\RedirectToTenantController::__invoke
 * @see vendor/filament/filament/src/Http/Controllers/RedirectToTenantController.php:11
 * @route '//merchant.localhost'
 */
RedirectToTenantController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectToTenantController.url(options),
    method: 'head',
})
export default RedirectToTenantController