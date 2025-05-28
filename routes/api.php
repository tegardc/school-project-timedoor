<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SchoolDetailController;
use App\Http\Controllers\SchoolGalleryController;
use App\Http\Controllers\SchoolImageController;
use App\Http\Controllers\SubdistrictController;
use App\Http\Controllers\UserController;
use App\Http\Requests\ReviewRequest;
use App\Models\Province;
use App\Models\SubDistrict;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Role;

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


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user', [UserController::class, 'update']);
    Route::delete('/user', [UserController::class, 'destroy']);

    Route::middleware(['check.role:student,parent'])->group(function () {
        Route::post('/review/{schoolDetailId}', [ReviewController::class, 'store']);
    });
    Route::middleware('check.role:admin')->group(function () {
        Route::post('/schools', [SchoolController::class, 'store']);
        Route::put('/school/{id}', [SchoolController::class, 'update']);
        Route::delete('/school/{id}', [SchoolController::class, 'destroy']);
        Route::get('/users', [UserController::class, 'index']);
        Route::put('/school-details/{id}', [SchoolDetailController::class, 'update']);
        Route::delete('/school-details/{id}', [SchoolDetailController::class, 'destroy']);
        Route::post('/school-details', [SchoolDetailController::class, 'store']);
        Route::put('/reviews/{id}/approve', [ReviewController::class, 'approve']);
        Route::put('/reviews/{id}/reject', [ReviewController::class, 'reject']);
        Route::get('/review/pending-reviews', [ReviewController::class, 'pendingReviews']);
        Route::get('/review/rejected-reviews', [ReviewController::class, 'rejectedReviews']);
        Route::get('/review/approved-reviews', [ReviewController::class, 'rejectedReviews']);
    });
});
Route::post('/upload', [SchoolGalleryController::class, 'uploadFile']);
Route::get('/review/{schoolDetailId}', [ReviewController::class, 'index']);
Route::delete('/review/{id}', [ReviewController::class, 'destroy']);
Route::apiResource('schools', SchoolController::class);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


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
Route::get('/school-details/ranking', [SchoolDetailController::class, 'ranking']);

Route::get('/school-details/{id}', [SchoolDetailController::class, 'show']);
