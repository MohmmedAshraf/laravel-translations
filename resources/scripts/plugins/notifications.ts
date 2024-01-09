import { TYPE as NotificationType, useToast } from "vue-toastification"
import "vue-toastification/dist/index.css"

const toast = useToast()

export type Type = 'success' | 'error' | 'warning' | 'info' | 'default';

export type NotificationData = {
    type: Type
    body: string
}

export const notifications = () => {
    router.on("finish", () => {
        const notification = usePage().props.notification as NotificationData | null

        if (notification) {
            toast(notification.body, { type: notification.type as NotificationType })
        }
    })
}
