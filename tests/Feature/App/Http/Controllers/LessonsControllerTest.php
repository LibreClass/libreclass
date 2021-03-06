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
use Carbon\Carbon;
use App\Http\Controllers\LessonsController;

class LessonsControllerTest extends TestCase
{
	protected function setUp(): void
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

	}

	public function testHideTransferredStudentFromFrequency()
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

		$response = $this->call('GET', '/lessons', ['l' => encrypt($this->lesson->id)]);

		$response->assertViewHas('students', function($students){
			return $students->contains('name', $this->student->name);
		});

		$response->assertViewHas('students', function($students) use ($transferredStudent){
			return !$students->contains('name', $transferredStudent->name);
		});
	}
}
