<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id',
		'title',
		'value', // S = sugestões; B = bugs
		'description',
	];

	/**
	 * The model's default values for attributes.
	 *
	 * @var array
	 */
	protected $attributes = [
		'value' => 'S',
	];
}
