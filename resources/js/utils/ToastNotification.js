import { useToast } from "vue-toastification";

export function showNotification({ message, type = "success", className = "custom-toast", position = "top-right", timeout = 5000 }) {
    const toast = useToast(); // Use the toast composable
    toast(message, {
        type,
        className,
        position,
        timeout,
    });
}
