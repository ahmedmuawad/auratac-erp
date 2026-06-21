<div class="space-y-6">
    {{-- Search --}}
    <div class="md-card-elevated p-8">
        <div class="max-w-2xl mx-auto text-center space-y-4">
            <h1 class="text-headline text-on-surface">{{ __('messages.comprehensive_search') }}</h1>
            <p class="text-body text-on-surface-variant">{{ __('messages.search_history_placeholder') }}</p>
            <div class="relative mt-4">
                <span class="material-symbols-rounded absolute inset-y-0 start-4 flex items-center text-on-surface-variant pointer-events-none">search</span>
                <input wire:model.live.debounce.500ms="search" wire:keydown.enter="searchCustomer" type="text"
                       class="md-field !h-14 ps-12 pe-28 rounded-md-md text-title" placeholder="{{ __('messages.search_history_placeholder') }}">
                <button wire:click="searchCustomer" class="md-btn md-btn-filled absolute inset-y-2 end-2">{{ __('messages.search') }}</button>
            </div>
        </div>
    </div>

    @if($foundCustomer)
        {{-- Profile --}}
        <div class="bg-onyx p-6 rounded-md-lg flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-md-lg bg-white/10 flex items-center justify-center text-headline text-on-onyx">{{ mb_substr($foundCustomer->full_name, 0, 1) }}</div>
                <div>
                    <h2 class="text-title-lg text-on-onyx">{{ $foundCustomer->full_name }}</h2>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="md-status bg-white/10 text-on-onyx-variant">{{ $foundCustomer->phone }}</span>
                        <span class="md-status bg-white/10 text-on-onyx-variant">{{ $foundCustomer->national_id }}</span>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <p class="text-label-sm text-primary uppercase tracking-widest mb-1">{{ __('messages.total_transactions') }}</p>
                <h3 class="text-display text-on-onyx">{{ count($history) }}</h3>
            </div>
        </div>

        {{-- Timeline --}}
        <div class="space-y-4">
            <h3 class="text-label text-on-surface-variant uppercase tracking-widest px-2">{{ __('messages.repair_timeline') }}</h3>
            @forelse($history as $card)
                @php $meta = $card->statusMeta(); @endphp
                <div class="md-card-elevated p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-title-lg text-on-surface">{{ $card->card_number }}</h4>
                            <p class="text-label-sm text-on-surface-variant">{{ $card->created_at->format('Y-m-d') }} · {{ $card->item->type }} ({{ $card->item->item_number }})</p>
                        </div>
                        <span class="md-status bg-surface-container text-on-surface-variant">{{ $meta['label'] }}</span>
                    </div>

                    <div class="md-card-filled p-4">
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-3">{{ __('messages.works_done') }}</p>
                        @forelse($card->repairTasks ?? [] as $task)
                            <div class="flex items-start gap-3 py-1.5 border-b last:border-0" style="border-color:var(--md-outline-variant)">
                                <span class="material-symbols-rounded text-primary mt-0.5" style="font-size:16px">check_circle</span>
                                <div class="flex-1">
                                    <p class="text-label text-on-surface">{{ $task->task_description }}</p>
                                    <p class="text-label-sm text-on-surface-variant">{{ __('messages.by_label') }} {{ $task->technician?->name }}
                                        @if($task->start_time && $task->end_time) · {{ $task->start_time->diffInMinutes($task->end_time) }} {{ __('messages.minutes_short') }} @endif
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-label text-on-surface-variant">{{ __('messages.no_tech_log') }}</p>
                        @endforelse
                    </div>

                    @if($card->status == 'delivered')
                        <div class="grid grid-cols-3 gap-3 mt-4">
                            <div class="md-card-filled p-3"><p class="text-label-sm text-on-surface-variant">أجور اليد</p><p class="text-title text-on-surface">{{ number_format($card->final_labor_cost, 2) }}</p></div>
                            <div class="md-card-filled p-3"><p class="text-label-sm text-on-surface-variant">قيمة القطع</p><p class="text-title text-on-surface">{{ number_format($card->final_parts_cost, 2) }}</p></div>
                            <div class="p-3 rounded-md-md bg-primary-container" style="color:var(--md-on-primary-container)"><p class="text-label-sm">الإجمالي</p><p class="text-title">{{ number_format($card->final_total_cost, 2) }}</p></div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-16 md-card text-on-surface-variant"><p class="text-label uppercase tracking-widest">{{ __('messages.no_history_records') }}</p></div>
            @endforelse
        </div>
    @elseif($search)
        <div class="text-center py-16 md-card text-on-surface-variant"><p class="text-title">{{ __('messages.no_history_found') }}</p></div>
    @endif
</div>
