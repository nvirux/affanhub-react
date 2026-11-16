import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
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

    /**
* @see \Filament\Http\Controllers\RedirectToTenantController::__invoke
 * @see vendor/filament/filament/src/Http/Controllers/RedirectToTenantController.php:11
 * @route '//merchant.localhost'
 */
    const RedirectToTenantControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RedirectToTenantController.url(options),
        method: 'get',
    })

            /**
* @see \Filament\Http\Controllers\RedirectToTenantController::__invoke
 * @see vendor/filament/filament/src/Http/Controllers/RedirectToTenantController.php:11
 * @route '//merchant.localhost'
 */
        RedirectToTenantControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectToTenantController.url(options),
            method: 'get',
        })
            /**
* @see \Filament\Http\Controllers\RedirectToTenantController::__invoke
 * @see vendor/filament/filament/src/Http/Controllers/RedirectToTenantController.php:11
 * @route '//merchant.localhost'
 */
        RedirectToTenantControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectToTenantController.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RedirectToTenantController.form = RedirectToTenantControllerForm
export default RedirectToTenantController