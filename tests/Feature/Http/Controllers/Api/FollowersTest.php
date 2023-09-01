<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Followers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Models\User;


class FollowersTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_to_see_if_a_user_can_follow_another_user(): void
    {
        $this->userCreate();
        $user = User::factory()->create([
            "name"=> "emeka john"
        ]);
        
        $response = $this->get('/api/followers/follow/'.$user->id);

        $response->assertStatus(200)->assertJson(
            [
                "status" => "success",
                "message" => "you now follow emeka john"
            ]
        );
    }

    public function test_to_see_if_user_has_already_been_followed(){
        $loggedInUser = $this->userCreate();
        $user = User::factory()->create([
            "name"=> "emeka john"
        ]);

        Followers::factory()->create([
            "user_id" => $loggedInUser->id,
            "user_follower_id" => $user->id,
        ]);
        
        $response = $this->get('/api/followers/follow/'.$user->id);

        $response->assertStatus(400)->assertJson(
            [
                "status" => "error",
                "message" => "You are already following emeka john"
            ]
        );
    }
    public function test_to_see_if_a_followed_user_can_be_unfollowed(){
        $loggedInUser = $this->userCreate();
        $user = User::factory()->create([
            "name"=> "emeka john"
        ]);

        Followers::factory()->create([
            "user_id" => $loggedInUser->id,
            "user_follower_id" => $user->id,
        ]);
        
        $response = $this->get('/api/followers/unfollow/'.$user->id);

        $response->assertStatus(200)->assertJson(
            [
                "status" => "success",
                "message" => "you have successfully unfollowed the above user"
            ]
        );

    }

    public function test_that_we_can_fetch_a_loggedin_users_followers(){

    }
}
