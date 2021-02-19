<?php namespace App;

use Illuminate\Database\Eloquent\Model;
class Bind extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id',
		'discipline_id',
	];

	public function discipline()
	{
		return $this->hasOne(Discipline::class);
	}
}
