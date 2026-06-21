<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ get_setting('system_name', 'Barq Alramay') }}</title>

    <!-- Fonts: Cairo/Roboto via app.css @import; Material Symbols self-hosted (app.css @font-face) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS / JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @livewireStyles
</head>
<body x-data="{ sidebarOpen: false }" class="antialiased bg-background text-on-background font-sans">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <!-- Main Content -->
        <main class="flex-1 md:ms-64 min-h-screen flex flex-col">
            <!-- Header -->
            @include('layouts.header')

            <!-- Slot Content -->
            <div class="p-6 flex-1">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <footer class="p-4 text-center text-label-sm uppercase tracking-[0.2em] text-on-surface-variant border-t bg-surface" style="border-color:var(--md-outline-variant)">
                &copy; {{ date('Y') }} {{ get_setting('system_name', 'AURA TAC') }} — {{ get_setting('footer_text', 'Integrated Guns Room Solutions') }}
            </footer>
        </main>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
