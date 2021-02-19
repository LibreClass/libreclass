<?php namespace App;

use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
	use SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'unit_id', // Id da unidade à que a aula está relacionada
		'date',
		'title',
		'description', // summary/abstract, breve descrição da aula
		'goals',
		'content',
		'methodology', // Metodologia de classe
		'resources', // Recursos necessários
		'keyworks',
		'estimated_time', // Tempo estimado de uma aula
		'bibliography',
		'valuation', // Método de avaliação (prova, trabalho, lista...)
		'notes',
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

	protected $dates = [
		'deleted_at',
	];

	protected $casts = [
		'id' => 'array',
	];

	public function setEstimatedTimeAttribute($value)
	{
		$this->attributes['estimated_time'] = (int) $value;
	}

	public function unit()
	{
		return $this->belongsTo(Unit::class);
	}
}
