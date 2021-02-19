<?php

use Faker\Generator as Faker;
use App\Course;

$factory->define(Course::class, function (Faker $faker) {
	return [
		'name' => $faker->name,
	];
});
