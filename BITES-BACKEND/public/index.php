<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
*/

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        //
    })
    ->withExceptions(function ($exceptions) {
        //
    })
    ->create();

/*
|--------------------------------------------------------------------------
| Handle The Request
|--------------------------------------------------------------------------
*/

$request = Request::capture();
$response = $app->handle($request);
$response->send();

$app->terminate($request, $response);