<template>
    <div>
    <ContentSkeleton v-if="isSkeletonLoading" />
    <v-row v-else>

        <!-- Welcome Text and Setup Bill Button -->
        <v-col cols="12">
            <v-row>
                <!-- Calendar Card -->
                <v-col cols="12" md="12">
                    <v-card class="calendar-card pa-4 rounded-3" elevation="10">
                        <!-- <v-card-title class="text-h3 font-weight-bold mb-4 lt-sp">My Cards</v-card-title> -->

                        <!-- Credit Card and Transactions Section -->
                        <section class="credit-card-section">
                            <div class="py-5 p-5">
                                <!-- Credit Cards -->
                                <div class="d-flex justify-space-between align-items-center mb-4">
                                    <h3 class="section-title">My Credit Cards</h3>
                                </div>
                                <div class="card-body mb-8 mb-xl-12">
                                    <div v-if="isLoadingCards">Loading cards...</div>
                                    <div v-else>
                                        <div class="row gx-9 gy-6">
                                            <div v-for="card in cards" :key="card.id" class="col-xl-6"
                                                data-kt-billing-element="card" :data-card-id="card.id">
                                                <!--begin::Card-->
                                                <div
                                                    class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6">
                                                    <!--begin::Info-->
                                                    <div class="d-flex flex-column py-2">
                                                        <!--begin::Owner-->
                                                        <div class="d-flex align-items-center fs-4 fw-bold mb-5">
                                                            {{ card.name }}
                                                            <span class="badge badge-light-success fs-7 ms-2"
                                                                v-if="card.is_primary">
                                                                Primary
                                                            </span>
                                                        </div>
                                                        <!--end::Owner-->
                                                        <!--begin::Wrapper-->
                                                        <div class="d-flex align-items-center">
                                                            <!--begin::Icon-->
                                                            <img src="@assets/cards/mastercard.png" alt="Card Icon"
                                                                class="me-4" style="width:50px">
                                                            <!--end::Icon-->
                                                            <!--begin::Details-->
                                                            <div>
                                                                <div class="fs-4 fw-bold">**** **** **** {{
                                                                    card.number.slice(-4) }}</div>
                                                                <div class="fs-6 fw-semibold text-gray-500">
                                                                    Card expires at {{ card.expiryMonth }}/{{
                                                                        card.expiryYear }}
                                                                </div>
                                                            </div>
                                                            <!--end::Details-->
                                                        </div>
                                                        <!--end::Wrapper-->
                                                    </div>
                                                    <!--end::Info-->
                                                    <!--begin::Actions-->
                                                    <div class="d-flex align-items-center py-2">
                                                        <button
                                                            class="btn btn-sm btn-light btn-active-light-primary me-3"
                                                            @click="confirmDeleteCard(card.id)">
                                                            Delete
                                                        </button>
                                                        <!-- <button class="btn btn-sm btn-light btn-active-light-primary"
                                                            @click="editCardModal(card.id)">
                                                            Edit
                                                        </button> -->
                                                    </div>
                                                    <!--end::Actions-->
                                                </div>
                                                <!--end::Card-->
                                            </div>

                                            <div class="col-xl-6">
                                                <!--begin::Notice-->
                                                <div class="notice d-flex bg-light-primary border-primary border border-dashed h-lg-100 p-6"
                                                    style="background-color: #a000f900 !important">
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                                                        <!--begin::Content-->
                                                        <div class="mb-3 mb-md-0 fw-semibold">
                                                            <h4 class="text-gray-900 fw-bold">Important Note!</h4>
                                                            <div class="fs-6 text-gray-700 pe-7">Please carefully read
                                                                <a href="#" class="fw-bold me-1">Product Terms</a>
                                                                adding
                                                                <br> your new payment card
                                                            </div>
                                                        </div>
                                                        <!--end::Content-->
                                                        <button
                                                            class="btn btn-primary px-6 align-self-center text-nowrap"
                                                            @click="toggleAddCardModal">Add Card</button>
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </div>
                                                <!--end::Notice-->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Transactions -->
                                <div class="card mb-5 mb-xl-12">
                                    <!--begin::Card header-->
                                    <div class="card-header card-header-stretch pb-0">
                                        <!--begin::Title-->
                                        <div class="card-title">
                                            <h3 class="m-0">Transactions</h3>
                                        </div>
                                    </div>

                                    <div class="card-body pt-0">
                                        <div id="kt_customers_table_wrapper"
                                            class="dt-container dt-bootstrap5 dt-empty-footer">
                                            <div id="" class="table-responsive">
                                                <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable"
                                                    id="kt_customers_table" style="width: 100%;">
                                                    <colgroup>
                                                        <col data-dt-column="0" style="width: 36.3906px;">
                                                        <col data-dt-column="1" style="width: 132.766px;">
                                                        <col data-dt-column="2" style="width: 166.844px;">
                                                        <col data-dt-column="3" style="width: 191.25px;">
                                                        <col data-dt-column="4" style="width: 140.078px;">
                                                        <col data-dt-column="5" style="width: 177.438px;">
                                                        <col data-dt-column="6" style="width: 111.734px;">
                                                    </colgroup>
                                                    <thead>
                                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0"
                                                            role="row">
                                                            <th class="w-10px pe-2 dt-orderable-none" data-dt-column="0"
                                                                rowspan="1" colspan="1" aria-label="">
                                                                <span class="dt-column-title">
                                                                    <div
                                                                        class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            data-kt-check="true"
                                                                            data-kt-check-target="#kt_customers_table .form-check-input"
                                                                            value="1">
                                                                    </div>
                                                                </span>
                                                                <span class="dt-column-order"></span>
                                                            </th>
                                                            <th class="min-w-125px dt-orderable-asc dt-orderable-desc"
                                                                data-dt-column="1" rowspan="1" colspan="1"
                                                                aria-label="Customer Name: Activate to sort"
                                                                tabindex="0"><span class="dt-column-title"
                                                                    role="button">Transaction ID</span><span
                                                                    class="dt-column-order"></span></th>
                                                            <th class="min-w-125px dt-orderable-asc dt-orderable-desc"
                                                                data-dt-column="2" rowspan="1" colspan="1"
                                                                aria-label="Email: Activate to sort" tabindex="0"><span
                                                                    class="dt-column-title"
                                                                    role="button">Amount</span><span
                                                                    class="dt-column-order"></span></th>
                                                            <th class="min-w-125px dt-orderable-asc dt-orderable-desc"
                                                                data-dt-column="4" rowspan="1" colspan="1"
                                                                aria-label="Payment Method: Activate to sort"
                                                                tabindex="0"><span class="dt-column-title" role="button"
                                                                    id="tableCardType">Credit Card</span><span
                                                                    class="dt-column-order"></span></th>
                                                            <th class="min-w-125px dt-orderable-asc dt-orderable-desc"
                                                                data-dt-column="5" rowspan="1" colspan="1"
                                                                aria-label="Created Date: Activate to sort"
                                                                tabindex="0"><span class="dt-column-title"
                                                                    role="button">Date</span><span
                                                                    class="dt-column-order"></span></th>
                                                            <th class="text-end min-w-70px dt-orderable-none"
                                                                data-dt-column="6" rowspan="1" colspan="1"
                                                                aria-label="Actions"><span
                                                                    class="dt-column-title">Actions</span><span
                                                                    class="dt-column-order"></span></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="fw-semibold text-gray-600">
                                                        <tr>
                                                            <td>
                                                                <div
                                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="1">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                #DFSGDFHGGFDJHGF
                                                            </td>
                                                            <td>
                                                                $500.00
                                                            </td>
                                                            <td data-filter="visa">
                                                                <img src="https://c.webcyborg.com.ng/public/assets/cards/mastercard.png"
                                                                    class="w-35px me-3" alt="">
                                                                **** 3215
                                                            </td>
                                                            <td data-order="2020-08-18T15:34:00+01:00">
                                                                18 Aug 2020, 3:34 pm
                                                            </td>
                                                            <td class="text-end">
                                                                <!-- <a href="#" class="menu-link px-3">View</a> -->
                                                                <button
                                                                    class="btn btn-sm btn-light btn-active-light-primary px-3"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#kt_modal_new_card">View</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </v-card>
                </v-col>

            </v-row>
        </v-col>

    <!-- Delete Confirmation Dialog -->
    <v-dialog v-model="deleteDialog" max-width="500px">
        <v-card>
            <v-card-title class="text-h5 bg-error text-white">
                Delete Card
            </v-card-title>

            <v-card-text class="pt-5">
                <p class="text-body-1 mb-4">Are you sure you want to delete this card? This action cannot be undone.</p>

                <v-form ref="deleteForm" @submit.prevent="handleDeleteCard">
                    <v-text-field v-model="deletePassword" :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="showPassword ? 'text' : 'password'" label="Enter your password"
                        :error-messages="deleteError" @click:append="showPassword = !showPassword"
                        @input="deleteError = ''" required></v-text-field>
                </v-form>
            </v-card-text>

            <v-card-actions class="pb-4 px-6">
                <v-spacer></v-spacer>
                <v-btn color="grey-darken-1" variant="outlined" @click="closeDeleteDialog" :disabled="isDeletingCard">
                    Cancel
                </v-btn>
                <v-btn color="error" class="ml-3" @click="handleDeleteCard" :loading="isDeletingCard">
                    Delete Card
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <!-- Modal Add-->
    <v-dialog v-model="isAddCardModalOpen" max-width="600px">
        <v-card>
            <!-- Header -->
            <v-card-title class="d-flex justify-space-between align-center">
                <span>Add New Card</span>
                <v-btn icon @click="toggleAddCardModal">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>

            <!-- Form Content -->
            <v-card-text>
                <v-form @submit.prevent="validateAndSubmitAddCard" ref="form">
                    <!-- Name on Card -->
                    <v-text-field v-model="newCard.name" label="Name On Card"
                        :rules="[rules.required, rules.nameValidation]" placeholder="Cardholder Name"
                        @input="clearErrorAndValidate('name', validateName)" hint="Specify a card holder's name"
                        persistent-hint required>
                        <template v-slot:append>
                            <v-tooltip location="top" text="Specify a card holder's name">
                                <template v-slot:activator="{ props }">
                                    <v-icon v-bind="props" color="grey">mdi-information</v-icon>
                                </template>
                            </v-tooltip>
                        </template>
                    </v-text-field>

                    <!-- Card Number -->
                    <v-text-field v-model="newCard.number" label="Card Number"
                        :rules="[rules.required, rules.cardNumberValidation]" placeholder="Enter card number"
                        @input="clearErrorAndValidate('number', validateCardNumber)" maxlength="19"
                        hint="Enter your 16-digit card number" persistent-hint required>
                        <template v-slot:append>
                            <v-tooltip location="top" text="Enter your 16-digit card number without spaces">
                                <template v-slot:activator="{ props }">
                                    <v-icon v-bind="props" color="grey">mdi-information</v-icon>
                                </template>
                            </v-tooltip>
                        </template>
                    </v-text-field>

                    <!-- Expiration Date and CVV -->
                    <v-row>
                        <v-col cols="12" md="8">
                            <v-row>
                                <v-col cols="6">
                                    <v-select v-model="newCard.expiryMonth" label="Month" :items="months"
                                        :rules="[rules.required]"
                                        @change="clearErrorAndValidate('expiry', validateExpiry)" required></v-select>
                                </v-col>
                                <v-col cols="6">
                                    <v-select v-model="newCard.expiryYear" label="Year" :items="years"
                                        :rules="[rules.required]"
                                        @change="clearErrorAndValidate('expiry', validateExpiry)" required></v-select>
                                </v-col>
                            </v-row>
                        </v-col>

                        <v-col cols="12" md="4">
                            <v-text-field v-model="newCard.cvv" label="CVV"
                                :rules="[rules.required, rules.cvvValidation]" placeholder="CVV"
                                @input="clearErrorAndValidate('cvv', validateCVV)" maxlength="4" required>
                                <template v-slot:append>
                                    <v-tooltip location="top" text="Enter a card CVV code">
                                        <template v-slot:activator="{ props }">
                                            <v-icon v-bind="props">mdi-credit-card</v-icon>
                                        </template>
                                    </v-tooltip>
                                </template>
                            </v-text-field>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <!-- Actions -->
            <v-card-actions class="justify-center pb-6 gap-4">
                <v-btn color="purple" @click="validateAndSubmitAddCard" :loading="isAddingCard">
                    Submit
                </v-btn>
                <v-btn color="error" variant="outlined" @click="toggleAddCardModal" :disabled="isAddingCard">
                    Cancel
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    </v-row>
    </div>
</template>

<script>
import {
    useToast
} from "vue-toastification";
import { useNotifications } from '@/stores/useNotifications';

import ContentSkeleton from '@/components/skeleton/cards.vue'
export default { 
    components: {
    ContentSkeleton
  },
    data() {
        const { unreadCount, refresh } = useNotifications();
        return {
            isSkeletonLoading: true,
            unreadCount,
            refreshNotifications: refresh,
            deleteDialog: false,
            deleteCardId: null,
            deletePassword: '',
            deleteError: '',
            showPassword: false,
            isAddingCard: false,    // For add card operation
            isDeletingCard: false,  // For delete card operation
            isLoadingCards: false,  // For fetching cards list
            isAddCardModalOpen: false,
            isFormValid: false,
            newCard: {
                name: "",
                number: "",
                expiryMonth: "",
                expiryYear: "",
                cvv: "",
            },
            isEditCardModalOpen: false,
            editCard: {
                name: "",
                number: "",
                expiryMonth: "",
                expiryYear: "",
                cvv: "",
            },
            editErrors: {}, // Errors specific to Edit Card Modal
            addErrors: {
                name: '',
                number: '',
                expiry: '',
                cvv: ''
            },
            months: Array.from({
                length: 12
            }, (_, i) => i + 1),
            years: Array.from({
                length: 10
            }, (_, i) => new Date().getFullYear() + i),
            errors: {},
            showDeleteModal: false,
            deleteCardId: null,
            deletePassword: "",
            deleteError: null,
            notificationPollingInterval: null,
            cards: [],
            loading: true,
            rules: {
                required: v => !!v || 'This field is required',
                nameValidation: v => (v && v.length >= 3) || 'Name must be at least 3 characters',
                //             cardNumberValidation: v => {
                //     const cleaned = v.replace(/\s+/g, '');
                //     if (cleaned.length !== 16) return 'Card number must be 16 digits';
                //     if (!/^\d+$/.test(cleaned)) return 'Card number can only contain digits';
                //     return true;
                // },
                cardNumberValidation: v => {
                    const cleanNumber = v.replace(/\s+/g, '');
                    return /^\d{16}$/.test(cleanNumber) || 'Invalid card number';
                },
                cvvValidation: v => {
                    if (!v) return 'CVV is required';
                    const cleaned = v.replace(/\D/g, '');
                    if (cleaned.length < 3 || cleaned.length > 4) return 'CVV must be 3 or 4 digits';
                    if (!/^\d+$/.test(cleaned)) return 'CVV can only contain digits';
                    return true;
                },
            },
        };
    },
    mounted() {
    },
    async created() { try {
    await this.fetchCards();
  } finally {
    this.isSkeletonLoading = false;
  }
    },
    watch: {
        deletePassword(newValue) {
            if (newValue) {
                this.deleteError = ''; // Clear error when user starts typing
            }
        }
    },
    methods: {
        // Function to open delete confirmation dialog
        confirmDeleteCard(cardId) {
            this.deleteCardId = cardId;
            this.deleteDialog = true;
            this.deletePassword = '';
            this.deleteError = '';
        },

        // Function to close delete dialog
        closeDeleteDialog() {
            this.deleteDialog = false;
            this.deleteCardId = null;
            this.deletePassword = '';
            this.deleteError = '';
        },
        // Function to handle card deletion
        async handleDeleteCard() {
            if (!this.deletePassword) {
                this.deleteError = 'Password is required';
                return;
            }

            this.isDeletingCard = true;
            try {
                const response = await axios.post(`/api/cards/${this.deleteCardId}/delete`, {
                    password: this.deletePassword
                });

                // Success case
                await this.fetchCards();
                useToast().success(response.data.message || 'Card deleted successfully');
                await this.refreshNotifications(); // Refresh notifications
                this.closeDeleteDialog();

            } catch (error) {
                // Handle specific status codes
                switch (error.response?.status) {
                    case 401:
                        this.deleteError = 'Incorrect password';
                        break;
                    case 404:
                        useToast().error('Card not found');
                        this.closeDeleteDialog();
                        await this.fetchCards(); // Refresh list as card might have been deleted
                        break;
                    case 403:
                        useToast().error('You do not have permission to delete this card');
                        break;
                    case 422:
                        this.deleteError = error.response.data.errors?.password?.[0] || 'Validation failed';
                        break;
                    default:
                        useToast().error(
                            error.response?.data?.message ||
                            'An error occurred while deleting the card'
                        );
                }
                console.error('Error deleting card:', error);
            } finally {
                this.isDeletingCard = false;
            }
        },
        async fetchCards() {
            this.isLoadingCards = true;
            try {
                const response = await axios.get('/api/cards');
                this.cards = Object.values(response.data);
            } catch (error) {
                console.error('Error fetching cards:', error);
                useToast().error('Failed to fetch cards');
            } finally {
                this.isLoadingCards = false;
            }
        },
        resetForm() {
            this.$refs.form?.reset();
            this.newCard = {
                name: '',
                number: '',
                expiryMonth: '',
                expiryYear: '',
                cvv: ''
            };
            this.addErrors = {
                name: '',
                number: '',
                expiry: '',
                cvv: ''
            };
        },
        clearErrorAndValidate(field, validationFn) {
            this.addErrors[field] = '';
            if (validationFn) validationFn();
        },

        toggleAddCardModal() {
            this.isAddCardModalOpen = !this.isAddCardModalOpen;
            if (!this.isAddCardModalOpen) {
                this.resetForm();
            }
        },
        validateName() {
            if (!this.newCard.name.trim()) {
                this.addErrors.name = "Card holder name is required.";
            }
            // Clear error if validation passes
            this.addErrors.name = "";
            return true;
        },
        validateCardNumber() {
            // First remove all non-digits to work with clean number
            // const cleaned = this.newCard.number.replace(/\D/g, "");

            // // Check if we have 16 digits before formatting
            // if (cleaned.length !== 16) {
            //     this.addErrors.number = "Card number must be 16 digits";
            //     return false;
            // }

            // // Format the number with spaces
            // this.newCard.number = cleaned.match(/.{1,4}/g).join(" ");

            this.newCard.number = this.newCard.number
                .replace(/\D/g, '') // Remove non-digits
                .replace(/(\d{4})(?=\d)/g, '$1 '); // Add space every 4 digits

            if (!/^\d{4} \d{4} \d{4} \d{4}$/.test(this.newCard.number)) {
                this.addErrors.number = 'Invalid card number format.';
            }

            // Clear error if we reach here
            this.addErrors.number = "";
            return true;
        },
        validateExpiry() {
            if (!this.newCard.expiryMonth || !this.newCard.expiryYear) {
                this.addErrors.expiry = "Expiration date is required.";
            }
            // Clear error if validation passes
            this.addErrors.expiry = "";
            return true;
        },
        validateCVV() {
            // First clean the input to only have digits
            this.newCard.cvv = this.newCard.cvv.replace(/\D/g, "");

            // Check length and set/clear error accordingly
            if (this.newCard.cvv.length < 3 || this.newCard.cvv.length > 4) {
                this.addErrors.cvv = "Invalid CVV";
                return false;
            }

            // Clear error if validation passes
            this.addErrors.cvv = "";
            return true;
        },
        async validateForm() {
            const isValid = await this.$refs.form.validate();
            this.isFormValid = isValid;
            return isValid;
        },
        async validateAndSubmitAddCard() {
            await this.$refs.form.resetValidation();
            const { valid } = await this.$refs.form.validate();

            if (!valid) {
                useToast().error('Please fix all form errors before submitting');
                return false;
            }

            const hasErrors = Object.values(this.addErrors).some(error => error !== '');
            if (hasErrors) {
                console.log(hasErrors, this.addErrors);

                useToast().error('Please fix all form errors before submitting');
                return false;
            }

            this.isAddingCard = true;
            try {
                const response = await axios.post('/api/cards', {
                    name: this.newCard.name,
                    number: this.newCard.number.replace(/\s+/g, ''),
                    expiryMonth: this.newCard.expiryMonth,
                    expiryYear: this.newCard.expiryYear,
                    cvv: this.newCard.cvv
                });

                if (response.status === 201) {
                    // Fetch fresh cards data
                    await this.fetchCards();
                    // Show success message and close modal
                    useToast().success(response.data.message || 'Card added successfully');
                    await this.refreshNotifications(); // Refresh notifications
                    this.toggleAddCardModal();
                }
            } catch (error) {
                if (error.response?.status === 422) {
                    const errors = error.response.data.errors;
                    Object.keys(errors).forEach(field => {
                        this.addErrors[field] = errors[field][0];
                    });
                    useToast().error('Please check the form for errors');
                } else {
                    useToast().error(
                        error.response?.data?.message ||
                        'An error occurred while adding the card. Please try again.'
                    );
                }
            } finally {
                this.isAddingCard = false;
            }
        },
        async fetchCardDetails(cardId) {
            try {
                const response = await axios.get(`/api/cards/${cardId}`);
                console.log(response);

                this.editCard = response.data;
            } catch (error) {
                console.error("Error fetching card details:", error);
            }
        },
        // Clear error for a field and run its validation
        clearErrorAndValidate(field, validateFn) {
            // Clear the error for the specific field
            delete this.editErrors[field];

            // Validate after DOM updates
            this.$nextTick(() => {
                validateFn();
            });
        },
    },
    beforeUnmount() {
    },
};
</script>

<style>
/* Credit Card Section */
.credit-card-section {
    /* padding: 50px 0; */
    /* background-color: #f9f9f9; */
}

.btn.btn-light {
    background-color: #cfe6dbed !important;
}

.section-title {
    font-size: 24px;
    color: #1d1d1d;
    margin-bottom: 20px;
}

.card-container {
    margin-bottom: 40px;
}

.credit-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
}

.card-info {
    color: #555;
}

.card-info h4 {
    font-size: 18px;
    margin-bottom: 5px;
}

.badge.primary {
    background-color: #0d6efd;
    color: #fff;
    padding: 3px 8px;
    font-size: 12px;
    border-radius: 5px;
    margin-left: 10px;
}

.card-actions .btn {
    margin-left: 10px;
    padding: 5px 15px;
    border: 1px solid #ddd;
    background: #fff;
    color: #333;
    border-radius: 5px;
    font-size: 14px;
}

.card-actions .btn.edit {
    border-color: #0d6efd;
    color: #0d6efd;
}

.card-actions .btn.delete {
    border-color: #d9534f;
    color: #d9534f;
}

.transactions-container {
    margin-top: 40px;
}

.transactions-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border: 1px solid #ddd;
}

.transactions-table th,
.transactions-table td {
    padding: 15px;
    border: 1px solid #ddd;
    text-align: left;
}

.transactions-table th {
    background: #f7f7f7;
    color: #333;
}

.transactions-table td img {
    height: 20px;
    margin-right: 10px;
}

.view-link {
    color: #0d6efd;
    text-decoration: none;
}

/* Add Card Button */
.add-card {
    background-color: #0d6efd;
    color: #fff;
    padding: 10px 15px;
    font-size: 14px;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}

.modal-content {
    background: #fff;
    border-radius: 10px;
    /* width: 400px; */
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    position: relative;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.close-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
}

.modal-dialog {
    animation: zoom-in 0.3s ease;
}

@keyframes zoom-in {
    from {
        transform: scale(0.8);
    }

    to {
        transform: scale(1);
    }
}

/* Fade transition effect */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
