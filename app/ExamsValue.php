<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamsValue extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'attend_id',
		'exam_id',
		'value',
	];

	public static function getValue($user, $exam)
	{
		$attend_ids = Attend::where('user_id', $user)->get(['id']);
		$value = ExamsValue::where('exam_id', $exam)
			->whereIn('attend_id', $attend_ids)
			->first(['value']);

		return $value ? $value->value : '';
	}

}
