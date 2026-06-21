<?php

namespace App\Livewire\Maintenance;

use App\Models\MaintenanceCard;
use App\Models\QaInspection;
use Livewire\Component;
use Livewire\WithPagination;

class QualityControl extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $selectedCard = null;

    public $decision = 'passed';   // passed | rejected
    public $notes = '';

    protected $queryString = ['search'];

    public function openModal($cardId)
    {
        $this->selectedCard = MaintenanceCard::with(['customer', 'item', 'repairTasks.technician'])->find($cardId);
        $this->decision = 'passed';
        $this->notes = '';
        $this->showModal = true;
    }

    public function submitInspection()
    {
        $this->validate([
            'decision' => 'required|in:passed,rejected',
            'notes'    => 'nullable|string|max:1000',
        ]);

        $card = MaintenanceCard::findOrFail($this->selectedCard->id);

        QaInspection::create([
            'maintenance_card_id' => $card->id,
            'qa_supervisor_id'    => auth()->id(),
            'status'              => $this->decision,
            'notes'              => $this->notes,
        ]);

        // Passed -> ready for delivery. Rejected -> back to the technician.
        $card->update([
            'status' => $this->decision === 'passed' ? 'ready' : 'in_progress',
        ]);

        $this->showModal = false;
        $this->reset(['selectedCard', 'decision', 'notes']);
        session()->flash('success', $this->decision === 'passed'
            ? __('messages.qa_passed_success')
            : __('messages.qa_rejected_success'));
    }

    public function render()
    {
        $cards = MaintenanceCard::with(['customer', 'item', 'repairTasks'])
            ->where('status', 'ready_for_qa')
            ->where(function ($q) {
                $q->where('card_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('customer', function ($sub) {
                      $sub->where('full_name', 'like', '%' . $this->search . '%');
                  });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.maintenance.quality-control', [
            'cards' => $cards,
        ])->layout('layouts.app');
    }
}
