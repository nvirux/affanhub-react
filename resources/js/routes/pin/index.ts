import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import setup90f0be from './setup'
/**
* @see \App\Http\Controllers\Auth\ForgotPinController::request
 * @see app/Http/Controllers/Auth/ForgotPinController.php:19
 * @route '/forgot-pin'
 */
export const request = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: request.url(options),
    method: 'get',
})

request.definition = {
    methods: ["get","head"],
    url: '/forgot-pin',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\ForgotPinController::request
 * @see app/Http/Controllers/Auth/ForgotPinController.php:19
 * @route '/forgot-pin'
 */
request.url = (options?: RouteQueryOptions) => {
    return request.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ForgotPinController::request
 * @see app/Http/Controllers/Auth/ForgotPinController.php:19
 * @route '/forgot-pin'
 */
request.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: request.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Auth\ForgotPinController::request
 * @see app/Http/Controllers/Auth/ForgotPinController.php:19
 * @route '/forgot-pin'
 */
request.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: request.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Auth\ForgotPinController::request
 * @see app/Http/Controllers/Auth/ForgotPinController.php:19
 * @route '/forgot-pin'
 */
    const requestForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: request.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Auth\ForgotPinController::request
 * @see app/Http/Controllers/Auth/ForgotPinController.php:19
 * @route '/forgot-pin'
 */
        requestForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: request.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Auth\ForgotPinController::request
 * @see app/Http/Controllers/Auth/ForgotPinController.php:19
 * @route '/forgot-pin'
 */
        requestForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: request.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    request.form = requestForm
/**
* @see \App\Http\Controllers\Auth\ForgotPinController::email
 * @see app/Http/Controllers/Auth/ForgotPinController.php:31
 * @route '/forgot-pin'
 */
export const email = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: email.url(options),
    method: 'post',
})

email.definition = {
    methods: ["post"],
    url: '/forgot-pin',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\ForgotPinController::email
 * @see app/Http/Controllers/Auth/ForgotPinController.php:31
 * @route '/forgot-pin'
 */
email.url = (options?: RouteQueryOptions) => {
    return email.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ForgotPinController::email
 * @see app/Http/Controllers/Auth/ForgotPinController.php:31
 * @route '/forgot-pin'
 */
email.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: email.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Auth\ForgotPinController::email
 * @see app/Http/Controllers/Auth/ForgotPinController.php:31
 * @route '/forgot-pin'
 */
    const emailForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: email.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Auth\ForgotPinController::email
 * @see app/Http/Controllers/Auth/ForgotPinController.php:31
 * @route '/forgot-pin'
 */
        emailForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: email.url(options),
            method: 'post',
        })
    
    email.form = emailForm
/**
* @see \App\Http\Controllers\Auth\ForgotPinController::reset
 * @see app/Http/Controllers/Auth/ForgotPinController.php:76
 * @route '/reset-pin/{token}'
 */
export const reset = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reset.url(args, options),
    method: 'get',
})

reset.definition = {
    methods: ["get","head"],
    url: '/reset-pin/{token}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\ForgotPinController::reset
 * @see app/Http/Controllers/Auth/ForgotPinController.php:76
 * @route '/reset-pin/{token}'
 */
reset.url = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { token: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    token: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        token: args.token,
                }

    return reset.definition.url
            .replace('{token}', parsedArgs.token.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ForgotPinController::reset
 * @see app/Http/Controllers/Auth/ForgotPinController.php:76
 * @route '/reset-pin/{token}'
 */
reset.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reset.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Auth\ForgotPinController::reset
 * @see app/Http/Controllers/Auth/ForgotPinController.php:76
 * @route '/reset-pin/{token}'
 */
reset.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: reset.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Auth\ForgotPinController::reset
 * @see app/Http/Controllers/Auth/ForgotPinController.php:76
 * @route '/reset-pin/{token}'
 */
    const resetForm = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: reset.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Auth\ForgotPinController::reset
 * @see app/Http/Controllers/Auth/ForgotPinController.php:76
 * @route '/reset-pin/{token}'
 */
        resetForm.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: reset.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Auth\ForgotPinController::reset
 * @see app/Http/Controllers/Auth/ForgotPinController.php:76
 * @route '/reset-pin/{token}'
 */
        resetForm.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: reset.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    reset.form = resetForm
/**
* @see \App\Http\Controllers\Auth\ForgotPinController::update
 * @see app/Http/Controllers/Auth/ForgotPinController.php:93
 * @route '/reset-pin'
 */
export const update = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

update.definition = {
    methods: ["post"],
    url: '/reset-pin',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\ForgotPinController::update
 * @see app/Http/Controllers/Auth/ForgotPinController.php:93
 * @route '/reset-pin'
 */
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ForgotPinController::update
 * @see app/Http/Controllers/Auth/ForgotPinController.php:93
 * @route '/reset-pin'
 */
update.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Auth\ForgotPinController::update
 * @see app/Http/Controllers/Auth/ForgotPinController.php:93
 * @route '/reset-pin'
 */
    const updateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Auth\ForgotPinController::update
 * @see app/Http/Controllers/Auth/ForgotPinController.php:93
 * @route '/reset-pin'
 */
        updateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(options),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::setup
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
export const setup = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: setup.url(options),
    method: 'get',
})

setup.definition = {
    methods: ["get","head"],
    url: '/setup-pin',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::setup
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
setup.url = (options?: RouteQueryOptions) => {
    return setup.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::setup
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
setup.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: setup.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::setup
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
setup.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: setup.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::setup
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
    const setupForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: setup.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::setup
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
        setupForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: setup.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Auth\TenantPinSetupController::setup
 * @see app/Http/Controllers/Auth/TenantPinSetupController.php:17
 * @route '/setup-pin'
 */
        setupForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: setup.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    setup.form = setupForm
const pin = {
    request: Object.assign(request, request),
email: Object.assign(email, email),
reset: Object.assign(reset, reset),
update: Object.assign(update, update),
setup: Object.assign(setup, setup90f0be),
}

export default pin