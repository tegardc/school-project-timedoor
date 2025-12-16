    <?php

    use App\Helpers\ResponseHelper;
    use App\Http\Controllers\AccreditationController;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\CSVImportController;
    use App\Http\Controllers\DistrictController;
    use App\Http\Controllers\EducationExperienceController;
    use App\Http\Controllers\EducationLevelController;
    use App\Http\Controllers\EducationProgramController;
    use App\Http\Controllers\FacilityController;
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

    Route::get('/health', function () {
        return ResponseHelper::success([
            'status' => 'ok'
        ], "PRABOWO SAID: HIDUP JOKOWII!!!");
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource('education-experiences', EducationExperienceController::class);
        Route::get('/user', [UserController::class, 'show']);
        Route::put('/user', [UserController::class, 'update']);
        Route::delete('/user', [UserController::class, 'destroy']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/experiences-user', [EducationExperienceController::class, 'getExperienceByUser']);
        Route::post('/reviews/{schoolDetailId}', [ReviewController::class, 'store']);
        Route::post('/review/submit', [ReviewController::class, 'submitReview']);
        // Route::post('/reviews/{id}/like', [ReviewController::class, 'toggleLike']);
        Route::post('reviews/{reviewId}/like', [ReviewController::class, 'toggleLike']);

        // Cek apakah user sudah like review
        Route::get('reviews/{reviewId}/likes/check', [ReviewController::class, 'checkIfLiked']);

        // Upload image
        Route::post('/upload', [SchoolGalleryController::class, 'uploadFile']);

        //ROUTE FOR USER ROLE//
        Route::middleware(['check.role:student,parent'])->group(function () {

            Route::get('/questions', [QuestionController::class, 'index']);
            Route::get('/questions/{id}', [QuestionController::class, 'show']);

            Route::put('/reviews/{id}', [ReviewController::class, 'update']);
            Route::post('/school-details/save', [SchoolDetailController::class, 'saveSchool']);
            Route::get('/school-details/saved', [SchoolDetailController::class, 'showSaved']);
            Route::put('/profile/complete', [UserController::class, 'profileStore']);

            Route::get('/reviews/user', [ReviewController::class, 'getUserReviews']);
            Route::put('/reviews-user/{id}', [ReviewController::class, 'update']);
            Route::delete('/reviews-user/{id}', [ReviewController::class, 'deleteReviewForUser']);
        });

        //ROUTE FOR ADMIN ROLE//
        Route::middleware('check.role:admin')->group(function () {

            Route::get('/users', [UserController::class, 'index']);
            Route::delete('/users/{id}', [UserController::class, 'deleteUser']);

            // School & Detail
            Route::apiResource('schools', SchoolController::class)->except(['index', 'show']);
            Route::apiResource('school-details', SchoolDetailController::class)->except(['index', 'show']);
            Route::post('/school-details/featured', [SchoolDetailController::class, 'updateFeatured']);
            Route::post('/school-details/highlight', [SchoolDetailController::class, 'updateHighlight']);
            Route::post('reviews/{id}/toggle-pin', [ReviewController::class, 'togglePin']);

            Route::post('/schools/set-recommendation', [SchoolDetailController::class, 'setRecommendation']);
            Route::get('/schools/recommendation', [SchoolDetailController::class, 'getRecommendation']);




            // Master Data
            Route::apiResource('provinces', ProvinceController::class)->except(['index', 'show']);
            Route::apiResource('districts', DistrictController::class)->except(['index', 'show']);
            Route::apiResource('sub-districts', SubDistrictController::class)->except(['index', 'show']);
            Route::apiResource('education-programs', EducationProgramController::class);

            Route::delete('/education-levels/{id}', [EducationLevelController::class, 'delete']);
            Route::post('/school-status', [SchoolStatusController::class, 'store']);





            // Questions
            Route::apiResource('questions', QuestionController::class)->only(['store', 'update', 'destroy', 'trash', 'restore']);

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
                Route::get('/facilities', [FacilityController::class, 'trash']);
                Route::get('/education-programs', [EducationProgramController::class, 'trash']);
                Route::get('/education-experiences', [EducationExperienceController::class, 'trash']);
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
                Route::post('/facilities/{id}', [FacilityController::class, 'restore']);
                Route::post('/education-programs/{id}', [EducationProgramController::class, 'restore']);
                Route::post('/education-experiences/{id}', [EducationExperienceController::class, 'restore']);
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
    Route::get('/school-details/ranking', [SchoolDetailController::class, 'rankedSchools']);
    Route::get('/ranking/school-details', [SchoolDetailController::class, 'ranking']);
    Route::get('/schools-detail/{id}/reviews', [SchoolDetailController::class, 'showT']);
    Route::get('/schools/{id}/details', [SchoolDetailController::class, 'getSchoolDetailBySchoolId']);
    Route::get('/schools/{schoolDetailId}/reviews', [ReviewController::class, 'index']);
    Route::get('/school-status', [SchoolStatusController::class, 'index']);
    Route::get('/education-levels', [EducationLevelController::class, 'index']);
    Route::get('/accreditations', [AccreditationController::class, 'index']);
    Route::get('/reviews/recent', [ReviewController::class, 'recent']);
    Route::get('/school-detail/featured', [SchoolDetailController::class, 'featured']);
    Route::get('/school-detail/highlighted', [SchoolDetailController::class, 'highlight']);
    Route::get('/all-reviews', [ReviewController::class, 'getAllReview']);
    Route::get('/schools/top', [SchoolDetailController::class, 'topSchools']);
    Route::get('/schools/recommendations', [SchoolDetailController::class, 'recommendedSchools']);
    Route::get('/search-schools', [SchoolDetailController::class, 'searchByName']);


    // Wilayah
    Route::get('/provinces', [ProvinceController::class, 'index']);
    Route::get('/districts', [DistrictController::class, 'index']);
    Route::get('/sub-districts', [SubdistrictController::class, 'index']);
    Route::get('/provinces/{id}/districts', [DistrictController::class, 'getByProvince']);
    Route::get('/districts/{id}/sub-districts', [SubdistrictController::class, 'getByDistrict']);
    Route::get('/sub-districts/{id}/school-details', [SchoolDetailController::class, 'getBySubDistrict']);



    Route::prefix('facilities')->group(function () {
        Route::get('/', [FacilityController::class, 'index']);
        Route::post('/', [FacilityController::class, 'store']);
        Route::get('/{id}', [FacilityController::class, 'show']);
        Route::put('/{id}', [FacilityController::class, 'update']);
        Route::delete('/{id}', [FacilityController::class, 'destroy']);
        //     Route::get('/trash/all', [FacilityController::class, 'trash']);
        //     Route::patch('/restore/{id}', [FacilityController::class, 'restore']);
        // });
    });

    Route::get('/education-programs', [EducationProgramController::class, 'index']);

    //IMPORT CSV

    Route::prefix('csv')->group(function () {
        Route::post('/preview', [CSVImportController::class, 'previews']);
        Route::post('/import', [CsvImportController::class, 'imports']);
    });

    Route::get('reviews/{reviewId}/likes/count', [ReviewController::class, 'getLikesCount']);

    // Get list user yang like
    Route::get('reviews/{reviewId}/likes/users', [ReviewController::class, 'getUsersWhoLiked']);

    // Get review dengan likes terbanyak
    Route::get('reviews/most-liked', [ReviewController::class, 'getMostLiked']);
