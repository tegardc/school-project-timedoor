<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class BaseApiResource extends JsonResource
{
    protected string $customMessage = '';
    protected bool $success = true;

    public function withMessage(string $message, bool $success = true): static
    {
        $this->customMessage = $message;
        $this->success = $success;
        return $this;
    }

    public function with($request): array
    {
        return [
            'success' => $this->success,
            'message' => $this->customMessage,
        ];
    }
}

/**
 * Transform the resource into an array.
 *
 * @return array<string, mixed>
 */
