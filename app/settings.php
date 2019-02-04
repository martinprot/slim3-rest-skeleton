<?php

/// CONSTANTS ///
require __DIR__.'db.php';
// // db.php shouldn't be stored into version control. Please create it with thoses variables defined:
// define('SERVER','localhost');
// define('USER','your-database-user');
// define('PASS','y0urP4ssw0rd');
// define('DATABASE','your-db-name');

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
