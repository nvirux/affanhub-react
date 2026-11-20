import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
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

    /**
* @see \App\Http\Controllers\ServicesController::index
 * @see app/Http/Controllers/ServicesController.php:18
 * @route '/services'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::index
 * @see app/Http/Controllers/ServicesController.php:18
 * @route '/services'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::index
 * @see app/Http/Controllers/ServicesController.php:18
 * @route '/services'
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
* @see \App\Http\Controllers\ServicesController::cable
 * @see app/Http/Controllers/ServicesController.php:95
 * @route '/vtu/cable'
 */
export const cable = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: cable.url(options),
    method: 'get',
})

cable.definition = {
    methods: ["get","head"],
    url: '/vtu/cable',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::cable
 * @see app/Http/Controllers/ServicesController.php:95
 * @route '/vtu/cable'
 */
cable.url = (options?: RouteQueryOptions) => {
    return cable.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::cable
 * @see app/Http/Controllers/ServicesController.php:95
 * @route '/vtu/cable'
 */
cable.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: cable.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::cable
 * @see app/Http/Controllers/ServicesController.php:95
 * @route '/vtu/cable'
 */
cable.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: cable.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::cable
 * @see app/Http/Controllers/ServicesController.php:95
 * @route '/vtu/cable'
 */
    const cableForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: cable.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::cable
 * @see app/Http/Controllers/ServicesController.php:95
 * @route '/vtu/cable'
 */
        cableForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: cable.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::cable
 * @see app/Http/Controllers/ServicesController.php:95
 * @route '/vtu/cable'
 */
        cableForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: cable.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    cable.form = cableForm
/**
* @see \App\Http\Controllers\ServicesController::electricity
 * @see app/Http/Controllers/ServicesController.php:100
 * @route '/vtu/electricity'
 */
export const electricity = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: electricity.url(options),
    method: 'get',
})

electricity.definition = {
    methods: ["get","head"],
    url: '/vtu/electricity',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::electricity
 * @see app/Http/Controllers/ServicesController.php:100
 * @route '/vtu/electricity'
 */
electricity.url = (options?: RouteQueryOptions) => {
    return electricity.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::electricity
 * @see app/Http/Controllers/ServicesController.php:100
 * @route '/vtu/electricity'
 */
electricity.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: electricity.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::electricity
 * @see app/Http/Controllers/ServicesController.php:100
 * @route '/vtu/electricity'
 */
electricity.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: electricity.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::electricity
 * @see app/Http/Controllers/ServicesController.php:100
 * @route '/vtu/electricity'
 */
    const electricityForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: electricity.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::electricity
 * @see app/Http/Controllers/ServicesController.php:100
 * @route '/vtu/electricity'
 */
        electricityForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: electricity.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::electricity
 * @see app/Http/Controllers/ServicesController.php:100
 * @route '/vtu/electricity'
 */
        electricityForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: electricity.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    electricity.form = electricityForm
/**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/education'
 */
const education65c3c307d1da24e74fbdfdcf602f61d6 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: education65c3c307d1da24e74fbdfdcf602f61d6.url(options),
    method: 'get',
})

education65c3c307d1da24e74fbdfdcf602f61d6.definition = {
    methods: ["get","head"],
    url: '/vtu/education',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/education'
 */
education65c3c307d1da24e74fbdfdcf602f61d6.url = (options?: RouteQueryOptions) => {
    return education65c3c307d1da24e74fbdfdcf602f61d6.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/education'
 */
education65c3c307d1da24e74fbdfdcf602f61d6.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: education65c3c307d1da24e74fbdfdcf602f61d6.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/education'
 */
education65c3c307d1da24e74fbdfdcf602f61d6.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: education65c3c307d1da24e74fbdfdcf602f61d6.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/education'
 */
    const education65c3c307d1da24e74fbdfdcf602f61d6Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: education65c3c307d1da24e74fbdfdcf602f61d6.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/education'
 */
        education65c3c307d1da24e74fbdfdcf602f61d6Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: education65c3c307d1da24e74fbdfdcf602f61d6.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/education'
 */
        education65c3c307d1da24e74fbdfdcf602f61d6Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: education65c3c307d1da24e74fbdfdcf602f61d6.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    education65c3c307d1da24e74fbdfdcf602f61d6.form = education65c3c307d1da24e74fbdfdcf602f61d6Form
    /**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam-pins'
 */
const education29242d0896af91ff51106137d052e415 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: education29242d0896af91ff51106137d052e415.url(options),
    method: 'get',
})

education29242d0896af91ff51106137d052e415.definition = {
    methods: ["get","head"],
    url: '/vtu/exam-pins',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam-pins'
 */
education29242d0896af91ff51106137d052e415.url = (options?: RouteQueryOptions) => {
    return education29242d0896af91ff51106137d052e415.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam-pins'
 */
education29242d0896af91ff51106137d052e415.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: education29242d0896af91ff51106137d052e415.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam-pins'
 */
education29242d0896af91ff51106137d052e415.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: education29242d0896af91ff51106137d052e415.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam-pins'
 */
    const education29242d0896af91ff51106137d052e415Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: education29242d0896af91ff51106137d052e415.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam-pins'
 */
        education29242d0896af91ff51106137d052e415Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: education29242d0896af91ff51106137d052e415.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam-pins'
 */
        education29242d0896af91ff51106137d052e415Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: education29242d0896af91ff51106137d052e415.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    education29242d0896af91ff51106137d052e415.form = education29242d0896af91ff51106137d052e415Form
    /**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam_pins'
 */
const education64c91afa394d03e84b7deccf0d93e4d2 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: education64c91afa394d03e84b7deccf0d93e4d2.url(options),
    method: 'get',
})

education64c91afa394d03e84b7deccf0d93e4d2.definition = {
    methods: ["get","head"],
    url: '/vtu/exam_pins',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam_pins'
 */
education64c91afa394d03e84b7deccf0d93e4d2.url = (options?: RouteQueryOptions) => {
    return education64c91afa394d03e84b7deccf0d93e4d2.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam_pins'
 */
education64c91afa394d03e84b7deccf0d93e4d2.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: education64c91afa394d03e84b7deccf0d93e4d2.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam_pins'
 */
education64c91afa394d03e84b7deccf0d93e4d2.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: education64c91afa394d03e84b7deccf0d93e4d2.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam_pins'
 */
    const education64c91afa394d03e84b7deccf0d93e4d2Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: education64c91afa394d03e84b7deccf0d93e4d2.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam_pins'
 */
        education64c91afa394d03e84b7deccf0d93e4d2Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: education64c91afa394d03e84b7deccf0d93e4d2.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam_pins'
 */
        education64c91afa394d03e84b7deccf0d93e4d2Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: education64c91afa394d03e84b7deccf0d93e4d2.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    education64c91afa394d03e84b7deccf0d93e4d2.form = education64c91afa394d03e84b7deccf0d93e4d2Form

/**
* Multiple routes resolve to \App\Http\Controllers\ServicesController::education, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `education['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const education = {
    '/vtu/education': education65c3c307d1da24e74fbdfdcf602f61d6,
    '/vtu/exam-pins': education29242d0896af91ff51106137d052e415,
    '/vtu/exam_pins': education64c91afa394d03e84b7deccf0d93e4d2,
}

/**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk-sms'
 */
const bulkSms45cbe33695ee1a540ce1f15e14c7e8d4 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bulkSms45cbe33695ee1a540ce1f15e14c7e8d4.url(options),
    method: 'get',
})

bulkSms45cbe33695ee1a540ce1f15e14c7e8d4.definition = {
    methods: ["get","head"],
    url: '/vtu/bulk-sms',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk-sms'
 */
bulkSms45cbe33695ee1a540ce1f15e14c7e8d4.url = (options?: RouteQueryOptions) => {
    return bulkSms45cbe33695ee1a540ce1f15e14c7e8d4.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk-sms'
 */
bulkSms45cbe33695ee1a540ce1f15e14c7e8d4.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bulkSms45cbe33695ee1a540ce1f15e14c7e8d4.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk-sms'
 */
bulkSms45cbe33695ee1a540ce1f15e14c7e8d4.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: bulkSms45cbe33695ee1a540ce1f15e14c7e8d4.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk-sms'
 */
    const bulkSms45cbe33695ee1a540ce1f15e14c7e8d4Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: bulkSms45cbe33695ee1a540ce1f15e14c7e8d4.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk-sms'
 */
        bulkSms45cbe33695ee1a540ce1f15e14c7e8d4Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bulkSms45cbe33695ee1a540ce1f15e14c7e8d4.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk-sms'
 */
        bulkSms45cbe33695ee1a540ce1f15e14c7e8d4Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bulkSms45cbe33695ee1a540ce1f15e14c7e8d4.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    bulkSms45cbe33695ee1a540ce1f15e14c7e8d4.form = bulkSms45cbe33695ee1a540ce1f15e14c7e8d4Form
    /**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk_sms'
 */
const bulkSms3ddcec96774a8e8cfc17421312b45e64 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bulkSms3ddcec96774a8e8cfc17421312b45e64.url(options),
    method: 'get',
})

bulkSms3ddcec96774a8e8cfc17421312b45e64.definition = {
    methods: ["get","head"],
    url: '/vtu/bulk_sms',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk_sms'
 */
bulkSms3ddcec96774a8e8cfc17421312b45e64.url = (options?: RouteQueryOptions) => {
    return bulkSms3ddcec96774a8e8cfc17421312b45e64.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk_sms'
 */
bulkSms3ddcec96774a8e8cfc17421312b45e64.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bulkSms3ddcec96774a8e8cfc17421312b45e64.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk_sms'
 */
bulkSms3ddcec96774a8e8cfc17421312b45e64.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: bulkSms3ddcec96774a8e8cfc17421312b45e64.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk_sms'
 */
    const bulkSms3ddcec96774a8e8cfc17421312b45e64Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: bulkSms3ddcec96774a8e8cfc17421312b45e64.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk_sms'
 */
        bulkSms3ddcec96774a8e8cfc17421312b45e64Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bulkSms3ddcec96774a8e8cfc17421312b45e64.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk_sms'
 */
        bulkSms3ddcec96774a8e8cfc17421312b45e64Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bulkSms3ddcec96774a8e8cfc17421312b45e64.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    bulkSms3ddcec96774a8e8cfc17421312b45e64.form = bulkSms3ddcec96774a8e8cfc17421312b45e64Form

/**
* Multiple routes resolve to \App\Http\Controllers\ServicesController::bulkSms, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `bulkSms['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const bulkSms = {
    '/vtu/bulk-sms': bulkSms45cbe33695ee1a540ce1f15e14c7e8d4,
    '/vtu/bulk_sms': bulkSms3ddcec96774a8e8cfc17421312b45e64,
}

/**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin-validation'
 */
const ninValidation6471f4c9a589f7799468a73c7dcf8f39 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ninValidation6471f4c9a589f7799468a73c7dcf8f39.url(options),
    method: 'get',
})

ninValidation6471f4c9a589f7799468a73c7dcf8f39.definition = {
    methods: ["get","head"],
    url: '/identity/nin-validation',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin-validation'
 */
ninValidation6471f4c9a589f7799468a73c7dcf8f39.url = (options?: RouteQueryOptions) => {
    return ninValidation6471f4c9a589f7799468a73c7dcf8f39.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin-validation'
 */
ninValidation6471f4c9a589f7799468a73c7dcf8f39.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ninValidation6471f4c9a589f7799468a73c7dcf8f39.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin-validation'
 */
ninValidation6471f4c9a589f7799468a73c7dcf8f39.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ninValidation6471f4c9a589f7799468a73c7dcf8f39.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin-validation'
 */
    const ninValidation6471f4c9a589f7799468a73c7dcf8f39Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ninValidation6471f4c9a589f7799468a73c7dcf8f39.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin-validation'
 */
        ninValidation6471f4c9a589f7799468a73c7dcf8f39Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ninValidation6471f4c9a589f7799468a73c7dcf8f39.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin-validation'
 */
        ninValidation6471f4c9a589f7799468a73c7dcf8f39Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ninValidation6471f4c9a589f7799468a73c7dcf8f39.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ninValidation6471f4c9a589f7799468a73c7dcf8f39.form = ninValidation6471f4c9a589f7799468a73c7dcf8f39Form
    /**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin_validation'
 */
const ninValidation5717fe426e85dcc53a25d60ad2f03c00 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ninValidation5717fe426e85dcc53a25d60ad2f03c00.url(options),
    method: 'get',
})

ninValidation5717fe426e85dcc53a25d60ad2f03c00.definition = {
    methods: ["get","head"],
    url: '/identity/nin_validation',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin_validation'
 */
ninValidation5717fe426e85dcc53a25d60ad2f03c00.url = (options?: RouteQueryOptions) => {
    return ninValidation5717fe426e85dcc53a25d60ad2f03c00.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin_validation'
 */
ninValidation5717fe426e85dcc53a25d60ad2f03c00.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ninValidation5717fe426e85dcc53a25d60ad2f03c00.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin_validation'
 */
ninValidation5717fe426e85dcc53a25d60ad2f03c00.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ninValidation5717fe426e85dcc53a25d60ad2f03c00.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin_validation'
 */
    const ninValidation5717fe426e85dcc53a25d60ad2f03c00Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ninValidation5717fe426e85dcc53a25d60ad2f03c00.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin_validation'
 */
        ninValidation5717fe426e85dcc53a25d60ad2f03c00Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ninValidation5717fe426e85dcc53a25d60ad2f03c00.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin_validation'
 */
        ninValidation5717fe426e85dcc53a25d60ad2f03c00Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ninValidation5717fe426e85dcc53a25d60ad2f03c00.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ninValidation5717fe426e85dcc53a25d60ad2f03c00.form = ninValidation5717fe426e85dcc53a25d60ad2f03c00Form
    /**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/validation'
 */
const ninValidationaab8fec0e466d1270769b4a954fb2550 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ninValidationaab8fec0e466d1270769b4a954fb2550.url(options),
    method: 'get',
})

ninValidationaab8fec0e466d1270769b4a954fb2550.definition = {
    methods: ["get","head"],
    url: '/identity/validation',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/validation'
 */
ninValidationaab8fec0e466d1270769b4a954fb2550.url = (options?: RouteQueryOptions) => {
    return ninValidationaab8fec0e466d1270769b4a954fb2550.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/validation'
 */
ninValidationaab8fec0e466d1270769b4a954fb2550.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ninValidationaab8fec0e466d1270769b4a954fb2550.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/validation'
 */
ninValidationaab8fec0e466d1270769b4a954fb2550.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ninValidationaab8fec0e466d1270769b4a954fb2550.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/validation'
 */
    const ninValidationaab8fec0e466d1270769b4a954fb2550Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ninValidationaab8fec0e466d1270769b4a954fb2550.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/validation'
 */
        ninValidationaab8fec0e466d1270769b4a954fb2550Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ninValidationaab8fec0e466d1270769b4a954fb2550.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/validation'
 */
        ninValidationaab8fec0e466d1270769b4a954fb2550Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ninValidationaab8fec0e466d1270769b4a954fb2550.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ninValidationaab8fec0e466d1270769b4a954fb2550.form = ninValidationaab8fec0e466d1270769b4a954fb2550Form

/**
* Multiple routes resolve to \App\Http\Controllers\ServicesController::ninValidation, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `ninValidation['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const ninValidation = {
    '/identity/nin-validation': ninValidation6471f4c9a589f7799468a73c7dcf8f39,
    '/identity/nin_validation': ninValidation5717fe426e85dcc53a25d60ad2f03c00,
    '/identity/validation': ninValidationaab8fec0e466d1270769b4a954fb2550,
}

/**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe-clearance'
 */
const ipeClearance42308dc100cc82b5e5e20df55a595d6a = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ipeClearance42308dc100cc82b5e5e20df55a595d6a.url(options),
    method: 'get',
})

ipeClearance42308dc100cc82b5e5e20df55a595d6a.definition = {
    methods: ["get","head"],
    url: '/identity/ipe-clearance',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe-clearance'
 */
ipeClearance42308dc100cc82b5e5e20df55a595d6a.url = (options?: RouteQueryOptions) => {
    return ipeClearance42308dc100cc82b5e5e20df55a595d6a.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe-clearance'
 */
ipeClearance42308dc100cc82b5e5e20df55a595d6a.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ipeClearance42308dc100cc82b5e5e20df55a595d6a.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe-clearance'
 */
ipeClearance42308dc100cc82b5e5e20df55a595d6a.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ipeClearance42308dc100cc82b5e5e20df55a595d6a.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe-clearance'
 */
    const ipeClearance42308dc100cc82b5e5e20df55a595d6aForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ipeClearance42308dc100cc82b5e5e20df55a595d6a.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe-clearance'
 */
        ipeClearance42308dc100cc82b5e5e20df55a595d6aForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ipeClearance42308dc100cc82b5e5e20df55a595d6a.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe-clearance'
 */
        ipeClearance42308dc100cc82b5e5e20df55a595d6aForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ipeClearance42308dc100cc82b5e5e20df55a595d6a.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ipeClearance42308dc100cc82b5e5e20df55a595d6a.form = ipeClearance42308dc100cc82b5e5e20df55a595d6aForm
    /**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe_clearance'
 */
const ipeClearanced5716d6f30695938d187cbf2b78f1c31 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ipeClearanced5716d6f30695938d187cbf2b78f1c31.url(options),
    method: 'get',
})

ipeClearanced5716d6f30695938d187cbf2b78f1c31.definition = {
    methods: ["get","head"],
    url: '/identity/ipe_clearance',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe_clearance'
 */
ipeClearanced5716d6f30695938d187cbf2b78f1c31.url = (options?: RouteQueryOptions) => {
    return ipeClearanced5716d6f30695938d187cbf2b78f1c31.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe_clearance'
 */
ipeClearanced5716d6f30695938d187cbf2b78f1c31.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ipeClearanced5716d6f30695938d187cbf2b78f1c31.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe_clearance'
 */
ipeClearanced5716d6f30695938d187cbf2b78f1c31.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ipeClearanced5716d6f30695938d187cbf2b78f1c31.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe_clearance'
 */
    const ipeClearanced5716d6f30695938d187cbf2b78f1c31Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ipeClearanced5716d6f30695938d187cbf2b78f1c31.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe_clearance'
 */
        ipeClearanced5716d6f30695938d187cbf2b78f1c31Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ipeClearanced5716d6f30695938d187cbf2b78f1c31.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe_clearance'
 */
        ipeClearanced5716d6f30695938d187cbf2b78f1c31Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ipeClearanced5716d6f30695938d187cbf2b78f1c31.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ipeClearanced5716d6f30695938d187cbf2b78f1c31.form = ipeClearanced5716d6f30695938d187cbf2b78f1c31Form
    /**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe'
 */
const ipeClearance7ad7049c3fb022d73c4bbef1292b3d32 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ipeClearance7ad7049c3fb022d73c4bbef1292b3d32.url(options),
    method: 'get',
})

ipeClearance7ad7049c3fb022d73c4bbef1292b3d32.definition = {
    methods: ["get","head"],
    url: '/identity/ipe',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe'
 */
ipeClearance7ad7049c3fb022d73c4bbef1292b3d32.url = (options?: RouteQueryOptions) => {
    return ipeClearance7ad7049c3fb022d73c4bbef1292b3d32.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe'
 */
ipeClearance7ad7049c3fb022d73c4bbef1292b3d32.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ipeClearance7ad7049c3fb022d73c4bbef1292b3d32.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe'
 */
ipeClearance7ad7049c3fb022d73c4bbef1292b3d32.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ipeClearance7ad7049c3fb022d73c4bbef1292b3d32.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe'
 */
    const ipeClearance7ad7049c3fb022d73c4bbef1292b3d32Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ipeClearance7ad7049c3fb022d73c4bbef1292b3d32.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe'
 */
        ipeClearance7ad7049c3fb022d73c4bbef1292b3d32Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ipeClearance7ad7049c3fb022d73c4bbef1292b3d32.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe'
 */
        ipeClearance7ad7049c3fb022d73c4bbef1292b3d32Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ipeClearance7ad7049c3fb022d73c4bbef1292b3d32.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ipeClearance7ad7049c3fb022d73c4bbef1292b3d32.form = ipeClearance7ad7049c3fb022d73c4bbef1292b3d32Form

/**
* Multiple routes resolve to \App\Http\Controllers\ServicesController::ipeClearance, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `ipeClearance['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const ipeClearance = {
    '/identity/ipe-clearance': ipeClearance42308dc100cc82b5e5e20df55a595d6a,
    '/identity/ipe_clearance': ipeClearanced5716d6f30695938d187cbf2b78f1c31,
    '/identity/ipe': ipeClearance7ad7049c3fb022d73c4bbef1292b3d32,
}

const ServicesController = { index, cable, electricity, education, bulkSms, ninValidation, ipeClearance }

export default ServicesController