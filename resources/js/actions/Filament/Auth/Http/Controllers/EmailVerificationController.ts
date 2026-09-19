import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \Filament\Auth\Http\Controllers\EmailVerificationController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/EmailVerificationController.php:10
 * @route '//merchant.localhost/email-verification/verify/{id}/{hash}'
 */
const EmailVerificationController = (args: { id: string | number, hash: string | number } | [id: string | number, hash: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EmailVerificationController.url(args, options),
    method: 'get',
})

EmailVerificationController.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/email-verification/verify/{id}/{hash}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Auth\Http\Controllers\EmailVerificationController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/EmailVerificationController.php:10
 * @route '//merchant.localhost/email-verification/verify/{id}/{hash}'
 */
EmailVerificationController.url = (args: { id: string | number, hash: string | number } | [id: string | number, hash: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    id: args[0],
                    hash: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        id: args.id,
                                hash: args.hash,
                }

    return EmailVerificationController.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace('{hash}', parsedArgs.hash.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Filament\Auth\Http\Controllers\EmailVerificationController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/EmailVerificationController.php:10
 * @route '//merchant.localhost/email-verification/verify/{id}/{hash}'
 */
EmailVerificationController.get = (args: { id: string | number, hash: string | number } | [id: string | number, hash: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EmailVerificationController.url(args, options),
    method: 'get',
})
/**
* @see \Filament\Auth\Http\Controllers\EmailVerificationController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/EmailVerificationController.php:10
 * @route '//merchant.localhost/email-verification/verify/{id}/{hash}'
 */
EmailVerificationController.head = (args: { id: string | number, hash: string | number } | [id: string | number, hash: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EmailVerificationController.url(args, options),
    method: 'head',
})
export default EmailVerificationController