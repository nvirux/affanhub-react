import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/cac',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac'
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
/**
* @see \App\Http\Controllers\CacController::store
 * @see app/Http/Controllers/CacController.php:88
 * @route '/cac'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/cac',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\CacController::store
 * @see app/Http/Controllers/CacController.php:88
 * @route '/cac'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CacController::store
 * @see app/Http/Controllers/CacController.php:88
 * @route '/cac'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\CacController::store
 * @see app/Http/Controllers/CacController.php:88
 * @route '/cac'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\CacController::store
 * @see app/Http/Controllers/CacController.php:88
 * @route '/cac'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\CacController::downloadCertificate
 * @see app/Http/Controllers/CacController.php:173
 * @route '/cac/{reference}/download-certificate'
 */
export const downloadCertificate = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadCertificate.url(args, options),
    method: 'get',
})

downloadCertificate.definition = {
    methods: ["get","head"],
    url: '/cac/{reference}/download-certificate',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CacController::downloadCertificate
 * @see app/Http/Controllers/CacController.php:173
 * @route '/cac/{reference}/download-certificate'
 */
downloadCertificate.url = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return downloadCertificate.definition.url
            .replace('{reference}', parsedArgs.reference.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\CacController::downloadCertificate
 * @see app/Http/Controllers/CacController.php:173
 * @route '/cac/{reference}/download-certificate'
 */
downloadCertificate.get = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadCertificate.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\CacController::downloadCertificate
 * @see app/Http/Controllers/CacController.php:173
 * @route '/cac/{reference}/download-certificate'
 */
downloadCertificate.head = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: downloadCertificate.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\CacController::downloadCertificate
 * @see app/Http/Controllers/CacController.php:173
 * @route '/cac/{reference}/download-certificate'
 */
    const downloadCertificateForm = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: downloadCertificate.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\CacController::downloadCertificate
 * @see app/Http/Controllers/CacController.php:173
 * @route '/cac/{reference}/download-certificate'
 */
        downloadCertificateForm.get = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: downloadCertificate.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\CacController::downloadCertificate
 * @see app/Http/Controllers/CacController.php:173
 * @route '/cac/{reference}/download-certificate'
 */
        downloadCertificateForm.head = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: downloadCertificate.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    downloadCertificate.form = downloadCertificateForm
const cac = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
downloadCertificate: Object.assign(downloadCertificate, downloadCertificate),
}

export default cac