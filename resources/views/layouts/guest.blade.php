<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TASPEN Gudang') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#EAF3FF] bg-opacity-70 relative overflow-hidden">
            
            <!-- Decorative Background Elements -->
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-[#1557A6] rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
            <div class="absolute top-[20%] right-[-10%] w-72 h-72 bg-taspen-yellow rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
            <div class="absolute bottom-[-20%] left-[20%] w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-xl overflow-hidden sm:rounded-2xl border border-white/50 relative z-10 backdrop-blur-sm">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center text-sm text-gray-500 relative z-10 font-medium">
                &copy; {{ date('Y') }} PT TASPEN (Persero) Kantor Cabang Jember.<br>Hak Cipta Dilindungi Undang-Undang.
            </div>
        </div>
    </body>
</html>
