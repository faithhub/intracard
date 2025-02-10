<template>
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-xl-row flex-column-fluid">
            <div class="container">
                <div class="center-div">
                    <div class="flex-row-fluid flex-center justfiy-content-xl-first p-10">
                        <div class="">
                            <form class="form" novalidate="novalidate" @submit.prevent="verifyOTP">
                                <div class="text-center mb-9">
                                    <router-link class="mb-15" to="">
                                        <img alt="Logo" src="@assets/logos/intracard.png" class="h-100px" />
                                    </router-link>
                                    <h2 class="text-gray-900 mb-3">Verify OTP</h2>
                                    <div class="text-gray-500 fw-semibold fs-4">
                                        Enter the OTP code sent to your email
                                    </div>
                                </div>
                                <div class="fv-row mb-5 text-center">
                                    <p v-if="error" class="text-danger mb-3">{{ error }}</p>
                                    <div class="otp-container">
                                        <input v-for="(box, index) in 6" :key="index" ref="otpInputs" :maxlength="1"
                                            type="text" class="otp-box" @input="handleInput(index, $event)"
                                            @keydown.backspace="handleBackspace(index, $event)" />
                                    </div>
                                    <div class="text-center mt-9">
                                        <button class="btn btn-dark" type="submit" :disabled="!isFormValid || loading">
                                            <span v-if="!loading">Verify & Proceed</span>
                                            <span v-else>Verifying...</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button @click="handleLogout" class="btn btn-outline-danger-custom mt-2"
                                        type="button">Logout</button>
                                    <div class="mt-4">
                                        <p class="text-black">
                                            If you haven't received your OTP, you can request it again in
                                            <span class="countdown">{{ countdown }}</span>
                                        </p>
                                        <button
                                            :class="{ 'btn-secondary': countdownTime > 0, 'btn-primary': countdownTime === 0 }"
                                            :disabled="countdownTime > 0 || resendLoading" @click.prevent="resendOTP"
                                            class="btn" type="button">
                                            <span v-if="resendLoading">Resending...</span>
                                            <span v-else>Resend OTP</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "@/utils/axios";
import { useAuthStore } from "@/stores/authStore";
import { useToast } from 'vue-toastification'; // Use the package name directly
import router from '@/router/index.js';

export default {
    setup() {
        const authStore = useAuthStore();

        return {
            logout: authStore.logout, // Access the logout method from the store
        };
    },
    data() {
        return {
            otp: Array(6).fill(""), // Six boxes for OTP
            loading: false,
            resendLoading: false, // For Resend OTP button
            error: null,
            countdownTime: 300, // 5 minutes in seconds
            intervalId: null,
        };
    },
    computed: {
        isFormValid() {
            // Check if all OTP inputs are filled and the length is exactly 6
            return this.otp.every((value) => value !== "") && this.otp.join("").length === 6;
        },
        countdown() {
            const minutes = Math.floor(this.countdownTime / 60);
            const seconds = this.countdownTime % 60;
            return `${minutes}:${seconds.toString().padStart(2, "0")}`;
        },
    },
    methods: {
        // Handle OTP input focus
        handleInput(index, event) {
            const value = event.target.value;
            if (/^[0-9]$/.test(value)) {
                this.otp[index] = value;
                if (index < 5) {
                    this.$refs.otpInputs[index + 1].focus();
                }
            } else {
                event.target.value = ""; // Reset invalid input
            }
        },
        handleBackspace(index, event) {
            if (event.target.value === "") {
                this.otp[index] = ""; // Clear the value in the OTP array
                if (index > 0) {
                    this.$refs.otpInputs[index - 1].focus();
                }
            } else {
                this.otp[index] = ""; // Clear the value in the OTP array
            }
        },
        async resendOTP() {
            const toast = useToast(); // Toast notification
            this.resendLoading = true; // Start resend button loading

            try {
                const response = await axios.post("/auth/otp-resend");

                // Handle success response
                console.log(response);
                
                if (response.data.success) {
                    toast.success(response.data.message || "OTP resent successfully!", { timeout: 3000 });
                    this.countdownTime = 300; // Reset countdown timer
                    localStorage.setItem("otpCountdown", Math.floor(Date.now() / 1000) + 300);
                } else {
                    toast.error(response.data.message || "Failed to resend OTP.", { timeout: 5000 });
                }
            } catch (error) {
                toast.error("Failed to resend OTP. Please try again later.", { timeout: 5000 });
            } finally {
                this.resendLoading = false; // Stop resend button loading
            }
        }
        ,
        async verifyOTP() {
            const toast = useToast();
            this.loading = true;
            this.error = null;
            try {
                const otpCode = this.otp.join(""); // Combine OTP inputs
                if (!this.isFormValid) {
                    throw new Error("Please fill in all the OTP boxes.");
                }

                console.log(otpCode);

                const response = await axios.post("/auth/otp-verify", {
                    otp_code: otpCode,
                });
                if (response.data.success) {
                    const authStore = useAuthStore()


                    // Update the user state using the updateUserState function
                    await authStore.updateUserState({
                        ...authStore.user,
                        otp_verified: true
                    });

                    // Display success message and redirect to dashboard
                    toast.success(response.data.message || "OTP verified successfully!", { timeout: 3000 });
                    router.push(response.data.redirect_url || "/dashboard");
                    return; // Exit function after successful handling
                } else {
                    toast.error(response.data.message || "Failed to verify OTP.", { timeout: 5000 });
                }
            } catch (error) {
                if (error.message === "Please fill in all the OTP boxes.") {
                    this.error = error.message;
                    toast.error(this.error, { timeout: 5000 });
                } else if (error.response) {
                    if (error.response.status === 422) {
                        const validationErrors = error.response.data.errors || {};
                        const firstError = Object.values(validationErrors)[0][0] || "Validation failed.";
                        this.error = firstError;
                        toast.error(firstError, { timeout: 5000 });
                    } else if (error.response.status === 409) {
                        this.error = error.response.data.message || "Invalid or expired OTP.";
                        toast.error(this.error, { timeout: 5000 });
                    } else {
                        this.error = error.response.data.message || "An unexpected server error occurred.";
                        toast.error(this.error, { timeout: 5000 });
                    }
                } else if (error.request) {
                    this.error = "No response from the server. Please try again.";
                    toast.error(this.error, { timeout: 5000 });
                } else {
                    this.error = "An error occurred while verifying OTP.";
                    toast.error(this.error, { timeout: 5000 });
                }
            } finally {
                this.loading = false;
            }
        },
        startCountdown() {
            const storedTime = localStorage.getItem("otpCountdown");
            const now = Math.floor(Date.now() / 1000);

            if (storedTime && storedTime > now) {
                this.countdownTime = storedTime - now;
            } else {
                this.countdownTime = 300; // Reset to 5 minutes
                localStorage.setItem("otpCountdown", now + 300);
            }

            this.intervalId = setInterval(() => {
                if (this.countdownTime > 0) {
                    this.countdownTime--;
                } else {
                    clearInterval(this.intervalId);
                }
            }, 1000);
        },
        async handleLogout() {
            const toast = useToast();

            try {
                // Call the logout method from AuthStore
                await this.logout();
            } catch (error) {
                toast.error("Logout failed. Please try again.", { timeout: 5000 });
            }
        },
    },
    mounted() {
        this.startCountdown();
    },
    beforeUnmount() {
        clearInterval(this.intervalId); // Clear countdown timer
    },
};
</script>

<style scoped>
.btn:not(.btn-outline):not(.btn-dashed):not(.btn-bordered):not(.border-hover):not(.border-active):not(.btn-flush):not(.btn-icon):not(.btn-hover-outline) {
    border: 0;
    padding: 12px;
}

.otp-container {
    display: inline-flex;
    gap: 10px;
}

input {
    border-style: dashed !important;
}

.otp-box {
    width: 40px;
    height: 40px;
    font-size: 18px;
    /* border: 2px black !important; */
    text-align: center;
    border-radius: 9px !important;
}

.countdown {
    color: red;
    font-weight: 600;
}

/* Custom style for the outline-danger button */
.btn-outline-danger-custom {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    color: #dc3545;
    /* Bootstrap "danger" color */
    border: 2px solid #dc3545;
    /* Solid red border */
    border-radius: 8px;
    /* Rounded corners */
    background-color: transparent;
    /* Transparent background */
    cursor: pointer;
    /* Pointer cursor on hover */
    transition: all 0.3s ease;
    /* Smooth transition for hover effects */
}

/* Hover effect */
.btn-outline-danger-custom:hover {
    background-color: #dc3545;
    /* Red background on hover */
    color: #fff;
    /* White text on hover */
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
    /* Subtle shadow effect */
}

/* Focus effect */
.btn-outline-danger-custom:focus {
    outline: none;
    /* Remove default focus outline */
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.5);
    /* Red focus outline */
}

/* Disabled state */
.btn-outline-danger-custom:disabled {
    color: #dc3545;
    /* Keep text red */
    background-color: transparent;
    /* Transparent background */
    opacity: 0.6;
    /* Reduce opacity */
    cursor: not-allowed;
    /* Show not-allowed cursor */
}

.btn-secondary {
    background-color: #6c757d;
    /* Bootstrap gray */
    color: white;
    cursor: not-allowed;
}

.btn-primary {
    background-color: #111318;
    /* Bootstrap blue */
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #111318;
    /* Darker blue on hover */
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
    color: #e74c3c !important;
    /* Red color for error messages */
    font-size: 1rem;
    /* Slightly smaller font size */
    margin-top: 5px;
    /* Space between input and error message */
    display: block;
    /* Make sure it's displayed as a block below the input */
    font-weight: 600;
    /* Medium weight to stand out */
}
</style>
