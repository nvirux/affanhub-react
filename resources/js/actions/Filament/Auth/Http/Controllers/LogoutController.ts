import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//admin.localhost/logout'
 */
const LogoutController1d3811bdf67f5223ca089c7adacf23be = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: LogoutController1d3811bdf67f5223ca089c7adacf23be.url(options),
    method: 'post',
})

LogoutController1d3811bdf67f5223ca089c7adacf23be.definition = {
    methods: ["post"],
    url: '//admin.localhost/logout',
} satisfies RouteDefinition<["post"]>

/**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//admin.localhost/logout'
 */
LogoutController1d3811bdf67f5223ca089c7adacf23be.url = (options?: RouteQueryOptions) => {
    return LogoutController1d3811bdf67f5223ca089c7adacf23be.definition.url + queryParams(options)
}

/**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//admin.localhost/logout'
 */
LogoutController1d3811bdf67f5223ca089c7adacf23be.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: LogoutController1d3811bdf67f5223ca089c7adacf23be.url(options),
    method: 'post',
})

    /**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//admin.localhost/logout'
 */
    const LogoutController1d3811bdf67f5223ca089c7adacf23beForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: LogoutController1d3811bdf67f5223ca089c7adacf23be.url(options),
        method: 'post',
    })

            /**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//admin.localhost/logout'
 */
        LogoutController1d3811bdf67f5223ca089c7adacf23beForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: LogoutController1d3811bdf67f5223ca089c7adacf23be.url(options),
            method: 'post',
        })
    
    LogoutController1d3811bdf67f5223ca089c7adacf23be.form = LogoutController1d3811bdf67f5223ca089c7adacf23beForm
    /**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//merchant.localhost/logout'
 */
const LogoutControllerac59bdb999df2ea2be6ec88628af509b = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: LogoutControllerac59bdb999df2ea2be6ec88628af509b.url(options),
    method: 'post',
})

LogoutControllerac59bdb999df2ea2be6ec88628af509b.definition = {
    methods: ["post"],
    url: '//merchant.localhost/logout',
} satisfies RouteDefinition<["post"]>

/**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//merchant.localhost/logout'
 */
LogoutControllerac59bdb999df2ea2be6ec88628af509b.url = (options?: RouteQueryOptions) => {
    return LogoutControllerac59bdb999df2ea2be6ec88628af509b.definition.url + queryParams(options)
}

/**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//merchant.localhost/logout'
 */
LogoutControllerac59bdb999df2ea2be6ec88628af509b.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: LogoutControllerac59bdb999df2ea2be6ec88628af509b.url(options),
    method: 'post',
})

    /**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//merchant.localhost/logout'
 */
    const LogoutControllerac59bdb999df2ea2be6ec88628af509bForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: LogoutControllerac59bdb999df2ea2be6ec88628af509b.url(options),
        method: 'post',
    })

            /**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
 * @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
 * @route '//merchant.localhost/logout'
 */
        LogoutControllerac59bdb999df2ea2be6ec88628af509bForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: LogoutControllerac59bdb999df2ea2be6ec88628af509b.url(options),
            method: 'post',
        })
    
    LogoutControllerac59bdb999df2ea2be6ec88628af509b.form = LogoutControllerac59bdb999df2ea2be6ec88628af509bForm

/**
* Multiple routes resolve to \Filament\Auth\Http\Controllers\LogoutController::LogoutController, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `LogoutController['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
const LogoutController = {
    '//admin.localhost/logout': LogoutController1d3811bdf67f5223ca089c7adacf23be,
    '//merchant.localhost/logout': LogoutControllerac59bdb999df2ea2be6ec88628af509b,
}

export default LogoutController