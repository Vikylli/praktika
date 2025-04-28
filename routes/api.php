<?php

use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\LunarMissionController;
use App\Http\Controllers\SpacecraftController;
use App\Models\LunarMission;
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


Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [RegisterController::class, 'logout']);
});

Route::resource('spacecraft', SpacecraftController::class)->only(['index', 'store',  'update', 'destroy']);
Route::get('spacecraft/{id}', [SpacecraftController::class, 'show']);

// Route::post('/lunar_missions', [LunarMissionController::class, 'store']);
// Route::delete('lunar_missions/{id}', [LunarMissionController::class, 'destroy']);

Route::resource('lunar_missions', LunarMissionController::class);