<div class="w-full max-w-md">
    {{-- Language switcher --}}
    <div class="flex justify-end mb-6">
        <a href="{{ url('lang/' . (app()->getLocale() == 'ar' ? 'en' : 'ar')) }}"
           class="md-state flex items-center gap-2 h-10 px-4 rounded-md-xl text-label text-on-onyx-variant border border-white/10">
            <span class="material-symbols-rounded" style="font-size:18px">language</span>
            {{ app()->getLocale() == 'ar' ? 'English' : 'العربية' }}
        </a>
    </div>

    {{-- Login card --}}
    <div class="bg-onyx-surface rounded-md-xl border border-white/10 shadow-md-4 p-8 md:p-10">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-md-lg shadow-md-2 mb-5 p-3">
                <img src="{{ asset(get_setting('logo_path', 'logo.png')) }}" alt="Aura Tac" class="w-full h-full object-contain">
            </div>
            <h1 class="text-display text-on-onyx tracking-wide">{{ get_setting('system_name', 'AURA TAC') }}</h1>
            <div class="h-0.5 w-16 bg-primary mx-auto my-3 rounded-full"></div>
            <p class="text-label-sm text-primary uppercase tracking-[0.3em]">{{ get_setting('system_name_en', 'Maintenance System') }}</p>
            <p class="text-body text-on-onyx-variant mt-4">
                @if($step == 1) {{ __('messages.welcome_back') }} @else {{ __('messages.otp_verification') }} @endif
            </p>
        </div>

        @if($step == 1)
            <form wire:submit="submitCredentials" class="space-y-5">
                <div>
                    <label class="block text-label text-primary mb-1.5">{{ __('messages.username') }}</label>
                    <div class="relative">
                        <span class="material-symbols-rounded absolute inset-y-0 start-3 flex items-center text-on-onyx-variant" style="font-size:20px">person</span>
                        <input wire:model="username" type="text"
                               class="w-full h-12 ps-11 pe-4 rounded-md-sm bg-onyx text-on-onyx border border-white/10 focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none transition-all placeholder:text-on-onyx-variant/60"
                               placeholder="{{ __('messages.enter_username') }}">
                    </div>
                    @error('username') <span class="text-label-sm text-error mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-label text-primary mb-1.5">{{ __('messages.password') }}</label>
                    <div class="relative">
                        <span class="material-symbols-rounded absolute inset-y-0 start-3 flex items-center text-on-onyx-variant" style="font-size:20px">lock</span>
                        <input wire:model="password" type="password"
                               class="w-full h-12 ps-11 pe-4 rounded-md-sm bg-onyx text-on-onyx border border-white/10 focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none transition-all placeholder:text-on-onyx-variant/60"
                               placeholder="{{ __('messages.enter_password') }}">
                    </div>
                    @error('password') <span class="text-label-sm text-error mt-1 block">{{ $message }}</span> @enderror
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" wire:model="remember" class="w-4 h-4 accent-[#8A6A3D]">
                    <span class="text-label text-on-onyx-variant">{{ __('messages.remember_me') }}</span>
                </label>

                <button type="submit" wire:loading.attr="disabled"
                        class="md-btn md-btn-filled w-full h-12">
                    <span wire:loading.remove>{{ __('messages.login_button') }}</span>
                    <span wire:loading>{{ __('messages.processing') }}...</span>
                    <span wire:loading.remove class="material-symbols-rounded" style="font-size:20px">arrow_back</span>
                </button>
            </form>
        @else
            <form wire:submit="verifyOtp" class="space-y-6">
                <div class="text-center">
                    <p class="text-body text-on-onyx-variant mb-5">{{ __('messages.otp_sent_msg') }}</p>
                    <input wire:model="otp" type="text" maxlength="6" dir="ltr"
                           class="w-full h-16 rounded-md-md bg-onyx text-center text-3xl font-bold text-primary tracking-[0.8rem] border border-primary/30 focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none transition-all"
                           placeholder="------">
                    @error('otp') <span class="text-label-sm text-error mt-2 block">{{ $message }}</span> @enderror
                </div>

                <button type="submit" wire:loading.attr="disabled" class="md-btn md-btn-filled w-full h-12">
                    <span wire:loading.remove>{{ __('messages.verify_and_login') }}</span>
                    <span wire:loading>{{ __('messages.verifying') }}...</span>
                </button>
                <button type="button" wire:click="backToStepOne" class="md-btn md-btn-text w-full text-on-onyx-variant">
                    {{ __('messages.back_to_login') }}
                </button>
            </form>
        @endif
    </div>

    <div class="text-center mt-8">
        <p class="text-label-sm text-on-onyx-variant uppercase tracking-[0.3em] opacity-60">AURA TAC · {{ date('Y') }}</p>
    </div>
</div>
