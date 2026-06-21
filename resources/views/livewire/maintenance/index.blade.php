<div class="space-y-6">
    @php
        $roleClass = [
            'warning'   => 'bg-warning-container text-on-warning-container',
            'primary'   => 'bg-primary-container text-on-primary-container',
            'tertiary'  => 'bg-tertiary-container text-on-tertiary-container',
            'success'   => 'bg-success-container text-on-success-container',
            'secondary' => 'bg-secondary-container text-on-secondary-container',
        ];
    @endphp

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md-card-elevated p-6">
        <div>
            <h1 class="text-headline text-on-surface">{{ __('messages.new_work_card') }}</h1>
            <p class="text-body text-on-surface-variant mt-1">{{ __('messages.manage_maintenance_workflow') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <span class="material-symbols-rounded absolute inset-y-0 start-3 flex items-center text-on-surface-variant pointer-events-none" style="font-size:20px">search</span>
                <input wire:model.live="search" type="text" class="md-field !h-11 ps-11 w-full md:w-64 rounded-md-md" placeholder="{{ __('messages.search_by_card_or_customer') }}">
            </div>
            <button wire:click="openModal" class="md-btn md-btn-filled">
                <span class="material-symbols-rounded" style="font-size:20px">add</span>
                {{ __('messages.open_new_card') }}
            </button>
        </div>
    </div>

    {{-- Cards grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($cards as $card)
            @php $meta = $card->statusMeta(); @endphp
            <div class="md-card-elevated p-5 flex flex-col">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <span class="md-status bg-surface-container text-on-surface-variant">#{{ $card->card_number }}</span>
                        <h3 class="text-title text-on-surface mt-2 truncate w-44">{{ $card->customer->full_name }}</h3>
                    </div>
                    <span class="md-status {{ $roleClass[$meta['role']] ?? $roleClass['secondary'] }}">{{ $meta['label'] }}</span>
                </div>

                <div class="md-card-filled p-3 flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-md-sm bg-surface flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-rounded" style="font-size:20px">precision_manufacturing</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-label-sm text-on-surface-variant uppercase">{{ $card->item->type }}</p>
                        <p class="text-label text-on-surface truncate">{{ $card->item->item_number }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 mt-auto border-t" style="border-color:var(--md-outline-variant)">
                    <div>
                        <span class="text-label-sm text-on-surface-variant">{{ __('messages.total_estimated') }}</span>
                        <p class="text-title text-primary">{{ number_format($card->total_cost, 2) }} <small class="text-label-sm">{{ __('messages.sar') }}</small></p>
                    </div>
                    <div class="flex gap-1">
                        <a href="{{ route('maintenance.print', $card->id) }}" target="_blank" class="md-icon-btn" title="كرت العمل">
                            <span class="material-symbols-rounded" style="font-size:20px">print</span>
                        </a>
                        <a href="{{ route('maintenance.print-repair', $card->id) }}" target="_blank" class="md-icon-btn" title="كرت الإصلاح">
                            <span class="material-symbols-rounded" style="font-size:20px">build_circle</span>
                        </a>
                        <button wire:click="edit({{ $card->id }})" class="md-icon-btn" title="تعديل">
                            <span class="material-symbols-rounded" style="font-size:20px">edit</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 md-card flex flex-col items-center justify-center text-on-surface-variant">
                <span class="material-symbols-rounded mb-3" style="font-size:56px">description</span>
                <p class="text-label uppercase tracking-widest">{{ __('messages.no_cards_found') }}</p>
            </div>
        @endforelse
    </div>

    @if($cards->hasPages())
        <div>{{ $cards->links() }}</div>
    @endif

    {{-- Reception drawer --}}
    @if($showModal)
        <div class="fixed inset-0 z-[60]" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-onyx/50 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
            <div class="fixed inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} max-w-full flex">
                <div class="w-screen max-w-lg">
                    <div class="h-full flex flex-col bg-surface shadow-md-4">
                        <div class="p-6 border-b flex items-center justify-between bg-surface-low" style="border-color:var(--md-outline-variant)">
                            <div>
                                <h2 class="text-title-lg text-on-surface">{{ $editingCardId ? __('messages.edit_work_card') : __('messages.open_new_card') }}</h2>
                                <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mt-1">{{ __('messages.reception_department') }}</p>
                            </div>
                            <button wire:click="$set('showModal', false)" class="md-icon-btn"><span class="material-symbols-rounded">close</span></button>
                        </div>

                        <div class="flex-1 overflow-y-auto px-6 py-6 space-y-7 custom-scrollbar">
                            {{-- 01 Customer & item --}}
                            <div class="space-y-4">
                                <span class="md-status bg-primary text-on-primary">01 · {{ __('messages.customer_and_item_info') }}</span>
                                <div class="relative">
                                    <input wire:model.live="customerSearch" type="text" class="md-field rounded-md-sm" placeholder="{{ __('messages.search_customer_placeholder') }}" @if($selectedCustomer) disabled @endif>
                                    @if($selectedCustomer)
                                        <button type="button" wire:click="resetForm" class="absolute inset-y-0 end-0 pe-4 flex items-center text-error"><span class="material-symbols-rounded" style="font-size:20px">close</span></button>
                                    @endif
                                    @if(count($customersList) > 0)
                                        <div class="absolute z-20 w-full mt-1 bg-surface border rounded-md-md shadow-md-3 overflow-hidden" style="border-color:var(--md-outline-variant)">
                                            @foreach($customersList as $cust)
                                                <button type="button" wire:click="selectCustomer({{ $cust->id }})" class="md-state w-full text-start px-4 py-3 flex items-center justify-between">
                                                    <div><p class="text-label text-on-surface">{{ $cust->full_name }}</p><p class="text-label-sm text-on-surface-variant">{{ $cust->phone }}</p></div>
                                                    <span class="material-symbols-rounded text-primary" style="font-size:20px">check</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                @if($selectedCustomer)
                                    <div>
                                        <label class="md-label">{{ __('messages.select_item_to_repair') }}</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            @forelse($customerItems as $item)
                                                <label class="cursor-pointer">
                                                    <input type="radio" wire:model="item_id" value="{{ $item->id }}" class="peer sr-only">
                                                    <div class="md-card-filled p-3 border-2 border-transparent peer-checked:border-primary peer-checked:bg-primary-container transition-all">
                                                        <p class="text-label text-on-surface">{{ $item->manufacturer }}</p>
                                                        <p class="text-label-sm text-on-surface-variant">SN: {{ $item->item_number }}</p>
                                                    </div>
                                                </label>
                                            @empty
                                                <div class="col-span-2 md-status bg-warning-container text-on-warning-container">{{ __('messages.no_items_for_this_customer') }}</div>
                                            @endforelse
                                        </div>
                                        @error('item_id') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                                @error('customer_id') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                            </div>

                            {{-- 02 Repair requests (official checklist) --}}
                            <div class="space-y-3">
                                <span class="md-status bg-primary text-on-primary">02 · {{ __('messages.repair_requests_points') }}</span>
                                <div class="grid grid-cols-1 gap-2">
                                    @foreach(\App\Models\MaintenanceCard::standardServices() as $label)
                                        <label class="md-state flex items-center gap-3 p-3 rounded-md-sm md-card-filled cursor-pointer">
                                            <input type="checkbox" wire:model="services" value="{{ $label }}" class="w-5 h-5 accent-[#8A6A3D]">
                                            <span class="text-label text-on-surface">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div>
                                    <label class="md-label">أخرى (طلبات إضافية)</label>
                                    <textarea wire:model="custom_request" rows="2" class="md-field" placeholder="اكتب أي طلبات إضافية، كل طلب في سطر"></textarea>
                                </div>
                            </div>

                            {{-- 03 Estimated cost --}}
                            <div class="space-y-3">
                                <span class="md-status bg-primary text-on-primary">03 · {{ __('messages.estimated_costs') }}</span>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="md-label">{{ __('messages.labor_cost') }}</label>
                                        <input wire:model.live="expected_cost_labor" type="number" class="md-field rounded-md-sm">
                                        @error('expected_cost_labor') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="md-label">{{ __('messages.parts_cost') }}</label>
                                        <input wire:model.live="expected_cost_parts" type="number" class="md-field rounded-md-sm">
                                        @error('expected_cost_parts') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="p-4 bg-onyx rounded-md-md flex items-center justify-between">
                                    <span class="text-label text-on-onyx-variant">{{ __('messages.total_estimated_amount') }}</span>
                                    <span class="text-title-lg text-primary tabular-nums">{{ number_format((float)$expected_cost_labor + (float)$expected_cost_parts, 2) }} <small class="text-label">{{ __('messages.sar') }}</small></span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 border-t bg-surface-low flex gap-3" style="border-color:var(--md-outline-variant)">
                            <button type="button" wire:click="save" class="md-btn md-btn-filled flex-1">{{ __('messages.save_and_open_card') }}</button>
                            <button wire:click="$set('showModal', false)" class="md-btn md-btn-outlined">{{ __('messages.cancel') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Toast --}}
    @if(session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed bottom-8 end-8 z-[100]">
            <div class="bg-onyx text-on-onyx px-5 py-4 rounded-md-md shadow-md-4 flex items-center gap-3">
                <span class="material-symbols-rounded text-success" style="font-size:22px">check_circle</span>
                <p class="text-label">{{ session('success') }}</p>
            </div>
        </div>
    @endif
</div>
