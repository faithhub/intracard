<template>
    <div>
        <ContentSkeleton v-if="isSkeletonLoading" />
        <v-row v-else>
            <!-- Wallet Balance and Fund Wallet Button -->
            <v-col cols="12" md="12">
                <v-card class="calendar-card pa-4 rounded-3" elevation="10">
                    <!-- Wallet Balance Section -->
                    <div class="py-5 p-5">
                        <div class="card mb-5">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <!-- Wallet Balance -->
                                <div>
                                    <h3 class="text-gray-800 fw-bold mb-2">Wallet Balance</h3>
                                    <h1 class="text-black fw-bold">{{ formatAmount(walletBalance) }}</h1>
                                </div>

                                <!-- Buttons -->
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-secondary btn-sm me-3" @click="toggleWalletDetails">
                                        <i class="fa fa-chart-bar fs-4"></i> View Wallet
                                    </button>
                                    <button class="btn btn-primary btn-sm" @click="toggleFundWalletModal">
                                        <i class="fa fa-wallet fs-4"></i> Fund Wallet
                                    </button>
                                </div>
                            </div>
                        </div>


                        <!-- Wallet Details Section -->
                        <v-expand-transition>
                            <v-card v-if="showWalletDetails" class="mb-6">
                                <v-card-text>
                                    <v-row>
                                        <!-- Chart Column -->
                                        <v-col cols="12" md="8">
                                            <h3 class="text-h6 mb-4">Wallet Breakdown by Bill Types</h3>
                                            <v-chart class="chart" :style="{ height: '300px' }"
                                                :option="chartOptions" />
                                        </v-col>

                                        <!-- Stats Column -->
                                        <v-col cols="12" md="4">
                                            <h3 class="text-h6 mb-4">Quick Statistics</h3>
                                            <v-list>
                                                <v-list-item>
                                                    <v-list-item-title class="d-flex justify-space-between">
                                                        <span>Total Transactions</span>
                                                        <span class="font-weight-bold">{{ quickStats.totalTransactions
                                                            }}</span>
                                                    </v-list-item-title>
                                                </v-list-item>

                                                <v-list-item>
                                                    <v-list-item-title class="d-flex justify-space-between">
                                                        <span>Total Transaction Amount</span>
                                                        <span class="font-weight-bold">{{
                                                            formatAmount(quickStats.totalTransactionAmount) }}</span>
                                                    </v-list-item-title>
                                                </v-list-item>

                                                <v-list-item>
                                                    <v-list-item-title class="d-flex justify-space-between">
                                                        <span>Total Charges</span>
                                                        <span class="font-weight-bold">{{
                                                            formatAmount(quickStats.totalCharges)
                                                            }}</span>
                                                    </v-list-item-title>
                                                </v-list-item>

                                                <v-list-item>
                                                    <v-list-item-title class="d-flex justify-space-between">
                                                        <span>Total Allocated</span>
                                                        <span class="font-weight-bold">{{
                                                            formatAmount(quickStats.totalAllocated)
                                                            }}</span>
                                                    </v-list-item-title>
                                                </v-list-item>

                                                <v-list-item>
                                                    <v-list-item-title class="d-flex justify-space-between">
                                                        <span>Available Balance</span>
                                                        <span class="font-weight-bold">{{
                                                            formatAmount(quickStats.availableBalance)
                                                            }}</span>
                                                    </v-list-item-title>
                                                </v-list-item>
                                            </v-list>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>
                        </v-expand-transition>

                        <!-- Transactions -->
                        <!-- Replace the existing transactions table section with this Vuetify implementation -->

                        <!-- Transactions -->
                        <v-card class="mb-5 mb-xl-12">
                            <v-card-title class="pb-0">
                                <h3 class="m-0">Transactions</h3>
                            </v-card-title>

                            <v-card-text class="pt-0">
                                <v-data-table :headers="transactionHeaders" :items="transactions" :items-per-page="5"
                                    :footer-props="{
                                        'items-per-page-options': [5, 10, 15, 20],
                                        'show-current-page': true,
                                        'show-first-last-page': true
                                    }" item-key="uuid" class="elevation-0">
                                    <!-- Custom rendering for Transaction ID column -->
                                    <template v-slot:item.uuid="{ item }">
                                        {{ item.uuid }}
                                    </template>

                                    <!-- Custom rendering for Date column -->
                                    <template v-slot:item.created_at="{ item }">
                                        {{ formatDate(item.created_at) }}
                                    </template>

                                    <!-- Custom rendering for Amount column -->
                                    <template v-slot:item.amount="{ item }">
                                        {{ formatAmount(item.amount) }}
                                    </template>

                                    <!-- Custom rendering for Transaction Type column -->
                                    <template v-slot:item.type="{ item }">
                                        <v-chip :color="item.type === 'inbound' ? 'light-green' : 'orange-lighten-1'"
                                            small class="px-2 py-1 ">
                                            {{ item.type === 'inbound' ? 'Inbound' : 'Outbound' }}
                                            <v-icon size="small" class="ml-1">
                                                {{ item.type === 'inbound' ? 'mdi-arrow-down' : 'mdi-arrow-up' }}
                                            </v-icon>
                                        </v-chip>
                                    </template>

                                    <!-- Custom rendering for Status column -->
                                    <template v-slot:item.status="{ item }">
                                        <v-chip :color="getStatusChipColor(item.status)" small>
                                            {{ capitalizeFirstLetter(item.status) }}
                                        </v-chip>
                                    </template>

                                    <!-- Custom rendering for Actions column -->
                                    <template v-slot:item.actions="{ item }">
                                        <v-btn color="deep-purple" variant="text" size="small"
                                            @click="showTransactionDetails(item)">
                                            View
                                        </v-btn>
                                    </template>
                                </v-data-table>
                            </v-card-text>
                        </v-card>
                    </div>
                </v-card>
            </v-col>

            <!-- Transaction Details Modal -->
            <v-dialog v-model="showTransactionDetailsModal" max-width="500px" transition="dialog-transition">
                <v-card>
                    <!-- Modal Header -->
                    <v-card-title class="d-flex justify-space-between align-center">
                        <span>Transaction Details</span>
                        <v-btn icon variant="text" @click="toggleTransactionDetailsModal">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-card-title>

                    <!-- Modal Body -->
                    <v-card-text>
                        <v-list>
                            <v-list-item>
                                <template v-slot:prepend>
                                    <span class="font-weight-bold me-2">Transaction ID:</span>
                                </template>
                                <v-list-item-title>{{ selectedTransaction.uuid }}</v-list-item-title>
                            </v-list-item>

                            <v-list-item>
                                <template v-slot:prepend>
                                    <span class="font-weight-bold me-2">Date:</span>
                                </template>
                                <v-list-item-title>{{ formatDate(selectedTransaction.created_at) }}</v-list-item-title>
                            </v-list-item>

                            <v-list-item>
                                <template v-slot:prepend>
                                    <span class="font-weight-bold me-2">Amount:</span>
                                </template>
                                <v-list-item-title>{{ formatAmount(selectedTransaction.amount) }}</v-list-item-title>
                            </v-list-item>

                            <v-list-item>
                                <template v-slot:prepend>
                                    <span class="font-weight-bold me-2">Service:</span>
                                </template>
                                <v-list-item-title>{{ selectedTransaction.service_name }}</v-list-item-title>
                            </v-list-item>

                            <v-list-item>
                                <template v-slot:prepend>
                                    <span class="font-weight-bold me-2">Type:</span>
                                </template>
                                <v-list-item-title>{{ capitalizeFirstLetter(selectedTransaction.type)
                                    }}</v-list-item-title>
                            </v-list-item>

                            <v-list-item>
                                <template v-slot:prepend>
                                    <span class="font-weight-bold me-2">Status:</span>
                                </template>
                                <v-list-item-title>{{ capitalizeFirstLetter(selectedTransaction.status)
                                    }}</v-list-item-title>
                            </v-list-item>

                            <v-list-item>
                                <template v-slot:prepend>
                                    <span class="font-weight-bold me-2">Charge:</span>
                                </template>
                                <v-list-item-title>{{ formatAmount(selectedTransaction.charge) }}</v-list-item-title>
                            </v-list-item>

                            <!-- <v-list-item>
                                <template v-slot:prepend>
                                    <span class="font-weight-bold me-2">Details:</span>
                                </template>
                                <v-list-item-title>{{ selectedTransaction.details }}</v-list-item-title>
                            </v-list-item> -->
                        </v-list>
                    </v-card-text>

                    <!-- Modal Footer -->
                    <v-card-actions class="d-flex justify-end pa-4">
                        <v-btn color="grey-darken-1" variant="text" @click="toggleTransactionDetailsModal">
                            Close
                        </v-btn>
                        <v-btn color="primary" prepend-icon="mdi-download" @click="downloadTransactionReceipt">
                            Download Receipt
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>



            <!-- Fund Wallet Modal -->
            <v-dialog v-model="isFundWalletModalOpen" max-width="500">
                <v-card>
                    <v-card-title class="text-h6">Fund Wallet</v-card-title>
                    <v-card-text>
                        <v-form @submit.prevent="validateAndSubmitFundWallet">
                            <!-- Amount Input -->
                            <v-text-field v-model="fundWallet.amount" label="Enter Amount" outlined dense required
                                @input="calculateCharges" :error-messages="fundWalletErrors.amount"></v-text-field>

                            <!-- Show charges and total when amount is entered -->
                            <div v-if="fundWallet.amount" class="pa-3 mb-2 rounded bg-purple-lighten-5">
                                <div class="d-flex justify-space-between mb-1">
                                    <span class="text-body-2">Amount:</span>
                                    <span class="text-body-2">{{ formatToCAD(parseFloat(fundWallet.amount).toFixed(2))
                                        }}</span>
                                </div>
                                <div class="d-flex justify-space-between mb-1">
                                    <span class="text-body-2">Charges (5%):</span>
                                    <span class="text-body-2">{{ formatToCAD(transactionCharges.toFixed(2)) }}</span>
                                </div>
                                <v-divider class="my-2"></v-divider>
                                <div class="d-flex justify-space-between">
                                    <span class="text-body-1 font-weight-bold">Total:</span>
                                    <span class="text-body-1 font-weight-bold">{{ formatToCAD(totalAmount.toFixed(2))
                                        }}</span>
                                </div>
                            </div>

                            <!-- Services Dropdown -->
                            <!-- <v-select v-model="fundWallet.service" :items="billTypes" item-title="name" item-value="id"
                                label="Select Service" outlined dense required
                                :error-messages="fundWalletErrors.service" @update:model-value="clearError('service')">
                                <template v-slot:item="{ props, item }">
                                    <v-list-item v-bind="props" :title="item.raw.name"></v-list-item>
                                </template>
                            </v-select> -->

                            <v-select v-model="fundWallet.service" :items="billTypes" item-title="name" item-value="id"
                                label="Select Service" outlined dense required
                                :error-messages="fundWalletErrors.service" @update:model-value="clearError('service')">
                                <template v-slot:item="{ props, item }">
                                    <v-list-item v-bind="props" :disabled="!item.raw.is_setup"
                                        :class="{ 'text-grey': !item.raw.is_setup }">
                                        <v-list-item-title>
                                            <!-- {{ item.raw.name }} -->
                                            <span v-if="!item.raw.is_setup"
                                                class="text-caption text-grey-darken-1 ms-2">
                                                (Not set up)
                                            </span>
                                        </v-list-item-title>
                                    </v-list-item>
                                </template>
                            </v-select>
                            <!-- Payment Card Selection -->
                            <!-- <v-divider class="my-4"></v-divider> -->
                            <h5 class="mb-3 mt-5" style="font-size: 13px;">Select Payment Card</h5>
                            <!-- Payment Card Selection -->
                            <div v-if="isLoading">Loading cards...</div>
                            <div v-else>
                                <div class="mb-2">
                                    <!-- Loop through cards dynamically -->
                                    <label v-for="card in cards" :key="card.id"
                                        class="d-flex flex-stack mb-5 cursor-pointer">
                                        <span class="d-flex align-items-center me-2">
                                            <!-- Card Logo -->
                                            <span class="symbol symbol-50px me-6">
                                                <span class="symbol-label">
                                                    <img :src="card.logoUrl" :alt="card.type" class="h-40px" />
                                                </span>
                                            </span>
                                            <!-- Card Details -->
                                            <span class="d-flex flex-column">
                                                <span class="fw-bold text-gray-800 text-hover-primary fs-5">
                                                    {{ card.name }} **** {{ card.lastFourDigits }}
                                                </span>
                                                <span class="small-fs fw-semibold text-muted">
                                                    Expires {{ card.expiryMonth }}/{{ card.expiryYear }}
                                                </span>
                                                <span class="small-fs fw-semibold text-muted">
                                                    Card Limit:
                                                    <span class="fw-bold text-gray-800">
                                                        {{ formatToCAD(card.limit.toLocaleString()) }}
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                        <!-- Radio Button -->
                                        <span class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" name="payment_card"
                                                :value="card.id" v-model="fundWallet.card"
                                                @change="onCardSelect(card)" />
                                        </span>
                                    </label>
                                    <!-- Error Display -->
                                    <small v-if="fundWalletErrors.card" class="v-messages__message"
                                        style="color: rgb(var(--v-theme-error));">{{
                                            fundWalletErrors.card
                                        }}</small>
                                </div>
                            </div>

                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <!-- <v-btn color="purple" @click="submitFundWalletForm">Confirm</v-btn> -->
                        <v-btn :loading="isSubmitting" :disabled="isSubmitting" @click="validateAndSubmitFundWallet"
                            color="purple" variant="elevated">
                            <v-icon v-if="isSubmitting" left>mdi-loading mdi-spin</v-icon>
                            <span>{{ isSubmitting ? 'Processing...' : 'Fund Wallet' }}</span>
                        </v-btn>

                        <v-btn color="secondary" variant="outlined" @click="toggleFundWalletModal">Cancel</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!-- Confirmation Modal -->
            <v-dialog v-model="showConfirmationModal" max-width="400">
                <v-card>
                    <v-card-title class="text-h6">
                        Confirm Transaction
                        <v-btn icon @click="toggleConfirmationModal" class="float-right">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-card-title>
                    <v-card-text>
                        <div class="pa-3">
                            <div class="d-flex justify-space-between mb-2">
                                <strong>Amount:</strong>
                                <span>${{ parseFloat(fundWallet.amount).toFixed(2) }}</span>
                            </div>
                            <div class="d-flex justify-space-between mb-2">
                                <strong>Bill Type:</strong>
                                <span>{{ getBillName(fundWallet.service) }}</span>
                            </div>
                            <div class="d-flex justify-space-between mb-2">
                                <strong>Card:</strong>
                                <span>{{ getCardDetails(fundWallet.card) }}</span>
                            </div>
                            <div class="d-flex justify-space-between mb-2">
                                <strong>Charges:</strong>
                                <span>${{ transactionCharges.toFixed(2) }}</span>
                            </div>
                            <v-divider class="my-2"></v-divider>
                            <div class="d-flex justify-space-between font-weight-bold">
                                <strong>Total:</strong>
                                <span>${{ totalAmount.toFixed(2) }}</span>
                            </div>
                        </div>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="grey darken-1" variant="outlined" text
                            @click="toggleConfirmationModal">Cancel</v-btn>
                        <v-btn color="purple" variant="elevated" :loading="isSubmitting" :disabled="isSubmitting"
                            @click="submitFundWalletForm">
                            {{ isSubmitting ? 'Processing...' : 'Confirm' }}
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
import axios from "axios";
import Chart from 'chart.js/auto';
import { useNotifications } from '@/stores/useNotifications';

import { use } from 'echarts/core';
import { CanvasRenderer } from 'echarts/renderers';
import { PieChart } from 'echarts/charts';
import { LegendComponent, TooltipComponent } from 'echarts/components';
import VChart from 'vue-echarts';
import ContentSkeleton from '@/components/skeleton/wallet.vue'

use([
    CanvasRenderer,
    PieChart,
    LegendComponent,
    TooltipComponent,
]);
export default {
    components: {
        VChart,
        ContentSkeleton,
    },
    data() {
        const { unreadCount, refresh } = useNotifications();
        return {
            isSkeletonLoading: true,
            allocations: [],
            unreadCount,
            refreshNotifications: refresh,
            showTransactionDetailsModal: false, // Control visibility of the modal
            selectedTransaction: {
                uuid: '',
                created_at: '',
                amount: '',
                service_name: '',
                type: '',
                status: '',
                charge: '',
                details: '',
            },
            walletBalance: 0, // Wallet balance fetched from backend
            walletDetails: [], // Wallet transactions fetched from backend
            showWalletDetails: false, // Toggle wallet details
            isFundWalletModalOpen: false, // Modal state
            fundWallet: {
                amount: null,
                service: "",
                card: null,
            },
            isLoading: false,
            formattedAmount: '', // Formatted CAD value
            typedAmount: '', // Value user is typing (unformatted)
            fundWalletErrors: {}, // Validation errors
            isSubmitting: false, // Tracks form submission state
            cards: [],
            transactionHeaders: [
                { title: 'Transaction ID', key: 'uuid', width: '17%' },
                { title: 'Date', key: 'created_at', width: '17%' },
                { title: 'Amount', key: 'amount', width: '15%' },
                { title: 'Bill Type', key: 'service_name', width: '10%' },
                { title: 'Transaction Type', key: 'type', width: '18%' },
                { title: 'Status', key: 'status', width: '5%' },
                { title: 'Actions', key: 'actions', width: '10%', sortable: false, align: 'end' }
            ],
            billTypes: [], // Holds the bill types fetched from the server
            transactionCharges: 0, // Stores calculated charges
            totalAmount: 0, // Total amount (amount + charges)
            showConfirmationModal: false, // Toggle confirmation modal
            transactions: [],
            chargePercentage: 5, // Will be populated from API
            chartOptions: {
                tooltip: {
                    trigger: 'item',
                    formatter: '{b}: ${c} ({d}%)'
                },
                legend: {
                    orient: 'horizontal',  // Changed to horizontal for better layout
                    bottom: '0',          // Positioned at bottom
                    textStyle: {
                        fontWeight: 'bold'  // Makes all legend text bold
                    },
                    padding: 15
                },
                series: [{
                    type: 'pie',
                    radius: ['40%', '70%'],  // Adjusted inner and outer radius for thicker donut
                    avoidLabelOverlap: true,
                    label: {
                        show: false
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontWeight: 'bold',
                            fontSize: '14'
                        }
                    },
                    data: []
                }]
            },
        };
    },
    methods: {
        formatAmount(amount) {
            return new Intl.NumberFormat("en-CA", {
                style: "currency",
                currency: "CAD",
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(amount);
        },
        // Add this method to your methods section
        getStatusChipColor(status) {
            switch (status.toLowerCase()) {
                case 'completed':
                    return 'light-green';
                case 'pending':
                    return 'amber';
                case 'failed':
                    return 'red';
                default:
                    return 'grey';
            }
        },

        // Your existing methods can stay as is
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        calculateCharges() {
            // Clear previous errors
            this.clearError('amount');

            // Check if amount exists and is a number
            if (!this.fundWallet.amount) {
                this.fundWalletErrors.amount = "Please enter an amount.";
                this.transactionCharges = 0;
                this.totalAmount = 0;
                return;
            }

            const amount = parseFloat(this.fundWallet.amount);

            // Validate amount is positive
            if (isNaN(amount) || amount <= 0) {
                this.fundWalletErrors.amount = "Please enter a valid amount.";
                this.transactionCharges = 0;
                this.totalAmount = 0;
                return;
            }

            // Calculate charges
            this.transactionCharges = (amount * this.chargePercentage) / 100;
            this.totalAmount = amount + this.transactionCharges;

            // Check card limit if card is selected
            if (this.fundWallet.card) {
                const selectedCard = this.cards.find(c => c.id === this.fundWallet.card);
                if (selectedCard && this.totalAmount > selectedCard.limit) {
                    this.fundWalletErrors.amount = `Total amount ($${this.totalAmount.toFixed(2)}) exceeds card limit of $${selectedCard.limit}`;
                }
            }
        },
        // Validate and Submit the Fund Wallet Form
        validateAndSubmitFundWallet() {
            this.fundWalletErrors = {}; // Clear all errors
            let hasErrors = false;

            // Amount validation
            if (!this.fundWallet.amount) {
                this.fundWalletErrors.amount = "Please enter an amount.";
                hasErrors = true;
            }

            // Service validation
            if (!this.fundWallet.service) {
                this.fundWalletErrors.service = "Please select a service.";
                hasErrors = true;
            }

            // Card validation
            if (!this.fundWallet.card) {
                this.fundWalletErrors.card = "Please select a payment card.";
                hasErrors = true;
            }

            // Calculate charges and check limits
            if (!hasErrors) {
                this.calculateCharges();
                // Check if calculateCharges found any errors
                if (this.fundWalletErrors.amount) {
                    hasErrors = true;
                }
            }

            if (!hasErrors) {
                this.toggleConfirmationModal();
            }
        },
        onCardSelect(selectedCard) {
            this.clearError('card');
            this.calculateCharges(); // This will handle card limit validation
        },
        async submitFundWalletForm() {
            this.isSubmitting = true; // Show spinner and disable buttons

            try {
                // Submit form data to the backend
                const response = await axios.post("/api/wallet/fund", this.fundWallet);

                if (response.data.success) {
                    // Update wallet balance and reset form
                    this.walletBalance = response.data.newBalance;

                    // Re-fetch wallet details and transactions
                    await this.fetchWalletData();
                    this.resetFundWalletForm();
                    this.transactions = response.data.transactions; // Dynamically update transactions
                    this.handleCancel();
                    this.toggleConfirmationModal(); // Close confirmation modal
                    await this.refreshNotifications(); // Refresh notifications
                    useToast().success(response.data.message || "Wallet funded successfully.");
                }
            } catch (error) {
                // Handle backend errors
                if (error.response && error.response.data.errors) {
                    const errors = error.response.data.errors;

                    // Map backend errors to form fields
                    if (errors.amount) {
                        this.fundWalletErrors.amount = Array.isArray(errors.amount) ?
                            errors.amount[0] :
                            errors.amount;
                    }
                    if (errors.service) {
                        this.fundWalletErrors.service = Array.isArray(errors.service) ?
                            errors.service[0] :
                            errors.service;
                    }
                    if (errors.card) {
                        this.fundWalletErrors.card = Array.isArray(errors.card) ?
                            errors.card[0] :
                            errors.card;
                    }

                    if (!Object.keys(errors).length) {
                        useToast().error("An unexpected error occurred. Please try again.");
                    }
                } else {
                    useToast().error("An unexpected error occurred. Please try again.");
                }
            } finally {
                this.isSubmitting = false; // Hide spinner and re-enable buttons
            }
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
            });
        },
        getTransactionTypeClass(type) {
            return type === 'inbound' ? 'badge badge-light-success text-black' : 'badge badge-light-warning text-black';
        },
        getTransactionIconClass(type) {
            return type === 'inbound' ? 'fa fa-arrow-down text-success' : 'fa fa-arrow-up text-warning';
        },
        getStatusClass(status) {
            if (status === 'completed') return 'badge badge-light-success';
            if (status === 'pending') return 'badge badge-light-warning';
            return 'badge badge-light-danger';
        },
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        toggleTransactionDetailsModal() {
            this.showTransactionDetailsModal = !this.showTransactionDetailsModal;
        },

        showTransactionDetails(transaction) {
            if (!transaction || !transaction.uuid) {
                console.error('Invalid transaction data passed to modal:', transaction);
                return;
            }
            this.selectedTransaction = transaction; // Set the selected transaction
            this.toggleTransactionDetailsModal(); // Open the modal
        },
        async downloadTransactionReceipt() {
            const { uuid, created_at, amount, service_name, type, status, charge, details } = this.selectedTransaction;

            // Create a new jsPDF instance
            const doc = new jsPDF();

            // Add content to the PDF
            doc.setFontSize(18);
            doc.text('Transaction Receipt', 10, 10);

            doc.setFontSize(12);
            doc.text(`Transaction ID: ${uuid}`, 10, 30);
            doc.text(`Date: ${this.formatDate(created_at)}`, 10, 40);
            doc.text(`Amount: ${this.formatAmount(amount)}`, 10, 50);
            doc.text(`Service: ${service_name}`, 10, 60);
            doc.text(`Type: ${this.capitalizeFirstLetter(type)}`, 10, 70);
            doc.text(`Status: ${this.capitalizeFirstLetter(status)}`, 10, 80);
            doc.text(`Charge: ${this.formatAmount(charge)}`, 10, 90);
            doc.text(`Details: ${details}`, 10, 100);

            // Save the PDF
            doc.save(`Transaction_${uuid}.pdf`);
        },
        getBillColor(billType) {
            const colorMap = {
                internetBill: '#4CAF50',
                mortgage: '#2196F3',
                rent: '#FFC107',
                phoneBill: '#FF5722',
                carBill: '#9C27B0',
                utilityBill: '#00BCD4',

                // Add more bill types and colors as needed
            };
            return colorMap[billType] || '#999999'; // Default color if type not found
        },
        updateChartData(allocations) {
            // Group allocations by bill type and sum their amounts
            const billGroups = allocations.reduce((acc, allocation) => {
                const billName = allocation.bill.name;
                if (!acc[billName]) {
                    acc[billName] = {
                        allocated: 0,
                        color: this.getBillColor(allocation.bill.value)
                    };
                }
                acc[billName].allocated += parseFloat(allocation.allocated_amount);
                return acc;
            }, {});

            // Transform the grouped data into the format needed for the chart
            const chartData = Object.entries(billGroups).map(([name, data]) => ({
                name: name,
                value: data.allocated,
                itemStyle: { color: data.color }
            }));

            // Update the chart series data
            this.chartOptions.series[0].data = chartData;
        },
        // Fetch wallet data
        async fetchWalletData() {
            try {
                this.isSkeletonLoading = true;
                const response = await axios.get('/api/wallet');
                if (response.data?.success) {
                    const { wallet_balance, transactions, allocations } = response.data.data;
                    this.walletBalance = wallet_balance;
                    this.transactions = transactions || [];
                    this.updateChartData(allocations);
                }
            } catch (error) {
                useToast().error(error.response?.data?.message || 'Failed to fetch wallet data');
            } finally {
                this.isSkeletonLoading = false;
            }
        },

        getWalletBreakdownByService() {
            const breakdown = {};

            this.walletDetails.forEach((transaction) => {
                const serviceName = transaction.service_name || "Unknown Service";

                if (!breakdown[serviceName]) {
                    breakdown[serviceName] = 0;
                }

                breakdown[serviceName] += parseFloat(transaction.amount.replace('$', '').replace(',', ''));
            });

            return {
                labels: Object.keys(breakdown),
                data: Object.values(breakdown),
            };
        },
        getWalletBreakdownByBillTypes() {
            if (!Array.isArray(this.walletDetails)) {
                console.error("walletDetails is not an array:", this.walletDetails);
                return {
                    labels: [],
                    data: []
                }; // Return default empty data
            }

            const breakdown = {};

            console.log(this.walletDetails)
            this.walletDetails.forEach((transaction) => {
                // Assume the `details` column contains the bill type in the JSON
                const details = transaction.details ? JSON.parse(transaction.details) : {};
                const billType = details.service || "Unknown"; // Use "Unknown" for missing bill type

                if (!breakdown[billType]) {
                    breakdown[billType] = 0;
                }
                breakdown[billType] += transaction.amount;
            });

            // Convert breakdown object to labels and data for the chart
            const labels = Object.keys(breakdown);
            const data = Object.values(breakdown);

            console.log(labels, data, this.walletDetails)
            return {
                labels,
                data
            };
        },
        // Toggle wallet details visibility
        toggleWalletDetails() {
            this.showWalletDetails = !this.showWalletDetails;

            // if (this.showWalletDetails) {
            //     this.fetchWalletData(); // Fetch wallet data
            //     this.$nextTick(() => {
            //         this.renderWalletChart(); // Render chart after data is fetched
            //     });
            // }
        },
        // Update raw value and allow free typing
        onAmountInput(event) {
            const rawValue = event.target.value.replace(/[^0-9.]/g, ''); // Remove invalid characters
            this.typedAmount = rawValue; // Update display value
            this.fundWallet.amount = parseFloat(rawValue) || ''; // Update raw numeric value

            // Clear the amount error if it exists
            if (this.fundWalletErrors.amount) {
                delete this.fundWalletErrors.amount;
            }
        },

        // Format amount on blur
        formatAmountOnBlur() {
            if (!this.typedAmount) return; // Skip formatting if empty
            const numericValue = parseFloat(this.typedAmount).toFixed(2);
            if (!isNaN(numericValue)) {
                this.typedAmount = this.formatToCAD(numericValue); // Format for display
                this.fundWallet.amount = parseFloat(numericValue); // Keep raw value accurate
            }
        },

        // Utility: Format numeric value to CAD
        formatToCAD(value) {
            return new Intl.NumberFormat('en-CA', {
                style: 'currency',
                currency: 'CAD',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(value);
        },
        toggleModal() {
            if (!this.isFundWalletModalOpen) {
                this.resetFundWalletForm();
            }
            this.isFundWalletModalOpen = !this.isFundWalletModalOpen;
        },
        async fetchBillTypes() {
            try {
                const response = await axios.get('/api/bill-types');
                this.billTypes = response.data; // Assume the response is an array of bill types
            } catch (error) {
                console.error("Error fetching bill types:", error);
                useToast().error("Failed to load bill types. Please try again.");
            }
        },
        // Fetch cards from the API
        async fetchCards() {
            this.isLoading = true;
            try {
                const response = await axios.get("/api/cards");

                // Process the response data
                if (response.data && typeof response.data === "object") {
                    // Convert the numbered object keys into an array
                    const rawCards = Object.values(response.data);
                    console.log(rawCards);

                    this.cards = rawCards.map((card) => ({
                        id: card.id,
                        name: card.name,
                        type: card.type || "Unknown", // Default to "Unknown" if type is missing
                        limit: card.limit || 0, // Default to "Unknown" if type is missing
                        lastFourDigits: card.number.slice(-4), // Extract the last 4 digits of the card number
                        expiryMonth: card.expiryMonth,
                        expiryYear: card.expiryYear,
                        isPrimary: card.is_primary || false, // Check if the card is primary
                        logoUrl: card.logoUrl || this.getCardLogo(card.type), // Use default logo if not provided
                    }));
                } else {
                    console.error("Unexpected response format:", response);
                    this.cards = []; // Fallback to an empty array
                }
            } catch (error) {
                console.error("Failed to fetch cards:", error);
                this.cards = []; // Reset cards on error
            } finally {
                this.isLoading = false;
            }
        },
        // Helper function to get card logo URL if not provided
        getCardLogo(type) {
            if (!type || typeof type !== "string") {
                // Return a default card logo if type is missing or invalid
                return "https://example.com/default-card.png";
            }

            switch (type.toLowerCase()) {
                case "visa":
                    return "https://example.com/visa.png";
                case "mastercard":
                    return "https://example.com/mastercard.png";
                default:
                    return "https://example.com/default-card.png";
            }
        },
        // Toggle the Fund Wallet Modal
        toggleFundWalletModal() {
            this.isFundWalletModalOpen = !this.isFundWalletModalOpen;
            if (!this.isFundWalletModalOpen) {
                this.resetFundWalletForm();
            }
        },

        // Reset the Fund Wallet Form
        resetFundWalletForm() {
            this.fundWallet = {
                amount: null,
                service: "",
                card: null,
            };
            this.fundWalletErrors = {};
        },

        handleCancel() {
            this.resetFundWalletForm(); // Clear form data and errors
            this.toggleFundWalletModal(); // Close the modal
        },
        // Clear Specific Error
        clearError(field) {
            if (this.fundWalletErrors[field]) {
                delete this.fundWalletErrors[field];
            }
        },
        getBillName(service) {
            const bill = this.billTypes.find((b) => b.id === service);
            console.log(bill, 'bill', service)
            return bill ? bill.name : "Unknown";
        },
        getCardDetails(cardId) {
            const card = this.cards.find((c) => c.id === cardId);
            return card ?
                `${card.type} **** ${card.lastFourDigits} (Expires ${card.expiryMonth}/${card.expiryYear})` :
                "Unknown Card";
        },
        toggleConfirmationModal() {
            this.showConfirmationModal = !this.showConfirmationModal;
        },

    },
    // Fetch cards when the component is mounted
    async mounted() {
        try {
            await this.fetchWalletData()
            await this.fetchCards()
            await this.fetchBillTypes()
        } finally {
            this.isSkeletonLoading = false
        }
    },
    watch: {
        'fundWallet.amount'(newVal) {
            if (!newVal) {
                this.transactionCharges = 0;
                this.totalAmount = 0;
            }
        }
    },
    computed: {
        quickStats() {
            return {
                totalTransactions: this.transactions?.length || 0,
                totalTransactionAmount: this.transactions?.reduce((sum, tx) =>
                    sum + (parseFloat(tx.amount) || 0), 0).toFixed(2) || '0.00',
                totalCharges: this.transactions?.reduce((sum, tx) =>
                    sum + (parseFloat(tx.charge) || 0), 0).toFixed(2) || '0.00',
                totalAllocated: this.allocations?.reduce((sum, alloc) =>
                    sum + (parseFloat(alloc.allocated_amount) || 0), 0)?.toFixed(2) || '0.00',
                availableBalance: this.walletBalance || 0
            }
        }
    },
};
</script>

<style>
.card-container {
    border-bottom: 1px solid #e0e0e0;
    transition: background-color 0.2s ease;
}

.card-container:last-child {
    border-bottom: none;
}

.card-container:hover {
    background-color: #fafafa;
}

/* Adjust radio button size and alignment */
:deep(.v-input--radio-group__input) {
    margin: 0;
    padding: 0;
}

:deep(.v-radio) {
    margin: 0;
    padding: 0;
}

.custom-modal-width {
    margin-left: 1rem;
    margin-right: 1rem;
    max-width: 600px;
    /* Adjust the width as needed */
    width: 100%;
}

.modal-overlay-confirm {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 3000;
}

.small-fs {
    font-size: 13px !important;
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

.chart {
    min-height: 300px;
}

.modal-content {
    background: #fff;
    border-radius: 10px;
    /* min-width: 400px; */
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
</style>
