<template>
    <v-app>
            <v-container fluid class="pa-0 fill-height">
                <v-row no-gutters class="fill-height">
                    <!-- Left Sidebar -->
                    <v-col cols="12" md="3" class="bg-primary-light">
                        <v-sheet class="pa-6 h-100" color="transparent">
                            <!-- Logo -->
                            <div class="d-flex justify-center py-10">
                                <v-img src="/images/logo.png" max-width="200" contain />
                            </div>

                            <!-- Stepper -->
                            <v-stepper v-model="currentStep" direction="vertical" class="bg-transparent elevation-0">
                                <v-stepper-item v-for="(step, i) in steps" :key="i" :value="i + 1"
                                    :complete="currentStep > i + 1">
                                    <template v-slot:title>
                                        <div class="d-flex align-center">
                                            <v-icon :icon="step.icon" class="mr-2" />
                                            <span class="text-h6">{{ step.title }}</span>
                                        </div>
                                    </template>
                                    <template v-slot:subtitle>
                                        {{ step.subtitle }}
                                    </template>
                                </v-stepper-item>
                            </v-stepper>
                        </v-sheet>
                    </v-col>

                    <!-- Main Content -->
                    <v-col cols="12" md="9" class="pa-8">
                        <!-- Step 1: Personal Details -->
                        <v-window v-model="currentStep" :value="1">
                            <v-window-item value="1">
                                <v-card flat>
                                    <v-card-title class="text-h4 font-weight-bold">
                                        Let's get started
                                    </v-card-title>
                                    <v-card-subtitle class="text-subtitle-1 mt-2">
                                        You're just a few steps away from a more rewarding experience
                                    </v-card-subtitle>

                                    <v-card-text>
                                        <v-form ref="personalForm">
                                            <v-row>
                                                <v-col cols="12" md="6">
                                                    <v-text-field v-model="formData.firstName" label="First Name"
                                                        :rules="[v => !!v || 'First name is required']"
                                                        placeholder="Enter first name" variant="outlined"
                                                        density="comfortable" />
                                                </v-col>

                                                <v-col cols="12" md="6">
                                                    <v-text-field v-model="formData.lastName" label="Last Name"
                                                        :rules="[v => !!v || 'Last name is required']"
                                                        placeholder="Enter last name" variant="outlined"
                                                        density="comfortable" />
                                                </v-col>
                                            </v-row>

                                            <v-text-field v-model="formData.middleName" label="Middle Name (optional)"
                                                placeholder="Enter middle name" variant="outlined"
                                                density="comfortable" />

                                            <v-text-field v-model="formData.password" label="Password"
                                                :rules="passwordRules" placeholder="Enter password" variant="outlined"
                                                density="comfortable"
                                                :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                                                :type="showPassword ? 'text' : 'password'"
                                                @click:append-inner="showPassword = !showPassword" />
                                            <div class="text-caption text-grey">
                                                Use 8 or more characters with a mix of letters, numbers & symbols
                                            </div>

                                            <v-text-field v-model="formData.passwordConfirm" label="Confirm Password"
                                                :rules="[
                                                    v => !!v || 'Please confirm your password',
                                                    v => v === formData.password || 'Passwords do not match'
                                                ]" placeholder="Confirm password" variant="outlined" density="comfortable"
                                                :type="showPassword ? 'text' : 'password'" />

                                            <!-- Email with verification -->
                                            <div class="d-flex">
                                                <v-text-field v-model="formData.email" label="Email" :rules="emailRules"
                                                    placeholder="Enter email address" variant="outlined"
                                                    density="comfortable" class="flex-grow-1 mr-2" />
                                                <v-btn color="primary" :loading="verifyingEmail"
                                                    :disabled="!formData.email || verifyingEmail"
                                                    @click="sendEmailVerification">
                                                    Get Code
                                                </v-btn>
                                            </div>

                                            <!-- Email verification code input (conditionally shown) -->
                                            <v-expand-transition>
                                                <div v-if="showEmailVerification">
                                                    <div class="d-flex">
                                                        <v-text-field v-model="emailVerificationCode"
                                                            label="Verification Code" placeholder="Enter 6-digit code"
                                                            variant="outlined" density="comfortable"
                                                            class="flex-grow-1 mr-2" maxlength="6" />
                                                        <v-btn color="primary" :loading="verifyingEmailCode"
                                                            @click="verifyEmailCode">
                                                            Verify
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>

                                            <!-- Phone with verification -->
                                            <div class="d-flex">
                                                <v-text-field v-model="formData.phone" label="Phone Number"
                                                    :rules="phoneRules" placeholder="Enter phone number"
                                                    variant="outlined" density="comfortable" class="flex-grow-1 mr-2"
                                                    prepend-inner-icon="mdi-flag" :prefix="+1" />
                                                <v-btn color="primary" :loading="verifyingPhone"
                                                    :disabled="!formData.phone || verifyingPhone"
                                                    @click="sendPhoneVerification">
                                                    Get Code
                                                </v-btn>
                                            </div>

                                            <!-- Phone verification code input (conditionally shown) -->
                                            <v-expand-transition>
                                                <div v-if="showPhoneVerification">
                                                    <div class="d-flex">
                                                        <v-text-field v-model="phoneVerificationCode"
                                                            label="Verification Code" placeholder="Enter 6-digit code"
                                                            variant="outlined" density="comfortable"
                                                            class="flex-grow-1 mr-2" maxlength="6" />
                                                        <v-btn color="primary" :loading="verifyingPhoneCode"
                                                            @click="verifyPhoneCode">
                                                            Verify
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>

                                            <v-checkbox v-model="formData.consent"
                                                label="For the above presented mean of contact email, phone and address"
                                                :rules="[v => !!v || 'You must agree to proceed']" />
                                        </v-form>
                                    </v-card-text>
                                </v-card>
                            </v-window-item>

                            <!-- Step 2: Account Type -->
                            <v-window-item value="2">
                                <v-card flat>
                                    <v-card-title class="text-h4 font-weight-bold">
                                        What are your primary goals with Intracard?
                                    </v-card-title>
                                    <v-card-subtitle class="text-subtitle-1 mt-2">
                                        Help us tailor your experience
                                    </v-card-subtitle>

                                    <v-card-text>
                                        <v-row>
                                            <v-col cols="12" md="6">
                                                <v-card :color="formData.accountType === 'rent' ? 'primary' : ''"
                                                    @click="formData.accountType = 'rent'" class="pa-4 cursor-pointer"
                                                    :class="{ 'text-white': formData.accountType === 'rent' }">
                                                    <v-icon size="32" class="mb-2">mdi-home</v-icon>
                                                    <div class="text-h6">Rent</div>
                                                    <div class="text-subtitle-2">
                                                        Pay my rent with a credit or debit card.
                                                    </div>
                                                </v-card>
                                            </v-col>

                                            <v-col cols="12" md="6">
                                                <v-card :color="formData.accountType === 'mortgage' ? 'primary' : ''"
                                                    @click="formData.accountType = 'mortgage'"
                                                    class="pa-4 cursor-pointer"
                                                    :class="{ 'text-white': formData.accountType === 'mortgage' }">
                                                    <v-icon size="32" class="mb-2">mdi-bank</v-icon>
                                                    <div class="text-h6">Mortgage</div>
                                                    <div class="text-subtitle-2">
                                                        Pay my mortgage with a credit or debit card.
                                                    </div>
                                                </v-card>
                                            </v-col>
                                        </v-row>
                                    </v-card-text>
                                </v-card>
                            </v-window-item>

                            <!-- Additional steps would go here -->

                        </v-window>

                        <!-- Navigation Buttons -->
                        <v-row class="mt-4">
                            <v-col class="d-flex">
                                <v-btn v-if="currentStep > 1" color="secondary" variant="outlined"
                                    @click="currentStep--">
                                    Previous
                                </v-btn>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" @click="handleNextStep" :disabled="!canProceed">
                                    {{ currentStep === steps.length ? 'Submit' : 'Continue' }}
                                </v-btn>
                            </v-col>
                        </v-row>

                        <!-- Login Link -->
                        <div class="text-center mt-4">
                            Already have an account?
                            <a href="/login" class="text-primary font-weight-bold text-decoration-none">
                                Login here
                            </a>
                        </div>
                    </v-col>
                </v-row>
            </v-container>

        <!-- Toast Messages -->
        <v-snackbar v-model="showToast" :color="toastColor" :timeout="3000">
            {{ toastMessage }}
            <template v-slot:actions>
                <v-btn color="white" variant="text" @click="showToast = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </v-app>
</template>

<script>
import { useAuthStore } from "@/stores/authStore";
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from 'axios'

export default {
    data() {
        return {
            currentStep: 1,
            defaultStep: 1,
            showPassword: false,
            showEmailVerification: false,
            showPhoneVerification: false,
            emailVerificationCode: '',
            phoneVerificationCode: '',
            verifyingEmail: false,
            verifyingPhone: false,
            verifyingEmailCode: false,
            verifyingPhoneCode: false,
            showToast: false,
            toastMessage: '',
            toastColor: 'success',
            isEmailVerified: false,
            isPhoneVerified: false,

            // Form data
            formData: {
                firstName: '',
                lastName: '',
                middleName: '',
                email: '',
                phone: '',
                password: '',
                passwordConfirm: '',
                consent: false,
                accountType: null
            },

            // Steps configuration
            steps: [
                {
                    title: 'Personal Details',
                    subtitle: 'Provide with us your details',
                    icon: 'mdi-account'
                },
                {
                    title: 'Account Type',
                    subtitle: 'Select your account type',
                    icon: 'mdi-file-document'
                },
                {
                    title: 'Address',
                    subtitle: 'Setup your rental/mortgage address',
                    icon: 'mdi-map-marker'
                },
                {
                    title: 'Payment Details',
                    subtitle: 'Setup your payment details',
                    icon: 'mdi-cash'
                },
                {
                    title: 'Verification',
                    subtitle: 'Verify personal information',
                    icon: 'mdi-shield-check'
                }
            ],

            // Validation rules
            rules: {
                password: [
                    v => !!v || 'Password is required',
                    v => v.length >= 8 || 'Password must be at least 8 characters',
                    v => /[A-Z]/.test(v) || 'Must contain uppercase letter',
                    v => /[0-9]/.test(v) || 'Must contain number',
                    v => /[^A-Za-z0-9]/.test(v) || 'Must contain symbol'
                ],
                email: [
                    v => !!v || 'Email is required',
                    v => /.+@.+\..+/.test(v) || 'Email must be valid'
                ],
                phone: [
                    v => !!v || 'Phone number is required',
                    v => /^\d{10}$/.test(v) || 'Must be 10 digits'
                ]
            }
        }
    },
    
    created() {
        this.currentStep = this.defaultStep || 1;
    },
    
    mounted() {
        this.currentStep = 1;
    },

    watch: {
    currentStep: {
        immediate: true,
        handler(val) {
            console.log('Current step changed to:', val)
        }
    }
},

    computed: {
        canProceed() {
            switch (this.currentStep) {
                case 1:
                    return this.isPersonalDetailsValid
                case 2:
                    return !!this.formData.accountType
                // Add other step validations
                default:
                    return true
            }
        },

        isPersonalDetailsValid() {
            return (
                this.formData.firstName &&
                this.formData.lastName &&
                this.formData.email &&
                this.formData.phone &&
                this.formData.password &&
                this.formData.password === this.formData.passwordConfirm &&
                this.formData.consent &&
                this.isEmailVerified &&
                this.isPhoneVerified
            )
        }
    },
    props: {
        defaultStep: {
            type: Number,
            default: 1
        }
    },

    beforeMount() {
    console.log('Default step:', this.defaultStep);
    this.currentStep = this.defaultStep;
    },

    methods: {
        async sendEmailVerification() {
            this.verifyingEmail = true
            try {
                const response = await axios.post('/api/verify-email', {
                    email: this.formData.email
                })
                this.showEmailVerification = true
                this.showToastMessage('Verification code sent to your email')
            } catch (error) {
                this.showToastMessage(error.response?.data?.message || 'Failed to send verification code', 'error')
            } finally {
                this.verifyingEmail = false
            }
        },

        async verifyEmailCode() {
            this.verifyingEmailCode = true
            try {
                const response = await axios.post('/api/verify-email-code', {
                    email: this.formData.email,
                    code: this.emailVerificationCode
                })
                this.isEmailVerified = true
                this.showEmailVerification = false
                this.showToastMessage('Email verified successfully')
            } catch (error) {
                this.showToastMessage(error.response?.data?.message || 'Invalid verification code', 'error')
            } finally {
                this.verifyingEmailCode = false
            }
        },

        async sendPhoneVerification() {
            this.verifyingPhone = true
            try {
                const response = await axios.post('/api/verify-phone', {
                    phone: this.formData.phone
                })
                this.showPhoneVerification = true
                this.showToastMessage('Verification code sent to your phone')
            } catch (error) {
                this.showToastMessage(error.response?.data?.message || 'Failed to send verification code', 'error')
            } finally {
                this.verifyingPhone = false
            }
        },

        async verifyPhoneCode() {
            this.verifyingPhoneCode = true
            try {
                const response = await axios.post('/api/verify-phone-code', {
                    phone: this.formData.phone,
                    code: this.phoneVerificationCode
                })
                this.isPhoneVerified = true
                this.showPhoneVerification = false
                this.showToastMessage('Phone verified successfully')
            } catch (error) {
                this.showToastMessage(error.response?.data?.message || 'Invalid verification code', 'error')
            } finally {
                this.verifyingPhoneCode = false
            }
        },

        showToastMessage(message, color = 'success') {
            this.toastMessage = message
            this.toastColor = color
            this.showToast = true
        },

        async handleNextStep() {
            if (this.currentStep < this.steps.length) {
                if (this.canProceed) {
                    this.currentStep++
                }
            } else {
                await this.submitForm()
            }
        },

        async submitForm() {
            try {
                const response = await axios.post('/api/register', this.formData)
                this.showToastMessage('Registration successful')
                window.location.href = '/login'
            } catch (error) {
                this.showToastMessage(error.response?.data?.message || 'Registration failed', 'error')
            }
        }
    }
}
</script>