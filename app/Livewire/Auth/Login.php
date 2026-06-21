<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    public $username = '';
    public $password = '';
    public $remember = false;
    public $otp = '';
    public $step = 1; // 1: Credentials, 2: OTP

    protected function rules()
    {
        if ($this->step == 1) {
            return [
                'username' => 'required|string',
                'password' => 'required|string',
            ];
        }
        return [
            'otp' => 'required|string|size:6',
        ];
    }

    public function submitCredentials()
    {
        $this->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (! Auth::validate(['username' => $this->username, 'password' => $this->password])) {
             throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        $this->step = 2;
    }

    public function verifyOtp()
    {
        $this->validate([
            'otp' => 'required|string|size:6',
        ]);

        // ثابت حالياً كما طلب العميل
        if ($this->otp !== '123456') {
             throw ValidationException::withMessages([
                'otp' => __('messages.otp_invalid'),
            ]);
        }

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }
    }

    public function backToStepOne()
    {
        $this->step = 1;
        $this->otp = '';
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}
