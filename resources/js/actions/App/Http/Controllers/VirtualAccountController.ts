import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\VirtualAccountController::generate
 * @see app/Http/Controllers/VirtualAccountController.php:22
 * @route '/virtual-account/generate'
 */
export const generate = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: generate.url(options),
    method: 'post',
})

generate.definition = {
    methods: ["post"],
    url: '/virtual-account/generate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\VirtualAccountController::generate
 * @see app/Http/Controllers/VirtualAccountController.php:22
 * @route '/virtual-account/generate'
 */
generate.url = (options?: RouteQueryOptions) => {
    return generate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VirtualAccountController::generate
 * @see app/Http/Controllers/VirtualAccountController.php:22
 * @route '/virtual-account/generate'
 */
generate.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: generate.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\VirtualAccountController::generate
 * @see app/Http/Controllers/VirtualAccountController.php:22
 * @route '/virtual-account/generate'
 */
    const generateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: generate.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\VirtualAccountController::generate
 * @see app/Http/Controllers/VirtualAccountController.php:22
 * @route '/virtual-account/generate'
 */
        generateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: generate.url(options),
            method: 'post',
        })
    
    generate.form = generateForm
const VirtualAccountController = { generate }

export default VirtualAccountController