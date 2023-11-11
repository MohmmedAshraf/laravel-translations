<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel Translations UI') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

        <!-- Scripts -->
        <wireui:scripts />
        <script src="{{ asset('vendor/translations/app.js') }}" defer></script>
        <link rel="stylesheet" href="{{ asset('vendor/translations/app.css') }}">
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-full">
            <x-translations::header />

            <main class="-mt-32">
                <div class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @livewire('livewire-ui-modal')
        <x-notifications z-index="z-50" />
        <x-dialog z-index="z-50" blur="md" align="center" />

        @stack('scripts')
    </body>
</html>
