<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AURA TAC — {{ get_setting('system_name', 'بورتال تتبع الصيانة للعميل') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased bg-background text-on-background font-sans min-h-screen flex flex-col justify-between">

    {{-- Top Bar Header --}}
    <header class="text-on-onyx border-b border-white/10 sticky top-0 z-50 shadow-md-2" style="background:#0F1C2E;">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center">
                <img src="{{ asset('images/brand/aura-tac-logo.svg') }}" alt="AURA TAC" class="h-10 w-auto">
            </a>

            <div class="flex items-center gap-4">
                <a href="{{ route('landing') }}" class="md-state flex items-center gap-2 h-10 px-4 rounded-md-xl text-label text-on-onyx-variant border border-white/10">
                    <span class="material-symbols-rounded" style="font-size:18px">home</span>
                    {{ __('messages.home') ?? 'الرئيسية' }}
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="md-btn md-btn-filled !h-10 text-label-sm font-bold">
                        {{ __('messages.dashboard') ?? 'لوحة الإدارة' }}
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Main Container --}}
    <main class="flex-1 max-w-7xl w-full mx-auto px-6 py-8">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="p-6 text-center text-label-sm uppercase tracking-widest text-on-surface-variant border-t bg-surface" style="border-color:var(--md-outline-variant)">
        &copy; {{ date('Y') }} Aura Tac - {!! str_ireplace('S-Plus', '<a href="https://s-plus.me" target="_blank" rel="noopener" class="text-primary font-bold hover:underline">S-PLUS</a>', e(get_setting('footer_text', 'تصميم وتطوير S-Plus'))) !!}
    </footer>

    @livewireScripts
</body>
</html>
