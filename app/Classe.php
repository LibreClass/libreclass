<?php namespace App;

use Illuminate\Database\Eloquent\Model;
class Classe extends Model
{
	
	public $table = 'classes';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'period_id',
		'name',
		'class',
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

	public function period()
	{
		return $this->belongsTo(Period::class);
	}

	public function getPeriod()
	{
		return Period::find($this->period_id);
	}

	public function fullName()
	{
		return "$this->name $this->class";
	}
}
