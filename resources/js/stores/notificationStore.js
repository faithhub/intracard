// stores/notificationStore.js
import { defineStore } from 'pinia';
import axios from 'axios';
import { useToast } from 'vue-toastification';

export const useNotificationStore = defineStore('notification', {
    state: () => ({
        notifications: [],
        unreadCount: 0,
        activeTab: 'all',
        loading: false,
        categoryCounts: {
            general: 0,
            payment: 0,
            reminder: 0
        }
    }),

    actions: {
        async fetchNotifications() {
            try {
                this.loading = true;
                const response = await axios.get('/api/notifications', {
                    params: {
                        type: this.activeTab !== 'all' ? this.activeTab : null
                    }
                });

                this.notifications = response.data.notifications.data;
                this.unreadCount = response.data.unread_count;

                if (response.data.category_counts) {
                    this.categoryCounts = response.data.category_counts;
                }
            } catch (error) {
                console.error('Error fetching notifications:', error);
                useToast().error('Failed to fetch notifications');
            } finally {
                this.loading = false;
            }
        },

        async markAsRead2(notificationId) {
            try {
                console.log(notificationId);

                await axios.patch(`/api/notifications/${notificationId}/read`);
                this.unreadCount = Math.max(0, this.unreadCount - 1);

                // Update notification in list
                const notification = this.notifications.find(n => n.id === notificationId);
                if (notification) {
                    notification.read_at = new Date().toISOString();
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },
        // stores/notificationStore.js
        async markAsRead(notification) {
            try {
                // Remove the notification from the list immediately for instant UI feedback
                this.notifications = this.notifications.filter(n => n.id !== notification.id);

                // Update unread count
                if (!notification.read_at) {
                    this.unreadCount = Math.max(0, this.unreadCount - 1);

                    // Update category counts
                    if (notification.category && this.categoryCounts[notification.category]) {
                        this.categoryCounts[notification.category] = Math.max(0, this.categoryCounts[notification.category] - 1);
                    }
                }

                // Make the API call after UI update
                await axios.patch(`/api/notifications/${notification.id}/read`);
            } catch (error) {
                console.error('Error marking notification as read:', error);
                useToast().error('Failed to mark notification as read');

                // If API call fails, revert the changes
                await this.fetchNotifications();
            }
        },

        async markAllAsRead2() {
            try {
                await axios.patch('/api/notifications/mark-all-read', {
                    type: this.activeTab
                });
                this.notifications = this.notifications.map(notification => ({
                    ...notification,
                    read_at: notification.read_at || new Date().toISOString()
                }));
                this.unreadCount = 0;
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        },
        async markAllAsRead() {
            try {
                // Clear notifications immediately
                const previousNotifications = [...this.notifications];
                this.notifications = [];
                this.unreadCount = 0;
                Object.keys(this.categoryCounts).forEach(key => {
                    this.categoryCounts[key] = 0;
                });

                // Make the API call after UI update
                await axios.patch('/api/notifications/mark-all-read', {
                    type: this.activeTab
                });
            } catch (error) {
                console.error('Error marking all as read:', error);
                useToast().error('Failed to mark all as read');

                // If API call fails, revert the changes
                await this.fetchNotifications();
            }
        },
        setActiveTab(tab) {
            this.activeTab = tab;
            this.fetchNotifications();
        },

        initializeWebSocket() {
            if (window.Echo) {
                window.Echo.private('notifications')
                    .listen('NotificationEvent', (e) => {
                        this.handleNewNotification(e);
                    });
            }
        },

        handleNewNotification(notification) {
            this.notifications.unshift({
                id: Date.now(),
                title: notification.title,
                message: notification.message,
                read_at: null,
                created_at: new Date().toISOString()
            });
            this.unreadCount++;

            useToast().info(notification.message, {
                title: notification.title
            });
        }
    }
});