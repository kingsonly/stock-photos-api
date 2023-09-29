<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Followers;
use Illuminate\Http\Request;

/**
     * This route is used to unfollow a user
     * @group Followers Management
     * APIs for managing basic followers requirments such as following a user , unfollowing a user, fetching all followers, fetching all followed etc
     */
class FollowersController extends Controller
{
    /**
     * When this route is called , it is used to follow a specific user.
     * @function follow
     * @ulrParam string $id
     * @response status=200 scenario="success"{
     *  "status":"success",
     *  "message":"you now follow John Doe"
     * }
     * @response status=400 scenario="error"{
     *  "status":"error",
     *  "message":"You are already following John Doe"
     * }
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow($id)
    {
        // Check if user has already been followed
        $user = auth()->guard('sanctum')->user();
        $follower = Followers::where(['user_id' => $user->id, "user_follower_id" => $id])->first();
        if (!empty($follower)) {
            return response()->json([
                "status" => "error",
                "message" => "You are already following " . $follower->followersName->name,
            ], 400);
        }
        $model = new Followers();
        $model->user_id = $user->id;
        $model->user_follower_id = $id;
        if ($model->save()) {
            return response()->json([
                "status" => "success",
                "message" => "you now follow " . $model->followersName->name,
            ], 200);
        }
    }

    /**
     * This route is used to unfollow a user
     * @function unfollow
     * @ulrParam string $id
     * @response status=200 scenario="success"{
     *  "status":"success",
     *  "message":"you have successfully unfollowed the above user"
     * }
     * @response status=400 scenario="error"{
     *  "status":"error",
     *  "message":"There was no user to unfollow"
     * }
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function unfollow($id)
    {
        $user = auth()->guard('sanctum')->user();
        $follower = Followers::where(['user_id' => $user->id, "user_follower_id" => $id])->first();
        if (empty($follower)) {
            return response()->json(["status" => "error", "message" => "There was no user to unfollow"], 400);
        }

        if ($follower->delete()) {
            return response()->json(['status' => "success", "message" => "you have successfully unfollowed the above user"], 200);
        }
    }

    /**
     * This route is used to unfollow a user
     * @function unfollow
     * @ulrParam string $id
     * @response status=200 scenario="success"{
     *  "status":"success",
     *  "message":"you have successfully unfollowed the above user"
     * }
     * @response status=400 scenario="error"{
     *  "status":"error",
     *  "message":"There was no user to unfollow"
     * }
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function following()
    {
    }

    /**
     * This route is used to unfollow a user
     * @function unfollow
     * @ulrParam string $id
     * @response status=200 scenario="success"{
     *  "status":"success",
     *  "message":"you have successfully unfollowed the above user"
     * }
     * @response status=400 scenario="error"{
     *  "status":"error",
     *  "message":"There was no user to unfollow"
     * }
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function followers($id)
    {
         // Find the user by their ID

         $user = auth()->guard('sanctum')->user();
        $followers = Followers::where(["user_id" => $loggedinuser->id])->get();
        return FollowersResource::collection($followers);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Get the user's followers with details
        $followers = $user->followers()->with('users')->get();

        return response()->json(['user' => $user, 'followers' => $followers]);
     
    }
}
