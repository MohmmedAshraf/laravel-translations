import { ref } from "vue"
import { POSITION, useToast } from "vue-toastification"

export default function useConfirmationDialog() {
    const toast = useToast()
    const loading = ref(false)
    const showDialog = ref(false)

    const openDialog = () => {
        showDialog.value = true
    }

    async function performAction(actionFunction: () => void, options?: { onSuccess?: () => void; onError?: () => void }) {
        loading.value = true

        try {
            actionFunction()

            loading.value = false
            closeDialog()

            if (options && options.onSuccess) {
                options.onSuccess()
            }
        } catch (error) {
            toast.error("Something went wrong, please try again.", {
                icon: true,
                position: POSITION.BOTTOM_CENTER,
            })
            loading.value = false
            if (options && options.onError) {
                options.onError()
            }
        }
    }

    const closeDialog = () => {
        showDialog.value = false
    }

    return {
        loading,
        showDialog,
        openDialog,
        performAction,
        closeDialog,
    }
}
