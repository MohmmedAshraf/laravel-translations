import "vue-toastification/dist/index.css"
import { Notification } from "../types"
import { POSITION, TYPE as NotificationType, useToast } from "vue-toastification"

const toast = useToast()

export const notifications = () => {
    router.on("finish", () => {
        const notification = usePage().props.notification as Notification | null

        if (notification) {
            toast(notification.body, {
                position: POSITION.BOTTOM_CENTER,
                type: notification.type as NotificationType,
                timeout: 5000,
                closeOnClick: true,
                pauseOnFocusLoss: true,
                pauseOnHover: true,
                draggable: true,
                draggablePercent: 0.6,
                showCloseButtonOnHover: false,
                hideProgressBar: true,
                closeButton: "button",
                icon: true,
            })
        }
    })
}
