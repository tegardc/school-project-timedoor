<?php

namespace App\Services;

use NotificationAPI\NotificationAPI;
use Exception;
use Illuminate\Support\Facades\Log;

class SendEmailService
{
    protected $notificationapi;

    public function __construct()
    {
        $clientId = config('services.notificationapi.client_id');
        $secretKey = config('services.notificationapi.client_secret');

        if (empty($clientId) || empty($secretKey)) {
            throw new Exception('NotificationAPI credentials tidak ditemukan');
        }

        $this->notificationapi = new NotificationAPI($clientId, $secretKey);
    }

    public function sendWithTemplate(string $email, string $templateId, array $parameters = [])
    {
        try {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Format email tidak valid: ' . $email);
            }

            $response = $this->notificationapi->send([
                'type' => config('services.notificationapi.type'),
                'templateId' => $templateId,
                'to' => [
                    'id' => $email,
                    'email' => $email
                ],
                "parameters" => $parameters,
            ]);

            Log::info('Email berhasil dikirim', [
                'email' => $email,
                'templateId' => $templateId,
                'response' => $response
            ]);

            return [
                'success' => true,
                'message' => 'Email berhasil dikirim',
                'data' => $response
            ];
        } catch (Exception $e) {
            Log::error('Gagal mengirim email', [
                'email' => $email,
                'templateId' => $templateId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal mengirim email: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
}
