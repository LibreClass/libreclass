<?php namespace Tests\Feature\App\Http\Controllers;

use App\User;
use App\Classe;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProgressionControllerTest extends TestCase
{
	public function testStudentsAndClasses401()
	{
		$response = $this->json('POST', 'progression/students-and-classes');

		$response->assertStatus(401);
	}

	public function testStudentsAndClassesOk()
	{
		$this->be(User::whereType('I')->first());

		$classes = Classe::take(2)->get();

		$response = $this->json('POST', 'progression/students-and-classes', [
			'previous_classe_id' => encrypt($classes[0]->id),
			'classe_id' => encrypt($classes[1]->id),
		]);

		$response->assertOk()
			->assertJson(['status' => 1]);
	}
}
