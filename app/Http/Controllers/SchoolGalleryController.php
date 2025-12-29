<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\SchoolGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolGalleryController extends Controller
{
    public function uploadFile(Request $request)
    {
        try {
            $request->validate(['files.*' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120']]); // SEMENTARA 5MB
            $uploadedFiles = [];
            foreach ($request->file('files') as $file) {
                if (!$file->isValid()) {
                    return response()->json([
                        'message' => 'File tidak valid',
                        'data' => null
                    ], 422);
                }
                $fileName = time() . '_' . uniqid();
                $resultFile = $file->storeAs('photos', "{$fileName}.{$file->extension()}", 'public');
                $baseUrl = Storage::url($resultFile);
                $uploadedFiles[] = $baseUrl;
            }
            return response()->json([
                'message' => 'Upload Files Success',
                'data' => ['urls' => $uploadedFiles]
            ], 200);
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops upload school image is failed ", $e, "[SCHOOL DELETED]: ");
        }
    }

}
