<?php

use App\Controllers\TestController;
use App\Controllers\UserController;
use App\Controllers\OAuth2TokenController;

// ************
// Middlewares
// ************

$middlewares = require __DIR__.'/middleware.php';
$apiAuth = $middlewares["apiAuth"];		// anonymous login
$userAuth = $middlewares["userAuth"];	// user login

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
