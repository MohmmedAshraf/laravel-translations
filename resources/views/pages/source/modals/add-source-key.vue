<script setup lang="ts">
const { close } = useModal()

const form = useForm({
    key: "",
    value: "",
})

const submit = () => {
    form.post(route("ltu.translation.store"), {
        preserveScroll: true,
        onSuccess: () => {
            close()
        },
    })
}
</script>

<template>
    <Head title="Add New Key" />

    <Dialog size="lg">
        <div class="flex items-start justify-between gap-4 border-b px-6 py-4">
            <div class="flex size-12 shrink-0 items-center justify-center rounded-full border">
                <IconKey class="size-6 text-gray-400" />
            </div>

            <div class="w-full">
                <h3 class="text-base font-semibold leading-6 text-gray-600">Add New Key</h3>

                <p class="mt-1 text-sm text-gray-500">Add a new key to your source language.</p>
            </div>

            <div class="flex w-8 cursor-pointer items-center justify-center text-gray-400 hover:text-gray-600" @click="close">
                <IconClose class="size-5" />
            </div>
        </div>

        <div>ssss</div>

        <div class="grid grid-cols-1 gap-6 border-t px-6 py-4 md:grid-cols-2">
            <BaseButton variant="secondary" type="button" size="lg" @click="close"> Close </BaseButton>

            <BaseButton variant="primary" type="button" size="lg" :disabled="form.processing" :is-loading="form.processing" @click="submit"> Save & Add Next </BaseButton>
        </div>
    </Dialog>
</template>
