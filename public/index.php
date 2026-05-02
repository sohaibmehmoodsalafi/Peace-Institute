<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
*/
if (file_exists($maintenance = __DIR__.'/../peace_institute/storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
| path: /home/USERNAME/peace_institute/vendor/autoload.php
| public_html => public folder ka content
| peace_institute => baaki sab Laravel files
|--------------------------------------------------------------------------
*/
require __DIR__.'/../peace_institute/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
*/
$app = require_once __DIR__.'/../peace_institute/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
