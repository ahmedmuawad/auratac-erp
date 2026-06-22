<?php

namespace App\Livewire\Maintenance;

use App\Models\MaintenanceCard;
use App\Models\Customer;
use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filter_status = '';
    public $showModal = false;
    public $editingCardId = null;

    // Selection logic
    public $customerSearch = '';
    public $customersList = [];
    public $selectedCustomer = null;
    public $customerItems = [];

    // Form fields
    public $customer_id = '';
    public $item_id = '';
    public $services = [];          // selected official services (labels)
    public $custom_request = '';    // free-text "أخرى"
    public $expected_cost_labor = 0;
    public $expected_cost_parts = 0;
    public $admin_notes = '';
    public $status = 'pending';

    protected $queryString = ['search', 'filter_status'];

    public function updatedCustomerSearch()
    {
        if (strlen($this->customerSearch) >= 2) {
            $this->customersList = Customer::where('full_name', 'like', '%' . $this->customerSearch . '%')
                ->orWhere('phone', 'like', '%' . $this->customerSearch . '%')
                ->take(5)
                ->get();
        } else {
            $this->customersList = [];
        }
    }

    public function selectCustomer($id)
    {
        $this->selectedCustomer = Customer::with('items')->find($id);
        $this->customer_id = $id;
        $this->customerSearch = $this->selectedCustomer->full_name;
        $this->customerItems = $this->selectedCustomer->items;
        $this->customersList = [];
    }

    public function edit($id)
    {
        $card = MaintenanceCard::with(['customer', 'item'])->findOrFail($id);
        $this->editingCardId = $id;
        $this->customer_id = $card->customer_id;
        $this->item_id = $card->item_id;
        $standard = array_values(MaintenanceCard::standardServices());
        $stored = collect($card->repair_requests ?? [])->filter(fn($v) => trim((string)$v) !== '')->values();
        $this->services = $stored->filter(fn($v) => in_array($v, $standard))->values()->all();
        $this->custom_request = $stored->reject(fn($v) => in_array($v, $standard))->implode("\n");
        $this->expected_cost_labor = $card->expected_cost_labor;
        $this->expected_cost_parts = $card->expected_cost_parts;
        $this->admin_notes = $card->admin_notes;
        $this->status = $card->status;
        
        $this->selectedCustomer = $card->customer;
        $this->customerSearch = $card->customer->full_name;
        $this->customerItems = $card->customer->items;
        
        $this->showModal = true;
    }

    public function delete($id)
    {
        MaintenanceCard::findOrFail($id)->delete();
        session()->flash('success', __('messages.card_deleted_success'));
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->customer_id = '';
        $this->item_id = '';
        $this->customerSearch = '';
        $this->selectedCustomer = null;
        $this->customerItems = [];
        $this->services = [];
        $this->custom_request = '';
        $this->expected_cost_labor = 0;
        $this->expected_cost_parts = 0;
        $this->admin_notes = '';
        $this->status = 'pending';
        $this->editingCardId = null;
    }

    public function save()
    {
        $this->validate([
            'customer_id' => 'required',
            'item_id' => 'required',
            'expected_cost_labor' => 'required|numeric|min:0',
            'expected_cost_parts' => 'required|numeric|min:0',
        ]);

        $total_cost = (float)$this->expected_cost_labor + (float)$this->expected_cost_parts;

        $repair_requests = $this->buildRepairRequests();

        if ($this->editingCardId) {
            $card = MaintenanceCard::find($this->editingCardId);
            $card->update([
                'customer_id' => $this->customer_id,
                'item_id' => $this->item_id,
                'repair_requests' => $repair_requests,
                'expected_cost_labor' => $this->expected_cost_labor,
                'expected_cost_parts' => $this->expected_cost_parts,
                'total_cost' => $total_cost,
                'admin_notes' => $this->admin_notes,
                'status' => $this->status,
            ]);
            session()->flash('success', __('messages.card_updated_success'));
        } else {
            // Generate Card Number: BRQ-YEAR-COUNT
            $count = MaintenanceCard::count() + 1001;
            $card_number = 'BRQ-' . date('Y') . '-' . $count;

            $card = MaintenanceCard::create([
                'card_number' => $card_number,
                'customer_id' => $this->customer_id,
                'item_id' => $this->item_id,
                'receiver_id' => auth()->id(),
                'repair_requests' => $repair_requests,
                'expected_cost_labor' => $this->expected_cost_labor,
                'expected_cost_parts' => $this->expected_cost_parts,
                'total_cost' => $total_cost,
                'admin_notes' => $this->admin_notes,
                'status' => 'pending',
            ]);
            $card->notifyRoles(['technician'], 'notif_new_card', 'assignment');

            try {
                $card->loadMissing('customer');
                $wa = app(\App\Services\WhatsAppService::class);
                if ($wa->isConfigured() && $card->customer?->phone) {
                    $caption = \App\Support\WaMessages::received($card->customer->full_name, $card->card_number);
                    $pdf = app(\App\Services\CardPdfService::class)->workCard($card);
                    $wa->notifyDocument($card->customer->phone, $pdf, \App\Support\WaMessages::fileName($card->card_number), $caption);
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Receipt WhatsApp failed: ' . $e->getMessage());
            }

            session()->flash('success', __('messages.card_added_success'));
        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('card-saved');
    }

    /**
     * يبني مصفوفة طلبات الإصلاح من الخدمات الرسمية المختارة + خانة "أخرى"
     */
    protected function buildRepairRequests(): array
    {
        $requests = array_values($this->services);
        foreach (preg_split('/\r\n|\r|\n/', (string) $this->custom_request) as $line) {
            if (trim($line) !== '') {
                $requests[] = trim($line);
            }
        }
        return $requests;
    }

    public function render()
    {
        $cards = MaintenanceCard::with(['customer', 'item'])
            ->when($this->search, function($query) {
                $query->where('card_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function($q) {
                        $q->where('full_name', 'like', '%' . $this->search . '%')
                          ->orWhere('phone', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->filter_status, function($query) {
                $query->where('status', $this->filter_status);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.maintenance.index', [
            'cards' => $cards
        ])->layout('layouts.app');
    }
}
