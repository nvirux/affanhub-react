import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
import onboarding from './onboarding'
/**
* @see \App\Filament\Merchant\Pages\Billing::__invoke
 * @see app/Filament/Merchant/Pages/Billing.php:7
 * @route '//merchant.localhost/{tenant}/billing'
 */
export const billing = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: billing.url(args, options),
    method: 'get',
})

billing.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/billing',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\Billing::__invoke
 * @see app/Filament/Merchant/Pages/Billing.php:7
 * @route '//merchant.localhost/{tenant}/billing'
 */
billing.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'public_id' in args) {
            args = { tenant: args.public_id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: typeof args.tenant === 'object'
                ? args.tenant.public_id
                : args.tenant,
                }

    return billing.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\Billing::__invoke
 * @see app/Filament/Merchant/Pages/Billing.php:7
 * @route '//merchant.localhost/{tenant}/billing'
 */
billing.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: billing.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\Billing::__invoke
 * @see app/Filament/Merchant/Pages/Billing.php:7
 * @route '//merchant.localhost/{tenant}/billing'
 */
billing.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: billing.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\Billing::__invoke
 * @see app/Filament/Merchant/Pages/Billing.php:7
 * @route '//merchant.localhost/{tenant}/billing'
 */
    const billingForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: billing.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\Billing::__invoke
 * @see app/Filament/Merchant/Pages/Billing.php:7
 * @route '//merchant.localhost/{tenant}/billing'
 */
        billingForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: billing.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\Billing::__invoke
 * @see app/Filament/Merchant/Pages/Billing.php:7
 * @route '//merchant.localhost/{tenant}/billing'
 */
        billingForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: billing.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    billing.form = billingForm
/**
* @see \App\Filament\Merchant\Pages\Domains::__invoke
 * @see app/Filament/Merchant/Pages/Domains.php:7
 * @route '//merchant.localhost/{tenant}/domains'
 */
export const domains = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: domains.url(args, options),
    method: 'get',
})

domains.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/domains',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\Domains::__invoke
 * @see app/Filament/Merchant/Pages/Domains.php:7
 * @route '//merchant.localhost/{tenant}/domains'
 */
domains.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'public_id' in args) {
            args = { tenant: args.public_id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: typeof args.tenant === 'object'
                ? args.tenant.public_id
                : args.tenant,
                }

    return domains.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\Domains::__invoke
 * @see app/Filament/Merchant/Pages/Domains.php:7
 * @route '//merchant.localhost/{tenant}/domains'
 */
domains.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: domains.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\Domains::__invoke
 * @see app/Filament/Merchant/Pages/Domains.php:7
 * @route '//merchant.localhost/{tenant}/domains'
 */
domains.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: domains.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\Domains::__invoke
 * @see app/Filament/Merchant/Pages/Domains.php:7
 * @route '//merchant.localhost/{tenant}/domains'
 */
    const domainsForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: domains.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\Domains::__invoke
 * @see app/Filament/Merchant/Pages/Domains.php:7
 * @route '//merchant.localhost/{tenant}/domains'
 */
        domainsForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: domains.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\Domains::__invoke
 * @see app/Filament/Merchant/Pages/Domains.php:7
 * @route '//merchant.localhost/{tenant}/domains'
 */
        domainsForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: domains.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    domains.form = domainsForm
/**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
export const manageServices = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: manageServices.url(args, options),
    method: 'get',
})

manageServices.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/manage-services',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
manageServices.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'public_id' in args) {
            args = { tenant: args.public_id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: typeof args.tenant === 'object'
                ? args.tenant.public_id
                : args.tenant,
                }

    return manageServices.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
manageServices.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: manageServices.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
manageServices.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: manageServices.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
    const manageServicesForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: manageServices.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
        manageServicesForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: manageServices.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\ManageServices::__invoke
 * @see app/Filament/Merchant/Pages/ManageServices.php:7
 * @route '//merchant.localhost/{tenant}/manage-services'
 */
        manageServicesForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: manageServices.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    manageServices.form = manageServicesForm
/**
* @see \App\Filament\Merchant\Pages\ReferralProgram::__invoke
 * @see app/Filament/Merchant/Pages/ReferralProgram.php:7
 * @route '//merchant.localhost/{tenant}/referral-program'
 */
export const referralProgram = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: referralProgram.url(args, options),
    method: 'get',
})

referralProgram.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/referral-program',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\ReferralProgram::__invoke
 * @see app/Filament/Merchant/Pages/ReferralProgram.php:7
 * @route '//merchant.localhost/{tenant}/referral-program'
 */
referralProgram.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'public_id' in args) {
            args = { tenant: args.public_id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: typeof args.tenant === 'object'
                ? args.tenant.public_id
                : args.tenant,
                }

    return referralProgram.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\ReferralProgram::__invoke
 * @see app/Filament/Merchant/Pages/ReferralProgram.php:7
 * @route '//merchant.localhost/{tenant}/referral-program'
 */
referralProgram.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: referralProgram.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\ReferralProgram::__invoke
 * @see app/Filament/Merchant/Pages/ReferralProgram.php:7
 * @route '//merchant.localhost/{tenant}/referral-program'
 */
referralProgram.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: referralProgram.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\ReferralProgram::__invoke
 * @see app/Filament/Merchant/Pages/ReferralProgram.php:7
 * @route '//merchant.localhost/{tenant}/referral-program'
 */
    const referralProgramForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: referralProgram.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\ReferralProgram::__invoke
 * @see app/Filament/Merchant/Pages/ReferralProgram.php:7
 * @route '//merchant.localhost/{tenant}/referral-program'
 */
        referralProgramForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: referralProgram.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\ReferralProgram::__invoke
 * @see app/Filament/Merchant/Pages/ReferralProgram.php:7
 * @route '//merchant.localhost/{tenant}/referral-program'
 */
        referralProgramForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: referralProgram.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    referralProgram.form = referralProgramForm
/**
* @see \App\Filament\Merchant\Pages\StoreActivityLogs::__invoke
 * @see app/Filament/Merchant/Pages/StoreActivityLogs.php:7
 * @route '//merchant.localhost/{tenant}/store-activity-logs'
 */
export const storeActivityLogs = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: storeActivityLogs.url(args, options),
    method: 'get',
})

storeActivityLogs.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/store-activity-logs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\StoreActivityLogs::__invoke
 * @see app/Filament/Merchant/Pages/StoreActivityLogs.php:7
 * @route '//merchant.localhost/{tenant}/store-activity-logs'
 */
storeActivityLogs.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'public_id' in args) {
            args = { tenant: args.public_id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: typeof args.tenant === 'object'
                ? args.tenant.public_id
                : args.tenant,
                }

    return storeActivityLogs.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\StoreActivityLogs::__invoke
 * @see app/Filament/Merchant/Pages/StoreActivityLogs.php:7
 * @route '//merchant.localhost/{tenant}/store-activity-logs'
 */
storeActivityLogs.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: storeActivityLogs.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\StoreActivityLogs::__invoke
 * @see app/Filament/Merchant/Pages/StoreActivityLogs.php:7
 * @route '//merchant.localhost/{tenant}/store-activity-logs'
 */
storeActivityLogs.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: storeActivityLogs.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\StoreActivityLogs::__invoke
 * @see app/Filament/Merchant/Pages/StoreActivityLogs.php:7
 * @route '//merchant.localhost/{tenant}/store-activity-logs'
 */
    const storeActivityLogsForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: storeActivityLogs.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\StoreActivityLogs::__invoke
 * @see app/Filament/Merchant/Pages/StoreActivityLogs.php:7
 * @route '//merchant.localhost/{tenant}/store-activity-logs'
 */
        storeActivityLogsForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: storeActivityLogs.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\StoreActivityLogs::__invoke
 * @see app/Filament/Merchant/Pages/StoreActivityLogs.php:7
 * @route '//merchant.localhost/{tenant}/store-activity-logs'
 */
        storeActivityLogsForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: storeActivityLogs.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    storeActivityLogs.form = storeActivityLogsForm
/**
* @see \App\Filament\Merchant\Pages\StoreSettings::__invoke
 * @see app/Filament/Merchant/Pages/StoreSettings.php:7
 * @route '//merchant.localhost/{tenant}/store-settings'
 */
export const storeSettings = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: storeSettings.url(args, options),
    method: 'get',
})

storeSettings.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/store-settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\StoreSettings::__invoke
 * @see app/Filament/Merchant/Pages/StoreSettings.php:7
 * @route '//merchant.localhost/{tenant}/store-settings'
 */
storeSettings.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'public_id' in args) {
            args = { tenant: args.public_id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: typeof args.tenant === 'object'
                ? args.tenant.public_id
                : args.tenant,
                }

    return storeSettings.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\StoreSettings::__invoke
 * @see app/Filament/Merchant/Pages/StoreSettings.php:7
 * @route '//merchant.localhost/{tenant}/store-settings'
 */
storeSettings.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: storeSettings.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\StoreSettings::__invoke
 * @see app/Filament/Merchant/Pages/StoreSettings.php:7
 * @route '//merchant.localhost/{tenant}/store-settings'
 */
storeSettings.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: storeSettings.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\StoreSettings::__invoke
 * @see app/Filament/Merchant/Pages/StoreSettings.php:7
 * @route '//merchant.localhost/{tenant}/store-settings'
 */
    const storeSettingsForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: storeSettings.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\StoreSettings::__invoke
 * @see app/Filament/Merchant/Pages/StoreSettings.php:7
 * @route '//merchant.localhost/{tenant}/store-settings'
 */
        storeSettingsForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: storeSettings.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\StoreSettings::__invoke
 * @see app/Filament/Merchant/Pages/StoreSettings.php:7
 * @route '//merchant.localhost/{tenant}/store-settings'
 */
        storeSettingsForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: storeSettings.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    storeSettings.form = storeSettingsForm
/**
* @see \App\Filament\Merchant\Pages\StoreWallet::__invoke
 * @see app/Filament/Merchant/Pages/StoreWallet.php:7
 * @route '//merchant.localhost/{tenant}/store-wallet'
 */
export const storeWallet = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: storeWallet.url(args, options),
    method: 'get',
})

storeWallet.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/store-wallet',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\StoreWallet::__invoke
 * @see app/Filament/Merchant/Pages/StoreWallet.php:7
 * @route '//merchant.localhost/{tenant}/store-wallet'
 */
storeWallet.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'public_id' in args) {
            args = { tenant: args.public_id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: typeof args.tenant === 'object'
                ? args.tenant.public_id
                : args.tenant,
                }

    return storeWallet.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\StoreWallet::__invoke
 * @see app/Filament/Merchant/Pages/StoreWallet.php:7
 * @route '//merchant.localhost/{tenant}/store-wallet'
 */
storeWallet.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: storeWallet.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\StoreWallet::__invoke
 * @see app/Filament/Merchant/Pages/StoreWallet.php:7
 * @route '//merchant.localhost/{tenant}/store-wallet'
 */
storeWallet.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: storeWallet.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\StoreWallet::__invoke
 * @see app/Filament/Merchant/Pages/StoreWallet.php:7
 * @route '//merchant.localhost/{tenant}/store-wallet'
 */
    const storeWalletForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: storeWallet.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\StoreWallet::__invoke
 * @see app/Filament/Merchant/Pages/StoreWallet.php:7
 * @route '//merchant.localhost/{tenant}/store-wallet'
 */
        storeWalletForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: storeWallet.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\StoreWallet::__invoke
 * @see app/Filament/Merchant/Pages/StoreWallet.php:7
 * @route '//merchant.localhost/{tenant}/store-wallet'
 */
        storeWalletForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: storeWallet.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    storeWallet.form = storeWalletForm
/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
export const dashboard = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(args, options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
dashboard.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'public_id' in args) {
            args = { tenant: args.public_id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    tenant: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tenant: typeof args.tenant === 'object'
                ? args.tenant.public_id
                : args.tenant,
                }

    return dashboard.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
dashboard.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(args, options),
    method: 'get',
})
/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
dashboard.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(args, options),
    method: 'head',
})

    /**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
    const dashboardForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: dashboard.url(args, options),
        method: 'get',
    })

            /**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
        dashboardForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url(args, options),
            method: 'get',
        })
            /**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '//merchant.localhost/{tenant}'
 */
        dashboardForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    dashboard.form = dashboardForm
const pages = {
    billing: Object.assign(billing, billing),
domains: Object.assign(domains, domains),
manageServices: Object.assign(manageServices, manageServices),
onboarding: Object.assign(onboarding, onboarding),
referralProgram: Object.assign(referralProgram, referralProgram),
storeActivityLogs: Object.assign(storeActivityLogs, storeActivityLogs),
storeSettings: Object.assign(storeSettings, storeSettings),
storeWallet: Object.assign(storeWallet, storeWallet),
dashboard: Object.assign(dashboard, dashboard),
}

export default pages