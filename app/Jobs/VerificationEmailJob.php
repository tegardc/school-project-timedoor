<?php

namespace App\Jobs;

use App\Services\SendEmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VerificationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    protected $email;
    protected $verificationLink;
    protected $userName;

    public function __construct(string $email, string $verificationLink, ?string $userName = null)
    {
        $this->email = $email;
        $this->verificationLink = $verificationLink;
        $this->userName = $userName;
    }

    public function handle(SendEmailService $emailService)
    {
        $result = $emailService->sendWithTemplate(
            email: $this->email,
            templateId: 'schoolpedia',
            parameters: [
                'verification_link' => $this->verificationLink,
                'user_name' => $this->userName ?? 'User'
            ]
        );

        if (!$result['success']) {
            throw new \Exception($result['message']);
        }

        Log::info('Verification email sent', ['email' => $this->email]);
    }

    public function failed(\Throwable $exception)
    {
        Log::error('Verification email job failed', [
            'email' => $this->email,
            'error' => $exception->getMessage()
        ]);
    }
}
