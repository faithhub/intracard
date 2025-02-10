import { useNotificationStore } from '@/stores/notificationStore';
import { storeToRefs } from 'pinia';
import { onMounted, onBeforeUnmount } from 'vue';
import dayjs from 'dayjs';

export function useNotifications() {
    const store = useNotificationStore();
    const { notifications, unreadCount, activeTab, loading } = storeToRefs(store);

    const refresh = () => store.fetchNotifications();
    const markAsRead = (notification) => store.markAsRead(notification);
    const markAllAsRead = () => store.markAllAsRead();
    const setActiveTab = (tab) => store.setActiveTab(tab);

    const getNotificationIcon = (type) => {
        const icons = {
            general: 'mdi-information',
            payment: 'mdi-cash',
            reminder: 'mdi-clock'
        };
        return icons[type] || icons.general;
    };

    const getNotificationColor = (type) => {
        const colors = {
            general: 'info',
            payment: 'success',
            reminder: 'warning'
        };
        return colors[type] || colors.general;
    };

    const formatDate = (date) => {
        return dayjs(date).fromNow();
    };

    const initializeWebSocket = () => {
        if (window.Echo) {
            window.Echo.private('notifications')
                .listen('NotificationEvent', (e) => {
                    store.handleNewNotification(e);
                });
        }
    };

    onMounted(() => {
        refresh();
        initializeWebSocket();
    });

    onBeforeUnmount(() => {
        if (window.Echo) {
            window.Echo.leave('notifications');
        }
    });

    return {
        notifications,
        unreadCount,
        activeTab,
        loading,
        refresh,
        markAsRead,
        markAllAsRead,
        setActiveTab,
        getNotificationIcon,
        getNotificationColor,
        formatDate
    };
}