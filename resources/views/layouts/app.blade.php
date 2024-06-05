<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased">
<x-banner/>

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div
        class="w-64 bg-white dark:bg-gray-800 flex flex-col space-y-4 border-r border-gray-200 dark:border-gray-700 overflow-y-auto">
        @include('sidenav')
    </div>

    <!-- Content Area -->
    <div class="flex-1 bg-gray-100 dark:bg-gray-900 overflow-y-auto">
        <div class="min-h-screen">
            <!-- Page Content -->

            <main class="p-4">
                @if (is_null(auth()->user()->two_factor_secret) && !request()->routeIs('profile.show'))
                    <div class="text-white p-1.5 flex items-center rounded-md flex-col">
                        <div class="dark:bg-red-600 dark:bg-opacity-40 p-4 rounded-md flex flex-col items-center">
                            <h1 class="text-2xl">2FA is not enabled!</h1>
                            <span>Please set up 2FA in your account settings.</span>
                            <x-button class="mt-2 grid items-center" wire:navigate.hover href="{{ route('profile.show') }}">
                                Go to settings
                            </x-button>
                        </div>
                    </div>
                @else
                    {{ $slot }}
                @endif
            </main>
        </div>
    </div>
</div>

@stack('modals')

@livewireScripts
</body>
</html>
