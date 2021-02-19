<?php

namespace Tests\Feature\App\Http\Controllers;

use Tests\TestCase;
use App\User;
use App\Offer;
use App\Unit;
use App\Frequency;
use App\Lesson;
use App\Classe;
use App\Period;
use App\Course;
use App\Discipline;
use App\Attend;
use App\Exam;
use App\ExamsValue;
use Carbon\Carbon;
use App\Http\Controllers\LessonsController;

class AvaliableControllerTest extends TestCase
{
	protected function setUp()
	{
		parent::setUp();

		$this->professor = factory(User::class)->create([
			'type' => 'P',
		]);

		$this->be($this->professor);

		$this->student = factory(User::class)->create([
			'name' => 'testAluno',
			'type' => 'N'
		]);

		$this->institution = factory(User::class)->create([
			'name' => 'testInstituicao',
			'type' => 'I',
		]);

		$this->course = factory(Course::class)->create([
			'institution_id' => $this->institution->id,
			'name' => 'testCourse',
			'average' => 7.00,
		]);

		$this->period = factory(Period::class)->create([
			'course_id' => $this->course->id,
		]);

		$this->classe = factory(Classe::class)->create([
			'period_id' => $this->period->id,
		]);

		$this->discipline = factory(Discipline::class)->create([
			'period_id' => $this->period->id,
		]);

		$this->offer = factory(Offer::class)->create([
			'class_id' => $this->classe->id,
			'discipline_id' => $this->discipline->id,
		]);

		$this->unit = factory(Unit::class)->create([
			'offer_id' => $this->offer->id,
		]);

		$this->attend = factory(Attend::class)->create([
			'user_id' => $this->student->id,
			'unit_id' => $this->unit->id,
			'status' => 'M',
		]);

		$this->lesson = factory(Lesson::class)->create([
			'unit_id' => $this->unit->id,
			'date' => Carbon::today()->format('Y-m-d'),
			'title' => 'aulaTeste',
		]);

		$this->frequency = factory(Frequency::class)->create([
			'attend_id' => $this->attend->id,
			'lesson_id' => $this->lesson->id,
			'value' => 'P',
		]);

		$this->exam = factory(Exam::class)->create([
			'unit_id' => $this->unit->id,
			'aval' => 'R',
			'date' => Carbon::today(),
		]);
	}

	public function testHideTransferredStudentFromRecoveryTest()
	{
		$transferredStudent = factory(User::class)->create([
			'name' => 'aluno2',
			'type' => 'N'
		]);

		$transferredAttend = factory(Attend::class)->create([
			'user_id' => $transferredStudent->id,
			'unit_id' => $this->unit->id,
			'status' => 'T',
		]);

		$transferredFrequency = factory(Frequency::Class)->create([
			'attend_id' => $transferredAttend->id,
			'lesson_id' => $this->lesson->id,
			'value' => 'P',
		]);

		$examValues = factory(ExamsValue::class)->create([
			'attend_id' => $this->attend->id,
			'exam_id' => $this->exam->id,
			'value' => 4.00,
		]);

		$transferredExamValues = factory(ExamsValue::class)->create([
			'attend_id' => $transferredAttend->id,
			'exam_id' => $this->exam->id,
			'value' => 4.00,
		]);

		$response = $this->call(
			'GET',
			'/avaliable/finalunit/' . encrypt($this->unit->id),
			['l' => encrypt($this->lesson->id)]
		);

		$response->assertSee($this->student->name);
		$response->assertDontSee($transferredStudent->name);
	}
}
