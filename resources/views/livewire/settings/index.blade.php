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
            @if(auth()->user()->role === 'manager')
            <button wire:click="$set('activeTab', 'repair_services')" class="md-state w-full flex items-center gap-3 px-5 h-12 rounded-md-xl text-label {{ $activeTab == 'repair_services' ? 'bg-primary text-on-primary' : 'bg-surface text-on-surface-variant' }}">
                <span class="material-symbols-rounded" style="font-size:20px">build</span> {{ __('messages.repair_services_settings') }}
            </button>
            @endif
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

                    {{-- Login Background Image Upload --}}
                    <div class="border-t pt-6" style="border-color:var(--md-outline-variant)">
                        <label class="md-label">صورة خلفية صفحة الدخول (Login Hero Image)</label>
                        <div class="flex items-center gap-6 mt-2">
                            <div class="w-44 h-28 rounded-md-lg bg-surface-container border-2 border-dashed flex items-center justify-center overflow-hidden" style="border-color:var(--md-outline-variant)">
                                @if ($newLoginBg)
                                    <img src="{{ $newLoginBg->temporaryUrl() }}" class="w-full h-full object-cover">
                                @else
                                    @php $loginBg = get_setting('login_bg_image'); @endphp
                                    @if($loginBg && file_exists(public_path($loginBg)))
                                        <img src="{{ asset($loginBg) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="text-center p-2 text-on-surface-variant text-label-sm">
                                            <span class="material-symbols-rounded block mb-1">image</span>
                                            الصورة الافتراضية
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div class="space-y-3">
                                <input type="file" wire:model="newLoginBg" class="hidden" id="login-bg-upload" accept="image/*">
                                <label for="login-bg-upload" class="md-btn md-btn-tonal cursor-pointer">
                                    <span class="material-symbols-rounded" style="font-size:20px">upload_file</span>
                                    اختر صورة خلفية لصفحة الدخول
                                </label>
                                <p class="text-label-sm text-on-surface-variant">تظهر هذه الصورة في الجانب المخصص لصفحة دخول الموظفين (تساعد في عكس نشاط صيانة الأسلحة والتجهيزات).</p>
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
                        <div>
                            <label class="md-label">{{ __('messages.wa_min_gap') }}</label>
                            <input wire:model="whatsapp_min_gap_seconds" type="number" min="0" step="1" class="md-field rounded-md-sm font-mono" dir="ltr" placeholder="4">
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

                    {{-- Connection / QR / Logout --}}
                    <div class="md-card-filled p-5 space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-label text-on-surface flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary"></span> {{ __('messages.wa_connection') }}
                            </h4>
                            @if($waState)
                                <span class="md-status {{ $waState === 'open' ? 'bg-success-container text-on-success-container' : 'bg-warning-container text-on-warning-container' }}">{{ $waState }}</span>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <button wire:click="checkConnection" wire:loading.attr="disabled" class="md-btn md-btn-tonal">
                                <span class="material-symbols-rounded" style="font-size:18px">sync</span> {{ __('messages.wa_check_status') }}
                            </button>
                            <button wire:click="showQr" wire:loading.attr="disabled" class="md-btn md-btn-tonal">
                                <span class="material-symbols-rounded" style="font-size:18px">qr_code_2</span> {{ __('messages.wa_show_qr') }}
                            </button>
                            <button wire:click="logoutSession" wire:confirm="{{ __('messages.wa_logout_confirm') }}" class="md-btn md-btn-danger">
                                <span class="material-symbols-rounded" style="font-size:18px">logout</span> {{ __('messages.wa_logout_session') }}
                            </button>
                        </div>

                        <div wire:loading wire:target="checkConnection,showQr,logoutSession" class="text-label-sm text-primary">{{ __('messages.processing') }}</div>

                        @if($waQr)
                            <div class="text-center">
                                <img src="{{ \Illuminate\Support\Str::startsWith($waQr, 'data:') ? $waQr : 'data:image/png;base64,' . $waQr }}" alt="QR" class="mx-auto w-56 h-56 rounded-md-md border bg-white p-2" style="border-color:var(--md-outline-variant)">
                                <p class="text-label-sm text-on-surface-variant mt-2">{{ __('messages.wa_scan_qr_hint') }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Message log --}}
                    <div class="md-card-filled p-5">
                        <h4 class="text-label text-on-surface flex items-center gap-2 mb-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span> {{ __('messages.wa_log') }}
                        </h4>
                        <div class="space-y-1 max-h-72 overflow-y-auto custom-scrollbar">
                            @forelse($waLogs as $log)
                                @php $sc = $log->status === 'sent' ? 'bg-success-container text-on-success-container' : ($log->status === 'skipped' ? 'bg-surface-container text-on-surface-variant' : 'bg-error-container text-on-error-container'); @endphp
                                <div class="flex items-start gap-3 py-2 border-b last:border-0" style="border-color:var(--md-outline-variant)">
                                    <span class="md-status {{ $sc }} shrink-0">{{ $log->status }}</span>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-label text-on-surface" dir="ltr">{{ $log->recipient }} · {{ $log->type }}</p>
                                        @if($log->response)<p class="text-label-sm text-error">{{ $log->response }}</p>@endif
                                        <p class="text-label-sm text-on-surface-variant">{{ $log->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-label text-on-surface-variant py-4 text-center">{{ __('messages.wa_no_logs') }}</p>
                            @endforelse
                        </div>
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
            @elseif($activeTab == 'repair_services' && auth()->user()->role === 'manager')
                <div class="space-y-6">
                    <div>
                        <h3 class="text-title-medium text-on-surface font-semibold">{{ __('messages.repair_services_settings') }}</h3>
                        <p class="text-body-sm text-on-surface-variant mt-0.5">{{ __('messages.manage_repair_services') }}</p>
                    </div>

                    {{-- Add new service form --}}
                    <form wire:submit.prevent="addRepairService" class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end md-card-filled p-4 rounded-md-lg">
                        <div class="md:col-span-1">
                            <label class="md-label">كود الخدمة (مثال: SRV-01)</label>
                            <input wire:model="newServiceCode" type="text" class="md-field rounded-md-sm uppercase" placeholder="SRV-01">
                            @error('newServiceCode') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-1">
                            <label class="md-label">{{ __('messages.service_name') }}</label>
                            <input wire:model="newServiceLabel" type="text" class="md-field rounded-md-sm" placeholder="مثال: تنظيف وتجميع أجزاء السلاح">
                            @error('newServiceLabel') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="md-btn md-btn-filled h-11 md:col-span-1">
                            <span class="material-symbols-rounded">add</span>
                            <span>{{ __('messages.add_repair_service') }}</span>
                        </button>
                    </form>

                    {{-- Services List --}}
                    <div class="space-y-3">
                        @forelse($repairServices as $key => $item)
                            @php
                                $code = is_array($item) ? ($item['code'] ?? '') : '';
                                $label = is_array($item) ? ($item['name'] ?? '') : $item;
                            @endphp
                            <div class="flex items-center justify-between p-4 bg-surface-container rounded-md-lg border" style="border-color:var(--md-outline-variant)">
                                @if($editingServiceKey === $key)
                                    <div class="flex items-center gap-3 flex-1">
                                        <input wire:model="editingServiceCode" type="text" class="md-field rounded-md-sm w-32 uppercase" placeholder="الكود">
                                        <input wire:model="editingServiceLabel" type="text" class="md-field rounded-md-sm flex-1" placeholder="اسم الخدمة">
                                        <button type="button" wire:click="updateRepairService" class="md-btn md-btn-filled h-10 px-4">
                                            <span class="material-symbols-rounded" style="font-size:18px">check</span>
                                            <span>{{ __('messages.save') }}</span>
                                        </button>
                                        <button type="button" wire:click="cancelEditingService" class="md-btn md-btn-tonal h-10 px-4">
                                            <span>{{ __('messages.cancel') }}</span>
                                        </button>
                                    </div>
                                @else
                                    <div class="flex items-center gap-3">
                                        <span class="px-2.5 py-1 text-xs font-mono rounded bg-primary/10 text-primary font-bold">{{ $code }}</span>
                                        <span class="text-title-small text-on-surface font-medium">{{ $label }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" wire:click="editRepairService('{{ $key }}')" class="p-2 text-on-surface-variant hover:text-primary transition-colors">
                                            <span class="material-symbols-rounded" style="font-size:20px">edit</span>
                                        </button>
                                        <button type="button" wire:click="deleteRepairService('{{ $key }}')" wire:confirm="{{ __('messages.confirm_delete_service') }}" class="p-2 text-on-surface-variant hover:text-error transition-colors">
                                            <span class="material-symbols-rounded" style="font-size:20px">delete</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-on-surface-variant">
                                <span class="material-symbols-rounded text-4xl mb-2">info</span>
                                <p>{{ __('messages.no_repair_services') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
