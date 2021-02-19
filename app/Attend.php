<?php namespace App;

use App\Frequency;
use Illuminate\Database\Eloquent\Model;

class Attend extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id',
		'unit_id',
		'status', // M = matriculado; D = desistente; R = remanejado; T = transferido
	];

	/**
	 * The model's default values for attributes.
	 *
	 * @var array
	 */
	protected $attributes = [
		'status' => 'M',
	];

	protected $casts = [
		'id' => 'array',
	];


	public function getUser()
	{
		return User::find($this->user_id);
	}

	public function getExamsValue($exam)
	{
		$examValue = ExamsValue::where('exam_id', $exam)
			->where('attend_id', $this->id)->first();

		if ($examValue) {
			return $examValue->value;
		} else {
			return null;
		}
	}

	public function getDescriptiveExam($exam)
	{
		$examDescriptive = DescriptiveExam::where('exam_id', $exam)
			->where('attend_id', $this->id)->first();
		if ($examDescriptive) {
			return [
				'description' => $examDescriptive->description,
				'approved' => $examDescriptive->approved,
			];
		} else {
			return null;
		}
	}

	public function getUnit()
	{
		return Unit::find($this->unit_id);
	}

	public function unit()
	{
		return $this->belongsTo(Unit::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function frequencies()
	{
		return $this->hasMany(Frequency::class);
	}
}
