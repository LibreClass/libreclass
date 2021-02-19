<?php

return [
	'default' => env('DB_CONNECTION', 'mysql'),

	'connections' => [

		'mongodb' => [
			'driver'   => 'mongodb',
			'host'     => env('DB_HOST', 'localhost'),
			'port'     => env('DB_PORT', 27017),
			'database' => env('DB_DATABASE', 'esus'),
			'username' => env('DB_USERNAME', ''),
			'password' => env('DB_PASSWORD', ''),
			'options'  => [
			]
		],

		'sqlite' => [
			'driver' => 'sqlite',
			'database' => env('DB_DATABASE', database_path('database.sqlite')),
			'prefix' => '',
			'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
		],

		'mysql' => [
			'driver' => 'mysql',
			'host' => env('DB_HOST', '127.0.0.1'),
			'port' => env('DB_PORT', '3306'),
			'database' => env('DB_DATABASE', 'forge'),
			'username' => env('DB_USERNAME', 'forge'),
			'password' => env('DB_PASSWORD', ''),
			'unix_socket' => env('DB_SOCKET', ''),
			'charset' => 'utf8mb4',
			'collation' => 'utf8mb4_unicode_ci',
			'prefix' => '',
			'prefix_indexes' => true,
			'strict' => true,
			'engine' => null,
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Migration Repository Table
	|--------------------------------------------------------------------------
	|
	| This table keeps track of all the migrations that have already run for
	| your application. Using this information, we can determine which of
	| the migrations on disk haven't actually been run in the database.
	|
	*/

	'migrations' => 'migrations',

	/*
	|--------------------------------------------------------------------------
	| Redis Databases
	|--------------------------------------------------------------------------
	|
	| Redis is an open source, fast, and advanced key-value store that also
	| provides a richer body of commands than a typical key-value system
	| such as APC or Memcached. Laravel makes it easy to dig right in.
	|
	*/

	'redis' => [

		'client' => 'predis',

		'default' => [
			'host' => env('REDIS_HOST', '127.0.0.1'),
			'password' => env('REDIS_PASSWORD', null),
			'port' => env('REDIS_PORT', 6379),
			'database' => env('REDIS_DB', 0),
		],

		'cache' => [
			'host' => env('REDIS_HOST', '127.0.0.1'),
			'password' => env('REDIS_PASSWORD', null),
			'port' => env('REDIS_PORT', 6379),
			'database' => env('REDIS_CACHE_DB', 1),
		],

	],

];
