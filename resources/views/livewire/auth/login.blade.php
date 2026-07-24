<div class="min-h-screen flex flex-col lg:flex-row w-full bg-white text-gray-900 font-sans selection:bg-amber-400 selection:text-black">

    {{-- Side 1: Form Side (50% Width) --}}
    <div class="w-full lg:w-1/2 min-h-screen flex flex-col justify-between p-6 sm:p-12 lg:p-16 bg-white z-10">
        
        {{-- Top Bar Action Links --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition-all">
                <span class="material-symbols-rounded text-base">arrow_forward</span>
                <span>{{ __('messages.back_to_landing') }}</span>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('portal.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100 text-xs font-bold transition-all">
                    <span class="material-symbols-rounded text-base">person_search</span>
                    <span>{{ __('messages.customer_portal') }}</span>
                </a>
                <a href="{{ url('lang/' . (app()->getLocale() == 'ar' ? 'en' : 'ar')) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-semibold transition-all">
                    <span class="material-symbols-rounded text-base">language</span>
                    <span>{{ app()->getLocale() == 'ar' ? 'ENGLISH' : 'العربية' }}</span>
                </a>
            </div>
        </div>

        {{-- Form Content Box --}}
        <div class="max-w-md w-full mx-auto py-8 space-y-8">
            
            {{-- Brand Logo & System Name from Settings --}}
            @php
                $logoPath = get_setting('logo_path', 'logo.png');
                $systemName = get_setting('system_name', 'AURA TAC');
                $systemNameEn = get_setting('system_name_en', 'ARMAMENT MAINTENANCE');
            @endphp
            <div class="space-y-4">
                <div class="w-20 h-20 rounded-2xl bg-gray-900 p-3.5 shadow-lg flex items-center justify-center border border-gray-800">
                    <img src="{{ asset($logoPath) }}" alt="{{ $systemName }}" class="w-full h-full object-contain">
                </div>

                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">{{ __('messages.admin_login_title') }}</h1>
                    <p class="text-xs text-amber-700 font-mono font-bold uppercase tracking-widest mt-1">{{ $systemName }} — {{ $systemNameEn }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ __('messages.enter_credentials_subtitle') }}</p>
                </div>
            </div>

            @if($step == 1)
                {{-- Credentials Form --}}
                <form wire:submit="submitCredentials" class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">{{ __('messages.username') }}</label>
                        <div class="relative">
                            <span class="material-symbols-rounded absolute inset-y-0 start-3.5 flex items-center text-amber-600 text-xl pointer-events-none">alternate_email</span>
                            <input wire:model="username" type="text" autofocus
                                   class="w-full h-12 ps-11 pe-4 bg-gray-50 text-gray-900 rounded-lg border border-gray-300 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 text-sm font-medium outline-none transition-all placeholder:text-gray-400"
                                   placeholder="{{ __('messages.enter_username') }}">
                        </div>
                        @error('username') <span class="text-xs text-rose-500 font-bold mt-1.5 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">{{ __('messages.password') }}</label>
                        <div class="relative">
                            <span class="material-symbols-rounded absolute inset-y-0 start-3.5 flex items-center text-amber-600 text-xl pointer-events-none">lock</span>
                            <input wire:model="password" type="password"
                                   class="w-full h-12 ps-11 pe-4 bg-gray-50 text-gray-900 rounded-lg border border-gray-300 focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-500/20 text-sm font-medium outline-none transition-all placeholder:text-gray-400"
                                   placeholder="{{ __('messages.enter_password') }}">
                        </div>
                        @error('password') <span class="text-xs text-rose-500 font-bold mt-1.5 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="remember" class="w-4 h-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-xs font-semibold text-gray-600">{{ __('messages.remember_me') }}</span>
                        </label>
                    </div>

                    <button type="submit" wire:loading.attr="disabled" class="w-full h-12 rounded-lg bg-gradient-to-r from-amber-500 to-amber-600 hover:brightness-110 text-black font-extrabold text-sm transition-all flex items-center justify-center gap-2 shadow-lg shadow-amber-500/20">
                        <span wire:loading.remove>{{ __('messages.login') }}</span>
                        <span wire:loading>{{ __('messages.processing') }}...</span>
                        <span wire:loading.remove class="material-symbols-rounded text-lg">arrow_back</span>
                    </button>
                </form>
            @else
                {{-- Step 2 OTP Form --}}
                <form wire:submit="verifyOtp" class="space-y-6">
                    <div class="text-center space-y-3">
                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900 font-medium">
                            {{ __('messages.otp_sent_msg') }}
                        </div>
                        <input wire:model="otp" type="text" maxlength="6" dir="ltr" autofocus
                               class="w-full h-16 rounded-xl bg-gray-50 text-center text-3xl font-bold font-mono text-amber-700 tracking-[0.6rem] border-2 border-amber-400 focus:border-amber-600 focus:bg-white outline-none transition-all"
                               placeholder="------">
                        @error('otp') <span class="text-xs text-rose-500 font-bold block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled" class="w-full h-12 rounded-lg bg-gray-900 text-white font-bold text-sm hover:bg-gray-800 transition-all">
                        <span wire:loading.remove>{{ __('messages.verify_and_login') }}</span>
                        <span wire:loading>{{ __('messages.verifying') }}...</span>
                    </button>
                    <button type="button" wire:click="backToStepOne" class="w-full text-xs text-gray-500 hover:text-gray-900 font-semibold py-2">
                        {{ __('messages.back_to_login') }}
                    </button>
                </form>
            @endif

        </div>

        {{-- Footer --}}
        <div class="text-xs text-gray-400 text-center sm:text-start flex flex-col sm:flex-row items-center justify-between gap-2 border-t border-gray-100 pt-6">
            <span>{{ $systemName }} SYSTEM {{ date('Y') }} ©</span>
            <span>{{ get_setting('footer_text', 'جميع الحقوق محفوظة') }}</span>
        </div>
    </div>

    {{-- Side 2: Hero Image & Activity Background Side (50% Width) --}}
    @php
        $loginBg = get_setting('login_bg_image');
        $hasCustomBg = !empty($loginBg) && file_exists(public_path($loginBg));
    @endphp
    <div class="hidden lg:flex lg:w-1/2 min-h-screen relative flex-col justify-between p-12 bg-gray-900 text-white overflow-hidden">
        {{-- Background Image --}}
        @if($hasCustomBg)
            <img src="{{ asset($loginBg) }}" alt="{{ $systemName }}" class="absolute inset-0 w-full h-full object-cover opacity-60 scale-105">
        @else
            {{-- Default High Quality Tactical Armament Background Mesh --}}
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-stone-900 to-black opacity-95"></div>
            <div class="absolute top-1/4 start-1/4 w-96 h-96 bg-amber-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 end-10 w-96 h-96 bg-amber-600/10 rounded-full blur-3xl"></div>
        @endif

        {{-- Dark Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>

        {{-- Top Right Watermark Badge --}}
        <div class="relative z-10 text-end">
            <span class="px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md text-amber-400 font-mono text-xs font-bold border border-white/15">
                {{ $systemNameEn }}
            </span>
        </div>

        {{-- Bottom Overlay Content (Matching System Logo & Name from Settings) --}}
        <div class="relative z-10 space-y-4 max-w-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gray-900/90 p-2 border border-amber-500/30 flex items-center justify-center">
                    <img src="{{ asset($logoPath) }}" alt="{{ $systemName }}" class="w-full h-full object-contain">
                </div>
                <span class="text-2xl font-black tracking-wider text-white">{{ $systemName }}</span>
            </div>

            <p class="text-base text-gray-200 leading-relaxed font-light">
                {{ __('messages.manage_all_items') }} — {{ __('messages.manage_maintenance_workflow') }}
            </p>

            <div class="pt-2 flex items-center gap-4 text-xs font-mono text-gray-400 border-t border-white/10">
                <span>WhatsApp OTP 🟢</span>
                <span>•</span>
                <span>QA Certified 🛡️</span>
                <span>•</span>
                <span>15% VAT 💳</span>
            </div>
        </div>
    </div>

</div>
