import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Merchant\Pages\Auth\EmailVerificationPrompt::__invoke
 * @see app/Filament/Merchant/Pages/Auth/EmailVerificationPrompt.php:7
 * @route '//merchant.localhost/email-verification/prompt'
 */
const EmailVerificationPrompt = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EmailVerificationPrompt.url(options),
    method: 'get',
})

EmailVerificationPrompt.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/email-verification/prompt',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Merchant\Pages\Auth\EmailVerificationPrompt::__invoke
 * @see app/Filament/Merchant/Pages/Auth/EmailVerificationPrompt.php:7
 * @route '//merchant.localhost/email-verification/prompt'
 */
EmailVerificationPrompt.url = (options?: RouteQueryOptions) => {
    return EmailVerificationPrompt.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Merchant\Pages\Auth\EmailVerificationPrompt::__invoke
 * @see app/Filament/Merchant/Pages/Auth/EmailVerificationPrompt.php:7
 * @route '//merchant.localhost/email-verification/prompt'
 */
EmailVerificationPrompt.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EmailVerificationPrompt.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Merchant\Pages\Auth\EmailVerificationPrompt::__invoke
 * @see app/Filament/Merchant/Pages/Auth/EmailVerificationPrompt.php:7
 * @route '//merchant.localhost/email-verification/prompt'
 */
EmailVerificationPrompt.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EmailVerificationPrompt.url(options),
    method: 'head',
})
export default EmailVerificationPrompt