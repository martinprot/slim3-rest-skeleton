<?php

use App\Utils\OAuth2Manager as OAuth2Manager;
use App\Utils\ResponseSerializer as ResponseSerializer;
use App\DataAccess\UserAccess;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// Application middleware

$middlewares = [];

$middlewares["apiAuth"] = function (Request $request, Response $response, $next) {
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

$middlewares["userAuth"] = function (Request $request, Response $response, $next) {
	$oauth = new OAuth2Manager($this->oAuth, $request);
	if ($oauth->isAuthenticated()) {
		if ($oauth->isLoggued()) {
			$userAccess = new UserAccess($this->logger, $this->pdo);
			$user = $userAccess->getUser($oauth->getUserId());
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

return $middlewares;