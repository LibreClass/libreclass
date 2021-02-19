<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	public $fillable = [
		'institution_id',
		'name',
		'type', // dados do csv
		'modality', // dados do csv
		'absent_percent',
		'average',
		'average_final',
		'status', // E - enable, D - disable
		'curricular_profile',
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

	public function setNameAttribute($value)
	{
		$this->attributes['name'] = titleCase(trimpp($value));
	}

	public function setAbsentPercentAttribute($value)
	{
		$this->attributes['absent_percent'] = (float) $value;
	}

	public function setAverageAttribute($value)
	{
		$this->attributes['average'] = (float) $value;
	}

	public function setAverageFinalAttribute($value)
	{
		$this->attributes['average_final'] = (float) $value;
	}

	public function institution()
	{
		return $this->belongsTo(User::class, 'institution_id');
	}

	public function periods()
	{
		return $this->hasMany(Period::class);
	}
}
