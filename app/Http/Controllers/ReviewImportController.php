<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper; // Asumsi kamu punya helper ini
use App\Http\Requests\ReviewImportRequest;
use App\Http\Requests\ReviewPreviewRequest;
use App\Services\ReviewImportService;
use Illuminate\Http\JsonResponse;

class ReviewImportController extends Controller
{
    protected ReviewImportService $reviewImport;

    public function __construct(ReviewImportService $reviewImport) {
        $this->reviewImport = $reviewImport;
    }

    /**
     * Langkah 1: Upload CSV dan dapatkan JSON Preview
     */
    public function previews(ReviewPreviewRequest $request): JsonResponse
    {
        try {
            $data = $this->reviewImport->preview($request->file('csv'));
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            // Error handling jika format CSV hancur
            return response()->json(['message' => 'Gagal membaca CSV', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Langkah 2: Kirim data JSON (yang sudah diedit/diconfirm user) untuk disimpan ke DB
     */
    public function imports(ReviewImportRequest $request): JsonResponse
    {
        try {
            $this->reviewImport->import($request->input('data'));
            return ResponseHelper::success('Google Reviews imported successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops import reviews failed ", $e, "[REVIEW IMPORT]: ");
        }
    }
}
