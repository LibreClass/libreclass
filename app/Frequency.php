<?php namespace App;

use App\Attend;

use Illuminate\Database\Eloquent\Model;

class Frequency extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'attend_id', // Id do relacionamento cursa
		'lesson_id', // Id da aula
		'value', // P = Presente; F = Falta;
	];

	public static function getValue($user, $lesson)
	{
		$attend_ids = Attend::where('user_id', $user)->get(['id']);

		$value = Frequency::where('lesson_id', $lesson)
			->whereIn('attend_id', $attend_ids)
			->first();

		return $value ? $value->value : '';
	}

	public function attend()
	{
		return $this->belongsTo(Attend::class);
	}
}
