<?php

namespace App\Livewire\Reports;

use App\Models\MaintenanceCard;
use App\Models\RepairTask;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class Analytics extends Component
{
    public $fromDate;
    public $toDate;

    public $totalLabor = 0;
    public $totalParts = 0;
    public $totalSubtotal = 0;
    public $totalVat = 0;
    public $totalGrand = 0;
    public $totalCards = 0;
    public $techStats = [];

    // chart datasets
    public $monthlyLabels = [];
    public $monthlyRevenue = [];
    public $statusLabels = [];
    public $statusData = [];
    public $techNames = [];
    public $techCards = [];

    public function mount()
    {
        $this->fromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->toDate = Carbon::now()->format('Y-m-d');
        $this->generateReport();
    }

    public function generateReport()
    {
        $from = Carbon::parse($this->fromDate)->startOfDay();
        $to = Carbon::parse($this->toDate)->endOfDay();

        $query = MaintenanceCard::where('status', 'delivered')->whereBetween('delivered_at', [$from, $to]);

        $this->totalLabor = (float) $query->clone()->sum('final_labor_cost');
        $this->totalParts = (float) $query->clone()->sum('final_parts_cost');
        $this->totalSubtotal = $this->totalLabor + $this->totalParts;
        $this->totalVat = (float) $query->clone()->sum('final_tax_amount');
        if ($this->totalVat == 0 && $this->totalSubtotal > 0) {
            $this->totalVat = round($this->totalSubtotal * 0.15, 2);
        }
        $this->totalGrand = (float) $query->clone()->sum('final_total_cost');
        if ($this->totalGrand == 0) {
            $this->totalGrand = round($this->totalSubtotal + $this->totalVat, 2);
        }
        $this->totalCards = $query->clone()->count();

        // Technician performance
        $stats = User::where('role', 'technician')->get()->map(function ($tech) use ($from, $to) {
            $tasks = RepairTask::where('technician_id', $tech->id)->whereBetween('created_at', [$from, $to]);
            return [
                'name' => $tech->name,
                'cards_count' => $tasks->clone()->distinct('maintenance_card_id')->count('maintenance_card_id'),
                'total_duration' => (int) $tasks->clone()->sum('duration'),
            ];
        });
        $this->techStats = $stats;
        $this->techNames = $stats->pluck('name')->all();
        $this->techCards = $stats->pluck('cards_count')->all();

        // Last 6 months delivered revenue
        $this->monthlyLabels = [];
        $this->monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $this->monthlyLabels[] = $m->translatedFormat('M');
            $this->monthlyRevenue[] = (float) MaintenanceCard::where('status', 'delivered')
                ->whereMonth('delivered_at', $m->month)
                ->whereYear('delivered_at', $m->year)
                ->sum('final_total_cost');
        }

        // Status distribution (all cards)
        $counts = MaintenanceCard::selectRaw('status, COUNT(*) as c')->groupBy('status')->pluck('c', 'status');
        $this->statusLabels = [];
        $this->statusData = [];
        foreach (['pending', 'in_progress', 'waiting_parts', 'ready_for_qa', 'ready', 'delivered'] as $st) {
            $this->statusLabels[] = __('messages.' . $st);
            $this->statusData[] = (int) ($counts[$st] ?? 0);
        }

        $this->dispatch('charts-updated');
    }

    public function render()
    {
        return view('livewire.reports.analytics')->layout('layouts.app');
    }
}
