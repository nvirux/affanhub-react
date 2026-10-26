import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Posts\Pages\CreatePost::__invoke
 * @see app/Filament/Resources/Posts/Pages/CreatePost.php:7
 * @route '//admin.localhost/posts/create'
 */
const CreatePost = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreatePost.url(options),
    method: 'get',
})

CreatePost.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/posts/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Posts\Pages\CreatePost::__invoke
 * @see app/Filament/Resources/Posts/Pages/CreatePost.php:7
 * @route '//admin.localhost/posts/create'
 */
CreatePost.url = (options?: RouteQueryOptions) => {
    return CreatePost.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Posts\Pages\CreatePost::__invoke
 * @see app/Filament/Resources/Posts/Pages/CreatePost.php:7
 * @route '//admin.localhost/posts/create'
 */
CreatePost.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreatePost.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Posts\Pages\CreatePost::__invoke
 * @see app/Filament/Resources/Posts/Pages/CreatePost.php:7
 * @route '//admin.localhost/posts/create'
 */
CreatePost.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreatePost.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Posts\Pages\CreatePost::__invoke
 * @see app/Filament/Resources/Posts/Pages/CreatePost.php:7
 * @route '//admin.localhost/posts/create'
 */
    const CreatePostForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreatePost.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Posts\Pages\CreatePost::__invoke
 * @see app/Filament/Resources/Posts/Pages/CreatePost.php:7
 * @route '//admin.localhost/posts/create'
 */
        CreatePostForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreatePost.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Posts\Pages\CreatePost::__invoke
 * @see app/Filament/Resources/Posts/Pages/CreatePost.php:7
 * @route '//admin.localhost/posts/create'
 */
        CreatePostForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreatePost.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreatePost.form = CreatePostForm
export default CreatePost