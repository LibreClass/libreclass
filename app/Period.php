<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	public $fillable = [
		'course_id',
		'name',
		'value', // 1 = primeiro período/série; 2 = segundo período/série; ...
		'status',
	];

	/**
	 * The model's default values for attributes.
	 *
	 * @var array
	 */
	protected $attributes = [
		'status' => 'E',
	];

	protected $casts = [
		'id' => 'array',
	];

	public function disciplines()
	{
		return $this->hasMany(Discipline::class);
	}

	public function course()
	{
		return $this->belongsTo(Course::class);
	}
}
