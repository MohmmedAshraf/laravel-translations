import { computed, ComputedRef } from "vue"

type ButtonSize = "xs" | "sm" | "md" | "lg"

const useInputSize = (size: ButtonSize): { sizeClass: ComputedRef<string> } => {
    const sizeClass = computed(() => {
        return {
            xs: "py-1 text-sm",
            sm: "py-1.5 text-sm",
            md: "py-2 text-sm",
            lg: "py-2.5 text-sm",
        }[size]
    })

    return {
        sizeClass,
    }
}

export default useInputSize
