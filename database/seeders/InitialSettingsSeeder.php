<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class InitialSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cache::forget('setting_system_name');
        Cache::forget('setting_system_name_en');
        Cache::forget('setting_logo_path');
        Cache::forget('setting_footer_text');
        Cache::forget('setting_sms_mode');
        Cache::forget('setting_terms_conditions');

        $settings = [
            // Branding Group
            [
                'key' => 'system_name',
                'value' => 'AURA TAC',
                'group' => 'branding',
            ],
            [
                'key' => 'system_name_en',
                'value' => 'Maintenance System',
                'group' => 'branding',
            ],
            [
                'key' => 'logo_path',
                'value' => 'logo.png',
                'group' => 'branding',
            ],
            [
                'key' => 'footer_text',
                'value' => 'تصميم وتطوير S-Plus',
                'group' => 'branding',
            ],
            
            // SMS Group
            [
                'key' => 'sms_mode',
                'value' => 'test', // test or production
                'group' => 'sms',
            ],
            [
                'key' => 'twilio_sid',
                'value' => '',
                'group' => 'sms',
            ],
            [
                'key' => 'twilio_token',
                'value' => '',
                'group' => 'sms',
            ],
            [
                'key' => 'twilio_from',
                'value' => '',
                'group' => 'sms',
            ],

            // General Group
            [
                'key' => 'terms_conditions',
                'value' => '1. المركز غير مسؤول عن أي ذخيرة متبقية في السلاح.\n2. يتم استلام السلاح بموجب هذا الكرت.\n3. أقصى مدة بقاء للسلاح بعد الإصلاح هي 30 يوماً.',
                'group' => 'general',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
