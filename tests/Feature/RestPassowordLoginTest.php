<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RestPassowordLoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_send_password()
    {

        $user = User::find(1);
        $response = $this->withoutExceptionHandling()->actingAs($user, 'web')->withSession(['banned' => false])
            ->post(route('welcome.password.first', ['user' =>  $user->getRouteKey()]));

        $response->assertStatus(200);;
    }
}
