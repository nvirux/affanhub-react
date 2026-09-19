import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
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
const impersonate = {
    consume: Object.assign(consume, consume),
leave: Object.assign(leave, leave),
}

export default impersonate