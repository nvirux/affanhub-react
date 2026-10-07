import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
const RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url(options),
    method: 'get',
})

RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '//127.0.0.1/privacy',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url = (options?: RouteQueryOptions) => {
    return RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url(options),
    method: 'get',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url(options),
    method: 'head',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url(options),
    method: 'post',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url(options),
    method: 'put',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url(options),
    method: 'patch',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url(options),
    method: 'delete',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url(options),
    method: 'options',
})

    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
    const RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url(options),
        method: 'get',
    })

            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
        RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url(options),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
        RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
        RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url(options),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
        RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
        RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
        RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//127.0.0.1/privacy'
 */
        RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'OPTIONS',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69.form = RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69Form
    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
const RedirectController90983a4a9928a11600412e010dcd6760 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController90983a4a9928a11600412e010dcd6760.url(options),
    method: 'get',
})

RedirectController90983a4a9928a11600412e010dcd6760.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '//localhost/privacy',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
RedirectController90983a4a9928a11600412e010dcd6760.url = (options?: RouteQueryOptions) => {
    return RedirectController90983a4a9928a11600412e010dcd6760.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
RedirectController90983a4a9928a11600412e010dcd6760.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController90983a4a9928a11600412e010dcd6760.url(options),
    method: 'get',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
RedirectController90983a4a9928a11600412e010dcd6760.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController90983a4a9928a11600412e010dcd6760.url(options),
    method: 'head',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
RedirectController90983a4a9928a11600412e010dcd6760.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController90983a4a9928a11600412e010dcd6760.url(options),
    method: 'post',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
RedirectController90983a4a9928a11600412e010dcd6760.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController90983a4a9928a11600412e010dcd6760.url(options),
    method: 'put',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
RedirectController90983a4a9928a11600412e010dcd6760.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController90983a4a9928a11600412e010dcd6760.url(options),
    method: 'patch',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
RedirectController90983a4a9928a11600412e010dcd6760.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController90983a4a9928a11600412e010dcd6760.url(options),
    method: 'delete',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
RedirectController90983a4a9928a11600412e010dcd6760.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController90983a4a9928a11600412e010dcd6760.url(options),
    method: 'options',
})

    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
    const RedirectController90983a4a9928a11600412e010dcd6760Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RedirectController90983a4a9928a11600412e010dcd6760.url(options),
        method: 'get',
    })

            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
        RedirectController90983a4a9928a11600412e010dcd6760Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController90983a4a9928a11600412e010dcd6760.url(options),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
        RedirectController90983a4a9928a11600412e010dcd6760Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController90983a4a9928a11600412e010dcd6760.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
        RedirectController90983a4a9928a11600412e010dcd6760Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController90983a4a9928a11600412e010dcd6760.url(options),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
        RedirectController90983a4a9928a11600412e010dcd6760Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController90983a4a9928a11600412e010dcd6760.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
        RedirectController90983a4a9928a11600412e010dcd6760Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController90983a4a9928a11600412e010dcd6760.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
        RedirectController90983a4a9928a11600412e010dcd6760Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController90983a4a9928a11600412e010dcd6760.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//localhost/privacy'
 */
        RedirectController90983a4a9928a11600412e010dcd6760Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController90983a4a9928a11600412e010dcd6760.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'OPTIONS',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RedirectController90983a4a9928a11600412e010dcd6760.form = RedirectController90983a4a9928a11600412e010dcd6760Form
    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
const RedirectController1fb5d6cc671d576699b2318882e2dd4e = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController1fb5d6cc671d576699b2318882e2dd4e.url(options),
    method: 'get',
})

RedirectController1fb5d6cc671d576699b2318882e2dd4e.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '//merchant.localhost/privacy',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
RedirectController1fb5d6cc671d576699b2318882e2dd4e.url = (options?: RouteQueryOptions) => {
    return RedirectController1fb5d6cc671d576699b2318882e2dd4e.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
RedirectController1fb5d6cc671d576699b2318882e2dd4e.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController1fb5d6cc671d576699b2318882e2dd4e.url(options),
    method: 'get',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
RedirectController1fb5d6cc671d576699b2318882e2dd4e.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController1fb5d6cc671d576699b2318882e2dd4e.url(options),
    method: 'head',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
RedirectController1fb5d6cc671d576699b2318882e2dd4e.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController1fb5d6cc671d576699b2318882e2dd4e.url(options),
    method: 'post',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
RedirectController1fb5d6cc671d576699b2318882e2dd4e.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController1fb5d6cc671d576699b2318882e2dd4e.url(options),
    method: 'put',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
RedirectController1fb5d6cc671d576699b2318882e2dd4e.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController1fb5d6cc671d576699b2318882e2dd4e.url(options),
    method: 'patch',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
RedirectController1fb5d6cc671d576699b2318882e2dd4e.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController1fb5d6cc671d576699b2318882e2dd4e.url(options),
    method: 'delete',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
RedirectController1fb5d6cc671d576699b2318882e2dd4e.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController1fb5d6cc671d576699b2318882e2dd4e.url(options),
    method: 'options',
})

    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
    const RedirectController1fb5d6cc671d576699b2318882e2dd4eForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RedirectController1fb5d6cc671d576699b2318882e2dd4e.url(options),
        method: 'get',
    })

            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
        RedirectController1fb5d6cc671d576699b2318882e2dd4eForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController1fb5d6cc671d576699b2318882e2dd4e.url(options),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
        RedirectController1fb5d6cc671d576699b2318882e2dd4eForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController1fb5d6cc671d576699b2318882e2dd4e.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
        RedirectController1fb5d6cc671d576699b2318882e2dd4eForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController1fb5d6cc671d576699b2318882e2dd4e.url(options),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
        RedirectController1fb5d6cc671d576699b2318882e2dd4eForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController1fb5d6cc671d576699b2318882e2dd4e.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
        RedirectController1fb5d6cc671d576699b2318882e2dd4eForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController1fb5d6cc671d576699b2318882e2dd4e.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
        RedirectController1fb5d6cc671d576699b2318882e2dd4eForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController1fb5d6cc671d576699b2318882e2dd4e.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//merchant.localhost/privacy'
 */
        RedirectController1fb5d6cc671d576699b2318882e2dd4eForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController1fb5d6cc671d576699b2318882e2dd4e.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'OPTIONS',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RedirectController1fb5d6cc671d576699b2318882e2dd4e.form = RedirectController1fb5d6cc671d576699b2318882e2dd4eForm
    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
const RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url(options),
    method: 'get',
})

RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '//admin.localhost/privacy',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url = (options?: RouteQueryOptions) => {
    return RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url(options),
    method: 'get',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url(options),
    method: 'head',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url(options),
    method: 'post',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url(options),
    method: 'put',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url(options),
    method: 'patch',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url(options),
    method: 'delete',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url(options),
    method: 'options',
})

    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
    const RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69fForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url(options),
        method: 'get',
    })

            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
        RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69fForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url(options),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
        RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69fForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
        RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69fForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url(options),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
        RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69fForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
        RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69fForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
        RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69fForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//admin.localhost/privacy'
 */
        RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69fForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'OPTIONS',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f.form = RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69fForm
    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
const RedirectController5937d986ec6e69c76864e78bfceac6a5 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController5937d986ec6e69c76864e78bfceac6a5.url(options),
    method: 'get',
})

RedirectController5937d986ec6e69c76864e78bfceac6a5.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/auth/login',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
RedirectController5937d986ec6e69c76864e78bfceac6a5.url = (options?: RouteQueryOptions) => {
    return RedirectController5937d986ec6e69c76864e78bfceac6a5.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
RedirectController5937d986ec6e69c76864e78bfceac6a5.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController5937d986ec6e69c76864e78bfceac6a5.url(options),
    method: 'get',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
RedirectController5937d986ec6e69c76864e78bfceac6a5.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController5937d986ec6e69c76864e78bfceac6a5.url(options),
    method: 'head',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
RedirectController5937d986ec6e69c76864e78bfceac6a5.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController5937d986ec6e69c76864e78bfceac6a5.url(options),
    method: 'post',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
RedirectController5937d986ec6e69c76864e78bfceac6a5.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController5937d986ec6e69c76864e78bfceac6a5.url(options),
    method: 'put',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
RedirectController5937d986ec6e69c76864e78bfceac6a5.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController5937d986ec6e69c76864e78bfceac6a5.url(options),
    method: 'patch',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
RedirectController5937d986ec6e69c76864e78bfceac6a5.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController5937d986ec6e69c76864e78bfceac6a5.url(options),
    method: 'delete',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
RedirectController5937d986ec6e69c76864e78bfceac6a5.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController5937d986ec6e69c76864e78bfceac6a5.url(options),
    method: 'options',
})

    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
    const RedirectController5937d986ec6e69c76864e78bfceac6a5Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RedirectController5937d986ec6e69c76864e78bfceac6a5.url(options),
        method: 'get',
    })

            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
        RedirectController5937d986ec6e69c76864e78bfceac6a5Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController5937d986ec6e69c76864e78bfceac6a5.url(options),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
        RedirectController5937d986ec6e69c76864e78bfceac6a5Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController5937d986ec6e69c76864e78bfceac6a5.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
        RedirectController5937d986ec6e69c76864e78bfceac6a5Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController5937d986ec6e69c76864e78bfceac6a5.url(options),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
        RedirectController5937d986ec6e69c76864e78bfceac6a5Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController5937d986ec6e69c76864e78bfceac6a5.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
        RedirectController5937d986ec6e69c76864e78bfceac6a5Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController5937d986ec6e69c76864e78bfceac6a5.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
        RedirectController5937d986ec6e69c76864e78bfceac6a5Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController5937d986ec6e69c76864e78bfceac6a5.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/login'
 */
        RedirectController5937d986ec6e69c76864e78bfceac6a5Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController5937d986ec6e69c76864e78bfceac6a5.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'OPTIONS',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RedirectController5937d986ec6e69c76864e78bfceac6a5.form = RedirectController5937d986ec6e69c76864e78bfceac6a5Form
    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
const RedirectController694f388d01b54be09f3b70932e0ac9c7 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController694f388d01b54be09f3b70932e0ac9c7.url(options),
    method: 'get',
})

RedirectController694f388d01b54be09f3b70932e0ac9c7.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/auth/register',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
RedirectController694f388d01b54be09f3b70932e0ac9c7.url = (options?: RouteQueryOptions) => {
    return RedirectController694f388d01b54be09f3b70932e0ac9c7.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
RedirectController694f388d01b54be09f3b70932e0ac9c7.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController694f388d01b54be09f3b70932e0ac9c7.url(options),
    method: 'get',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
RedirectController694f388d01b54be09f3b70932e0ac9c7.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController694f388d01b54be09f3b70932e0ac9c7.url(options),
    method: 'head',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
RedirectController694f388d01b54be09f3b70932e0ac9c7.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController694f388d01b54be09f3b70932e0ac9c7.url(options),
    method: 'post',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
RedirectController694f388d01b54be09f3b70932e0ac9c7.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController694f388d01b54be09f3b70932e0ac9c7.url(options),
    method: 'put',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
RedirectController694f388d01b54be09f3b70932e0ac9c7.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController694f388d01b54be09f3b70932e0ac9c7.url(options),
    method: 'patch',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
RedirectController694f388d01b54be09f3b70932e0ac9c7.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController694f388d01b54be09f3b70932e0ac9c7.url(options),
    method: 'delete',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
RedirectController694f388d01b54be09f3b70932e0ac9c7.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController694f388d01b54be09f3b70932e0ac9c7.url(options),
    method: 'options',
})

    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
    const RedirectController694f388d01b54be09f3b70932e0ac9c7Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RedirectController694f388d01b54be09f3b70932e0ac9c7.url(options),
        method: 'get',
    })

            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
        RedirectController694f388d01b54be09f3b70932e0ac9c7Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController694f388d01b54be09f3b70932e0ac9c7.url(options),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
        RedirectController694f388d01b54be09f3b70932e0ac9c7Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController694f388d01b54be09f3b70932e0ac9c7.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
        RedirectController694f388d01b54be09f3b70932e0ac9c7Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController694f388d01b54be09f3b70932e0ac9c7.url(options),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
        RedirectController694f388d01b54be09f3b70932e0ac9c7Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController694f388d01b54be09f3b70932e0ac9c7.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
        RedirectController694f388d01b54be09f3b70932e0ac9c7Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController694f388d01b54be09f3b70932e0ac9c7.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
        RedirectController694f388d01b54be09f3b70932e0ac9c7Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController694f388d01b54be09f3b70932e0ac9c7.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/auth/register'
 */
        RedirectController694f388d01b54be09f3b70932e0ac9c7Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController694f388d01b54be09f3b70932e0ac9c7.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'OPTIONS',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RedirectController694f388d01b54be09f3b70932e0ac9c7.form = RedirectController694f388d01b54be09f3b70932e0ac9c7Form
    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
const RedirectControllera2c058616aeb0c9393ca03a98bc05c02 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url(options),
    method: 'get',
})

RedirectControllera2c058616aeb0c9393ca03a98bc05c02.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/privacy',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url = (options?: RouteQueryOptions) => {
    return RedirectControllera2c058616aeb0c9393ca03a98bc05c02.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
RedirectControllera2c058616aeb0c9393ca03a98bc05c02.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url(options),
    method: 'get',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
RedirectControllera2c058616aeb0c9393ca03a98bc05c02.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url(options),
    method: 'head',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
RedirectControllera2c058616aeb0c9393ca03a98bc05c02.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url(options),
    method: 'post',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
RedirectControllera2c058616aeb0c9393ca03a98bc05c02.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url(options),
    method: 'put',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
RedirectControllera2c058616aeb0c9393ca03a98bc05c02.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url(options),
    method: 'patch',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
RedirectControllera2c058616aeb0c9393ca03a98bc05c02.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url(options),
    method: 'delete',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
RedirectControllera2c058616aeb0c9393ca03a98bc05c02.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url(options),
    method: 'options',
})

    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
    const RedirectControllera2c058616aeb0c9393ca03a98bc05c02Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url(options),
        method: 'get',
    })

            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
        RedirectControllera2c058616aeb0c9393ca03a98bc05c02Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url(options),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
        RedirectControllera2c058616aeb0c9393ca03a98bc05c02Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
        RedirectControllera2c058616aeb0c9393ca03a98bc05c02Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url(options),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
        RedirectControllera2c058616aeb0c9393ca03a98bc05c02Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
        RedirectControllera2c058616aeb0c9393ca03a98bc05c02Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
        RedirectControllera2c058616aeb0c9393ca03a98bc05c02Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/privacy'
 */
        RedirectControllera2c058616aeb0c9393ca03a98bc05c02Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectControllera2c058616aeb0c9393ca03a98bc05c02.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'OPTIONS',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RedirectControllera2c058616aeb0c9393ca03a98bc05c02.form = RedirectControllera2c058616aeb0c9393ca03a98bc05c02Form
    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
const RedirectController4b87d2df7e3aa853f6720faea796e36c = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'get',
})

RedirectController4b87d2df7e3aa853f6720faea796e36c.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/settings',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
RedirectController4b87d2df7e3aa853f6720faea796e36c.url = (options?: RouteQueryOptions) => {
    return RedirectController4b87d2df7e3aa853f6720faea796e36c.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
RedirectController4b87d2df7e3aa853f6720faea796e36c.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'get',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
RedirectController4b87d2df7e3aa853f6720faea796e36c.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'head',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
RedirectController4b87d2df7e3aa853f6720faea796e36c.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'post',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
RedirectController4b87d2df7e3aa853f6720faea796e36c.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'put',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
RedirectController4b87d2df7e3aa853f6720faea796e36c.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'patch',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
RedirectController4b87d2df7e3aa853f6720faea796e36c.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'delete',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
RedirectController4b87d2df7e3aa853f6720faea796e36c.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'options',
})

    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
    const RedirectController4b87d2df7e3aa853f6720faea796e36cForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
        method: 'get',
    })

            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
        RedirectController4b87d2df7e3aa853f6720faea796e36cForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
        RedirectController4b87d2df7e3aa853f6720faea796e36cForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
        RedirectController4b87d2df7e3aa853f6720faea796e36cForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
        RedirectController4b87d2df7e3aa853f6720faea796e36cForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
        RedirectController4b87d2df7e3aa853f6720faea796e36cForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
        RedirectController4b87d2df7e3aa853f6720faea796e36cForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
        RedirectController4b87d2df7e3aa853f6720faea796e36cForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'OPTIONS',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RedirectController4b87d2df7e3aa853f6720faea796e36c.form = RedirectController4b87d2df7e3aa853f6720faea796e36cForm

/**
* Multiple routes resolve to \Illuminate\Routing\RedirectController::RedirectController, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `RedirectController['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
const RedirectController = {
    '//127.0.0.1/privacy': RedirectControllerc50a2730d2fac8eff3b869ddcecc2a69,
    '//localhost/privacy': RedirectController90983a4a9928a11600412e010dcd6760,
    '//merchant.localhost/privacy': RedirectController1fb5d6cc671d576699b2318882e2dd4e,
    '//admin.localhost/privacy': RedirectControllerc88a625e849e3a0e92f0e6cb4be3c69f,
    '/auth/login': RedirectController5937d986ec6e69c76864e78bfceac6a5,
    '/auth/register': RedirectController694f388d01b54be09f3b70932e0ac9c7,
    '/privacy': RedirectControllera2c058616aeb0c9393ca03a98bc05c02,
    '/settings': RedirectController4b87d2df7e3aa853f6720faea796e36c,
}

export default RedirectController