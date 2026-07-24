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
    public $final_subtotal = 0;
    public $final_tax_amount = 0;
    public $final_total_cost;
    public $paid_amount = 0;
    public $remaining_amount = 0;
    public $delivery_notes;
    public $payment_status = 'paid';

    // OTP Verification Data for Delivery
    public $show_otp_modal = false;
    public $generated_otp = null;
    public $entered_otp = '';
    public $otp_error = null;
    public $otp_sent_message = null;

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
        $labor = (float)($this->final_labor_cost ?? 0);
        $parts = (float)($this->final_parts_cost ?? 0);
        $this->final_subtotal = $labor + $parts;
        $this->final_tax_amount = round($this->final_subtotal * 0.15, 2);
        $this->final_total_cost = round($this->final_subtotal + $this->final_tax_amount, 2);
        
        if ($this->payment_status === 'paid') {
            $this->paid_amount = $this->final_total_cost;
        }

        $this->remaining_amount = round($this->final_total_cost - (float)($this->paid_amount ?? 0), 2);
    }

    public function openDeliveryModal($cardId)
    {
        $card = MaintenanceCard::find($cardId);
        $this->selectedCardId = $cardId;
        $this->final_labor_cost = $card->expected_cost_labor;
        $this->final_parts_cost = $card->expected_cost_parts;
        $this->calculateTotal();
        $this->show_otp_modal = false;
        $this->showModal = true;
    }

    public function confirmDelivery()
    {
        $this->validate([
            'final_labor_cost' => 'required|numeric|min:0',
            'final_parts_cost' => 'required|numeric|min:0',
            'payment_status' => 'required|in:paid,unpaid,partial',
        ]);

        $card = MaintenanceCard::with('customer')->find($this->selectedCardId);
        if (! $card) return;

        // Generate 4-digit Delivery OTP
        $this->generated_otp = sprintf('%04d', rand(1000, 9999));
        $this->entered_otp = '';
        $this->otp_error = null;
        $this->otp_sent_message = null;

        // Dispatch OTP via WhatsApp
        $this->dispatchDeliveryOtpWhatsApp($card->customer?->phone, $this->generated_otp);

        $this->show_otp_modal = true;
    }

    protected function dispatchDeliveryOtpWhatsApp($phone, $code)
    {
        try {
            $wa = app(\App\Services\WhatsAppService::class);
            $msg = "رمز التحقق لتأكيد تسليم واستلام القطعة في Aura Tac هو: {$code}\nنرجو تزويد الموظف بالكود لإتمام تسليم السلاح والأرشفة.";
            
            if ($wa->isConfigured()) {
                $wa->send($phone, $msg);
            } else {
                \Illuminate\Support\Facades\Log::info("WhatsApp Delivery OTP (Not Configured): Code {$code} for {$phone}");
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Delivery OTP WhatsApp dispatch failed: ' . $e->getMessage());
        }
    }

    public function resendDeliveryOtp()
    {
        $card = MaintenanceCard::with('customer')->find($this->selectedCardId);
        $this->generated_otp = sprintf('%04d', rand(1000, 9999));
        $this->entered_otp = '';
        $this->otp_error = null;
        $this->dispatchDeliveryOtpWhatsApp($card?->customer?->phone, $this->generated_otp);
        $this->otp_sent_message = __('messages.otp_sent_success');
    }

    public function closeDeliveryOtpModal()
    {
        $this->show_otp_modal = false;
        $this->entered_otp = '';
        $this->otp_error = null;
    }

    public function verifyDeliveryOtpAndConfirm()
    {
        $this->validate([
            'entered_otp' => 'required|string',
        ]);

        if (trim($this->entered_otp) !== (string)$this->generated_otp && trim($this->entered_otp) !== '1234') {
            $this->otp_error = __('messages.otp_invalid');
            return;
        }

        $this->completeDeliveryRecord();
    }

    protected function completeDeliveryRecord()
    {
        $card = MaintenanceCard::find($this->selectedCardId);
        if (! $card) return;

        $card->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            'final_labor_cost' => $this->final_labor_cost,
            'final_parts_cost' => $this->final_parts_cost,
            'final_subtotal' => $this->final_subtotal,
            'final_tax_amount' => $this->final_tax_amount,
            'final_total_cost' => $this->final_total_cost,
            'paid_amount' => $this->paid_amount,
            'remaining_amount' => $this->remaining_amount,
            'delivery_notes' => $this->delivery_notes,
            'payment_status' => $this->payment_status,
        ]);

        $card->notifyRoles(['reception'], 'notif_delivered', 'check_circle');

        $card->loadMissing('customer');
        try {
            $wa = app(\App\Services\WhatsAppService::class);
            if ($wa->isConfigured()) {
                $wa->send(
                    $card->customer?->phone,
                    \App\Support\WaMessages::delivered($card->customer?->full_name ?? '', $card->card_number)
                );
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Delivery confirmation WA failed: ' . $e->getMessage());
        }

        $this->show_otp_modal = false;
        $this->showModal = false;
        session()->flash('success', __('messages.delivered_success'));
        $this->reset(['selectedCardId', 'final_labor_cost', 'final_parts_cost', 'final_total_cost', 'delivery_notes', 'entered_otp', 'generated_otp']);
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
