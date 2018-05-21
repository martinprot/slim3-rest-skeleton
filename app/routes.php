<?php

use App\Controllers\TestController;
use App\Controllers\BaseController;
use App\Controllers\OAuth2TokenController;
use App\Utils\OAuth2Manager as OAuth2Manager;
use App\Utils\ResponseSerializer as ResponseSerializer;
use App\DataAccess\DataAccess;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// Middlewares

$apiAuth = function (Request $request, Response $response, $next) {
	$oauth = new OAuth2Manager($this->oAuth, $request);
	if ($oauth->isAuthenticated()) {
		$this->logger->info("[OAuth2] authentication suceeded");
		return $next($request, $response);
	}
	else {
		$this->logger->info("[OAuth2] authentication failed!");
		$error = $this->oAuth->getResponse();
		$serializer = new ResponseSerializer($response);
		return $serializer->error(401, $error->getParameters());
	}
};

$userAuth = function (Request $request, Response $response, $next) {
	$oauth = new OAuth2Manager($this->oAuth, $request);
	if ($oauth->isAuthenticated()) {
		if ($oauth->isLoggued()) {
			$userId = $oauth->getUserId();
			$dataAccess = new DataAccess($this->logger, $this->pdo);
			$user = $dataAccess->get("user", ["id" => $userId]);
			$request = $request->withAttribute('user', $user);
			$this->logger->info("[OAuth2] authentication suceeded, user: " . $user);
			return $next($request, $response);
		}
		else {
			$this->logger->info("[OAuth2] user needs to be loggued");
			$error = $this->oAuth->getResponse();
			$serializer = new ResponseSerializer($response);
			return $serializer->error(403, "You must be loggued to access this resource");
		}
	}
	else {
		$this->logger->info("[OAuth2] authentication failed!");
		$error = $this->oAuth->getResponse();
		$serializer = new ResponseSerializer($response);
		return $serializer->error(401, $error->getParameters());
	}
};


// Routes
$app->get('/test/authenticated', TestController::class.':needsToBeAuth')->add($apiAuth);
$app->get('/test/loggued', TestController::class.':needsToBeLoggued')->add($userAuth);
// $app->group('/test', function () {
// 	$this->get('/authenticated', 'TestController:needsToBeAuth')->add($apiAuth);
// 	$this->get('/loggued', 'TestController:needsToBeLoggued')->add($userAuth);
// });

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
