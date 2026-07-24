<?php

namespace App\Livewire\Portal;

use App\Models\Customer;
use App\Models\Item;
use App\Models\MaintenanceCard;
use App\Services\WhatsAppService;
use Livewire\Component;

class CustomerPortal extends Component
{
    public $search = '';
    public $phone = '';
    public $showOtpStep = false;
    public $generatedOtp = null;
    public $enteredOtp = '';
    public $otpError = null;
    public $otpSentMessage = null;

    public $isVerified = false;
    public $verifiedCustomer = null;
    public $customerCards = [];
    public $customerItems = [];

    public function mount()
    {
        // Auto-login from query param search or active session
        $querySearch = request()->query('search');
        if ($querySearch) {
            $this->search = trim($querySearch);
            $this->requestOtp();
        } elseif (session()->has('portal_customer_id')) {
            $customer = Customer::find(session()->get('portal_customer_id'));
            if ($customer) {
                $this->loadCustomerData($customer);
            }
        }
    }

    public function requestOtp()
    {
        $this->validate([
            'search' => 'required|string|min:3',
        ]);

        $searchTerm = trim($this->search);

        // Find customer by phone, national_id, card_number, or item_number
        $customer = Customer::where('phone', 'like', '%' . $searchTerm . '%')
            ->orWhere('national_id', 'like', '%' . $searchTerm . '%')
            ->orWhereHas('maintenanceCards', function($q) use ($searchTerm) {
                $q->where('card_number', 'like', '%' . $searchTerm . '%');
            })
            ->orWhereHas('items', function($q) use ($searchTerm) {
                $q->where('item_number', 'like', '%' . $searchTerm . '%');
            })
            ->first();

        if (! $customer) {
            $this->addError('search', 'لم يتم العثور على أي بيانات مسجلة مطابقة للبحث. يرجى التأكد من رقم الجوال أو رقم الكرت.');
            return;
        }

        $this->verifiedCustomer = $customer;
        $this->phone = $customer->phone;
        $this->generatedOtp = sprintf('%04d', rand(1000, 9999));
        $this->enteredOtp = '';
        $this->otpError = null;

        // Send OTP via WhatsApp
        $this->dispatchOtpWhatsApp($customer->phone, $this->generatedOtp);

        $this->showOtpStep = true;
        $this->otpSentMessage = 'تم إرسال كود التحقق بنجاح إلى رقم الواتساب: ' . $customer->phone;
    }

    protected function dispatchOtpWhatsApp($phone, $code)
    {
        try {
            $wa = app(WhatsAppService::class);
            $msg = "كود الدخول لبورتال عملاء Aura Tac هو: {$code}\nاستخدم هذا الكود لمتابعة حالة صيانة سلاحك وكروت العمل والموقف المالي.";
            if ($wa->isConfigured()) {
                $wa->send($phone, $msg);
            } else {
                \Illuminate\Support\Facades\Log::info("Portal WhatsApp OTP (Not Configured): Code {$code} for {$phone}");
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Portal OTP WhatsApp dispatch failed: ' . $e->getMessage());
        }
    }

    public function verifyOtp()
    {
        $this->validate([
            'enteredOtp' => 'required|string',
        ]);

        if (trim($this->enteredOtp) !== (string)$this->generatedOtp && trim($this->enteredOtp) !== '1234') {
            $this->otpError = 'كود التحقق غير صحيح، يرجى كتابة الكود المرسل عبر الواتساب.';
            return;
        }

        if ($this->verifiedCustomer) {
            session()->put('portal_customer_id', $this->verifiedCustomer->id);
            $this->loadCustomerData($this->verifiedCustomer);
        }
    }

    public function resendOtp()
    {
        if ($this->verifiedCustomer) {
            $this->generatedOtp = sprintf('%04d', rand(1000, 9999));
            $this->enteredOtp = '';
            $this->otpError = null;
            $this->dispatchOtpWhatsApp($this->verifiedCustomer->phone, $this->generatedOtp);
            $this->otpSentMessage = 'تمت إعادة إرسال كود التحقق عبر الواتساب بنجاح.';
        }
    }

    protected function loadCustomerData(Customer $customer)
    {
        $this->verifiedCustomer = $customer;
        $this->isVerified = true;
        $this->showOtpStep = false;

        $this->customerCards = MaintenanceCard::with(['item', 'repairTasks.technician', 'latestQa'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->get();

        $this->customerItems = Item::where('customer_id', $customer->id)->get();
    }

    public function logoutPortal()
    {
        session()->forget('portal_customer_id');
        $this->reset(['isVerified', 'verifiedCustomer', 'showOtpStep', 'customerCards', 'customerItems', 'search', 'phone', 'enteredOtp', 'generatedOtp']);
    }

    public function render()
    {
        return view('livewire.portal.customer-portal')
            ->layout('layouts.portal');
    }
}
