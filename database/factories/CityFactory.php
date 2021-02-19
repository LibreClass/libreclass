<?php

use Faker\Generator as Faker;
use App\City;

$factory->define(City::class, function (Faker $faker) {
	return [
		'name' => $faker->name,
	];
});
