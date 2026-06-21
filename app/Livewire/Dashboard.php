<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Item;
use App\Models\MaintenanceCard;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $counts = MaintenanceCard::selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status');

        $metrics = [
            ['key' => 'pending',       'label' => __('messages.pending'),       'value' => (int) ($counts['pending'] ?? 0),       'role' => 'warning', 'icon' => 'pending_actions'],
            ['key' => 'in_progress',   'label' => __('messages.in_progress'),   'value' => (int) ($counts['in_progress'] ?? 0),   'role' => 'primary', 'icon' => 'construction'],
            ['key' => 'ready_for_qa',  'label' => __('messages.ready_for_qa'),  'value' => (int) ($counts['ready_for_qa'] ?? 0),  'role' => 'tertiary', 'icon' => 'verified_user'],
            ['key' => 'ready',         'label' => __('messages.ready'),         'value' => (int) ($counts['ready'] ?? 0),         'role' => 'success', 'icon' => 'inventory_2'],
        ];

        $deliveredThisMonth = MaintenanceCard::where('status', 'delivered')
            ->whereMonth('delivered_at', now()->month)
            ->whereYear('delivered_at', now()->year)
            ->sum('final_total_cost');

        // Extra KPIs
        $kpis = [
            ['label' => __('messages.customers'),          'value' => Customer::count(),                        'icon' => 'groups'],
            ['label' => __('messages.items_directory'),    'value' => Item::count(),                            'icon' => 'inventory_2'],
            ['label' => __('messages.total_cards'),        'value' => MaintenanceCard::count(),                 'icon' => 'description'],
            ['label' => __('messages.total_outstanding'),  'value' => number_format((float) MaintenanceCard::sum('remaining_amount')) , 'icon' => 'credit_card_off', 'suffix' => __('messages.sar')],
        ];

        // 7-day delivered revenue trend
        $trendLabels = [];
        $trendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $trendLabels[] = $day->translatedFormat('D');
            $trendData[] = (float) MaintenanceCard::where('status', 'delivered')
                ->whereDate('delivered_at', $day)
                ->sum('final_total_cost');
        }

        // Status distribution (for doughnut)
        $statusOrder = ['pending', 'in_progress', 'waiting_parts', 'ready_for_qa', 'ready', 'delivered'];
        $statusLabels = [];
        $statusData = [];
        foreach ($statusOrder as $st) {
            $statusLabels[] = __('messages.' . $st);
            $statusData[] = (int) ($counts[$st] ?? 0);
        }

        $recentCards = MaintenanceCard::with(['customer', 'item'])
            ->latest()
            ->take(6)
            ->get();

        return view('livewire.dashboard', [
            'metrics' => $metrics,
            'kpis' => $kpis,
            'deliveredThisMonth' => $deliveredThisMonth,
            'recentCards' => $recentCards,
            'trendLabels' => $trendLabels,
            'trendData' => $trendData,
            'statusLabels' => $statusLabels,
            'statusData' => $statusData,
        ]);
    }
}
