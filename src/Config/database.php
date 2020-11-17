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
		'username' => env('SULRADIO_DB_USERNAME', 'homestead'),
		'password' => env('SULRADIO_DB_PASSWORD', 'secret'),
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
	],
];
