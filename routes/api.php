    <?php

use App\Helpers\ResponseHelper;
use App\Http\Controllers\AccreditationController;
use App\Http\Controllers\AuthController;
    use App\Http\Controllers\DistrictController;
use App\Http\Controllers\EducationLevelController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReviewController;
    use App\Http\Controllers\SchoolController;
    use App\Http\Controllers\SchoolDetailController;
    use App\Http\Controllers\SchoolGalleryController;
    use App\Http\Controllers\SchoolImageController;
use App\Http\Controllers\SchoolStatusController;
use App\Http\Controllers\SubdistrictController;
    use App\Http\Controllers\UserController;
    use App\Http\Requests\ReviewRequest;
    use App\Models\Province;
use App\Models\Review;
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
    Route::get('/health', function(){
        return ResponseHelper::success([
            'status' => 'ok'
        ], "PRABOWO SAID: HIDUP JOKOWII!!!");
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/user', [UserController::class, 'show']);
        Route::put('/user', [UserController::class, 'update']);
        Route::delete('/user', [UserController::class, 'destroy']);
        Route::post('/logout', [AuthController::class, 'logout']);

        //ROUTE FOR USER ROLE//
        Route::middleware(['check.role:student,parent'])->group(function () {
            Route::get('/questions', [QuestionController::class, 'index']);
            Route::get('/questions/{id}', [QuestionController::class, 'show']);
            Route::post('/reviews/{schoolDetailId}', [ReviewController::class, 'store']);
            Route::put('/reviews/{id}', [ReviewController::class, 'update']);

        });

        //ROUTE FOR ADMIN ROLE//
        Route::middleware('check.role:admin')->group(function () {
            Route::get('/users', [UserController::class, 'index']);
            Route::delete('/users/{id}', [UserController::class, 'deleteUser']);

                // School & Detail
            Route::apiResource('schools', SchoolController::class)->except(['index', 'show']);
            Route::apiResource('school-details', SchoolDetailController::class)->except(['index', 'show']);

            // Master Data
            Route::apiResource('provinces', ProvinceController::class)->except(['index', 'show']);
            Route::apiResource('districts', DistrictController::class)->except(['index', 'show']);
            Route::apiResource('sub-districts', SubDistrictController::class)->except(['index', 'show']);



            // Upload image
            Route::post('/upload', [SchoolGalleryController::class, 'uploadFile']);

            // Questions
            Route::apiResource('questions', QuestionController::class)->only(['store', 'update', 'destroy','trash', 'restore']);

            //Trash and Restore Route
            Route::prefix('trash')->group(function () {
            Route::get('/users', [UserController::class, 'trash']);
            Route::get('/schools', [SchoolController::class, 'trash']);
            Route::get('/school-details', [SchoolDetailController::class, 'trash']);
            Route::get('/districts', [DistrictController::class, 'trash']);
            Route::get('/sub-districts', [SubdistrictController::class, 'trash']);
            Route::get('/provinces', [ProvinceController::class, 'trash']);
            Route::get('/questions', [QuestionController::class, 'trash']);
            Route::get('/reviews', [ReviewController::class, 'trash']);
        });

        Route::prefix('restore')->group(function () {
            Route::post('/users/{id}', [UserController::class, 'restore']);
            Route::post('/schools/{id}', [SchoolController::class, 'restore']);
            Route::post('/school-details/{id}', [SchoolDetailController::class, 'restore']);
            Route::post('/districts/{id}', [DistrictController::class, 'restore']);
            Route::post('/sub-districts/{id}', [SubdistrictController::class, 'restore']);
            Route::post('/provinces/{id}', [ProvinceController::class, 'restore']);
            Route::post('/questions/{id}', [QuestionController::class, 'restore']);
            Route::post('/reviews/{id}', [ReviewController::class, 'restore']);
        });

        // Review Approval
            Route::get('/reviews/pending', [ReviewController::class, 'pendingReviews']);
            Route::get('/reviews/approved', [ReviewController::class, 'approvedReviews']);
            Route::get('/reviews/rejected', [ReviewController::class, 'rejectedReviews']);
            Route::put('/reviews/{id}/approve', [ReviewController::class, 'approve']);
            Route::put('/reviews/{id}/reject', [ReviewController::class, 'reject']);
            Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);


        });
            });

    //ROUTE FOR EVERYONE//
   // Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // School Browsing
    Route::get('/schools', [SchoolController::class, 'index']);
    Route::get('/school-details', [SchoolDetailController::class, 'index']);
    Route::get('/school-details/{id}', [SchoolDetailController::class, 'show']);
    Route::get('/school-details/ranking', [SchoolDetailController::class, 'ranking']);
    Route::get('/schools/{id}/details', [SchoolDetailController::class, 'getSchoolDetailBySchoolId']);
    Route::get('/schools/{schoolDetailId}/reviews', [ReviewController::class, 'index']);
    Route::get('/school-status', [SchoolStatusController::class, 'index']);
    Route::get('/education-levels', [EducationLevelController::class, 'index']);
    Route::get('/accreditations', [AccreditationController::class, 'index']);

    // Wilayah
    Route::get('/provinces', [ProvinceController::class, 'index']);
    Route::get('/districts', [DistrictController::class, 'index']);
    Route::get('/sub-districts', [SubdistrictController::class, 'index']);
    Route::get('/provinces/{id}/districts', [DistrictController::class, 'getByProvince']);
    Route::get('/districts/{id}/sub-districts', [SubdistrictController::class, 'getByDistrict']);
    Route::get('/sub-districts/{id}/school-details', [SchoolDetailController::class, 'getBySubDistrict']);
