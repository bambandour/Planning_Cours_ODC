<?php

use App\Http\Controllers\AuthController;
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
Route::post('/import',[UserController::class,'import'])->name('import.user');

Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout']);
// Route::get('/user', [AuthController::class, 'user']);

Route::post('/cours',[CoursController::class,'store'])->name('store.cours');
Route::get('/cours',[CoursController::class,'index'])->name('index.cours');
Route::get('/modules',[CoursController::class,'allModuleWithProf'])->name('allModuleWithProf');
Route::get('/cours/prof/{profId}',[CoursController::class,'getCoursByProf'])->name('getCoursByProf');

Route::post('/session',[SessionController::class,'store'])->name('store.session');
Route::get('/session',[SessionController::class,'index'])->name('index.session');
Route::get('/session/prof/{profId}',[SessionController::class,'getSessionByProf'])->name('getSessionByProf.session');
Route::get('/session/cancel/{id}',[SessionController::class,'cancelSession'])->name('cancelSession.session');
Route::get('/session/validated/{id}',[SessionController::class,'validateSession'])->name('validateSession.session');
Route::get('/session/invalidated/{id}',[SessionController::class,'invalidateSession'])->name('invalidateSession.session');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
