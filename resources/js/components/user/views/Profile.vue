<template>
    <v-container>
        <!-- Profile Update Card -->
        <v-row>
            <v-card class="details-card pa-4 rounded-lg shadow-lg" style="margin: 0 auto; background-color: #f9f9f9;">
                <v-card-title class="d-flex justify-space-between align-center">
                    <span class="text-h6 font-weight-bold">My Profile</span>
                    <v-chip color="success" size="small" variant="tonal" prepend-icon="mdi-check-circle">
                        Verified Account
                    </v-chip>
                </v-card-title>

                <v-divider class="my-3"></v-divider>

                <v-form ref="form" @submit.prevent="handleSubmit">
                    <v-row class="mt-4">
                        <v-col cols="12" md="6">
                            <v-text-field v-model="formData.first_name" label="First Name" outlined dense
                                :readonly="isVerifying" prepend-inner-icon="mdi-account" :rules="[rules.required]"
                                color="purple" class="custom-purple-input"></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-text-field v-model="formData.last_name" label="Last Name" outlined dense
                                :readonly="isVerifying" prepend-inner-icon="mdi-account" :rules="[rules.required]"
                                color="purple" class="custom-purple-input"></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-text-field v-model="formData.middle_name" label="Middle Name" outlined dense
                                :readonly="isVerifying" prepend-inner-icon="mdi-account" color="purple"
                                class="custom-purple-input"></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-text-field v-model="formData.phone" label="Phone" outlined dense
                                :readonly="isVerifying" prepend-inner-icon="mdi-phone"
                                :rules="[rules.required, rules.phone]" color="purple"
                                class="custom-purple-input"></v-text-field>
                        </v-col>

                        <v-col cols="12">
                            <v-text-field v-model="formData.email" label="Email" outlined dense
                                :readonly="isVerifying" prepend-inner-icon="mdi-email"
                                :rules="[rules.required, rules.email]" color="purple"
                                class="custom-purple-input"></v-text-field>
                        </v-col>
                    </v-row>

                    <!-- Verification Section -->
                    <v-expand-transition>
                        <div v-if="isVerifying" class="mt-4">
                            <v-alert color="success" variant="tonal" border="start">
                                Please enter the verification code sent to {{ originalEmail }}
                            </v-alert>
                            <v-text-field v-model="verificationCode" label="Verification Code" variant="outlined"
                                class="mt-4 custom-purple-input" :rules="[rules.required]"></v-text-field>
                        </div>
                    </v-expand-transition>

                    <v-card-actions class="mt-4">
                        <v-spacer></v-spacer>
                        <v-btn color="purple" type="submit" variant="elevated" :loading="loading" :disabled="loading">
                            {{ isVerifying ? "Verify & Update" : "Update Profile" }}
                        </v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-row>

        <!-- Password Update Card -->
        <v-row class="mt-10">
            <v-card class="details-card pa-4 rounded-lg shadow-lg" style="margin: 0 auto; background-color: #f9f9f9;">
                <v-card-title class="d-flex justify-space-between align-center">
                    <span class="text-h6 font-weight-bold">Update Password</span>
                </v-card-title>

                <v-divider class="my-3"></v-divider>

                <v-form ref="passwordForm" @submit.prevent="handlePasswordUpdate">
                    <v-row class="mt-4">
                        <v-col cols="12" md="12">
                            <v-text-field v-model="passwordData.current_password" label="Current Password"
                                type="password" outlined dense :rules="[rules.required]"
                                prepend-inner-icon="mdi-lock" color="purple"></v-text-field>
                        </v-col>

                        <v-col cols="12" md="12">
                            <v-text-field v-model="passwordData.new_password" label="New Password" type="password"
                                 outlined dense :rules="[rules.required, rules.passwordStrength]"
                                prepend-inner-icon="mdi-lock" color="purple"></v-text-field>
                        </v-col>

                        <v-col cols="12" md="12">
                            <v-text-field v-model="passwordData.confirm_password" label="Confirm Password"
                                type="password"  outlined dense :rules="[rules.required, rules.passwordMatch]"
                                prepend-inner-icon="mdi-lock" color="purple"></v-text-field>
                        </v-col>
                    </v-row>

                    <v-card-actions class="mt-4 d-flex justify-end">
                        <v-btn color="purple" type="submit" variant="elevated" :loading="passwordLoading"
                            :disabled="passwordLoading" class="font-weight-bold">
                            Update Password
                        </v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-row>

        <!-- Deactivate Account Card -->
        <v-row class="mt-10">
            <v-card class="details-card pa-4 rounded-lg shadow-lg" style="margin: 0 auto; background-color: #f9f9f9;">
                <v-card-title class="d-flex justify-space-between align-center">
                    <span class="text-h6 font-weight-bold">Deactivate Account</span>
                </v-card-title>

                <v-divider class="my-3"></v-divider>

                <v-form ref="deactivateForm"
                    @submit.prevent="verificationCode ? handleDeactivate() : sendVerificationCode()">
                    <v-alert type="" outlined border="start" class="rounded-lg mb-6">
                        <div>
                            <h4 class="text-gray-900 fw-bold">You Are Deactivating Your Account</h4>
                            <p class="text-gray-700">
                                For extra security, we will send a verification code to your email. Please input the
                                code below to confirm your account deactivation.
                            </p>
                        </div>
                    </v-alert>

                    <!-- Step 1: Confirmation Checkbox -->
                    <div v-if="!verificationCode">
                        <v-checkbox v-model="confirmDeactivation" label="I confirm my account deactivation"
                            :rules="[rules.deactivate]" class="mb-1"></v-checkbox>

                        <v-card-actions class="d-flex justify-end py-4 px-9">
                            <v-btn size="large" variant="elevated" color="red" type="submit" :loading="deactivateLoading"
                                :disabled="deactivateLoading || !confirmDeactivation" class="fw-semibold">
                                Deactivate Account
                            </v-btn>
                        </v-card-actions>
                    </div>

                    <!-- Step 2: Input Verification Code -->
                    <div v-else>
                        <v-text-field v-model="verificationCodeInput" label="Verification Code" variant="outlined"
                            :rules="[rules.required]" color="primary" class="mb-1"></v-text-field>

                            <v-card-actions class="d-flex justify-end py-6 px-9">
                        <v-btn color="red" type="submit" :loading="deactivateLoading"
                            :disabled="deactivateLoading || !verificationCodeInput" class="fw-semibold">
                            Confirm and Deactivate
                        </v-btn>
                    </v-card-actions>
                    </div>
                </v-form>

            </v-card>
        </v-row>

        <!-- <v-btn color="primary" @click="startCountdown" class="mt-4">
            Test Countdown
        </v-btn> -->
    </v-container>

    <!-- Custom Overlay -->
    <div v-if="countdownActive" class="custom-overlay">
        <div class="overlay-content text-center">
            <h1 class="text-white mb-4">Logging out in {{ countdown }} seconds...</h1>
            <v-progress-circular :value="(countdown / 3) * 100" size="100" width="10"
                color="white"></v-progress-circular>
        </div>
    </div>

</template>

<script>
import { ref, reactive, watch, onMounted } from "vue";
import { useToast } from "vue-toastification";
import axios from "axios";
import {
    useAuthStore
} from '@/stores/authStore'; // Correct path to your store file

export default {
    setup() {
        const toast = useToast();
        const form = ref(null);
        const passwordForm = ref(null);
        const loading = ref(false);
        const passwordLoading = ref(false);
        const isVerifying = ref(false);
        const originalEmail = ref("");
        const verificationCode = ref("");
        const deactivateForm = ref(null);
        const sendingCode = ref(false);
        const deactivateLoading = ref(false);
        const confirmDeactivation = ref(false);
        const verificationSent = ref(false);
        const verificationCodeInput = ref(""); // Stores the input code
        const countdown = ref(3); // Countdown for logout
        const countdownActive = ref(false); // Tracks if countdown is active

        const formData = reactive({
            first_name: "",
            last_name: "",
            middle_name: "",
            phone: "",
            email: "",
        });

        const passwordData = reactive({
            current_password: "",
            new_password: "",
            confirm_password: "",
        });

        const rules = {
            deactivate: (value) => !!value || "You must confirm your account deactivation",
            required: (value) => !!value || "This field is required",
            email: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) || "Invalid email format",
            phone: (value) =>
                /^\d{10}$/.test(value.replace(/\D/g, "")) || "Invalid phone number format (10 digits required)",
            passwordStrength: (value) =>
                value.length >= 8 || "Password must be at least 8 characters",
            passwordMatch: (value) =>
                value === passwordData.new_password || "Passwords do not match",
        };

        // Send verification code to the user's email
        const sendVerificationCode = async () => {
            try {
                deactivateLoading.value = true;
                const response = await axios.post("/api/send-deactivation-code");

                if (response.status === 200) {
                    // toast.success("Verification code sent to your email.");
                    verificationCode.value = true; // Proceed to step 2
                } else {
                    throw new Error("Failed to send verification code.");
                }
            } catch (error) {
                console.error("Error sending verification code:", error);
                toast.error("Unable to send verification code. Please try again later.");
            } finally {
                deactivateLoading.value = false;
            }
        };

        // Handle account deactivation
        const handleDeactivate = async () => {
            try {
                deactivateLoading.value = true;

                // Send the verification code to the backend for validation
                const response = await axios.post("/api/deactivate-account", {
                    code: verificationCodeInput.value,
                });

                if (response.status === 200) {
                    toast.success("Your account has been successfully deactivated.");
                    sendDeactivationEmail(); // Send email after deactivation
                    startCountdown(); // Start the logout countdown
                } else {
                    throw new Error("Account deactivation failed.");
                }
            } catch (error) {
                console.error("Error deactivating account:", error);
                toast.error(error.response?.data?.message || "Failed to deactivate account.");
            } finally {
                deactivateLoading.value = false;
            }
        };

        // Start the countdown to logout
        const startCountdown = () => {
            console.log("Countdown started"); // Debugging message
            countdownActive.value = true;
            const interval = setInterval(() => {
                console.log("Countdown:", countdown.value); // Log the countdown value
                countdown.value -= 1;
                if (countdown.value === 0) {
                    clearInterval(interval);
                    resetCountdown(); // Reset the countdown after logout
                    logoutUser();
                }
            }, 1000);
        };

        const resetCountdown = () => {
            countdown.value = 3; // Reset to initial countdown value
            countdownActive.value = false; // Hide the overlay
        };


        // Log out the user
        const logoutUser = () => {
            // Redirect to logout endpoint or clear tokens
            const authStore = useAuthStore();
            authStore.logoutWithoutConfirmation();
        };

        // Send deactivation email
        const sendDeactivationEmail = async () => {
            try {
                await axios.post("/api/send-deactivation-email");
                // toast.info("A confirmation email has been sent.");
            } catch (error) {
                console.error("Error sending deactivation email:", error);
            }
        };

        const fetchProfile = async () => {
            try {
                loading.value = true;
                const response = await axios.get("/user-data", { params: { personal_info: true } });
                Object.assign(formData, response.data.personal_info || {});
                originalEmail.value = formData.email;
            } catch (error) {
                toast.error("Failed to load profile");
            } finally {
                loading.value = false;
            }
        };

        const handleSubmit = async () => {
            if (!form.value) {
                toast.error("Form not found");
                return;
            }

            // Validate the form and extract `valid` from the returned object
            const { valid } = await form.value.validate();

            // Stop submission if the form is invalid
            if (!valid) {
                toast.error("Please fill all required fields correctly");
                return;
            }

            const isEmailChanged = formData.email !== originalEmail.value;
            if (isEmailChanged && !isVerifying.value) {
                console.log("Email has changed and not verifying yet.");
                try {
                    loading.value = true;
                    const response = await axios.post("/api/send-verification", {
                        email: formData.email,
                    });
                    if (response.status === 200) {
                        isVerifying.value = true;
                        toast.info("Verification code sent to your email");
                    } else {
                        throw new Error("Failed to send verification code");
                    }
                } catch (error) {
                    console.error("Error sending verification email:", error);
                    toast.error("Failed to send verification code");
                } finally {
                    loading.value = false;
                }
                return;
            }

            if (isEmailChanged && !verificationCode.value) {
                toast.error("Please enter the verification code");
                return;
            }

            // Handle form submission
            try {
                loading.value = true;
                const payload = {
                    ...formData,
                    verificationCode: isEmailChanged ? verificationCode.value : null,
                };
                const response = await axios.post("/api/profile/update", payload);
                if (response.status === 200) {
                    toast.success("Profile updated successfully");
                    if (isEmailChanged) {
                        isVerifying.value = false;
                        verificationCode.value = "";
                        originalEmail.value = formData.email;
                    }
                } else {
                    throw new Error("Profile update failed");
                }
            } catch (error) {
                console.error("Error updating profile:", error);
                toast.error(error.response?.data?.message || "Failed to update profile");
            } finally {
                loading.value = false;
            }
        };

        const handlePasswordUpdate = async () => {
            if (!passwordForm.value) {
                toast.error("Form reference is not found.");
                return;
            }

            // Validate the form
            const { valid } = await passwordForm.value.validate();
            if (!valid) {
                toast.error("Please fill all required fields correctly.");
                return;
            }

            // Proceed with password update
            try {
                passwordLoading.value = true;

                const response = await axios.post('/api/update-password', {
                    current_password: passwordData.current_password,
                    new_password: passwordData.new_password,
                    new_password_confirmation: passwordData.confirm_password,
                });
                if (response.status === 200) {
                    toast.success("Password updated successfully!");
                    // Clear password fields after success
                    Object.assign(passwordData, {
                        current_password: "",
                        new_password: "",
                        confirm_password: "",
                    });
                    // Reset the form validation state
                    passwordForm.value.reset();
                } else {
                    throw new Error("Password update failed");
                }
            } catch (error) {
                console.error("Error updating password:", error);
                toast.error(error.response?.data?.message || "Failed to update password.");
            } finally {
                passwordLoading.value = false;
            }
        };

        watch(isVerifying, (newValue) => {
            console.log('isVerifying changed:', newValue);
        });

        // Watch changes to formData and validate
        watch(formData, async (newValue, oldValue) => {
            console.log("formData changed:", { oldValue, newValue });

            if (form.value) {
                const isValid = await form.value.validate();
                console.log("Validation status after formData change:", isValid);
            } else {
                console.error("Form reference is not available.");
            }
        });

        watch(verificationCode, (newValue) => {
            if (newValue) {
                toast.info("Please enter the verification code sent to your email.");
            }
        });

        onMounted(fetchProfile);

        return {
            handleDeactivate,
            sendVerificationCode,
            deactivateForm,
            verificationCodeInput,
            confirmDeactivation,
            deactivateLoading,
            form,
            sendingCode,
            countdownActive,
            countdown,
            passwordForm,
            formData,
            passwordData,
            loading,
            startCountdown,
            passwordLoading,
            isVerifying,
            verificationCode,
            rules,
            handleSubmit,
            handlePasswordUpdate,
        };
    },
};
</script>

<style scoped>
.custom-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    /* Black with transparency */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    /* Ensure it stays above all other elements */
}

.overlay-content {
    text-align: center;
    color: white;
}

.overlay-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    /* Full viewport height */
    width: 100vw;
    /* Full viewport width */
}

.details-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.custom-purple-input .v-input {
    border: 2px solid #7e57c2 !important;
    border-radius: 6px;
}

.custom-purple-input .v-input.v-input--focused {
    border-color: #5e35b1 !important;
}

.custom-purple-input .v-label {
    color: #7e57c2 !important;
}

.text-gray-900 {
    color: #212121 !important;
}

.text-gray-700 {
    color: #616161 !important;
}

.fw-bold {
    font-weight: bold;
}

.fw-semibold {
    font-weight: 600;
}

.bg-warning {
    background-color: #2100356b !important;
}
</style>