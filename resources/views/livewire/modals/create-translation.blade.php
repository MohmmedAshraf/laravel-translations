<div class="p-6 space-y-4">
    <div class="w-full flex items-center justify-between">
        <h3 class="text-base font-semibold leading-6 text-gray-700">
            Add a new language
        </h3>
        <x-iconsax-lin-add class="w-6 h-6 text-gray-400" />
    </div>

    <x-native-select placeholder="Select a language" :options="$languages" wire:model="language" option-label="name" option-value="id" />

    <div class="flex items-center space-x-4 rtl:space-x-reverse mt-6">
        <button type="button" wire:click="create" wire:loading.attr="disabled" class="flex-grow inline-flex items-center justify-center px-4 py-2 font-semibold leading-6 text-sm rounded-md text-white bg-violet-600 hover:bg-violet-700 transition ease-in-out duration-150 disabled:cursor-not-allowed">
            <svg wire:loading wire:target="create" class="animate-spin -ml-1 mr-3 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span wire:target="create" wire:loading.remove>Add language</span>
        </button>
        <button wire:click="$emit('closeModal')" type="button" class="items-center justify-center px-4 py-2 font-semibold leading-6 text-sm rounded-md text-gray-600 bg-gray-200 hover:bg-gray-300">
            Cancel
        </button>
    </div>
</div>
