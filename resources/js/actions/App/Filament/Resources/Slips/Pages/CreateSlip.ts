import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Slips\Pages\CreateSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/CreateSlip.php:7
 * @route '//admin.localhost/slips/create'
 */
const CreateSlip = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateSlip.url(options),
    method: 'get',
})

CreateSlip.definition = {
    methods: ["get","head"],
    url: '//admin.localhost/slips/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Slips\Pages\CreateSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/CreateSlip.php:7
 * @route '//admin.localhost/slips/create'
 */
CreateSlip.url = (options?: RouteQueryOptions) => {
    return CreateSlip.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Slips\Pages\CreateSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/CreateSlip.php:7
 * @route '//admin.localhost/slips/create'
 */
CreateSlip.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateSlip.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Slips\Pages\CreateSlip::__invoke
 * @see app/Filament/Resources/Slips/Pages/CreateSlip.php:7
 * @route '//admin.localhost/slips/create'
 */
CreateSlip.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateSlip.url(options),
    method: 'head',
})
export default CreateSlip