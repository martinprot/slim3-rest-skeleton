<?php

/// CONSTANTS ///
define('SERVER','localhost');
define('USER','woopsmaster');
define('PASS','7jiwKJeP');
define('DATABASE','woops');

return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,

        // database settings
        'db' => [
            'host' => SERVER,
            'user' => USER,
			'pass' => PASS,
			'dbname' => DATABASE
        ],

        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__.'/../log/app.log',
        ],
    ],
];
