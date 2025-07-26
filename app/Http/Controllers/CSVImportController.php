<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\CSVImportRequest;
use App\Http\Requests\CSVPreviewRequest;
use App\Services\CSVImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CSVImportController extends Controller
{
    protected CSVImportService $csvImport;

    public function __construct(CSVImportService $csvImport) {
        $this->csvImport = $csvImport;
    }
    public function previews(CSVPreviewRequest $request): JsonResponse
    {
        $data = $this->csvImport->preview($request->file('csv'));
        return response()->json(['data' => $data]);
    }

    public function imports(CSVImportRequest $request): JsonResponse
    {
        try {
            $this->csvImport->import($request->input('data'));
            return ResponseHelper::success('Data imported successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops import data is failed ", $e, "[SCHOOL IMPORT]: ");
        }
    }



    //
}
