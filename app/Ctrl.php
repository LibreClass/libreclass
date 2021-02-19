<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Ctrl extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'controller_id', // Id do usuário controlador
		'subject_id', // Id do usuário controlado
		'type', // Usuários (ex.: instituição) podem cadastrar usuários (ex.: professor); IP = Instituição controla Professor; ...
	];
}
