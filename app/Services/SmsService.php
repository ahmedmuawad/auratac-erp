<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * إرسال رسالة نصية بناءً على وضع النظام (تجريبي/فعلي)
     */
    public function send($phone, $message)
    {
        $mode = get_setting('sms_mode', 'test');

        if ($mode === 'test') {
            Log::info("SMS TEST MODE: Sent to {$phone}. Message: {$message}");
            return [
                'success' => true,
                'message' => 'Test mode: SMS logged instead of sent.',
                'sid' => 'TEST_SID_123456'
            ];
        }

        // Production Mode (Twilio)
        try {
            $sid = get_setting('twilio_sid');
            $token = get_setting('twilio_token');
            $from = get_setting('twilio_from');

            if (!$sid || !$token || !$from) {
                return ['success' => false, 'message' => 'Twilio credentials missing.'];
            }

            $client = new Client($sid, $token);
            $response = $client->messages->create($phone, [
                'from' => $from,
                'body' => $message
            ]);

            return [
                'success' => true,
                'sid' => $response->sid
            ];

        } catch (\Exception $e) {
            Log::error("Twilio Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
