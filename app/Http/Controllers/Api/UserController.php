<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(User::paginate(10)->load('files.tag.tag'));
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if(empty($user)){
            return response()->json(["status"=>"error","message"=>"No user was found with the above id ${id}"],400);
        }
        return new UserResource($user->load('files.tag.tag'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if(empty($user)){
            return response()->json(["status"=>"error","message"=>"No user was found with the above id ${id}"],400);
        }
        $user->delete();
        return response()->json(["status"=>"success","message"=>"The user with ${id} ID was deleted successfully"],200);
    }

    /**
     * This function is responsibloe for determining the total number of users who are registered on the systerm.
     *
     * @return response()->json()
     */
    public function totalUsers(){
        $totalUsers = User::count();
        return response()->json(["status" => "success", "data" => $totalUsers],200);
    }
}
