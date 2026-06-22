<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use App\Services\SmsService;
use App\Services\WhatsAppService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Cache;

class Index extends Component
{
    use WithFileUploads;

    public $activeTab = 'branding';
    
    // Branding Settings
    public $system_name, $system_name_en, $footer_text;
    public $newLogo;

    // SMS Settings
    public $sms_mode, $twilio_sid, $twilio_token, $twilio_from;
    public $testPhone;

    // WhatsApp (Evolution API) Settings
    public $whatsapp_enabled, $whatsapp_api_url, $whatsapp_api_key, $whatsapp_instance, $whatsapp_token, $whatsapp_country_code;
    public $waTestPhone;
    public $waState = null;   // open | connecting | close | null
    public $waQr = null;      // base64 QR image

    // General Settings
    public $terms_conditions;

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->system_name = get_setting('system_name');
        $this->system_name_en = get_setting('system_name_en');
        $this->footer_text = get_setting('footer_text');
        $this->sms_mode = get_setting('sms_mode', 'test');
        $this->twilio_sid = get_setting('twilio_sid');
        $this->twilio_token = get_setting('twilio_token');
        $this->twilio_from = get_setting('twilio_from');
        $this->whatsapp_enabled = get_setting('whatsapp_enabled', '0');
        $this->whatsapp_api_url = get_setting('whatsapp_api_url');
        $this->whatsapp_api_key = get_setting('whatsapp_api_key');
        $this->whatsapp_instance = get_setting('whatsapp_instance');
        $this->whatsapp_token = get_setting('whatsapp_token');
        $this->whatsapp_country_code = get_setting('whatsapp_country_code', '966');
        $this->terms_conditions = get_setting('terms_conditions');
    }

    public function saveSettings()
    {
        $data = [
            'system_name' => $this->system_name,
            'system_name_en' => $this->system_name_en,
            'footer_text' => $this->footer_text,
            'sms_mode' => $this->sms_mode,
            'twilio_sid' => $this->twilio_sid,
            'twilio_token' => $this->twilio_token,
            'twilio_from' => $this->twilio_from,
            'whatsapp_enabled' => $this->whatsapp_enabled ? '1' : '0',
            'whatsapp_api_url' => $this->whatsapp_api_url,
            'whatsapp_api_key' => $this->whatsapp_api_key,
            'whatsapp_instance' => $this->whatsapp_instance,
            'whatsapp_token' => $this->whatsapp_token,
            'whatsapp_country_code' => $this->whatsapp_country_code ?: '966',
            'terms_conditions' => $this->terms_conditions,
        ];

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            Cache::forget('setting_' . $key);
        }

        if ($this->newLogo) {
            $path = $this->newLogo->store('branding', 'public');
            Setting::updateOrCreate(['key' => 'logo_path'], ['value' => 'storage/' . $path]);
            Cache::forget('setting_logo_path');
        }

        session()->flash('success', __('messages.settings_updated_success'));
        return redirect()->route('settings.index');
    }

    public function sendTestSms()
    {
        $this->validate(['testPhone' => 'required']);
        $sms = new SmsService();
        $result = $sms->send($this->testPhone, "رسالة تجريبية من نظام " . $this->system_name);

        if ($result['success']) {
            session()->flash('sms_status', 'تم إرسال الرسالة التجريبية بنجاح!');
        } else {
            session()->flash('sms_error', 'فشل الإرسال: ' . $result['message']);
        }
    }

    public function sendTestWhatsApp()
    {
        $this->validate(['waTestPhone' => 'required']);

        // persist current WhatsApp settings first so the test uses them
        foreach ([
            'whatsapp_enabled' => $this->whatsapp_enabled ? '1' : '0',
            'whatsapp_api_url' => $this->whatsapp_api_url,
            'whatsapp_api_key' => $this->whatsapp_api_key,
            'whatsapp_instance' => $this->whatsapp_instance,
            'whatsapp_token' => $this->whatsapp_token,
            'whatsapp_country_code' => $this->whatsapp_country_code ?: '966',
        ] as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            Cache::forget('setting_' . $key);
        }

        $result = (new WhatsAppService())->sendWith([
            'url'      => $this->whatsapp_api_url,
            'key'      => $this->whatsapp_api_key,
            'instance' => $this->whatsapp_instance,
            'token'    => $this->whatsapp_token,
            'cc'       => $this->whatsapp_country_code ?: '966',
        ], $this->waTestPhone, 'رسالة تجريبية من نظام Aura Tac عبر واتساب ✅');

        if ($result['success']) {
            session()->flash('wa_status', __('messages.wa_test_sent'));
        } else {
            session()->flash('wa_error', $result['message']);
        }
    }

    protected function waConfig(): array
    {
        return [
            'url'      => $this->whatsapp_api_url,
            'key'      => $this->whatsapp_api_key,
            'instance' => $this->whatsapp_instance,
            'token'    => $this->whatsapp_token,
            'cc'       => $this->whatsapp_country_code ?: '966',
        ];
    }

    public function checkConnection()
    {
        $this->waQr = null;
        $this->waState = (new WhatsAppService())->connectionState($this->waConfig());
        if (! $this->waState) {
            session()->flash('wa_error', __('messages.wa_check_failed'));
        }
    }

    public function showQr()
    {
        $res = (new WhatsAppService())->connect($this->waConfig());
        $this->waState = $res['state'] ?? $this->waState;
        $this->waQr = $res['qr'] ?? null;

        if (! $this->waQr && ($this->waState === 'open')) {
            session()->flash('wa_status', __('messages.wa_already_connected'));
        } elseif (! $this->waQr) {
            session()->flash('wa_error', __('messages.wa_qr_failed'));
        }
    }

    public function logoutSession()
    {
        $ok = (new WhatsAppService())->logout($this->waConfig());
        $this->waQr = null;
        $this->waState = $ok ? 'close' : $this->waState;
        session()->flash($ok ? 'wa_status' : 'wa_error', $ok ? __('messages.wa_logged_out') : __('messages.wa_logout_failed'));
    }

    public function render()
    {
        return view('livewire.settings.index')
            ->layout('layouts.app');
    }
}
