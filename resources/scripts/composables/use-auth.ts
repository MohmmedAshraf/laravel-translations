import { Contributor } from "../types"

export const useAuth = () => {
    return computed(() => usePage().props.auth.user as Contributor)
}
