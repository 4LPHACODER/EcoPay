<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Ensure compiled view directory is writable on serverless environments (Vercel uses read-only FS).
// Allow overriding with VIEW_COMPILED_PATH in env; otherwise use system temp.
$compiledPath = getenv('VIEW_COMPILED_PATH') ?: (sys_get_temp_dir().'/views');

if (! is_dir($compiledPath)) {
    // create recursively and set permissive mode for serverless runtime
    mkdir($compiledPath, 0755, true);
}

// Make sure Laravel's config('view.compiled') can read this at runtime via env()
putenv('VIEW_COMPILED_PATH='.$compiledPath);

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
