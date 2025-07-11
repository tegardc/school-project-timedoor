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
            Route::post('/review/{schoolDetailId}', [ReviewController::class, 'store']);
             Route::get('/questions/{id}',[QuestionController::class,'show']);
             Route::get('/questions',[QuestionController::class,'index']);
        });

        //ROUTE FOR USER ROLE//
        Route::middleware('check.role:admin')->group(function () {
            Route::get('/users', [UserController::class, 'index']);
            Route::post('/schools', [SchoolController::class, 'store']);
            Route::put('/schools/{id}', [SchoolController::class, 'update']);
            Route::delete('/schools/{id}', [SchoolController::class, 'destroy']);
            Route::post('/school-details', [SchoolDetailController::class, 'store']);
            Route::put('/school-details/{id}', [SchoolDetailController::class, 'update']);
            Route::delete('/school-details/{id}', [SchoolDetailController::class, 'destroy']);
            Route::put('/reviews/{id}/approve', [ReviewController::class, 'approve']);
            Route::put('/reviews/{id}/reject', [ReviewController::class, 'reject']);
            Route::get('/review/pending-reviews', [ReviewController::class, 'pendingReviews']);
            Route::get('/review/rejected-reviews', [ReviewController::class, 'rejectedReviews']);
            Route::get('/review/approved-reviews', [ReviewController::class, 'approvedReviews']);
            // Route::apiResource('questions', \App\Http\Controllers\QuestionController::class);
            Route::put('/questions/{id}',[QuestionController::class,'update']);
            Route::delete('/questions/{id}',[QuestionController::class,'destroy']);


            Route::get('/sub-district/trash', [SubDistrictController::class, 'trash']);
            Route::post('/sub-district/{id}/restore', [SubDistrictController::class, 'restore']);
            Route::get('/district/trash', [DistrictController::class, 'trash']);
            Route::post('/district/{id}/restore', [DistrictController::class, 'restore']);
            Route::get('/province/trash', [ProvinceController::class, 'trash']);
            Route::post('/province/{id}/restore', [ProvinceController::class, 'restore']);
            Route::get('/school/trash', [SchoolController::class, 'trash']);
            Route::post('/school/{id}/restore', [SchoolController::class, 'restore']);
            Route::get('/school-detail/trash', [SchoolDetailController::class, 'trash']);
            Route::post('/school-detail/{id}/restore', [SchoolDetailController::class, 'restore']);
            Route::get('/user/trash', [UserController::class, 'trash']);
            Route::post('/user/{id}/restore', [UserController::class, 'restore']);
            Route::get('/question/trash', [QuestionController::class, 'trash']);
            Route::post('/question/{id}/restore', [QuestionController::class, 'restore']);
            Route::delete('/user/{id}', [UserController::class, 'deleteUser']);
            Route::apiResource('province', ProvinceController::class);
            Route::apiResource('sub-district', SubDistrictController::class);
            Route::apiResource('district', DistrictController::class);

        });
    });
    //ROUTE FOR EVERYONE//
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/schools', [SchoolController::class, 'index']);
    Route::post('/upload', [SchoolGalleryController::class, 'uploadFile']);
    Route::get('/review/{schoolDetailId}', [ReviewController::class, 'index']);
    // Route::delete('/review/{id}', [ReviewController::class, 'destroy']);
    // Route::apiResource('schools', SchoolController::class);

    //

    Route::get('/school-details/filter', [SchoolDetailController::class, 'filter']);

    Route::get('/provinces', [ProvinceController::class, 'index']);
    Route::get('/provinces/{provinceId}/districts', [DistrictController::class, 'getByProvince']);
    Route::get('/districts/{districtId}/sub-districts', [SubdistrictController::class, 'getByDistrict']);
    Route::get('/sub-districts/{subDistrictId}/school-details', [SchoolDetailController::class, 'getBySubDistrict']);

    // Route::get('/school', [SchoolController::class, 'index']);
    // Route::post('/school', [SchoolController::class, 'store']);
    // Route::get('/school/{id}', [SchoolController::class, 'show']);
    // Route::put('/school/{id}', [SchoolController::class, 'update']);
    // Route::delete('/school/{id}', [SchoolController::class, 'destroy']);


    Route::get('/province', [ProvinceController::class, 'index']);
    // Route::post('/province', [ProvinceController::class, 'store']);
    // Route::put('/province/{id}', [ProvinceController::class, 'update']);
    // Route::delete('/province/{id}', [ProvinceController::class, 'destroy']);
    Route::get('/district', [DistrictController::class, 'index']);
    // Route::post('/district', [DistrictController::class, 'store']);
    // Route::put('/district/{id}', [DistrictController::class, 'update']);
    // Route::delete('/district/{id}', [DistrictController::class, 'destroy']);

    Route::get('/sub-district', [SubdistrictController::class, 'index']);
    // Route::post('/sub-district', [SubdistrictController::class, 'store']);
    // Route::put('/sub-district/{id}', [SubdistrictController::class, 'update']);
    // Route::delete('/sub-district/{id}', [SubdistrictController::class, 'destroy']);

    Route::get('/school-details', [SchoolDetailController::class, 'index']);
    Route::get('/school-details/ranking', [SchoolDetailController::class, 'ranking']);

    Route::get('/school-details/{id}', [SchoolDetailController::class, 'show']);

    Route::get('/education-levels', [EducationLevelController::class, 'index']);
    Route::get('/education-levels/{id}', [EducationLevelController::class, 'show']);
    Route::get('/education-levels/{name}', [EducationLevelController::class, 'showByName']);

    Route::get('/accreditation', [AccreditationController::class, 'index']);
    Route::get('/accreditation/{id}', [AccreditationController::class, 'show']);

    Route::get('/school-status', [SchoolStatusController::class, 'index']);
    Route::get('/school-status/{id}', [SchoolStatusController::class, 'show']);

    Route::get('/school-detail/{schoolId}',[SchoolDetailController::class,'getSchoolDetailBySchoolId']);
