<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SiteControllerTest extends TestCase
{
    use RefreshDatabase;
    
    // this area deals with test for registration

    public function test_to_see_if_users_registration_information_entered_the_database(): void
    {
        Mail::fake();
        $data = [
            "firstname" => "kingsley Achumie",
            "lastname" => "kingsley Achumie",
            "email" => "kingsonly13c@gmail.com",
            "password" => "ubuxa##99",
           ];
        $response = $this->post('/api/register',$data);
        $response->assertStatus(201);
        $newData = [
            "email" => "kingsonly13c@gmail.com",
           ];
        $this->assertDatabaseHas("users", $newData);
    }

    public function test_to_see_if_the_returned_value_in_registration_page_is_correct(): void
    {
        Mail::fake();
        $data = [
            "firstname" => "kingsley Achumie",
            "lastname" => "kingsley Achumie",
            "email" => "kingsonly13a@gmail.com",
            "password" => "ubuxa##99",
           ];
        $response = $this->post('/api/register',$data);
        $response->assertStatus(201)->assertJsonStructure(
            [
                'status',
                'message',
                'data'=>[
                    'name',
                    'email',
                ]
            ]
        );
    }

    public function test_to_see_if_validatiopn_works(){
        Mail::fake();
        $data = [
            "firstname" => "kingsley Achumie",
            "lastname" => "kingsley Achumie",
            "email" => "kingsonly13a@gmail.com",
           ];
        $response = $this->post('/api/register',$data);
        $response->assertStatus(302);
    }

    // this area deals with test for login

    public function test_to_see_if_the_structure_of_the_login_json_returened_is_correct(){
        $data = [
            "email" => "kingsonly15c@gmail.com",
            "password" => "password",
        ];
        //create a user with the above $data
        User::factory(1)->create(["email" => $data["email"] ]);
        $response = $this->post("/api/login",$data);
        $response->assertOk()->assertJsonStructure([
            "status",
            "message",
            "data"=>[
                "token"
            ]
        ]);
    }

    public function test_that_login_validation_is_working(){
        $data = ["email" => "test"];
        $response = $this->post("/api/login",$data);
        $response->assertStatus(302);
    }
}
