<?php namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use DB;
use Exception;
use Log;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication;

	protected function setUp()
	{
		parent::setUp();

		try {
			$pdo = DB::connection()->getPdo();
			$pdo->exec(file_get_contents(base_path('create-db.sql')));
			DB::reconnect();
		} catch (Exception $e) {
			Log::info('db já existe...');
		}
	}

	protected function tearDown()
	{
		$tables = collect(DB::select('SHOW TABLES'))
			->map(function($table) {
				return collect((array) $table)->values()->first();
			})
			->all();

		$pdo = DB::connection()->getPdo();

		$pdo->exec('SET foreign_key_checks = 0;');

		foreach ($tables as $table) {
			$pdo->exec("TRUNCATE TABLE `$table`;");
		}

		$pdo->exec('SET foreign_key_checks = 1;');

		parent::tearDown();
	}
}
