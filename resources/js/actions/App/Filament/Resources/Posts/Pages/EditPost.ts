import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Posts\Pages\EditPost::__invoke
 * @see app/Filament/Resources/Posts/Pages/EditPost.php:7
 * @route '//admin.localhost/posts/{record}/edit'
 */
const EditPost = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditPost.url(args, options),
    method: 'get',
})

EditPost.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/posts/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Posts\Pages\EditPost::__invoke
 * @see app/Filament/Resources/Posts/Pages/EditPost.php:7
 * @route '//admin.localhost/posts/{record}/edit'
 */
EditPost.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditPost.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\Posts\Pages\EditPost::__invoke
 * @see app/Filament/Resources/Posts/Pages/EditPost.php:7
 * @route '//admin.localhost/posts/{record}/edit'
 */
EditPost.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditPost.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Posts\Pages\EditPost::__invoke
 * @see app/Filament/Resources/Posts/Pages/EditPost.php:7
 * @route '//admin.localhost/posts/{record}/edit'
 */
EditPost.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditPost.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Posts\Pages\EditPost::__invoke
 * @see app/Filament/Resources/Posts/Pages/EditPost.php:7
 * @route '//admin.localhost/posts/{record}/edit'
 */
    const EditPostForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditPost.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Posts\Pages\EditPost::__invoke
 * @see app/Filament/Resources/Posts/Pages/EditPost.php:7
 * @route '//admin.localhost/posts/{record}/edit'
 */
        EditPostForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditPost.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Posts\Pages\EditPost::__invoke
 * @see app/Filament/Resources/Posts/Pages/EditPost.php:7
 * @route '//admin.localhost/posts/{record}/edit'
 */
        EditPostForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditPost.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditPost.form = EditPostForm
export default EditPost