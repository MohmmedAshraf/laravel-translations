<template>
    <div class="flex min-h-full flex-1 bg-white">
        <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div class="w-full">
                    <Link :href="route('ltu.translation.index')" class="flex h-10 max-w-max">
                        <Logo class="h-10 w-auto fill-current text-gray-500" />
                    </Link>

                    <h2 class="mt-8 text-2xl font-bold leading-9 tracking-tight text-gray-900">
                        <slot name="title">Welcome back!</slot>
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-gray-500">
                        <slot name="subtitle" />
                    </p>
                </div>

                <div class="mt-10">
                    <slot />
                </div>
            </div>
        </div>

        <div class="relative hidden w-0 flex-1 lg:block">
            <img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1496917756835-20cb06e75b4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1908&q=80" alt="" />
        </div>
    </div>
</template>
