<?php

use Faker\Generator as Faker;
use App\Discipline;

$factory->define(Discipline::class, function (Faker $faker) {
	return [
		'name' => $faker->name,
	];
});
