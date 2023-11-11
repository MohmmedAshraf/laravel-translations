<div class="rounded-lg bg-white px-5 py-6 shadow sm:px-6">
    <div class="sm:flex sm:items-center">
        <div class="flex items-center sm:flex-auto space-x-3">
            <div class="h-7 flex-shrink-0">
                <x-dynamic-component class="h-7" component="flag-language-{{ $translation->language->code }}" />
            </div>
            <div class="flex items-center space-x-2">
                <div class="text-lg font-semibold text-gray-800">{{ $translation->language->name }}</div>
                <div class="text-sm text-gray-500 border rounded-md px-1.5 py-0.5">{{ $translation->language->code }}</div>
            </div>
        </div>

        <div class="mt-4 sm:mt-0 sm:ml-16 flex flex-col md:flex-row space-y-4 md:space-y-0 gap-4 w-full max-w-2xl">
            <div class="relative mt-4 sm:mt-0 w-full">
                <x-input wire:model.live="search" icon="search" type="search" placeholder="Search translations by key or value" shadowless />
            </div>
            <div class="relative mt-4 sm:mt-0 min-w-max">
                <x-native-select
                    placeholder="Status"
                    :options="$statuses"
                    wire:model.live="status"
                    option-label="label"
                    option-value="value"
                />
            </div>
            <button wire:click="$dispatch('openModal', {component: 'translations-ui::create-source-key-modal'})" type="button" class="flex-shrink-0 inline-flex space-x-2 items-center justify-center rounded-md border border-transparent bg-violet-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-violet-700 focus:outline-none sm:w-auto">
                <span class="text-sm">New Source Key</span>
                <x-iconsax-lin-add class="h-5 w-5" aria-hidden="true" />
            </button>
        </div>
    </div>
    <div class="mt-6 flex flex-col">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg overflow-x-auto">
                <div class="min-w-full divide-y divide-gray-300">
                    <div class="bg-gray-50 flex items-center">
                        <div class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 w-full max-w-24 md:max-w-xs">Key</div>
                        <div class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 w-full">File Name</div>
                        <div class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 w-full">Translation</div>
                    </div>
                    <div class="divide-y divide-gray-200 bg-white">
                        @forelse($phrases as $phrase)
                            <div class="flex items-center hover:bg-gray-50 cursor-pointer relative group">
                                <div class="py-4 pl-4 pr-3 text-sm sm:pl-6 w-full max-w-24 md:max-w-xs">
                                    <div class="flex items-center">
                                        <div class="text-sm text-gray-600 border rounded-md px-1.5 py-0.5 truncate">
                                            {{ $phrase->key }}
                                        </div>
                                    </div>
                                </div>
                                <div class="py-4 pl-4 pr-3 text-sm sm:pl-6 w-full max-w-24 md:max-w-xs">
                                    <div class="flex items-center">
                                        <div class="font-normal text-gray-900">
                                            {{ $phrase->group }}
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full py-4 pl-4 pr-3 text-sm sm:pl-6">
                                    <div class="flex items-center">
                                        <div class="font-normal text-gray-900">
                                            {{ $phrase->value }}
                                        </div>
                                    </div>
                                </div>
                                <div class="relative py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <div class="flex gap-4">
                                        <a href="{{ route('translations_ui.phrases.show', ['translation' => $phrase->translation, 'phrase' => $phrase]) }}" class="text-gray-400 hover:text-violet-700 relative z-50">
                                            <x-translations::icons.translate class="w-5 h-5" />
                                        </a>

                                        @if($phrase->translation->source)
                                            <button wire:click="confirmDelete({{ $phrase->id }})" class="text-gray-400 hover:text-red-500 relative z-50">
                                                <x-translations::icons.trash class="w-5 h-5" />
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('translations_ui.phrases.show', ['translation' => $phrase->translation, 'phrase' => $phrase]) }}" class="absolute inset-0 z-10"></a>
                            </div>
                        @empty
                            <div class="text-center py-12 text-gray-500">
                                There are no records matching the current criteria.
                            </div>
                        @endforelse
                    </div>
                </div>

                @if($phrases->hasPages())
                    <div class="px-6 border-t py-4">
                        {{ $phrases->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
