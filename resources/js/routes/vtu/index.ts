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
/**
* @see \App\Http\Controllers\ServicesController::cable
 * @see app/Http/Controllers/ServicesController.php:95
 * @route '/vtu/cable'
 */
export const cable = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: cable.url(options),
    method: 'get',
})

cable.definition = {
    methods: ["get","head"],
    url: '/vtu/cable',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::cable
 * @see app/Http/Controllers/ServicesController.php:95
 * @route '/vtu/cable'
 */
cable.url = (options?: RouteQueryOptions) => {
    return cable.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::cable
 * @see app/Http/Controllers/ServicesController.php:95
 * @route '/vtu/cable'
 */
cable.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: cable.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::cable
 * @see app/Http/Controllers/ServicesController.php:95
 * @route '/vtu/cable'
 */
cable.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: cable.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::cable
 * @see app/Http/Controllers/ServicesController.php:95
 * @route '/vtu/cable'
 */
    const cableForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: cable.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::cable
 * @see app/Http/Controllers/ServicesController.php:95
 * @route '/vtu/cable'
 */
        cableForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: cable.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::cable
 * @see app/Http/Controllers/ServicesController.php:95
 * @route '/vtu/cable'
 */
        cableForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: cable.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    cable.form = cableForm
/**
* @see \App\Http\Controllers\ServicesController::electricity
 * @see app/Http/Controllers/ServicesController.php:100
 * @route '/vtu/electricity'
 */
export const electricity = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: electricity.url(options),
    method: 'get',
})

electricity.definition = {
    methods: ["get","head"],
    url: '/vtu/electricity',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::electricity
 * @see app/Http/Controllers/ServicesController.php:100
 * @route '/vtu/electricity'
 */
electricity.url = (options?: RouteQueryOptions) => {
    return electricity.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::electricity
 * @see app/Http/Controllers/ServicesController.php:100
 * @route '/vtu/electricity'
 */
electricity.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: electricity.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::electricity
 * @see app/Http/Controllers/ServicesController.php:100
 * @route '/vtu/electricity'
 */
electricity.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: electricity.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::electricity
 * @see app/Http/Controllers/ServicesController.php:100
 * @route '/vtu/electricity'
 */
    const electricityForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: electricity.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::electricity
 * @see app/Http/Controllers/ServicesController.php:100
 * @route '/vtu/electricity'
 */
        electricityForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: electricity.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::electricity
 * @see app/Http/Controllers/ServicesController.php:100
 * @route '/vtu/electricity'
 */
        electricityForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: electricity.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    electricity.form = electricityForm
/**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/education'
 */
export const education = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: education.url(options),
    method: 'get',
})

education.definition = {
    methods: ["get","head"],
    url: '/vtu/education',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/education'
 */
education.url = (options?: RouteQueryOptions) => {
    return education.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/education'
 */
education.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: education.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/education'
 */
education.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: education.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/education'
 */
    const educationForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: education.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/education'
 */
        educationForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: education.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::education
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/education'
 */
        educationForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: education.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    education.form = educationForm
/**
* @see \App\Http\Controllers\ServicesController::examPins
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam-pins'
 */
export const examPins = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: examPins.url(options),
    method: 'get',
})

examPins.definition = {
    methods: ["get","head"],
    url: '/vtu/exam-pins',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::examPins
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam-pins'
 */
examPins.url = (options?: RouteQueryOptions) => {
    return examPins.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::examPins
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam-pins'
 */
examPins.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: examPins.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::examPins
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam-pins'
 */
examPins.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: examPins.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::examPins
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam-pins'
 */
    const examPinsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: examPins.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::examPins
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam-pins'
 */
        examPinsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: examPins.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::examPins
 * @see app/Http/Controllers/ServicesController.php:105
 * @route '/vtu/exam-pins'
 */
        examPinsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: examPins.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    examPins.form = examPinsForm
/**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk-sms'
 */
export const bulkSms = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bulkSms.url(options),
    method: 'get',
})

bulkSms.definition = {
    methods: ["get","head"],
    url: '/vtu/bulk-sms',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk-sms'
 */
bulkSms.url = (options?: RouteQueryOptions) => {
    return bulkSms.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk-sms'
 */
bulkSms.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bulkSms.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk-sms'
 */
bulkSms.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: bulkSms.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk-sms'
 */
    const bulkSmsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: bulkSms.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk-sms'
 */
        bulkSmsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bulkSms.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::bulkSms
 * @see app/Http/Controllers/ServicesController.php:90
 * @route '/vtu/bulk-sms'
 */
        bulkSmsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bulkSms.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    bulkSms.form = bulkSmsForm
const vtu = {
    airtime: Object.assign(airtime, airtimeCd3da4),
data: Object.assign(data, dataC044be),
airtimeToCash: Object.assign(airtimeToCash, airtimeToCash77cdf2),
cable: Object.assign(cable, cable),
electricity: Object.assign(electricity, electricity),
education: Object.assign(education, education),
examPins: Object.assign(examPins, examPins),
bulkSms: Object.assign(bulkSms, bulkSms),
}

export default vtu