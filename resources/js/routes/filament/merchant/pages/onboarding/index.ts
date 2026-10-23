import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Pages\OnboardingPlan::__invoke
 * @see app/Filament/Merchant/Pages/OnboardingPlan.php:7
 * @route '//merchant.localhost/{tenant}/onboarding/plan'
 */
export const plan = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: plan.url(args, options),
    method: 'get',
})

plan.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/{tenant}/onboarding/plan',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\OnboardingPlan::__invoke
 * @see app/Filament/Merchant/Pages/OnboardingPlan.php:7
 * @route '//merchant.localhost/{tenant}/onboarding/plan'
 */
plan.url = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions) => {
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

    return plan.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\OnboardingPlan::__invoke
 * @see app/Filament/Merchant/Pages/OnboardingPlan.php:7
 * @route '//merchant.localhost/{tenant}/onboarding/plan'
 */
plan.get = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: plan.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\OnboardingPlan::__invoke
 * @see app/Filament/Merchant/Pages/OnboardingPlan.php:7
 * @route '//merchant.localhost/{tenant}/onboarding/plan'
 */
plan.head = (args: { tenant: string | number | { public_id: string | number } } | [tenant: string | number | { public_id: string | number } ] | string | number | { public_id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: plan.url(args, options),
    method: 'head',
})
const onboarding = {
    plan: Object.assign(plan, plan),
}

export default onboarding