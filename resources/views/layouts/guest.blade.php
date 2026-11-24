<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laundrymam</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased">
        <div class="flex min-h-screen flex-col items-center justify-center bg-gradient-to-br from-indigo-50 via-slate-50 to-sky-100 px-4 py-10 sm:px-6">
            <div class="mb-6 text-center">
                <a href="/" class="inline-flex flex-col items-center gap-3">
                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-indigo-600 shadow-lg shadow-indigo-100 ring-1 ring-indigo-100">
                        <x-application-logo class="h-10 w-10 fill-current" />
                    </span>
                    <span class="text-xl font-bold tracking-tight text-slate-800">Laundrymam</span>
                </a>
            </div>

            <div class="w-full max-w-md overflow-hidden rounded-2xl border border-white/80 bg-white/95 px-6 py-7 shadow-xl shadow-slate-200/70 sm:px-8">
                {{ $slot }}
            </div>
            <p class="mt-6 text-center text-xs text-slate-500">Layanan laundry mudah, cepat, dan terpercaya.</p>
        </div>
    </body>
</html>
