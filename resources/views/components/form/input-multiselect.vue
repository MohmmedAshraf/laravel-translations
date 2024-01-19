<script setup lang="ts">
import { ChevronUpDownIcon } from "@heroicons/vue/24/outline/index.js"
import { Listbox, ListboxButton, ListboxOptions, ListboxOption } from "@headlessui/vue"

const props = defineProps<{
    modelValue: any[]
    items: any[]
    labelBy: (item: any) => string
    keyBy: (item: any) => string | number
}>()

const value = defineModel<any[]>({ required: true })

const results = computed(() => {
    const items = props.items.filter((item) => !props.modelValue.find((_item) => props.keyBy(_item) == props.keyBy(item)))

    return [...props.modelValue, ...items]
})
</script>

<template>
    <Listbox v-model="value" multiple>
        <div class="relative">
            <ListboxButton class="relative w-full cursor-default border border-gray-300 bg-white py-2.5 pl-3 pr-10 text-left text-sm transition focus:border-gray-700 focus:outline-none focus:ring-0">
                <span v-if="value.length" class="block truncate">{{ value.map((item) => labelBy(item)).join(", ") }}</span>

                <span v-else class="italic text-gray-500">Select some from the list</span>

                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                    <ChevronUpDownIcon class="size-5 text-gray-400" aria-hidden="true" />
                </div>
            </ListboxButton>

            <Transition leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <ListboxOptions class="absolute z-10 mt-1 w-full overflow-auto border border-gray-300 bg-white py-1 text-base shadow-lg focus:outline-none">
                    <ListboxOption v-for="item in results" v-slot="{ active, selected }" :key="keyBy(item)" :value="item" as="template">
                        <li class="relative cursor-default select-none py-2 pl-4 pr-10 text-sm font-medium transition-colors" :class="[active ? 'bg-gray-50 text-black' : 'text-gray-900']">
                            <span class="block truncate" :class="[selected ? 'font-medium' : 'font-normal']">{{ labelBy(item) }}</span>

                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="size-2 transition-colors" :class="[selected ? 'bg-gray-800' : active ? 'bg-gray-300' : 'bg-gray-100']" aria-hidden="true" />
                            </div>
                        </li>
                    </ListboxOption>
                </ListboxOptions>
            </Transition>
        </div>
    </Listbox>
</template>
