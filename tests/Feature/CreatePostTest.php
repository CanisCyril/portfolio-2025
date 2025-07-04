<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePostTest extends TestCase

{

    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_authenicated_user_can_create_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/create/post', [
            'title' => 'abc',
            'message' => '12346'
        ]);


        $response->assertStatus(200);
    }

    public function test_none_authenicated_user_cannot_create_post(): void
    {
        $this->assertGuest(); // Confirms no user is logged in

        $response = $this->post('/create/post', [
            'title' => 'abc',
            'message' => '12346'
        ]);
        $response->assertRedirect('/login'); // or assertStatus(302)
    }
}
