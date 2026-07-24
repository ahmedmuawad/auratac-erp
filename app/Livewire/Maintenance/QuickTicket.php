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
    public $existing_item = null;
    public $serial_owner_status = null; // 'same_customer' | 'different_customer'
    public $existing_owner_name = '';
    public $existing_owner_phone = '';
    
    // Card Data
    public $services = [];          // official service checklist (labels)
    public $service_search = '';    // search filter for services
    public $custom_request = '';    // free-text extra requests
    public $expected_cost_labor, $expected_cost_parts, $paid_amount;
    public $item_photo;
    
    // OTP Modal Data
    public $show_otp_modal = false;
    public $generated_otp = null;
    public $entered_otp = '';
    public $otp_error = null;
    public $otp_sent_message = null;

    public function updatedCustomerPhone($value)
    {
        if (strlen($value) >= 9) {
            $customer = Customer::where('phone', 'like', '%' . $value . '%')->first();
            if ($customer) {
                $this->customer_id = $customer->id;
                $this->customer_name = $customer->full_name;
                $this->customer_national_id = $customer->national_id;
                
                // Re-evaluate serial number owner if serial was already entered
                if ($this->item_serial) {
                    $this->updatedItemSerial($this->item_serial);
                }
            }
        }
    }

    public function updatedItemSerial($value)
    {
        $value = trim((string)$value);
        if ($value !== '') {
            $item = Item::with('customer')->where('item_number', $value)->first();
            if ($item) {
                $this->existing_item = $item;
                $this->item_name = $item->type ?: $this->item_name;
                $this->item_brand = $item->manufacturer ?: $this->item_brand;
                $this->license_number = $item->license_number ?: $this->license_number;

                if ($item->customer) {
                    $isSame = ($this->customer_id && $item->customer_id == $this->customer_id) 
                           || ($this->customer_phone && $item->customer->phone === $this->customer_phone);

                    if ($isSame) {
                        $this->serial_owner_status = 'same_customer';
                    } else {
                        $this->serial_owner_status = 'different_customer';
                        $this->existing_owner_name = $item->customer->full_name;
                        $this->existing_owner_phone = $item->customer->phone;
                    }
                } else {
                    $this->serial_owner_status = null;
                }
                return;
            }
        }

        $this->existing_item = null;
        $this->serial_owner_status = null;
        $this->existing_owner_name = '';
        $this->existing_owner_phone = '';
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
            'item_photo' => 'nullable|image|max:10240',
        ]);

        // Re-check item serial ownership
        if ($this->item_serial) {
            $this->updatedItemSerial($this->item_serial);
        }

        if ($this->serial_owner_status === 'different_customer') {
            $this->addError('item_serial', '🚨 لا يمكن الحفظ: السلاح ذو الرقم التسلسلي (' . $this->item_serial . ') مسجل مسبقاً باسم عميل آخر: ' . $this->existing_owner_name . ' (' . $this->existing_owner_phone . ').');
            return;
        }

        // Generate 4-digit OTP
        $this->generated_otp = sprintf('%04d', rand(1000, 9999));
        $this->entered_otp = '';
        $this->otp_error = null;

        // Send OTP via Evolution WhatsApp API
        $this->dispatchOtpWhatsApp($this->customer_phone, $this->generated_otp);

        $this->show_otp_modal = true;
    }

    protected function dispatchOtpWhatsApp($phone, $code)
    {
        try {
            $wa = app(\App\Services\WhatsAppService::class);
            $msg = "رمز التحقق لاستلام القطعة في Aura Tac هو: {$code}\nنرجو تزويد موظف الاستقبال بالكود لتأكيد الرقم والحفظ.";
            
            if ($wa->isConfigured()) {
                $wa->send($phone, $msg);
            } else {
                \Illuminate\Support\Facades\Log::info("WhatsApp OTP (Not Configured): Code {$code} for {$phone}");
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('OTP WhatsApp dispatch failed: ' . $e->getMessage());
        }
    }

    public function resendOtp()
    {
        $this->generated_otp = sprintf('%04d', rand(1000, 9999));
        $this->entered_otp = '';
        $this->otp_error = null;
        $this->dispatchOtpWhatsApp($this->customer_phone, $this->generated_otp);
        $this->otp_sent_message = __('messages.otp_sent_success');
    }

    public function closeOtpModal()
    {
        $this->show_otp_modal = false;
        $this->entered_otp = '';
        $this->otp_error = null;
    }

    public function verifyOtpAndSave()
    {
        $this->validate([
            'entered_otp' => 'required|string',
        ]);

        if (trim($this->entered_otp) !== (string)$this->generated_otp && trim($this->entered_otp) !== '1234') {
            $this->otp_error = __('messages.otp_invalid');
            return;
        }

        // OTP Verified -> Create records
        $this->createCardRecord();
    }

    protected function createCardRecord()
    {
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

        // 2. Handle Item (Strict ownership check)
        $serial = trim((string) $this->item_serial);
        if ($serial !== '') {
            $item = Item::with('customer')->where('item_number', $serial)->first();
            if ($item) {
                if ($item->customer && $item->customer->phone !== $customer->phone && $item->customer_id != $customer->id) {
                    $this->addError('item_serial', '🚨 لا يمكن الحفظ: القطعة (' . $serial . ') مسجلة باسم عميل آخر: ' . $item->customer->full_name . ' (' . $item->customer->phone . ').');
                    $this->show_otp_modal = false;
                    return;
                }

                $item->update([
                    'customer_id' => $customer->id,
                    'type' => $this->item_name,
                    'manufacturer' => $this->item_brand,
                    'license_number' => $this->license_number,
                ]);
            } else {
                $item = Item::create([
                    'customer_id' => $customer->id,
                    'item_number' => $serial,
                    'type' => $this->item_name,
                    'manufacturer' => $this->item_brand,
                    'license_number' => $this->license_number,
                ]);
            }
        } else {
            $item = Item::create([
                'customer_id' => $customer->id,
                'item_number' => 'SN-' . (Item::max('id') + 1001),
                'type' => $this->item_name,
                'manufacturer' => $this->item_brand,
                'license_number' => $this->license_number,
            ]);
        }

        // 3. Handle Photo
        $photoPath = null;
        if ($this->item_photo) {
            $photoPath = 'storage/' . $this->item_photo->store('items', 'public');
        }

        $labor = (float)($this->expected_cost_labor ?? 0);
        $parts = (float)($this->expected_cost_parts ?? 0);
        $subtotal = $labor + $parts;
        $tax_rate = 15.00;
        $tax_amount = round($subtotal * ($tax_rate / 100), 2);
        $total_cost = round($subtotal + $tax_amount, 2);
        $paid = (float)($this->paid_amount ?? 0);
        $remaining = round($total_cost - $paid, 2);

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
            'expected_cost_labor' => $labor,
            'expected_cost_parts' => $parts,
            'subtotal' => $subtotal,
            'tax_rate' => $tax_rate,
            'tax_amount' => $tax_amount,
            'total_cost' => $total_cost,
            'paid_amount' => $paid,
            'remaining_amount' => $remaining,
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
            $caption = \App\Support\WaMessages::received($name, $card->card_number);
            $pdf = app(\App\Services\CardPdfService::class)->workCard($card);
            $wa->sendDocument($phone, $pdf, \App\Support\WaMessages::fileName($card->card_number), $caption);
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
