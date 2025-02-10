<template>
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="container">
            <div class="center-div">
                <div class="flex-row-fluid flex-center justify-content-xl-first p-10">
                    <div>
                        <form @submit.prevent="submitForm" class="form" novalidate>
                            <div class="text-center mb-10">
                                <router-link to="/" class="mb-15" @click.prevent>
                                    <img alt="Logo" src="@assets/logos/intracard.png" class="h-100px" />
                                </router-link>
                                <h3 class="text-gray-900 mb-3 mt-3">Sign in to your account</h3>
                            </div>
                            <div class="fv-row mb-10">
                                <label class="form-label fs-6 fw-bold text-gray-900">Email</label>
                                <input v-model="email" class="form-control form-control-lg form-control-solid"
                                    type="email" autocomplete="off" value="rent_new@gmail.com" @input="clearError('email')" />
                                <span class="text-danger">{{ errors.email }}</span>
                            </div>
                            <div class="fv-row mb-10">
                                <div class="d-flex flex-stack mb-2">
                                    <label class="form-label fw-bold text-gray-900 fs-6 mb-0">Password</label>
                                    <router-link to="/auth/forgot-password" class="link-dark fs-6 fw-bold"
                                        @click.prevent="goToForgotPassword">
                                        Forgot Password?
                                    </router-link>
                                </div>
                                <div class="position-relative">
                                    <input v-model="password" :type="showPassword ? 'text' : 'password'"
                                        class="form-control form-control-lg form-control-solid pe-10" autocomplete="off"
                                        @input="clearError('password')" />
                                    <button type="button"
                                        class="btn btn-sm btn-icon position-absolute end-0 top-50 translate-middle-y"
                                        @click="togglePassword">
                                        <i :class="showPassword ? 'fa fa-eye-slash' : 'fa fa-eye'"></i>
                                    </button>
                                </div>
                                <span class="text-danger">{{ errors.password }}</span>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-lg btn-primary w-100 mb-5" :disabled="loading">
                                    <span v-if="!loading">Sign In</span>
                                    <span v-else>
                                        Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center text-gray-500 fw-semibold fs-6 mt-1">
                                Didn't have an account?
                                <a href="/auth/sign-up" class="link-dark fw-bolder">
                                    Sign Up
                                </a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useAuthStore } from "@/stores/authStore";
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";

export default {
    setup() {
        const authStore = useAuthStore();
        const router = useRouter(); // Import the router using useRouter

        const email = ref("");
        const password = ref("");
        const showPassword = ref(false);
        const errors = computed(() => authStore.errors);
        const loading = computed(() => authStore.loading);

        const goToForgotPassword = (event) => {
            event.preventDefault();
            router.push("/auth/sign-up");
        };

        const togglePassword = () => {
            showPassword.value = !showPassword.value;
        };

        const clearError = (field) => {
            if (authStore.errors[field]) {
                authStore.errors[field] = "";
            }
        };

        const submitForm = async () => {
            const redirectUrl = await authStore.login(email.value, password.value);
            if (redirectUrl) {
                router.push(redirectUrl); // Redirect on successful login
            }
        };

        return {
            email,
            password,
            showPassword,
            errors,
            loading,
            goToForgotPassword,
            togglePassword,
            clearError,
            submitForm,
        };
    }
};

</script>

<style scoped>
.text-danger {
    color: red;
}

.spinner-border {
    width: 1rem;
    height: 1rem;
}

.text-center img {
    display: inline-block;
    height: 100px;
    width: auto;
    margin: 0 auto;
}


body,
html {
    height: 100%;
    /* Ensure the body takes the full height */
    margin: 0;
    /* Remove default margin */
}

.container {
    display: flex;
    justify-content: center;
    /* Center horizontally */
    align-items: center;
    /* Center vertically */
    height: 100vh;
    /* Full viewport height */
}

.center-div {
    width: 100%;
    /* Full width on small screens */
    padding: 20px;
    color: white;
    /* Text color */
    border-radius: 8px;
    /* Rounded corners */
    /* text-align: center; */
    /* Center text */
    /* box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Optional shadow for better appearance */
    box-shadow: -1px 13px 30px 0px rgba(93, 125, 135, 0.78);
    -webkit-box-shadow: -1px 13px 30px 0px rgba(93, 125, 135, 0.78);
    -moz-box-shadow: -1px 13px 30px 0px rgba(93, 125, 135, 0.78);
}

@media (min-width: 768px) {
    .center-div {
        max-width: 60%;
        /* Maximum width of 25% on larger screens */
    }
}

@media (min-width: 1000px) {
    .center-div {
        max-width: 50%;
        /* Maximum width of 25% on larger screens */
    }
}
@media (min-width: 1200px) {
    .center-div {
        max-width: 40%;
        /* Maximum width of 25% on larger screens */
    }
}

.fv-row .error-message {
    font-family: 'Kodchasan';
    color: #e74c3c !important; /* Red color for error messages */
    font-size: 1rem; /* Slightly smaller font size */
    margin-top: 5px; /* Space between input and error message */
    display: block; /* Make sure it's displayed as a block below the input */
    font-weight: 600; /* Medium weight to stand out */
}
</style>
