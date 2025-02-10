<script setup>
import Icon from '../Icon.vue';
import { useRoute } from "vue-router"; // Import Vue Router's `useRoute`

const props = defineProps({ item: Object, level: Number });

const route = useRoute(); // Get the current route

// Check if the current route matches the item
const isActive = (to) => {
  // Treat "/" as "/dashboard"
  
  const normalizedTo = to === '/' ? '/dashboard' : to;
  
  return route.path === normalizedTo; // Compare normalized path with current route
};
</script>

<template>
    <!-- Single Menu Item -->
    <div class="menu-item">
        <v-list-item 
            :to="item.type === 'external' ? '' : resolveRoute(item.to)" 
            :href="item.type === 'external' ? item.to : ''"
            :class="[ 
                'menu-link',
                { 'active-tab': isActive(resolveRoute(item.to)) } // Use isActive function
            ]" 
            :ripple="false" 
            :disabled="item.disabled" 
            :target="item.type === 'external' ? '_blank' : ''"
        >
            <!-- Icon Section -->
            <template v-slot:prepend>
                <div class="menu-icon">
                    <Icon :item="item.icon" :level="level" class="icon-box" />
                </div>
            </template>

            <!-- Title -->
            <v-list-item-title class="menu-title">
                {{ item.title }}
            </v-list-item-title>
        </v-list-item>
    </div>
</template>

<script>
export default {
    methods: {
        /**
         * Resolve route to handle base '/' and redirect it to '/dashboard'
         * @param {String} route The route to resolve
         * @returns {String} The resolved route
         */
        resolveRoute(route) {
            // Replace '/' with '/dashboard' if the route is the base route
            return route === '/' ? '/dashboard' : route;
        },
        /**
         * Check if the given route is the active one
         * @param {String} route The route to check
         * @returns {Boolean} Whether the route is active
         */
        isActive(route) {
            return this.$route.path === route;
        },
    },
};</script>  

<style scoped>
/* Menu Item */
.menu-item {
    margin-bottom: 20px; /* Consistent spacing */
    position: relative;
}

.menu-link {
    padding: 12px 16px;
    display: flex;
    align-items: center;
    border-radius: 8px;
    transition: all 0.3s ease;
    color: #210035 !important; /* Ensure uniform color */
    font-size: 16px;
    position: relative;
    overflow: hidden;
}

/* Pseudo-element for full-width background animation */
.menu-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 0;
    background-color: #e0d4ec; /* Background color for hover/active */
    transition: width 0.4s ease; /* Smooth left-to-right animation */
    z-index: 1;
}

.menu-link:hover::before,
.menu-link.active-tab::before {
    width: 100%; /* Expand background to full width */
}

.menu-link:hover,
.menu-link.active-tab {
    padding: 15px !important;
    border-radius: 0 50px 50px 0 !important;
    color: #210035 !important;
    /* Ensure consistent text color */
}


/* Ensure text and icons are above the pseudo-element */
.menu-title,
.menu-icon {
    position: relative;
    z-index: 2;
}

/* Remove the icon background */
.menu-icon {
    width: 24px;
    height: 24px;
    margin-right: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-box {
    font-size: 24px;
    color: #210035; /* Icon color */
}

/* Title Styling */
.menu-title {
    font-weight: 600;
    font-size: 16px;
    color: #210035 !important;
}
</style>
