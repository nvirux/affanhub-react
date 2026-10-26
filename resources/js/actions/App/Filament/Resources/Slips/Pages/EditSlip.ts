import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Slips\Pages\EditSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/EditSlip.php:7
 * @route '//admin.localhost/slips/{record}/edit'
 */
const EditSlip = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditSlip.url(args, options),
    method: 'get',
})

EditSlip.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/slips/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Slips\Pages\EditSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/EditSlip.php:7
 * @route '//admin.localhost/slips/{record}/edit'
 */
EditSlip.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { record: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    record: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        record: args.record,
                }

    return EditSlip.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\Slips\Pages\EditSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/EditSlip.php:7
 * @route '//admin.localhost/slips/{record}/edit'
 */
EditSlip.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditSlip.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Slips\Pages\EditSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/EditSlip.php:7
 * @route '//admin.localhost/slips/{record}/edit'
 */
EditSlip.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditSlip.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Slips\Pages\EditSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/EditSlip.php:7
 * @route '//admin.localhost/slips/{record}/edit'
 */
    const EditSlipForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditSlip.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Slips\Pages\EditSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/EditSlip.php:7
 * @route '//admin.localhost/slips/{record}/edit'
 */
        EditSlipForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditSlip.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Slips\Pages\EditSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/EditSlip.php:7
 * @route '//admin.localhost/slips/{record}/edit'
 */
        EditSlipForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditSlip.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditSlip.form = EditSlipForm
export default EditSlip