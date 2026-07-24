<div class="max-w-5xl mx-auto space-y-6 pb-16">
    {{-- Header --}}
    <div class="bg-onyx rounded-md-xl p-8 flex flex-col md:flex-row justify-between items-center gap-5">
        <div class="text-center md:text-start">
            <h1 class="text-headline text-on-onyx">{{ __('messages.quick_reception') }}</h1>
            <p class="text-body text-on-onyx-variant mt-1">{{ __('messages.quick_reception_sub') }}</p>
        </div>
        <div class="flex items-center gap-3 bg-white/5 px-4 py-3 rounded-md-md">
            <div class="text-end">
                <p class="text-label-sm text-on-onyx-variant uppercase tracking-widest">{{ __('messages.today_date') }}</p>
                <p class="text-title text-on-onyx">{{ date('Y/m/d') }}</p>
            </div>
            <div class="w-11 h-11 rounded-md-sm bg-primary flex items-center justify-center text-on-primary">
                <span class="material-symbols-rounded">event</span>
            </div>
        </div>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main column --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Customer --}}
            <div class="md-card-elevated p-6 space-y-5">
                <h3 class="text-title-lg text-on-surface flex items-center gap-3 border-b pb-4" style="border-color:var(--md-outline-variant)">
                    <span class="w-9 h-9 rounded-md-sm bg-primary-container text-on-primary-container flex items-center justify-center">01</span>
                    {{ __('messages.customer_data') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="md-label">{{ __('messages.mobile_smart_search') }}</label>
                        <input wire:model.live="customer_phone" type="text" class="md-field rounded-md-sm" placeholder="05xxxxxxxx">
                        @error('customer_phone') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="md-label">{{ __('messages.customer_full_name') }}</label>
                        <input wire:model="customer_name" type="text" class="md-field rounded-md-sm">
                        @error('customer_name') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="md-label">{{ __('messages.national_id_optional') }}</label>
                        <input wire:model="customer_national_id" type="text" class="md-field rounded-md-sm">
                    </div>
                </div>
            </div>

            {{-- Item --}}
            <div class="md-card-elevated p-6 space-y-5">
                <h3 class="text-title-lg text-on-surface flex items-center gap-3 border-b pb-4" style="border-color:var(--md-outline-variant)">
                    <span class="w-9 h-9 rounded-md-sm bg-primary-container text-on-primary-container flex items-center justify-center">02</span>
                    {{ __('messages.weapon_data') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="md-label">{{ __('messages.weapon_type') }}</label>
                        <input wire:model="item_name" type="text" class="md-field rounded-md-sm" placeholder="{{ __('messages.example_pistol') }}">
                        @error('item_name') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="md-label">{{ __('messages.serial_number') }}</label>
                        <input wire:model.live.debounce.300ms="item_serial" type="text" class="md-field rounded-md-sm">
                    </div>
                    <div>
                        <label class="md-label">{{ __('messages.brand_manufacturer') }}</label>
                        <input wire:model="item_brand" type="text" class="md-field rounded-md-sm">
                    </div>
                    <div>
                        <label class="md-label">{{ __('messages.license_if_any') }}</label>
                        <input wire:model="license_number" type="text" class="md-field rounded-md-sm">
                    </div>
                </div>

                @if($existing_item)
                    <div class="p-4 rounded-md-md flex items-start gap-3 border {{ $serial_owner_status === 'same_customer' ? 'bg-info-container text-on-info-container border-info' : 'bg-error-container text-on-error-container border-error' }}">
                        <span class="material-symbols-rounded shrink-0" style="font-size:24px">
                            {{ $serial_owner_status === 'same_customer' ? 'info' : 'warning' }}
                        </span>
                        <div class="space-y-1 text-body-sm">
                            @if($serial_owner_status === 'same_customer')
                                <p class="font-semibold">{{ __('messages.item_previously_registered_same_customer') }}</p>
                            @else
                                <p class="font-bold text-base">{{ __('messages.item_previously_registered_different_customer') }}</p>
                                <p class="font-medium">
                                    {{ __('messages.registered_owner') }}: <span class="underline font-bold">{{ $existing_owner_name }}</span> ({{ $existing_owner_phone }})
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Repair requests (official checklist) --}}
            <div class="md-card-elevated p-6 space-y-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b pb-4" style="border-color:var(--md-outline-variant)">
                    <h3 class="text-title-lg text-on-surface flex items-center gap-3">
                        <span class="w-9 h-9 rounded-md-sm bg-primary-container text-on-primary-container flex items-center justify-center">03</span>
                        {{ __('messages.repair_request') }}
                    </h3>
                    <div class="relative w-full md:w-72">
                        <input wire:model.live.debounce.150ms="service_search" type="text" class="md-field rounded-md-sm pr-9 text-sm" placeholder="🔍 بحث بالاسم أو الكود (مثال: SRV-01)">
                    </div>
                </div>

                @php
                    $allServices = \App\Models\MaintenanceCard::standardServices();
                    if (filled($service_search)) {
                        $q = mb_strtolower(trim($service_search));
                        $allServices = array_filter($allServices, function($s) use ($q) {
                            $code = is_array($s) ? ($s['code'] ?? '') : '';
                            $name = is_array($s) ? ($s['name'] ?? '') : $s;
                            return str_contains(mb_strtolower($name), $q) || str_contains(mb_strtolower($code), $q);
                        });
                    }
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @forelse($allServices as $key => $s)
                        @php
                            $code = is_array($s) ? ($s['code'] ?? '') : '';
                            $name = is_array($s) ? ($s['name'] ?? '') : $s;
                        @endphp
                        <label class="md-state flex items-center justify-between p-3 rounded-md-sm md-card-filled cursor-pointer">
                            <div class="flex items-center gap-3 min-w-0">
                                <input type="checkbox" wire:model="services" value="{{ $name }}" class="w-5 h-5 accent-[#8A6A3D] shrink-0">
                                <span class="text-label text-on-surface truncate">{{ $name }}</span>
                            </div>
                            @if($code)
                                <span class="px-2 py-0.5 text-xs font-mono rounded bg-primary/10 text-primary font-bold shrink-0">{{ $code }}</span>
                            @endif
                        </label>
                    @empty
                        <div class="md:col-span-2 text-center py-4 text-on-surface-variant text-label-sm">
                            لا توجد خدمات إصلاح مطابقة للبحث "{{ $service_search }}"
                        </div>
                    @endforelse
                </div>
                <div>
                    <label class="md-label">{{ __('messages.other_requests') }}</label>
                    <textarea wire:model="custom_request" rows="2" class="md-field" placeholder="{{ __('messages.other_requests_placeholder') }}"></textarea>
                </div>
            </div>
        </div>

        {{-- Sidebar column --}}
        <div class="space-y-6">
            {{-- Photo --}}
            <div class="md-card-elevated p-6 text-center space-y-4">
                <h4 class="text-label text-on-surface-variant uppercase tracking-widest">{{ __('messages.document_state_photo') }}</h4>
                <div class="relative group mx-auto w-44 h-44 rounded-md-lg bg-surface-container border-2 border-dashed flex items-center justify-center overflow-hidden" style="border-color:var(--md-outline-variant)">
                    @if ($item_photo)
                        <img src="{{ $item_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                        <button type="button" wire:click="$set('item_photo', null)" class="absolute inset-0 bg-onyx/50 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition-opacity">
                            <span class="material-symbols-rounded">delete</span>
                        </button>
                    @else
                        <label for="take-photo" class="cursor-pointer flex flex-col items-center gap-2 text-on-surface-variant">
                            <span class="material-symbols-rounded" style="font-size:36px">add_a_photo</span>
                            <span class="text-label-sm">{{ __('messages.tap_to_capture') }}</span>
                        </label>
                        <input type="file" wire:model="item_photo" class="hidden" id="take-photo" accept="image/*" capture="environment">
                    @endif
                </div>
                <div wire:loading wire:target="item_photo" class="text-label-sm text-primary">{{ __('messages.processing') }}</div>
                @error('item_photo') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
            </div>

            {{-- Financials --}}
            @php
                $subtotal = (float)($expected_cost_labor ?? 0) + (float)($expected_cost_parts ?? 0);
                $vatAmount = round($subtotal * 0.15, 2);
                $total = round($subtotal + $vatAmount, 2);
                $paid = (float)($paid_amount ?? 0);
                $rem = $total - $paid;
            @endphp
            <div class="md-card-elevated p-6 space-y-5">
                <h4 class="text-label text-on-surface-variant uppercase tracking-widest border-b pb-3" style="border-color:var(--md-outline-variant)">{{ __('messages.financial_estimate_payment') }}</h4>

                <div class="p-4 bg-onyx rounded-md-md space-y-2">
                    <div class="flex justify-between items-center text-on-onyx-variant text-label-sm">
                        <span>{{ __('messages.subtotal') ?? 'المجموع قبل الضريبة' }}</span>
                        <span class="tabular-nums font-mono">{{ number_format($subtotal, 2) }} {{ __('messages.sar') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-primary text-label-sm font-medium">
                        <span>{{ __('messages.vat_amount') ?? 'ضريبة القيمة المضافة (15%)' }}</span>
                        <span class="tabular-nums font-mono">+{{ number_format($vatAmount, 2) }} {{ __('messages.sar') }}</span>
                    </div>
                    <div class="border-t pt-2 flex justify-between items-center border-white/10">
                        <span class="text-label text-on-onyx font-bold">{{ __('messages.total_estimate') }} (شامل الضريبة 15%)</span>
                        <span class="text-title-lg text-primary tabular-nums font-bold">{{ number_format($total, 2) }} <small class="text-label">{{ __('messages.sar') }}</small></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="md-label">{{ __('messages.hand_labor') }}</label>
                        <input wire:model.live="expected_cost_labor" type="number" step="0.01" class="md-field rounded-md-sm" placeholder="0.00">
                    </div>
                    <div>
                        <label class="md-label">{{ __('messages.parts_value') }}</label>
                        <input wire:model.live="expected_cost_parts" type="number" step="0.01" class="md-field rounded-md-sm" placeholder="0.00">
                    </div>
                </div>

                <div>
                    <label class="md-label text-primary">{{ __('messages.paid_on_account') }}</label>
                    <input wire:model.live="paid_amount" type="number" step="0.01" class="md-field rounded-md-sm text-center text-title-lg" placeholder="0.00">
                </div>

                @if($rem > 0)
                    <div class="md-status bg-warning-container text-on-warning-container w-full justify-between">
                        <span>{{ __('messages.remaining_amount_label') }}</span>
                        <span class="tabular-nums font-bold">{{ number_format($rem, 2) }} {{ __('messages.sar') }}</span>
                    </div>
                @elseif($total > 0)
                    <div class="md-status bg-success-container text-on-success-container w-full justify-center gap-2">
                        <span class="material-symbols-rounded" style="font-size:18px">check_circle</span>
                        {{ __('messages.fully_paid') }}
                    </div>
                @endif
            </div>

            <button type="submit" wire:loading.attr="disabled" class="md-btn md-btn-filled w-full h-14 text-title">
                <span wire:loading.remove>{{ __('messages.save_and_print') }}</span>
                <span wire:loading>{{ __('messages.saving') }}</span>
                <span wire:loading.remove class="material-symbols-rounded">print</span>
            </button>
        </div>
    </form>

    {{-- OTP Verification Modal --}}
    @if($show_otp_modal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm animate-fade-in" style="background-color: rgba(26, 26, 26, 0.8);">
            <div class="md-card-elevated w-full max-w-md p-6 space-y-6 rounded-md-xl border shadow-2xl" style="border-color:var(--md-outline-variant); background-color: var(--md-surface, #ffffff);">
                <div class="flex items-center justify-between border-b pb-4" style="border-color:var(--md-outline-variant)">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-rounded">phonelink_ring</span>
                        </span>
                        <div>
                            <h3 class="text-title-medium text-on-surface font-bold">{{ __('messages.otp_modal_title') }}</h3>
                            <p class="text-label-sm text-on-surface-variant font-mono" dir="ltr">{{ $customer_phone }}</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeOtpModal" class="text-on-surface-variant hover:text-error transition-colors">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>

                <p class="text-body-sm text-on-surface-variant">
                    {{ __('messages.otp_modal_desc') }}
                </p>

                @if($otp_sent_message)
                    <div class="p-3 bg-success-container text-on-success-container rounded-md-sm text-label-sm flex items-center gap-2">
                        <span class="material-symbols-rounded" style="font-size:18px">check_circle</span>
                        <span>{{ $otp_sent_message }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="verifyOtpAndSave" class="space-y-5">
                    <div>
                        <label class="md-label text-center block mb-2">{{ __('messages.enter_otp_code') }}</label>
                        <input wire:model="entered_otp" type="text" maxlength="6" autofocus placeholder="• • • •" class="md-field text-center text-3xl font-mono tracking-widest rounded-md-md h-14 uppercase" style="letter-spacing: 0.4em;">
                        @if($otp_error)
                            <p class="text-label-sm text-error text-center mt-2 font-bold">{{ $otp_error }}</p>
                        @endif
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" wire:loading.attr="disabled" class="md-btn md-btn-filled w-full h-12 text-title-small font-bold">
                            <span wire:loading.remove>{{ __('messages.verify_and_confirm_receipt') }}</span>
                            <span wire:loading>{{ __('messages.verifying') }}</span>
                            <span wire:loading.remove class="material-symbols-rounded">check_circle</span>
                        </button>

                        <div class="flex items-center justify-between pt-2">
                            <button type="button" wire:click="resendOtp" class="text-label-sm text-primary hover:underline flex items-center gap-1">
                                <span class="material-symbols-rounded" style="font-size:16px">refresh</span>
                                <span>{{ __('messages.resend_otp') }}</span>
                            </button>

                            <button type="button" wire:click="closeOtpModal" class="text-label-sm text-on-surface-variant hover:underline">
                                {{ __('messages.cancel') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
