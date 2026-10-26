import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ModificationController::store
 * @see app/Http/Controllers/ModificationController.php:151
 * @route '/identity/modification'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/identity/modification',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ModificationController::store
 * @see app/Http/Controllers/ModificationController.php:151
 * @route '/identity/modification'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ModificationController::store
 * @see app/Http/Controllers/ModificationController.php:151
 * @route '/identity/modification'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ModificationController::store
 * @see app/Http/Controllers/ModificationController.php:151
 * @route '/identity/modification'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ModificationController::store
 * @see app/Http/Controllers/ModificationController.php:151
 * @route '/identity/modification'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\ModificationController::download
 * @see app/Http/Controllers/ModificationController.php:369
 * @route '/identity/modification/{reference}/download'
 */
export const download = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

download.definition = {
    methods: ["get","head"],
    url: '/identity/modification/{reference}/download',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ModificationController::download
 * @see app/Http/Controllers/ModificationController.php:369
 * @route '/identity/modification/{reference}/download'
 */
download.url = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { reference: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    reference: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        reference: args.reference,
                }

    return download.definition.url
            .replace('{reference}', parsedArgs.reference.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ModificationController::download
 * @see app/Http/Controllers/ModificationController.php:369
 * @route '/identity/modification/{reference}/download'
 */
download.get = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ModificationController::download
 * @see app/Http/Controllers/ModificationController.php:369
 * @route '/identity/modification/{reference}/download'
 */
download.head = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ModificationController::download
 * @see app/Http/Controllers/ModificationController.php:369
 * @route '/identity/modification/{reference}/download'
 */
    const downloadForm = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: download.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ModificationController::download
 * @see app/Http/Controllers/ModificationController.php:369
 * @route '/identity/modification/{reference}/download'
 */
        downloadForm.get = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: download.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ModificationController::download
 * @see app/Http/Controllers/ModificationController.php:369
 * @route '/identity/modification/{reference}/download'
 */
        downloadForm.head = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: download.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    download.form = downloadForm
const modification = {
    store: Object.assign(store, store),
download: Object.assign(download, download),
}

export default modification