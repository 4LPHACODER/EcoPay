<?php

return [
    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel default path within resources/views will already be
    | registered for you.
    |
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the storage
    | directory. However, as usual, you are free to change this value.
    |
    | On Vercel serverless, the filesystem is read-only except for /tmp.
    | Use VIEW_COMPILED_PATH env var to point to a writable location.
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        // Use system temp dir by default on serverless environments where storage/framework/views is read-only.
        sys_get_temp_dir().'/views'
    ),
];
