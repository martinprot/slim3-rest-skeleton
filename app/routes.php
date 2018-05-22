<?php

use App\Controllers\TestController;
use App\Controllers\BaseController;
use App\Controllers\OAuth2TokenController;


// Middlewares

$middlewares = require __DIR__.'/middleware.php';

// Routes
$app->get('/test/authenticated', TestController::class.':needsToBeAuth')->add($middlewares["apiAuth"]);
$app->get('/test/loggued', TestController::class.':needsToBeLoggued')->add($middlewares["userAuth"]);

// oAuth2
$app->group('/oauth', function () {
    $this->post('/token', OAuth2TokenController::class.':token');
});

// Books controller
$app->group('/books', function () {
    $this->get   ('',             BaseController::class.':getAll');
    $this->get   ('/{id:[0-9]+}', BaseController::class.':get');
    $this->post  ('',             BaseController::class.':add');
    $this->put   ('/{id:[0-9]+}', BaseController::class.':update');
    $this->delete('/{id:[0-9]+}', BaseController::class.':delete');
//})->add(function ($request, $response, $next) {
//	$this->settings['localtable'] = "categories";
//    $response = $next($request, $response);
//    return $response;
});

// Custom Controllers
//$app->group('/mycustom', function () {
//    $this->get   ('',             MyCustomController::class.':getAll');
//    $this->post
//    ...
//});
