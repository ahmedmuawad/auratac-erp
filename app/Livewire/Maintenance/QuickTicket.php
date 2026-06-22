<?php

namespace App\Livewire\Maintenance;

use App\Models\Customer;
use App\Models\Item;
use App\Models\MaintenanceCard;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class QuickTicket extends Component
{
    use WithFileUploads;

    // Customer Data
    public $customer_id, $customer_name, $customer_phone, $customer_national_id;
    
    // Item Data
    public $item_name, $item_serial, $item_brand, $license_number;
    
    // Card Data
    public $services = [];          // official service checklist (labels)
    public $custom_request = '';    // free-text extra requests
    public $expected_cost_labor, $expected_cost_parts, $paid_amount;
    public $item_photo;

    public function updatedCustomerPhone($value)
    {
        if (strlen($value) >= 9) {
            $customer = Customer::where('phone', 'like', '%' . $value . '%')->first();
            if ($customer) {
                $this->customer_id = $customer->id;
                $this->customer_name = $customer->full_name; // Fixed: using full_name
                $this->customer_national_id = $customer->national_id;
            }
        }
    }

    public function save()
    {
        $this->validate([
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'item_name' => 'required',
            'expected_cost_labor' => 'nullable|numeric|min:0',
            'expected_cost_parts' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'item_photo' => 'nullable|image|max:10240', // 10MB max
        ]);

        // 1. Handle Customer
        $customer = Customer::where('phone', $this->customer_phone)->first();
        if (!$customer && $this->customer_national_id) {
            $customer = Customer::where('national_id', $this->customer_national_id)->first();
        }

        if ($customer) {
            $customer->update([
                'phone' => $this->customer_phone,
                'full_name' => $this->customer_name,
                'national_id' => $this->customer_national_id ?: $customer->national_id,
            ]);
        } else {
            $customer = Customer::create([
                'phone' => $this->customer_phone,
                'full_name' => $this->customer_name,
                'national_id' => $this->customer_national_id,
            ]);
        }

        // 2. Handle Item
        $item = Item::updateOrCreate(
            [
                'customer_id' => $customer->id,
                'item_number' => $this->item_serial
            ],
            [
                'type' => $this->item_name,
                'manufacturer' => $this->item_brand,
                'license_number' => $this->license_number,
            ]
        );

        // 3. Handle Photo
        $photoPath = null;
        if ($this->item_photo) {
            $photoPath = 'storage/' . $this->item_photo->store('items', 'public');
        }

        $total_cost = ($this->expected_cost_labor ?? 0) + ($this->expected_cost_parts ?? 0);
        $paid = $this->paid_amount ?? 0;

        // Build repair requests from official checklist + custom lines
        $repair_requests = array_values($this->services);
        foreach (preg_split('/\r\n|\r|\n/', (string) $this->custom_request) as $line) {
            if (trim($line) !== '') {
                $repair_requests[] = trim($line);
            }
        }

        // 4. Create Card
        $card = MaintenanceCard::create([
            'card_number' => 'BRQ-' . date('Y') . '-' . (MaintenanceCard::count() + 1001),
            'customer_id' => $customer->id,
            'item_id' => $item->id,
            'receiver_id' => auth()->id(),
            'repair_requests' => $repair_requests,
            'expected_cost_labor' => $this->expected_cost_labor ?? 0,
            'expected_cost_parts' => $this->expected_cost_parts ?? 0,
            'total_cost' => $total_cost,
            'paid_amount' => $paid,
            'remaining_amount' => $total_cost - $paid,
            'status' => 'pending',
            'item_image' => $photoPath,
            'payment_status' => ($paid >= $total_cost && $total_cost > 0) ? 'paid' : ($paid > 0 ? 'partially_paid' : 'unpaid'),
        ]);

        $card->notifyRoles(['technician'], 'notif_new_card', 'assignment');
        $this->sendReceiptWhatsApp($card, $customer->phone, $customer->full_name);

        session()->flash('success', __('messages.card_added_success'));
        return redirect()->route('maintenance.created', $card->id);
    }

    protected function sendReceiptWhatsApp($card, $phone, $name): void
    {
        try {
            $wa = app(\App\Services\WhatsAppService::class);
            if (! $wa->isConfigured()) {
                return;
            }
            $caption = "عميلنا العزيز {$name}،\nتم استلام قطعتك بقسم الصيانة - Aura Tac.\nرقم الكرت: {$card->card_number}\nمرفق كرت العمل (يشمل التكلفة). سنبلغك عند الجاهزية. شكراً لثقتك.";
            $pdf = app(\App\Services\CardPdfService::class)->workCard($card);
            $wa->notifyDocument($phone, $pdf, "AuraTac-{$card->card_number}.pdf", $caption);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Receipt WhatsApp failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.maintenance.quick-ticket')
            ->layout('layouts.app');
    }
}
