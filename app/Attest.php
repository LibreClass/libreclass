<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Attest extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'institution_id',
		'student_id',
		'date',
		'days',
		'description',
	];

	public function setDaysAttribute($value)
	{
		$this->attributes['days'] = (int) $value;
	}
}
