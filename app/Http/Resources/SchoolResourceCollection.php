<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SchoolResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return SchoolResource::collection($this->collection)->resolve();
    }
    public function toResponse($request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Display Data Success',
            'data' => $this->collection,
        ]);
    }
}
