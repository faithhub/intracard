<template>

    <!-- Loading state with centered loader -->
    <v-container v-if="isLoading" class="fill-height d-flex align-center justify-center">
        <v-row justify="center" align="center">
            <v-col cols="12" class="text-center">
                <v-progress-circular indeterminate color="purple-darken-2" size="64" width="4"></v-progress-circular>
            </v-col>
        </v-row>
    </v-container>


    <v-container v-else class="fill-height">

        <!-- Show error state if invitation is invalid -->
        <v-row v-if="!isValid" align="center" justify="center">
            <v-col cols="12" sm="8" md="6">
                <v-card class="text-center pa-6">
                    <v-icon size="64" :color="errorIconColor" class="mb-4">
                        {{ errorIcon }}
                    </v-icon>
                    <h2 class="text-h5 mb-4">{{ errorMessage }}</h2>
                    <p class="mb-6">{{ errorDescription }}</p>
                    <router-link to="/auth/sign-in" class="home-button">Return to Login</router-link>
                </v-card>
            </v-col>
        </v-row>


        <!-- Show registration form if invitation is valid -->
        <v-row v-else align="center" justify="center">
            <v-col cols="12" sm="10" md="10" lg="8">
                <v-card class="elevation-12">
                    <!-- Header with Logo -->
                    <div class="text-center pb-5" style="background-color: #7b1fa2 !important;">
                        <router-link to="/" @click.prevent>
                            <img alt="Logo" src="@assets/logos/intracard-small.png" class="h-65px mb-2 mt-2" />
                        </router-link>
                        <v-toolbar-title class="text-white">Complete Your Registration</v-toolbar-title>
                    </div>

                    <!-- Team and Payment Info Section -->
                    <v-card-text>
                        <v-alert type="info" variant="tonal" color="purple-lighten-4" class="mb-4 invitation-details"
                            border>
                            <h3 class="text-h6 mb-2 font-weight-bold primary-text">Team: {{ invitation?.team_name }}
                            </h3>
                            <v-divider class="my-2"></v-divider>
                            <div class="mt-2">
                                <div class="label-text mb-1">Property Address:</div>
                                <p class="content-text mb-2">{{ invitation?.property_address }}</p>
                            </div>
                            <v-divider class="my-2"></v-divider>
                            <div class="mt-2">
                                <div class="d-flex justify-space-between align-center mb-1">
                                    <span class="label-text">Total Amount for {{ capitalizeFirstLetter(invitation?.role)
                                        }}:</span>
                                    <span class="content-text">{{ formatToCAD(invitation?.total_amount) }}</span>
                                </div>
                                <div class="d-flex justify-space-between align-center mb-1">
                                    <span class="label-text">Your Percentage:</span>
                                    <span class="content-text">{{ invitation?.percentage }}%</span>
                                </div>
                                <div class="d-flex justify-space-between align-center">
                                    <span class="label-text">Your Monthly Payment:</span>
                                    <span class="content-text">{{ formatToCAD(invitation?.monthly_payment) }}</span>
                                </div>
                            </div>
                        </v-alert>

                        <v-form ref="form" @submit.prevent="register">
                            <!-- Name Fields -->
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-text-field v-model="form.firstName" :error-messages="validationErrors.firstName"
                                        label="First Name" outlined dense @input="clearError('firstName')"
                                        required></v-text-field>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field v-model="form.lastName" :error-messages="validationErrors.lastName"
                                        label="Last Name" outlined dense @input="clearError('lastName')"
                                        required></v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12" md="6">
                                    <!-- Email -->
                                    <v-text-field v-model="form.email" :error-messages="validationErrors.email"
                                        label="Email" outlined dense @input="clearError('email')"
                                        required></v-text-field></v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field v-model="form.middleName" label="Middle Name (Optional)" outlined
                                        dense></v-text-field></v-col>
                            </v-row>

                            <!-- Phone Number with Verification -->
                            <v-row align="center" class="mb-2 mt-2" no-gutters> <!-- Added no-gutters -->
                                <v-col cols="10" class="pe-2">
                                    <v-text-field v-model="form.phone" :error-messages="validationErrors.phone"
                                        label="Phone Number" :readonly="isPhoneVerified"
                                        :suffix="isPhoneVerified ? '✓ Verified' : ''" outlined dense
                                        @update:model-value="onPhoneInput" class="input-no-spacing">
                                        <template v-slot:prepend>
                                            <div class="d-flex align-center">
                                                <img src="@assets/logos/ca-flag.png" alt="Canadian flag" class="mr-1"
                                                    style="width: 20px; height: 15px;" />
                                                <span class="country-prefix">+1</span>
                                            </div>
                                        </template>
                                    </v-text-field>
                                </v-col>
                                <v-col cols="2" class="pl-1"> <!-- Changed pl-0 to pl-1 -->
                                    <v-btn color="purple-darken-2" :loading="isSendingCode"
                                        :disabled="!canVerifyPhone || cooldownTime > 0" @click="sendVerificationCode"
                                        width="120" height="40" class="ml-auto d-block">
                                        {{ isPhoneVerified ? '✓ Verified' : cooldownTime > 0 ? `${cooldownTime}s` : 'Get Code' }}
                                    </v-btn>
                                </v-col>
                            </v-row>

                            <!-- Verification Code Input -->
                            <v-row v-if="isCodeSent && !isPhoneVerified" align="center" class="mb-2">
                                <!-- Changed from mb-4 to mb-2 -->
                                <v-col cols="10" class="pt-0"> <!-- Added pt-0 to remove top padding -->
                                    <v-text-field v-model="verificationCode"
                                        :error-messages="validationErrors.verificationCode"
                                        label="Enter Verification Code" outlined dense maxlength="6"
                                        placeholder="Enter 6-digit code"></v-text-field>
                                </v-col>
                                <v-col cols="2" class="pl-0 pt-0"> <!-- Added pt-0 to remove top padding -->
                                    <v-btn color="success" :loading="isVerifyingCode" @click="verifyCode" width="120"
                                        height="40" class="ml-auto d-block"
                                        :disabled="!verificationCode || verificationCode.length !== 6">
                                        Verify
                                    </v-btn>
                                </v-col>
                            </v-row>


                            <!-- Password Fields -->
                            <v-text-field class="mb-3" v-model="form.password"
                                :error-messages="validationErrors.password" :type="showPassword ? 'text' : 'password'"
                                label="Password" outlined dense :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                @click:append="showPassword = !showPassword" @input="clearError('password')"
                                required></v-text-field>

                            <v-text-field v-model="form.password_confirmation"
                                :error-messages="validationErrors.password_confirmation"
                                :type="showPassword ? 'text' : 'password'" label="Confirm Password" outlined dense
                                @input="clearError('password_confirmation')" required></v-text-field>

                            <!-- Consent Checkbox -->
                            <v-checkbox v-model="form.consent" :error-messages="validationErrors.consent"
                                :label="`I agree to the terms and conditions and confirm that I will be responsible for my portion of the ${invitation?.role || 'rent/mortgage'} payment`"
                                @change="clearError('consent')" required></v-checkbox>
                        </v-form>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn variant="outlined" color="error" @click="showDeclineDialog = true"
                            :loading="isDeclining">
                            Decline
                        </v-btn>
                        <v-btn variant="flat" color="purple-darken-2" @click="register" :loading="isRegistering"
                            class="ml-4">
                            Complete Registration
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>

        <!-- Add this dialog component -->
        <v-dialog v-model="showDeclineDialog" max-width="400">
            <v-card>
                <v-card-title>Decline Invitation?</v-card-title>
                <v-card-text>
                    Are you sure you want to decline this invitation? This action cannot be undone.
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="grey-darken-1" variant="text" @click="showDeclineDialog = false">
                        Cancel
                    </v-btn>
                    <v-btn color="error" variant="text" :loading="isDeclining" @click="confirmDecline">
                        Yes, Decline
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </v-container>
</template>


<script>
import intracardLogo from '@assets/logos/intracard.png'  // Adjust path as needed
import { useToast } from "vue-toastification";
import axios from "axios";

export default {
    setup() {
        const toast = useToast();
        return {
            toast
        };
    },
    data() {
        return {
            showDeclineDialog: false,
            isDeclining: false,
            isLoading: true,
            isValid: false,
            errorMessage: '',
            invitation: null,
            errorDescription: '',
            status: '',
            logoSrc: intracardLogo,
            verificationCode: '',
            cooldownTime: 0,
            cooldownInterval: null,
            form: {
                firstName: '',
                middleName: '',
                lastName: '',
                email: '',
                phone: '',
                password: '',
                password_confirmation: '',
                consent: false,
            },
            token: null,
            attemptsRemaining: 5,
            validationErrors: {
                phone: [],
                verificationCode: []
            },
            showPassword: false,
            isRegistering: false,
            isDeclining: false,
            isSendingCode: false,
            isVerifyingCode: false,
            isCodeSent: false,
            isPhoneVerified: false,
            verificationCode: '',
            displayPhone: '',
            rules: {
                required: v => !!v || 'This field is required',
                email: v => /.+@.+\..+/.test(v) || 'Enter a valid email address',
                min: v => v.length >= 8 || 'Password must be at least 8 characters',
                passwordMatch: v => v === this.form.password || 'Passwords must match',
                phone: v => /^\+?[\d\s-]{10,}$/.test(v) || 'Enter a valid phone number',
                consent: v => v || 'You must agree to the terms'
            }
        }
    },
    computed: {
        isPhoneValid() {
            return this.rules.phone(this.form.phone) === true;
        },
        isFormValid() {
            return this.valid && this.isPhoneVerified && this.form.consent;
        },
        canVerifyPhone() {
            const phoneDigits = this.form.phone.replace(/\D/g, '');
            return phoneDigits.length === 10 && !this.isPhoneVerified;
        },
        errorIcon() {
            switch (this.status) {
                case 'declined':
                    return 'mdi-close-circle';
                case 'accepted':
                    return 'mdi-check-circle';
                case 'expired':
                    return 'mdi-clock-alert';
                default:
                    return 'mdi-alert-circle';
            }
        },
        errorIconColor() {
            switch (this.status) {
                case 'declined':
                    return 'error';
                case 'accepted':
                    return 'success';
                case 'expired':
                    return 'warning';
                default:
                    return 'error';
            }
        },

        errorDescription() {
            switch (this.status) {
                case 'declined':
                    return 'This invitation has been declined. Please contact the team administrator for a new invitation if needed.';
                case 'accepted':
                    return 'This invitation has already been used. Please login to your account.';
                case 'expired':
                    return 'This invitation has expired. Please contact the team administrator for a new invitation.';
                default:
                    return 'Please contact the team administrator for assistance.';
            }
        },
    },
    created() {
        this.token = this.$route.params.token;
        if (!this.token) {
            this.handleInvalidInvitation('Invalid invitation link');
            return;
        }
        this.checkInvitation();
    },
    methods: {
        async register() {
    // First validate all fields
    if (!this.validateForm()) {
        this.toast.error('Please fill in all required fields');
        return;
    }

    // Validate phone verification
    if (!this.isPhoneVerified) {
        this.toast.error('Please verify your phone number');
        this.validationErrors.phone = ['Phone number must be verified'];
        return;
    }

    this.isRegistering = true;
    try {
        const formData = {
            first_name: this.form.firstName.trim(),
            middle_name: this.form.middleName?.trim() || null,
            last_name: this.form.lastName.trim(),
            email: this.form.email.trim(),
            phone: this.form.phone.replace(/\D/g, ''), // Clean phone number
            password: this.form.password,
            password_confirmation: this.form.password_confirmation,
            terms_accepted: this.form.consent
        };

        const response = await axios.post(
            `/api/team/invitation/${this.token}/accept`, 
            formData
        );

        this.toast.success('Registration completed! Please check your email for verification instructions.');
        
        // Redirect to login page with a message
        setTimeout(() => {
            this.$router.push({ name: 'Login' });
        }, 2000);

    } catch (error) {
        if (error.response?.status === 422) {
            // Validation errors
            this.validationErrors = error.response.data.errors;
            this.toast.error('Please correct the errors in the form');
        } else if (error.response?.status === 404) {
            // Invalid token
            this.toast.error('Invalid or expired invitation');
            setTimeout(() => this.$router.push({ name: 'Login' }), 3000);
        } else if (error.response?.status === 409) {
            // Conflict (e.g., email already exists)
            this.validationErrors.email = [error.response.data.message];
            this.toast.error('Email address is already registered');
        } else if (error.response?.data?.message) {
            this.toast.error(error.response.data.message);
        } else {
            this.toast.error('An unexpected error occurred. Please try again.');
            console.error('Registration error:', error);
        }
    } finally {
        this.isRegistering = false;
    }
},
        async checkInvitation() {
            try {
                const response = await axios.get(`/api/team/invitation/${this.token}`);

                if (!response.data.valid) {
                    this.handleInvalidInvitation(
                        response.data.message,
                        response.data.status
                    );
                    return;
                }

                this.isValid = true;
                this.invitation = response.data.data;
                this.form.email = this.invitation.email;

                // Split name if provided
                if (this.invitation.name) {
                    const names = this.invitation.name.split(' ');
                    this.form.firstName = names[0] || '';
                    this.form.lastName = names[names.length - 1] || '';
                    if (names.length > 2) {
                        this.form.middleName = names.slice(1, -1).join(' ');
                    }
                }
            } catch (error) {
                const message = error.response?.data?.message ||
                    'Error loading invitation details';
                const status = error.response?.data?.status || '';
                this.handleInvalidInvitation(message, status);
            } finally {
                this.isLoading = false;
            }
        },
        handleInvalidInvitation(message, status = '') {
            this.isValid = false;
            this.errorMessage = message;
            this.status = status;
            this.isLoading = false;

            // Only auto-redirect for certain statuses
            if (status === 'accepted') {
                setTimeout(() => {
                    this.$router.push({ name: 'login' });
                }, 3000);
            }
        },
        onPhoneInput(value) {
            // Clear any existing error
            this.clearError('phone');

            // Remove any non-digit characters
            let cleaned = (value || '').replace(/\D/g, '');

            // Limit to 10 digits
            if (cleaned.length > 10) {
                cleaned = cleaned.slice(0, 10);
            }

            // Format the number with spaces
            let formatted = cleaned;
            if (cleaned.length > 6) {
                formatted = `${cleaned.slice(0, 3)} ${cleaned.slice(3, 6)} ${cleaned.slice(6)}`;
            } else if (cleaned.length > 3) {
                formatted = `${cleaned.slice(0, 3)} ${cleaned.slice(3)}`;
            }

            // Update the form value
            this.form.phone = formatted;
        },
        clearError(field) {
            if (this.validationErrors[field]) {
                delete this.validationErrors[field];
            }
        },
        async sendVerificationCode() {
            this.isSendingCode = true;
            try {
                const cleanPhone = this.form.phone.replace(/\D/g, '');

                const response = await axios.post('/api/phone/send-code', {
                    phone: cleanPhone
                });
                this.isCodeSent = true;
                this.toast.success('Verification code sent');
                this.cooldownTime = 60;
                this.startCooldownTimer();
                // If there's additional data in the response
                if (response.data.data) {
                    this.attemptsRemaining = response.data.data.attempts_remaining;
                }
            } catch (error) {
                if (error.response?.data?.errors) {
                    // Handle validation errors
                    const errorMessage = Object.values(error.response.data.errors)
                        .flat()
                        .join('\n');
                    this.toast.error(errorMessage);

                    // Update the validation errors in the form
                    if (error.response.data.errors.phone) {
                        this.validationErrors.phone = error.response.data.errors.phone;
                    }
                } else if (error.response?.data?.message) {
                    // Handle other API errors with messages
                    this.toast.error(error.response.data.message);
                } else {
                    // Handle unexpected errors
                    this.toast.error('Error sending verification code');
                }
            } finally {
                this.isSendingCode = false;
            }
        },
        async verifyCode() {
            this.isVerifyingCode = true;
            try {
                const cleanPhone = this.form.phone.replace(/\D/g, '');

                await axios.post('/api/phone/verify-code', {
                    phone: cleanPhone,
                    code: this.verificationCode
                });
                this.isPhoneVerified = true;
                this.toast.success('Phone number verified');
                this.validationErrors.phone = []; // Clear any existing errors

                // Optionally clear the verification code input
                this.verificationCode = '';
                this.isCodeSent = false;
            } catch (error) {
                if (error.response?.data?.errors) {
                    // Handle validation errors
                    const errorMessage = Object.values(error.response.data.errors)
                        .flat()
                        .join('\n');
                    this.toast.error(errorMessage);

                    // Update specific validation errors
                    if (error.response.data.errors.code) {
                        this.validationErrors.verificationCode = error.response.data.errors.code;
                    }
                    if (error.response.data.errors.phone) {
                        this.validationErrors.phone = error.response.data.errors.phone;
                    }
                } else if (error.response?.data?.message) {
                    // Handle other API errors with messages
                    this.toast.error(error.response.data.message);
                } else {
                    // Handle unexpected errors
                    this.toast.error('Invalid verification code');
                }
            } finally {
                this.isVerifyingCode = false;
            }
        },
        startCooldownTimer() {
            // Clear any existing interval
            if (this.cooldownInterval) {
                clearInterval(this.cooldownInterval);
            }

            this.cooldownInterval = setInterval(() => {
                if (this.cooldownTime > 0) {
                    this.cooldownTime--;
                } else {
                    clearInterval(this.cooldownInterval);
                }
            }, 1000);
        },
        capitalizeFirstLetter(string) {
            if (!string) return ''; // Handle null/undefined
            if (typeof string !== 'string') string = String(string); // Convert to string if not already
            return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
        },
        formatToCAD(value) {
            return new Intl.NumberFormat('en-CA', {
                style: 'currency',
                currency: 'CAD',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(value);
        },
        validateForm() {
            this.validationErrors = {};
            if (!this.form.firstName) this.validationErrors.firstName = ['First name is required'];
            if (!this.form.lastName) this.validationErrors.lastName = ['Last name is required'];
            if (!this.form.email) this.validationErrors.email = ['Email is required'];
            if (!this.form.phone) this.validationErrors.phone = ['Phone number is required'];
            // if (!this.isPhoneVerified) this.validationErrors.phone = ['Phone number must be verified'];
            if (!this.form.password) this.validationErrors.password = ['Password is required'];
            if (this.form.password.length < 8) this.validationErrors.password = ['Password must be at least 8 characters'];
            if (this.form.password !== this.form.password_confirmation) {
                this.validationErrors.password_confirmation = ['Passwords do not match'];
            }
            if (!this.form.consent) this.validationErrors.consent = ['You must agree to the terms'];

            return Object.keys(this.validationErrors).length === 0;
        },
        async confirmDecline() {
            this.isDeclining = true;
            try {
                const response = await axios.post(`/api/team/invitation/${this.token}/decline`);
                this.toast.success(response.data.message || 'Invitation declined successfully');

                // Short delay before redirect to allow toast to be seen
                setTimeout(() => {
                    this.$router.push({
                        name: 'Login'
                    });
                }, 1500);
            } catch (error) {
                if (error.response) {
                    // Handle specific error status codes
                    switch (error.response.status) {
                        case 404:
                            this.toast.error('Invitation not found or already processed');
                            break;
                        case 400:
                            this.toast.error(error.response.data.message || 'Invalid invitation status');
                            break;
                        case 403:
                            this.toast.error('You do not have permission to decline this invitation');
                            break;
                        default:
                            this.toast.error(error.response.data.message || 'Error declining invitation');
                    }
                } else if (error.request) {
                    // Network error
                    this.toast.error('Network error. Please check your connection and try again.');
                } else {
                    // Other errors
                    this.toast.error('An unexpected error occurred');
                    console.error('Decline error:', error);
                }
            } finally {
                this.isDeclining = false;
                this.showDeclineDialog = false;
            }
        }
    },
    // Clean up the interval when component is destroyed
    beforeDestroy() {
        if (this.cooldownInterval) {
            clearInterval(this.cooldownInterval);
        }
    }
}
</script>

<style scoped>
.invitation-details {
    background-color: #f3e5f5 !important;
    /* Lighter purple background */
}

.primary-text {
    color: #6a1b9a !important;
    /* Dark purple */
    font-size: 1.25rem !important;
    letter-spacing: 0.0125em;
}

.label-text {
    font-weight: 600;
    color: #4a148c;
    /* Darker purple for labels */
    font-size: 0.95rem;
}

.content-text {
    font-weight: 500;
    color: #000000DE;
    /* Dark text for better readability */
    font-size: 1rem;
}

:deep(.v-alert) {
    border-color: #9c27b0 !important;
    /* Purple border */
}

.v-btn {
    text-transform: none !important;
}

.country-prefix {
    color: rgba(0, 0, 0, 0.87);
    font-weight: 500;
    margin-left: 4px;
    margin-right: 8px;
}

.v-text-field :deep(.v-field__prepend-inner) {
    margin-right: 8px;
}

.v-container.fill-height {
    min-height: 100vh;
}

.input-no-spacing {
    margin: 0;
    padding: 0;
}

.home-button {
    display: block;
    width: 100%;
    padding: 12px 16px;
    background-color: #392F75;
    color: white;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    margin-top: 32px;
    transition: background-color 0.2s ease;
}

.home-button:hover {
    background-color: #2a2357;
}
</style>