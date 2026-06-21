<?php

namespace App\Livewire\Maintenance;

use App\Models\MaintenanceCard;
use App\Models\RepairTask;
use Livewire\Component;
use Livewire\WithPagination;

class TechnicianPanel extends Component
{
    use WithPagination;

    public $search = '';
    public $showTaskModal = false;
    public $selectedCard = null;

    // Task Form Fields
    public $task_description = '';
    public $used_parts_text = '';
    public $start_time;
    public $end_time;

    protected $queryString = ['search'];

    public function mount()
    {
        $this->start_time = now()->format('Y-m-d\TH:i');
        $this->end_time = now()->format('Y-m-d\TH:i');
    }

    public function openTaskModal($cardId)
    {
        $this->selectedCard = MaintenanceCard::with('repairTasks')->find($cardId);
        $this->resetTaskForm();
        $this->showTaskModal = true;
    }

    public function resetTaskForm()
    {
        $this->task_description = '';
        $this->used_parts_text = '';
        $this->start_time = now()->format('Y-m-d\TH:i');
        $this->end_time = now()->addHour()->format('Y-m-d\TH:i');
    }

    public function saveTask()
    {
        $this->validate([
            'task_description' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        RepairTask::create([
            'maintenance_card_id' => $this->selectedCard->id,
            'technician_id' => auth()->id(),
            'task_description' => $this->task_description,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'used_parts_text' => $this->used_parts_text,
        ]);

        // Update card status to in_progress if it was pending
        if ($this->selectedCard->status == 'pending') {
            $this->selectedCard->update(['status' => 'in_progress']);
        }

        session()->flash('success', __('messages.task_added_success'));
        $this->showTaskModal = false;
        $this->resetTaskForm();
    }

    public function updateStatus($cardId, $status)
    {
        $card = MaintenanceCard::find($cardId);
        $card->update(['status' => $status]);
        session()->flash('success', __('messages.status_updated_to_' . $status));
    }

    public function render()
    {
        $cards = MaintenanceCard::with(['customer', 'item', 'repairTasks'])
            ->whereIn('status', ['pending', 'in_progress', 'waiting_parts'])
            ->where(function($query) {
                $query->where('card_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('customer', function($q) {
                          $q->where('full_name', 'like', '%' . $this->search . '%');
                      });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.maintenance.technician-panel', [
            'cards' => $cards
        ])->layout('layouts.app');
    }
}
