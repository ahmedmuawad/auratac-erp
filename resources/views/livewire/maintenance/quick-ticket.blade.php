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
                        <input wire:model="item_serial" type="text" class="md-field rounded-md-sm">
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
            </div>

            {{-- Repair requests (official checklist) --}}
            <div class="md-card-elevated p-6 space-y-5">
                <h3 class="text-title-lg text-on-surface flex items-center gap-3 border-b pb-4" style="border-color:var(--md-outline-variant)">
                    <span class="w-9 h-9 rounded-md-sm bg-primary-container text-on-primary-container flex items-center justify-center">03</span>
                    {{ __('messages.repair_request') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach(\App\Models\MaintenanceCard::standardServices() as $label)
                        <label class="md-state flex items-center gap-3 p-3 rounded-md-sm md-card-filled cursor-pointer">
                            <input type="checkbox" wire:model="services" value="{{ $label }}" class="w-5 h-5 accent-[#8A6A3D]">
                            <span class="text-label text-on-surface">{{ $label }}</span>
                        </label>
                    @endforeach
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
            <div class="md-card-elevated p-6 space-y-5">
                <h4 class="text-label text-on-surface-variant uppercase tracking-widest border-b pb-3" style="border-color:var(--md-outline-variant)">{{ __('messages.financial_estimate_payment') }}</h4>

                <div class="p-4 bg-onyx rounded-md-md flex justify-between items-center">
                    <span class="text-label text-on-onyx-variant">{{ __('messages.total_estimate') }}</span>
                    <span class="text-title-lg text-primary tabular-nums">{{ number_format((float)($expected_cost_labor ?? 0) + (float)($expected_cost_parts ?? 0), 2) }} <small class="text-label">{{ __('messages.sar') }}</small></span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="md-label">{{ __('messages.hand_labor') }}</label>
                        <input wire:model.live="expected_cost_labor" type="number" class="md-field rounded-md-sm" placeholder="0.00">
                    </div>
                    <div>
                        <label class="md-label">{{ __('messages.parts_value') }}</label>
                        <input wire:model.live="expected_cost_parts" type="number" class="md-field rounded-md-sm" placeholder="0.00">
                    </div>
                </div>

                <div>
                    <label class="md-label text-primary">{{ __('messages.paid_on_account') }}</label>
                    <input wire:model.live="paid_amount" type="number" class="md-field rounded-md-sm text-center text-title-lg" placeholder="0.00">
                </div>

                @php
                    $total = (float)($expected_cost_labor ?? 0) + (float)($expected_cost_parts ?? 0);
                    $paid = (float)($paid_amount ?? 0);
                    $rem = $total - $paid;
                @endphp
                @if($rem > 0)
                    <div class="md-status bg-warning-container text-on-warning-container w-full justify-between">
                        <span>{{ __('messages.remaining_amount_label') }}</span>
                        <span class="tabular-nums">{{ number_format($rem, 2) }} {{ __('messages.sar') }}</span>
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
</div>
