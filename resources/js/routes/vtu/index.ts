import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import airtimeCd3da4 from './airtime'
import dataC044be from './data'
import airtimeToCash77cdf2 from './airtime-to-cash'
/**
* @see \App\Http\Controllers\AirtimeController::airtime
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
export const airtime = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: airtime.url(options),
    method: 'get',
})

airtime.definition = {
    methods: ["get","head"],
    url: '/vtu/airtime',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AirtimeController::airtime
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
airtime.url = (options?: RouteQueryOptions) => {
    return airtime.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AirtimeController::airtime
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
airtime.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: airtime.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AirtimeController::airtime
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
airtime.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: airtime.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AirtimeController::airtime
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
    const airtimeForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: airtime.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AirtimeController::airtime
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
        airtimeForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: airtime.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AirtimeController::airtime
 * @see app/Http/Controllers/AirtimeController.php:23
 * @route '/vtu/airtime'
 */
        airtimeForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: airtime.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    airtime.form = airtimeForm
/**
* @see \App\Http\Controllers\DataController::data
 * @see app/Http/Controllers/DataController.php:22
 * @route '/vtu/data'
 */
export const data = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: data.url(options),
    method: 'get',
})

data.definition = {
    methods: ["get","head"],
    url: '/vtu/data',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DataController::data
 * @see app/Http/Controllers/DataController.php:22
 * @route '/vtu/data'
 */
data.url = (options?: RouteQueryOptions) => {
    return data.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DataController::data
 * @see app/Http/Controllers/DataController.php:22
 * @route '/vtu/data'
 */
data.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: data.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DataController::data
 * @see app/Http/Controllers/DataController.php:22
 * @route '/vtu/data'
 */
data.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: data.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DataController::data
 * @see app/Http/Controllers/DataController.php:22
 * @route '/vtu/data'
 */
    const dataForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: data.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DataController::data
 * @see app/Http/Controllers/DataController.php:22
 * @route '/vtu/data'
 */
        dataForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: data.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DataController::data
 * @see app/Http/Controllers/DataController.php:22
 * @route '/vtu/data'
 */
        dataForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: data.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    data.form = dataForm
/**
* @see \App\Http\Controllers\AirtimeCashController::airtimeToCash
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
export const airtimeToCash = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: airtimeToCash.url(options),
    method: 'get',
})

airtimeToCash.definition = {
    methods: ["get","head"],
    url: '/vtu/airtime-to-cash',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AirtimeCashController::airtimeToCash
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
airtimeToCash.url = (options?: RouteQueryOptions) => {
    return airtimeToCash.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AirtimeCashController::airtimeToCash
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
airtimeToCash.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: airtimeToCash.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AirtimeCashController::airtimeToCash
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
airtimeToCash.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: airtimeToCash.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AirtimeCashController::airtimeToCash
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
    const airtimeToCashForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: airtimeToCash.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AirtimeCashController::airtimeToCash
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
        airtimeToCashForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: airtimeToCash.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AirtimeCashController::airtimeToCash
 * @see app/Http/Controllers/AirtimeCashController.php:20
 * @route '/vtu/airtime-to-cash'
 */
        airtimeToCashForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: airtimeToCash.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    airtimeToCash.form = airtimeToCashForm
const vtu = {
    airtime: Object.assign(airtime, airtimeCd3da4),
data: Object.assign(data, dataC044be),
airtimeToCash: Object.assign(airtimeToCash, airtimeToCash77cdf2),
}

export default vtu