<script setup lang="ts">
const { close } = useModal()
</script>

<template>
    <Head title="Confirmation Modal" />

    <Dialog>
        <div class="sm:flex sm:items-start">
            <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>

            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                <h3 id="modal-title" class="text-base font-semibold leading-6 text-gray-900">Deactivate account</h3>

                <div class="mt-2">
                    <p class="text-sm text-gray-500">Are you sure you want to deactivate your account? All of your data will be permanently removed from our servers forever. This action cannot be undone.</p>
                </div>
            </div>
        </div>

        <div class="mt-6 gap-4 sm:ml-10 sm:mt-4 sm:flex sm:pl-4">
            <BaseButton variant="danger" type="button" size="sm">
                Deactivate
            </BaseButton>

            <BaseButton variant="secondary" type="button" size="sm" @click="close">
                Cancel
            </BaseButton>
        </div>
    </Dialog>
</template>
