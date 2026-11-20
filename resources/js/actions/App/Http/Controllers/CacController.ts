import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac'
 */
const index186b4bee5cf2e9815c45be8af7e43f01 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index186b4bee5cf2e9815c45be8af7e43f01.url(options),
    method: 'get',
})

index186b4bee5cf2e9815c45be8af7e43f01.definition = {
    methods: ["get","head"],
    url: '/cac',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac'
 */
index186b4bee5cf2e9815c45be8af7e43f01.url = (options?: RouteQueryOptions) => {
    return index186b4bee5cf2e9815c45be8af7e43f01.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac'
 */
index186b4bee5cf2e9815c45be8af7e43f01.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index186b4bee5cf2e9815c45be8af7e43f01.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac'
 */
index186b4bee5cf2e9815c45be8af7e43f01.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index186b4bee5cf2e9815c45be8af7e43f01.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac'
 */
    const index186b4bee5cf2e9815c45be8af7e43f01Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index186b4bee5cf2e9815c45be8af7e43f01.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac'
 */
        index186b4bee5cf2e9815c45be8af7e43f01Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index186b4bee5cf2e9815c45be8af7e43f01.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac'
 */
        index186b4bee5cf2e9815c45be8af7e43f01Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index186b4bee5cf2e9815c45be8af7e43f01.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index186b4bee5cf2e9815c45be8af7e43f01.form = index186b4bee5cf2e9815c45be8af7e43f01Form
    /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac-services'
 */
const indexfb87b4066f59df57ddbe3cf170c346ab = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexfb87b4066f59df57ddbe3cf170c346ab.url(options),
    method: 'get',
})

indexfb87b4066f59df57ddbe3cf170c346ab.definition = {
    methods: ["get","head"],
    url: '/cac-services',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac-services'
 */
indexfb87b4066f59df57ddbe3cf170c346ab.url = (options?: RouteQueryOptions) => {
    return indexfb87b4066f59df57ddbe3cf170c346ab.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac-services'
 */
indexfb87b4066f59df57ddbe3cf170c346ab.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexfb87b4066f59df57ddbe3cf170c346ab.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac-services'
 */
indexfb87b4066f59df57ddbe3cf170c346ab.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexfb87b4066f59df57ddbe3cf170c346ab.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac-services'
 */
    const indexfb87b4066f59df57ddbe3cf170c346abForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: indexfb87b4066f59df57ddbe3cf170c346ab.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac-services'
 */
        indexfb87b4066f59df57ddbe3cf170c346abForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexfb87b4066f59df57ddbe3cf170c346ab.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/cac-services'
 */
        indexfb87b4066f59df57ddbe3cf170c346abForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexfb87b4066f59df57ddbe3cf170c346ab.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    indexfb87b4066f59df57ddbe3cf170c346ab.form = indexfb87b4066f59df57ddbe3cf170c346abForm
    /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac'
 */
const index0221c31c6f4eae59a667016fa8a29477 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index0221c31c6f4eae59a667016fa8a29477.url(options),
    method: 'get',
})

index0221c31c6f4eae59a667016fa8a29477.definition = {
    methods: ["get","head"],
    url: '/identity/cac',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac'
 */
index0221c31c6f4eae59a667016fa8a29477.url = (options?: RouteQueryOptions) => {
    return index0221c31c6f4eae59a667016fa8a29477.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac'
 */
index0221c31c6f4eae59a667016fa8a29477.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index0221c31c6f4eae59a667016fa8a29477.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac'
 */
index0221c31c6f4eae59a667016fa8a29477.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index0221c31c6f4eae59a667016fa8a29477.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac'
 */
    const index0221c31c6f4eae59a667016fa8a29477Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index0221c31c6f4eae59a667016fa8a29477.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac'
 */
        index0221c31c6f4eae59a667016fa8a29477Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index0221c31c6f4eae59a667016fa8a29477.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac'
 */
        index0221c31c6f4eae59a667016fa8a29477Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index0221c31c6f4eae59a667016fa8a29477.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index0221c31c6f4eae59a667016fa8a29477.form = index0221c31c6f4eae59a667016fa8a29477Form
    /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac-services'
 */
const index4ac1cc28f0566f5e3e8e9d8757984ff8 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index4ac1cc28f0566f5e3e8e9d8757984ff8.url(options),
    method: 'get',
})

index4ac1cc28f0566f5e3e8e9d8757984ff8.definition = {
    methods: ["get","head"],
    url: '/identity/cac-services',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac-services'
 */
index4ac1cc28f0566f5e3e8e9d8757984ff8.url = (options?: RouteQueryOptions) => {
    return index4ac1cc28f0566f5e3e8e9d8757984ff8.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac-services'
 */
index4ac1cc28f0566f5e3e8e9d8757984ff8.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index4ac1cc28f0566f5e3e8e9d8757984ff8.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac-services'
 */
index4ac1cc28f0566f5e3e8e9d8757984ff8.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index4ac1cc28f0566f5e3e8e9d8757984ff8.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac-services'
 */
    const index4ac1cc28f0566f5e3e8e9d8757984ff8Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index4ac1cc28f0566f5e3e8e9d8757984ff8.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac-services'
 */
        index4ac1cc28f0566f5e3e8e9d8757984ff8Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index4ac1cc28f0566f5e3e8e9d8757984ff8.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\CacController::index
 * @see app/Http/Controllers/CacController.php:24
 * @route '/identity/cac-services'
 */
        index4ac1cc28f0566f5e3e8e9d8757984ff8Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index4ac1cc28f0566f5e3e8e9d8757984ff8.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index4ac1cc28f0566f5e3e8e9d8757984ff8.form = index4ac1cc28f0566f5e3e8e9d8757984ff8Form

/**
* Multiple routes resolve to \App\Http\Controllers\CacController::index, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `index['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const index = {
    '/cac': index186b4bee5cf2e9815c45be8af7e43f01,
    '/cac-services': indexfb87b4066f59df57ddbe3cf170c346ab,
    '/identity/cac': index0221c31c6f4eae59a667016fa8a29477,
    '/identity/cac-services': index4ac1cc28f0566f5e3e8e9d8757984ff8,
}

/**
* @see \App\Http\Controllers\CacController::store
 * @see app/Http/Controllers/CacController.php:104
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
 * @see app/Http/Controllers/CacController.php:104
 * @route '/cac'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CacController::store
 * @see app/Http/Controllers/CacController.php:104
 * @route '/cac'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\CacController::store
 * @see app/Http/Controllers/CacController.php:104
 * @route '/cac'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\CacController::store
 * @see app/Http/Controllers/CacController.php:104
 * @route '/cac'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\CacController::downloadCertificate
 * @see app/Http/Controllers/CacController.php:189
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
 * @see app/Http/Controllers/CacController.php:189
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
 * @see app/Http/Controllers/CacController.php:189
 * @route '/cac/{reference}/download-certificate'
 */
downloadCertificate.get = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadCertificate.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\CacController::downloadCertificate
 * @see app/Http/Controllers/CacController.php:189
 * @route '/cac/{reference}/download-certificate'
 */
downloadCertificate.head = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: downloadCertificate.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\CacController::downloadCertificate
 * @see app/Http/Controllers/CacController.php:189
 * @route '/cac/{reference}/download-certificate'
 */
    const downloadCertificateForm = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: downloadCertificate.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\CacController::downloadCertificate
 * @see app/Http/Controllers/CacController.php:189
 * @route '/cac/{reference}/download-certificate'
 */
        downloadCertificateForm.get = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: downloadCertificate.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\CacController::downloadCertificate
 * @see app/Http/Controllers/CacController.php:189
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
const CacController = { index, store, downloadCertificate }

export default CacController