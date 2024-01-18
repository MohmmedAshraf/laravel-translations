<script setup lang="ts">
import { ref } from "vue"
import { Role } from "../../../../scripts/types"
import { RadioGroup, RadioGroupDescription, RadioGroupLabel, RadioGroupOption } from "@headlessui/vue"

const props = defineProps<{
    roles: Role[]
}>()

const { close } = useModal()

const form = useForm({
    email: "",
    role: props.roles[1].value,
})

const canSubmit = computed(() => {
    return form.email.length > 0 && form.email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)
})

const submit = () => {
    form.post(route("ltu.contributors.invite.store"), {
        preserveScroll: true,
        onSuccess: () => {
            close()
        },
    })
}
</script>

<template>
    <Head title="Invite Contributor by e-mail" />

    <Dialog size="lg">
        <div class="flex items-start justify-between gap-4 border-b px-6 py-4">
            <div class="flex size-12 shrink-0 items-center justify-center rounded-full border">
                <IconMail class="size-6 text-gray-400" />
            </div>

            <div class="w-full">
                <h3 class="text-base font-semibold leading-6 text-gray-600">Invite Contributor by e-mail</h3>

                <p class="mt-1 text-sm text-gray-500">Invite a new contributor to your project.</p>
            </div>

            <div class="flex w-8 cursor-pointer items-center justify-center text-gray-400 hover:text-gray-600" @click="close">
                <IconClose class="size-5" />
            </div>
        </div>

        <div class="flex w-full flex-col space-y-6 p-6">
            <div class="w-full space-y-1">
                <InputLabel value="E-mail address" />

                <inputText v-model="form.email" label="E-mail" type="email" placeholder="E-mail address of the contributor" :error="form.errors.email" :disabled="form.processing" />

                <InputError :message="form.errors.email" />
            </div>

            <div class="w-full space-y-1">
                <InputLabel value="Select role" />

                <RadioGroup v-model="form.role">
                    <RadioGroupLabel class="sr-only">Role</RadioGroupLabel>

                    <div class="-space-y-px rounded-md bg-white">
                        <RadioGroupOption v-for="(role, rolesIdx) in roles" :key="role.value" v-slot="{ checked, active }" as="template" :value="role.value">
                            <div class="relative flex cursor-pointer border p-4 focus:outline-none" :class="[rolesIdx === 0 ? 'rounded-t-md' : '', rolesIdx === roles.length - 1 ? 'rounded-b-md' : '', checked ? 'z-10 border-blue-200 bg-blue-50' : 'border-gray-200']">
                                <div class="mt-0.5 flex size-4 shrink-0 cursor-pointer items-center justify-center rounded-full border" :class="[checked ? 'border-transparent bg-blue-600' : 'border-gray-300 bg-white', active ? 'ring-2 ring-blue-600 ring-offset-2' : '']" aria-hidden="true">
                                    <span class="size-1.5 rounded-full bg-white" />
                                </div>

                                <div class="ml-3 flex flex-col space-y-2">
                                    <RadioGroupLabel as="span" class="block text-sm font-medium" :class="[checked ? 'text-blue-900' : 'text-gray-900']">{{ role.label }}</RadioGroupLabel>

                                    <RadioGroupDescription as="span" class="block text-sm" :class="[checked ? 'text-blue-700' : 'text-gray-500']">{{ role.description }}</RadioGroupDescription>
                                </div>
                            </div>
                        </RadioGroupOption>
                    </div>
                </RadioGroup>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 border-t px-6 py-4 md:grid-cols-2">
            <BaseButton variant="secondary" type="button" size="lg" @click="close"> Close </BaseButton>

            <BaseButton variant="primary" type="button" size="lg" :disabled="!canSubmit || form.processing" :is-loading="form.processing" @click="submit"> Send Invitation </BaseButton>
        </div>
    </Dialog>
</template>
