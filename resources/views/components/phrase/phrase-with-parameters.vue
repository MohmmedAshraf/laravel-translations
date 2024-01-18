<script setup lang="ts">
defineProps<{
    copyable?: boolean
    phrase: Array<{ value: string; parameter: boolean }>
}>()

function copyParameters(text: string): void {
    const textarea = document.getElementById("textArea") as HTMLTextAreaElement
    const scrollPos = textarea.scrollTop
    let strPos = 0
    const br = textarea.selectionStart != undefined ? "ff" : document.selection ? "ie" : false

    function createRange(): Range | TextRange {
        return br === "ie" ? document.selection.createRange() : (document.createRange() as Range)
    }

    function setCursorPosition(position: number): void {
        if (br === "ie") {
            const range = createRange()
            range.moveStart("character", -textarea.value.length)
            range.moveStart("character", position)
            range.moveEnd("character", 0)
            range.select()
        } else if (br === "ff") {
            textarea.selectionStart = position
            textarea.selectionEnd = position
            textarea.focus()
        }
    }

    if (br === "ie") {
        textarea.focus()
        const range = createRange()
        range.moveStart("character", -textarea.value.length)
        strPos = range.text.length
    } else if (br === "ff") {
        strPos = textarea.selectionStart as number
    }

    const front = textarea.value.substring(0, strPos)
    const back = textarea.value.substring(strPos, textarea.value.length)
    textarea.value = front + text + back
    strPos = strPos + text.length

    setCursorPosition(strPos)

    textarea.scrollTop = scrollPos
}
</script>

<template>
    <div
        v-for="word in phrase"
        :key="word.value"
        class="flex items-center"
        :class="{
            'text-gray-600': !word.parameter,
            'cursor-pointer': word.parameter && copyable,
            'rounded bg-blue-50 px-1 py-px text-sm text-blue-600 hover:bg-blue-100': word.parameter,
        }"
        @click="copyable && word.parameter && copyParameters(word.value)">
        {{ word.value }}
    </div>
</template>
