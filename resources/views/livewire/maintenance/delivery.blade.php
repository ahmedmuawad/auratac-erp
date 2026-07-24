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

                                <div class="bg-onyx p-5 rounded-md-md space-y-2">
                                    <div class="flex justify-between items-center text-on-onyx-variant text-label-sm">
                                        <span>{{ __('messages.subtotal') ?? 'المجموع قبل الضريبة' }}</span>
                                        <span class="tabular-nums font-mono">{{ number_format($final_subtotal, 2) }} {{ __('messages.sar') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-primary text-label-sm font-medium">
                                        <span>{{ __('messages.vat_amount') ?? 'ضريبة القيمة المضافة (15%)' }}</span>
                                        <span class="tabular-nums font-mono">+{{ number_format($final_tax_amount, 2) }} {{ __('messages.sar') }}</span>
                                    </div>
                                    <div class="border-t pt-2 flex justify-between items-center border-white/10">
                                        <span class="text-label text-on-onyx font-bold">{{ __('messages.final_total') }} (شامل الضريبة 15%)</span>
                                        <span class="text-title-lg text-primary tabular-nums font-bold">{{ number_format($final_total_cost, 2) }} <small class="text-label">{{ __('messages.sar') }}</small></span>
                                    </div>
                                    @if($payment_status !== 'paid')
                                        <div class="flex justify-between items-center pt-2 border-t border-white/10">
                                            <span class="text-label text-on-onyx-variant">{{ __('messages.amount_paid') }}</span>
                                            <span class="text-title text-success">{{ number_format($paid_amount, 2) }} {{ __('messages.sar') }}</span>
                                        </div>
                                        <div class="flex justify-between items-center pt-2 border-t border-white/10">
                                            <span class="text-label text-error">{{ __('messages.remaining_debt') }}</span>
                                            <span class="text-title text-error">{{ number_format($remaining_amount, 2) }} {{ __('messages.sar') }}</span>
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

    {{-- Delivery OTP Verification Modal --}}
    @if($show_otp_modal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 backdrop-blur-sm animate-fade-in" style="background-color: rgba(26, 26, 26, 0.8);">
            <div class="md-card-elevated w-full max-w-md p-6 space-y-6 rounded-md-xl border shadow-2xl" style="border-color:var(--md-outline-variant); background-color: var(--md-surface, #ffffff);">
                <div class="flex items-center justify-between border-b pb-4" style="border-color:var(--md-outline-variant)">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-rounded">mark_email_read</span>
                        </span>
                        <div>
                            <h3 class="text-title-medium text-on-surface font-bold">تأكيد كود تسليم واستلام السلاح</h3>
                            <p class="text-label-sm text-on-surface-variant font-mono">التحقق عبر الواتساب</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeDeliveryOtpModal" class="text-on-surface-variant hover:text-error transition-colors">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>

                <p class="text-body-sm text-on-surface-variant">
                    تم إرسال كود تحقق مكون من 4 أرقام عبر واتساب إلى رقم العميل لتأكيد الهوية واستلام السلاح وإغلاق الكرت.
                </p>

                @if($otp_sent_message)
                    <div class="p-3 bg-success-container text-on-success-container rounded-md-sm text-label-sm flex items-center gap-2">
                        <span class="material-symbols-rounded" style="font-size:18px">check_circle</span>
                        <span>{{ $otp_sent_message }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="verifyDeliveryOtpAndConfirm" class="space-y-5">
                    <div>
                        <label class="md-label text-center block mb-2">{{ __('messages.enter_otp_code') }}</label>
                        <input wire:model="entered_otp" type="text" maxlength="6" autofocus placeholder="• • • •" class="md-field text-center text-3xl font-mono tracking-widest rounded-md-md h-14 uppercase" style="letter-spacing: 0.4em;">
                        @if($otp_error)
                            <p class="text-label-sm text-error text-center mt-2 font-bold">{{ $otp_error }}</p>
                        @endif
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" wire:loading.attr="disabled" class="md-btn md-btn-filled w-full h-12 text-title-small font-bold">
                            <span wire:loading.remove>تأكيد الكود وإتمام التسليم والأرشفة</span>
                            <span wire:loading>{{ __('messages.verifying') }}</span>
                            <span wire:loading.remove class="material-symbols-rounded">check_circle</span>
                        </button>

                        <div class="flex items-center justify-between pt-2">
                            <button type="button" wire:click="resendDeliveryOtp" class="text-label-sm text-primary hover:underline flex items-center gap-1">
                                <span class="material-symbols-rounded" style="font-size:16px">refresh</span>
                                <span>{{ __('messages.resend_otp') }}</span>
                            </button>

                            <button type="button" wire:click="closeDeliveryOtpModal" class="text-label-sm text-on-surface-variant hover:underline">
                                {{ __('messages.cancel') }}
                            </button>
                        </div>
                    </div>
                </form>
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
