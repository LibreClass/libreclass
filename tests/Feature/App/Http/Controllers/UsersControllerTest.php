<?php namespace Tests\Feature\App\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// use PDF;
use Mockery;

use App\User;
use App\Relationship;
use App\Course;
use App\Period;
use App\Classe;
use App\Offer;
use App\Unit;
use App\Attend;
use App\Discipline;
use App\City;
use App\State;
use App\Country;

class UsersControllerTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();

		$this->institution = factory(User::class)->create([
			'name' => 'testInstituicao',
			'type' => 'I',
		]);
	}

	public function testPrintScholarReportWithoutLogo()
	{
		$this->be($this->institution);

		$school_year = '2019';

		$discipline = factory(Discipline::class)->create();

		$student = factory(User::class)->create([
			'name' => 'testAluno',
			'type' => 'N',
		]);

		factory(Relationship::class)->create([
			'user_id' => $this->institution->id,
			'friend_id' => $student->id,
			'type' => '1',
		]);

		$course = factory(Course::class)->create([
			'institution_id' => $this->institution->id,
		]);

		$period = factory(Period::class)->create([
			'course_id' => $course->id,
		]);

		$classe = factory(Classe::class)->create([
			'period_id' => $period->id,
			'school_year' => $school_year,
		]);

		$offer = factory(Offer::class)->create([
			'class_id' => $classe->id,
			'discipline_id' => $discipline->id,
		]);

		$unit = factory(Unit::class)->create([
			'offer_id' => $offer->id,
			'value' => 1,
		]);

		factory(Attend::class)->create([
			'user_id' => $student->id,
			'unit_id' => $unit->id,
		]);


		Mockery::mock('alias:PDF')
			->shouldReceive('loadView')
			->with('reports.arroio_dos_ratos-rs.final_result', Mockery::any())
			->once()
			->andReturnUsing(function($view, $data) {
				return Mockery::mock('AnyAny')
					->shouldReceive('stream')
					->andReturn(view($view, $data))
					->getMock();
			});

		$this->call('GET', '/user/scholar-report', [
				'u' => encrypt($student->id),
				'school_year' => $school_year,
				'course' => $course->id,
				'unit_value' => [1, 2, 3, 4],
			])
			->assertOk()
			->assertSee('logo-libreclass-vertical.png');

		Mockery::close();
	}

	public function testPrintScholarReportWithLogo()
	{
		$this->be($this->institution);

		$school_year = '2019';

		$discipline = factory(Discipline::class)->create();

		$city = factory(City::class)->create([
			'name' => 'Arroio dos Ratos',
			'state_id' => factory(State::class)->create([
				'country_id' => factory(Country::class)->create(),
			])
		]);

		$this->institution->city_id = $city->id;
		$this->institution->save();

		$student = factory(User::class)->create([
			'name' => 'testAluno',
			'type' => 'N',
		]);

		factory(Relationship::class)->create([
			'user_id' => $this->institution->id,
			'friend_id' => $student->id,
			'type' => '1',
		]);

		$course = factory(Course::class)->create([
			'institution_id' => $this->institution->id,
		]);

		$period = factory(Period::class)->create([
			'course_id' => $course->id,
		]);

		$classe = factory(Classe::class)->create([
			'period_id' => $period->id,
			'school_year' => $school_year,
		]);

		$offer = factory(Offer::class)->create([
			'class_id' => $classe->id,
			'discipline_id' => $discipline->id,
		]);

		$unit = factory(Unit::class)->create([
			'offer_id' => $offer->id,
			'value' => 1,
		]);

		factory(Attend::class)->create([
			'user_id' => $student->id,
			'unit_id' => $unit->id,
		]);


		Mockery::mock('alias:PDF')
			->shouldReceive('loadView')
			->with('reports.arroio_dos_ratos-rs.final_result', Mockery::any())
			->once()
			->andReturnUsing(function($view, $data) {
				return Mockery::mock('AnyAny')
					->shouldReceive('stream')
					->andReturn(view($view, $data))
					->getMock();
			});

		$this->call('GET', '/user/scholar-report', [
				'u' => encrypt($student->id),
				'school_year' => $school_year,
				'course' => $course->id,
				'unit_value' => [1, 2, 3, 4],
			])
			->assertOk()
			->assertSee('logo-arroio_dos_ratos-rs.jpg');

		Mockery::close();
	}
}
