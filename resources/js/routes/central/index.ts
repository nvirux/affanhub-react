import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
 * @see routes/web.php:50
 * @route '//127.0.0.1/privacy-policy'
 */
export const privacyPolicy = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: privacyPolicy.url(options),
    method: 'get',
})

privacyPolicy.definition = {
    methods: ["get","head"],
    url: '//127.0.0.1/privacy-policy',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web.php:50
 * @route '//127.0.0.1/privacy-policy'
 */
privacyPolicy.url = (options?: RouteQueryOptions) => {
    return privacyPolicy.definition.url + queryParams(options)
}

/**
 * @see routes/web.php:50
 * @route '//127.0.0.1/privacy-policy'
 */
privacyPolicy.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: privacyPolicy.url(options),
    method: 'get',
})
/**
 * @see routes/web.php:50
 * @route '//127.0.0.1/privacy-policy'
 */
privacyPolicy.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: privacyPolicy.url(options),
    method: 'head',
})

    /**
 * @see routes/web.php:50
 * @route '//127.0.0.1/privacy-policy'
 */
    const privacyPolicyForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: privacyPolicy.url(options),
        method: 'get',
    })

            /**
 * @see routes/web.php:50
 * @route '//127.0.0.1/privacy-policy'
 */
        privacyPolicyForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: privacyPolicy.url(options),
            method: 'get',
        })
            /**
 * @see routes/web.php:50
 * @route '//127.0.0.1/privacy-policy'
 */
        privacyPolicyForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: privacyPolicy.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    privacyPolicy.form = privacyPolicyForm
/**
 * @see routes/web.php:50
 * @route '//localhost/privacy-policy'
 */
export const privacyPolicy = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: privacyPolicy.url(options),
    method: 'get',
})

privacyPolicy.definition = {
    methods: ["get","head"],
    url: '//localhost/privacy-policy',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web.php:50
 * @route '//localhost/privacy-policy'
 */
privacyPolicy.url = (options?: RouteQueryOptions) => {
    return privacyPolicy.definition.url + queryParams(options)
}

/**
 * @see routes/web.php:50
 * @route '//localhost/privacy-policy'
 */
privacyPolicy.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: privacyPolicy.url(options),
    method: 'get',
})
/**
 * @see routes/web.php:50
 * @route '//localhost/privacy-policy'
 */
privacyPolicy.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: privacyPolicy.url(options),
    method: 'head',
})

    /**
 * @see routes/web.php:50
 * @route '//localhost/privacy-policy'
 */
    const privacyPolicyForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: privacyPolicy.url(options),
        method: 'get',
    })

            /**
 * @see routes/web.php:50
 * @route '//localhost/privacy-policy'
 */
        privacyPolicyForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: privacyPolicy.url(options),
            method: 'get',
        })
            /**
 * @see routes/web.php:50
 * @route '//localhost/privacy-policy'
 */
        privacyPolicyForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: privacyPolicy.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    privacyPolicy.form = privacyPolicyForm
/**
 * @see routes/web.php:50
 * @route '//merchant.localhost/privacy-policy'
 */
export const privacyPolicy = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: privacyPolicy.url(options),
    method: 'get',
})

privacyPolicy.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/privacy-policy',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web.php:50
 * @route '//merchant.localhost/privacy-policy'
 */
privacyPolicy.url = (options?: RouteQueryOptions) => {
    return privacyPolicy.definition.url + queryParams(options)
}

/**
 * @see routes/web.php:50
 * @route '//merchant.localhost/privacy-policy'
 */
privacyPolicy.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: privacyPolicy.url(options),
    method: 'get',
})
/**
 * @see routes/web.php:50
 * @route '//merchant.localhost/privacy-policy'
 */
privacyPolicy.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: privacyPolicy.url(options),
    method: 'head',
})

    /**
 * @see routes/web.php:50
 * @route '//merchant.localhost/privacy-policy'
 */
    const privacyPolicyForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: privacyPolicy.url(options),
        method: 'get',
    })

            /**
 * @see routes/web.php:50
 * @route '//merchant.localhost/privacy-policy'
 */
        privacyPolicyForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: privacyPolicy.url(options),
            method: 'get',
        })
            /**
 * @see routes/web.php:50
 * @route '//merchant.localhost/privacy-policy'
 */
        privacyPolicyForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: privacyPolicy.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    privacyPolicy.form = privacyPolicyForm
/**
 * @see routes/web.php:50
 * @route '//admin.localhost/privacy-policy'
 */
export const privacyPolicy = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: privacyPolicy.url(options),
    method: 'get',
})

privacyPolicy.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/privacy-policy',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web.php:50
 * @route '//admin.localhost/privacy-policy'
 */
privacyPolicy.url = (options?: RouteQueryOptions) => {
    return privacyPolicy.definition.url + queryParams(options)
}

/**
 * @see routes/web.php:50
 * @route '//admin.localhost/privacy-policy'
 */
privacyPolicy.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: privacyPolicy.url(options),
    method: 'get',
})
/**
 * @see routes/web.php:50
 * @route '//admin.localhost/privacy-policy'
 */
privacyPolicy.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: privacyPolicy.url(options),
    method: 'head',
})

    /**
 * @see routes/web.php:50
 * @route '//admin.localhost/privacy-policy'
 */
    const privacyPolicyForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: privacyPolicy.url(options),
        method: 'get',
    })

            /**
 * @see routes/web.php:50
 * @route '//admin.localhost/privacy-policy'
 */
        privacyPolicyForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: privacyPolicy.url(options),
            method: 'get',
        })
            /**
 * @see routes/web.php:50
 * @route '//admin.localhost/privacy-policy'
 */
        privacyPolicyForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: privacyPolicy.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    privacyPolicy.form = privacyPolicyForm
const central = {
    privacyPolicy: Object.assign(privacyPolicy, privacyPolicy),
}

export default central