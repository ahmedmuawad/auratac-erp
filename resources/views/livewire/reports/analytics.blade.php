<div class="space-y-6">
    {{-- Filters --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md-card-elevated p-6">
        <div>
            <h1 class="text-headline text-on-surface">{{ __('messages.financial_reports') }}</h1>
            <p class="text-body text-on-surface-variant mt-1">{{ __('messages.technician_performance') }}</p>
        </div>
        <div class="flex items-end gap-3">
            <div>
                <label class="md-label">{{ __('messages.from_date') }}</label>
                <input wire:model.live="fromDate" type="date" class="md-field !h-11 rounded-md-sm">
            </div>
            <div>
                <label class="md-label">{{ __('messages.to_date') }}</label>
                <input wire:model.live="toDate" type="date" class="md-field !h-11 rounded-md-sm">
            </div>
            <button wire:click="generateReport" class="md-icon-btn bg-primary text-on-primary"><span class="material-symbols-rounded" style="font-size:20px">refresh</span></button>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-onyx p-6 rounded-md-lg">
            <p class="text-label-sm text-primary uppercase tracking-widest mb-2">{{ __('messages.total_labor') }}</p>
            <h3 class="text-display text-on-onyx">{{ number_format($totalLabor, 2) }} <small class="text-label text-primary">{{ __('messages.sar') }}</small></h3>
        </div>
        <div class="md-card-elevated p-6">
            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-2">{{ __('messages.total_parts') }}</p>
            <h3 class="text-display text-on-surface">{{ number_format($totalParts, 2) }} <small class="text-label text-on-surface-variant">{{ __('messages.sar') }}</small></h3>
        </div>
        <div class="p-6 rounded-md-lg bg-primary-container" style="color:var(--md-on-primary-container)">
            <p class="text-label-sm uppercase tracking-widest mb-2">{{ __('messages.total_cards_count') }}</p>
            <h3 class="text-display">{{ $totalCards }}</h3>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Monthly revenue (6 months) --}}
        <div class="lg:col-span-2 md-card-elevated p-6">
            <h3 class="text-title-lg text-on-surface mb-4">{{ __('messages.revenue_summary') }} — 6 {{ __('messages.this_month') }}</h3>
            @php
                $monthlyCfg = [
                    'type' => 'line',
                    'data' => ['labels' => $monthlyLabels, 'datasets' => [[
                        'label' => __('messages.revenue_summary'), 'data' => $monthlyRevenue,
                        'borderColor' => '#8A6A3D', 'backgroundColor' => 'rgba(138,106,61,0.12)',
                        'fill' => true, 'tension' => 0.35, 'borderWidth' => 3,
                        'pointBackgroundColor' => '#8A6A3D', 'pointRadius' => 4,
                    ]]],
                    'options' => [
                        'responsive' => true, 'maintainAspectRatio' => false,
                        'plugins' => ['legend' => ['display' => false]],
                        'scales' => [
                            'y' => ['beginAtZero' => true, 'grid' => ['color' => 'rgba(0,0,0,0.06)'], 'ticks' => ['color' => '#807667']],
                            'x' => ['grid' => ['display' => false], 'ticks' => ['color' => '#807667']],
                        ],
                    ],
                ];
            @endphp
            <div style="height:300px" wire:key="chart-monthly-{{ md5(json_encode($monthlyRevenue)) }}">
                <canvas data-chart='@json($monthlyCfg, JSON_HEX_APOS|JSON_HEX_QUOT)'></canvas>
            </div>
        </div>

        {{-- Status doughnut --}}
        <div class="md-card-elevated p-6">
            <h3 class="text-title-lg text-on-surface mb-4">{{ __('messages.maintenance_cards') }}</h3>
            @php
                $statusCfg = [
                    'type' => 'doughnut',
                    'data' => ['labels' => $statusLabels, 'datasets' => [[
                        'data' => $statusData,
                        'backgroundColor' => ['#8A5A00', '#8A6A3D', '#B8860B', '#4E6354', '#3B6D44', '#211D17'],
                        'borderWidth' => 0,
                    ]]],
                    'options' => [
                        'responsive' => true, 'maintainAspectRatio' => false, 'cutout' => '60%',
                        'plugins' => ['legend' => ['position' => 'bottom', 'labels' => ['color' => '#4D4639', 'boxWidth' => 12, 'padding' => 8]]],
                    ],
                ];
            @endphp
            <div style="height:300px" wire:key="chart-status-{{ md5(json_encode($statusData)) }}">
                <canvas data-chart='@json($statusCfg, JSON_HEX_APOS|JSON_HEX_QUOT)'></canvas>
            </div>
        </div>
    </div>

    {{-- Technician performance chart --}}
    <div class="md-card-elevated p-6">
        <h3 class="text-title-lg text-on-surface mb-4">{{ __('messages.technician_performance') }}</h3>
        @php
            $techCfg = [
                'type' => 'bar',
                'data' => ['labels' => $techNames, 'datasets' => [[
                    'label' => __('messages.cards_done'), 'data' => $techCards,
                    'backgroundColor' => '#4E6354', 'borderRadius' => 6, 'maxBarThickness' => 46,
                ]]],
                'options' => [
                    'responsive' => true, 'maintainAspectRatio' => false,
                    'plugins' => ['legend' => ['display' => false]],
                    'scales' => [
                        'y' => ['beginAtZero' => true, 'grid' => ['color' => 'rgba(0,0,0,0.06)'], 'ticks' => ['color' => '#807667', 'precision' => 0]],
                        'x' => ['grid' => ['display' => false], 'ticks' => ['color' => '#807667']],
                    ],
                ],
            ];
        @endphp
        <div style="height:280px" wire:key="chart-tech-{{ md5(json_encode($techCards)) }}">
            <canvas data-chart='@json($techCfg, JSON_HEX_APOS|JSON_HEX_QUOT)'></canvas>
        </div>
    </div>

    {{-- Technician table --}}
    <div class="md-card-elevated overflow-hidden">
        <div class="p-6 border-b flex justify-between items-center" style="border-color:var(--md-outline-variant)">
            <h3 class="text-title-lg text-on-surface">{{ __('messages.technician_performance') }}</h3>
            <span class="md-status bg-surface-container text-on-surface-variant">{{ __('messages.technicians_actual') }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-low">
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.technician_col') }}</th>
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.cards_done') }}</th>
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.work_hours') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($techStats as $stat)
                        <tr class="border-b last:border-0 hover:bg-surface-low transition-colors" style="border-color:var(--md-outline-variant)">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold">{{ mb_substr($stat['name'], 0, 1) }}</div>
                                    <p class="text-label text-on-surface">{{ $stat['name'] }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-title text-on-surface">{{ $stat['cards_count'] }}</td>
                            <td class="px-6 py-5 text-label text-on-surface-variant">{{ round($stat['total_duration'] / 60, 1) }} {{ __('messages.hour_unit') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-16 text-center text-body text-on-surface-variant">{{ __('messages.no_data_range') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
