<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\DemandeController;
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


Route::post('/cours',[CoursController::class,'store'])->name('store.cours');
Route::get('/cours',[CoursController::class,'index'])->name('index.cours');
Route::get('/role/{role}/user/{user}/cours',[CoursController::class,'getCoursByRole'])->name('getCoursByRole.cours');
Route::get('/modules',[CoursController::class,'allModuleWithProf'])->name('allModuleWithProf');
Route::get('/cours/prof/{profId}',[CoursController::class,'getCoursByProf'])->name('getCoursByProf');
Route::get('/eleve/{profId}/cours',[CoursController::class,'getCoursByUser'])->name('getCoursByUser');

Route::post('/session',[SessionController::class,'store'])->name('store.session');
Route::get('/session',[SessionController::class,'index'])->name('index.session');
Route::get('/role/{role}/user/{user}/sessions',[SessionController::class,'getSessionsByRole'])->name('getSessionsByRole.cours');
Route::get('/session/prof/{profId}',[SessionController::class,'getSessionByProf'])->name('getSessionByProf.session');
Route::get('/eleve/{userId}/session',[SessionController::class,'getSessionsByUser'])->name('getSessionsByUser.session');
Route::get('/session/cancel/{id}',[SessionController::class,'cancelSession'])->name('cancelSession.session');
Route::get('/session/validated/{id}',[SessionController::class,'validateSession'])->name('validateSession.session');
Route::get('/session/invalidated/{id}',[SessionController::class,'invalidateSession'])->name('invalidateSession.session');
Route::put('/user/{id}/emargement',[SessionController::class,'emargement'])->name('emargement.session');

Route::get('/demande',[DemandeController::class,'index'])->name('index.demande');
Route::post('/demande',[DemandeController::class,'store'])->name('store.demande');

Route::get('/absences',[AbsenceController::class,'index'])->name('index.demande');


Route::get('/classes',[ClasseController::class,'index'])->name('index.classes');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
