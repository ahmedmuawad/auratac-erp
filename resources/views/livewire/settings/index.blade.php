<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between md-card-elevated p-6">
        <div>
            <h1 class="text-headline text-on-surface">{{ __('messages.settings') }}</h1>
            <p class="text-body text-on-surface-variant mt-1">{{ __('messages.settings_sub') }}</p>
        </div>
        <button wire:click="saveSettings" wire:loading.attr="disabled" class="md-btn md-btn-filled">
            <span wire:loading.remove>{{ __('messages.save_all_changes') }}</span>
            <span wire:loading>{{ __('messages.saving') }}</span>
            <span wire:loading.remove class="material-symbols-rounded" style="font-size:20px">save</span>
        </button>
    </div>

    @if (session()->has('success'))
        <div class="md-status bg-success-container text-on-success-container w-full justify-center h-11">
            <span class="material-symbols-rounded" style="font-size:20px">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row gap-6">
        {{-- Tabs --}}
        <div class="md:w-60 space-y-2">
            <button wire:click="$set('activeTab', 'branding')" class="md-state w-full flex items-center gap-3 px-5 h-12 rounded-md-xl text-label {{ $activeTab == 'branding' ? 'bg-primary text-on-primary' : 'bg-surface text-on-surface-variant' }}">
                <span class="material-symbols-rounded" style="font-size:20px">palette</span> {{ __('messages.visual_identity') }}
            </button>
            <button wire:click="$set('activeTab', 'sms')" class="md-state w-full flex items-center gap-3 px-5 h-12 rounded-md-xl text-label {{ $activeTab == 'sms' ? 'bg-primary text-on-primary' : 'bg-surface text-on-surface-variant' }}">
                <span class="material-symbols-rounded" style="font-size:20px">sms</span> {{ __('messages.sms_settings') }}
            </button>
            <button wire:click="$set('activeTab', 'whatsapp')" class="md-state w-full flex items-center gap-3 px-5 h-12 rounded-md-xl text-label {{ $activeTab == 'whatsapp' ? 'bg-primary text-on-primary' : 'bg-surface text-on-surface-variant' }}">
                <span class="material-symbols-rounded" style="font-size:20px">chat</span> {{ __('messages.whatsapp_settings') }}
            </button>
            <button wire:click="$set('activeTab', 'general')" class="md-state w-full flex items-center gap-3 px-5 h-12 rounded-md-xl text-label {{ $activeTab == 'general' ? 'bg-primary text-on-primary' : 'bg-surface text-on-surface-variant' }}">
                <span class="material-symbols-rounded" style="font-size:20px">description</span> {{ __('messages.terms_printing') }}
            </button>
        </div>

        {{-- Content --}}
        <div class="flex-1 md-card-elevated p-6 min-h-[480px]">
            @if($activeTab == 'branding')
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="md-label">{{ __('messages.system_name_ar') }}</label>
                            <input wire:model="system_name" type="text" class="md-field rounded-md-sm">
                        </div>
                        <div>
                            <label class="md-label">{{ __('messages.system_name_en_label') }}</label>
                            <input wire:model="system_name_en" type="text" class="md-field rounded-md-sm" dir="ltr">
                        </div>
                    </div>
                    <div>
                        <label class="md-label">{{ __('messages.footer_text_label') }}</label>
                        <input wire:model="footer_text" type="text" class="md-field rounded-md-sm">
                    </div>
                    <div>
                        <label class="md-label">{{ __('messages.system_logo') }}</label>
                        <div class="flex items-center gap-6">
                            <div class="w-28 h-28 rounded-md-lg bg-surface-container border-2 border-dashed flex items-center justify-center overflow-hidden" style="border-color:var(--md-outline-variant)">
                                @if ($newLogo)
                                    <img src="{{ $newLogo->temporaryUrl() }}" class="w-full h-full object-contain">
                                @else
                                    <img src="{{ asset(get_setting('logo_path', 'logo.png')) }}" class="w-full h-full object-contain p-2">
                                @endif
                            </div>
                            <div class="space-y-3">
                                <input type="file" wire:model="newLogo" class="hidden" id="logo-upload">
                                <label for="logo-upload" class="md-btn md-btn-tonal cursor-pointer">
                                    <span class="material-symbols-rounded" style="font-size:20px">upload</span>
                                    {{ __('messages.choose_new_logo') }}
                                </label>
                                <p class="text-label-sm text-on-surface-variant">{{ __('messages.logo_hint') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($activeTab == 'sms')
                <div class="space-y-6">
                    <div class="p-5 bg-onyx rounded-md-md flex items-center justify-between gap-4">
                        <div>
                            <h4 class="text-title text-on-onyx">{{ __('messages.sms_mode_title') }}</h4>
                            <p class="text-label-sm text-on-onyx-variant mt-1">{{ __('messages.sms_mode_sub') }}</p>
                        </div>
                        <select wire:model.live="sms_mode" class="md-field !h-11 w-auto rounded-md-sm">
                            <option value="test">{{ __('messages.sms_test_mode') }}</option>
                            <option value="production">{{ __('messages.sms_prod_mode') }}</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-5 @if($sms_mode == 'test') opacity-40 pointer-events-none @endif">
                        <div>
                            <label class="md-label">Twilio Account SID</label>
                            <input wire:model="twilio_sid" type="text" class="md-field rounded-md-sm font-mono" dir="ltr">
                        </div>
                        <div>
                            <label class="md-label">Twilio Auth Token</label>
                            <input wire:model="twilio_token" type="password" class="md-field rounded-md-sm font-mono" dir="ltr">
                        </div>
                        <div class="col-span-2">
                            <label class="md-label">Twilio From Number</label>
                            <input wire:model="twilio_from" type="text" class="md-field rounded-md-sm font-mono" dir="ltr" placeholder="+1234567890">
                        </div>
                    </div>

                    <div class="md-card-filled p-5 space-y-3">
                        <h4 class="text-label text-on-surface flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span> {{ __('messages.test_real_link') }}
                        </h4>
                        <div class="flex items-end gap-3">
                            <div class="flex-1">
                                <label class="md-label">{{ __('messages.test_phone') }}</label>
                                <input wire:model="testPhone" type="text" class="md-field rounded-md-sm" dir="ltr" placeholder="+966xxxxxxxxx">
                            </div>
                            <button wire:click="sendTestSms" class="md-btn md-btn-tonal">{{ __('messages.send_test') }}</button>
                        </div>
                        @if (session()->has('sms_status')) <p class="text-label-sm text-success">{{ session('sms_status') }}</p> @endif
                        @if (session()->has('sms_error')) <p class="text-label-sm text-error">{{ session('sms_error') }}</p> @endif
                    </div>
                </div>

            @elseif($activeTab == 'whatsapp')
                <div class="space-y-6">
                    <div class="p-5 bg-onyx rounded-md-md flex items-center justify-between gap-4">
                        <div>
                            <h4 class="text-title text-on-onyx flex items-center gap-2"><span class="material-symbols-rounded text-primary">chat</span> {{ __('messages.whatsapp_evolution') }}</h4>
                            <p class="text-label-sm text-on-onyx-variant mt-1">{{ __('messages.whatsapp_sub') }}</p>
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="whatsapp_enabled" value="1" class="w-5 h-5 accent-[#8A6A3D]">
                            <span class="text-label text-on-onyx">{{ __('messages.enabled') }}</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="md-label">{{ __('messages.wa_api_url') }}</label>
                            <input wire:model="whatsapp_api_url" type="text" class="md-field rounded-md-sm font-mono" dir="ltr" placeholder="https://evolution.example.com">
                        </div>
                        <div>
                            <label class="md-label">{{ __('messages.wa_api_key') }}</label>
                            <input wire:model="whatsapp_api_key" type="password" class="md-field rounded-md-sm font-mono" dir="ltr" placeholder="API Key">
                        </div>
                        <div>
                            <label class="md-label">{{ __('messages.wa_instance') }}</label>
                            <input wire:model="whatsapp_instance" type="text" class="md-field rounded-md-sm font-mono" dir="ltr" placeholder="instance name">
                        </div>
                        <div>
                            <label class="md-label">{{ __('messages.wa_country_code') }}</label>
                            <input wire:model="whatsapp_country_code" type="text" class="md-field rounded-md-sm font-mono" dir="ltr" placeholder="966">
                        </div>
                        <div class="md:col-span-2">
                            <label class="md-label">{{ __('messages.wa_token') }}</label>
                            <input wire:model="whatsapp_token" type="password" class="md-field rounded-md-sm font-mono" dir="ltr" placeholder="Bearer token (optional)">
                        </div>
                    </div>

                    <div class="md-card-filled p-5 space-y-3">
                        <h4 class="text-label text-on-surface flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span> {{ __('messages.test_real_link') }}
                        </h4>
                        <div class="flex items-end gap-3">
                            <div class="flex-1">
                                <label class="md-label">{{ __('messages.test_phone') }}</label>
                                <input wire:model="waTestPhone" type="text" class="md-field rounded-md-sm" dir="ltr" placeholder="05xxxxxxxx">
                            </div>
                            <button wire:click="sendTestWhatsApp" class="md-btn md-btn-tonal">{{ __('messages.send_test') }}</button>
                        </div>
                        @if (session()->has('wa_status')) <p class="text-label-sm text-success">{{ session('wa_status') }}</p> @endif
                        @if (session()->has('wa_error')) <p class="text-label-sm text-error">{{ session('wa_error') }}</p> @endif
                        <p class="text-label-sm text-on-surface-variant">{{ __('messages.wa_hint') }}</p>
                    </div>
                </div>

            @elseif($activeTab == 'general')
                <div class="space-y-4">
                    <div>
                        <label class="md-label">{{ __('messages.terms_label') }}</label>
                        <textarea wire:model="terms_conditions" rows="10" class="md-field"></textarea>
                        <p class="text-label-sm text-on-surface-variant mt-1">{{ __('messages.terms_hint') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
