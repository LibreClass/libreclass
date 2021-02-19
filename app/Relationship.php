<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id', // Id do usuário
		'friend_id', // Id do amigo do usuário
		'enrollment', // Matricula
		'status', // Relacionamento ativo ou inativo
		'type', // F = friends; P = parents; S = subscribe;
			// 1 = instituição->aluno; 2 = instituição->professor; 3 = professor->aluno
	];

	/**
	 * The model's default values for attributes.
	 *
	 * @var array
	 */
	protected $attributes = [
		'status' => 'E',
	];

	public function getUser()
	{
		return User::find($this->user_id);
	}

	public function getFriend()
	{
		return User::find($this->friend_id);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function friend()
	{
		return $this->belongsTo(User::class, 'friend_id');
	}
}
