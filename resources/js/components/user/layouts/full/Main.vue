<script setup>
import { ref, shallowRef, onMounted } from 'vue';
import NavGroup from './vertical-sidebar/NavGroup/index.vue';
import NavItem from './vertical-sidebar/NavItem/index.vue';
import Logo from './logo/Logo.vue';
// Icon Imports
import { Menu2Icon } from 'vue-tabler-icons';
import NotificationDD from './vertical-header/NotificationDD.vue';
import ProfileDD from './vertical-header/ProfileDD.vue';

// const NavGroup = () => import('./vertical-sidebar/NavGroup/index.vue');
// const NavItem = () => import('./vertical-sidebar/NavItem/index.vue');
// const NotificationDD = () => import('./vertical-header/NotificationDD.vue');
// const ProfileDD = () => import('./vertical-header/ProfileDD.vue');


// Use a shallowRef to avoid deep reactivity
const sidebarMenu = shallowRef(null);

// Sidebar Drawer State
const sDrawer = ref(true);

// Load sidebar items dynamically when the component is mounted
onMounted(async () => {
    try {
        const module = await import('./vertical-sidebar/sidebarItem.js');
        sidebarMenu.value = module.default; // Assign the imported module's default export
    } catch (error) {
        console.error("Failed to load sidebar items:", error);
        sidebarMenu.value = []; // Fallback to an empty array if import fails
    }
});
</script>

<template>
    <v-navigation-drawer
        left
        v-model="sDrawer"
        app
        class="leftSidebar ml-sm-5 mt-sm-5 bg-containerBg"
        elevation="10"
        width="270"
    >
        <div class="pa-5 pl-4 ">
            <Logo />
        </div>
        <!-- Navigation -->
        <perfect-scrollbar class="scrollnavbar bg-containerBg overflow-y-hidden">
            <v-list class="py-4 px-4 bg-containerBg">
                <!---Menu Loop -->
                <template v-if="sidebarMenu">
                    <template v-for="(item, i) in sidebarMenu" :key="i">
                        <!---Item Sub Header -->
                        <NavGroup v-if="item.header" :item="item" />
                        <!---Single Item-->
                        <NavItem v-else class="leftPadding" :item="item" />
                    </template>
                </template>
                <template v-else>
                    <div class="text-center text-muted">
                        Loading menu...
                    </div>
                </template>
            </v-list>
        </perfect-scrollbar>
    </v-navigation-drawer>
    <div class="container verticalLayout">
        <div class="maxWidth">
            <v-app-bar elevation="0" height="70">
                <div class="d-flex align-center justify-space-between w-100">
                    <div>
                        <v-btn
                            class="hidden-lg-and-up text-muted"
                            @click="sDrawer = !sDrawer"
                            icon
                            variant="flat"
                            size="small"
                        >
                            <Menu2Icon size="20" stroke-width="1.5" />
                        </v-btn>
                        <!-- Notification -->
                        <NotificationDD />
                    </div>
                    <div>
                        <!-- User Profile -->
                        <ProfileDD />
                    </div>
                </div>
            </v-app-bar>
        </div>
    </div>
</template>
