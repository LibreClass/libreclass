<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Period extends Model
{
	use SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	public $fillable = [
		'course_id',
		'name',
		'value', // 1 = primeiro período/ano; 2 = segundo período/ano; ...
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

	protected $dates = [
		'deleted_at'
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
