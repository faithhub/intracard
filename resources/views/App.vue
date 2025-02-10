<!-- <template>
    <div>
        <Loader v-if="showLoader" />
        <router-view />
    </div>
</template>

<script>
import { useLoaderStore } from "@/stores/loaderStore";
import { useAuthStore } from "@/stores/authStore";
import { loadAssets } from "@/utils/assetLoader";
import Loader from "@/components/Loader.vue";

export default {
    components: {
        Loader, // Dynamic import of the Loader component
    },

    setup() {
        const loaderStore = useLoaderStore();

        return {
            showLoader: loaderStore.isLoading,
        };
    },

    async mounted() {
        const authStore = useAuthStore();
        const loaderStore = useLoaderStore();

        loaderStore.showLoader(); // Show loader for initialization

        try {
            // Example: Check auth and load assets
            if (authStore.isAuthenticated) {
                await authStore.fetchAuthStatus();
                await loadAssets(authStore.user?.role || "guest");
                authStore.startSessionPing();
            } else {
                await loadAssets("guest");
                authStore.stopSessionPing();
            }
        } catch (error) {
            console.error("Error during app initialization:", error);
        } finally {
            loaderStore.hideLoader(); // Ensure loader is hidden after initialization
        }
    },
};
</script>

<style>
/* Add global styles if needed */
</style> -->

<template>
    <div>
        <!-- Page Loader - Full screen overlay for page transitions -->
        <!-- <Loader 
            v-if="loader.isPageLoading" 
            type="page"
        /> -->
        
        <!-- Background Loader - Subtle indicator for background operations -->
        <!-- <Loader 
            v-if="loader.isBackgroundLoading" 
            type="background"
        /> -->
        
        <!-- Overlay Loader - For modal/specific section loading -->
        <!-- <Loader 
            v-if="loader.isOverlayLoading" 
            type="overlay"
        /> -->

        <!-- Main app content -->
        <router-view />
    </div>
</template>

<script>
import { useLoaderStore, LoadingType } from "@/stores/loaderStore";
import { useAuthStore } from "@/stores/authStore";
import { loadAssets } from "@/utils/assetLoader";
import Loader from "@/components/Loader.vue";

export default {
    components: {
        Loader,
    },

    setup() {
        const loader = useLoaderStore(); // Access loader store

        return {
            loader, // Expose loader store to template
        };
    },

    async mounted() {
        const authStore = useAuthStore(); // Access auth store
        const loader = useLoaderStore(); // Access loader store
        console.log(loader.isBackgroundLoading, loader.isOverlayLoading);
        

        // Show page loader for initialization
        loader.showLoader(LoadingType.PAGE);

        try {
            if (authStore.isAuthenticated) {
                // Fetch user authentication status
                await authStore.fetchAuthStatus();
                // Load assets based on user role
                await loadAssets(authStore.user?.role || "guest");
                // Start session pinging for authenticated users
                authStore.startSessionPing();
            } else {
                // Load assets for guest users
                await loadAssets("guest");
                // Stop session pinging for unauthenticated users
                authStore.stopSessionPing();
            }
        } catch (error) {
            // Log any errors during initialization
            console.error("Error during app initialization:", error);
        } finally {
            // Hide the page loader after initialization
            loader.hideLoader(LoadingType.PAGE);
        }
    },
};
</script>

<style>
/* Optionally, include any styles for loaders or transitions */
.primary-bg {
  background-color: #e0d4ec !important;
}
</style>
