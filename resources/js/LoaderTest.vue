<template>
    <div>
        <button @click="showLoader">Show Loader</button>
        <button @click="hideLoader">Hide Loader</button>

        <!-- Loader component should render if isLoading is true -->
        <Loader v-if="isLoading" />
    </div>
</template>

<script>
import { ref } from "vue";
import Loader from "@/components/Loader.vue"; // Adjust the path to your Loader component

export default {
    components: {
        Loader,
    },
    setup() {
        const isLoading = ref(false); // Reactive variable to track the loader state

        const showLoader = () => {
            isLoading.value = true;
            console.log("Loader is shown");
        };

        const hideLoader = () => {
            isLoading.value = false;
            console.log("Loader is hidden");
        };

        return { isLoading, showLoader, hideLoader };
    },
};
</script>

<style scoped>
/* Add some styles for the buttons */
button {
    margin: 10px;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    background-color: #007bff;
    color: white;
}
</style>
