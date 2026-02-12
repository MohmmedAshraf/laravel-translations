<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        @if (file_exists(public_path('hot-translations')))
            @php($hotUrl = trim(file_get_contents(public_path('hot-translations'))))
            <link rel="icon" href="{{ $hotUrl }}/dist/favicon.svg" type="image/svg+xml">
        @else
            <link rel="icon" href="{{ asset('vendor/translations/favicon.svg') }}" type="image/svg+xml">
        @endif

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @if (file_exists(public_path('hot-translations')))
            @php($hotUrl = trim(file_get_contents(public_path('hot-translations'))))
            <script type="module" src="{{ $hotUrl . '/' . '@' . 'vite/client' }}"></script>
            <script type="module">
                import RefreshRuntime from '{{ $hotUrl . '/' . '@' . 'react-refresh' }}'
                RefreshRuntime.injectIntoGlobalHook(window)
                window.$RefreshReg$ = () => {}
                window.$RefreshSig$ = () => (type) => type
                window.__vite_plugin_react_preamble_installed__ = true
            </script>
            <script type="module" src="{{ $hotUrl }}/resources/js/app.tsx"></script>
        @else
            <link rel="stylesheet" href="{{ asset('vendor/translations/css/app.css') }}">
        @endif
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
        @if (!file_exists(public_path('hot-translations')))
            <script type="module" src="{{ asset('vendor/translations/js/app.js') }}"></script>
        @endif
    </body>
</html>
