<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'period_id',
		'name', // Nome da disciplina
		'ementa', // ementa da disciplina
		'status', // E = Enabled; D = Disabled; F = Finalized;
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

	public function period()
	{
		return $this->belongsTo(Period::class);
	}

	public function getPeriod()
	{
		return Period::find($this->period_id);
	}

	public function offers()
	{
		return $this->hasMany(Offer::class);
	}
}
