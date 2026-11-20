import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import ninBd9d09 from './nin'
import bvnC51c8d from './bvn'
import modification88c75b from './modification'
import verifications from './verifications'
import cac from './cac'
import cacServices from './cac-services'
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
* @see \App\Http\Controllers\NinVerificationController::nin
 * @see app/Http/Controllers/NinVerificationController.php:24
 * @route '/identity/nin'
 */
    const ninForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: nin.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\NinVerificationController::nin
 * @see app/Http/Controllers/NinVerificationController.php:24
 * @route '/identity/nin'
 */
        ninForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: nin.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\NinVerificationController::nin
 * @see app/Http/Controllers/NinVerificationController.php:24
 * @route '/identity/nin'
 */
        ninForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: nin.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    nin.form = ninForm
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
* @see \App\Http\Controllers\BvnVerificationController::bvn
 * @see app/Http/Controllers/BvnVerificationController.php:24
 * @route '/identity/bvn'
 */
    const bvnForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: bvn.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\BvnVerificationController::bvn
 * @see app/Http/Controllers/BvnVerificationController.php:24
 * @route '/identity/bvn'
 */
        bvnForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bvn.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\BvnVerificationController::bvn
 * @see app/Http/Controllers/BvnVerificationController.php:24
 * @route '/identity/bvn'
 */
        bvnForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bvn.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    bvn.form = bvnForm
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
* @see \App\Http\Controllers\ModificationController::ninModification
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin-modification'
 */
    const ninModificationForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ninModification.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ModificationController::ninModification
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin-modification'
 */
        ninModificationForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ninModification.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ModificationController::ninModification
 * @see app/Http/Controllers/ModificationController.php:26
 * @route '/identity/nin-modification'
 */
        ninModificationForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ninModification.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ninModification.form = ninModificationForm
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
* @see \App\Http\Controllers\ModificationController::bvnModification
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn-modification'
 */
    const bvnModificationForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: bvnModification.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ModificationController::bvnModification
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn-modification'
 */
        bvnModificationForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bvnModification.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ModificationController::bvnModification
 * @see app/Http/Controllers/ModificationController.php:34
 * @route '/identity/bvn-modification'
 */
        bvnModificationForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bvnModification.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    bvnModification.form = bvnModificationForm
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

    /**
* @see \App\Http\Controllers\ModificationController::modification
 * @see app/Http/Controllers/ModificationController.php:42
 * @route '/identity/modification'
 */
    const modificationForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: modification.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ModificationController::modification
 * @see app/Http/Controllers/ModificationController.php:42
 * @route '/identity/modification'
 */
        modificationForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: modification.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ModificationController::modification
 * @see app/Http/Controllers/ModificationController.php:42
 * @route '/identity/modification'
 */
        modificationForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: modification.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    modification.form = modificationForm
/**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin-validation'
 */
export const ninValidation = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ninValidation.url(options),
    method: 'get',
})

ninValidation.definition = {
    methods: ["get","head"],
    url: '/identity/nin-validation',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin-validation'
 */
ninValidation.url = (options?: RouteQueryOptions) => {
    return ninValidation.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin-validation'
 */
ninValidation.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ninValidation.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin-validation'
 */
ninValidation.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ninValidation.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin-validation'
 */
    const ninValidationForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ninValidation.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin-validation'
 */
        ninValidationForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ninValidation.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::ninValidation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/nin-validation'
 */
        ninValidationForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ninValidation.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ninValidation.form = ninValidationForm
/**
* @see \App\Http\Controllers\ServicesController::validation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/validation'
 */
export const validation = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: validation.url(options),
    method: 'get',
})

validation.definition = {
    methods: ["get","head"],
    url: '/identity/validation',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::validation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/validation'
 */
validation.url = (options?: RouteQueryOptions) => {
    return validation.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::validation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/validation'
 */
validation.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: validation.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::validation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/validation'
 */
validation.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: validation.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::validation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/validation'
 */
    const validationForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: validation.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::validation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/validation'
 */
        validationForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: validation.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::validation
 * @see app/Http/Controllers/ServicesController.php:110
 * @route '/identity/validation'
 */
        validationForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: validation.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    validation.form = validationForm
/**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe-clearance'
 */
export const ipeClearance = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ipeClearance.url(options),
    method: 'get',
})

ipeClearance.definition = {
    methods: ["get","head"],
    url: '/identity/ipe-clearance',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe-clearance'
 */
ipeClearance.url = (options?: RouteQueryOptions) => {
    return ipeClearance.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe-clearance'
 */
ipeClearance.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ipeClearance.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe-clearance'
 */
ipeClearance.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ipeClearance.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe-clearance'
 */
    const ipeClearanceForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ipeClearance.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe-clearance'
 */
        ipeClearanceForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ipeClearance.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::ipeClearance
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe-clearance'
 */
        ipeClearanceForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ipeClearance.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ipeClearance.form = ipeClearanceForm
/**
* @see \App\Http\Controllers\ServicesController::ipe
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe'
 */
export const ipe = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ipe.url(options),
    method: 'get',
})

ipe.definition = {
    methods: ["get","head"],
    url: '/identity/ipe',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::ipe
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe'
 */
ipe.url = (options?: RouteQueryOptions) => {
    return ipe.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::ipe
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe'
 */
ipe.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ipe.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ServicesController::ipe
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe'
 */
ipe.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ipe.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ServicesController::ipe
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe'
 */
    const ipeForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ipe.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ServicesController::ipe
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe'
 */
        ipeForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ipe.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ServicesController::ipe
 * @see app/Http/Controllers/ServicesController.php:115
 * @route '/identity/ipe'
 */
        ipeForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ipe.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ipe.form = ipeForm
const identity = {
    nin: Object.assign(nin, ninBd9d09),
bvn: Object.assign(bvn, bvnC51c8d),
ninModification: Object.assign(ninModification, ninModification),
bvnModification: Object.assign(bvnModification, bvnModification),
modification: Object.assign(modification, modification88c75b),
verifications: Object.assign(verifications, verifications),
ninValidation: Object.assign(ninValidation, ninValidation),
validation: Object.assign(validation, validation),
ipeClearance: Object.assign(ipeClearance, ipeClearance),
ipe: Object.assign(ipe, ipe),
cac: Object.assign(cac, cac),
cacServices: Object.assign(cacServices, cacServices),
}

export default identity