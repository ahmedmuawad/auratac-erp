<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use App\Services\SmsService;
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

    public function render()
    {
        return view('livewire.settings.index')
            ->layout('layouts.app');
    }
}
