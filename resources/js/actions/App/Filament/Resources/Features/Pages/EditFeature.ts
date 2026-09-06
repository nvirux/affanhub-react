import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Features\Pages\EditFeature::__invoke
 * @see app/Filament/Resources/Features/Pages/EditFeature.php:7
 * @route '//admin.localhost/features/{record}/edit'
 */
const EditFeature = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditFeature.url(args, options),
    method: 'get',
})

EditFeature.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/features/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Features\Pages\EditFeature::__invoke
 * @see app/Filament/Resources/Features/Pages/EditFeature.php:7
 * @route '//admin.localhost/features/{record}/edit'
 */
EditFeature.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditFeature.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\Features\Pages\EditFeature::__invoke
 * @see app/Filament/Resources/Features/Pages/EditFeature.php:7
 * @route '//admin.localhost/features/{record}/edit'
 */
EditFeature.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditFeature.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Features\Pages\EditFeature::__invoke
 * @see app/Filament/Resources/Features/Pages/EditFeature.php:7
 * @route '//admin.localhost/features/{record}/edit'
 */
EditFeature.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditFeature.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Features\Pages\EditFeature::__invoke
 * @see app/Filament/Resources/Features/Pages/EditFeature.php:7
 * @route '//admin.localhost/features/{record}/edit'
 */
    const EditFeatureForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditFeature.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Features\Pages\EditFeature::__invoke
 * @see app/Filament/Resources/Features/Pages/EditFeature.php:7
 * @route '//admin.localhost/features/{record}/edit'
 */
        EditFeatureForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditFeature.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Features\Pages\EditFeature::__invoke
 * @see app/Filament/Resources/Features/Pages/EditFeature.php:7
 * @route '//admin.localhost/features/{record}/edit'
 */
        EditFeatureForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditFeature.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditFeature.form = EditFeatureForm
export default EditFeature