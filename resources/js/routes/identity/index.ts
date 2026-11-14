import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
import ninBd9d09 from './nin'
import bvnC51c8d from './bvn'
import modification88c75b from './modification'
import verifications from './verifications'
/**
* @see \App\Http\Controllers\NinVerificationController::nin
 * @see app/Http/Controllers/NinVerificationController.php:24
 * @route '/identity/nin'
 */
export const nin = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: nin.url(options),
    method: 'get',
})

nin.definition = {
    methods: ["get","head"],
    url: '/identity/nin',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\NinVerificationController::nin
 * @see app/Http/Controllers/NinVerificationController.php:24
 * @route '/identity/nin'
 */
nin.url = (options?: RouteQueryOptions) => {
    return nin.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\NinVerificationController::nin
 * @see app/Http/Controllers/NinVerificationController.php:24
 * @route '/identity/nin'
 */
nin.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: nin.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\NinVerificationController::nin
 * @see app/Http/Controllers/NinVerificationController.php:24
 * @route '/identity/nin'
 */
nin.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: nin.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\BvnVerificationController::bvn
 * @see app/Http/Controllers/BvnVerificationController.php:24
 * @route '/identity/bvn'
 */
export const bvn = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bvn.url(options),
    method: 'get',
})

bvn.definition = {
    methods: ["get","head"],
    url: '/identity/bvn',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\BvnVerificationController::bvn
 * @see app/Http/Controllers/BvnVerificationController.php:24
 * @route '/identity/bvn'
 */
bvn.url = (options?: RouteQueryOptions) => {
    return bvn.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\BvnVerificationController::bvn
 * @see app/Http/Controllers/BvnVerificationController.php:24
 * @route '/identity/bvn'
 */
bvn.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bvn.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\BvnVerificationController::bvn
 * @see app/Http/Controllers/BvnVerificationController.php:24
 * @route '/identity/bvn'
 */
bvn.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: bvn.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ModificationController::ninModification
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin-modification'
 */
export const ninModification = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ninModification.url(options),
    method: 'get',
})

ninModification.definition = {
    methods: ["get","head"],
    url: '/identity/nin-modification',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ModificationController::ninModification
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin-modification'
 */
ninModification.url = (options?: RouteQueryOptions) => {
    return ninModification.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ModificationController::ninModification
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin-modification'
 */
ninModification.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ninModification.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ModificationController::ninModification
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin-modification'
 */
ninModification.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ninModification.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ModificationController::bvnModification
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn-modification'
 */
export const bvnModification = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bvnModification.url(options),
    method: 'get',
})

bvnModification.definition = {
    methods: ["get","head"],
    url: '/identity/bvn-modification',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ModificationController::bvnModification
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn-modification'
 */
bvnModification.url = (options?: RouteQueryOptions) => {
    return bvnModification.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ModificationController::bvnModification
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn-modification'
 */
bvnModification.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bvnModification.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ModificationController::bvnModification
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn-modification'
 */
bvnModification.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: bvnModification.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ModificationController::modification
 * @see app/Http/Controllers/ModificationController.php:42
 * @route '/identity/modification'
 */
export const modification = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: modification.url(options),
    method: 'get',
})

modification.definition = {
    methods: ["get","head"],
    url: '/identity/modification',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ModificationController::modification
 * @see app/Http/Controllers/ModificationController.php:42
 * @route '/identity/modification'
 */
modification.url = (options?: RouteQueryOptions) => {
    return modification.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ModificationController::modification
 * @see app/Http/Controllers/ModificationController.php:42
 * @route '/identity/modification'
 */
modification.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: modification.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ModificationController::modification
 * @see app/Http/Controllers/ModificationController.php:42
 * @route '/identity/modification'
 */
modification.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: modification.url(options),
    method: 'head',
})
const identity = {
    nin: Object.assign(nin, ninBd9d09),
bvn: Object.assign(bvn, bvnC51c8d),
ninModification: Object.assign(ninModification, ninModification),
bvnModification: Object.assign(bvnModification, bvnModification),
modification: Object.assign(modification, modification88c75b),
verifications: Object.assign(verifications, verifications),
}

export default identity