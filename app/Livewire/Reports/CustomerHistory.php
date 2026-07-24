<?php

namespace App\Livewire\Reports;

use App\Models\Customer;
use App\Models\MaintenanceCard;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerHistory extends Component
{
    use WithPagination;

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = trim($this->search);

        $foundCustomer = null;
        if ($search !== '') {
            $foundCustomer = Customer::where('full_name', 'like', '%' . $search . '%')
                ->orWhere('national_id', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->first();
        }

        $query = MaintenanceCard::with(['customer', 'item', 'repairTasks.technician']);

        if ($search !== '') {
            $query->where(function($q) use ($search, $foundCustomer) {
                if ($foundCustomer) {
                    $q->where('customer_id', $foundCustomer->id);
                } else {
                    $q->where('card_number', 'like', '%' . $search . '%')
                      ->orWhereHas('customer', function($sub) use ($search) {
                          $sub->where('full_name', 'like', '%' . $search . '%')
                              ->orWhere('phone', 'like', '%' . $search . '%')
                              ->orWhere('national_id', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('item', function($sub) use ($search) {
                          $sub->where('item_number', 'like', '%' . $search . '%')
                              ->orWhere('type', 'like', '%' . $search . '%');
                      });
                }
            });
        }

        $cards = $query->latest()->paginate(12);

        return view('livewire.reports.customer-history', [
            'foundCustomer' => $foundCustomer,
            'cards' => $cards,
        ])->layout('layouts.app');
    }
}
