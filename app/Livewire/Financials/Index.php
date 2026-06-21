<?php

namespace App\Livewire\Financials;

use App\Models\MaintenanceCard;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'all'; // all, with_debt, cleared

    public function collectRemaining($cardId)
    {
        $card = MaintenanceCard::find($cardId);
        if ($card && $card->remaining_amount > 0) {
            $card->update([
                'paid_amount' => $card->final_total_cost,
                'remaining_amount' => 0,
                'payment_status' => 'paid'
            ]);
            session()->flash('success', 'تم تحصيل المبلغ المتبقي بنجاح');
        }
    }

    public function render()
    {
        $query = MaintenanceCard::with(['customer', 'item'])
            ->where('status', 'delivered')
            ->where(function($q) {
                $q->whereHas('customer', function($sub) {
                    $sub->where('full_name', 'like', '%' . $this->search . '%');
                })->orWhere('card_number', 'like', '%' . $this->search . '%');
            });

        if ($this->status === 'with_debt') {
            $query->where('remaining_amount', '>', 0);
        } elseif ($this->status === 'cleared') {
            $query->where('remaining_amount', 0);
        }

        $cards = $query->latest('delivered_at')->paginate(10);
        
        $totalDebts = MaintenanceCard::where('status', 'delivered')->sum('remaining_amount');
        $totalCollected = MaintenanceCard::where('status', 'delivered')->sum('paid_amount');

        return view('livewire.financials.index', [
            'cards' => $cards,
            'totalDebts' => $totalDebts,
            'totalCollected' => $totalCollected
        ])->layout('layouts.app');
    }
}
