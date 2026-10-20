import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//{central_domain}/privacy'
 */
const RedirectController16efd12767bca8b30488c11b51d3e0cf = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController16efd12767bca8b30488c11b51d3e0cf.url(args, options),
    method: 'get',
})

RedirectController16efd12767bca8b30488c11b51d3e0cf.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '//{central_domain}/privacy',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//{central_domain}/privacy'
 */
RedirectController16efd12767bca8b30488c11b51d3e0cf.url = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { central_domain: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    central_domain: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        central_domain: args.central_domain,
                }

    return RedirectController16efd12767bca8b30488c11b51d3e0cf.definition.url
            .replace('{central_domain}', parsedArgs.central_domain.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//{central_domain}/privacy'
 */
RedirectController16efd12767bca8b30488c11b51d3e0cf.get = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController16efd12767bca8b30488c11b51d3e0cf.url(args, options),
    method: 'get',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//{central_domain}/privacy'
 */
RedirectController16efd12767bca8b30488c11b51d3e0cf.head = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController16efd12767bca8b30488c11b51d3e0cf.url(args, options),
    method: 'head',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//{central_domain}/privacy'
 */
RedirectController16efd12767bca8b30488c11b51d3e0cf.post = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController16efd12767bca8b30488c11b51d3e0cf.url(args, options),
    method: 'post',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//{central_domain}/privacy'
 */
RedirectController16efd12767bca8b30488c11b51d3e0cf.put = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController16efd12767bca8b30488c11b51d3e0cf.url(args, options),
    method: 'put',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//{central_domain}/privacy'
 */
RedirectController16efd12767bca8b30488c11b51d3e0cf.patch = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController16efd12767bca8b30488c11b51d3e0cf.url(args, options),
    method: 'patch',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//{central_domain}/privacy'
 */
RedirectController16efd12767bca8b30488c11b51d3e0cf.delete = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController16efd12767bca8b30488c11b51d3e0cf.url(args, options),
    method: 'delete',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//{central_domain}/privacy'
 */
RedirectController16efd12767bca8b30488c11b51d3e0cf.options = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController16efd12767bca8b30488c11b51d3e0cf.url(args, options),
    method: 'options',
})

    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//{central_domain}/privacy'
 */
    const RedirectController16efd12767bca8b30488c11b51d3e0cfForm = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RedirectController16efd12767bca8b30488c11b51d3e0cf.url(args, options),
        method: 'get',
    })

            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//{central_domain}/privacy'
 */
        RedirectController16efd12767bca8b30488c11b51d3e0cfForm.get = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController16efd12767bca8b30488c11b51d3e0cf.url(args, options),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//{central_domain}/privacy'
 */
        RedirectController16efd12767bca8b30488c11b51d3e0cfForm.head = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController16efd12767bca8b30488c11b51d3e0cf.url(args, {
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
 * @route '//{central_domain}/privacy'
 */
        RedirectController16efd12767bca8b30488c11b51d3e0cfForm.post = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController16efd12767bca8b30488c11b51d3e0cf.url(args, options),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '//{central_domain}/privacy'
 */
        RedirectController16efd12767bca8b30488c11b51d3e0cfForm.put = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController16efd12767bca8b30488c11b51d3e0cf.url(args, {
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
 * @route '//{central_domain}/privacy'
 */
        RedirectController16efd12767bca8b30488c11b51d3e0cfForm.patch = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController16efd12767bca8b30488c11b51d3e0cf.url(args, {
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
 * @route '//{central_domain}/privacy'
 */
        RedirectController16efd12767bca8b30488c11b51d3e0cfForm.delete = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController16efd12767bca8b30488c11b51d3e0cf.url(args, {
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
 * @route '//{central_domain}/privacy'
 */
        RedirectController16efd12767bca8b30488c11b51d3e0cfForm.options = (args: { central_domain: string | number } | [central_domain: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController16efd12767bca8b30488c11b51d3e0cf.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'OPTIONS',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RedirectController16efd12767bca8b30488c11b51d3e0cf.form = RedirectController16efd12767bca8b30488c11b51d3e0cfForm
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
    '//{central_domain}/privacy': RedirectController16efd12767bca8b30488c11b51d3e0cf,
    '/auth/login': RedirectController5937d986ec6e69c76864e78bfceac6a5,
    '/auth/register': RedirectController694f388d01b54be09f3b70932e0ac9c7,
    '/settings': RedirectController4b87d2df7e3aa853f6720faea796e36c,
}

export default RedirectController