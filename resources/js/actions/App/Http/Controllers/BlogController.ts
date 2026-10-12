import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//127.0.0.1/blog'
 */
const indexd8fda94521323d8a01a4bd15021e966c = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexd8fda94521323d8a01a4bd15021e966c.url(options),
    method: 'get',
})

indexd8fda94521323d8a01a4bd15021e966c.definition = {
    methods: ["get","head"],
    url: '//127.0.0.1/blog',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//127.0.0.1/blog'
 */
indexd8fda94521323d8a01a4bd15021e966c.url = (options?: RouteQueryOptions) => {
    return indexd8fda94521323d8a01a4bd15021e966c.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//127.0.0.1/blog'
 */
indexd8fda94521323d8a01a4bd15021e966c.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexd8fda94521323d8a01a4bd15021e966c.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//127.0.0.1/blog'
 */
indexd8fda94521323d8a01a4bd15021e966c.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexd8fda94521323d8a01a4bd15021e966c.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//127.0.0.1/blog'
 */
    const indexd8fda94521323d8a01a4bd15021e966cForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: indexd8fda94521323d8a01a4bd15021e966c.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//127.0.0.1/blog'
 */
        indexd8fda94521323d8a01a4bd15021e966cForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexd8fda94521323d8a01a4bd15021e966c.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//127.0.0.1/blog'
 */
        indexd8fda94521323d8a01a4bd15021e966cForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexd8fda94521323d8a01a4bd15021e966c.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    indexd8fda94521323d8a01a4bd15021e966c.form = indexd8fda94521323d8a01a4bd15021e966cForm
    /**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//localhost/blog'
 */
const indexbe1bd784ce737379898c476d2b2521ef = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexbe1bd784ce737379898c476d2b2521ef.url(options),
    method: 'get',
})

indexbe1bd784ce737379898c476d2b2521ef.definition = {
    methods: ["get","head"],
    url: '//localhost/blog',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//localhost/blog'
 */
indexbe1bd784ce737379898c476d2b2521ef.url = (options?: RouteQueryOptions) => {
    return indexbe1bd784ce737379898c476d2b2521ef.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//localhost/blog'
 */
indexbe1bd784ce737379898c476d2b2521ef.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexbe1bd784ce737379898c476d2b2521ef.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//localhost/blog'
 */
indexbe1bd784ce737379898c476d2b2521ef.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexbe1bd784ce737379898c476d2b2521ef.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//localhost/blog'
 */
    const indexbe1bd784ce737379898c476d2b2521efForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: indexbe1bd784ce737379898c476d2b2521ef.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//localhost/blog'
 */
        indexbe1bd784ce737379898c476d2b2521efForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexbe1bd784ce737379898c476d2b2521ef.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//localhost/blog'
 */
        indexbe1bd784ce737379898c476d2b2521efForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexbe1bd784ce737379898c476d2b2521ef.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    indexbe1bd784ce737379898c476d2b2521ef.form = indexbe1bd784ce737379898c476d2b2521efForm
    /**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//merchant.localhost/blog'
 */
const indexed82d1ee74c87a638b338ed7a8ad2c0d = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexed82d1ee74c87a638b338ed7a8ad2c0d.url(options),
    method: 'get',
})

indexed82d1ee74c87a638b338ed7a8ad2c0d.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/blog',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//merchant.localhost/blog'
 */
indexed82d1ee74c87a638b338ed7a8ad2c0d.url = (options?: RouteQueryOptions) => {
    return indexed82d1ee74c87a638b338ed7a8ad2c0d.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//merchant.localhost/blog'
 */
indexed82d1ee74c87a638b338ed7a8ad2c0d.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexed82d1ee74c87a638b338ed7a8ad2c0d.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//merchant.localhost/blog'
 */
indexed82d1ee74c87a638b338ed7a8ad2c0d.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexed82d1ee74c87a638b338ed7a8ad2c0d.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//merchant.localhost/blog'
 */
    const indexed82d1ee74c87a638b338ed7a8ad2c0dForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: indexed82d1ee74c87a638b338ed7a8ad2c0d.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//merchant.localhost/blog'
 */
        indexed82d1ee74c87a638b338ed7a8ad2c0dForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexed82d1ee74c87a638b338ed7a8ad2c0d.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//merchant.localhost/blog'
 */
        indexed82d1ee74c87a638b338ed7a8ad2c0dForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexed82d1ee74c87a638b338ed7a8ad2c0d.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    indexed82d1ee74c87a638b338ed7a8ad2c0d.form = indexed82d1ee74c87a638b338ed7a8ad2c0dForm
    /**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//admin.localhost/blog'
 */
const index1406aa77d966ffdc43a911891b0304df = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index1406aa77d966ffdc43a911891b0304df.url(options),
    method: 'get',
})

index1406aa77d966ffdc43a911891b0304df.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/blog',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//admin.localhost/blog'
 */
index1406aa77d966ffdc43a911891b0304df.url = (options?: RouteQueryOptions) => {
    return index1406aa77d966ffdc43a911891b0304df.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//admin.localhost/blog'
 */
index1406aa77d966ffdc43a911891b0304df.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index1406aa77d966ffdc43a911891b0304df.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//admin.localhost/blog'
 */
index1406aa77d966ffdc43a911891b0304df.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index1406aa77d966ffdc43a911891b0304df.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//admin.localhost/blog'
 */
    const index1406aa77d966ffdc43a911891b0304dfForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index1406aa77d966ffdc43a911891b0304df.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//admin.localhost/blog'
 */
        index1406aa77d966ffdc43a911891b0304dfForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index1406aa77d966ffdc43a911891b0304df.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\BlogController::index
 * @see app/Http/Controllers/BlogController.php:15
 * @route '//admin.localhost/blog'
 */
        index1406aa77d966ffdc43a911891b0304dfForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index1406aa77d966ffdc43a911891b0304df.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index1406aa77d966ffdc43a911891b0304df.form = index1406aa77d966ffdc43a911891b0304dfForm

/**
* Multiple routes resolve to \App\Http\Controllers\BlogController::index, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `index['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const index = {
    '//127.0.0.1/blog': indexd8fda94521323d8a01a4bd15021e966c,
    '//localhost/blog': indexbe1bd784ce737379898c476d2b2521ef,
    '//merchant.localhost/blog': indexed82d1ee74c87a638b338ed7a8ad2c0d,
    '//admin.localhost/blog': index1406aa77d966ffdc43a911891b0304df,
}

/**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//127.0.0.1/blog/{slug}'
 */
const show84cca4f12fe9a9f6886377f9f0e3ad1e = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show84cca4f12fe9a9f6886377f9f0e3ad1e.url(args, options),
    method: 'get',
})

show84cca4f12fe9a9f6886377f9f0e3ad1e.definition = {
    methods: ["get","head"],
    url: '//127.0.0.1/blog/{slug}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//127.0.0.1/blog/{slug}'
 */
show84cca4f12fe9a9f6886377f9f0e3ad1e.url = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { slug: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    slug: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        slug: args.slug,
                }

    return show84cca4f12fe9a9f6886377f9f0e3ad1e.definition.url
            .replace('{slug}', parsedArgs.slug.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//127.0.0.1/blog/{slug}'
 */
show84cca4f12fe9a9f6886377f9f0e3ad1e.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show84cca4f12fe9a9f6886377f9f0e3ad1e.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//127.0.0.1/blog/{slug}'
 */
show84cca4f12fe9a9f6886377f9f0e3ad1e.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show84cca4f12fe9a9f6886377f9f0e3ad1e.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//127.0.0.1/blog/{slug}'
 */
    const show84cca4f12fe9a9f6886377f9f0e3ad1eForm = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show84cca4f12fe9a9f6886377f9f0e3ad1e.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//127.0.0.1/blog/{slug}'
 */
        show84cca4f12fe9a9f6886377f9f0e3ad1eForm.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show84cca4f12fe9a9f6886377f9f0e3ad1e.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//127.0.0.1/blog/{slug}'
 */
        show84cca4f12fe9a9f6886377f9f0e3ad1eForm.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show84cca4f12fe9a9f6886377f9f0e3ad1e.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show84cca4f12fe9a9f6886377f9f0e3ad1e.form = show84cca4f12fe9a9f6886377f9f0e3ad1eForm
    /**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//localhost/blog/{slug}'
 */
const show0b71d6ab1a3afecaa8db4d117e7f4fb1 = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show0b71d6ab1a3afecaa8db4d117e7f4fb1.url(args, options),
    method: 'get',
})

show0b71d6ab1a3afecaa8db4d117e7f4fb1.definition = {
    methods: ["get","head"],
    url: '//localhost/blog/{slug}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//localhost/blog/{slug}'
 */
show0b71d6ab1a3afecaa8db4d117e7f4fb1.url = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { slug: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    slug: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        slug: args.slug,
                }

    return show0b71d6ab1a3afecaa8db4d117e7f4fb1.definition.url
            .replace('{slug}', parsedArgs.slug.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//localhost/blog/{slug}'
 */
show0b71d6ab1a3afecaa8db4d117e7f4fb1.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show0b71d6ab1a3afecaa8db4d117e7f4fb1.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//localhost/blog/{slug}'
 */
show0b71d6ab1a3afecaa8db4d117e7f4fb1.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show0b71d6ab1a3afecaa8db4d117e7f4fb1.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//localhost/blog/{slug}'
 */
    const show0b71d6ab1a3afecaa8db4d117e7f4fb1Form = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show0b71d6ab1a3afecaa8db4d117e7f4fb1.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//localhost/blog/{slug}'
 */
        show0b71d6ab1a3afecaa8db4d117e7f4fb1Form.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show0b71d6ab1a3afecaa8db4d117e7f4fb1.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//localhost/blog/{slug}'
 */
        show0b71d6ab1a3afecaa8db4d117e7f4fb1Form.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show0b71d6ab1a3afecaa8db4d117e7f4fb1.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show0b71d6ab1a3afecaa8db4d117e7f4fb1.form = show0b71d6ab1a3afecaa8db4d117e7f4fb1Form
    /**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//merchant.localhost/blog/{slug}'
 */
const showae58c91ef7a91d63869d250076cf2031 = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showae58c91ef7a91d63869d250076cf2031.url(args, options),
    method: 'get',
})

showae58c91ef7a91d63869d250076cf2031.definition = {
    methods: ["get","head"],
    url: '//merchant.localhost/blog/{slug}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//merchant.localhost/blog/{slug}'
 */
showae58c91ef7a91d63869d250076cf2031.url = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { slug: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    slug: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        slug: args.slug,
                }

    return showae58c91ef7a91d63869d250076cf2031.definition.url
            .replace('{slug}', parsedArgs.slug.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//merchant.localhost/blog/{slug}'
 */
showae58c91ef7a91d63869d250076cf2031.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showae58c91ef7a91d63869d250076cf2031.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//merchant.localhost/blog/{slug}'
 */
showae58c91ef7a91d63869d250076cf2031.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: showae58c91ef7a91d63869d250076cf2031.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//merchant.localhost/blog/{slug}'
 */
    const showae58c91ef7a91d63869d250076cf2031Form = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: showae58c91ef7a91d63869d250076cf2031.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//merchant.localhost/blog/{slug}'
 */
        showae58c91ef7a91d63869d250076cf2031Form.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: showae58c91ef7a91d63869d250076cf2031.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//merchant.localhost/blog/{slug}'
 */
        showae58c91ef7a91d63869d250076cf2031Form.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: showae58c91ef7a91d63869d250076cf2031.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    showae58c91ef7a91d63869d250076cf2031.form = showae58c91ef7a91d63869d250076cf2031Form
    /**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//admin.localhost/blog/{slug}'
 */
const show09a1c9edb9c9cb166c3f84e77beb57b4 = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show09a1c9edb9c9cb166c3f84e77beb57b4.url(args, options),
    method: 'get',
})

show09a1c9edb9c9cb166c3f84e77beb57b4.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/blog/{slug}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//admin.localhost/blog/{slug}'
 */
show09a1c9edb9c9cb166c3f84e77beb57b4.url = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { slug: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    slug: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        slug: args.slug,
                }

    return show09a1c9edb9c9cb166c3f84e77beb57b4.definition.url
            .replace('{slug}', parsedArgs.slug.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//admin.localhost/blog/{slug}'
 */
show09a1c9edb9c9cb166c3f84e77beb57b4.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show09a1c9edb9c9cb166c3f84e77beb57b4.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//admin.localhost/blog/{slug}'
 */
show09a1c9edb9c9cb166c3f84e77beb57b4.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show09a1c9edb9c9cb166c3f84e77beb57b4.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//admin.localhost/blog/{slug}'
 */
    const show09a1c9edb9c9cb166c3f84e77beb57b4Form = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show09a1c9edb9c9cb166c3f84e77beb57b4.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//admin.localhost/blog/{slug}'
 */
        show09a1c9edb9c9cb166c3f84e77beb57b4Form.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show09a1c9edb9c9cb166c3f84e77beb57b4.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\BlogController::show
 * @see app/Http/Controllers/BlogController.php:72
 * @route '//admin.localhost/blog/{slug}'
 */
        show09a1c9edb9c9cb166c3f84e77beb57b4Form.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show09a1c9edb9c9cb166c3f84e77beb57b4.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show09a1c9edb9c9cb166c3f84e77beb57b4.form = show09a1c9edb9c9cb166c3f84e77beb57b4Form

/**
* Multiple routes resolve to \App\Http\Controllers\BlogController::show, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `show['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
export const show = {
    '//127.0.0.1/blog/{slug}': show84cca4f12fe9a9f6886377f9f0e3ad1e,
    '//localhost/blog/{slug}': show0b71d6ab1a3afecaa8db4d117e7f4fb1,
    '//merchant.localhost/blog/{slug}': showae58c91ef7a91d63869d250076cf2031,
    '//admin.localhost/blog/{slug}': show09a1c9edb9c9cb166c3f84e77beb57b4,
}

const BlogController = { index, show }

export default BlogController