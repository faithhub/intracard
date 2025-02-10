<!-- BillConfirmationModal.vue -->
<template>
    <v-dialog :model-value="show" @update:model-value="$emit('update:show')" max-width="500px" persistent>
      <v-card>
        <v-card-title class="text-h5 bg-purple text-white">
          Confirm Bill Setup
        </v-card-title>
  
        <v-card-text class="pt-4">
          <h3 class="text-h6 mb-4">Bill Summary</h3>
          
          <!-- Bill Type -->
          <div class="d-flex justify-space-between mb-2">
            <span class="font-weight-medium">Bill Type:</span>
            <span>{{ getBillTypeName(formData.bill_id) }}</span>
          </div>
  
          <!-- Amount -->
          <div class="d-flex justify-space-between mb-2">
            <span class="font-weight-medium">Amount:</span>
            <span>{{ formatAmount(billAmount) }}</span>
          </div>
  
          <!-- Service Charge -->
          <div class="d-flex justify-space-between mb-2">
            <span class="font-weight-medium">Service Charge (5%):</span>
            <span>{{ formatAmount(serviceCharge) }}</span>
          </div>
  
          <!-- Total Amount -->
          <v-divider class="my-3"></v-divider>
          <div class="d-flex justify-space-between mb-4">
            <span class="font-weight-bold">Total Amount:</span>
            <span class="font-weight-bold text-purple">{{ formatAmount(totalAmount) }}</span>
          </div>
  
          <!-- Additional Details -->
          <div class="bg-grey-lighten-4 pa-3 rounded">
            <template v-if="formData.bill_type === 'carBill'">
              <div class="mb-2">
                <strong>Car Details:</strong>
                <div class="ml-3">
                  <div>Model: {{ formData.car_model }}</div>
                  <div>Year: {{ formData.car_year }}</div>
                  <div>VIN: {{ formData.car_vin }}</div>
                  <div>Frequency: {{ formData.frequency }}</div>
                </div>
              </div>
            </template>
            <template v-else>
              <div class="mb-2">
                <strong>Bill Details:</strong>
                <div class="ml-3">
                    <div>Phone Number: {{ formData.phone_number }}</div>
                  <div>Provider: {{ getProviderName(formData.provider) }}</div>
                  <div>Account Number: {{ formData.account_number }}</div>
                  <div>Due Date: {{ formatDate(formData.due_date || formData.due_date_car) }}</div>
                </div>
              </div>
            </template>
          </div>
  
          <!-- Warning Message -->
          <v-alert type="warning" variant="tonal" class="mt-4">
            By confirming, you agree to pay the total amount including the 5% service charge.
          </v-alert>
        </v-card-text>
  
        <v-card-actions class="pb-4 px-4">
          <v-btn
            color="grey-darken-1"
            variant="flat"
            @click="onCancel"
          >
            Cancel
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            color="purple"
            :loading="loading"
            @click="onConfirm"
          >
            Confirm & Pay
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </template>
  
  <script>
  export default {
    name: 'BillConfirmationModal',
    props: {
      show: {
        type: Boolean,
        required: true
      },
      'onUpdate:show': {
        type: Function,
        required: true
      },
      formData: {
        type: Object,
        required: true
      },
      billTypes: {
        type: Array,
        required: true
      },
      providers: {
        type: Array,
        required: true
      },
      loading: {
        type: Boolean,
        default: false
      }
    },
    computed: {
      billAmount() {
        return this.formData.bill_type === 'carBill' 
          ? Number(this.formData.car_amount) 
          : Number(this.formData.amount);
      },
      serviceCharge() {
        return this.billAmount * 0.05;
      },
      totalAmount() {
        return this.billAmount + this.serviceCharge;
      }
    },
    methods: {
      formatAmount(amount) {
        return new Intl.NumberFormat('en-CA', {
          style: 'currency',
          currency: 'CAD',
          minimumFractionDigits: 2
        }).format(amount);
      },
      formatDate(date) {
        if (!date) return '';
        return new Date(date).toLocaleDateString('en-CA', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });
      },
      getBillTypeName(billId) {
        const bill = this.billTypes.find(b => b.id === billId);
        return bill ? bill.name : '';
      },
      getProviderName(providerId) {
        const provider = this.providers.find(p => p.value === providerId);
        return provider ? provider.name : '';
      },
      onCancel() {
        this.$emit('cancel');
      },
      onConfirm() {
        this.$emit('confirm', {
        ...this.formData,
        service_charge: this.serviceCharge,
        amount: this.formData.bill_type === 'carBill' ? this.formData.car_amount : this.formData.amount,
        total_amount: this.totalAmount,
        bill_id: this.formData.bill_id || null,
        user_id: this.formData.user_id || null,
        card_id: this.formData.payment_card,
        provider: this.formData.provider || null,
        bill_type: this.formData.bill_type || null,
        due_date: this.formData.due_date || this.formData.due_date_car || null,
        account_number: this.formData.account_number || null,
        frequency: this.formData.frequency || null,
        car_model: this.formData.car_model || null,
        car_year: this.formData.car_year || null,
        car_vin: this.formData.car_vin || null,
        phone: this.formData.phone || null
      });
      }
    }
  };
  </script>