<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DescriptiveExam extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'attend_id',
		'exam_id',
		'description',
		'approved',
	];

	public function student()
	{
		return $this->attend->user;
	}

	public function attend()
	{
		return $this->belongsTo(Attend::class);
	}

	public function exam()
	{
		return $this->belongsTo(Exam::class);
	}
}
