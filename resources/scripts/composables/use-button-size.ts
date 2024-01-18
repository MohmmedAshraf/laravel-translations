import { computed, ComputedRef } from "vue"

type ButtonSize = "xs" | "sm" | "md" | "lg"

const useButtonSize = (size: ButtonSize): { sizeClass: ComputedRef<string> } => {
    const sizeClass = computed(() => {
        return {
            xs: "btn-xs",
            sm: "btn-sm",
            md: "btn-md",
            lg: "btn-lg",
        }[size]
    })

    return {
        sizeClass,
    }
}

export default useButtonSize
