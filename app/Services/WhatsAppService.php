<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * WhatsApp notifications via Evolution API.
 * Configurable from Settings -> WhatsApp (url / api key / instance / country code).
 */
class WhatsAppService
{
    public function isConfigured(): bool
    {
        return get_setting('whatsapp_enabled') == '1'
            && filled(get_setting('whatsapp_api_url'))
            && filled(get_setting('whatsapp_api_key'))
            && filled(get_setting('whatsapp_instance'));
    }

    /**
     * Send a text message. Returns ['success'=>bool, 'message'=>string].
     */
    public function send(?string $phone, string $message): array
    {
        if (! $this->isConfigured()) {
            return ['success' => false, 'message' => 'WhatsApp not configured/enabled'];
        }
        if (blank($phone)) {
            return ['success' => false, 'message' => 'No phone number'];
        }

        $base = rtrim(get_setting('whatsapp_api_url'), '/');
        $key = get_setting('whatsapp_api_key');
        $instance = get_setting('whatsapp_instance');
        $number = $this->normalize($phone);

        try {
            $resp = Http::withHeaders(['apikey' => $key])
                ->timeout(15)
                ->acceptJson()
                ->post("{$base}/message/sendText/{$instance}", [
                    'number' => $number,
                    'text' => $message,
                ]);

            if ($resp->successful()) {
                return ['success' => true, 'message' => 'Sent'];
            }

            Log::warning('WhatsApp send failed', ['status' => $resp->status(), 'body' => $resp->body()]);
            return ['success' => false, 'message' => 'HTTP ' . $resp->status() . ': ' . $resp->body()];
        } catch (\Throwable $e) {
            Log::error('WhatsApp error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Fire-and-forget: never breaks the request flow.
     */
    public function notify(?string $phone, string $message): void
    {
        try {
            if ($this->isConfigured()) {
                $this->send($phone, $message);
            }
        } catch (\Throwable $e) {
            Log::error('WhatsApp notify error: ' . $e->getMessage());
        }
    }

    /**
     * Normalize a Saudi/local number to international format (no +).
     */
    private function normalize(string $phone): string
    {
        $cc = get_setting('whatsapp_country_code', '966');
        $p = preg_replace('/\D/', '', $phone);

        if (str_starts_with($p, '00')) {
            $p = substr($p, 2);
        }
        if (str_starts_with($p, '0')) {
            $p = $cc . substr($p, 1);
        } elseif (! str_starts_with($p, $cc)) {
            $p = $cc . $p;
        }

        return $p;
    }
}
