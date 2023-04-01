<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SiteControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_to_see_if_users_registration_information_entered_the_database(): void
    {
        $data = [
            "name" => "kingsley Achumie",
            "email" => "kingsonly13c@gmail.com",
            "password" => "ubuxa##99",
           ];
        $response = $this->post('/api/register');
        unset($data["password"]);
        $response->assertStatus(201)->assertDatabaseHas("users", $data);
    }
}
