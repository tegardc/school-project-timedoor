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
            $request->validate(['files.*' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048']]);
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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolGallery $schoolGallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolGallery $schoolGallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolGallery $schoolGallery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolGallery $schoolGallery)
    {
        //
    }
}
