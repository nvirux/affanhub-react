import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Domains\Pages\EditDomain::__invoke
 * @see app/Filament/Resources/Domains/Pages/EditDomain.php:7
 * @route '//admin.localhost/domains/{record}/edit'
 */
const EditDomain = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditDomain.url(args, options),
    method: 'get',
})

EditDomain.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/domains/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Domains\Pages\EditDomain::__invoke
 * @see app/Filament/Resources/Domains/Pages/EditDomain.php:7
 * @route '//admin.localhost/domains/{record}/edit'
 */
EditDomain.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditDomain.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\Domains\Pages\EditDomain::__invoke
 * @see app/Filament/Resources/Domains/Pages/EditDomain.php:7
 * @route '//admin.localhost/domains/{record}/edit'
 */
EditDomain.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditDomain.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Domains\Pages\EditDomain::__invoke
 * @see app/Filament/Resources/Domains/Pages/EditDomain.php:7
 * @route '//admin.localhost/domains/{record}/edit'
 */
EditDomain.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditDomain.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Domains\Pages\EditDomain::__invoke
 * @see app/Filament/Resources/Domains/Pages/EditDomain.php:7
 * @route '//admin.localhost/domains/{record}/edit'
 */
    const EditDomainForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditDomain.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Domains\Pages\EditDomain::__invoke
 * @see app/Filament/Resources/Domains/Pages/EditDomain.php:7
 * @route '//admin.localhost/domains/{record}/edit'
 */
        EditDomainForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditDomain.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Domains\Pages\EditDomain::__invoke
 * @see app/Filament/Resources/Domains/Pages/EditDomain.php:7
 * @route '//admin.localhost/domains/{record}/edit'
 */
        EditDomainForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditDomain.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditDomain.form = EditDomainForm
export default EditDomain