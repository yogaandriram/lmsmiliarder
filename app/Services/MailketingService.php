<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MailketingService
{
    public function send(string $recipient, string $subject, string $content, ?string $fromName = null, ?string $fromEmail = null): bool
    {
        $apiToken = config('services.mailketing.api_token');
        $endpoint = config('services.mailketing.endpoint');
        $fromName = $fromName ?: config('services.mailketing.from_name');
        $fromEmail = $fromEmail ?: config('services.mailketing.from_email');

        if (!$apiToken) {
            Log::error('Mailketing API token is missing.');
            return false;
        }

        $payload = [
            'api_token' => $apiToken,
            'from_name' => $fromName,
            'from_email' => $fromEmail,
            'recipient' => $recipient,
            'subject' => $subject,
            'content' => $content,
        ];

        try {
            $response = Http::asForm()->post($endpoint, $payload);
            if ($response->ok()) {
                $json = $response->json();
                $status = $json['status'] ?? null;
                return strtolower((string)$status) === 'success';
            }
        } catch (\Throwable $e) {
            Log::error('Mailketing send error: '.$e->getMessage());
        }

        return false;
    }
}