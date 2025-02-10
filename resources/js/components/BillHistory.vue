<template>
    <v-col cols="12">
        <v-card class="calendar-card pa-4" elevation="10">
            <div class="d-flex justify-space-between align-center mb-4">
                <h3 class="text-h6 font-weight-bold">Bill History</h3>
                <v-btn color="primary" @click="fetchBillHistories" :loading="loading" icon="mdi-refresh"></v-btn>
            </div>

            <!-- Loading Skeleton -->
            <div v-if="loading" class="py-3">
                <v-skeleton-loader v-for="n in 3" :key="n" type="list-item-two-line" class="mb-2"></v-skeleton-loader>
            </div>

            <!-- Error State -->
            <v-alert v-else-if="error" type="error" class="mb-4">
                {{ error }}
            </v-alert>

            <!-- Empty State -->
            <v-alert v-else-if="!billHistories.length" type="primary-light" variant="text" class="mb-4">
                No bill history found.
            </v-alert>
            <!-- Empty State -->
            <v-alert v-else-if="!billHistories.length" color="primary" variant="text" class="mb-4">
                No bill history found.
            </v-alert>

            <!-- Bill History Table -->
            <v-table v-else>
                <thead>
                    <tr>
                        <th>Bill Type</th>
                        <th>Amount</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="bill in billHistories" :key="bill.uuid">
                        <td>{{ bill.bill_type }}</td>
                        <td>{{ formatAmount(bill.amount) }}</td>
                        <td>{{ formatDate(bill.due_date) }}</td>
                        <td>
                            <v-chip :color="bill.status === 'active' ? 'success' : 'error'" size="small">
                                {{ bill.status }}
                            </v-chip>
                        </td>
                        <td>
                            <v-btn color="primary" variant="text" size="small" class="me-2" @click="viewDetails(bill)">
                                View
                            </v-btn>
                            <v-btn color="error" variant="text" size="small" @click="confirmDelete(bill)"
                                :loading="deletingId === bill.uuid">
                                Delete
                            </v-btn>
                        </td>
                    </tr>
                </tbody>
            </v-table>

        </v-card>

        <!-- Delete Confirmation Dialog -->
        <v-dialog v-model="showDeleteDialog" max-width="400px">
            <v-card>
                <v-card-title class="text-h6">
                    Delete Bill History
                </v-card-title>
                <v-card-text>
                    Are you sure you want to delete this bill history? This action cannot be undone.
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="grey-darken-1" variant="text" @click="showDeleteDialog = false">
                        Cancel
                    </v-btn>
                    <v-btn color="error" variant="text" @click="deleteBillHistory" :loading="isDeleting">
                        Delete
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- View Details Modal -->
        <!-- View Details Modal -->
        <v-dialog v-model="showViewDialog" max-width="600px">
            <v-card v-if="selectedBill">
                <v-card-title class="text-h6 bg-primary-light text-white pa-4">
                    Bill Details
                    <v-chip :color="selectedBill.status === 'active' ? 'success' : 'error'" size="small" class="ml-2">
                        {{ selectedBill.status }}
                    </v-chip>
                </v-card-title>

                <v-card-text class="pa-4">
                    <v-row>
                        <!-- Basic Information -->
                        <v-col cols="12">
                            <h3 class="text-h6 mb-3">Basic Information</h3>
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex justify-space-between">
                                    <span class="font-weight-medium">Bill Type:</span>
                                    <span>{{ selectedBill.bill_type }}</span>
                                </div>
                                <div class="d-flex justify-space-between">
                                    <span class="font-weight-medium">Amount:</span>
                                    <span class="text-primary">{{ formatAmount(selectedBill.amount) }}</span>
                                </div>
                                <div class="d-flex justify-space-between">
                                    <span class="font-weight-medium">Due Date:</span>
                                    <span>{{ formatDate(selectedBill.due_date) }}</span>
                                </div>
                                <div class="d-flex justify-space-between" v-if="selectedBill.provider">
                                    <span class="font-weight-medium">Provider:</span>
                                    <span>{{ getProviderName(selectedBill.provider) }}</span>
                                </div>
                                <!-- Show phone number only for Phone and Internet bills -->
                                <div v-if="['Phone Bill', 'Internet Bill'].includes(selectedBill.bill_type)"
                                    class="d-flex justify-space-between">
                                    <span class="font-weight-medium">Phone:</span>
                                    <span>{{ selectedBill.phone || 'N/A' }}</span>
                                </div>
                                <!-- Show frequency only if it's not monthly -->
                                <div v-if="selectedBill.frequency && selectedBill.frequency !== 'monthly'"
                                    class="d-flex justify-space-between">
                                    <span class="font-weight-medium">Frequency:</span>
                                    <span>{{ selectedBill.frequency }}</span>
                                </div>
                            </div>
                        </v-col>

                        <!-- Payment Information -->
                        <v-col cols="12">
                            <h3 class="text-h6 mb-3">Payment Information</h3>
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex justify-space-between">
                                    <span class="font-weight-medium">Card Type:</span>
                                    <span>{{ selectedBill.card_info.type }}</span>
                                </div>
                                <div class="d-flex justify-space-between">
                                    <span class="font-weight-medium">Card Number:</span>
                                    <span>**** {{ selectedBill.card_info.last_four }}</span>
                                </div>
                                <div class="d-flex justify-space-between">
                                    <span class="font-weight-medium">Account Number:</span>
                                    <span>{{ selectedBill.account_number || 'N/A' }}</span>
                                </div>
                            </div>
                        </v-col>

                        <!-- Car Information (only for Car Bill) -->
                        <v-col v-if="selectedBill.bill_type === 'Car Bill'" cols="12">
                            <h3 class="text-h6 mb-3">Car Information</h3>
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex justify-space-between">
                                    <span class="font-weight-medium">Car Model:</span>
                                    <span>{{ selectedBill.car_model }}</span>
                                </div>
                                <div class="d-flex justify-space-between">
                                    <span class="font-weight-medium">Car Year:</span>
                                    <span>{{ selectedBill.car_year }}</span>
                                </div>
                                <div class="d-flex justify-space-between">
                                    <span class="font-weight-medium">VIN:</span>
                                    <span>{{ selectedBill.car_vin }}</span>
                                </div>
                            </div>
                        </v-col>

                        <!-- Timestamps -->
                        <v-col cols="12">
                            <div class="d-flex justify-space-between text-caption text-grey">
                                <span>Created: {{ formatDateTime(selectedBill.created_at) }}</span>
                            </div>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions class="pa-4">
                    <v-spacer></v-spacer>
                    <v-btn color="grey-darken-1" variant="text" @click="showViewDialog = false">
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-col>
</template>

<script>
import { ref, onMounted, computed } from 'vue'; // Add computed
import axios from 'axios';
import { useToast } from 'vue-toastification';

export default {
    name: 'BillHistory',
    setup() {
        const toast = useToast();
        const billHistories = ref([]);
        const loading = ref(false);
        const error = ref(null);
        const showDeleteDialog = ref(false);
        const selectedBill = ref(null);
        const isDeleting = ref(false);
        const deletingId = ref(null);
        const showViewDialog = ref(false);

        const fetchBillHistories = async () => {
            loading.value = true;
            error.value = null;
            try {
                const response = await axios.get('/api/bill-histories');
                billHistories.value = response.data;
            } catch (err) {
                error.value = 'Failed to load bill histories. Please try again.';
                console.error('Error fetching bill histories:', err);
            } finally {
                loading.value = false;
            }
        };

        const hasCarInfo = computed(() => {
            return selectedBill.value && (
                selectedBill.value.car_model ||
                selectedBill.value.car_year ||
                selectedBill.value.car_vin
            );
        });

        const viewDetails = (bill) => {
            selectedBill.value = bill;
            showViewDialog.value = true;
        };

        const confirmDelete = (bill) => {
            selectedBill.value = bill;
            showDeleteDialog.value = true;
        };

        const deleteBillHistory = async () => {
            if (!selectedBill.value) return;

            isDeleting.value = true;
            deletingId.value = selectedBill.value.uuid;

            try {
                await axios.delete(`/api/bill-histories/${selectedBill.value.uuid}`);
                await fetchBillHistories();
                toast.success('Bill history deleted successfully');
                showDeleteDialog.value = false;
            } catch (err) {
                toast.error('Failed to delete bill history. Please try again.');
                console.error('Error deleting bill history:', err);
            } finally {
                isDeleting.value = false;
                deletingId.value = null;
                selectedBill.value = null;
            }
        };
        const getProviderName = (providerId) => {
            return providerId
                .split('_')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                .join(' ');
        }

        const formatAmount = (amount) => {
            return new Intl.NumberFormat('en-CA', {
                style: 'currency',
                currency: 'CAD',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(amount);
        };

        const formatDate = (date) => {
            if (!date) return 'N/A';
            return new Date(date).toLocaleDateString('en-CA', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });
        };
        const formatDateTime = (datetime) => {
            if (!datetime) return 'N/A';
            return new Date(datetime).toLocaleString('en-CA', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        };

        onMounted(fetchBillHistories);

        return {
            getProviderName,
            showViewDialog,
            selectedBill,
            hasCarInfo,
            viewDetails,
            billHistories,
            loading,
            error,
            showDeleteDialog,
            isDeleting,
            deletingId,
            fetchBillHistories,
            confirmDelete,
            deleteBillHistory,
            formatAmount,
            formatDate,
            formatDateTime, // Add this
        };
    },
};
</script>