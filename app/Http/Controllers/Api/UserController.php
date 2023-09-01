<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FollowersResource;
use App\Http\Resources\UserResource;
use App\Models\Followers;
use Illuminate\Http\Request;
use App\Models\User;

/**
 * @group User management
 *
 * APIs for managing basic site requirments such as login, logout, registration etc
 */
class UserController extends Controller
{
    /**
     * @autenticated
     * This route is responsible for fetching a perticular logedin users basic data
     * @response {
     *  "data": {
     *      "name": "Frederic Hane",
     *      "email": "paul.tillman@example.org",
     *      "file": []
     *  }
     * }
     */
    public function index()
    {
        $loggedinuser = auth()->guard('sanctum')->user();
        if( !isset($loggedinuser))
        {
          return response()->json(['status'=>'error', 'message'=>'you dont have write and edit access',  'data' =>''],400);
        }
        $userId = $loggedinuser->id;
        //return UserResource::collection(User::paginate(10)->load('files.tag.tag'));
        return new UserResource(User::where(["id" => $userId])->first()->load('files.tag.tag'));
    }

    
    /**
     * This route is used by a user to destroy their account
     * @response {
     *  "status": "success",
     *  "message": "The user with 2 ID was deleted successfully"
     * s}
     */
    public function destroy()
    {
        $loggedinuser = auth()->guard('sanctum')->user();
        if( !isset($loggedinuser))
        {
          return response()->json(['status'=>'error', 'message'=>'you dont have write and edit access',  'data' =>''],400);
        }
        $id = $loggedinuser->id;
        $user = User::find($id);
        if (empty($user)) {
            return response()->json(["status" => "error", "message" => "No user was found with the above id ${id}"], 400);
        }
        $user->delete();
        return response()->json(["status" => "success", "message" => "The user with ${id} ID was deleted successfully"], 200);
    }

    /**
     * This route helped us to get all followers for a specific logged in user.
     * @response {
     *  "data": {
     *      "id": 1,
     *      "name": "Gavin Abbott",
     *      "image": ""
     *    }
     * }
     */
    public function  followers()
    {
        $loggedinuser = auth()->guard('sanctum')->user();
        $followers = Followers::where(["user_id" => $loggedinuser->id])->get();
        return FollowersResource::collection($followers);
    }

    /**
     * This route is used to fetch the total number of followerss a logged in user has
     * @response {
     *  "status": "success",
     *  "data": 5
     * }
     */
    public function totalFollowers()
    {
        $loggedinuser = auth()->guard('sanctum')->user();
        $followers = Followers::where(["user_id" => $loggedinuser->id])->count();
        return response()->json(["status" => "success", "data" => $followers ],200);
    }
}
