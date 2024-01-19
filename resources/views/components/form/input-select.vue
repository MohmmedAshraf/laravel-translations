<script setup lang="ts">
import { Listbox, ListboxButton, ListboxOptions, ListboxOption } from "@headlessui/vue"
import { ChevronUpDownIcon } from "@heroicons/vue/24/outline"

const props = defineProps<{
    items: any[]
    labelBy: (item: any) => string
    displayBy: (item: any) => string
    keyBy?: (item: any) => string | number
    valueBy: (item: any) => any
    size?: "xs" | "sm" | "md" | "lg"
    error?: string
}>()

const value = defineModel<any>()

const { sizeClass } = useInputSize(props.size ?? "lg")
</script>

<template>
    <Listbox v-model="value">
        <div class="relative">
            <ListboxButton class="w-full rounded-md border border-gray-300 bg-white px-3 text-left shadow-sm focus:border-blue-500 focus:ring-blue-500" :class="[sizeClass, { 'border-red-300 text-red-900 placeholder:text-red-300 focus:border-red-500 focus:ring-red-500': error }]">
                <span v-if="value" class="block truncate">{{ displayBy(value) }}</span>

                <span v-else class="italic text-gray-500">Select from the list</span>

                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                    <ChevronUpDownIcon class="size-5 text-gray-400" aria-hidden="true" />
                </div>
            </ListboxButton>

            <Transition leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <ListboxOptions class="absolute z-10 mt-1 w-full overflow-auto rounded-md border border-gray-300 bg-white py-1 text-base shadow-lg focus:outline-none">
                    <ListboxOption v-for="(item, index) in items" v-slot="{ active, selected }" :key="keyBy ? keyBy(item) : index" :value="valueBy(item)" as="template">
                        <li class="relative cursor-pointer select-none py-2 pl-3 pr-10 text-sm font-medium transition-colors" :class="[active ? 'bg-gray-50 text-black' : 'text-gray-900']">
                            <span class="block truncate" :class="[selected ? 'font-medium' : 'font-normal']">
                                {{ labelBy(item) }}
                            </span>

                            <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="size-2 transition-colors" :class="[selected ? 'bg-gray-800' : active ? 'bg-gray-300' : 'bg-gray-100']" aria-hidden="true" />
                            </span>
                        </li>
                    </ListboxOption>
                </ListboxOptions>
            </Transition>
        </div>
    </Listbox>
</template>
