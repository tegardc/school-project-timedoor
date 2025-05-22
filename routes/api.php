<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SchoolDetailController;
use App\Http\Controllers\SubdistrictController;
use App\Http\Controllers\UserController;
use App\Models\Province;
use App\Models\SubDistrict;
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


Route::middleware(['role:parent'])->group(function () {});
Route::middleware(['role:student'])->group(function () {});
Route::middleware('auth:sanctum', 'role:admin')->group(function () {

    Route::post('/school-details', [SchoolDetailController::class, 'store']);
    Route::get('/user-profile', [UserController::class, 'show']);
    Route::put('/user', [UserController::class, 'update']);
    Route::delete('/user', [UserController::class, 'destroy']);
});

Route::apiResource('school', SchoolController::class);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user-all', [UserController::class, 'index']);

// Route::get('/school', [SchoolController::class, 'index']);
// Route::post('/school', [SchoolController::class, 'store']);
// Route::get('/school/{id}', [SchoolController::class, 'show']);
// Route::put('/school/{id}', [SchoolController::class, 'update']);
// Route::delete('/school/{id}', [SchoolController::class, 'destroy']);

Route::get('/province', [ProvinceController::class, 'index']);
Route::get('/district', [DistrictController::class, 'index']);
Route::post('/district', [DistrictController::class, 'store']);
Route::get('/sub-district', [SubdistrictController::class, 'index']);

Route::get('/school-details', [SchoolDetailController::class, 'index']);

Route::get('/school-details/{id}', [SchoolDetailController::class, 'show']);
Route::put('/school-details/{id}', [SchoolDetailController::class, 'update']);
Route::delete('/school-details/{id}', [SchoolDetailController::class, 'destroy']);
