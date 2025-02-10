<!-- AddAddressModal.vue -->
<template>
  <v-dialog v-model="dialog" width="900" persistent>
    <v-card class="rounded-lg modal-card">
      <!-- Header -->
      <div class="modal-fixed-header">
        <v-card-title class="d-flex justify-space-between align-center py-3">
          <span class="text-h5">Add New Address</span>
          <v-btn icon="mdi-close" variant="text" @click="closeDialog"></v-btn>
        </v-card-title>
      </div>

      <!-- Content -->
      <div class="modal-scrollable-content">
        <v-stepper v-model="currentStep">
          <!-- Stepper Header -->
          <v-stepper-header class="primary-bg py-2">
            <template v-for="(step, index) in steps" :key="index">
              <v-stepper-item :value="index + 1" :complete="currentStep > index + 1" :title="step.title">
                <template v-slot:title>
                  <span class="text-subtitle-1 font-weight-medium">{{ step.title }}</span>
                </template>
              </v-stepper-item>
              <v-divider v-if="index < steps.length - 1"></v-divider>
            </template>
          </v-stepper-header>

          <!-- Stepper Content -->
          <v-stepper-items class="overflow-y-auto" style="max-height: 60vh;">
            <!-- Step 1: Address Details -->
            <v-stepper-content v-if="currentStep === 1" step="1">
              <v-card-text>
                <v-form ref="addressForm">
                  <v-text-field v-model="addressInput" label="Address" @input="handleAddressInput"
                    :rules="[rules.required]"></v-text-field>

                  <!-- Address Suggestions -->
                  <v-list v-if="suggestions?.length" class="suggestions-list">
                    <v-list-item v-for="suggestion in suggestions" :key="suggestion.id ?? index"
                      @click="selectAddress(suggestion)" class="suggestion-item">
                      {{ suggestion.place_name }}
                    </v-list-item>
                  </v-list>

                  <v-row class="mt-3">
                    <v-col cols="6">
                      <v-select v-model="formData.province" :items="provinces" label="Province"
                        :rules="[rules.required]" @change="handleProvinceChange"></v-select>
                    </v-col>

                    <v-col cols="6">
                      <v-select v-model="formData.city" :items="cities" label="City" :rules="[rules.required]"
                        :disabled="!formData.province"></v-select>
                    </v-col>
                  </v-row>

                  <v-row>
                    <v-col cols="6">
                      <v-text-field v-model="formData.postalCode" label="Postal Code" maxlength="7"
                        placeholder="A1A 1A1" :rules="[rules.postalCode]"
                        @input="limitText('postalCode', 7)"></v-text-field>

                    </v-col>

                    <v-col cols="6">
                      <v-text-field v-model="formData.unitNumber" label="Unit Number" :rules="[rules.maxLength(20)]"
                        @input="limitText('unitNumber', 20)" placeholder="Apartment or Condo"></v-text-field>
                    </v-col>
                  </v-row>

                  <v-row>
                    <v-col cols="6">
                      <v-text-field v-model="formData.houseNumber" label="House Number"
                        :rules="[rules.required, rules.onlyNumbers, rules.maxLength(20)]" type="text"
                        @input="limitNumber('houseNumber', 1, 20)"></v-text-field>
                    </v-col>

                    <v-col cols="6">
                      <v-text-field v-model="formData.streetName" label="Street Name"
                        :rules="[rules.required, rules.maxLength(50)]" maxlength="50"
                        @input="limitText('streetName', 50)"></v-text-field>
                    </v-col>
                  </v-row>
                </v-form>
              </v-card-text>
            </v-stepper-content>

            <!-- Step 2: Amount Details -->
            <v-stepper-content v-if="currentStep === 2" step="2">
              <v-card-text>
                <v-form ref="amountForm">
                  <v-text-field class="mb-3" v-model="formData.monthlyAmount" label="Monthly Amount" type="text"
                    :rules="[rules.required, rules.minAmount, rules.maxAmount]"
                    @input="limitNumber('monthlyAmount', 20)" prefix="$"></v-text-field>

                  <v-select v-model="formData.paymentDay" :items="days" label="Monthly Payment Day"
                    :rules="[rules.required]"></v-select>
                </v-form>
              </v-card-text>
            </v-stepper-content>

            <!-- Step 3: Agreement Details -->
            <v-stepper-content v-if="currentStep === 3" step="3">
              <v-card-text>
                <v-form ref="agreementForm">
                  <!-- <v-file-input v-model="formData.tenancyAgreement" label="Upload Tenancy Agreement"
                    :rules="[rules.required, rules.fileType, rules.fileSize]" prepend-icon="mdi-file-document"
                    accept="image/*,.pdf,.doc,.docx" :show-size="true" :hint="'Max file size: 10MB'"></v-file-input> -->
                  <v-file-input v-model="formData.tenancyAgreement" label="Upload Tenancy Agreement" :rules="fileRules"
                    prepend-icon="mdi-file-document" accept="image/*,.pdf,.doc,.docx" :show-size="true"
                    :hint="'Max file size: 10MB'" class="mb-6"></v-file-input>
                  <v-row>
                    <v-col cols="6">
                      <div class="d-flex justify-center">
                        <v-date-input clearable v-model="formData.startDate" label="Start date"
                          :rules="[rules.startDate]" validate-on="input"></v-date-input>
                      </div>
                    </v-col>
                    <v-col cols="6">
                      <div class="d-flex justify-center">
                        <v-date-input clearable v-model="formData.endDate" label="End date" :rules="[rules.endDate]"
                          validate-on="input"></v-date-input>
                      </div>
                    </v-col>
                  </v-row>
                </v-form>
              </v-card-text>
            </v-stepper-content>

            <!-- Step 4: Payment Details -->
            <v-stepper-content v-if="currentStep === 4" step="4">
              <v-card-text>
                <v-form ref="paymentForm">
                  <!-- Rent Flow -->
                  <template v-if="formData.accountGoal === 'rent'">
                    <div class="payment-method-section">
                      <h3 class="text-h6 mb-4">Landlord Details</h3>
                      <p class="text-body-1 mb-6">How does your landlord accept rent?</p>

                      <v-radio-group v-model="formData.paymentMethod" :rules="[rules.required]">
                        <v-row>
                          <!-- Interac Option -->
                          <v-col cols="12">
                            <div class="payment-option" :class="{ 'selected': formData.paymentMethod === 'interac' }"
                              @click="formData.paymentMethod = 'interac'" role="button">
                              <div class="d-flex align-center">
                                <div class="payment-radio-wrapper">
                                  <v-radio label="" value="interac" hide-details class="mr-2"></v-radio>
                                </div>
                                <div class="payment-content">
                                  <v-icon color="primary" size="20" class="mr-2">mdi-bank-transfer</v-icon>
                                  <span class="payment-label">Interac E-transfer</span>
                                </div>
                              </div>
                            </div>
                          </v-col>

                          <!-- Cheque Option -->
                          <v-col cols="12">
                            <div class="payment-option" :class="{ 'selected': formData.paymentMethod === 'cheque' }"
                              @click="formData.paymentMethod = 'cheque'" role="button">
                              <div class="d-flex align-center">
                                <div class="payment-radio-wrapper">
                                  <v-radio label="" value="cheque" hide-details class="mr-2"></v-radio>
                                </div>
                                <div class="payment-content">
                                  <v-icon color="primary" size="20" class="mr-2">mdi-checkbox-marked</v-icon>
                                  <span class="payment-label">Cheque</span>
                                </div>
                              </div>
                            </div>
                          </v-col>
                        </v-row>
                      </v-radio-group>

                      <!-- Interac Email Field -->
                      <v-expand-transition>
                        <div v-if="formData.paymentMethod === 'interac'" class="mt-6">
                          <v-text-field v-model="formData.landlordEmail" label="Enter your landlord's email"
                            :rules="[rules.required, rules.email]" class="mb-6"></v-text-field>
                        </div>
                      </v-expand-transition>

                      <v-expand-transition>
                        <div v-if="formData.paymentMethod === 'cheque'">
                          <!-- Interac Email Field -->
                          <v-expand-transition>
                            <div v-if="formData.paymentMethod === 'interac'" class="mt-6">
                              <v-text-field v-model="formData.landlordEmail" label="Enter your landlord's email"
                                :rules="[rules.required, rules.email]" @input="limitText('landlordEmail', 30)"
                                class="mb-6"></v-text-field>
                            </div>
                          </v-expand-transition>

                          <!-- Landlord Type Selection -->
                          <h3 class="text-h6 mb-4 mt-6">Landlord Type</h3>
                          <p class="text-body-1 mb-6">Is your landlord an individual or business?</p>

                          <v-radio-group v-model="formData.landlordType" :rules="[rules.required]">
                            <v-row>
                              <!-- Business Option -->
                              <v-col cols="12">
                                <div class="payment-option"
                                  :class="{ 'selected': formData.landlordType === 'business' }"
                                  @click="formData.landlordType = 'business'" role="button">
                                  <div class="d-flex align-center">
                                    <div class="payment-radio-wrapper">
                                      <v-radio label="" value="business" hide-details class="mr-2"></v-radio>
                                    </div>
                                    <div class="payment-content">
                                      <v-icon color="primary" size="20" class="mr-2">mdi-domain</v-icon>
                                      <span class="payment-label">Business</span>
                                    </div>
                                  </div>
                                </div>
                              </v-col>

                              <!-- Individual Option -->
                              <v-col cols="12">
                                <div class="payment-option"
                                  :class="{ 'selected': formData.landlordType === 'individual' }"
                                  @click="formData.landlordType = 'individual'" role="button">
                                  <div class="d-flex align-center">
                                    <div class="payment-radio-wrapper">
                                      <v-radio label="" value="individual" hide-details class="mr-2"></v-radio>
                                    </div>
                                    <div class="payment-content">
                                      <v-icon color="primary" size="20" class="mr-2">mdi-account</v-icon>
                                      <span class="payment-label">Individual</span>
                                    </div>
                                  </div>
                                </div>
                              </v-col>
                            </v-row>
                          </v-radio-group>

                          <!-- Business Name -->
                          <v-expand-transition>
                            <div v-if="formData.landlordType === 'business'" class="mt-6">
                              <v-text-field v-model="formData.businessName" label="Business Name"
                                :rules="[rules.required]" @input="limitText('businessName', 30)"
                                class="mb-6"></v-text-field>
                            </div>
                          </v-expand-transition>

                          <!-- Individual Name Fields -->
                          <v-expand-transition>
                            <div v-if="formData.landlordType === 'individual'" class="mt-6">
                              <v-text-field v-model="formData.landlordFirstName" label="First Name"
                                :rules="[rules.required]" @input="limitText('landlordFirstName', 30)"
                                class="mb-6"></v-text-field>

                              <v-text-field v-model="formData.landlordLastName" label="Last Name"
                                :rules="[rules.required]" @input="limitText('landlordLastName', 30)"
                                class="mb-6"></v-text-field>

                              <v-text-field v-model="formData.landlordMiddleName" @input="limitText('businessName', 30)"
                                label="Middle Name" class="mb-6"></v-text-field>
                            </div>
                          </v-expand-transition>
                        </div>
                      </v-expand-transition>

                    </div>
                  </template>

                  <!-- Mortgage Flow -->
                  <template v-if="formData.accountGoal === 'mortgage'">
                    <!-- Payment Method Selection -->
                    <div class="payment-method-section">
                      <h3 class="text-h6 mb-4">Mortgage Financer Details</h3>
                      <p class="text-body-1 mb-6">How does your mortgage financer accept payment?</p>

                      <v-radio-group v-model="formData.paymentMethod" :rules="[rules.required]">
                        <v-row>
                          <!-- EFT Option -->
                          <v-col cols="12">
                            <div class="payment-option" :class="{ 'selected': formData.paymentMethod === 'eft' }"
                              @click="formData.paymentMethod = 'eft'" role="button">
                              <div class="d-flex align-center">
                                <div class="payment-radio-wrapper">
                                  <v-radio label="" value="eft" hide-details class="mr-2"></v-radio>
                                </div>
                                <div class="payment-content">
                                  <v-icon color="primary" size="20" class="mr-2">mdi-bank-transfer</v-icon>
                                  <span class="payment-label">EFT</span>
                                  <span class="payment-description">(Electronic Funds Transfer)</span>
                                </div>
                              </div>
                            </div>
                          </v-col>

                          <!-- Cheque Option -->
                          <v-col cols="12">
                            <div class="payment-option" :class="{ 'selected': formData.paymentMethod === 'cheque' }"
                              @click="formData.paymentMethod = 'cheque'" role="button">
                              <div class="d-flex align-center">
                                <div class="payment-radio-wrapper">
                                  <v-radio label="" value="cheque" hide-details class="mr-2"></v-radio>
                                </div>
                                <div class="payment-content">
                                  <v-icon color="primary" size="20" class="mr-2">mdi-checkbox-marked</v-icon>
                                  <span class="payment-label">Cheque</span>
                                </div>
                              </div>
                            </div>
                          </v-col>
                        </v-row>
                      </v-radio-group>
                    </div>

                    <!-- EFT Fields -->
                    <template v-if="formData.paymentMethod === 'eft'">
                      <v-text-field v-model="formData.mortgageAccountNumber" label="Mortgage Account Number"
                        persistent-hint :rules="[rules.required, rules.onlyNumbers]"
                        @input="limitNumber('mortgageAccountNumber', 20)" class="mb-4"></v-text-field>

                      <!-- Lender Details -->
                      <v-text-field v-model="formData.lenderName" label="Lender Name" persistent-hint
                        :rules="[rules.required]" @input="limitText('lenderName', 20)" class="mb-4"></v-text-field>

                      <div class="address-container">
                        <v-text-field v-model="lenderAddressInput" label="Lender Address"
                          @input="handleLenderAddressInput" :rules="[rules.required]" persistent-hint
                          class="mb-4"></v-text-field>

                        <!-- Address Suggestions -->
                        <v-list v-if="lenderSuggestions?.length" class="suggestions-list">
                          <v-list-item v-for="suggestion in lenderSuggestions" :key="suggestion.id"
                            @click="selectLenderAddress(suggestion)" class="suggestion-item" density="compact">
                            {{ suggestion.place_name }}
                          </v-list-item>
                        </v-list>
                      </div>

                      <!-- Bank Information -->
                      <v-row>
                        <v-col cols="6">
                          <v-text-field v-model="formData.institutionNumber" label="Institution Number" persistent-hint
                            :rules="[rules.required, rules.institutionNumber]"
                            @input="limitNumber('institutionNumber', 20)"></v-text-field>
                        </v-col>
                        <v-col cols="6">
                          <v-text-field v-model="formData.transitNumber" label="Transit Number" persistent-hint
                            :rules="[rules.required, rules.transitNumber]"
                            @input="limitNumber('transitNumber', 10)"></v-text-field>
                        </v-col>
                      </v-row>

                      <v-text-field v-model="formData.bankAccountNumber" label="Bank Account Number" persistent-hint
                        :rules="[rules.required, rules.accountNumber]" @input="limitNumber('bankAccountNumber', 20)"
                        class="mb-4"></v-text-field>

                      <v-select v-model="formData.paymentFrequency" label="Payment Frequency"
                        :items="['Monthly', 'Bi-weekly']" persistent-hint :rules="[rules.required]"
                        class="mb-4"></v-select>

                      <v-text-field v-model="formData.referenceNumber" label="Reference Number or Code" persistent-hint
                        :rules="[rules.required]" @input="limitNumber('referenceNumber', 30)"></v-text-field>
                    </template>

                    <!-- Cheque Fields -->
                    <template v-if="formData.paymentMethod === 'cheque'">
                      <v-text-field v-model="formData.accountNumber" label="Account Number" persistent-hint
                        :rules="[rules.required]" @input="limitNumber('accountNumber', 10)" class="mb-4"></v-text-field>

                      <v-row>
                        <v-col cols="6">
                          <v-text-field v-model="formData.transitNumber" label="Transit Number" persistent-hint
                            :rules="[rules.required, rules.transitNumber]"
                            @input="limitNumber('transitNumber', 10)"></v-text-field>
                        </v-col>
                        <v-col cols="6">
                          <v-text-field v-model="formData.institutionNumber" label="Institution Number" persistent-hint
                            :rules="[rules.required, rules.institutionNumber]"
                            @input="limitNumber('institutionNumber', 10)"></v-text-field>
                        </v-col>
                      </v-row>

                      <v-text-field v-model="formData.chequeName" label="Name" persistent-hint :rules="[rules.required]"
                        @input="limitText('chequeName', 50)" class="mb-4"></v-text-field>

                      <div class="address-container">
                        <v-text-field v-model="lenderAddressInput" label="Lender Address"
                          @input="handleLenderAddressInput" :rules="[rules.required]" persistent-hint
                          class="mb-4"></v-text-field>

                        <!-- Address Suggestions -->
                        <v-list v-if="lenderSuggestions?.length" class="suggestions-list">
                          <v-list-item v-for="suggestion in lenderSuggestions" :key="suggestion.id"
                            @click="selectLenderAddress(suggestion)" class="suggestion-item" density="compact">
                            {{ suggestion.place_name }}
                          </v-list-item>
                        </v-list>
                      </div>
                    </template>
                  </template>

                </v-form>
              </v-card-text>
            </v-stepper-content>

          </v-stepper-items>
        </v-stepper>
      </div>

      <!-- Footer Actions -->
      <div class="modal-fixed-footer">
        <v-card-actions class="pa-4">
          <v-btn variant="outlined" @click="prevStep" :disabled="currentStep === 1">
            Back
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn variant="elevated" color="primary" @click="handleNext" :loading="loading">
            {{ isLastStep ? 'Submit' : 'Next' }}
          </v-btn>
        </v-card-actions>
      </div>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import { VDateInput } from 'vuetify/labs/VDateInput'
import { useToast } from "vue-toastification"
import mbxGeocoding from '@mapbox/mapbox-sdk/services/geocoding'
import { useAuthStore } from '@/stores/authStore'
import { storeToRefs } from 'pinia'
import { useNotifications } from '@/stores/useNotifications';

// Import at the top
import { format } from 'date-fns'

const { unreadCount, refresh: refreshNotifications } = useNotifications();

const authStore = useAuthStore()
const { user } = storeToRefs(authStore)

const emit = defineEmits(['refresh-data'])

const dialog = ref(false)
const currentStep = ref(1)
const loading = ref(false)
const addressInput = ref('')
const suggestions = ref([])
const cities = ref([])
const fileError = ref('')
const validationErrors = ref({});

const addressForm = ref(null)
const amountForm = ref(null)
const agreementForm = ref(null)
const paymentForm = ref(null)

const geocodingClient = mbxGeocoding({ accessToken: 'sk.eyJ1IjoiZmFpdGhkaW5ubyIsImEiOiJjbTNlaTBpemYwZGg0MmlxeHBvdmN1Njc1In0.DxTSIOGOsItCew8yHtscJw' });


const provinces = [
  'Ontario',
  'British Columbia',
  'Quebec',
  'Alberta',
  'Manitoba',
  'Nova Scotia',
  'Newfoundland and Labrador',
  'New Brunswick',
  'Prince Edward Island',
  'Saskatchewan'
]

const formData = ref({
  address: '',
  province: '',
  city: '',
  postalCode: '',
  unitNumber: '',
  houseNumber: '',
  streetName: '',
  accountGoal: user.value?.account_goal || '', // Set from user data
  monthlyAmount: '',
  paymentDay: '',
  startDate: null,
  endDate: null,
  tenancyAgreement: null,
  paymentMethod: '',
  landlordEmail: '',
  landlordType: '',
  businessName: '',
  landlordFirstName: '',
  landlordLastName: '',
  accountNumber: '',
  mortgageAccountNumber: '',
  chequeName: '',
  lenderName: '',
  institutionNumber: '',
  transitNumber: '',
  bankAccountNumber: ''
})

// Watch for user changes and update accountGoal
watch(() => user.value?.account_goal, (newAccountGoal) => {
  if (newAccountGoal) {
    formData.value.accountGoal = newAccountGoal
  }
}, { immediate: true })

// Add this for template reference
const accountGoal = computed(() => user.value?.account_goal || '')

console.log('accountGoal', accountGoal);


const steps = ref([
  { title: 'Address Info', valid: false },
  { title: 'Amount', valid: false },
  { title: 'Agreement', valid: false },
  { title: 'Payment Details', valid: false }
])

// Add this computed property
// Validation rules for the file input
const fileRules = computed(() => [
  (v) => !!v || 'Please upload a document', // File required validation
  (v) => {
    if (!v || v.length === 0) return true; // Skip further validation if no file
    const file = v[0];
    console.log('Selected file:', file); // Debugging log
    const allowedTypes = ['image/png', 'image/jpeg', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    return allowedTypes.includes(file.type) || 'Please upload a valid file type (PDF, DOC, DOCX, JPG, PNG)';
  },
  (v) => {
    if (!v || v.length === 0) return true; // Skip further validation if no file
    const file = v[0];
    const maxSize = 10 * 1024 * 1024; // 10MB
    console.log(`Uploaded file size: ${file.size}`);
    return file.size <= maxSize || 'File size must be less than 10MB';
  },
]);

const handleFileChange = (file) => {
  console.log('File change:', file);
  if (file) {
    console.log('Selected file:', {
      name: file.name,
      type: file.type,
      size: `${(file.size / 1024 / 1024).toFixed(2)}MB`
    });

    const isValidType = rules.fileType(file) === true;
    const isValidSize = rules.fileSize(file) === true;

    if (!isValidType || !isValidSize) {
      formData.value.tenancyAgreement = null;
      return;
    }

    formData.value.tenancyAgreement = file;
  } else {
    formData.value.tenancyAgreement = null;
  }
};

const rules = {
  required: (v) => {
    if (v === null) return 'Field is required';
    if (typeof v === 'object' && v instanceof File) return true;
    return !!v || 'Field is required';
  },
  email: v => /.+@.+\..+/.test(v) || 'Email must be valid',
  institutionNumber: v => /^\d{3}$/.test(v) || 'Institution number must be 3 digits',
  transitNumber: v => /^\d{5}$/.test(v) || 'Transit number must be 5 digits',
  accountNumber: v => /^\d{7,12}$/.test(v) || 'Account number must be 7-12 digits',
  postalCode: v => /^[ABCEGHJ-NPRSTVXY]\d[ABCEGHJ-NPRSTV-Z] ?\d[ABCEGHJ-NPRSTV-Z]\d$/i.test(v) ||
    'Format: A1A 1A1 (Letters: A-Z except D, F, I, O, Q, U; Numbers: 0-9)',
  postalMaxLength: v => (!v || v.length <= 7) || 'Postal code must be 7 characters max (including space)',
  minAmount: v => v >= 100 || 'Amount must be at least $100.00',
  maxAmount: v => v <= 50000000 || 'Amount cannot exceed $50,000.00',
  maxLength: (max) => (v) =>
    (!v || v.length <= max) || `Maximum ${max} characters allowed`,
  // Only Numbers Validation
  onlyNumbers: (v) =>
    /^[0-9]*$/.test(v) || "Only numeric values are allowed",

  // Min-Max Number Validation
  minMaxValue: (min, max) => (v) => {
    if (!v) return "Value is required";
    const num = Number(v);
    if (isNaN(num)) return "Invalid number";
    if (num < min) return `Value must be at least ${min}`;
    if (num > max) return `Value must not exceed ${max}`;
    return true;
  },
  amount: [
    v => !!v || 'Amount is required',
    v => !isNaN(parseFloat(v)) || 'Amount must be a number',
    v => parseFloat(v) > 0 || 'Amount must be greater than 0'
  ],
  startDate: v => {
    if (!v) return 'Start date is required';
    const currentDate = new Date();
    currentDate.setDate(1); // First day of current month
    const inputDate = new Date(v);
    return inputDate >= currentDate || 'Start date cannot be before current month';
  },
  endDate: v => {
    if (!v) return 'End date is required';
    if (!formData.value?.startDate) return true;

    const startDate = new Date(formData.value.startDate);
    const endDate = new Date(v);
    const minEndDate = new Date(startDate);
    minEndDate.setMonth(startDate.getMonth() + 1);

    return endDate >= minEndDate || 'End date must be at least 1 month after start date';
  },
}

// Function to limit text fields to a max character count
const limitText = (field, max) => {
  if (formData.value[field].length > max) {
    formData.value[field] = formData.value[field].slice(0, max);
  }
};

// Function to limit numeric fields within a range
const limitNumber = (field, minLength, maxLength) => {
  let input = formData.value[field];

  // Remove non-numeric characters
  input = input.replace(/\D/g, "");

  // Ensure input does not exceed max length
  if (input.length > maxLength) {
    input = input.slice(0, maxLength);
  }

  // Ensure input meets the minimum length requirement
  if (input.length < minLength) {
    validationErrors.value[field] = `Must be at least ${minLength} characters`;
  } else {
    validationErrors.value[field] = ""; // Clear error when valid
  }

  formData.value[field] = input;
};


const days = computed(() => Array.from({ length: 31 }, (_, i) => i + 1))
const isLastStep = computed(() => currentStep.value === steps.value.length)

const debounce = (fn, delay) => {
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => fn(...args), delay);
  };
};

// Methods
const handleAddressInput = debounce(async () => {
  if (addressInput.value.length < 3) {
    suggestions.value = []
    return
  }

  try {
    const response = await geocodingClient.forwardGeocode({
      query: addressInput.value,
      countries: ['CA'],
      autocomplete: true
    }).send()

    suggestions.value = response.body.features
  } catch (error) {
    console.error('Error fetching suggestions:', error)
    useToast().error('Error fetching address suggestions')
  }
}, 500)

const selectAddress = (feature) => {
  if (!feature) return

  addressInput.value = feature.place_name
  suggestions.value = []
  formData.value.address = feature.place_name

  if (feature.context) {
    feature.context.forEach(component => {
      if (component.id.includes('region')) {
        formData.value.province = component.text
        handleProvinceChange()
      } else if (component.id.includes('place')) {
        formData.value.city = component.text
      }
    })
  }

  const addressParts = feature.place_name.split(',')[0].trim().split(' ')
  if (addressParts.length > 1) {
    formData.value.houseNumber = addressParts[0]
    formData.value.streetName = addressParts.slice(1).join(' ')
  }
}

const handleProvinceChange = async () => {
  if (!formData.value.province) {
    cities.value = []
    return
  }

  try {
    const response = await axios.get(`/api/addresses/cities/${encodeURIComponent(formData.value.province)}`)
    cities.value = response.data
  } catch (error) {
    console.error('Error fetching cities:', error)
    cities.value = []
  }
}

const handleNext3 = async () => {
  const formRefs = {
    1: addressForm,
    2: amountForm,
    3: agreementForm,
    4: paymentForm
  }

  const currentForm = formRefs[currentStep.value]
  if (currentForm?.value) {
    const { valid } = await currentForm.value.validate()
    if (valid) {
      if (isLastStep.value) {
        await submitForm()
      } else {
        currentStep.value++
      }
    }
  }
}

const handleNext = async () => {
  const formRefs = {
    1: addressForm,
    2: amountForm,
    3: agreementForm,
    4: paymentForm
  }

  const currentForm = formRefs[currentStep.value]
  if (currentForm?.value) {
    try {
      const { valid } = await currentForm.value.validate()

      // Special handling for agreement form
      if (currentStep.value === 3) {
        if (valid && formData.value.tenancyAgreement && formData.value.startDate && formData.value.endDate) {
          currentStep.value++
          return
        }
        if (!formData.value.tenancyAgreement) {
          useToast().error('Please upload a document')
          return
        }
      }

      if (valid) {
        if (isLastStep.value) {
          await submitForm()
        } else {
          currentStep.value++
        }
      }
    } catch (error) {
      console.error('Validation error:', error)
    }
  }
}

// Define the formatting function
const formatDateForBackend = (date) => {
  if (!date) return null;
  return format(new Date(date), 'yyyy-MM-dd');
}

// Update the submitForm method
const submitForm = async () => {
  loading.value = true;
  try {
    const formDataObj = new FormData();

    // Prepare the payment details based on account goal
    const paymentDetails = {
      payment_method: formData.value.paymentMethod
    };

    // Format dates properly before adding to FormData
    formDataObj.append('startDate', formatDateForBackend(formData.value.startDate));
    formDataObj.append('endDate', formatDateForBackend(formData.value.endDate));

    if (formData.value.accountGoal === 'rent') {
      if (formData.value.paymentMethod === 'interac') {
        paymentDetails.email = formData.value.landlordEmail;
      }

      paymentDetails.landlord_type = formData.value.landlordType;
      if (formData.value.landlordType === 'business') {
        paymentDetails.businessName = formData.value.businessName;
      } else {
        paymentDetails.firstName = formData.value.landlordFirstName;
        paymentDetails.lastName = formData.value.landlordLastName;
        paymentDetails.middleName = formData.value.landlordMiddleName || '';
      }
    } else { // mortgage
      if (formData.value.paymentMethod === 'eft') {
        paymentDetails.accountNumber = formData.value.mortgageAccountNumber;
        paymentDetails.lenderName = formData.value.lenderName;
        paymentDetails.institutionNumber = formData.value.institutionNumber;
        paymentDetails.transitNumber = formData.value.transitNumber;
        paymentDetails.bankAccountNumber = formData.value.bankAccountNumber;
        paymentDetails.paymentFrequency = formData.value.paymentFrequency;
        paymentDetails.refNumber = formData.value.referenceNumber;
      }
      if (formData.value.paymentMethod === 'cheque') {
        paymentDetails.accountNumber = formData.value.accountNumber;
        paymentDetails.institutionNumber = formData.value.institutionNumber;
        paymentDetails.transitNumber = formData.value.transitNumber;
        paymentDetails.chequeName = formData.value.chequeName;
        paymentDetails.lenderAddress = formData.value.lenderAddress;
      }
    }

    // Append form fields
    Object.entries(formData.value).forEach(([key, value]) => {
      // Skip startDate and endDate as they're already handled
      if (key === 'startDate' || key === 'endDate') return;
      
      if (key === 'tenancyAgreement') {
        if (value) formDataObj.append('tenancyAgreement', value);
      } else if (value !== null && value !== '') {
        formDataObj.append(key, value);
      }
    });

    // Append payment details
    formDataObj.append('payment_details', JSON.stringify(paymentDetails));

    await axios.post('/api/addresses/setup', formDataObj, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    dialog.value = false;
    useToast().success('Address added successfully');
    await refreshNotifications();
    emit('refresh-data'); // This will trigger the parent's handleRefreshData
    resetForm();
  } catch (error) {
    handleSubmissionError(error);
  } finally {
    loading.value = false;
  }
};


const handleSubmissionError = (error) => {
  const status = error.response?.status;
  const responseData = error.response?.data;

  switch (status) {
    case 422:
      // Check if we have a general message or validation errors
      if (responseData.errors) {
        // Handle validation errors (multiple errors)
        Object.values(responseData.errors).forEach(errors => {
          errors.forEach(error => useToast().error(error));
        });
      } else if (responseData.message) {
        // Handle single error message
        useToast().error(responseData.message);
      }
      break;
    case 413:
      useToast().error('File size too large');
      break;
    default:
      useToast().error('An error occurred while adding the address');
  }
}

const lenderAddressInput = ref('');
const lenderSuggestions = ref([]);

// Handle address input with debounce
const handleLenderAddressInput = debounce(async () => {
  console.log('Input value:', lenderAddressInput.value); // Debug log

  if (lenderAddressInput.value.length < 3) {
    lenderSuggestions.value = [];
    return;
  }

  try {
    const response = await geocodingClient.forwardGeocode({
      query: lenderAddressInput.value,
      countries: ['CA'],
      autocomplete: true
    }).send();

    console.log('API response:', response.body.features); // Debug log
    lenderSuggestions.value = response.body.features;
  } catch (error) {
    console.error('Error fetching suggestions:', error);
  }
}, 500);

// Handle address selection
const selectLenderAddress = (feature) => {
  if (!feature) return;

  formData.value.lenderAddress = feature.place_name;
  lenderAddressInput.value = feature.place_name;
  lenderSuggestions.value = [];
};

const prevStep = () => {
  currentStep.value--
}

const closeDialog = () => {
  dialog.value = false
  resetForm()
}

const resetForm = () => {
  currentStep.value = 1
  addressInput.value = ''
  suggestions.value = []
  formData.value = {
    address: '',
    province: '',
    city: '',
    postalCode: '',
    unitNumber: '',
    houseNumber: '',
    streetName: '',
    accountGoal: '',
    monthlyAmount: '',
    paymentDay: '',
    startDate: null,
    endDate: null,
    tenancyAgreement: null,
    paymentMethod: '',
    landlordEmail: '',
    landlordType: '',
    businessName: '',
    landlordFirstName: '',
    landlordLastName: '',
    accountNumber: '',
    mortgageAccountNumber: '',
    lenderName: '',
    institutionNumber: '',
    transitNumber: '',
    bankAccountNumber: ''
  }
}

const showModal = () => {
  dialog.value = true
}

defineExpose({ showModal })
</script>


<style scoped>
.payment-option {
  border: 1px solid #E0E0E0;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s ease;
  background: white;
}

.payment-option:hover {
  border-color: var(--v-primary-base);
  background: rgb(var(--v-theme-primary-lighten-5));
}

.payment-option.selected {
  border-color: var(--v-primary-base);
  background: rgb(var(--v-theme-primary-lighten-5));
}

.payment-content {
  display: flex;
  align-items: center;
  gap: 8px;
}

.payment-label {
  font-weight: 500;
  color: #333;
}

:deep(.v-radio) {
  margin-right: 8px;
}

:deep(.v-radio .v-selection-control--dirty) {
  color: rgb(var(--v-theme-primary));
}

:deep(.v-expand-transition) {
  transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}


.v-dialog {
  transition: none !important;
}

:deep(.v-overlay__content) {
  transition: none !important;
}

:deep(.v-dialog-transition-enter-active),
:deep(.v-dialog-transition-leave-active) {
  transition: none !important;
}

/* Override dialog transition duration */
:deep(.v-dialog-transition-enter-active),
:deep(.v-dialog-transition-leave-active) {
  transition-duration: 0.1s !important;
}

.v-stepper {
  box-shadow: none;
}

.v-stepper-header {
  border-radius: 8px;
  margin: 16px;
}

.v-stepper-item--complete {
  color: rgb(var(--v-theme-purple-darken-1)) !important;
}

.v-stepper-item--active {
  color: rgb(var(--v-theme-purple-darken-1)) !important;
}

.suggestions-list {
  position: absolute;
  width: 100%;
  z-index: 1000;
  background: white;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.suggestion-item {
  cursor: pointer;
  padding: 8px 16px;
}

.suggestion-item:hover {
  background-color: rgb(var(--v-theme-purple-lighten-5));
}

.v-card-actions {
  position: sticky;
  bottom: 0;
  background: white;
  border-top: 1px solid rgba(0, 0, 0, 0.12);
}

.v-stepper-content {
  min-height: 300px;
  /* Adjust based on content */
}

/* Add these styles */
.v-stepper-items {
  overflow-y: auto;
  max-height: 60vh;
  /* Adjust based on your needs */
}

.v-card {
  display: flex;
  flex-direction: column;
  max-height: 90vh;
}

.v-card-text {
  overflow-y: auto;
}

/* Optional: Add custom scrollbar styling */
.v-stepper-items::-webkit-scrollbar {
  width: 8px;
}

.v-stepper-items::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.v-stepper-items::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

.modal-card {
  display: flex;
  flex-direction: column;
  height: 90vh;
  max-height: 90vh;
  overflow: hidden;
}

.modal-fixed-header {
  flex-shrink: 0;
}

.modal-scrollable-content {
  flex-grow: 1;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 0 20px;
}

.modal-fixed-footer {
  flex-shrink: 0;
  background: white;
  border-top: 1px solid rgba(0, 0, 0, 0.12);
}

/* Hide stepper default scrollbar */
.v-stepper {
  overflow: visible !important;
}

/* Custom scrollbar */
.modal-scrollable-content::-webkit-scrollbar {
  width: 8px;
}

.modal-scrollable-content::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.modal-scrollable-content::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

.modal-scrollable-content::-webkit-scrollbar-thumb:hover {
  background: #666;
}


.payment-option {
  display: flex;
  align-items: center;
  padding: 16px;
  border: 1px solid #E0E0E0;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s ease;
  background: white;
  height: 100%;
}

.payment-option:hover {
  border-color: #9155FD;
  background: #F9F5FF;
}

.payment-option.selected {
  border-color: #9155FD;
  background: #F9F5FF;
}

.payment-radio-wrapper {
  margin-right: 12px;
  display: flex;
  align-items: center;
}

.payment-content {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 8px;
}

.payment-label {
  font-weight: 500;
  color: #333;
  margin-right: 4px;
}

.payment-description {
  color: #666;
  font-size: 0.9em;
}

:deep(.v-radio) {
  color: #9155FD;
}

:deep(.v-radio .v-selection-control--dirty) {
  color: #9155FD;
}

:deep(.v-radio .v-label) {
  margin-left: 0;
}
</style>
