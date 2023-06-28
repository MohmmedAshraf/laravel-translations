<x-translations::layouts.app>
    @if($installed)
        @livewire('translations-ui::translations-list')
    @else
        <div class="w-full bg-white rounded-md shadow flex min-h-36 py-12 px-6">
            <div class="w-full flex flex-col items-center justify-center">
                <x-translations::icons.translate class="h-12 text-gray-500" />
                <span class="text-sm text-gray-500 mt-8">No translations found!,</span>
                <span class="text-sm text-gray-500 mt-2">Please run the following command to import your translations</span>
                <code class="text-sm text-gray-700 border rounded-md mt-4 px-3 py-1">php artisan translations:import</code>
            </div>
        </div>
    @endif
</x-translations::layouts.app>
