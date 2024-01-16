import { computed, ComputedRef } from "vue"

type ButtonVariant = "primary" | "secondary" | "success" | "danger" | "warning"

const useButtonVariant = (variant: ButtonVariant): { variantClass: ComputedRef<string> } => {
    const variantClass = computed(() => {
        return {
            primary: "btn-primary",
            secondary: "btn-secondary",
            success: "btn-success",
            danger: "btn-danger",
            warning: "btn-warning",
        }[variant]
    })

    return {
        variantClass,
    }
}

export default useButtonVariant
