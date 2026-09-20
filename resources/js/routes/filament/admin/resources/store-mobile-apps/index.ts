import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/store-mobile-apps',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/ListStoreMobileApps.php:7
 * @route '//admin.localhost/store-mobile-apps'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\CreateStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/CreateStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/store-mobile-apps/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\CreateStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/CreateStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\CreateStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/CreateStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\CreateStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/CreateStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\CreateStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/CreateStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\CreateStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/CreateStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\CreateStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/CreateStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/create'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\EditStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/EditStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/{record}/edit'
 */
export const edit = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/store-mobile-apps/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\EditStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/EditStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/{record}/edit'
 */
edit.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\EditStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/EditStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/{record}/edit'
 */
edit.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\StoreMobileApps\Pages\EditStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/EditStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/{record}/edit'
 */
edit.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\EditStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/EditStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/{record}/edit'
 */
    const editForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\EditStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/EditStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/{record}/edit'
 */
        editForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\StoreMobileApps\Pages\EditStoreMobileApp::__invoke
 * @see app/Filament/Resources/StoreMobileApps/Pages/EditStoreMobileApp.php:7
 * @route '//admin.localhost/store-mobile-apps/{record}/edit'
 */
        editForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
const storeMobileApps = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
edit: Object.assign(edit, edit),
}

export default storeMobileApps