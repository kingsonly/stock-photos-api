<?php

use App\Http\Controllers\Api\FollowersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SiteController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\TagsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// site route
Route::group(['prefix' => 'site'], function () {
    Route::post("register", [SiteController::class, "register"]);
    Route::get('confirmemail/{code}', [SiteController::class, 'confirmemail'])->name('confirmemail');
    Route::post("login", [SiteController::class, "login"]);
    Route::post("sendpasswordresetlink", [SiteController::class, 'sendpasswordresetlink']);
    Route::post("recoverpassword/{id}", [SiteController::class, 'resetpassword']);
    Route::middleware('auth:sanctum')->group(function () {
    });
}); //->middlewareGroup();

// users route
Route::group(['prefix' => 'user', 'middleware' => ['auth:sanctum']], function () {
    //Route::middleware('auth:sanctum')->group( function () {
    Route::get('/', [UserController::class, 'index'])->name('users');
    Route::get("/deleteuser", [UserController::class, 'destroy']);
    Route::get("/followers", [UserController::class, 'followers']);
    Route::get("/totalfollowers", [UserController::class, 'totalFollowers']);
});

// Tag route
Route::group(['prefix' => 'tag', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [TagsController::class, 'show'])->name('tags');
    Route::get("/create", [TagsController::class, 'store']);
    Route::get("/followers", [TagsController::class, 'followers']);
    Route::get("/totalfollowers", [TagsController::class, 'totalFollowers']);
});

Route::group(['prefix' => 'followers', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/follow/{id}', [FollowersController::class, 'follow'])->name('follow');
    Route::get('/unfollow/{id}', [FollowersController::class, 'unfollow'])->name('unfollow');
});

Route::group(['prefix' => 'user'], function () {

    Route::get("getuser/{id}", [UserController::class, 'show']);
});


