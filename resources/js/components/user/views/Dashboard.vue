<template>
    <div>
        <ContentSkeleton v-if="isSkeletonLoading" />
        <v-row v-else>
            <!-- Your existing content -->

            <!-- Welcome Text and Setup Bill Button -->
            <v-col cols="12" class="d-flex justify-space-between align-center mb-4">
                <h2 class="text-h4 font-weight-bold">Welcome, {{ user?.first_name || 'User' }}!</h2>
                <!-- Button to Open Modal -->
                <v-btn color="purple" @click="showModal = true">Setup Bill</v-btn>
            </v-col>

            <!-- Main Section -->
            <v-col cols="12">
                <v-row>
                    <!-- Calendar Card -->
                    <v-col cols="12" md="8">
                        <v-card class="calendar-card pa-4" elevation="10">
                            <h3 class="text-h6 font-weight-bold mb-4">My Payment Calendar</h3>

                            <!-- Legend -->
                            <div class="legend mb-4">
                                <v-chip color="purple" class="ma-1">Payment Duration</v-chip>
                                <v-chip color="amber" class="ma-1">Bills Payment</v-chip>
                                <v-chip color="green" class="ma-1">Last Payments</v-chip>
                                <v-chip color="blue" class="ma-1">Upcoming Payments</v-chip>
                            </div>

                            <!-- FullCalendar -->
                            <FullCalendar :options="calendarOptions" ref="calendar" />
                        </v-card>


                        <transition name="fade">
                            <div v-if="isEventModalOpen" class="modal-overlay" @click.self="toggleEventModal">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0">
                                        <!-- Modal Header -->
                                        <div class="modal-header bg-gradient border-0"
                                            style="background: linear-gradient(to right, #3B82F6, #2563EB)">
                                            <h5 class="modal-title text-black d-flex align-items-center">
                                                <i class="fas fa-calendar-check me-2"></i>
                                                {{ selectedEvent?.payment_type === 'bill' ? selectedEvent.bill_type :
                                                    capitalizeFirstLetter(selectedEvent?.payment_type) }} Payment
                                            </h5>
                                            <button type="button" class="btn-close btn-close-black"
                                                @click="toggleEventModal"></button>
                                        </div>

                                        <!-- Modal Body -->
                                        <div class="modal-body p-4">
                                            <!-- Payment Date -->
                                            <div class="mb-3 d-flex align-items-center">
                                                <i class="fas fa-calendar-day me-3 text-success fs-5"></i>
                                                <div>
                                                    <span class="fw-medium">Payment Date: </span>
                                                    <strong>{{ formatDate(selectedEvent?.payment_date) }}</strong>
                                                </div>
                                            </div>

                                            <!-- Status -->
                                            <div class="mb-3 d-flex align-items-center">
                                                <i :class="[
                                                    'fas',
                                                    selectedEvent?.status === 'active' ? 'fa-check-circle text-success' : '',
                                                    selectedEvent?.status === 'paid' ? 'fa-check-circle text-success' : '',
                                                    selectedEvent?.status === 'due' ? 'fa-exclamation-circle text-warning' : '',
                                                    selectedEvent?.status === 'overdue' ? 'fa-times-circle text-danger' : ''
                                                ]" class="me-3 fs-5"></i>
                                                <div>
                                                    <span class="fw-medium">Status: </span>
                                                    <span :class="[
                                                        'badge',
                                                        selectedEvent?.status === 'active' ? 'bg-success' :
                                                            selectedEvent?.status === 'paid' ? 'bg-success' :
                                                                selectedEvent?.status === 'due' ? 'bg-warning' :
                                                                    selectedEvent?.status === 'overdue' ? 'bg-danger' : ''
                                                    ]">
                                                        {{ capitalizeFirstLetter(selectedEvent?.status) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Team Member View (For Mortgage/Rent) -->
                                            <template
                                                v-if="selectedEvent?.payment_type !== 'bill' && selectedEvent?.team_info">
                                                <div class="mb-3 d-flex align-items-center">
                                                    <i class="fas fa-dollar-sign me-3 text-success fs-5"></i>
                                                    <div>
                                                        <span class="fw-medium">Your Share ({{
                                                            selectedEvent.team_info?.percentage }}%): </span>
                                                        <strong style="color: green; font-size: 1.2rem;">
                                                            {{ formatAmount(selectedEvent.amount) }} CAD
                                                        </strong>
                                                    </div>
                                                </div>
                                                <div class="mb-3 d-flex align-items-center">
                                                    <i class="fas fa-dollar-sign me-3 text-success fs-5"></i>
                                                    <div>
                                                        <span class="fw-medium">Total Amount: </span>
                                                        <strong style="color: #666; font-size: 1.1rem;">
                                                            {{ formatAmount(selectedEvent.total_amount) }} CAD
                                                        </strong>
                                                    </div>
                                                </div>
                                            </template>

                                            <template
                                                v-else-if="selectedEvent?.payment_type !== 'bill' && !selectedEvent?.team_info">
                                                <div class="mb-3 d-flex align-items-center">
                                                    <i class="fas fa-dollar-sign me-3 text-success fs-5"></i>
                                                    <div>
                                                        <span class="fw-medium">Amount: </span>
                                                        <strong style="color: green; font-size: 1.2rem;">
                                                            {{ formatAmount(selectedEvent.amount) }} CAD
                                                        </strong>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- Bill Details View -->
                                            <template
                                                v-else-if="selectedEvent?.payment_type === 'bill' && selectedEvent?.bill_details">
                                                <!-- Amount -->
                                                <div class="mb-3 d-flex align-items-center">
                                                    <i class="fas fa-dollar-sign me-3 text-success fs-5"></i>
                                                    <div>
                                                        <span class="fw-medium">Amount: </span>
                                                        <strong style="color: green; font-size: 1.2rem;">
                                                            {{ formatAmount(selectedEvent.amount) }} CAD
                                                        </strong>
                                                    </div>
                                                </div>

                                                <!-- Provider -->
                                                <template v-if="selectedEvent.bill_details.provider">
                                                    <div class="mb-3 d-flex align-items-center">
                                                        <i class="fas fa-building me-3 text-success fs-5"></i>
                                                        <div>
                                                            <span class="fw-medium">Provider: </span>
                                                            <span>{{
                                                                getProviderName(selectedEvent.bill_details.provider)
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                </template>

                                                <!-- Account Number -->
                                                <template v-if="selectedEvent.bill_details.account_number">
                                                    <div class="mb-3 d-flex align-items-center">
                                                        <i class="fas fa-hashtag me-3 text-success fs-5"></i>
                                                        <div>
                                                            <span class="fw-medium">Account Number: </span>
                                                            <span>{{ selectedEvent.bill_details.account_number }}</span>
                                                        </div>
                                                    </div>
                                                </template>

                                                <!-- Phone Number (if applicable) -->
                                                <div v-if="selectedEvent.bill_details.phone"
                                                    class="mb-3 d-flex align-items-center">
                                                    <i class="fas fa-phone me-3 text-success fs-5"></i>
                                                    <div>
                                                        <span class="fw-medium">Phone Number: </span>
                                                        <span>+1 {{ selectedEvent.bill_details.phone }}</span>
                                                    </div>
                                                </div>
                                                <!-- Car Details -->
                                                <template v-if="selectedEvent.bill_details.car_details">
                                                    <div class="mb-3 d-flex align-items-center">
                                                        <i class="fas fa-car me-3 text-success fs-5"></i>
                                                        <div>
                                                            <span class="fw-medium">Car Model: </span>
                                                            <span>{{ selectedEvent.bill_details.car_details.car_model
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 d-flex align-items-center">
                                                        <i class="fas fa-calendar me-3 text-success fs-5"></i>
                                                        <div>
                                                            <span class="fw-medium">Car Year: </span>
                                                            <span>{{ selectedEvent.bill_details.car_details.car_year
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 d-flex align-items-center">
                                                        <i class="fas fa-barcode me-3 text-success fs-5"></i>
                                                        <div>
                                                            <span class="fw-medium">VIN: </span>
                                                            <span>{{ selectedEvent.bill_details.car_details.car_vin
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                </template>
                                            </template>

                                            <!-- Reminder Message -->
                                            <div class="alert alert-info mt-4 mb-0">
                                                <p class="mb-2">
                                                    Your {{ selectedEvent?.payment_type === 'bill' ?
                                                        selectedEvent.bill_type.toLowerCase() :
                                                        selectedEvent?.payment_type?.toLowerCase() }} payment is due on
                                                    <strong>{{ formatDate(selectedEvent?.payment_date) }}</strong>.
                                                </p>
                                                <p class="mb-2">
                                                    We will send reminders on the following dates:<br>
                                                    <strong>{{ formatReminderDates(selectedEvent?.reminder_dates)
                                                        }}</strong>
                                                </p>
                                                <p class="mb-0">
                                                    Kindly ensure your card or wallet is funded to avoid any
                                                    disruptions.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </transition>

                    </v-col>

                    <!-- Cards Beside Calendar -->
                    <v-col cols="12" md="4">
                        <!-- Mortgage Amount Card -->
                        <v-card class="calendar-card pa-4 mb-4" elevation="10">
                            <h3 v-if="address && address.amount" class="text-h6 font-weight-bold mb-1 text-purple">
                                {{ formatAmount(address.amount) }}
                            </h3>
                            <p class="text-caption mb-0"><b class="text-capitalize">{{ user.account_goal }} Amount</b>
                            </p>
                            <v-divider></v-divider>
                            <div class="mt-1">
                                <p><strong>Account Goal:</strong> <b class="text-capitalize">{{ user.account_goal
                                        }}</b></p>
                                <p><strong>Contract Address:</strong></p>
                                <!-- Display address details -->
                                <p v-if="address" class="text-caption text-muted">
                                    Address: {{ address.name }} <br />
                                    Province: {{ address.province }} <br />
                                    City: {{ address.city }} <br />
                                    Postal Code: {{ address.postal_code }}
                                </p>

                                <!-- Loading state -->
                                <p v-else class="text-caption text-muted">
                                    Loading address details...
                                </p>
                            </div>
                        </v-card>

                        <!-- Credit Score Card -->
                        <!-- <v-card v-if="buildCredit && buildCredit.some(item => item.card_id && item.user_id)" class="calendar-card pa-4" elevation="10">
                    <div class="text-center">
                        <CreditGauge />
                    </div>
                </v-card> -->
                        <!-- Credit Score Card -->
                        <!-- <v-card v-if="buildCredit && buildCredit.card_id && buildCredit.user_id" class="calendar-card pa-4" elevation="10"> -->
                        <v-card v-if="buildCredit && buildCredit.user_id" class="calendar-card pa-4" elevation="10">
                            <div class="text-center">
                                <CreditGauge />
                            </div>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row>
                    <!-- Bill History Section -->
                    <BillHistory />
                </v-row>
            </v-col>

            <!-- Modal -->
            <!-- Vuetify Dialog -->
            <v-dialog v-model="showModal" max-width="800px" persistent :retain-focus="false">
                <v-card @click.stop>
                    <!-- Modal Header -->
                    <v-card-title>
                        <span class="text-h6 text-purple">Setup New Bill</span>
                    </v-card-title>
                    <!-- Modal Body -->
                    <v-card-text>
                        <form @submit.prevent="submitForm" @click.stop>
                            <!-- Bill Type Dropdown -->

                            <div class="mb-4">
                                <label for="billType" class="form-label">Bill Type</label>
                                <v-select v-model="form.bill_id" :items="billTypes" item-title="name" item-value="id"
                                    label="Select Bill" @update:model-value="handleBillChange"
                                    class="form-select-solid"></v-select>
                                <span v-if="errors.bill_type" class="text-danger mt-1">{{ errors.bill_type }}</span>
                            </div>

                            <!-- Shared Fields -->
                            <div v-if="form.bill_type !== 'carBill'">
                                <div class="mb-4">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <!-- <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1">
                                            <img src="https://upload.wikimedia.org/wikipedia/en/c/cf/Flag_of_Canada.svg"
                                                alt="Canada" style="width: 20px; height: 14px;" />
                                            +1
                                        </span>
                                        <input v-model="form.phone" type="text" class="form-control"
                                            placeholder="Enter phone number" pattern="[0-9]*" inputmode="numeric"
                                            maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            @input="clearError('phone')" />
                                            </div> -->


                                    <v-text-field v-model="form.phone" label="Phone Number" prefix="+1" maxlength="10"
                                        :rules="[v => /^[0-9]*$/.test(v) || 'Numbers only']" hint="Enter phone number"
                                        :error-messages="errors.phone" @update:model-value="(val) => {
                                            if (val) {
                                                form.phone = val.toString().replace(/[^0-9]/g, '');
                                            }
                                            clearError('phone');
                                        }"></v-text-field>
                                    <!-- <span v-if="errors.phone" class="text-danger mt-1">{{ errors.phone }}</span> -->
                                </div>


                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input v-model="form.amount" type="text" class="form-control" pattern="[0-9]*"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            @input="clearError('amount')" placeholder="Enter amount" maxlength="15" />
                                        <span v-if="errors.amount" class="text-danger mt-1">{{ errors.amount }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="due_date" class="form-label">Due Date</label>
                                        <input v-model="form.due_date" type="date" class="form-control"
                                            @input="clearError('due_date')" />
                                        <span v-if="errors.due_date" class="text-danger mt-1">{{ errors.due_date
                                            }}</span>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="provider" class="form-label">Provider</label>
                                        <!-- <select v-model="form.provider" class="form-select"
                                            @change="clearError('provider')">
                                            <option value="" selected>Select Provider</option>
                                            <option v-for="provider in providers" :key="provider.id"
                                                :value="provider.value">
                                                {{ provider.name }}
                                            </option>
                                        </select> -->
                                        <v-select v-model="form.provider" :items="providers" item-title="name"
                                            item-value="value" label="Select Provider"
                                            @update:model-value="clearError('provider')"></v-select>
                                        <span v-if="errors.provider" class="text-danger mt-1">{{ errors.provider
                                            }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="account_number" class="form-label">Account Number</label>
                                        <input v-model="form.account_number" type="text" class="form-control"
                                            pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            placeholder="Enter account number" maxlength="15"
                                            @input="clearError('account_number')" />
                                        <span v-if="errors.account_number" class="text-danger mt-1">{{
                                            errors.account_number }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Car-Specific Fields -->
                            <div v-if="form.bill_type === 'carBill'">
                                <div class="row mb-4">
                                    <!-- Car Bill Amount -->
                                    <div class="col-md-6">
                                        <label for="car_amount" class="form-label">Car Bill Amount</label>
                                        <input v-model="form.car_amount" type="text" class="form-control" maxlength="15"
                                            pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            placeholder="Enter car bill amount" @input="clearError('car_amount')" />
                                        <span v-if="errors.car_amount" class="text-danger mt-1">{{ errors.car_amount
                                            }}</span>
                                    </div>
                                    <!-- Car VIN -->
                                    <div class="col-md-6">
                                        <label for="car_vin" class="form-label">Car VIN</label>
                                        <input v-model="form.car_vin" type="text" class="form-control"
                                            placeholder="Enter car VIN" @input="clearError('car_vin')" maxlength="20" />
                                        <span v-if="errors.car_vin" class="text-danger mt-1">{{ errors.car_vin }}</span>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <!-- Frequency -->
                                    <div class="col-md-6">
                                        <label for="frequency" class="form-label">Frequency</label>
                                        <select v-model="form.frequency" class="form-select"
                                            @change="clearError('frequency')">
                                            <option value="" disabled>Select Frequency</option>
                                            <option value="bi-weekly">Bi-weekly</option>
                                            <option value="monthly">Monthly</option>
                                        </select>
                                        <span v-if="errors.frequency" class="text-danger mt-1">{{ errors.frequency
                                            }}</span>
                                    </div>
                                    <!-- Due Date -->
                                    <div class="col-md-6">
                                        <label for="due_date_car" class="form-label">Due Date</label>
                                        <input v-model="form.due_date_car" type="date" class="form-control"
                                            @input="clearError('due_date_car')" />
                                        <span v-if="errors.due_date_car" class="text-danger mt-1">{{ errors.due_date_car
                                            }}</span>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <!-- Car Model -->
                                    <div class="col-md-6">
                                        <label for="car_model" class="form-label">Car Model</label>
                                        <input v-model="form.car_model" type="text" class="form-control"
                                            placeholder="Enter car model" maxlength="20"
                                            @input="clearError('car_model')" />
                                        <span v-if="errors.car_model" class="text-danger mt-1">{{ errors.car_model
                                            }}</span>
                                    </div>
                                    <!-- Car Year -->
                                    <div class="col-md-6">
                                        <label for="car_year" class="form-label">Car Year</label>
                                        <input v-model="form.car_year" type="text" class="form-control" maxlength="4"
                                            pattern="[0-9]*" inputmode="numeric"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            placeholder="Enter car year (e.g., 2022)" @input="clearError('car_year')" />
                                        <span v-if="errors.car_year" class="text-danger mt-1">{{ errors.car_year
                                            }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Card Selection -->
                            <div class="mb-4">
                                <label for="payment_card" class="form-label">Payment Card</label>
                                <div>
                                    <label v-for="card in cards" :key="card.id" class="d-flex align-items-center mb-3">
                                        <input type="radio" :value="card.id" v-model="form.payment_card"
                                            class="form-check-input me-2" @change="clearError('payment_card')" />
                                        <img :src="card.logoUrl" alt="Card Logo" class="h-40px me-3"
                                            style="width: 45px;" />
                                        <div>
                                            <div>{{ card.type }} **** {{ card.number.slice(-4) }}</div>
                                            <div class="text-muted">Expires {{ card.expiryMonth }}/{{ card.expiryYear }}
                                            </div>
                                            <div class="text-muted">Limit: ${{ card.limit.toLocaleString() }}</div>
                                        </div>
                                    </label>
                                </div>
                                <span v-if="errors.payment_card" class="text-danger mt-1">{{ errors.payment_card
                                    }}</span>
                            </div>

                            <!-- Modal Footer -->
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <button type="button" class="btn btn-secondary me-2"
                                    @click="toggleModal">Cancel</button>
                                <button type="submit" class="btn btn-primary text-white" :disabled="loading">
                                    <span v-if="loading">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"
                                            aria-hidden="true"></span>
                                        Setting up bill...
                                    </span>
                                    <span v-else>
                                        Submit
                                    </span>
                                </button>

                            </v-card-actions>
                        </form>

                    </v-card-text>
                </v-card>
            </v-dialog>

            <!-- Add this before the closing </v-row> tag -->
            <BillConfirmationModal v-model:show="showConfirmationModal" :form-data="form" :bill-types="billTypes"
                :providers="providers" :loading="loading" @cancel="showConfirmationModal = false"
                @confirm="handleFormSubmission" />
        </v-row>
    </div>
</template>


<script>
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import CreditGauge from '@/components/CreditGauge.vue';
import ContentSkeleton from '@/components/skeleton/dashboard.vue';
import BillConfirmationModal from '@/components/BillConfirmationModal.vue';
import BillHistory from '@/components/BillHistory.vue';

import axios from "axios";

import {
    useToast
} from "vue-toastification";

import {
    useAuthStore
} from '@/stores/authStore'; // Correct path to your store file

export default {
    components: {
        CreditGauge,
        FullCalendar,
        ContentSkeleton,
        BillHistory,
        BillConfirmationModal,
    },
    data() {
        const authStore = useAuthStore();
        return {
            showConfirmationModal: false,
            isSkeletonLoading: true,
            loading: false, // Tracks if the form is being submitted
            showModal: false, // Controls modal visibility
            creditAgency: "Equifax", // Default agency
            scores: {
                TransUnion: 750,
                Equifax: 680,
            },
            billTypes: [], // Initialize as empty
            providers: [{
                id: 1,
                value: "abc_power",
                name: "ABC Power Company"
            },
            {
                id: 2,
                value: "xyz_energy",
                name: "XYZ Energy Solutions"
            },
            {
                id: 3,
                value: "national_grid",
                name: "National Grid"
            },
            {
                id: 4,
                value: "xyz_mobile",
                name: "XYZ Mobile"
            },
            {
                id: 5,
                value: "abc_telecom",
                name: "ABC Telecom"
            },
            {
                id: 6,
                value: "global_mobile",
                name: "Global Mobile"
            },
            {
                id: 7,
                value: "netfast_internet",
                name: "NetFast Internet"
            },
            {
                id: 8,
                value: "speednet",
                name: "SpeedNet"
            },
            {
                id: 9,
                value: "global_internet",
                name: "Global Internet"
            },
            ],
            isSetupBillModalOpen: false,
            form: {
                bill_id: null, // Selected bill ID
                bill_type: null, // Internal use for logic
                amount: "",
                due_date: null,
                phone: "",
                provider: "",
                account_number: "",
                car_amount: "",
                due_date_car: "",
                frequency: "",
                car_model: "",
                car_year: "",
                car_vin: "",
                payment_card: "",
            }, // Form data
            initialFormState: {
                bill_type: "",
                amount: "",
                due_date: "",
                provider: "",
                account_number: "",
                due_date_car: "",
                car_amount: "",
                frequency: "",
                car_model: "",
                car_year: "",
                car_vin: "",
                payment_card: "",
            },
            errors: {}, // Validation errors
            cards: [], // Card data fetched from API
            newBill: {
                bill_type: "",
                amount: "",
                due_date: "",
                provider: "",
                account_number: "",
                car_amount: "",
                frequency: "",
                car_model: "",
                car_year: "",
                car_vin: "",
                payment_card: "",
            }, // Form data
            addErrors: {},
            user: authStore.user,
            address: null, // Placeholder for the address data
            buildCredit: [], // Placeholder for the address data
            // FullCalendar options
            calendarOptions: {
                height: 'auto',
                plugins: [dayGridPlugin, interactionPlugin],
                initialView: "dayGridMonth",
                headerToolbar: {
                    start: "prev,next today",
                    center: "title",
                    end: "dayGridYear,dayGridMonth,dayGridWeek,dayGridDay",
                },
                // events(info, successCallback, failureCallback) {
                //     // axios.get("/api/payment-schedules")
                //     //     .then(response => {
                //     //         const events = response.data.flatMap(schedule => [{
                //     //                 id: schedule.id,
                //     //                 title: `${schedule.payment_type.toUpperCase()} Payment`,
                //     //                 start: schedule.payment_date,
                //     //                 color: 'purple',
                //     //                 extendedProps: schedule
                //     //             },
                //     //             // ...Object.entries(schedule.reminder_dates || {}).map(([key, date]) => ({
                //     //             //     id: `${schedule.id}_${key}`,
                //     //             //     title: `${schedule.payment_type.toUpperCase()} Reminder`,
                //     //             //     start: date,
                //     //             //     color: '#FCD34D',
                //     //             //     extendedProps: {
                //     //             //         reminderFor: schedule.id,
                //     //             //         reminderType: key,
                //     //             //         originalEvent: schedule
                //     //             //     }
                //     //             // }))
                //     //         ]);
                //     //         successCallback(events);
                //     //     })
                //     //     .catch(error => {
                //     //         // console.error(error);
                //     //         failureCallback(error);
                //     //     });
                //     axios.get("/api/payment-schedules")
                //         .then(response => {
                //             const events = response.data.flatMap(schedule => {
                //                 // Determine the title based on payment type
                //                 const title = schedule.payment_type === 'bill' && schedule.bill_type ?
                //                     `${schedule.bill_type}` :
                //                     `${schedule.payment_type.toUpperCase()}`;

                //                 // Assign colors based on payment type
                //                 let color;
                //                 switch (schedule.payment_type) {
                //                     case 'bill':
                //                         color = '#FCD34D'; // Yellow for "Bills Payment"
                //                         break;
                //                     case 'mortgage':
                //                         color = 'purple'; // Purple for "Payment Duration"
                //                         break;
                //                     case 'rent':
                //                         color = 'purple'; // Purple for "Payment Duration"
                //                         break;
                //                     case 'last_payment':
                //                         color = '#DFF3DF'; // Green for "Last Payments"
                //                         break;
                //                     case 'upcoming_payment':
                //                         color = '#D3E6FA'; // Blue for "Upcoming Payments"
                //                         break;
                //                     default:
                //                         color = '#D9D9D9'; // Default gray for unspecified types
                //                 }

                //                 return [{
                //                     id: schedule.id,
                //                     title: title, // Use the dynamic title
                //                     start: schedule.payment_date,
                //                     color: color, // Use the dynamic color
                //                     extendedProps: schedule
                //                 }];
                //             });
                //             successCallback(events);
                //         })
                //         .catch(error => {
                //             // Handle errors
                //             failureCallback(error);
                //         });

                // },

                events: this.fetchEvents, // Events will be loaded dynamically
                eventClick: this.handleEventClick, // Attach event click handler
                eventMouseEnter: function (info) {
                    info.el.style.cursor = "pointer"; // Change cursor on hover
                },
            },
            isEventModalOpen: false, // Tracks modal visibility
            selectedEvent: null, // Stores data of the clicked event
        };
    },
    methods: {
        getProviderName(providerId) {
            const provider = this.providers.find(p => p.value === providerId);
            return provider ? provider.name : providerId;
        },
        async fetchEvents(info, successCallback, failureCallback) {
            // console.log('info:', info);
            // console.log('successCallback:', successCallback);
            // console.log('failureCallback:', failureCallback);

            try {
                const response = await axios.get('/api/payment-schedules');
                const events = response.data.map(schedule => ({
                    id: schedule.id,
                    title: schedule.payment_type === 'bill' && schedule.bill_type ?
                        `${schedule.bill_type}` : `${schedule.payment_type.toUpperCase()}`,
                    start: schedule.payment_date,
                    color: schedule.payment_type === 'bill' ? '#FCD34D' : 'purple',
                    extendedProps: schedule,
                }));

                if (typeof successCallback === 'function') {
                    successCallback(events); // Call the callback with the events
                } else {
                    console.error('successCallback is not a function');
                }
            } catch (error) {
                console.error('Error fetching events:', error);
                if (typeof failureCallback === 'function') {
                    failureCallback(error); // Call the error callback
                }
            }
        },
        refreshCalendar() {
            if (this.$refs.calendar) {
                this.$refs.calendar.getApi().refetchEvents();
            }
        },

        handleOutsideClick() {
            // Optional: Add confirmation before closing
            this.showModal = false;
            // if (confirm('Are you sure you want to close this form? Any unsaved changes will be lost.')) {
            // }
        },
        handleBillChange() {
            // Find the selected bill or reset if no selection
            const selectedBill = this.billTypes.find(
                (bill) => bill.id === this.form.bill_id
            );

            // Update form.bill_type
            this.form.bill_type = selectedBill ? selectedBill.value : null;

            console.log("Selected Bill ID:", this.form.bill_id);
            console.log("Selected Bill Type:", this.form.bill_type);
        },
        async fetchBillTypes() {
            try {
                const response = await axios.get('/api/bill-types');
                this.billTypes = [
                    ...response.data, // Append fetched bill types
                ];
            } catch (error) {
                console.error("Error fetching bill types:", error);
                // useToast().error("Failed to load bill types. Please try again.");
            }
        },
        async getCards() {
            try {
                const response = await axios.get("/api/cards"); // Fetch card data from Laravel API
                this.cards = Object.values(response.data); // Convert object to array
            } catch (error) {
                console.error("Error fetching cards:", error);
            }
        },
        toggleModal() {
            if (this.hasFormChanges()) {
                this.showModal = false;
                this.resetForm();
                // if (confirm('Are you sure you want to close this form? Any unsaved changes will be lost.')) {
                // }
            } else {
                this.showModal = false;
                this.resetForm();
            }
        },
        // Add a method to check if form has changes
        hasFormChanges() {
            // Compare current form values with initial values
            return Object.keys(this.form).some(key =>
                this.form[key] !== this.initialFormState[key]
            );
        },

        updateBillDetails() {
            // Reset specific fields when bill type changes
            if (this.form.bill_type !== "carBill") {
                this.form.car_amount = "";
                this.form.frequency = "";
            }
        },
        resetForm() {
            // this.form = {
            //     bill_type: "",
            //     amount: "",
            //     due_date: "",
            //     provider: "",
            //     account_number: "",
            //     due_date_car: "",
            //     car_amount: "",
            //     frequency: "",
            //     car_model: "",
            //     car_year: "",
            //     car_vin: "",
            //     payment_card: "",
            // };

            this.form = { ...this.initialFormState };
            this.errors = {};
        },
        validateForm() {
            this.errors = {}; // Clear previous errors

            // Bill Type Validation
            if (!this.form.bill_type) {
                this.errors.bill_type = "Bill type is required.";
            }

            // Shared Fields Validation (Non-Car Bills)
            if (this.form.bill_type !== "carBill") {
                if (!this.form.amount) {
                    this.errors.amount = "Amount is required.";
                } else if (this.form.amount <= 0) {
                    this.errors.amount = "Amount must be greater than 0.";
                }

                if (!this.form.due_date) {
                    this.errors.due_date = "Due date is required.";
                }

                if (!this.form.provider) {
                    this.errors.provider = "Provider is required.";
                }

                if (!this.form.account_number) {
                    this.errors.account_number = "Account number is required.";
                } else if (this.form.account_number.length < 6) {
                    this.errors.account_number =
                        "Account number must be at least 6 characters.";
                }

                // Add Phone Number Validation for Internet and Phone Bills
                if (!this.form.phone) {
                    this.errors.phone = "Phone number is required.";
                } else if (!/^\d{10}$/.test(this.form.phone)) {
                    this.errors.phone = "Phone number must be a valid 10-digit Canadian number.";
                }

            }

            // Car-Specific Fields Validation
            if (this.form.bill_type === "carBill") {
                if (!this.form.car_amount) {
                    this.errors.car_amount = "Car bill amount is required.";
                } else if (this.form.car_amount <= 0) {
                    this.errors.car_amount = "Amount must be greater than 0.";
                }

                if (!this.form.frequency) {
                    this.errors.frequency = "Frequency is required.";
                }
                if (!this.form.due_date_car) {
                    this.errors.due_date_car = "Car due date is required.";
                }

                if (!this.form.car_model) {
                    this.errors.car_model = "Car model is required.";
                } else if (this.form.car_model.length < 2) {
                    this.errors.car_model =
                        "Car model must be at least 2 characters.";
                }

                if (!this.form.car_year) {
                    this.errors.car_year = "Car year is required.";
                } else if (
                    String(this.form.car_year).length !== 4 ||
                    isNaN(this.form.car_year)
                ) {
                    this.errors.car_year =
                        "Car year must be a valid 4-digit number.";
                }

                if (!this.form.car_vin) {
                    this.errors.car_vin = "Car VIN is required.";
                } else if (this.form.car_vin.length < 10) {
                    this.errors.car_vin =
                        "Car VIN must be at least 10 characters.";
                }
            }

            // Payment Card Validation
            if (!this.form.payment_card) {
                this.errors.payment_card = "Please select a payment card.";
            }

            return Object.keys(this.errors).length === 0; // Return true if no errors
        },
        async submitForm() {
            if (this.validateForm()) {
                this.loading = true; // Set loading state
                try {
                    // Prepare the data for submission
                    const billData = {
                        bill_id: this.form.bill_id || null, // Optional, adjust as needed
                        user_id: this.form.user_id || null, // Adjust based on authentication
                        card_id: this.form.payment_card,
                        provider: this.form.provider || null,
                        bill_type: this.form.bill_type || null,
                        // Dynamically set the amount based on bill_type
                        amount: this.form.bill_type === 'carBill' ? this.form.car_amount : this.form.amount,
                        due_date: this.form.due_date || this.form.due_date_car || null,
                        account_number: this.form.account_number || null,
                        frequency: this.form.frequency || null,
                        car_model: this.form.car_model || null,
                        car_year: this.form.car_year || null,
                        car_vin: this.form.car_vin || null,
                        phone: this.form.phone || null,
                    };

                    if (this.validateForm()) {
                        // Show confirmation modal instead of submitting directly
                        this.showConfirmationModal = true;
                    }
                    // Send data to the backend via an API call
                    // const response = await axios.post('/api/bill-histories', billData, {
                    //     timeout: 40000
                    // });
                    // console.log("Bill saved successfully:", response.data);


                    // // Refresh the calendar
                    // this.refreshCalendar();

                    // useToast().success(response.data.message || 'Bill saved successfully');
                    // this.toggleModal(); // Close modal
                } catch (error) {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                        useToast().error('Please check the form and try again.');
                    } else if (error.response && error.response.status === 401) {
                        console.log(error.response.data);

                        useToast().error(error.response.data.message);
                    } else {
                        console.error("An unexpected error occurred:", error);
                        useToast().error('Error submitting the form.');
                    }
                } finally {
                    this.loading = false; // Reset loading state
                }
            }
        },
        async handleFormSubmission(confirmedData) {
            console.log(confirmedData);

            this.loading = true;
            try {
                const response = await axios.post('/api/bill-histories', confirmedData, {
                    timeout: 40000
                });
                console.log("Bill saved successfully:", response.data);

                // Refresh the calendar
                this.refreshCalendar();

                useToast().success(response.data.message || 'Bill saved successfully');
                this.showConfirmationModal = false;
                this.toggleModal(); // Close main modal
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                    useToast().error('Please check the form and try again.');
                } else if (error.response && error.response.status === 401) {
                    useToast().error(error.response.data.message);
                } else {
                    console.error("An unexpected error occurred:", error);
                    useToast().error('Error submitting the form.');
                }
            } finally {
                this.loading = false;
            }
        },
        clearError(field) {
            // Clear error for the specified field on input
            if (this.errors[field]) {
                delete this.errors[field];
            }
        },
        clearUnusedFields() {
            if (this.form.bill_type !== 'carBill') {
                this.form.due_date_car = null;
                this.form.frequency = null;
                this.form.car_model = null;
                this.form.car_year = null;
                this.form.car_vin = null;
            }

            if (this.form.bill_type === 'carBill') {
                this.form.due_date = null;
                this.form.amount = null;
                this.form.provider = null;
                this.form.account_number = null;
            }
        },
        formatAmount(amount) {
            // Format the amount as Canadian dollars with two decimal places
            return new Intl.NumberFormat('en-CA', {
                style: 'currency',
                currency: 'CAD',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(amount);
        },
        async fetchUserData(params) {
            try {
                const response = await axios.get('/user-data', {
                    params
                });
                if (params.address) this.address = response.data.address;
                if (params.build_credit) this.buildCredit = response.data.build_credit;
            } catch (error) {
                console.error('Error fetching user data:', error);
            }
        },
        transformEvents(events) {
            let calendarEvents = [];

            events.forEach(event => {
                // Main payment event
                calendarEvents.push({
                    id: event.id,
                    title: `${event.payment_type.toUpperCase()} Payment`,
                    start: event.payment_date,
                    classNames: ['bg-purple-600'],
                    extendedProps: {
                        ...event
                    }
                });

                // Reminder events
                Object.entries(event.reminder_dates).forEach(([key, date]) => {
                    calendarEvents.push({
                        id: `${event.id}_${key}`,
                        title: `${event.payment_type} Reminder`,
                        start: date,
                        classNames: ['bg-yellow-400'],
                        extendedProps: {
                            reminderFor: event.id,
                            reminderType: key
                        }
                    });
                });
            });

            return calendarEvents;
        },
        capitalizeFirstLetter(text) {
            if (!text) return ""; // Handle null or undefined input
            return text.charAt(0).toUpperCase() + text.slice(1).toLowerCase();
        },
        // Combine reminder dates into a single string
        formatReminderDates(reminderDates) {
            if (!reminderDates) return "N/A"; // Handle null or undefined reminders
            const dates = Object.values(reminderDates); // Extract reminder dates
            return dates.join(", "); // Combine dates into a single string
        },
        formatDate(date) {
            if (!date) return "";
            return new Date(date).toLocaleDateString("en-US", {
                year: "numeric",
                month: "long",
                day: "numeric",
            });
        },
        formatReminderDates(reminderDates) {
            if (!reminderDates) return "";
            return Object.values(reminderDates)
                .map((date) => this.formatDate(date))
                .join(", ");
        },
        // Handle event click to open modal
        handleEventClick(info) {
            // console.log("Event clicked:", info.event.extendedProps); // Debug log
            this.selectedEvent = info.event.extendedProps; // Get event details
            this.isEventModalOpen = true; // Show modal
        },
        // Toggle modal visibility
        toggleEventModal() {
            this.isEventModalOpen = !this.isEventModalOpen;
            if (!this.isEventModalOpen) this.selectedEvent = null; // Clear selected event on close
        },
        // Get color for event based on payment type
        getEventColor(paymentType) {
            switch (paymentType) {
                case "rent":
                    return "blue";
                case "mortgage":
                    return "purple";
                case "bill":
                    return "orange";
                default:
                    return "gray";
            }
        },
    },
    watch: {
        'form.bill_type'(newType) {
            this.clearUnusedFields();
        },
    },
    computed: {
        creditScore() {
            return this.scores[this.creditAgency];
        },
    },
    async mounted() {
        // this.fetchEvents();
        // this.fetchBillTypes();
        // Set default selected bill (e.g., the first bill in the list)
        this.form.bill_id = ""; // Example: Default to "Car Bill" (id: 1)
        this.handleBillChange(); // Trigger the change handler to set the bill type

        try {
            await Promise.all([
                this.fetchEvents(),
                this.fetchBillTypes(),
                this.getCards(),
                this.fetchUserData({
                    address: true,
                    build_credit: true,
                })
            ])
        } finally {
            this.isSkeletonLoading = false
        }
    },
    async created() {
        this.form = { ...this.initialFormState };
        await this.getCards();
        // Example: Fetch only personal info and wallet for this page
        this.fetchUserData({
            address: true,
            build_credit: true,
        });
    },
};
</script>
<style scoped>
.speedometer {
    text-align: center;
    margin: 20px auto;
}

canvas {
    display: block;
    margin: 0 auto;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.modal-content {
    max-width: 500px;
}

.fc-event {
    cursor: pointer;
    /* Set pointer cursor for calendar events */
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    /* background-color: rgb(49 131 131 / 50%); */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}

.modal-overlay {
    padding: 20px;
    /* Add spacing around the modal */
    background-color: rgba(0, 0, 0, 0.5);
    /* Ensure visibility for the modal */
}

.modal-content {
    background: #fff;
    border-radius: 10px;
    /* width: 900px; */
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

.close-button:hover {
    color: #d32f2f;
}

.calendar-card {
    background-color: #fffdfd;
    border-radius: 12px;
}

.legend {
    display: flex;
    flex-wrap: wrap;
}

.text-purple {
    color: #6a1b9a !important;
}

.text-muted {
    color: #6c757d !important;
}

.pa-4 {
    padding: 1rem;
}

.modal-content {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.modal-title {
    font-size: 1.5rem;
    display: flex;
    align-items: center;
}

.modal-body {
    padding: 5px 20px;
}

.fa {
    margin-right: 5px;
}
</style>
