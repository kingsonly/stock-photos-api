<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("register",[SiteController::class,"register"]);
Route::get('confirmemail/{link}', [SiteController::class, 'confirmemail'])->name('confirmemail');
Route::post("login",[SiteController::class,"login"]);

// users route

Route::get('allusers', [UserController::class, 'index'])->name('allusers');
Route::get("getuser/{id}",[UserController::class,'show']);
Route::get("deleteuser/{id}",[UserController::class,'destroy']);
Route::get("totalusers",[UserController::class,'totalUsers']);