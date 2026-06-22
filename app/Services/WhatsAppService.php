<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
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
     * Send a document (e.g. PDF) using saved settings.
     */
    public function sendDocument(?string $phone, string $pdfBytes, string $fileName, string $caption = ''): array
    {
        if (! $this->isConfigured()) {
            return ['success' => false, 'message' => 'WhatsApp not configured/enabled'];
        }
        if (blank($phone)) {
            return ['success' => false, 'message' => 'No phone number'];
        }

        $cc = get_setting('whatsapp_country_code', '966');
        $config = [
            'url' => get_setting('whatsapp_api_url'),
            'key' => get_setting('whatsapp_api_key'),
            'instance' => get_setting('whatsapp_instance'),
            'token' => get_setting('whatsapp_token'),
        ];

        $base = rtrim((string) $config['url'], '/');
        $payload = [
            'number' => $this->normalize($phone, $cc),
            'mediatype' => 'document',
            'mimetype' => 'application/pdf',
            'media' => base64_encode($pdfBytes),
            'fileName' => $fileName,
            'caption' => $caption,
        ];

        try {
            $this->throttle();
            $resp = Http::withHeaders($this->headers($config))
                ->connectTimeout(7)->timeout(40)->acceptJson()
                ->post("{$base}/message/sendMedia/{$config['instance']}", $payload);

            if ($resp->successful()) {
                return ['success' => true, 'message' => 'Sent'];
            }
            Log::warning('WhatsApp document failed', ['status' => $resp->status(), 'body' => $resp->body()]);
            return ['success' => false, 'message' => 'HTTP ' . $resp->status()];
        } catch (\Throwable $e) {
            Log::error('WhatsApp document error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Fire-and-forget text: never breaks the request flow.
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
     * Fire-and-forget document: never breaks the request flow.
     */
    public function notifyDocument(?string $phone, string $pdfBytes, string $fileName, string $caption = ''): void
    {
        try {
            if ($this->isConfigured()) {
                $this->sendDocument($phone, $pdfBytes, $fileName, $caption);
            }
        } catch (\Throwable $e) {
            Log::error('WhatsApp notifyDocument error: ' . $e->getMessage());
        }
    }

    /**
     * Instance connection state: open | connecting | close | null
     */
    public function connectionState(array $config): ?string
    {
        try {
            $resp = Http::withHeaders($this->headers($config))->timeout(15)
                ->get($this->endpoint($config, 'instance/connectionState'));
            return $resp->json('instance.state');
        } catch (\Throwable $e) {
            Log::error('WhatsApp state error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Connect / fetch QR. Returns ['state'=>?, 'qr'=>?base64].
     */
    public function connect(array $config): array
    {
        try {
            $resp = Http::withHeaders($this->headers($config))->timeout(20)
                ->get($this->endpoint($config, 'instance/connect'));
            $j = $resp->json() ?? [];

            $qr = $j['base64'] ?? ($j['qrcode']['base64'] ?? ($j['qr'] ?? null));
            $state = $j['instance']['state'] ?? null;

            return ['state' => $state, 'qr' => $qr];
        } catch (\Throwable $e) {
            Log::error('WhatsApp connect error: ' . $e->getMessage());
            return ['state' => null, 'qr' => null, 'error' => $e->getMessage()];
        }
    }

    /**
     * Logout / disconnect the instance session.
     */
    public function logout(array $config): bool
    {
        try {
            $resp = Http::withHeaders($this->headers($config))->timeout(15)
                ->delete($this->endpoint($config, 'instance/logout'));
            return $resp->successful();
        } catch (\Throwable $e) {
            Log::error('WhatsApp logout error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Space out sends with a small jittered gap to reduce spam heuristics.
     */
    private function throttle(): void
    {
        $min = (float) get_setting('whatsapp_min_gap_seconds', 4);
        if ($min <= 0) {
            return;
        }

        $last = (float) Cache::get('whatsapp:last_sent', 0);
        $elapsed = microtime(true) - $last;
        $wait = ($min - $elapsed) + (mt_rand(0, 1500) / 1000); // + 0..1.5s jitter
        $wait = max(0, min($wait, $min + 2));                  // cap

        if ($wait > 0) {
            usleep((int) ($wait * 1_000_000));
        }
        Cache::put('whatsapp:last_sent', microtime(true), 120);
    }

    private function headers(array $config): array
    {
        $h = ['apikey' => $config['key'] ?? '', 'Content-Type' => 'application/json'];
        if (filled($config['token'] ?? null)) {
            $h['Authorization'] = 'Bearer ' . $config['token'];
        }
        return $h;
    }

    private function endpoint(array $config, string $path): string
    {
        return rtrim((string) ($config['url'] ?? ''), '/') . '/' . $path . '/' . ($config['instance'] ?? '');
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

        $this->throttle();

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
