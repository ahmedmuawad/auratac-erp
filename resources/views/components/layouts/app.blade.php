<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Barq Alramay') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- CSS / JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @livewireStyles
</head>
<body x-data="{ sidebarOpen: false }" class="antialiased bg-slate-50 text-slate-900 {{ app()->getLocale() == 'ar' ? 'font-tajawal' : 'font-inter' }}">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <main class="flex-1 md:ms-64 min-h-screen flex flex-col">
            <!-- Header -->
            @include('layouts.header')

            <!-- Slot Content -->
            <div class="p-6 flex-1">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <footer class="p-4 text-center text-sm text-slate-500 border-t bg-white">
                &copy; {{ date('Y') }} Aura Tac - {{ get_setting('footer_text', 'تصميم وتطوير S-Plus') }}
            </footer>
        </main>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
