<script setup lang="ts">
const { close } = useModal()

const loading = ref(false)

const submit = () => {
    loading.value = true

    router.post(route("ltu.translation.export"), {}, {
        preserveScroll: true,
        onSuccess: () => {
            loading.value = false

            close()
        },
    });
}
</script>

<template>
    <Head title="Add Languages" />

    <Dialog size="md">
        <div class="relative flex flex-col items-start justify-center gap-4 px-6 py-4">
            <div class="mx-auto flex size-12 shrink-0 items-center justify-center">
                <IconPublish class="size-10 text-blue-500" />
            </div>

            <div class="w-full text-center">
                <h3 class="text-xl font-semibold leading-6 text-gray-600">
                    Publish Translations
                </h3>

                <p class="mt-4 text-sm text-gray-500">
                    Be cautious when publishing, as this action will update your project with all translations and changes made in the database, overwriting project files.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 border-t px-6 py-4 md:grid-cols-2">
            <BaseButton variant="secondary" type="button" size="lg" @click="close"> Close </BaseButton>

            <BaseButton variant="primary" type="button" size="lg" :disabled="loading" :is-loading="loading" @click="submit">
                Publish
            </BaseButton>
        </div>
    </Dialog>
</template>
