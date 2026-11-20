import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin-modification'
 */
const ninIndex667a8af2db60ad1d826284afcdbe0f0a = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ninIndex667a8af2db60ad1d826284afcdbe0f0a.url(options),
    method: 'get',
})

ninIndex667a8af2db60ad1d826284afcdbe0f0a.definition = {
    methods: ["get","head"],
    url: '/identity/nin-modification',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin-modification'
 */
ninIndex667a8af2db60ad1d826284afcdbe0f0a.url = (options?: RouteQueryOptions) => {
    return ninIndex667a8af2db60ad1d826284afcdbe0f0a.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin-modification'
 */
ninIndex667a8af2db60ad1d826284afcdbe0f0a.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ninIndex667a8af2db60ad1d826284afcdbe0f0a.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin-modification'
 */
ninIndex667a8af2db60ad1d826284afcdbe0f0a.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ninIndex667a8af2db60ad1d826284afcdbe0f0a.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin-modification'
 */
    const ninIndex667a8af2db60ad1d826284afcdbe0f0aForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ninIndex667a8af2db60ad1d826284afcdbe0f0a.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin-modification'
 */
        ninIndex667a8af2db60ad1d826284afcdbe0f0aForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ninIndex667a8af2db60ad1d826284afcdbe0f0a.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin-modification'
 */
        ninIndex667a8af2db60ad1d826284afcdbe0f0aForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ninIndex667a8af2db60ad1d826284afcdbe0f0a.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ninIndex667a8af2db60ad1d826284afcdbe0f0a.form = ninIndex667a8af2db60ad1d826284afcdbe0f0aForm
    /**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin_modification'
 */
const ninIndexb8397a4f881f2f4343d745f01c1b2784 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ninIndexb8397a4f881f2f4343d745f01c1b2784.url(options),
    method: 'get',
})

ninIndexb8397a4f881f2f4343d745f01c1b2784.definition = {
    methods: ["get","head"],
    url: '/identity/nin_modification',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin_modification'
 */
ninIndexb8397a4f881f2f4343d745f01c1b2784.url = (options?: RouteQueryOptions) => {
    return ninIndexb8397a4f881f2f4343d745f01c1b2784.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin_modification'
 */
ninIndexb8397a4f881f2f4343d745f01c1b2784.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ninIndexb8397a4f881f2f4343d745f01c1b2784.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin_modification'
 */
ninIndexb8397a4f881f2f4343d745f01c1b2784.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ninIndexb8397a4f881f2f4343d745f01c1b2784.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin_modification'
 */
    const ninIndexb8397a4f881f2f4343d745f01c1b2784Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ninIndexb8397a4f881f2f4343d745f01c1b2784.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin_modification'
 */
        ninIndexb8397a4f881f2f4343d745f01c1b2784Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ninIndexb8397a4f881f2f4343d745f01c1b2784.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ModificationController::ninIndex
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin_modification'
 */
        ninIndexb8397a4f881f2f4343d745f01c1b2784Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ninIndexb8397a4f881f2f4343d745f01c1b2784.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ninIndexb8397a4f881f2f4343d745f01c1b2784.form = ninIndexb8397a4f881f2f4343d745f01c1b2784Form

/**
* Multiple routes resolve to \App\Http\Controllers\ModificationController::ninIndex, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `ninIndex['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const ninIndex = {
    '/identity/nin-modification': ninIndex667a8af2db60ad1d826284afcdbe0f0a,
    '/identity/nin_modification': ninIndexb8397a4f881f2f4343d745f01c1b2784,
}

/**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn-modification'
 */
const bvnIndex3225bfe169070270e2f2c97d1528f6fb = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bvnIndex3225bfe169070270e2f2c97d1528f6fb.url(options),
    method: 'get',
})

bvnIndex3225bfe169070270e2f2c97d1528f6fb.definition = {
    methods: ["get","head"],
    url: '/identity/bvn-modification',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn-modification'
 */
bvnIndex3225bfe169070270e2f2c97d1528f6fb.url = (options?: RouteQueryOptions) => {
    return bvnIndex3225bfe169070270e2f2c97d1528f6fb.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn-modification'
 */
bvnIndex3225bfe169070270e2f2c97d1528f6fb.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bvnIndex3225bfe169070270e2f2c97d1528f6fb.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn-modification'
 */
bvnIndex3225bfe169070270e2f2c97d1528f6fb.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: bvnIndex3225bfe169070270e2f2c97d1528f6fb.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn-modification'
 */
    const bvnIndex3225bfe169070270e2f2c97d1528f6fbForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: bvnIndex3225bfe169070270e2f2c97d1528f6fb.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn-modification'
 */
        bvnIndex3225bfe169070270e2f2c97d1528f6fbForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bvnIndex3225bfe169070270e2f2c97d1528f6fb.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn-modification'
 */
        bvnIndex3225bfe169070270e2f2c97d1528f6fbForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bvnIndex3225bfe169070270e2f2c97d1528f6fb.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    bvnIndex3225bfe169070270e2f2c97d1528f6fb.form = bvnIndex3225bfe169070270e2f2c97d1528f6fbForm
    /**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn_modification'
 */
const bvnIndexf324de4f6317a1d923318a265bcb7b1b = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bvnIndexf324de4f6317a1d923318a265bcb7b1b.url(options),
    method: 'get',
})

bvnIndexf324de4f6317a1d923318a265bcb7b1b.definition = {
    methods: ["get","head"],
    url: '/identity/bvn_modification',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn_modification'
 */
bvnIndexf324de4f6317a1d923318a265bcb7b1b.url = (options?: RouteQueryOptions) => {
    return bvnIndexf324de4f6317a1d923318a265bcb7b1b.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn_modification'
 */
bvnIndexf324de4f6317a1d923318a265bcb7b1b.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bvnIndexf324de4f6317a1d923318a265bcb7b1b.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn_modification'
 */
bvnIndexf324de4f6317a1d923318a265bcb7b1b.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: bvnIndexf324de4f6317a1d923318a265bcb7b1b.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn_modification'
 */
    const bvnIndexf324de4f6317a1d923318a265bcb7b1bForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: bvnIndexf324de4f6317a1d923318a265bcb7b1b.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn_modification'
 */
        bvnIndexf324de4f6317a1d923318a265bcb7b1bForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bvnIndexf324de4f6317a1d923318a265bcb7b1b.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ModificationController::bvnIndex
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn_modification'
 */
        bvnIndexf324de4f6317a1d923318a265bcb7b1bForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bvnIndexf324de4f6317a1d923318a265bcb7b1b.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    bvnIndexf324de4f6317a1d923318a265bcb7b1b.form = bvnIndexf324de4f6317a1d923318a265bcb7b1bForm

/**
* Multiple routes resolve to \App\Http\Controllers\ModificationController::bvnIndex, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `bvnIndex['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const bvnIndex = {
    '/identity/bvn-modification': bvnIndex3225bfe169070270e2f2c97d1528f6fb,
    '/identity/bvn_modification': bvnIndexf324de4f6317a1d923318a265bcb7b1b,
}

/**
* @see \App\Http\Controllers\ModificationController::index
 * @see app/Http/Controllers/ModificationController.php:42
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
 * @see app/Http/Controllers/ModificationController.php:42
 * @route '/identity/modification'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ModificationController::index
 * @see app/Http/Controllers/ModificationController.php:42
 * @route '/identity/modification'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ModificationController::index
 * @see app/Http/Controllers/ModificationController.php:42
 * @route '/identity/modification'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ModificationController::index
 * @see app/Http/Controllers/ModificationController.php:42
 * @route '/identity/modification'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ModificationController::index
 * @see app/Http/Controllers/ModificationController.php:42
 * @route '/identity/modification'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ModificationController::index
 * @see app/Http/Controllers/ModificationController.php:42
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
 * @see app/Http/Controllers/ModificationController.php:170
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
 * @see app/Http/Controllers/ModificationController.php:170
 * @route '/identity/modification'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ModificationController::store
 * @see app/Http/Controllers/ModificationController.php:170
 * @route '/identity/modification'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ModificationController::store
 * @see app/Http/Controllers/ModificationController.php:170
 * @route '/identity/modification'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ModificationController::store
 * @see app/Http/Controllers/ModificationController.php:170
 * @route '/identity/modification'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\ModificationController::downloadResult
 * @see app/Http/Controllers/ModificationController.php:398
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
 * @see app/Http/Controllers/ModificationController.php:398
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
 * @see app/Http/Controllers/ModificationController.php:398
 * @route '/identity/modification/{reference}/download'
 */
downloadResult.get = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadResult.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ModificationController::downloadResult
 * @see app/Http/Controllers/ModificationController.php:398
 * @route '/identity/modification/{reference}/download'
 */
downloadResult.head = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: downloadResult.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ModificationController::downloadResult
 * @see app/Http/Controllers/ModificationController.php:398
 * @route '/identity/modification/{reference}/download'
 */
    const downloadResultForm = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: downloadResult.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ModificationController::downloadResult
 * @see app/Http/Controllers/ModificationController.php:398
 * @route '/identity/modification/{reference}/download'
 */
        downloadResultForm.get = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: downloadResult.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ModificationController::downloadResult
 * @see app/Http/Controllers/ModificationController.php:398
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