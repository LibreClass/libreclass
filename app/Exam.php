<?php namespace App;

use Illuminate\Database\Eloquent\Model;
class Exam extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'unit_id',
		'title',
		'type', // Tipo de avaliação: E = exams; L = list; P = projects;...
		'aval', // A = Avaliação, R = Recuperação da Unidade
		'weight',
		'date',
		'comments',
		'status',
	];

	/**
	 * The model's default values for attributes.
	 *
	 * @var array
	 */
	protected $attributes = [
		'status' => 'E',
		'weight' => 1,
	];

	protected $casts = [
		'id' => 'array',
	];

	public function setWeightAttribute($value)
	{
		$this->attributes['weight'] = (int) $value;
	}

	public function unit()
	{
		return $this->belongsTo(Unit::class);
	}

	public function descriptive_exams()
	{
		$descriptive_exams = $this->hasMany('App\DescriptiveExam', 'exam_id')->get();

		foreach ($descriptive_exams as $key => $descriptive_exam) {
			$descriptive_exams[$key]['student'] = $descriptive_exam->student();
		}

		return $descriptive_exams;
	}
}
