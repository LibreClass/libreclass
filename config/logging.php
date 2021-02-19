<?php

use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

	'default' => env('LOG_CHANNEL', 'stack'),

	/*
	|--------------------------------------------------------------------------
	| Log Channels
	|--------------------------------------------------------------------------
	|
	| Here you may configure the log channels for your application. Out of
	| the box, Laravel uses the Monolog PHP logging library. This gives
	| you a variety of powerful log handlers / formatters to utilize.
	|
	| Available Drivers: "single", "daily", "slack", "syslog",
	|                    "errorlog", "monolog",
	|                    "custom", "stack"
	|
	*/

	'channels' => [
		'stack' => [
			'driver' => 'stack',
			'channels' => ['daily', 'slack'],
		],

		'single' => [
			'driver' => 'single',
			'path' => storage_path('logs/laravel.log'),
			'level' => 'debug',
		],

		'daily' => [
			'driver' => 'daily',
			'path' => storage_path('logs/laravel.log'),
			'level' => 'debug',
			'days' => 30,
		],

		'slack' => [
			'driver' => 'slack',
			'url' => env('LOG_SLACK_WEBHOOK_URL'),
			'username' => 'Laravel Log',
			'emoji' => ':boom:',
			'level' => 'warning',
		],

		/* Mudei para tratar um dos erros, mas acaba gerando mais:
			'slack' => [
			'driver' => 'slack',
			'url' => 'https://hooks.slack.com/services/TC3S00...',
			'username' => 'app',
			'emoji' => ':boom:',
			'level' => 'info',
		],
		*/

		'papertrail' => [
			'driver'  => 'monolog',
			'level' => 'debug',
			'handler' => SyslogUdpHandler::class,
			'handler_with' => [
				'host' => env('PAPERTRAIL_URL'),
				'port' => env('PAPERTRAIL_PORT'),
			],
		],

		'stderr' => [
			'driver' => 'monolog',
			'handler' => StreamHandler::class,
			'with' => [
				'stream' => 'php://stderr',
			],
		],

		'syslog' => [
			'driver' => 'syslog',
			'level' => 'debug',
		],

		'errorlog' => [
			'driver' => 'errorlog',
			'level' => 'debug',
		],
	],
];
