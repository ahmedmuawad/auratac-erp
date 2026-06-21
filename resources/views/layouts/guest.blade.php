<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.login') }} - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased font-sans bg-onyx text-on-onyx min-h-screen overflow-hidden">

    {{-- Ambient bronze glow --}}
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute -top-20 start-1/4 w-96 h-96 rounded-full blur-3xl opacity-20" style="background:#8A6A3D"></div>
        <div class="absolute -bottom-24 end-1/4 w-96 h-96 rounded-full blur-3xl opacity-10" style="background:#8A6A3D"></div>
    </div>

    <div class="relative z-10 min-h-screen flex items-center justify-center p-6">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
