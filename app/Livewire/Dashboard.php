<?php

namespace App\Livewire;

use App\Models\MaintenanceCard;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $counts = MaintenanceCard::selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status');

        $metrics = [
            ['key' => 'pending',       'label' => 'بانتظار البدء',   'value' => (int) ($counts['pending'] ?? 0),       'role' => 'warning', 'icon' => 'pending_actions'],
            ['key' => 'in_progress',   'label' => 'قيد التنفيذ',     'value' => (int) ($counts['in_progress'] ?? 0),   'role' => 'primary', 'icon' => 'construction'],
            ['key' => 'ready_for_qa',  'label' => 'بانتظار الجودة',  'value' => (int) ($counts['ready_for_qa'] ?? 0),  'role' => 'tertiary', 'icon' => 'verified_user'],
            ['key' => 'ready',         'label' => 'جاهز للتسليم',    'value' => (int) ($counts['ready'] ?? 0),         'role' => 'success', 'icon' => 'inventory_2'],
        ];

        $deliveredThisMonth = MaintenanceCard::where('status', 'delivered')
            ->whereMonth('delivered_at', now()->month)
            ->whereYear('delivered_at', now()->year)
            ->sum('final_total_cost');

        $recentCards = MaintenanceCard::with(['customer', 'item'])
            ->latest()
            ->take(6)
            ->get();

        return view('livewire.dashboard', [
            'metrics' => $metrics,
            'deliveredThisMonth' => $deliveredThisMonth,
            'recentCards' => $recentCards,
        ]);
    }
}
