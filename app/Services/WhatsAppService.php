<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * WhatsApp notifications via Evolution API.
 * Configurable from Settings -> WhatsApp (url / api key / instance / token / country code).
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
     * Send using the saved settings (respects the enabled flag).
     */
    public function send(?string $phone, string $message): array
    {
        if (! $this->isConfigured()) {
            return ['success' => false, 'message' => 'WhatsApp not configured/enabled'];
        }

        return $this->dispatch([
            'url'      => get_setting('whatsapp_api_url'),
            'key'      => get_setting('whatsapp_api_key'),
            'instance' => get_setting('whatsapp_instance'),
            'token'    => get_setting('whatsapp_token'),
            'cc'       => get_setting('whatsapp_country_code', '966'),
        ], $phone, $message);
    }

    /**
     * Send using an explicit config (used by the Settings test button so it
     * works with the typed values regardless of cache).
     */
    public function sendWith(array $config, ?string $phone, string $message): array
    {
        if (blank($config['url'] ?? null) || blank($config['key'] ?? null) || blank($config['instance'] ?? null)) {
            return ['success' => false, 'message' => 'Missing URL / API key / instance'];
        }

        return $this->dispatch($config, $phone, $message);
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
     * Core HTTP call to Evolution API with retries.
     */
    private function dispatch(array $config, ?string $phone, string $message): array
    {
        if (blank($phone)) {
            return ['success' => false, 'message' => 'No phone number'];
        }

        $base = rtrim((string) $config['url'], '/');
        $instance = $config['instance'];
        $number = $this->normalize($phone, $config['cc'] ?? '966');

        $headers = [
            'apikey' => $config['key'],
            'Content-Type' => 'application/json',
        ];
        if (filled($config['token'] ?? null)) {
            $headers['Authorization'] = 'Bearer ' . $config['token'];
        }

        $lastError = 'unknown';
        for ($attempt = 1; $attempt <= 2; $attempt++) {
            try {
                $resp = Http::withHeaders($headers)
                    ->connectTimeout(7)
                    ->timeout(20)
                    ->acceptJson()
                    ->post("{$base}/message/sendText/{$instance}", [
                        'number' => $number,
                        'text' => $message,
                    ]);

                if ($resp->successful()) {
                    return ['success' => true, 'message' => 'Sent'];
                }

                $lastError = 'HTTP ' . $resp->status() . ': ' . $resp->body();
                Log::warning('WhatsApp send failed', ['attempt' => $attempt, 'status' => $resp->status(), 'body' => $resp->body()]);

                if (! in_array($resp->status(), [408, 429]) && $resp->status() < 500) {
                    break;
                }
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
                Log::error('WhatsApp error (attempt ' . $attempt . '): ' . $e->getMessage());
            }
        }

        return ['success' => false, 'message' => $lastError];
    }

    /**
     * Normalize a local number to international format (no +).
     */
    private function normalize(string $phone, string $cc = '966'): string
    {
        $p = preg_replace('/\D/', '', $phone);

        if (str_starts_with($p, '00')) {
            $p = substr($p, 2);
        }
        if (str_starts_with($p, '0')) {
            $p = $cc . substr($p, 1);
        } elseif (! str_starts_with($p, $cc) && strlen($p) <= 10) {
            $p = $cc . $p;
        }

        return $p;
    }
}
