<?php namespace App;

use App\Attend;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
	protected $fillable = [
		'offer_id',
		'value', // 1 = primeira unidade; 2 = segunda unidade; ...
		'calculation', // Tipo de cálculo para média: S = sum; A = avarage, W=média ponderada, P = Parecer Descritivo
		'status', // E = Enable, D = Disable
	];

	/**
	 * The model's default values for attributes.
	 *
	 * @var array
	 */
	protected $attributes = [
		'value' => 1,
		'calculation' => 'A',
		'status' => 'E',
	];

	protected $casts = [
		'id' => 'array',
	];

	public function setValueAttribute($value)
	{
		$this->attributes['value'] = (int) $value;
	}

	public function offer()
	{
		return $this->belongsTo(Offer::class);
	}

	public function getOffer()
	{
		return Offer::find($this->offer_id);
	}

	public function getAverage($student)
	{
		$exams = Exam::where("unit_id", $this->id)->whereAval("A")->whereStatus("E")->get();
		$attend = Attend::where("unit_id", $this->id)->where("user_id", $student)->first();
		if (!$attend) {
			return null;
		}

		$out = [null, null];
		$sum = 0.;
		$weight = 0.;

		foreach ($exams as $exam) {
			$value = ExamsValue::where("exam_id", $exam->id)
				->where("attend_id", $attend->id)
				->first();

			if ($value) {
				$sum += ((float) $value->value) * ($this->calculation == "W" ? $exam->weight : 1);
			}
			/* so multiplica pelo peso quando for média ponderada */
			$weight += $exam->weight;
		}

		/* tipo de calculo da média */
		if ($this->calculation == "A" && count($exams)) {
			$out[0] = $sum / count($exams);
		} elseif ($this->calculation == "W" && $weight > 0) {
			$out[0] = $sum / $weight;
		} elseif ($this->calculation == "S") {
			$out[0] = $sum;
		}

		$final = Exam::where("unit_id", $this->id)->whereAval("R")->first();
		if ($final) {
			$value = ExamsValue::where("exam_id", $final->id)->where("attend_id", $attend->id)->first();
			if ($value) {
				$out[1] = $value->value;
			}
		}

		return $out;
	}

	public function getLessons()
	{
		return Lesson::where("unit_id", $this->id)->whereStatus("E")->get();
	}

	public function getLessonsToPdf()
	{
		return Lesson::where("unit_id", $this->id)->whereStatus("E")->orderBy("date", "asc")->orderBy("id", "asc")->get();
	}

	public function countLessons()
	{
		return Lesson::where("unit_id", $this->id)->whereStatus("E")->count();
	}

	public function getExams()
	{
		return Exam::where("unit_id", $this->id)->whereStatus("E")->whereAval("A")->get();
	}

	public function getRecovery()
	{
		return Exam::where("unit_id", $this->id)->whereStatus("E")->whereAval("R")->first();
	}

	public function attend()
	{
		return $this->hasOne(Attend::class);
	}

}
