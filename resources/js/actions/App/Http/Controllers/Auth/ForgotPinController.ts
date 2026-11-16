import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\ForgotPinController::create
 * @see app/Http/Controllers/Auth/ForgotPinController.php:19
 * @route '/forgot-pin'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/forgot-pin',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\ForgotPinController::create
 * @see app/Http/Controllers/Auth/ForgotPinController.php:19
 * @route '/forgot-pin'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ForgotPinController::create
 * @see app/Http/Controllers/Auth/ForgotPinController.php:19
 * @route '/forgot-pin'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Auth\ForgotPinController::create
 * @see app/Http/Controllers/Auth/ForgotPinController.php:19
 * @route '/forgot-pin'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Auth\ForgotPinController::create
 * @see app/Http/Controllers/Auth/ForgotPinController.php:19
 * @route '/forgot-pin'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Auth\ForgotPinController::create
 * @see app/Http/Controllers/Auth/ForgotPinController.php:19
 * @route '/forgot-pin'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Auth\ForgotPinController::create
 * @see app/Http/Controllers/Auth/ForgotPinController.php:19
 * @route '/forgot-pin'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Http\Controllers\Auth\ForgotPinController::store
 * @see app/Http/Controllers/Auth/ForgotPinController.php:31
 * @route '/forgot-pin'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/forgot-pin',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\ForgotPinController::store
 * @see app/Http/Controllers/Auth/ForgotPinController.php:31
 * @route '/forgot-pin'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ForgotPinController::store
 * @see app/Http/Controllers/Auth/ForgotPinController.php:31
 * @route '/forgot-pin'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Auth\ForgotPinController::store
 * @see app/Http/Controllers/Auth/ForgotPinController.php:31
 * @route '/forgot-pin'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Auth\ForgotPinController::store
 * @see app/Http/Controllers/Auth/ForgotPinController.php:31
 * @route '/forgot-pin'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Auth\ForgotPinController::edit
 * @see app/Http/Controllers/Auth/ForgotPinController.php:76
 * @route '/reset-pin/{token}'
 */
export const edit = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/reset-pin/{token}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\ForgotPinController::edit
 * @see app/Http/Controllers/Auth/ForgotPinController.php:76
 * @route '/reset-pin/{token}'
 */
edit.url = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{token}', parsedArgs.token.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ForgotPinController::edit
 * @see app/Http/Controllers/Auth/ForgotPinController.php:76
 * @route '/reset-pin/{token}'
 */
edit.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Auth\ForgotPinController::edit
 * @see app/Http/Controllers/Auth/ForgotPinController.php:76
 * @route '/reset-pin/{token}'
 */
edit.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Auth\ForgotPinController::edit
 * @see app/Http/Controllers/Auth/ForgotPinController.php:76
 * @route '/reset-pin/{token}'
 */
    const editForm = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Auth\ForgotPinController::edit
 * @see app/Http/Controllers/Auth/ForgotPinController.php:76
 * @route '/reset-pin/{token}'
 */
        editForm.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Auth\ForgotPinController::edit
 * @see app/Http/Controllers/Auth/ForgotPinController.php:76
 * @route '/reset-pin/{token}'
 */
        editForm.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
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
const ForgotPinController = { create, store, edit, update }

export default ForgotPinController