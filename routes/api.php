<?php

use App\Http\Controllers\CoursController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::get('/users',[UserController::class,'index'])->name('index.user');

Route::post('/user',[UserController::class,'store'])->name('store.user');

Route::post('/cours',[CoursController::class,'store'])->name('store.cours');

Route::get('/cours',[CoursController::class,'index'])->name('index.cours');

Route::get('/modules',[CoursController::class,'allModuleWithProf'])->name('allModuleWithProf');

Route::post('/session',[SessionController::class,'store'])->name('store.session');


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
