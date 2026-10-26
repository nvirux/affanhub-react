import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Pages\HelpFaq::__invoke
 * @see app/Filament/Merchant/Pages/HelpFaq.php:7
 * @route '//merchant.localhost/{tenant}/help-faq'
 */
const HelpFaq = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: HelpFaq.url(args, options),
    method: 'get',
})

HelpFaq.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/help-faq',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\HelpFaq::__invoke
 * @see app/Filament/Merchant/Pages/HelpFaq.php:7
 * @route '//merchant.localhost/{tenant}/help-faq'
 */
HelpFaq.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return HelpFaq.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\HelpFaq::__invoke
 * @see app/Filament/Merchant/Pages/HelpFaq.php:7
 * @route '//merchant.localhost/{tenant}/help-faq'
 */
HelpFaq.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: HelpFaq.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\HelpFaq::__invoke
 * @see app/Filament/Merchant/Pages/HelpFaq.php:7
 * @route '//merchant.localhost/{tenant}/help-faq'
 */
HelpFaq.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: HelpFaq.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Merchant\Pages\HelpFaq::__invoke
 * @see app/Filament/Merchant/Pages/HelpFaq.php:7
 * @route '//merchant.localhost/{tenant}/help-faq'
 */
    const HelpFaqForm = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: HelpFaq.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Merchant\Pages\HelpFaq::__invoke
 * @see app/Filament/Merchant/Pages/HelpFaq.php:7
 * @route '//merchant.localhost/{tenant}/help-faq'
 */
        HelpFaqForm.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: HelpFaq.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Merchant\Pages\HelpFaq::__invoke
 * @see app/Filament/Merchant/Pages/HelpFaq.php:7
 * @route '//merchant.localhost/{tenant}/help-faq'
 */
        HelpFaqForm.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: HelpFaq.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    HelpFaq.form = HelpFaqForm
export default HelpFaq