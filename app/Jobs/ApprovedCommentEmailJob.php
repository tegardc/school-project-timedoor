<?php

namespace App\Jobs;

use App\Services\SendEmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ApprovedCommentEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    protected $email;
    protected $fullname;
    protected $userComment;

    public function __construct(string $email, string $fullname, string $userComment)
    {
        $this->email = $email;
        $this->fullname = $fullname;
        $this->userComment = $userComment;
    }

    public function handle(SendEmailService $emailService)
    {
        $result = $emailService->sendWithTemplate(
            email: $this->email,
            templateId: 'approved_comment_ui',
            parameters: [
                'fullname' => $this->fullname,
                'user_comment' => $this->userComment ?? 'Tidak ada komentar dari pengguna.',
            ]
        );

        if (!$result['success']) {
            throw new \Exception($result['message']);
        }

        Log::info('Approved comment email sent', ['email' => $this->email]);
    }

    public function failed(\Throwable $exception)
    {
        Log::error('Approved comment email job failed', [
            'email' => $this->email,
            'error' => $exception->getMessage()
        ]);
    }
}
