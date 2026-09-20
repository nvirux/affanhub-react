import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Settings\SecurityController::edit
 * @see app/Http/Controllers/Settings/SecurityController.php:21
 * @route '/settings/security'
 */
export const edit = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/settings/security',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Settings\SecurityController::edit
 * @see app/Http/Controllers/Settings/SecurityController.php:21
 * @route '/settings/security'
 */
edit.url = (options?: RouteQueryOptions) => {
    return edit.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\SecurityController::edit
 * @see app/Http/Controllers/Settings/SecurityController.php:21
 * @route '/settings/security'
 */
edit.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Settings\SecurityController::edit
 * @see app/Http/Controllers/Settings/SecurityController.php:21
 * @route '/settings/security'
 */
edit.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Settings\SecurityController::edit
 * @see app/Http/Controllers/Settings/SecurityController.php:21
 * @route '/settings/security'
 */
    const editForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Settings\SecurityController::edit
 * @see app/Http/Controllers/Settings/SecurityController.php:21
 * @route '/settings/security'
 */
        editForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Settings\SecurityController::edit
 * @see app/Http/Controllers/Settings/SecurityController.php:21
 * @route '/settings/security'
 */
        editForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
/**
* @see \App\Http\Controllers\Settings\SecurityController::update
 * @see app/Http/Controllers/Settings/SecurityController.php:48
 * @route '/settings/password'
 */
export const update = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/settings/password',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Settings\SecurityController::update
 * @see app/Http/Controllers/Settings/SecurityController.php:48
 * @route '/settings/password'
 */
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\SecurityController::update
 * @see app/Http/Controllers/Settings/SecurityController.php:48
 * @route '/settings/password'
 */
update.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\Settings\SecurityController::update
 * @see app/Http/Controllers/Settings/SecurityController.php:48
 * @route '/settings/password'
 */
    const updateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url({
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Settings\SecurityController::update
 * @see app/Http/Controllers/Settings/SecurityController.php:48
 * @route '/settings/password'
 */
        updateForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\Settings\SecurityController::updateLoginPin
 * @see app/Http/Controllers/Settings/SecurityController.php:66
 * @route '/settings/login-pin'
 */
export const updateLoginPin = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updateLoginPin.url(options),
    method: 'put',
})

updateLoginPin.definition = {
    methods: ["put"],
    url: '/settings/login-pin',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Settings\SecurityController::updateLoginPin
 * @see app/Http/Controllers/Settings/SecurityController.php:66
 * @route '/settings/login-pin'
 */
updateLoginPin.url = (options?: RouteQueryOptions) => {
    return updateLoginPin.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\SecurityController::updateLoginPin
 * @see app/Http/Controllers/Settings/SecurityController.php:66
 * @route '/settings/login-pin'
 */
updateLoginPin.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updateLoginPin.url(options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\Settings\SecurityController::updateLoginPin
 * @see app/Http/Controllers/Settings/SecurityController.php:66
 * @route '/settings/login-pin'
 */
    const updateLoginPinForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updateLoginPin.url({
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Settings\SecurityController::updateLoginPin
 * @see app/Http/Controllers/Settings/SecurityController.php:66
 * @route '/settings/login-pin'
 */
        updateLoginPinForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updateLoginPin.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    updateLoginPin.form = updateLoginPinForm
/**
* @see \App\Http\Controllers\Settings\SecurityController::updateTransactionPin
 * @see app/Http/Controllers/Settings/SecurityController.php:105
 * @route '/settings/transaction-pin'
 */
export const updateTransactionPin = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updateTransactionPin.url(options),
    method: 'put',
})

updateTransactionPin.definition = {
    methods: ["put"],
    url: '/settings/transaction-pin',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Settings\SecurityController::updateTransactionPin
 * @see app/Http/Controllers/Settings/SecurityController.php:105
 * @route '/settings/transaction-pin'
 */
updateTransactionPin.url = (options?: RouteQueryOptions) => {
    return updateTransactionPin.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\SecurityController::updateTransactionPin
 * @see app/Http/Controllers/Settings/SecurityController.php:105
 * @route '/settings/transaction-pin'
 */
updateTransactionPin.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updateTransactionPin.url(options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\Settings\SecurityController::updateTransactionPin
 * @see app/Http/Controllers/Settings/SecurityController.php:105
 * @route '/settings/transaction-pin'
 */
    const updateTransactionPinForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updateTransactionPin.url({
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Settings\SecurityController::updateTransactionPin
 * @see app/Http/Controllers/Settings/SecurityController.php:105
 * @route '/settings/transaction-pin'
 */
        updateTransactionPinForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updateTransactionPin.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    updateTransactionPin.form = updateTransactionPinForm
const SecurityController = { edit, update, updateLoginPin, updateTransactionPin }

export default SecurityController