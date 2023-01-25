<div class="col-span-1 bg-white rounded-md overflow-hidden border">
    <div class="flex items-center border-b space-x-2 px-3 py-2">
        <div class="h-5 flex-shrink-0">
            <x-dynamic-component class="h-5" component="flag-language-{{ $phrase->translation->language->code }}" />
        </div>
        <div class="flex items-center space-x-2">
            <div class="text-sm font-semibold text-gray-800">{{ $phrase->translation->language->name }}</div>
            <div class="text-xs text-gray-500 border rounded-md px-1.5 py-0.5">{{ $phrase->translation->language->code }}</div>
        </div>
    </div>
    <div class="w-full p-3">
        @livewire('translations-ui::source-phrase', ['phrase' => $phrase])
    </div>
    <div class="w-full p-3 grid grid-cols-2 gap-6">
        <div class="w-full border py-2 px-4 flex items-center rounded-md space-x-2 bg-gray-50">
            <x-iconsax-lin-folder class="h-5 text-gray-500" />
            <span class="text-sm text-gray-500 truncate">{{ $phrase->file->file_name }}</span>
        </div>

        <div class="w-full border py-2 px-4 flex items-center rounded-md space-x-2 bg-gray-50">
            <svg class="h-5 text-gray-500" viewBox="0 0 24 24">
                <path fill="currentColor" d="M21 18h-6v-3h-1.7c-1.1 2.4-3.6 4-6.3 4c-3.9 0-7-3.1-7-7s3.1-7 7-7c2.7 0 5.2 1.6 6.3 4H24v6h-3v3m-4-2h2v-3h3v-2H11.9l-.2-.7C11 8.3 9.1 7 7 7c-2.8 0-5 2.2-5 5s2.2 5 5 5c2.1 0 4-1.3 4.7-3.3l.2-.7H17v3M7 15c-1.7 0-3-1.3-3-3s1.3-3 3-3s3 1.3 3 3s-1.3 3-3 3m0-4c-.6 0-1 .4-1 1s.4 1 1 1s1-.4 1-1s-.4-1-1-1Z"/>
            </svg>
            <span class="text-sm text-gray-500 truncate">{{ $phrase->key }}</span>
        </div>
    </div>
</div>
