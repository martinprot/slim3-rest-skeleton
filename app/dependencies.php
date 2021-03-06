<?php

// User
use App\Controllers\TestController;
use App\Controllers\UserController;

use App\DataAccess\OAuth2_CustomStorage;
use App\Controllers\OAuth2TokenController;

// Custom Controllers / DataAccess
//use App\Controllers\MyCustomController;
//use App\DataAccess\MyCustomDataAccess;

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new \Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['logger']['path'], \Monolog\Logger::DEBUG));

    return $logger;
};

// Database
$container['pdo'] = function ($c) {
	$db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    return $pdo;
};

// oAuth
$container['oAuth'] = function ($c) {
	
    $storage = new OAuth2_CustomStorage($c->get('pdo'));

    // Pass a storage object or array of storage objects to the OAuth2 server class
    $server = new OAuth2\Server($storage, [
        'access_lifetime' => 86400 // 24 hours
	]);
    
    // add grant types
	$server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
	$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
	$server->addGrantType(new OAuth2\GrantType\RefreshToken($storage, [
		'refresh_token_lifetime' => 24192000, // 280 days
        'always_issue_new_refresh_token' => true,
		'unset_refresh_token_after_use' => false
	]));
	
    return $server;
};

// Test Controller
$container['App\Controllers\TestController'] = function ($c) {
    return new TestController($c->get('logger'));
};

// User Controller
$container['App\Controllers\UserController'] = function ($c) {
    return new UserController($c->get('logger'), $c->get('pdo'));
};

// oAuth Controller for retrieving tokens
$container['App\Controllers\OAuth2TokenController'] = function ($c) {
    return new OAuth2TokenController($c->get('logger'), $c->get('oAuth'));
};
