<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * test that the index page can pull all userson the system
     */
    public function test_that_all_user_is_fetched_from_the_database(): void
    {
        // use user factory to add 20 records into the user table
        User::factory(10)->create();
        // goto the users route
        $this->userCreate();
        $response = $this->get('/api/allusers');
        // start assertions here .
        $response->assertStatus(200)->assertJsonStructure([
            "data"=>[
                "*" =>[
                    "name"
                ]
            ]
            
        ]);
    }

    public function test_if_a_single_user_an_be_fetched(){
        User::factory(10)->create();
        $this->userCreate();
        $response = $this->get('/api/getuser/5');
        $response->assertStatus(200)->assertJsonStructure([
            "data"=>[
                "name"
            ]
            
        ]);
    }

    public function test_to_confirm_that_user_doesnot_exist(){
        User::factory(10)->create();
        $this->userCreate();
        $response = $this->get('/api/getuser/16');
        $response->assertStatus(400)->assertJsonStructure([
            "status",
            "message"
            
        ]);
    }

    public function test_user_has_been_deleted(){

        User::factory(10)->create();
        $this->userCreate();
        $response = $this->get('/api/deleteuser/5');
        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', [
            'id' => 5,
        ]);
    }

    public function test_that_total_users_is_correct(){
        User::factory(10)->create();
        $response = $this->get('/api/totalusers');
        $response->assertJson(
            [
                "status" => "success",
                "data" => 10
            ]
            );
        
    }
}
