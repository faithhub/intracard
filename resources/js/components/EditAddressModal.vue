<!-- EditAddressModal.vue -->
<template>
  <v-dialog v-model="dialog" width="900" persistent :model-value="dialog" @update:model-value="handleDialogUpdate">
    <v-card class="rounded-lg modal-card">
      <!-- Fixed Header Section -->
      <div class="modal-fixed-header">
        <v-card-title class="d-flex justify-space-between align-center py-3">
          <span class="text-h5">Update Address</span>
          <v-btn icon="mdi-close" variant="text" @click="closeDialog"></v-btn>
        </v-card-title>

        <v-alert border="none" class="ma-4" bg-color="primary-light" color="white" icon="mdi-information">
          <div class="text-body-1">
            <strong>Update History</strong>
            <div class="mt-1">Updates used: {{ address?.edit_count || 0 }}/3 this year</div>
            <div v-if="address?.last_edit_date">Last updated: {{ formatDate(address.last_edit_date) }}</div>
            <div>Remaining updates: {{ 3 - (address?.edit_count || 0) }}</div>
          </div>
        </v-alert>
      </div>

      <!-- Scrollable Content -->
      <div class="modal-scrollable-content">
        <!-- Stepper -->
        <v-stepper v-model="currentStep">
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

            <!-- Step 2: Only amount -->
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
                  <v-file-input v-model="formData.tenancyAgreement" label="Upload Tenancy Agreement"
                    :rules="[rules.required, rules.fileType, rules.fileSize]" prepend-icon="mdi-file-document"
                    accept="image/*,.pdf,.doc,.docx" :show-size="true" :hint="'Max file size: 10MB'"></v-file-input>
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
                  <!-- <v-date-input 
    clearable 
    :model-value="formData.startDate"
    @update:model-value="date => handleStartDateChange(date)"
    label="Start date"
    :rules="[rules.startDate]"
    validate-on="input"
></v-date-input>

                  <v-date-input clearable v-model="formData.endDate" label="End date" :rules="[rules.endDate]"
                    @update:model-value="date => handleDateSelection(date, 'endDate')"
                    validate-on="input"></v-date-input> -->
                </v-form>
              </v-card-text>
            </v-stepper-content>

            <!-- Step 4: Payment Details -->
            <!-- Payment Details -->
            <v-stepper-content v-if="currentStep === 4" step="4">
              <v-card-text>
                <v-form ref="paymentForm">
                  <!-- Rent Flow -->
                  <template v-if="props.address?.account_goal === 'rent'">
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
                                :rules="[rules.required, rules.email]"  @input="limitText('landlordEmail', 30)" class="mb-6"></v-text-field>
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
                                :rules="[rules.required]" @input="limitText('businessName', 30)" class="mb-6"></v-text-field>
                            </div>
                          </v-expand-transition>

                          <!-- Individual Name Fields -->
                          <v-expand-transition>
                            <div v-if="formData.landlordType === 'individual'" class="mt-6">
                              <v-text-field v-model="formData.landlordFirstName" label="First Name"
                                :rules="[rules.required]" @input="limitText('landlordFirstName', 30)" class="mb-6"></v-text-field>

                              <v-text-field v-model="formData.landlordLastName" label="Last Name"
                                :rules="[rules.required]" @input="limitText('landlordLastName', 30)" class="mb-6"></v-text-field>

                              <v-text-field v-model="formData.landlordMiddleName" @input="limitText('businessName', 30)" label="Middle Name"
                                class="mb-6"></v-text-field>
                            </div>
                          </v-expand-transition>
                        </div>
                      </v-expand-transition>


                    </div>
                  </template>

                  <!-- Mortgage Flow -->
                  <template v-if="props.address?.account_goal === 'mortgage'">
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

      <!-- Actions -->
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
import {
  useToast
} from "vue-toastification";
// import { VDatePicker, VMenu, VTextField } from 'vuetify/lib';

import mbxGeocoding from '@mapbox/mapbox-sdk/services/geocoding';
import { useAuthStore } from '@/stores/authStore'
import { storeToRefs } from 'pinia'
import { useNotifications } from '@/stores/useNotifications';
const { unreadCount, refresh: refreshNotifications } = useNotifications();

const authStore = useAuthStore()
const { user } = storeToRefs(authStore)

// Add a new ref to store initial form data
const initialFormData = ref({});

const emit = defineEmits(['refresh-data']) // Change emit name to match parent

const props = defineProps({
  address: {
    type: Object,
    required: true,
    validator(value) {
      console.log('Incoming address prop:', value);
      return true;
    }
  },
  landlordFinance: {
    type: Object,
    required: true,
    validator(value) {
      console.log('Incoming landlord finance prop:', value);
      return true;
    }
  }
})

const landlordFinanceObject = props.landlordFinance[0] || {};

const submitForm2 = async () => {
  console.log(agreementForm);

  if (!agreementForm.value || !await agreementForm.value.validate()) return;

  console.log(agreementForm.value);

  // Check if form has changes
  if (!hasFormChanged()) {
    useToast().info('No changes detected');
    return;
  }

  console.log(agreementForm.value);


  loading.value = true;
  try {
    const formDataObj = new FormData();

    console.log(formDataObj);

    const formattedData = {
      ...formData.value,
      startDate: formData.value.startDate ? new Date(formData.value.startDate).toISOString().split('T')[0] : null,
      endDate: formData.value.endDate ? new Date(formData.value.endDate).toISOString().split('T')[0] : null
    };

    Object.entries(formattedData).forEach(([key, value]) => {
      if (value !== null) formDataObj.append(key, value);
    });

    await axios.post(`/api/addresses/update`, formDataObj, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    // Close dialog first
    dialog.value = false;

    // Then show success message and emit event
    useToast().success('Address updated successfully');
    emit('refresh-data');
  } catch (error) {
    const status = error.response?.status;
    switch (status) {
      case 422:
        Object.values(error.response.data.errors).forEach(errors => {
          errors.forEach(error => useToast().error(error));
        });
        break;
      case 403:
        useToast().error('Edit limit reached for this year');
        break;
      case 404:
        useToast().error('Address not found');
        break;
      case 413:
        useToast().error('File size too large');
        break;
      default:
        useToast().error('An error occurred while updating the address');
    }
  } finally {
    loading.value = false;
  }
};

const submitForm = async () => {
  // Validate all forms
  const formRefs = {
    addressForm,
    amountForm,
    agreementForm,
    paymentForm
  }

  // Validate all forms
  try {
    const validations = await Promise.all(
      Object.values(formRefs).map(async (formRef) => {
        if (!formRef.value) return true;
        const { valid } = await formRef.value.validate();
        return valid;
      })
    );

    if (validations.includes(false)) {
      useToast().error('Please fill in all required fields');
      return;
    }

    // Check if form has changes
    if (!hasFormChanged()) {
      useToast().info('No changes detected');
      return;
    }


    loading.value = true;
    const formDataObj = new FormData();

    // Format dates
    const formattedData = {
      ...formData.value,
      accountGoal: user.value?.account_goal, // Add account goal from user
      startDate: formData.value.startDate ? new Date(formData.value.startDate).toISOString().split('T')[0] : null,
      endDate: formData.value.endDate ? new Date(formData.value.endDate).toISOString().split('T')[0] : null
    };

    // Prepare payment details
    const paymentDetails = {
      payment_method: formData.value.paymentMethod
    };

    if (formattedData.accountGoal === 'rent') {
      if (formData.value.paymentMethod === 'interac') {
        paymentDetails.email = formData.value.landlordEmail;
      }

      if (formData.value.landlordType === 'business') {
        paymentDetails.businessName = formData.value.businessName;
      } else {
        paymentDetails.firstName = formData.value.landlordFirstName;
        paymentDetails.lastName = formData.value.landlordLastName;
        paymentDetails.middleName = formData.value.landlordMiddleName || '';
      }
      paymentDetails.landlordType = formData.value.landlordType;
    } else {
      if (formData.value.paymentMethod === 'eft') {
        paymentDetails.accountNumber = formData.value.mortgageAccountNumber;
        paymentDetails.lenderName = formData.value.lenderName;
        paymentDetails.lenderAddress = formData.value.lenderAddress;
        paymentDetails.institutionNumber = formData.value.institutionNumber;
        paymentDetails.transitNumber = formData.value.transitNumber;
        paymentDetails.bankAccountNumber = formData.value.bankAccountNumber;
        paymentDetails.paymentFrequency = formData.value.paymentFrequency;
        paymentDetails.refNumber = formData.value.referenceNumber;
      } else {
        paymentDetails.accountNumber = formData.value.accountNumber;
        paymentDetails.transitNumber = formData.value.transitNumber;
        paymentDetails.institutionNumber = formData.value.institutionNumber;
        paymentDetails.chequeName = formData.value.chequeName;
        paymentDetails.lenderAddress = formData.value.lenderAddress;
      }
    }

    // Append all form fields
    Object.entries(formattedData).forEach(([key, value]) => {
      if (key === 'tenancyAgreement') {
        if (value) formDataObj.append('tenancy_agreement', value);
      } else if (value !== null && value !== '') {
        formDataObj.append(key, value);
      }
    });

    // Append payment details as JSON
    formDataObj.append('payment_details', JSON.stringify(paymentDetails));

    // Submit the form
    await axios.post(`/api/addresses/update`, formDataObj, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    dialog.value = false;
    useToast().success('Address updated successfully');
    await refreshNotifications();
    emit('refresh-data');
    resetForm();
  } catch (error) {
    const status = error.response?.status;
    switch (status) {
      case 422:
        Object.values(error.response.data.errors).forEach(errors => {
          errors.forEach(error => useToast().error(error));
        });
        break;
      case 403:
        useToast().error('Edit limit reached for this year');
        break;
      case 404:
        useToast().error('Address not found');
        break;
      case 413:
        useToast().error('File size too large');
        break;
      default:
        useToast().error('An error occurred while updating the address');
    }
  } finally {
    loading.value = false;
  }
};


const debounce = (fn, delay) => {
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => fn(...args), delay);
  };
};

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

const menu = ref(false);
const dialog = ref(false)
const validationErrors = ref({});
const date = ref('');
const currentStep = ref(1)
const loading = ref(false)
const editCount = ref(0)
const lastEditDate = ref(null)
const geocodingClient = mbxGeocoding({ accessToken: 'sk.eyJ1IjoiZmFpdGhkaW5ubyIsImEiOiJjbTNlaTBpemYwZGg0MmlxeHBvdmN1Njc1In0.DxTSIOGOsItCew8yHtscJw' });

const addressInput = ref('')
const suggestions = ref([])
const cities = ref([])

const addressForm = ref(null)
const amountForm = ref(null)
const agreementForm = ref(null)
const paymentForm = ref(null);

const startMenu = ref(false);
const endMenu = ref(false);

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
  monthlyAmount: '',
  tenancyAgreement: null,
  paymentDay: '',
  startDate: null,
  endDate: null,
  paymentMethod: '',
  landlordEmail: '',
  landlordType: '',
  businessName: '',
  landlordFirstName: '',
  landlordLastName: '',
  landlordMiddleName: '',
  accountNumber: '',
  mortgageAccountNumber: '',
  chequeName: '',
  lenderName: '',
  lenderAddress: '',
  institutionNumber: '',
  transitNumber: '',
  bankAccountNumber: '',
  paymentFrequency: '',
  referenceNumber: ''
})

const steps = ref([
  { title: 'Address Info', valid: false },
  { title: 'Amount', valid: false },
  { title: 'Agreement', valid: false },
  { title: 'Payment Details', valid: false }
]);

const rules = {
  required: v => !!v || 'Field is required',
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
  fileType: v => {
    if (!v || !v.type) return true;
    const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    return allowedTypes.includes(v.type) || 'Invalid file type';
  },
  fileSize: v => {
    if (!v || !v.size) return true;
    return v.size < 10 * 1024 * 1024 || 'File size should be less than 10MB';
  },
  startDate: v => !!v || 'Start date is required',
  // startDate: v => {
  //   if (!v) return 'Start date is required';
  //   const currentDate = new Date();
  //   currentDate.setDate(1); // First day of current month
  //   const inputDate = new Date(v);
  //   return inputDate >= currentDate || 'Start date cannot be before current month';
  // },
  // endDate: v => {
  //   if (!v) return 'End date is required';
  //   if (!formData.value?.startDate) return true;

  //   const startDate = new Date(formData.value.startDate);
  //   const endDate = new Date(v);
  //   const minEndDate = new Date(startDate);
  //   minEndDate.setMonth(startDate.getMonth() + 1);

  //   return endDate >= minEndDate || 'End date must be at least 1 month after start date';
  // },
  endDate: v => {
    if (!v) return 'End date is required';
    if (!formData.value.startDate) return true;

    try {
      const startDate = new Date(formData.value.startDate);
      const endDate = new Date(v);
      const minEndDate = new Date(startDate);
      minEndDate.setMonth(startDate.getMonth() + 1);

      return endDate >= minEndDate || 'End date must be at least 1 month after start date';
    } catch (error) {
      return 'Invalid date format';
    }
  }
}

const handleDateSelection = (date, field) => {
  try {
    formData.value[field] = date ? new Date(date).toISOString().split('T')[0] : null;
  } catch (error) {
    console.error('Invalid date:', error);
    formData.value[field] = null;
  }
};

const handleStartDateChange = (date) => {
  try {
    // If date is a string (from echoed data), convert it to proper format
    if (date && typeof date === 'string') {
      const formattedDate = new Date(date).toISOString().split('T')[0];
      formData.value.startDate = formattedDate;
    } else {
      formData.value.startDate = date;
    }
  } catch (error) {
    console.error('Date handling error:', error);
    formData.value.startDate = null;
  }
};

watch(() => props.address, (newAddress) => {
  if (newAddress && dialog.value) {
    // Existing address details
    formData.value = {
      address: newAddress.name || '',
      province: newAddress.province || '',
      city: newAddress.city || '',
      postalCode: newAddress.postal_code || '',
      unitNumber: newAddress.unit_number || '',
      houseNumber: newAddress.house_number || '',
      streetName: newAddress.street_name || '',
      monthlyAmount: newAddress.amount || '',
      tenancyAgreement: null,
      paymentDay: newAddress.reoccurring_monthly_day || '',
      // startDate: newAddress.duration_from || null,
      startDate: newAddress.duration_from ? new Date(newAddress.duration_from).toISOString().split('T')[0] : null,
      // endDate: newAddress.duration_to || null,
      endDate: newAddress.duration_to ? new Date(newAddress.duration_to).toISOString().split('T')[0] : null,
    };

    // Get finance details if available
    // Payment details based on account goal
    console.log(landlordFinanceObject, newAddress);

    if (landlordFinanceObject?.payment_method) {
      const details = JSON.parse(landlordFinanceObject.details);
      formData.value.paymentMethod = landlordFinanceObject.payment_method?.toLowerCase() || '';

      if (newAddress.account_goal === 'mortgage') {
        if (landlordFinanceObject.payment_method === 'EFT') {
          // Set EFT fields
          formData.value = {
            ...formData.value,
            mortgageAccountNumber: details.accountNumber,
            lenderName: details.lenderName,
            lenderAddress: details.lenderAddress,
            institutionNumber: details.institutionNumber,
            transitNumber: details.transitNumber,
            bankAccountNumber: details.bankAccountNumber,
            paymentFrequency: details.paymentFrequency,
            referenceNumber: details.refNumber
          };
        } else if (landlordFinanceObject.payment_method === 'cheque') {
          // Set cheque fields
          formData.value = {
            ...formData.value,
            accountNumber: details.accountNumber,
            transitNumber: details.transitNumber,
            institutionNumber: details.institutionNumber,
            chequeName: details.chequeName,
            address: details.address
          };
        }
      } else if (newAddress.account_goal === 'rent') {
        // Set rent-specific fields
        formData.value.landlordType = landlordFinanceObject.landlordType;
        if (landlordFinanceObject.payment_method === 'interac') {
          formData.value.landlordEmail = details.email;
        }
        if (landlordFinanceObject.landlordType === 'business') {
          formData.value.businessName = details.businessName;
        } else {
          formData.value.landlordFirstName = details.firstName;
          formData.value.landlordLastName = details.lastName;
          formData.value.landlordMiddleName = details.middleName;
        }
      }
    }

    lenderAddressInput.value = newAddress.lenderAddress || '';
    addressInput.value = newAddress.name || '';
  }
}, { deep: true });

const showModal = () => {
  console.log('showModal called with address:', props.address);

  if (props.address) {
    console.log('Account goal:', props.address.account_goal);
    console.log('Financer data:', landlordFinanceObject);
    // ... rest of the code
  }
  if (props.address) {
    const details = JSON.parse(landlordFinanceObject.details);
    console.log(details?.accountNumber);
    const formValues = {

      address: props.address?.name ?? '',
      province: props.address?.province ?? '',
      city: props.address?.city ?? '',
      postalCode: props.address?.postal_code ?? '',
      unitNumber: props.address?.unit_number ?? '',
      houseNumber: props.address?.house_number ?? '',
      streetName: props.address?.street_name ?? '',
      monthlyAmount: props.address?.total_amount ?? '',
      tenancyAgreement: null,
      paymentDay: props.address?.reoccurring_monthly_day ?? '',
      // startDate: props.address?.duration_from ?? null,
      // endDate: props.address?.duration_to ?? null,
      // startDate: newAddress.duration_from || null,
      startDate: props.address?.duration_from ? new Date(props.address?.duration_from).toISOString().split('T')[0] : null,
      // endDate: newAddress.duration_to || null,
      endDate: props.address?.duration_to ? new Date(props.address?.duration_to).toISOString().split('T')[0] : null,


      // Initialize fields from landlordFinanceObject
      paymentMethod: landlordFinanceObject?.payment_method?.toLowerCase() || '',
      landlordEmail: details?.email || '',
      landlordType: landlordFinanceObject?.landlordType || '',
      businessName: details?.businessName || '',
      landlordFirstName: details?.firstName || '',
      landlordLastName: details?.lastName || '',
      landlordMiddleName: details?.middleName || '',
      accountNumber: details?.accountNumber || '',
      mortgageAccountNumber: details?.accountNumber || '',
      lenderName: details?.lenderName || '',
      chequeName: details?.chequeName || '',
      lenderAddress: details?.lenderAddress || '',
      institutionNumber: details?.institutionNumber || '',
      transitNumber: details?.transitNumber || '',
      bankAccountNumber: details?.bankAccountNumber || '',
      paymentFrequency: details?.paymentFrequency || '',
      referenceNumber: details?.refNumber || ''
    };

    console.log("formValues", formValues);

    formData.value = { ...formValues };
    initialFormData.value = { ...formValues }; // Store initial state
    addressInput.value = props.address?.name ?? '';
    lenderAddressInput.value = details?.lenderAddress ?? '';
  }

  dialog.value = true
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



const handleFileChange = (file) => {
  if (file) {
    formData.value.tenancyAgreement = file;
  }
};

const isLastStep = computed(() => currentStep.value === steps.value.length)

const days = computed(() => Array.from({ length: 31 }, (_, i) => i + 1))

const handleNext = async () => {
  const formRefs = {
    1: addressForm,
    2: amountForm,
    3: agreementForm,
    4: paymentForm
  };

  const currentForm = formRefs[currentStep.value];
  if (currentForm?.value) {
    const { valid } = await currentForm.value.validate();
    if (valid) {
      if (isLastStep.value) {
        await submitForm();
      } else {
        currentStep.value++;
      }
    }
  }
};


const prevStep = () => {
  currentStep.value--
}

const handleAddressInput = debounce(async () => {
  if (addressInput.value.length < 3) {
    suggestions.value = [];
    return;
  }

  try {
    const response = await geocodingClient.forwardGeocode({
      query: addressInput.value,
      countries: ['CA'],
      autocomplete: true
    }).send();

    suggestions.value = response.body.features;
  } catch (error) {
    console.error('Error fetching suggestions:', error);
  }
}, 500); // Debounce with 500ms delay

const handleProvinceChange = async () => {
  if (!formData.value.province) {
    cities.value = []
    return
  }

  try {
    const response = await axios.get(`/auth/sign-up-cities/${encodeURIComponent(formData.value.province)}`)
    cities.value = response.data || []
  } catch (error) {
    console.error('Error fetching cities:', error)
    cities.value = []
  }
}

const selectAddress = (feature) => {
  if (!feature) return

  addressInput.value = feature.place_name || ''
  suggestions.value = []
  formData.value.address = feature.place_name || ''

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

  // Extract house number and street name
  const addressParts = (feature.place_name || '').split(',')[0].trim().split(' ')
  if (addressParts.length > 1) {
    formData.value.houseNumber = addressParts[0]
    formData.value.streetName = addressParts.slice(1).join(' ')
  }
}

const handleDialogUpdate = (value) => {
  if (!value) {
    dialog.value = false;
    currentStep.value = 1;
    resetForm();
  }
};

// Reset the initial data when closing
const closeDialog = () => {
  dialog.value = false;
  setTimeout(() => {
    currentStep.value = 1;
    resetForm();
  }, 0);
  initialFormData.value = {};
};

const resetForm = () => {
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
    monthlyAmount: '',
    tenancyAgreement: null,
    paymentDay: '',
    startDate: null, // Empty string initially
    endDate: null,   // Empty string initially
    paymentMethod: '',
    // Rent fields
    landlordEmail: '',
    landlordType: '',
    businessName: '',
    landlordFirstName: '',
    landlordLastName: '',
    landlordMiddleName: '',
    // Mortgage fields
    accountNumber: '',
    mortgageAccountNumber: '',
    chequeName: '',
    lenderName: '',
    lenderAddress: '',
    institutionNumber: '',
    transitNumber: '',
    bankAccountNumber: '',
    paymentFrequency: '',
    referenceNumber: '',
  }
  steps.value.forEach(step => step.valid = false)
}
// const formatDate = (date) => new Date(date).toISOString().split('T')[0];
const formatDate = (date) => {
  return new Date(date).toISOString().split('T')[0];
}

watch(() => formData.value.startDate, (newVal) => {
  console.log('Start Date:', newVal);
});

const hasFormChanged = () => {
  // Skip file comparison if new file is selected
  if (formData.value.tenancyAgreement) return true;

  return Object.keys(formData.value).some(key => {
    // Skip tenancyAgreement in comparison
    if (key === 'tenancyAgreement') return false;

    // Convert values to strings for comparison
    const initial = String(initialFormData.value[key] || '');
    const current = String(formData.value[key] || '');

    return initial !== current;
  });
};


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

.address-container {
  position: relative;
}
</style>