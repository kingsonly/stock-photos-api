<?php

use App\Http\Controllers\Api\FollowersController;
use App\Http\Controllers\Api\CollectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SiteController;
use App\Http\Controllers\Api\UserController;

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

Route::group(['prefix' => 'followers', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/follow/{id}', [FollowersController::class, 'follow'])->name('follow');
    Route::get('/unfollow/{id}', [FollowersController::class, 'unfollow'])->name('unfollow');
});

// collections route
Route::group(['prefix' => 'collection', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [CollectionController::class, 'index'])->name('collections');
    Route::post('/', [CollectionController::class, 'store']);
    Route::get('/create', [CollectionController::class, 'create']);
    Route::put('/{id}', [CollectionController::class, 'update']);
    Route::get('/{id}', [CollectionController::class, 'show']);
    Route::put('/{id}/edit', [CollectionController::class, 'edit']);
    Route::delete('/{id}', [CollectionController::class, 'destroy']);
    Route::get('/search/{search}', [CollectionController::class, 'search']);
});

Route::group(['prefix' => 'user'], function () {

    Route::get("getuser/{id}", [UserController::class, 'show']);
});


