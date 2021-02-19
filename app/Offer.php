<?php namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'offer_id',
		'class_id',
		'discipline_id',
		'classroom',
		'day_period', // dados do csv
		'grouping', // N = Nothing, M = Master, S = Slave
		'maxlessons',
		'type_final',
		'date_final',
		'comments',
		'status',
	];

	/**
	 * The model's default values for attributes.
	 *
	 * @var array
	 */
	protected $attributes = [
		'maxlessons' => 180,
		'status' => 'E',
		'grouping' => 'N',
	];

	protected $casts = [
		'id' => 'array',
	];

	public function setMaxlessonsTimeAttribute($value)
	{
		$this->attributes['maxlessons'] = (int) $value;
	}

	public function master()
	{
		return $this->belongsTo(Offer::class, 'offer_id');
	}

	public function slaves()
	{
		return $this->hasMany(Offer::class, 'offer_id');
	}

	public function discipline()
	{
		return $this->belongsTo(Discipline::class);
	}

	public function units()
	{
		return $this->hasMany(Unit::class);
	}

	public function classe()
	{
		return $this->belongsTo(Classe::class, 'class_id');
	}

	public function getDiscipline()
	{
		return Discipline::find($this->discipline_id);
	}

	public function getClass()
	{
		return Classe::find($this->class_id);
	}

	public function getFirstUnit()
	{
		return Unit::where("offer_id", $this->id)->first();
	}

	public function getLastUnit()
	{
		return Unit::where("offer_id", $this->id)->orderBy("value", "desc")->first();
	}

	public function getUnits()
	{
		return Unit::where("offer_id", $this->id)->get();
	}

	public function getLectures()
	{
		return Lecture::where("offer_id", $this->id)->first();
	}

	public function getAllLectures()
	{
		return Lecture::where("offer_id", $this->id)->get();
	}

	public function qtdAbsences($student_id)
	{
		return DB::select("SELECT count(*) as 'qtd'
			from units, attends, lessons, frequencies
			where units.offer_id=? and
				units.id=lessons.unit_id and
				lessons.id=frequencies.lesson_id and
				lessons.deleted_at is null and
				frequencies.attend_id=attends.id and
				frequencies.value='f' and
				attends.user_id=?", [$this->id, $student_id])[0]->qtd;
	}

	public function qtdUnitAbsences($student_id, $unitValue)
	{
		return DB::select("SELECT count(*) as 'qtd'
			from units, attends, lessons, frequencies
			where units.offer_id = ? and
				units.value = ? and
				units.id = lessons.unit_id and
				lessons.id = frequencies.lesson_id and
				lessons.deleted_at is null and
				frequencies.attend_id = attends.id and
				frequencies.value = 'f' and
				attends.user_id = ?", [$this->id, $unitValue, $student_id])[0]->qtd;
	}

	public function qtdLessons()
	{
		return DB::select("SELECT count(*) as 'qtd'
			from units, lessons
			where units.offer_id=? and
						units.id=lessons.unit_id and
						lessons.deleted_at is null", [$this->id])[0]->qtd;
	}

	public function lessons()
	{
		return DB::select("SELECT *
			from units, lessons
			where units.offer_id=? and
						units.id=lessons.unit_id and
						lessons.deleted_at is null", [$this->id]);
	}

	public function qtdUnitLessons($unitValue)
	{
		return DB::select("SELECT count(*) as 'qtd'
			from units, lessons
			where units.offer_id=? and
				units.value=? and
				units.id=lessons.unit_id and
				lessons.deleted_at is null", [$this->id, $unitValue])[0]->qtd;
	}

	public function getCourse()
	{
		$offer = null;
		if (!$this->offer_id) {
			$offer = Offer::whereOfferId($this->id)->first();
		}
		if (!$offer) {
			$offer = $this;
		}

		return $offer->discipline->period->course;
	}

	public function getTeachers()
	{
		$teachers = [];
		$lectures = Lecture::where("offer_id", $this->id)->get();
		foreach ($lectures as $lecture) {
			$teachers[] = $lecture->getUser()->name;
		}
		return $teachers;
	}
}
