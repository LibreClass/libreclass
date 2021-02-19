<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id', // Id do professor que leciona a disciplina
		'offer_id', // Id da disciplina lecionada
		'order',
	];

	/**
	 * The model's default values for attributes.
	 *
	 * @var array
	 */
	protected $attributes = [
		'order' => 1,
	];

	public function setOrderAttribute($value)
	{
		$this->attributes['order'] = (int) $value;
	}

	public function getUser()
	{
		return User::find($this->user_id);
	}

	public function getOffer()
	{
		return Offer::find($this->offer_id);
	}

	public function offer()
	{
		return $this->belongsTo(Offer::class);
	}
}
