<template>
    <div></div> <!-- No UI elements required -->
</template>

<script>
export default {
    methods: {
        /**
         * Display a toast notification
         * @param {Object} options - Options for the toast
         */
        showNotification({ message, type = "success", position = "top-right", timeout = 5000 }) {
            this.$toast(message, {
                type,
                position,
                timeout,
            });
        },
    },
};
</script>



<style scoped>
/* .Toast--success {
    background-color: #28a745 !important;
}

.Toast--error {
    background-color: #dc3545 !important;
}

.Toast--info {
    background-color: #17a2b8 !important;
}

.Toast--warning {
    background-color: #ffc107 !important;
} */
</style>