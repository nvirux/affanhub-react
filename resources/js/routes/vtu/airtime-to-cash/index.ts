import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\AirtimeCashController::submit
 * @see app/Http/Controllers/AirtimeCashController.php:109
 * @route '/vtu/airtime-to-cash/submit'
 */
export const submit = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: submit.url(options),
    method: 'post',
})

submit.definition = {
    methods: ["post"],
    url: '/vtu/airtime-to-cash/submit',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AirtimeCashController::submit
 * @see app/Http/Controllers/AirtimeCashController.php:109
 * @route '/vtu/airtime-to-cash/submit'
 */
submit.url = (options?: RouteQueryOptions) => {
    return submit.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AirtimeCashController::submit
 * @see app/Http/Controllers/AirtimeCashController.php:109
 * @route '/vtu/airtime-to-cash/submit'
 */
submit.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: submit.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AirtimeCashController::submit
 * @see app/Http/Controllers/AirtimeCashController.php:109
 * @route '/vtu/airtime-to-cash/submit'
 */
    const submitForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: submit.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AirtimeCashController::submit
 * @see app/Http/Controllers/AirtimeCashController.php:109
 * @route '/vtu/airtime-to-cash/submit'
 */
        submitForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: submit.url(options),
            method: 'post',
        })
    
    submit.form = submitForm
const airtimeToCash = {
    submit: Object.assign(submit, submit),
}

export default airtimeToCash