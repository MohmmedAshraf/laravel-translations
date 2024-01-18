<script setup lang="ts">
import { ChevronUpDownIcon } from "@heroicons/vue/24/outline/index.js"
import { Combobox, ComboboxInput, ComboboxOptions, ComboboxOption, ComboboxButton, TransitionRoot } from "@headlessui/vue"

const props = withDefaults(
    defineProps<{
        items?: string[]
    }>(),
    {
        items: () => [],
    },
)

const value = defineModel<string[]>({ required: true })

const options = reactive(props.items)

const query = ref("")

const button = ref()
const input = ref()

const results = computed(() => {
    let items = query.value === "" ? options : options.filter((item) => item.toLowerCase().replace(/\s+/g, "").includes(query.value.toLowerCase().replace(/\s+/g, "")))

    if (!items.length && query.value) {
        items.push(query.value)
    }

    items = [...items, ...value.value].filter((x, i, a) => a.indexOf(x) == i)

    return items
})

const focus = () => {
    button.value.el.click()
    input.value.el.value = ""
}
</script>

<template>
    <Combobox v-model="value" multiple>
        <div class="relative">
            <div class="relative w-full cursor-default overflow-hidden">
                <ComboboxInput ref="input" class="w-full cursor-default truncate border border-gray-300 py-2.5 pl-3 pr-8 text-sm transition focus:border-gray-700" :display-value="(items) => ((items ?? []) as string[]).join(', ')" @focus="focus" @change="query = $event.target.value" />

                <ComboboxButton ref="button" class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                    <ChevronUpDownIcon class="size-5 text-gray-400" aria-hidden="true" />
                </ComboboxButton>
            </div>

            <TransitionRoot leave="transition ease-in duration-100" leave-from="opacity-100" leave-to="opacity-0" @before-leave="query = ''">
                <ComboboxOptions class="absolute z-10 mt-1 w-full overflow-auto border border-gray-300 bg-white py-1 text-base shadow-lg focus:outline-none">
                    <li v-if="!results.length" class="px-3 py-2 text-sm text-gray-600">Start typing to add an option</li>

                    <ComboboxOption v-for="(item, index) in results" :key="index" v-slot="{ selected, active }" as="template" :value="item">
                        <li class="relative cursor-default select-none py-2 pl-3 pr-10 text-sm font-medium transition-colors" :class="[active ? 'bg-gray-50 text-black' : 'text-gray-900']">
                            <span class="block truncate" :class="[selected ? 'font-medium' : 'font-normal']">{{ item }}</span>

                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="size-2 transition-colors" :class="[selected ? 'bg-gray-800' : active ? 'bg-gray-300' : 'bg-gray-100']" aria-hidden="true" />
                            </div>
                        </li>
                    </ComboboxOption>
                </ComboboxOptions>
            </TransitionRoot>
        </div>
    </Combobox>
</template>
