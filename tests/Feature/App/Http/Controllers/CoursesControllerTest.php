<?php namespace Tests\Feature\App\Http\Controllers;

use App\User;
use Tests\TestCase;

class CoursesControllerTest extends TestCase
{
    protected function setUp(): void {
        parent::setUp();
        $this->institution = factory(User::class)->create(['type' => 'I']);
        $this->be($this->institution);
    }

    public function testCourseSelectedSave()
    {
            $response = $this->postJson('/courses/selected', ['course_id' => '1']);
        $response->assertJson(['status' => true, 'value' => 1]);
    }

    public function testCourseSelected()
    {
        $response = $this->getJson('/courses/selected');
        $response->assertStatus(200);
        $response->assertJson(['status' => false, 'value' => null]);
    }
}
