<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md-card-elevated p-6">
        <div>
            <h1 class="text-headline text-on-surface flex items-center gap-2">
                <span class="material-symbols-rounded text-primary" style="font-size:28px">local_shipping</span>
                {{ __('messages.delivery_handover') }}
            </h1>
            <p class="text-body text-on-surface-variant mt-1">{{ __('messages.manage_final_delivery_and_costs') }}</p>
        </div>
        <div class="relative">
            <span class="material-symbols-rounded absolute inset-y-0 start-3 flex items-center text-on-surface-variant pointer-events-none" style="font-size:20px">search</span>
            <input wire:model.live="search" type="text" class="md-field !h-11 ps-11 w-full md:w-80 rounded-md-md" placeholder="{{ __('messages.search_by_card_or_customer') }}">
        </div>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($cards as $card)
            <div class="md-card-elevated overflow-hidden flex flex-col">
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="md-status bg-surface-container text-on-surface-variant">{{ $card->card_number }}</span>
                            <h3 class="text-title-lg text-on-surface mt-2">{{ $card->customer->full_name }}</h3>
                        </div>
                        <div class="w-11 h-11 rounded-md-md bg-success-container flex items-center justify-center" style="color:var(--md-on-success-container)">
                            <span class="material-symbols-rounded">check_circle</span>
                        </div>
                    </div>

                    <div class="md-card-filled p-3 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-md-sm bg-surface flex items-center justify-center text-primary shrink-0">
                            <span class="material-symbols-rounded" style="font-size:20px">precision_manufacturing</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-label-sm text-on-surface-variant uppercase">{{ $card->item->type }}</p>
                            <p class="text-label text-on-surface truncate">{{ $card->item->item_number }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-3 border-t" style="border-color:var(--md-outline-variant)">
                        <span class="text-label text-on-surface-variant">{{ __('messages.estimated_cost') }}</span>
                        <span class="text-title text-primary">{{ number_format($card->total_cost, 2) }} {{ __('messages.sar') }}</span>
                    </div>
                </div>

                <button wire:click="openDeliveryModal({{ $card->id }})" class="md-state p-4 bg-primary text-on-primary text-center text-label border-t" style="border-color:var(--md-outline-variant)">
                    {{ __('messages.proceed_to_delivery') }}
                </button>
            </div>
        @empty
            <div class="col-span-full py-20 md-card flex flex-col items-center justify-center text-on-surface-variant">
                <span class="material-symbols-rounded mb-3" style="font-size:56px">inventory_2</span>
                <p class="text-label uppercase tracking-widest">{{ __('messages.no_ready_for_delivery') }}</p>
            </div>
        @endforelse
    </div>

    @if($cards->hasPages())
        <div>{{ $cards->links() }}</div>
    @endif

    {{-- Delivery modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-[60]" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-onyx/50 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
            <div class="fixed inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} max-w-full flex">
                <div class="w-screen max-w-md">
                    <div class="h-full flex flex-col bg-surface shadow-md-4">
                        <div class="p-6 border-b flex items-center justify-between bg-surface-low" style="border-color:var(--md-outline-variant)">
                            <h2 class="text-title-lg text-on-surface">{{ __('messages.close_card_delivery') }}</h2>
                            <button wire:click="$set('showModal', false)" class="md-icon-btn"><span class="material-symbols-rounded">close</span></button>
                        </div>

                        <div class="flex-1 overflow-y-auto px-6 py-6 space-y-6 custom-scrollbar">
                            <div class="space-y-4">
                                <p class="text-label-sm text-on-surface-variant uppercase tracking-widest border-b pb-2" style="border-color:var(--md-outline-variant)">{{ __('messages.final_costs_collection') }}</p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="md-label">{{ __('messages.final_hand_labor') }}</label>
                                        <input wire:model.live="final_labor_cost" type="number" class="md-field rounded-md-sm">
                                        @error('final_labor_cost') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="md-label">{{ __('messages.final_parts_value') }}</label>
                                        <input wire:model.live="final_parts_cost" type="number" class="md-field rounded-md-sm">
                                        @error('final_parts_cost') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="bg-onyx p-5 rounded-md-md space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-label text-on-onyx-variant">{{ __('messages.final_total') }}</span>
                                        <span class="text-title-lg text-primary">{{ number_format($final_total_cost, 2) }}</span>
                                    </div>
                                    @if($payment_status !== 'paid')
                                        <div class="flex justify-between items-center pt-2 border-t border-white/10">
                                            <span class="text-label text-on-onyx-variant">{{ __('messages.amount_paid') }}</span>
                                            <span class="text-title text-success">{{ number_format($paid_amount, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between items-center pt-2 border-t border-white/10">
                                            <span class="text-label text-error">{{ __('messages.remaining_debt') }}</span>
                                            <span class="text-title text-error">{{ number_format($remaining_amount, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="text-label-sm text-on-surface-variant uppercase tracking-widest border-b pb-2 block" style="border-color:var(--md-outline-variant)">{{ __('messages.payment_status_label') }}</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <button wire:click="$set('payment_status', 'paid')" class="md-state py-3 rounded-md-sm text-label-sm border-2 {{ $payment_status == 'paid' ? 'bg-success-container text-on-success-container' : 'text-on-surface-variant' }}" style="border-color:{{ $payment_status == 'paid' ? 'var(--md-success)' : 'var(--md-outline-variant)' }}">{{ __('messages.paid_full') }}</button>
                                    <button wire:click="$set('payment_status', 'partial')" class="md-state py-3 rounded-md-sm text-label-sm border-2 {{ $payment_status == 'partial' ? 'bg-warning-container text-on-warning-container' : 'text-on-surface-variant' }}" style="border-color:{{ $payment_status == 'partial' ? 'var(--md-warning)' : 'var(--md-outline-variant)' }}">{{ __('messages.partial_payment') }}</button>
                                    <button wire:click="$set('payment_status', 'unpaid')" class="md-state py-3 rounded-md-sm text-label-sm border-2 {{ $payment_status == 'unpaid' ? 'bg-error-container text-on-error-container' : 'text-on-surface-variant' }}" style="border-color:{{ $payment_status == 'unpaid' ? 'var(--md-error)' : 'var(--md-outline-variant)' }}">{{ __('messages.unpaid') }}</button>
                                </div>

                                @if($payment_status === 'partial')
                                    <div>
                                        <label class="md-label">{{ __('messages.amount_paid_now') }}</label>
                                        <input wire:model.live="paid_amount" type="number" step="0.01" class="md-field rounded-md-sm" placeholder="0.00">
                                    </div>
                                @endif
                                <textarea wire:model="delivery_notes" placeholder="{{ __('messages.delivery_notes_placeholder') }}" rows="3" class="md-field"></textarea>
                            </div>
                        </div>

                        <div class="p-6 border-t bg-surface-low" style="border-color:var(--md-outline-variant)">
                            <button wire:click="confirmDelivery" class="md-btn md-btn-filled w-full">{{ __('messages.finish_delivery_archive') }}</button>
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
