import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:25
 * @route '/identity/nin-modification'
 */
export const ninIndex = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ninIndex.url(options),
    method: 'get',
})

ninIndex.definition = {
    methods: ["get","head"],
    url: '/identity/nin-modification',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:25
 * @route '/identity/nin-modification'
 */
ninIndex.url = (options?: RouteQueryOptions) => {
    return ninIndex.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:25
 * @route '/identity/nin-modification'
 */
ninIndex.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ninIndex.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:25
 * @route '/identity/nin-modification'
 */
ninIndex.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ninIndex.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:25
 * @route '/identity/nin-modification'
 */
    const ninIndexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ninIndex.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:25
 * @route '/identity/nin-modification'
 */
        ninIndexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ninIndex.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:25
 * @route '/identity/nin-modification'
 */
        ninIndexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ninIndex.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ninIndex.form = ninIndexForm
/**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:33
 * @route '/identity/bvn-modification'
 */
export const bvnIndex = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bvnIndex.url(options),
    method: 'get',
})

bvnIndex.definition = {
    methods: ["get","head"],
    url: '/identity/bvn-modification',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:33
 * @route '/identity/bvn-modification'
 */
bvnIndex.url = (options?: RouteQueryOptions) => {
    return bvnIndex.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:33
 * @route '/identity/bvn-modification'
 */
bvnIndex.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bvnIndex.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:33
 * @route '/identity/bvn-modification'
 */
bvnIndex.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: bvnIndex.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:33
 * @route '/identity/bvn-modification'
 */
    const bvnIndexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: bvnIndex.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:33
 * @route '/identity/bvn-modification'
 */
        bvnIndexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bvnIndex.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:33
 * @route '/identity/bvn-modification'
 */
        bvnIndexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bvnIndex.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    bvnIndex.form = bvnIndexForm
/**
* @see \App\Http\Controllers\ModificationController::index
 * @see app/Http/Controllers/ModificationController.php:41
 * @route '/identity/modification'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/identity/modification',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ModificationController::index
 * @see app/Http/Controllers/ModificationController.php:41
 * @route '/identity/modification'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ModificationController::index
 * @see app/Http/Controllers/ModificationController.php:41
 * @route '/identity/modification'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ModificationController::index
 * @see app/Http/Controllers/ModificationController.php:41
 * @route '/identity/modification'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ModificationController::index
 * @see app/Http/Controllers/ModificationController.php:41
 * @route '/identity/modification'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ModificationController::index
 * @see app/Http/Controllers/ModificationController.php:41
 * @route '/identity/modification'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ModificationController::index
 * @see app/Http/Controllers/ModificationController.php:41
 * @route '/identity/modification'
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
* @see \App\Http\Controllers\ModificationController::downloadResult
 * @see app/Http/Controllers/ModificationController.php:369
 * @route '/identity/modification/{reference}/download'
 */
export const downloadResult = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadResult.url(args, options),
    method: 'get',
})

downloadResult.definition = {
    methods: ["get","head"],
    url: '/identity/modification/{reference}/download',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ModificationController::downloadResult
 * @see app/Http/Controllers/ModificationController.php:369
 * @route '/identity/modification/{reference}/download'
 */
downloadResult.url = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return downloadResult.definition.url
            .replace('{reference}', parsedArgs.reference.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ModificationController::downloadResult
 * @see app/Http/Controllers/ModificationController.php:369
 * @route '/identity/modification/{reference}/download'
 */
downloadResult.get = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadResult.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ModificationController::downloadResult
 * @see app/Http/Controllers/ModificationController.php:369
 * @route '/identity/modification/{reference}/download'
 */
downloadResult.head = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: downloadResult.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ModificationController::downloadResult
 * @see app/Http/Controllers/ModificationController.php:369
 * @route '/identity/modification/{reference}/download'
 */
    const downloadResultForm = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: downloadResult.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ModificationController::downloadResult
 * @see app/Http/Controllers/ModificationController.php:369
 * @route '/identity/modification/{reference}/download'
 */
        downloadResultForm.get = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: downloadResult.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ModificationController::downloadResult
 * @see app/Http/Controllers/ModificationController.php:369
 * @route '/identity/modification/{reference}/download'
 */
        downloadResultForm.head = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: downloadResult.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    downloadResult.form = downloadResultForm
const ModificationController = { ninIndex, bvnIndex, index, store, downloadResult }

export default ModificationController