<script lang="ts" setup>
import { useModal } from "momentum-modal"
import { XMarkIcon } from "@heroicons/vue/24/outline"
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from "@headlessui/vue"

const props = withDefaults(
    defineProps<{
        size?: "sm" | "md" | "lg" | "xl" | "2xl"
        closeable?: boolean
    }>(),
    {
        size: "lg",
        closeable: true,
    },
)

const { show, close, redirect } = useModal()

const closeDialog = () => {
    if (props.closeable) {
        close()
    }
}

const maxWidthClass = computed(() => {
    return {
        sm: "sm:max-w-sm",
        md: "sm:max-w-md",
        lg: "sm:max-w-lg",
        xl: "sm:max-w-xl",
        "2xl": "sm:max-w-2xl",
    }[props.size]
})
</script>

<template>
    <TransitionRoot as="template" :show="show">
        <Dialog as="div" class="relative z-10" @close="closeDialog">
            <TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100" leave-to="opacity-0" @after-leave="redirect">
                <div class="fixed inset-0 bg-gray-500/25 backdrop-blur transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full">
                        <TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700" enter-from="translate-x-full" enter-to="translate-x-0" leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0" leave-to="translate-x-full">
                            <DialogPanel :class="[maxWidthClass]" class="pointer-events-auto w-screen">
                                <div class="relative flex h-screen flex-col bg-white shadow-xl">
                                    <div class="flex grow flex-col overflow-y-auto">
                                        <div class="border-b px-4 py-6 sm:px-6 sm:py-8">
                                            <div class="flex items-start justify-between">
                                                <DialogTitle class="text-lg font-semibold text-gray-900">
                                                    <slot name="title"> Panel title </slot>
                                                </DialogTitle>

                                                <div class="ml-3 flex h-7 items-center">
                                                    <button type="button" class="relative rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" @click="close">
                                                        <span class="absolute -inset-2.5" />

                                                        <span class="sr-only">Close panel</span>

                                                        <XMarkIcon class="size-6" aria-hidden="true" />
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="relative flex-1 px-4 py-6 sm:px-6 sm:py-8">
                                            <slot />
                                        </div>
                                    </div>

                                    <div class="shrink-0 bg-gray-100 px-4 py-6 sm:px-6 sm:py-8">
                                        <div class="flex justify-between gap-6">
                                            <baseButton type="button" size="lg" class="btn btn-secondary-darker !text-sm" @click="close"> Cancel </baseButton>

                                            <slot name="slideover_submit" />
                                        </div>
                                    </div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
