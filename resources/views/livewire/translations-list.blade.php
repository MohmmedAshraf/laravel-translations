<div class="rounded-lg bg-white px-5 py-6 shadow sm:px-6">
    <div class="sm:flex sm:items-center">
        <div class="flex items-center sm:flex-auto space-x-3">
            <h1 class="text-xl font-semibold text-gray-900">Translations</h1>
        </div>

        <div class="mt-4 sm:mt-0 sm:ml-16 flex flex-col md:flex-row space-y-4 md:space-y-0 gap-4 w-full max-w-2xl">
            <div class="relative mt-4 sm:mt-0 w-full">
                <x-input wire:model.live="search" icon="search" type="search" placeholder="Search languages by name or code" shadowless />
            </div>
            <button wire:click="$dispatch('openModal', {component: 'translations-ui::create-translation-modal'})" type="button" class="flex-shrink-0 inline-flex space-x-2 items-center justify-center rounded-md border border-transparent bg-violet-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-violet-700 focus:outline-none sm:w-auto">
                <span class="text-sm">New Language</span>
                <x-iconsax-lin-add class="h-5 w-5" aria-hidden="true" />
            </button>
        </div>
    </div>
    <div class="mt-6 flex flex-col">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg overflow-x-auto">
                <div class="min-w-full divide-y divide-gray-300">
                    <div class="bg-gray-50 flex items-center">
                        <div class="w-full py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                            Name
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200 bg-white">
                        @forelse($translations as $translation)
                            <div class="hover:bg-gray-50 cursor-pointer relative flex">
                                <div class="w-full py-4 pl-4 pr-3 text-sm sm:pl-6">
                                    <div class="flex items-center">
                                        <div class="h-6 w-6 flex-shrink-0">
                                            <x-dynamic-component class="w-6 h-6" component="flag-language-{{ $translation->language->code }}" />
                                        </div>
                                        <div class="ml-4 flex items-center space-x-2">
                                            <div class="font-semibold text-gray-900">{{ $translation->language->name }}</div>
                                            <div class="text-xs text-gray-500 border rounded-md px-1.5 py-0.5">{{ $translation->language->code }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full flex items-center z-10">
                                    @if(! $translation->source)
                                        <div class="flex justify-center mx-auto w-full max-w-xs px-0 sm:px-6 group relative">
                                            <div class="translation-progress w-full rounded-full overflow-hidden bg-gray-200">
                                                <div class="h-2 bg-green-600" style="width: {{ $this->getTranslationProgressPercentage($translation) }}%"></div>
                                            </div>
                                            <div class="absolute -top-8 group-hover:opacity-100 w-full max-w-xs px-0 sm:px-6 mx-auto opacity-0 transition-opacity">
                                                <span class="flex bg-gray-600 w-full bg-opacity-60 px-2 py-1 rounded-md text-gray-100 text-sm">
                                                    Phrase Translated {{ $this->getTranslationProgressPercentage($translation) }}%
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="w-full relative py-4 pl-3 pr-4 text-sm font-medium sm:pr-6">
                                    <div class="flex gap-3 items-center">
                                        <a href="{{ route('translations_ui.phrases.index', $translation) }}" class="text-gray-400 hover:text-violet-700 ml-auto relative z-50">
                                            @if(! $translation->source)
                                                <x-translations::icons.translate class="w-5 h-5" />
                                            @else
                                                <x-iconsax-lin-setting-2 class="w-5 h-5" />
                                            @endif
                                        </a>
                                        @if(! $translation->source)
                                            <button wire:click="confirmDelete({{ $translation->id }})" class="text-gray-400 hover:text-red-500 relative z-50">
                                                <x-translations::icons.trash class="w-5 h-5" />
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('translations_ui.phrases.index', $translation) }}" class="absolute inset-0"></a>
                            </div>
                        @empty
                            <div class="text-center py-12 text-gray-500">
                                There are no records matching the current criteria.
                            </div>
                        @endforelse
                    </div>
                </div>

                @if($translations->hasPages())
                    <div class="px-6 border-t py-4">
                        {{ $translations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
