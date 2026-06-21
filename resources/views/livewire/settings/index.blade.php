<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between md-card-elevated p-6">
        <div>
            <h1 class="text-headline text-on-surface">{{ __('messages.settings') }}</h1>
            <p class="text-body text-on-surface-variant mt-1">إدارة كافة إعدادات الهوية والربط التقني للنظام</p>
        </div>
        <button wire:click="saveSettings" wire:loading.attr="disabled" class="md-btn md-btn-filled">
            <span wire:loading.remove>حفظ كافة التغييرات</span>
            <span wire:loading>جاري الحفظ...</span>
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
                <span class="material-symbols-rounded" style="font-size:20px">palette</span> الهوية البصرية
            </button>
            <button wire:click="$set('activeTab', 'sms')" class="md-state w-full flex items-center gap-3 px-5 h-12 rounded-md-xl text-label {{ $activeTab == 'sms' ? 'bg-primary text-on-primary' : 'bg-surface text-on-surface-variant' }}">
                <span class="material-symbols-rounded" style="font-size:20px">sms</span> إعدادات SMS
            </button>
            <button wire:click="$set('activeTab', 'general')" class="md-state w-full flex items-center gap-3 px-5 h-12 rounded-md-xl text-label {{ $activeTab == 'general' ? 'bg-primary text-on-primary' : 'bg-surface text-on-surface-variant' }}">
                <span class="material-symbols-rounded" style="font-size:20px">description</span> الشروط والطباعة
            </button>
        </div>

        {{-- Content --}}
        <div class="flex-1 md-card-elevated p-6 min-h-[480px]">
            @if($activeTab == 'branding')
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="md-label">اسم النظام (عربي)</label>
                            <input wire:model="system_name" type="text" class="md-field rounded-md-sm">
                        </div>
                        <div>
                            <label class="md-label">اسم النظام (English)</label>
                            <input wire:model="system_name_en" type="text" class="md-field rounded-md-sm" dir="ltr">
                        </div>
                    </div>
                    <div>
                        <label class="md-label">نص الفوتر (Footer)</label>
                        <input wire:model="footer_text" type="text" class="md-field rounded-md-sm">
                    </div>
                    <div>
                        <label class="md-label">شعار النظام (Logo)</label>
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
                                    اختر شعاراً جديداً
                                </label>
                                <p class="text-label-sm text-on-surface-variant">يفضّل صورة شفافة (PNG) بمقاس 512×512.</p>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($activeTab == 'sms')
                <div class="space-y-6">
                    <div class="p-5 bg-onyx rounded-md-md flex items-center justify-between gap-4">
                        <div>
                            <h4 class="text-title text-on-onyx">وضع تشغيل الرسائل</h4>
                            <p class="text-label-sm text-on-onyx-variant mt-1">اختر بين التجربة والعمل الفعلي</p>
                        </div>
                        <select wire:model.live="sms_mode" class="md-field !h-11 w-auto rounded-md-sm">
                            <option value="test">الوضع التجريبي (كود 123456)</option>
                            <option value="production">الوضع الفعلي (Twilio)</option>
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
                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span> اختبار الربط الفعلي
                        </h4>
                        <div class="flex items-end gap-3">
                            <div class="flex-1">
                                <label class="md-label">رقم الجوال للاختبار</label>
                                <input wire:model="testPhone" type="text" class="md-field rounded-md-sm" dir="ltr" placeholder="+966xxxxxxxxx">
                            </div>
                            <button wire:click="sendTestSms" class="md-btn md-btn-tonal">إرسال تجربة</button>
                        </div>
                        @if (session()->has('sms_status')) <p class="text-label-sm text-success">{{ session('sms_status') }}</p> @endif
                        @if (session()->has('sms_error')) <p class="text-label-sm text-error">{{ session('sms_error') }}</p> @endif
                    </div>
                </div>

            @elseif($activeTab == 'general')
                <div class="space-y-4">
                    <div>
                        <label class="md-label">الشروط والأحكام (تظهر في كرت الاستلام)</label>
                        <textarea wire:model="terms_conditions" rows="10" class="md-field"></textarea>
                        <p class="text-label-sm text-on-surface-variant mt-1">استخدم سطراً جديداً لكل شرط لتظهر منظّمة في الطباعة.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
