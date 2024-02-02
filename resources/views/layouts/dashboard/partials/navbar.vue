<script setup lang="ts">
import { Bars3Icon, XMarkIcon, ArrowRightEndOnRectangleIcon } from "@heroicons/vue/24/outline"
import { Popover, PopoverButton, PopoverOverlay, PopoverPanel, TransitionChild, TransitionRoot } from "@headlessui/vue"

const user = useAuth()

const navigation = [
    { name: "Translations", href: route("ltu.translation.index"), current: route().current("ltu.translation*") || route().current("ltu.source_translation*") || route().current("ltu.phrases*") },
    { name: "Contributors", href: route("ltu.contributors.index"), current: route().current("ltu.contributors*") },
    { name: "Account Settings", href: route("ltu.profile.edit"), current: route().current("ltu.profile*") },
]
</script>

<template>
    <div class="mx-auto max-w-7xl px-2 sm:px-4 lg:px-8">
        <Popover v-slot="{ open }" class="flex h-16 justify-between">
            <div class="flex px-2 lg:px-0">
                <div class="flex shrink-0 items-center">
                    <Link :href="route('ltu.translation.index')">
                        <Logo class="h-8 w-auto text-white" />
                    </Link>
                </div>

                <nav aria-label="Global" class="hidden lg:ml-6 lg:flex lg:items-center lg:space-x-4">
                    <Link v-for="item in navigation" :key="item.name" :href="item.href" class="rounded-md px-3 py-2 text-sm font-medium" :class="[item.current ? 'bg-blue-500 text-white' : 'text-white hover:bg-blue-700 hover:text-white']" :aria-current="item.current ? 'page' : undefined">
                        {{ item.name }}
                    </Link>
                </nav>
            </div>

            <div class="flex items-center lg:hidden">
                <PopoverButton class="relative inline-flex items-center justify-center rounded-md p-2 text-white focus:outline-none">
                    <span class="absolute -inset-0.5" />

                    <span class="sr-only">Open main menu</span>

                    <Bars3Icon class="block size-6" aria-hidden="true" />
                </PopoverButton>
            </div>

            <TransitionRoot as="template" :show="open">
                <div class="lg:hidden">
                    <TransitionChild as="template" enter="duration-150 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-150 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                        <PopoverOverlay class="fixed inset-0 z-20 bg-black/25" aria-hidden="true" />
                    </TransitionChild>

                    <TransitionChild as="template" enter="duration-150 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-150 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <PopoverPanel focus class="absolute right-0 top-0 z-30 w-full max-w-none origin-top p-2 transition">
                            <div class="divide-y divide-gray-200 rounded-lg bg-white shadow-lg ring-1 ring-black/5">
                                <div class="pb-2 pt-3">
                                    <div class="flex items-center justify-between px-4">
                                        <Link :href="route('ltu.translation.index')" tabindex="-1" class="flex items-center gap-3">
                                            <Logo class="h-8 w-auto" />

                                            <h1 class="mt-1 text-xl font-medium text-gray-600">Translations <span class="font-bold text-blue-600">UI</span></h1>
                                        </Link>

                                        <div class="-mr-2">
                                            <PopoverButton class="relative inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none">
                                                <span class="absolute -inset-0.5" />

                                                <span class="sr-only">Close menu</span>

                                                <XMarkIcon class="size-6" aria-hidden="true" />
                                            </PopoverButton>
                                        </div>
                                    </div>

                                    <div class="mt-6 space-y-1 px-2">
                                        <Link v-for="item in navigation" :key="item.name" :href="item.href" class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-800">
                                            {{ item.name }}
                                        </Link>
                                    </div>
                                </div>

                                <div class="py-4">
                                    <div class="flex items-center px-5">
                                        <div>
                                            <div class="text-base font-medium text-gray-800">{{ user.name }}</div>

                                            <div class="text-sm font-medium text-gray-500">{{ user.email }}</div>
                                        </div>

                                        <Link as="button" method="POST" href="{{ route('ltu.logout') }}" class="relative ml-auto shrink-0 rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            <span class="absolute -inset-1.5" />

                                            <span class="sr-only">Log Out</span>

                                            <ArrowRightEndOnRectangleIcon class="size-6" aria-hidden="true" />
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </PopoverPanel>
                    </TransitionChild>
                </div>
            </TransitionRoot>

            <div class="hidden gap-4 lg:ml-4 lg:flex lg:items-center">
                <div class="flex">
                    <Link :href="route('ltu.translation.publish')" class="btn btn-xs btn-success">
                        <IconPublish class="size-4" />

                        <span>Publish</span>
                    </Link>
                </div>

                <Link :href="route('ltu.logout')" v-tooltip="'Logout'" class="flex items-center text-white hover:text-gray-900">
                    <ArrowRightEndOnRectangleIcon class="size-6" />
                </Link>
            </div>
        </Popover>
    </div>
</template>
