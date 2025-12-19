<?php

namespace App\Services;

use App\Models\User;
use App\Models\VerificationToken;
use Illuminate\Support\Str;

class TokenService
{
    public function createToken(User $user, int $type, int $minutes = 60): string
    {
        $tokenString = Str::random(20);

        VerificationToken::where('user_id', $user->id)
            ->where('type', $type)
            ->delete();

        VerificationToken::create([
            'user_id' => $user->id,
            'token' => $tokenString,
            'type' => $type,
            'expires_at' => now()->addMinutes($minutes),
            'is_used' => false
        ]);

        return $tokenString;
    }

    public function validateToken(string $tokenString, int $type): ?VerificationToken
    {
        $tokenData = VerificationToken::where('token', $tokenString)
            ->where('type', $type)
            ->valid()
            ->first();

        return $tokenData;
    }

    public function markAsUsed(VerificationToken $token): void
    {
        $token->update(['is_used' => true]);
    }
}
