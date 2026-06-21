<div>
    <div class="space-y-6">
        {{-- Title --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-headline text-on-surface">{{ __('messages.dashboard') }}</h1>
                <p class="text-body text-on-surface-variant">{{ __('messages.welcome') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-label text-on-surface-variant">{{ now()->translatedFormat('l، j F Y') }}</span>
                <div class="md-icon-btn bg-surface-container">
                    <span class="material-symbols-rounded" style="font-size:22px">calendar_month</span>
                </div>
            </div>
        </div>

        {{-- Metric cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($metrics as $m)
                @php
                    $bg = match($m['role']) {
                        'warning' => 'var(--md-warning-container)',
                        'primary' => 'var(--md-primary-container)',
                        'tertiary' => 'var(--md-tertiary-container)',
                        default => 'var(--md-success-container)',
                    };
                    $fg = match($m['role']) {
                        'warning' => 'var(--md-on-warning-container)',
                        'primary' => 'var(--md-on-primary-container)',
                        'tertiary' => 'var(--md-on-tertiary-container)',
                        default => 'var(--md-on-success-container)',
                    };
                @endphp
                <a href="{{ route('maintenance.index', ['filter_status' => $m['key']]) }}" class="md-card-elevated md-state p-5 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-md-md flex items-center justify-center shrink-0" style="background:{{ $bg }};color:{{ $fg }}">
                        <span class="material-symbols-rounded" style="font-size:28px">{{ $m['icon'] }}</span>
                    </div>
                    <div>
                        <p class="text-label text-on-surface-variant">{{ $m['label'] }}</p>
                        <h3 class="text-display text-on-surface" style="font-size:1.75rem;line-height:2rem">{{ $m['value'] }}</h3>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Main grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- Revenue summary --}}
            <div class="lg:col-span-2 md-card-elevated p-6 flex flex-col min-h-[360px]">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-title-lg text-on-surface">{{ __('messages.revenue_summary') }}</h3>
                    <span class="md-status bg-success-container text-on-success-container">{{ __('messages.this_month') }}</span>
                </div>
                <div class="flex-1 flex flex-col items-center justify-center text-center">
                    <span class="material-symbols-rounded text-primary mb-2" style="font-size:48px">payments</span>
                    <p class="text-display text-on-surface">{{ number_format($deliveredThisMonth, 2) }}</p>
                    <p class="text-label text-on-surface-variant mt-1">{{ __('messages.riyal') }} — {{ __('messages.collected_this_month_note') }}</p>
                </div>
            </div>

            {{-- Recent activity --}}
            <div class="md-card-elevated p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-title-lg text-on-surface">{{ __('messages.recent_cards') }}</h3>
                    <a href="{{ route('maintenance.index') }}" class="text-label text-primary">{{ __('messages.view_all') }}</a>
                </div>
                <div class="space-y-1">
                    @forelse($recentCards as $card)
                        <a href="{{ route('maintenance.index') }}" class="md-state flex items-center gap-3 p-2 rounded-md-sm">
                            <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center shrink-0">
                                <span class="material-symbols-rounded text-primary" style="font-size:20px">description</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-label text-on-surface truncate">{{ $card->customer->full_name ?? '—' }}</p>
                                <p class="text-label-sm text-on-surface-variant">#{{ $card->card_number }} · {{ $card->created_at->diffForHumans() }}</p>
                            </div>
                            @php $meta = $card->statusMeta(); @endphp
                            <span class="md-status bg-surface-container text-on-surface-variant shrink-0">{{ $meta['label'] }}</span>
                        </a>
                    @empty
                        <p class="text-body text-on-surface-variant py-8 text-center">{{ __('messages.no_cards_yet') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
