<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 15/08/18
 * Time: 15:10
 */
return [
	'sulradio' => [
		'driver' => 'mysql',
		'url' => env('DATABASE_URL'),
		'host' => env('SULRADIO_DB_HOST', '127.0.0.1'),
		'port' => env('SULRADIO_DB_PORT', '3306'),
		'database' => env('SULRADIO_DB_DATABASE', 'sulradio'),
		'username' => env('SULRADIO_DB_USERNAME', 'root'),
		'password' => env('SULRADIO_DB_PASSWORD', ''),
		'unix_socket' => env('SULRADIO_DB_SOCKET', ''),
		'charset' => 'utf8mb4',
		'collation' => 'utf8mb4_unicode_ci',
		'prefix' => '',
		'prefix_indexes' => true,
		'strict' => true,
		'engine' => null,
		'options' => extension_loaded('pdo_mysql') ? array_filter([
			PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
		]) : [],
        'dump' => [
            'use_single_transaction',
            'skip_lock_tables',
        ],
	],
	'sulradio_mongo' => [
		'driver' => 'mongodb',
		'host' => env('SULRADIO_MONGO_DB_HOST', '127.0.0.1'),
		'port' => env('SULRADIO_MONGO_DB_PORT', 27017),
		'database' => env('SULRADIO_MONGO_DB_NAME', 'sulradio'),
		'username' => env('SULRADIO_MONGO_DB_USERNAME', ''),
		'password' => env('SULRADIO_MONGO_DB_PASSWORD', ''),
		'charset' => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix' => '',
		'strict' => false,
		'options' => [
			'database' => env('SULRADIO_MONGO_DB_NAME', 'sulradio') 
		],
        'dump' =>[
            'mongodb_user_auth'=>'admin'
        ]
	]
];
