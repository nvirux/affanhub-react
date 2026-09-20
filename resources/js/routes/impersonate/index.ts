import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\ImpersonationController::consume
 * @see app/Http/Controllers/ImpersonationController.php:19
 * @route '/impersonate/consume'
 */
export const consume = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: consume.url(options),
    method: 'get',
})

consume.definition = {
    methods: ["get","head"],
    url: '/impersonate/consume',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ImpersonationController::consume
 * @see app/Http/Controllers/ImpersonationController.php:19
 * @route '/impersonate/consume'
 */
consume.url = (options?: RouteQueryOptions) => {
    return consume.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ImpersonationController::consume
 * @see app/Http/Controllers/ImpersonationController.php:19
 * @route '/impersonate/consume'
 */
consume.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: consume.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ImpersonationController::consume
 * @see app/Http/Controllers/ImpersonationController.php:19
 * @route '/impersonate/consume'
 */
consume.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: consume.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ImpersonationController::consume
 * @see app/Http/Controllers/ImpersonationController.php:19
 * @route '/impersonate/consume'
 */
    const consumeForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: consume.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ImpersonationController::consume
 * @see app/Http/Controllers/ImpersonationController.php:19
 * @route '/impersonate/consume'
 */
        consumeForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: consume.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ImpersonationController::consume
 * @see app/Http/Controllers/ImpersonationController.php:19
 * @route '/impersonate/consume'
 */
        consumeForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: consume.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    consume.form = consumeForm
/**
* @see \App\Http\Controllers\ImpersonationController::leave
 * @see app/Http/Controllers/ImpersonationController.php:58
 * @route '/impersonate/leave'
 */
export const leave = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: leave.url(options),
    method: 'get',
})

leave.definition = {
    methods: ["get","head"],
    url: '/impersonate/leave',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ImpersonationController::leave
 * @see app/Http/Controllers/ImpersonationController.php:58
 * @route '/impersonate/leave'
 */
leave.url = (options?: RouteQueryOptions) => {
    return leave.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ImpersonationController::leave
 * @see app/Http/Controllers/ImpersonationController.php:58
 * @route '/impersonate/leave'
 */
leave.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: leave.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ImpersonationController::leave
 * @see app/Http/Controllers/ImpersonationController.php:58
 * @route '/impersonate/leave'
 */
leave.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: leave.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ImpersonationController::leave
 * @see app/Http/Controllers/ImpersonationController.php:58
 * @route '/impersonate/leave'
 */
    const leaveForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: leave.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ImpersonationController::leave
 * @see app/Http/Controllers/ImpersonationController.php:58
 * @route '/impersonate/leave'
 */
        leaveForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: leave.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ImpersonationController::leave
 * @see app/Http/Controllers/ImpersonationController.php:58
 * @route '/impersonate/leave'
 */
        leaveForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: leave.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    leave.form = leaveForm
const impersonate = {
    consume: Object.assign(consume, consume),
leave: Object.assign(leave, leave),
}

export default impersonate