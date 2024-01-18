<script setup lang="ts">
const input = ref<HTMLInputElement | null>(null)

const value = defineModel<string | number | null>()

const type = ref<string>("password")

const showPassword = () => {
    type.value = type.value === "password" ? "text" : "password"
}
</script>

<template>
    <div class="bg-grey-100 placeholder:text-grey-400 flex items-center overflow-hidden rounded-md border border-gray-300 focus-within:border-blue-500 focus-within:shadow">
        <InputText ref="input" v-model="value" :type="type" v-bind="$attrs" class="border-none focus:border-transparent focus:ring-transparent" />

        <button type="button" class="px-4 text-gray-400 focus:text-blue-500 focus:outline-none" tabindex="-1" @click="showPassword">
            <IconEye v-if="type === 'password'" class="size-5" />

            <IconEyeOff v-else class="size-5" />
        </button>
    </div>
</template>
