<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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

Route::controller(AuthController::class)->group(function () {
    Route::post("login", "login")->name("login");
    Route::post("register", "register")->name("register");
    Route::post("logout", "logout")->name("logout");
});

Route::controller(UserController::class)->group(function () {
    Route::get("user/index", "index")->middleware('can:index')->name("index");
    Route::get("user/show/{id}", "show")->middleware('can:show')->name("show");
    Route::put("user/update", "update")->middleware('can:update')->name("update");
    Route::delete("user/destroy/{id}", "destroy")->middleware('can:destroy')->name("destroy");
});
