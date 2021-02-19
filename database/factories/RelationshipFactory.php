<?php

use Faker\Generator as Faker;
use App\Relationship;

$factory->define(Relationship::class, function (Faker $faker) {
	return [
		'status' => 'E',
	];
});
