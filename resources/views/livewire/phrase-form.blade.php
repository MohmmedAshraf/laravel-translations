<div class="col-span-1 bg-white border group rounded-md overflow-hidden">
    <div class="flex items-center border-b space-x-2 px-3 py-2">
        <div class="h-5 flex-shrink-0">
            <x-dynamic-component class="h-5" component="flag-language-{{ $translation->language->code }}" />
        </div>
        <div class="flex items-center space-x-2">
            <div class="text-sm font-semibold text-gray-800">{{ $translation->language->name }}</div>
            <div class="text-xs text-gray-500 border rounded-md px-1.5 py-0.5">{{ $translation->language->code }}</div>
        </div>
    </div>
    <div class="w-full p-3">
        <label for="textArea"></label>
        <textarea id="textArea" dir="auto" wire:model="content" class="w-full min-h-36 without-ring resize-none border-0 m-0 p-0"></textarea>
    </div>
    <div class="w-full grid grid-cols-2 border-t gap-6 px-4 py-3">
        <a href="{{ route('translations_ui.phrases.index', $translation) }}" class="text-sm font-medium text-center w-full border border-violet-400 text-violet-700 hover:bg-violet-50 py-3 rounded-md uppercase">
            Cancel
        </a>
        <button wire:click="save" class="text-sm font-medium w-full bg-violet-700 hover:bg-violet-500 text-white py-3 rounded-md uppercase">
            Save Changes
        </button>
    </div>
</div>
