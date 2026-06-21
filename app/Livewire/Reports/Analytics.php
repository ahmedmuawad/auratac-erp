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
    public $totalCards = 0;
    public $techStats = [];

    public function mount()
    {
        $this->fromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->toDate = Carbon::now()->format('Y-m-d');
        $this->generateReport();
    }

    public function generateReport()
    {
        $query = MaintenanceCard::where('status', 'delivered')
            ->whereBetween('delivered_at', [
                Carbon::parse($this->fromDate)->startOfDay(),
                Carbon::parse($this->toDate)->endOfDay()
            ]);

        $this->totalLabor = $query->sum('final_labor_cost');
        $this->totalParts = $query->sum('final_parts_cost');
        $this->totalCards = $query->count();

        // Technician Performance
        $this->techStats = User::where('role', 'technician')->get()->map(function($tech) {
            $tasks = RepairTask::where('technician_id', $tech->id)
                ->whereBetween('created_at', [
                    Carbon::parse($this->fromDate)->startOfDay(),
                    Carbon::parse($this->toDate)->endOfDay()
                ]);
            
            return [
                'name' => $tech->name,
                'cards_count' => $tasks->distinct('maintenance_card_id')->count('maintenance_card_id'),
                'total_duration' => $tasks->sum('duration'), // in minutes
            ];
        });
    }

    public function render()
    {
        return view('livewire.reports.analytics')
            ->layout('layouts.app');
    }
}
