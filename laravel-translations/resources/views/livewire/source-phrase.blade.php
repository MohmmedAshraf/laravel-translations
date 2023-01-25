<div dir="auto" name="phrase" class="w-full min-h-20 lg:min-h-38 without-ring resize-none border-0 m-0 p-0">
    @if(! blank($phrase->parameters))
        @foreach(explode(' ', $phrase->value) as $word)
            @if(preg_match('/(?<!\w):(\w+)/', $word))
                <span onclick="copyParameters('textArea', '{{ preg_replace('/[^a-zA-Z0-9:]/', ' ', $word) }}')" class="text-violet-700 font-medium cursor-pointer">{{ $word }}</span>
            @else
                <span class="text-gray-600 font-normal">{{ $word }}</span>
            @endif
        @endforeach
    @else
        <span class="text-gray-600 font-normal">{!! $phrase->value !!}</span>
    @endif

    @push('scripts')
        <script>
            function copyParameters(areaId, text) {
                let range;
                const textarea = document.getElementById(areaId);
                const scrollPos = textarea.scrollTop;
                let strPos = 0;
                const br = ((textarea.selectionStart || textarea.selectionStart === '0') ?
                    "ff" : (document.selection ? "ie" : false));
                if (br === "ie") {
                    textarea.focus();
                    range = document.selection.createRange();
                    range.moveStart ('character', -textarea.value.length);
                    strPos = range.text.length;
                }
                else if (br === "ff") strPos = textarea.selectionStart;

                const front = (textarea.value).substring(0, strPos);
                const back = (textarea.value).substring(strPos, textarea.value.length);
                textarea.value=front+text+back;
                strPos = strPos + text.length;
                if (br === "ie") {
                    textarea.focus();
                    range = document.selection.createRange();
                    range.moveStart ('character', -textarea.value.length);
                    range.moveStart ('character', strPos);
                    range.moveEnd ('character', 0);
                    range.select();
                }
                else if (br === "ff") {
                    textarea.selectionStart = strPos;
                    textarea.selectionEnd = strPos;
                    textarea.focus();
                }
                textarea.scrollTop = scrollPos;
            }
        </script>
    @endpush
</div>
