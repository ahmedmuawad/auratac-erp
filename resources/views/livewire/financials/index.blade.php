<div class="space-y-6">
    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-onyx p-6 rounded-md-lg flex items-center justify-between">
            <div>
                <p class="text-label-sm text-primary uppercase tracking-widest mb-2">إجمالي التحصيل الفعلي</p>
                <h3 class="text-display text-on-onyx">{{ number_format($totalCollected, 2) }} <small class="text-label text-primary">{{ __('messages.sar') }}</small></h3>
            </div>
            <span class="material-symbols-rounded text-primary" style="font-size:48px">savings</span>
        </div>
        <div class="p-6 rounded-md-lg flex items-center justify-between bg-error-container" style="color:var(--md-on-error-container)">
            <div>
                <p class="text-label-sm uppercase tracking-widest mb-2">إجمالي المديونيات المتبقية</p>
                <h3 class="text-display">{{ number_format($totalDebts, 2) }} <small class="text-label">{{ __('messages.sar') }}</small></h3>
            </div>
            <span class="material-symbols-rounded" style="font-size:48px">credit_card_off</span>
        </div>
    </div>

    {{-- Header & filters --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md-card-elevated p-6">
        <div>
            <h1 class="text-headline text-on-surface">إدارة المديونيات والتحصيل</h1>
            <p class="text-body text-on-surface-variant mt-1">تتبّع المبالغ المتبقية في ذمة العملاء وتحصيل الفواتير</p>
        </div>
        <div class="flex items-center gap-3">
            <select wire:model.live="status" class="md-field !h-11 w-auto rounded-md-md">
                <option value="all">كافة الكروت المؤرشفة</option>
                <option value="with_debt">كروت بها مديونية</option>
                <option value="cleared">كروت مسددة بالكامل</option>
            </select>
            <div class="relative">
                <span class="material-symbols-rounded absolute inset-y-0 start-3 flex items-center text-on-surface-variant pointer-events-none" style="font-size:20px">search</span>
                <input wire:model.live="search" type="text" class="md-field !h-11 ps-11 w-full md:w-64 rounded-md-md" placeholder="بحث بالاسم أو رقم الكرت...">
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="md-card-elevated overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-low border-b" style="border-color:var(--md-outline-variant)">
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">التاريخ والكرت</th>
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">العميل والبيانات</th>
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">الإجمالي</th>
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">المسدد</th>
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">المتبقي</th>
                        <th class="px-6 py-4 text-end text-label-sm text-on-surface-variant uppercase tracking-widest">التحصيل</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cards as $card)
                        <tr class="border-b last:border-0 hover:bg-surface-low transition-colors" style="border-color:var(--md-outline-variant)">
                            <td class="px-6 py-5">
                                <p class="text-label text-on-surface">{{ $card->card_number }}</p>
                                <p class="text-label-sm text-on-surface-variant">{{ $card->delivered_at?->format('Y-m-d') }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-label-sm">{{ mb_substr($card->customer->full_name, 0, 1) }}</div>
                                    <div>
                                        <p class="text-label text-on-surface">{{ $card->customer->full_name }}</p>
                                        <p class="text-label-sm text-on-surface-variant">{{ $card->item->type }} - {{ $card->item->item_number }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-label text-on-surface">{{ number_format($card->final_total_cost, 2) }}</td>
                            <td class="px-6 py-5 text-label text-success">{{ number_format($card->paid_amount, 2) }}</td>
                            <td class="px-6 py-5">
                                <span class="md-status {{ $card->remaining_amount > 0 ? 'bg-error-container text-on-error-container' : 'bg-success-container text-on-success-container' }}">{{ number_format($card->remaining_amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-5 text-end">
                                @if($card->remaining_amount > 0)
                                    <button wire:click="collectRemaining({{ $card->id }})" class="md-icon-btn bg-warning-container" style="color:var(--md-on-warning-container)" title="تحصيل المتبقي">
                                        <span class="material-symbols-rounded" style="font-size:20px">paid</span>
                                    </button>
                                @else
                                    <span class="md-icon-btn text-success opacity-60"><span class="material-symbols-rounded" style="font-size:20px">check</span></span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-body text-on-surface-variant">لا توجد سجلات مالية مطابقة حالياً</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($cards->hasPages())
            <div class="px-6 py-4 border-t bg-surface-low" style="border-color:var(--md-outline-variant)">{{ $cards->links() }}</div>
        @endif
    </div>

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
