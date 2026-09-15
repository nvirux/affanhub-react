<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Class Namespace
    |--------------------------------------------------------------------------
    |
    | This value sets the root class namespace for Livewire component classes in
    | your application. This value will change where component auto-discovery
    | finds components.
    |
    */

    'class_namespace' => 'App\\Livewire',

    /*
    |--------------------------------------------------------------------------
    | View Path
    |--------------------------------------------------------------------------
    |
    | This value is the path where Livewire component views are stored.
    |
    */

    'view_path' => resource_path('views/livewire'),

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    | The default layout view that will be used when rendering a component via
    | Route::get('/path/to/component', App\Livewire\Component::class);
    |
    */

    'layout' => 'components.layouts.app',

    /*
    |--------------------------------------------------------------------------
    | Lazy Loading Placeholder
    |--------------------------------------------------------------------------
    | Livewire allows components to be lazy loaded when they are not in the
    | initial viewport. This placeholder will be shown until component renders.
    |
    */

    'lazy_placeholder' => null,

    /*
    |--------------------------------------------------------------------------
    | Temporary File Uploads
    |--------------------------------------------------------------------------
    |
    | Livewire handles file uploads by storing uploads in a temporary directory
    | before the file is validated and stored permanently. All file uploads
    | are guaranteed to be temporary and are garbage collected automatically.
    |
    | Using the 'local' disk ensures that temporary chunk uploads and image
    | previews function smoothly even when the default filesystem is S3 or R2.
    |
    */

    'temporary_file_upload' => [
        'disk' => 'local',
        'rules' => ['file', 'max:15360'], // 15MB max temp upload
        'directory' => 'livewire-tmp',
        'middleware' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 5,
        'cleanup' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Render On Redirect
    |--------------------------------------------------------------------------
    |
    | This value determines if Livewire will render a component's view when
    | the component has redirected the user to a new URL.
    |
    */

    'render_on_redirect' => false,

    /*
    |--------------------------------------------------------------------------
    | Back Button Cache
    |--------------------------------------------------------------------------
    |
    | This value determines if Livewire will cache pages to improve back button
    | navigation for full-page Livewire components.
    |
    */

    'back_button_cache' => false,

    /*
    |--------------------------------------------------------------------------
    | Invalidate Cache
    |--------------------------------------------------------------------------
    |
    | This value determines if Livewire will invalidate the browser's cache for
    | Livewire assets on every deploy.
    |
    */

    'invalidate_cache' => true,

];
