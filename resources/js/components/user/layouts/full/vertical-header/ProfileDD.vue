<template>
    <div class="d-flex align-center">
        <!-- Notification Icon -->
        <v-menu :close-on-content-click="false" offset-y transition="slide-y-transition"
            :max-width="$vuetify.display.smAndDown ? 300 : 400">
            <template v-slot:activator="{ props }">
                <v-btn v-bind="props" variant="text" icon :size="$vuetify.display.smAndDown ? 'small' : 'default'"
                    class="mr-2">
                    <v-badge :content="unreadCount.toString()" :value="unreadCount > 0" color="error" location="top end"
                        offset-x="3" offset-y="3" :min-width="16" class="custom-badge">
                        <v-icon :size="$vuetify.display.smAndDown ? 24 : 28" color="grey-darken-1">
                            mdi-bell-outline
                        </v-icon>
                    </v-badge>
                </v-btn>
            </template>

            <v-card elevation="10" class="notification-card rounded-lg">
                <!-- Header -->
                <div class="notification-header bg-purple d-flex justify-space-between align-center py-4 px-4">
                    <span class="text-subtitle-1 text-white font-weight-medium">Notifications</span>
                    <v-btn v-if="unreadCount > 0" variant="text" density="compact" size="small" color="white"
                        class="text-caption px-2" @click="markAllAsRead">
                        Mark all as read
                    </v-btn>
                </div>

                <!-- Tabs -->
                <v-tabs v-model="activeTab" density="compact" height="38" grow slider-color="purple"
                    @update:modelValue="handleTabChange">
                    <v-tab value="all" class="text-caption px-1">All</v-tab>
                    <v-tab value="general" class="text-caption px-1">General</v-tab>
                    <v-tab value="payment" class="text-caption px-1">Payment</v-tab>
                    <v-tab value="reminder" class="text-caption px-1">Reminder</v-tab>
                </v-tabs>

                <!-- Notifications List -->
                <v-list class="py-1" density="compact">
                    <v-list-item v-for="notification in filteredNotifications" :key="notification.id"
                        :class="{ 'unread': !notification.read_at }" @click="markAsRead(notification)"
                        class="notification-item" min-height="64">
                        <template v-slot:prepend>
                            <v-avatar color="purple" :size="32" class="mr-2">
                                <v-icon color="white" :size="16">
                                    mdi-credit-card
                                </v-icon>
                            </v-avatar>
                        </template>

                        <div class="notification-content">
                            <div class="text-body-2 font-weight-medium">{{ notification.title }}</div>
                            <div class="text-caption text-grey">{{ notification.message }}</div>
                            <div class="text-caption text-grey-darken-1">{{ formatDate(notification.created_at) }}</div>
                        </div>
                    </v-list-item>

                    <v-list-item v-if="filteredNotifications.length === 0" class="pa-4">
                        <v-list-item-title class="text-center text-body-2 text-grey">
                            <v-icon :size="24" color="grey-lighten-1" class="mb-1">mdi-bell-off-outline</v-icon>
                            <div>No notifications</div>
                        </v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-card>
        </v-menu>

        <!-- Profile Menu -->
        <v-menu :close-on-content-click="false" offset-y transition="slide-y-transition"
            :max-width="$vuetify.display.smAndDown ? 280 : 320">
            <template v-slot:activator="{ props }">
                <v-btn v-bind="props" variant="text" icon size="small">
                    <v-avatar :size="$vuetify.display.smAndDown ? 35 : 45">
                        <img src="@/assets/images/profile/avatar.jpg" class="img-avatar" alt="user" />
                    </v-avatar>
                </v-btn>
            </template>

            <v-card elevation="10" class="profile-dropdown rounded-lg">
                <!-- Header -->
                <div class="profile-header pa-4">
                    <div class="d-flex align-center">
                        <v-avatar :size="$vuetify.display.smAndDown ? 45 : 55" class="profile-avatar">
                            <img src="@/assets/images/profile/avatar.jpg" class="img-avatar" alt="User Avatar" />
                        </v-avatar>
                        <div class="ml-3">
                            <h3 class="text-body-1 font-weight-bold text-white mb-1">
                                {{ user?.first_name || "User Name" }} {{ user?.last_name || "" }}
                            </h3>
                            <p class="text-caption text-white text-opacity-70">
                                {{ user?.email || "user@example.com" }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Menu Items -->
                <v-list class="py-1" density="compact">
                    <v-list-item to="/profile" value="profile" link class="menu-item" active-color="purple">
                        <template v-slot:prepend>
                            <v-icon size="20" color="purple">mdi-account-outline</v-icon>
                        </template>
                        <v-list-item-title class="text-body-2">My Account</v-list-item-title>
                        <v-list-item-subtitle class="text-caption">Account settings</v-list-item-subtitle>
                    </v-list-item>

                    <v-list-item v-if="user?.is_team && user?.team_id" to="/team" value="team" link class="menu-item"
                        active-color="purple">
                        <template v-slot:prepend>
                            <v-icon size="20" color="purple">mdi-account-group-outline</v-icon>
                        </template>
                        <v-list-item-title class="text-body-2">My Team</v-list-item-title>
                        <v-list-item-subtitle class="text-caption">Team Management</v-list-item-subtitle>
                    </v-list-item>
                </v-list>

                <v-divider></v-divider>

                <div class="pa-3">
                    <v-btn block color="purple" variant="flat" class="logout-btn"
                        :height="$vuetify.display.smAndDown ? 36 : 40" @click="logout">
                        <v-icon size="18" class="mr-1">mdi-logout</v-icon>
                        Logout
                    </v-btn>
                </div>
            </v-card>
        </v-menu>
    </div>
</template>
<script>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue';
import { useAuthStore } from "@/stores/authStore";
import { useNotifications } from '@/stores/useNotifications';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

dayjs.extend(relativeTime);

export default {
    setup() {
        const user = ref(null);
        const authStore = useAuthStore();

        // Get notification functionality from composable
        const {
            notifications,
            unreadCount,
            activeTab,
            loading,
            markAsRead,
            markAllAsRead,
            setActiveTab,
            getNotificationIcon,
            getNotificationColor,
            formatDate,
        } = useNotifications();

        // Methods
        const logout = async () => {
            await authStore.logout();
        };

        const fetchUser = () => {
            user.value = authStore.user;
        };

        const handleTabChange = () => {
            setActiveTab(activeTab.value);
        };

        // Lifecycle hooks
        onMounted(() => {
            fetchUser();
        });

        // Add computed property for filtered notifications
        const filteredNotifications = computed(() => {
            if (activeTab.value === 'all') {
                return notifications.value;
            }
            return notifications.value.filter(notification =>
                notification.category === activeTab.value.toLowerCase()
            );
        });

        // Return everything needed in template
        return {
            // State
            user,
            notifications,
            unreadCount,
            activeTab,
            loading,

            // Methods
            logout,
            fetchUser,
            handleTabChange,
            markAsRead,
            markAllAsRead,
            getNotificationIcon,
            getNotificationColor,
            filteredNotifications,
            formatDate
        };
    }
};
</script>

<style scoped>
/* Your existing styles */
.notification-card {
    max-width: 400px;
    min-width: 360px;
    border-radius: 16px;
    overflow: hidden;
}

.notification-header {
    background-color: #9c27b0;
}

.notification-item {
    transition: background-color 0.2s ease;
    margin: 0 8px;
}

.notification-item:hover {
    background-color: rgb(var(--v-theme-primary), 0.09);
    cursor: pointer;
}

.unread {
    background-color: rgb(var(--v-theme-primary), 0.08);
}

.notification-content {
    max-width: 280px;
}

.custom-badge :deep(.v-badge__badge) {
    font-size: 12px;
    height: 20px !important;
    min-width: 20px;
    padding: 0 6px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.v-list-item:hover {
    cursor: pointer;
}

/* Mobile specific styles */
@media (max-width: 600px) {
    .notification-list {
        max-height: 300px;
    }

    .notification-item {
        padding: 8px;
    }
}


/* Custom badge styling */
.custom-badge :deep(.v-badge__badge) {
    font-size: 12px;
    height: 20px !important;
    min-width: 20px;
    padding: 0 6px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-dropdown {
    overflow: hidden;
    border: none;
}

.profile-header {
    background: linear-gradient(135deg, #9c27b0, #673ab7);
}

.profile-avatar {
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.menu-item {
    min-height: 48px;
    margin: 2px 4px;
    border-radius: 6px;
}

.menu-item:hover {
    background-color: rgb(var(--v-theme-purple), 0.05);
}

.logout-btn {
    text-transform: none;
    font-weight: 500;
    font-size: 0.875rem;
}

/* Animation */
.slide-y-transition-enter-active,
.slide-y-transition-leave-active {
    transition: transform 0.2s ease, opacity 0.2s ease;
}

.slide-y-transition-enter-from,
.slide-y-transition-leave-to {
    transform: translateY(8px);
    opacity: 0;
}


/* Rest of your existing styles */
</style>