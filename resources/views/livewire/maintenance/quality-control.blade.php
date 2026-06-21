<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md-card-elevated p-6">
        <div>
            <h1 class="text-headline text-on-surface flex items-center gap-2">
                <span class="material-symbols-rounded text-primary" style="font-size:28px">verified_user</span>
                فحص الجودة
            </h1>
            <p class="text-body text-on-surface-variant mt-1">مراجعة الكروت المنتهية من الفني واعتمادها قبل التسليم</p>
        </div>

        <div class="relative">
            <span class="material-symbols-rounded absolute inset-y-0 start-3 flex items-center text-on-surface-variant pointer-events-none" style="font-size:20px">search</span>
            <input wire:model.live="search" type="text" class="md-field !h-11 ps-11 w-full md:w-72 rounded-md-md" placeholder="بحث برقم الكرت أو العميل">
        </div>
    </div>

    {{-- Cards grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        @forelse($cards as $card)
            <div class="md-card-elevated p-6 flex flex-col">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <span class="md-status bg-onyx text-primary">#{{ $card->card_number }}</span>
                        <h3 class="text-title-lg text-on-surface mt-2">{{ $card->customer->full_name }}</h3>
                        <p class="text-label text-on-surface-variant mt-0.5">{{ $card->item->manufacturer }} — {{ $card->item->type }}</p>
                    </div>
                    <span class="md-status bg-tertiary-container text-on-tertiary-container">بانتظار الجودة</span>
                </div>

                {{-- Repair log --}}
                <div class="md-card-filled p-4 mb-4">
                    <h4 class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-3 flex items-center gap-2">
                        <span class="material-symbols-rounded" style="font-size:18px">build</span>
                        سجل الإصلاحات ({{ $card->repairTasks->count() }})
                    </h4>
                    @forelse($card->repairTasks as $task)
                        <div class="flex items-start justify-between gap-2 py-1.5 border-b last:border-0" style="border-color:var(--md-outline-variant)">
                            <p class="text-label text-on-surface flex-1">{{ $task->task_description }}</p>
                            <span class="text-label-sm text-on-surface-variant whitespace-nowrap">
                                @if($task->start_time && $task->end_time)
                                    {{ $task->start_time->diffInMinutes($task->end_time) }} د
                                @endif
                            </span>
                        </div>
                    @empty
                        <p class="text-label text-on-surface-variant">لا توجد جلسات إصلاح مسجّلة.</p>
                    @endforelse
                </div>

                <button wire:click="openModal({{ $card->id }})" class="md-btn md-btn-filled w-full mt-auto">
                    <span class="material-symbols-rounded" style="font-size:20px">fact_check</span>
                    اعتماد / رفض
                </button>
            </div>
        @empty
            <div class="lg:col-span-2 py-20 md-card flex flex-col items-center justify-center text-on-surface-variant">
                <span class="material-symbols-rounded mb-3" style="font-size:56px">inventory</span>
                <p class="text-label uppercase tracking-widest">لا توجد كروت بانتظار الفحص</p>
            </div>
        @endforelse
    </div>

    @if($cards->hasPages())
        <div>{{ $cards->links() }}</div>
    @endif

    {{-- Inspection modal --}}
    @if($showModal && $selectedCard)
        <div class="fixed inset-0 z-[60]" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-onyx/60 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="bg-surface w-full max-w-lg rounded-md-xl shadow-md-4 overflow-hidden">
                    <div class="p-6 border-b flex items-center justify-between" style="border-color:var(--md-outline-variant)">
                        <div>
                            <h2 class="text-title-lg text-on-surface">قرار فحص الجودة</h2>
                            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mt-1">#{{ $selectedCard->card_number }} — {{ $selectedCard->customer->full_name }}</p>
                        </div>
                        <button wire:click="$set('showModal', false)" class="md-icon-btn">
                            <span class="material-symbols-rounded">close</span>
                        </button>
                    </div>

                    <div class="p-6 space-y-5">
                        <div>
                            <span class="md-label">القرار</span>
                            <div class="grid grid-cols-2 gap-3">
                                <button type="button" wire:click="$set('decision', 'passed')"
                                    class="md-state h-12 rounded-md-md text-label flex items-center justify-center gap-2 border-2 {{ $decision === 'passed' ? 'bg-success-container text-on-success-container' : 'text-on-surface-variant' }}"
                                    style="border-color: {{ $decision === 'passed' ? 'var(--md-success)' : 'var(--md-outline-variant)' }}">
                                    <span class="material-symbols-rounded" style="font-size:20px">check_circle</span>
                                    مطابق
                                </button>
                                <button type="button" wire:click="$set('decision', 'rejected')"
                                    class="md-state h-12 rounded-md-md text-label flex items-center justify-center gap-2 border-2 {{ $decision === 'rejected' ? 'bg-error-container text-on-error-container' : 'text-on-surface-variant' }}"
                                    style="border-color: {{ $decision === 'rejected' ? 'var(--md-error)' : 'var(--md-outline-variant)' }}">
                                    <span class="material-symbols-rounded" style="font-size:20px">cancel</span>
                                    مرفوض — إعادة للفني
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="md-label">ملاحظات المشرف</label>
                            <textarea wire:model="notes" rows="3" class="md-field" placeholder="ملاحظات الفحص (اختياري)"></textarea>
                            @error('notes') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="p-6 border-t bg-surface-low flex gap-3" style="border-color:var(--md-outline-variant)">
                        <button wire:click="submitInspection" class="md-btn md-btn-filled flex-1">حفظ القرار</button>
                        <button wire:click="$set('showModal', false)" class="md-btn md-btn-outlined">إلغاء</button>
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
