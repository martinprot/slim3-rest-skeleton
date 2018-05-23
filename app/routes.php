<?php

use App\Controllers\TestController;
use App\Controllers\GenericController;
use App\Controllers\UserController;
use App\Controllers\OAuth2TokenController;

// ************
// Middlewares
// ************

$middlewares = require __DIR__.'/middleware.php';
$apiAuth = $middlewares["apiAuth"];
$userAuth = $middlewares["userAuth"];

// ************
// Routes
// ************

// Test
$app->get('/test/authenticated', TestController::class.':needsToBeAuth')->add($apiAuth);
$app->get('/test/loggued', TestController::class.':needsToBeLoggued')->add($userAuth);

// OAuth2
$app->post('/oauth/token', OAuth2TokenController::class.':token');

// User controller
$app->get('/user', UserController::class.':getAll')->add($apiAuth);
$app->get('/user/{id:[0-9]+}', UserController::class.':get')->add($apiAuth);
$app->post('/user', UserController::class.':add')->add($apiAuth);
$app->put('/user/{id:[0-9]+}', UserController::class.':update')->add($userAuth);
$app->delete('/user/{id:[0-9]+}', UserController::class.':delete')->add($userAuth);

// $app->group('/books', function () {
//     $this->get   ('',             GenericController::class.':getAll');
//     $this->get   ('/{id:[0-9]+}', GenericController::class.':get');
//     $this->post  ('',             GenericController::class.':add');
//     $this->put   ('/{id:[0-9]+}', GenericController::class.':update');
//     $this->delete('/{id:[0-9]+}', GenericController::class.':delete');
//})->add(function ($request, $response, $next) {
//	$this->settings['localtable'] = "categories";
//    $response = $next($request, $response);
//    return $response;
//});

// Custom Controllers
//$app->group('/mycustom', function () {
//    $this->get   ('',             MyCustomController::class.':getAll');
//    $this->post
//    ...
//});
