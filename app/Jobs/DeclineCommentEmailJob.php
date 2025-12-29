<?php

namespace App\Jobs;

use App\Services\SendEmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeclineCommentEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    protected $email;
    protected $fullname;
    protected $userComment;
    protected $adminReason;

    public function __construct(string $email, string $fullname, string $userComment, string $adminReason)
    {
        $this->email = $email;
        $this->fullname = $fullname;
        $this->userComment = $userComment;
        $this->adminReason = $adminReason;
    }

    public function handle(SendEmailService $emailService)
    {
        $result = $emailService->sendWithTemplate(
            email: $this->email,
            templateId: 'decline_comment_ui',
            parameters: [
                'fullname' => $this->fullname,
                'admin_reason' => $this->adminReason,
                'user_comment' => $this->userComment
            ]
        );

        if (!$result['success']) {
            throw new \Exception($result['message']);
        }

        Log::info('Decline comment email sent', ['email' => $this->email]);
    }

    public function failed(\Throwable $exception)
    {
        Log::error('Decline comment email job failed', [
            'email' => $this->email,
            'error' => $exception->getMessage()
        ]);
    }
}
