<?php

use Faker\Generator as Faker;
use App\State;

$factory->define(State::class, function (Faker $faker) {
	return [
		'name' => $faker->name,
	];
});
