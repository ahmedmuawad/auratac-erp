<?php

namespace App\Livewire\Maintenance;

use App\Models\MaintenanceCard;
use Livewire\Component;
use Livewire\WithPagination;

class Delivery extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $selectedCardId = null;

    // Final Costs
    public $final_labor_cost;
    public $final_parts_cost;
    public $final_total_cost;
    public $paid_amount = 0;
    public $remaining_amount = 0;
    public $delivery_notes;
    public $payment_status = 'paid';

    public function updatedFinalLaborCost() { $this->calculateTotal(); }
    public function updatedFinalPartsCost() { $this->calculateTotal(); }
    public function updatedPaidAmount() { $this->calculateTotal(); }

    public function updatedPaymentStatus($value)
    {
        if ($value === 'paid') {
            $this->paid_amount = $this->final_total_cost;
        } elseif ($value === 'unpaid') {
            $this->paid_amount = 0;
        }
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->final_total_cost = (float)($this->final_labor_cost ?? 0) + (float)($this->final_parts_cost ?? 0);
        
        if ($this->payment_status === 'paid') {
            $this->paid_amount = $this->final_total_cost;
        }

        $this->remaining_amount = $this->final_total_cost - (float)($this->paid_amount ?? 0);
    }

    public function openDeliveryModal($cardId)
    {
        $card = MaintenanceCard::find($cardId);
        $this->selectedCardId = $cardId;
        $this->final_labor_cost = $card->expected_cost_labor;
        $this->final_parts_cost = $card->expected_cost_parts;
        $this->calculateTotal();
        $this->showModal = true;
    }

    public function confirmDelivery()
    {
        $this->validate([
            'final_labor_cost' => 'required|numeric|min:0',
            'final_parts_cost' => 'required|numeric|min:0',
            'payment_status' => 'required|in:paid,unpaid,partial',
        ]);

        $card = MaintenanceCard::find($this->selectedCardId);
        $card->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            'final_labor_cost' => $this->final_labor_cost,
            'final_parts_cost' => $this->final_parts_cost,
            'final_total_cost' => $this->final_total_cost,
            'paid_amount' => $this->paid_amount,
            'remaining_amount' => $this->remaining_amount,
            'delivery_notes' => $this->delivery_notes,
            'payment_status' => $this->payment_status,
        ]);

        $this->showModal = false;
        session()->flash('success', __('messages.delivered_success'));
        $this->reset(['selectedCardId', 'final_labor_cost', 'final_parts_cost', 'final_total_cost', 'delivery_notes']);
    }

    public function render()
    {
        $cards = MaintenanceCard::with(['customer', 'item'])
            ->where('status', 'ready')
            ->where(function($q) {
                $q->whereHas('customer', function($sub) {
                    $sub->where('full_name', 'like', '%' . $this->search . '%');
                })->orWhere('card_number', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.maintenance.delivery', [
            'cards' => $cards
        ])->layout('layouts.app');
    }
}
