import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::show
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:20
 * @route '/invitations/{token}'
 */
export const show = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/invitations/{token}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::show
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:20
 * @route '/invitations/{token}'
 */
show.url = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { token: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    token: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        token: args.token,
                }

    return show.definition.url
            .replace('{token}', parsedArgs.token.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::show
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:20
 * @route '/invitations/{token}'
 */
show.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::show
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:20
 * @route '/invitations/{token}'
 */
show.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::show
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:20
 * @route '/invitations/{token}'
 */
    const showForm = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::show
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:20
 * @route '/invitations/{token}'
 */
        showForm.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::show
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:20
 * @route '/invitations/{token}'
 */
        showForm.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::acceptExisting
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:46
 * @route '/invitations/{token}/accept'
 */
export const acceptExisting = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: acceptExisting.url(args, options),
    method: 'post',
})

acceptExisting.definition = {
    methods: ["post"],
    url: '/invitations/{token}/accept',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::acceptExisting
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:46
 * @route '/invitations/{token}/accept'
 */
acceptExisting.url = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { token: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    token: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        token: args.token,
                }

    return acceptExisting.definition.url
            .replace('{token}', parsedArgs.token.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::acceptExisting
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:46
 * @route '/invitations/{token}/accept'
 */
acceptExisting.post = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: acceptExisting.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::acceptExisting
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:46
 * @route '/invitations/{token}/accept'
 */
    const acceptExistingForm = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: acceptExisting.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::acceptExisting
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:46
 * @route '/invitations/{token}/accept'
 */
        acceptExistingForm.post = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: acceptExisting.url(args, options),
            method: 'post',
        })
    
    acceptExisting.form = acceptExistingForm
/**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::registerAndAccept
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:100
 * @route '/invitations/{token}/register'
 */
export const registerAndAccept = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: registerAndAccept.url(args, options),
    method: 'post',
})

registerAndAccept.definition = {
    methods: ["post"],
    url: '/invitations/{token}/register',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::registerAndAccept
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:100
 * @route '/invitations/{token}/register'
 */
registerAndAccept.url = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { token: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    token: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        token: args.token,
                }

    return registerAndAccept.definition.url
            .replace('{token}', parsedArgs.token.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::registerAndAccept
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:100
 * @route '/invitations/{token}/register'
 */
registerAndAccept.post = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: registerAndAccept.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::registerAndAccept
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:100
 * @route '/invitations/{token}/register'
 */
    const registerAndAcceptForm = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: registerAndAccept.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Merchant\StaffInvitationController::registerAndAccept
 * @see app/Http/Controllers/Merchant/StaffInvitationController.php:100
 * @route '/invitations/{token}/register'
 */
        registerAndAcceptForm.post = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: registerAndAccept.url(args, options),
            method: 'post',
        })
    
    registerAndAccept.form = registerAndAcceptForm
const StaffInvitationController = { show, acceptExisting, registerAndAccept }

export default StaffInvitationController