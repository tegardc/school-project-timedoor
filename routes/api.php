<?php

use App\Http\Controllers\SchoolController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/school', [SchoolController::class, 'index']);
Route::post('/school', [SchoolController::class, 'store']);
Route::get('/school/{id}', [SchoolController::class, 'show']);
Route::put('/school/{id}', [SchoolController::class, 'update']);
Route::delete('/school/{id}', [SchoolController::class, 'destroy']);
