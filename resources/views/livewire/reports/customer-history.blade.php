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

    {{-- Search Bar --}}
    <div class="md-card-elevated p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-headline text-on-surface flex items-center gap-2">
                    <span class="material-symbols-rounded text-primary" style="font-size:28px">history_edu</span>
                    {{ __('messages.comprehensive_search') }}
                </h1>
                <p class="text-body text-on-surface-variant mt-1">{{ __('messages.search_history_placeholder') }}</p>
            </div>
            <div class="relative w-full md:w-96">
                <span class="material-symbols-rounded absolute inset-y-0 start-4 flex items-center text-on-surface-variant pointer-events-none">search</span>
                <input wire:model.live.debounce.300ms="search" type="text"
                       class="md-field !h-12 ps-12 pe-4 rounded-md-md text-title-small w-full" placeholder="ابحث باسم العميل، الهاتف، كارت العمل أو السريال...">
            </div>
        </div>
    </div>

    {{-- Found Customer Profile Banner --}}
    @if($foundCustomer)
        <div class="bg-onyx p-6 rounded-md-lg flex flex-col md:flex-row md:items-center justify-between gap-5 shadow-md">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-md-lg bg-primary/20 text-primary flex items-center justify-center text-title-lg font-bold">
                    {{ mb_substr($foundCustomer->full_name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-title-lg text-on-onyx font-bold">{{ $foundCustomer->full_name }}</h2>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="md-status bg-white/10 text-on-onyx-variant font-mono"><span class="material-symbols-rounded text-xs me-1">call</span>{{ $foundCustomer->phone }}</span>
                        @if($foundCustomer->national_id)
                            <span class="md-status bg-white/10 text-on-onyx-variant font-mono"><span class="material-symbols-rounded text-xs me-1">badge</span>{{ $foundCustomer->national_id }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="text-end">
                <p class="text-label-sm text-primary uppercase tracking-widest mb-1">{{ __('messages.total_transactions') }}</p>
                <h3 class="text-display text-on-onyx font-bold">{{ $cards->total() }}</h3>
            </div>
        </div>
    @endif

    {{-- Cards Grid (Identical to Maintenance Index Grid) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($cards as $card)
            @php $meta = $card->statusMeta(); @endphp
            <div class="md-card-elevated p-5 flex flex-col justify-between hover:border-primary/40 transition-colors">
                <div>
                    {{-- Card header & Status --}}
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <span class="md-status bg-surface-container text-on-surface-variant font-mono">#{{ $card->card_number }}</span>
                            <h3 class="text-title text-on-surface mt-2 font-bold truncate w-48">{{ $card->customer?->full_name ?? '—' }}</h3>
                            <p class="text-label-sm text-on-surface-variant font-mono mt-0.5" dir="ltr">{{ $card->customer?->phone }}</p>
                        </div>
                        <span class="md-status {{ $roleClass[$meta['role']] ?? $roleClass['secondary'] }}">{{ $meta['label'] }}</span>
                    </div>

                    {{-- Item details box --}}
                    <div class="md-card-filled p-3 flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-md-sm bg-surface flex items-center justify-center text-primary shrink-0">
                            <span class="material-symbols-rounded" style="font-size:20px">precision_manufacturing</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-label-sm text-on-surface-variant uppercase font-bold">{{ $card->item?->type ?? '—' }}</p>
                            <p class="text-label text-on-surface font-mono truncate">{{ $card->item?->item_number ?? '—' }}</p>
                        </div>
                    </div>

                    {{-- Financial summary breakdown --}}
                    @php
                        $labor = (float)($card->final_labor_cost ?? $card->expected_cost_labor ?? 0);
                        $parts = (float)($card->final_parts_cost ?? $card->expected_cost_parts ?? 0);
                        $sub = $card->final_subtotal > 0 ? $card->final_subtotal : ($card->subtotal > 0 ? $card->subtotal : ($labor + $parts));
                        $vat = $card->final_tax_amount > 0 ? $card->final_tax_amount : ($card->tax_amount > 0 ? $card->tax_amount : round($sub * 0.15, 2));
                        $tot = $card->final_total_cost > 0 ? $card->final_total_cost : ($card->total_cost > 0 ? $card->total_cost : round($sub + $vat, 2));
                    @endphp
                    <div class="p-3 bg-onyx rounded-md-sm space-y-1 text-label-sm mb-4">
                        <div class="flex justify-between text-on-onyx-variant">
                            <span>المجموع (قبل الضريبة)</span>
                            <span class="font-mono">{{ number_format($sub, 2) }} {{ __('messages.sar') }}</span>
                        </div>
                        <div class="flex justify-between text-primary font-medium">
                            <span>الضريبة (15%)</span>
                            <span class="font-mono">+{{ number_format($vat, 2) }} {{ __('messages.sar') }}</span>
                        </div>
                        <div class="border-t border-white/10 pt-1 flex justify-between text-on-onyx font-bold">
                            <span>الإجمالي شامل الضريبة</span>
                            <span class="text-primary font-mono">{{ number_format($tot, 2) }} {{ __('messages.sar') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Action buttons footer --}}
                <div class="flex items-center justify-between pt-3 border-t mt-auto" style="border-color:var(--md-outline-variant)">
                    <span class="text-label-sm text-on-surface-variant font-mono">{{ $card->created_at?->format('Y-m-d') }}</span>
                    <div class="flex gap-1">
                        <a href="{{ route('maintenance.print', $card->id) }}" target="_blank" class="md-icon-btn" title="{{ __('messages.work_card_print') }}">
                            <span class="material-symbols-rounded" style="font-size:20px">print</span>
                        </a>
                        <a href="{{ route('maintenance.print-repair', $card->id) }}" target="_blank" class="md-icon-btn" title="{{ __('messages.repair_card_print') }}">
                            <span class="material-symbols-rounded" style="font-size:20px">build_circle</span>
                        </a>
                        <a href="{{ route('maintenance.print-label', $card->id) }}" target="_blank" class="md-icon-btn" title="{{ __('messages.print_label') }}">
                            <span class="material-symbols-rounded" style="font-size:20px">label</span>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 md-card flex flex-col items-center justify-center text-on-surface-variant">
                <span class="material-symbols-rounded mb-3" style="font-size:56px">description</span>
                <p class="text-label uppercase tracking-widest">{{ __('messages.no_history_records') }}</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination links --}}
    @if($cards->hasPages())
        <div>{{ $cards->links() }}</div>
    @endif
</div>
