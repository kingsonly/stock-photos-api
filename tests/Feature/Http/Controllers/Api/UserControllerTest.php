<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Followers;
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
        $response = $this->get('/api/user');
        // start assertions here .
        $response->assertStatus(200)->assertJsonStructure([
            "data"=>[
                "name"
                
            ]
            
        ]);
    }

    public function test_user_has_been_deleted(){

        User::factory(10)->create();
        $this->userCreate();
        $response = $this->get('/api/user/deleteuser');
        $response->assertStatus(200)->assertJsonStructure([
            "status",
            "message"
        ]);
        
    }
    
    public function test_to_see_if_followers_fetched_are_correct_for_a_perticular_user(){
        User::factory(10)->create();
        $user = $this->userCreate();
        Followers::factory()->count(5)->sequence(
            ['user_id' => $user->id ,"user_follower_id" => 1],
            ['user_id' => $user->id ,"user_follower_id" => 2],
            ['user_id' => $user->id ,"user_follower_id" => 3],
            ['user_id' => $user->id ,"user_follower_id" =>4],
            ['user_id' => $user->id ,"user_follower_id" => 5],
        )->create();
        $response = $this->get('/api/user/followers');
        $response->assertOk()->assertJsonStructure(
            [
                "data" => [
                    "*" =>[
                        "id",
                        "name",
                        "image"
                    ]
                ]
            ]
        );
        
    }

    public function test_that_total_followers_is_correct(){
        User::factory(10)->create();
        $user = $this->userCreate();
        Followers::factory()->count(5)->sequence(
            ['user_id' => $user->id ,"user_follower_id" => 1],
            ['user_id' => $user->id ,"user_follower_id" => 2],
            ['user_id' => $user->id ,"user_follower_id" => 3],
            ['user_id' => $user->id ,"user_follower_id" =>4],
            ['user_id' => $user->id ,"user_follower_id" => 5],
        )->create();
        $response = $this->get('/api/user/totalfollowers');
        $response->assertStatus(200)->assertJson(
            [
                "status" => "success",
                "data" => 5
            ]
            );
        
    }

    //followers
}
