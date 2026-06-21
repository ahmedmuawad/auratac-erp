<?php

namespace App\Livewire\Reports;

use App\Models\Customer;
use App\Models\MaintenanceCard;
use Livewire\Component;

class CustomerHistory extends Component
{
    public $search = '';
    public $foundCustomer = null;
    public $history = [];

    public function searchCustomer()
    {
        $this->foundCustomer = Customer::where('full_name', 'like', '%' . $this->search . '%')
            ->orWhere('national_id', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->first();

        if ($this->foundCustomer) {
            $this->history = MaintenanceCard::with(['item', 'repairTasks.technician'])
                ->where('customer_id', $this->foundCustomer->id)
                ->latest()
                ->get();
        } else {
            $this->history = [];
        }
    }

    public function render()
    {
        return view('livewire.reports.customer-history')
            ->layout('layouts.app');
    }
}
