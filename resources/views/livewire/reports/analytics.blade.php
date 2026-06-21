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
            <p class="text-label-sm uppercase tracking-widest mb-2">إجمالي عدد الكروت</p>
            <h3 class="text-display">{{ $totalCards }}</h3>
        </div>
    </div>

    {{-- Technician performance --}}
    <div class="md-card-elevated overflow-hidden">
        <div class="p-6 border-b flex justify-between items-center" style="border-color:var(--md-outline-variant)">
            <h3 class="text-title-lg text-on-surface">{{ __('messages.technician_performance') }}</h3>
            <span class="md-status bg-surface-container text-on-surface-variant">إنجاز الفنيين الفعلي</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-low">
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">الفني</th>
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">الكروت المنجزة</th>
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">ساعات العمل</th>
                        <th class="px-6 py-4 text-end text-label-sm text-on-surface-variant uppercase tracking-widest">كفاءة الأداء</th>
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
                            <td class="px-6 py-5 text-label text-on-surface-variant">{{ round($stat['total_duration'] / 60, 1) }} ساعة</td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-24 h-2 bg-surface-container rounded-full overflow-hidden">
                                        <div class="h-full bg-primary rounded-full" style="width: {{ min($stat['cards_count'] * 10, 100) }}%"></div>
                                    </div>
                                    <span class="text-label-sm text-primary">{{ min($stat['cards_count'] * 10, 100) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-16 text-center text-body text-on-surface-variant">لا توجد بيانات متاحة لهذا النطاق</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
