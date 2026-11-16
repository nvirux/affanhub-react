import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
import auth from './auth'
import pages from './pages'
import resources from './resources'
/**
* @see \Filament\Http\Controllers\RedirectToTenantController::__invoke
 * @see vendor/filament/filament/src/Http/Controllers/RedirectToTenantController.php:11
 * @route '//merchant.localhost'
 */
export const tenant = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: tenant.url(options),
    method: 'get',
})

tenant.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Http\Controllers\RedirectToTenantController::__invoke
 * @see vendor/filament/filament/src/Http/Controllers/RedirectToTenantController.php:11
 * @route '//merchant.localhost'
 */
tenant.url = (options?: RouteQueryOptions) => {
    return tenant.definition.url + queryParams(options)
}

/**
* @see \Filament\Http\Controllers\RedirectToTenantController::__invoke
 * @see vendor/filament/filament/src/Http/Controllers/RedirectToTenantController.php:11
 * @route '//merchant.localhost'
 */
tenant.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: tenant.url(options),
    method: 'get',
})
/**
* @see \Filament\Http\Controllers\RedirectToTenantController::__invoke
 * @see vendor/filament/filament/src/Http/Controllers/RedirectToTenantController.php:11
 * @route '//merchant.localhost'
 */
tenant.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: tenant.url(options),
    method: 'head',
})

    /**
* @see \Filament\Http\Controllers\RedirectToTenantController::__invoke
 * @see vendor/filament/filament/src/Http/Controllers/RedirectToTenantController.php:11
 * @route '//merchant.localhost'
 */
    const tenantForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: tenant.url(options),
        method: 'get',
    })

            /**
* @see \Filament\Http\Controllers\RedirectToTenantController::__invoke
 * @see vendor/filament/filament/src/Http/Controllers/RedirectToTenantController.php:11
 * @route '//merchant.localhost'
 */
        tenantForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: tenant.url(options),
            method: 'get',
        })
            /**
* @see \Filament\Http\Controllers\RedirectToTenantController::__invoke
 * @see vendor/filament/filament/src/Http/Controllers/RedirectToTenantController.php:11
 * @route '//merchant.localhost'
 */
        tenantForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: tenant.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    tenant.form = tenantForm
const merchant = {
    auth: Object.assign(auth, auth),
tenant: Object.assign(tenant, tenant),
pages: Object.assign(pages, pages),
resources: Object.assign(resources, resources),
}

export default merchant