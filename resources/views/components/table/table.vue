<template>
    <div class="w-full divide-y overflow-hidden rounded-md bg-white shadow">
        <slot name="filter"></slot>

        <slot />

        <slot name="footer"></slot>
    </div>
</template>
