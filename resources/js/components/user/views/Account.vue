<template>
  <div>
    <ContentSkeleton v-if="isSkeletonLoading" />
    <v-row v-else>
      <!-- Your existing content here -->

      <v-col cols="12">
        <v-card class="details-card pa-4" elevation="10">
          <!-- Header -->
          <v-card-title class="d-flex justify-space-between align-center mb-6">
            <h2 class="text-h5">{{ personalInfo.account_goal === 'mortgage' ? 'Mortgage' : 'Rent' }} Details</h2>
            <!-- <div>
              <v-btn color="primary" variant="outlined" class="mr-2" @click="addNewAddress">
                ADD ADDRESS
              </v-btn>
              <v-btn color="primary" variant="elevated" @click="editAddress">
                EDIT ADDRESS
              </v-btn>
            </div> -->
            <div>
              <v-btn v-if="canAddAddress" color="primary" variant="outlined" class="mr-2" @click="addNewAddress">
                SETUP ADDRESS
              </v-btn>

              <v-btn v-if="canEditAddress" color="primary" variant="elevated" @click="editAddress">
                EDIT ADDRESS
              </v-btn>
            </div>

            <EditAddressModal ref="editModal" :address="addressDetails" :landlord-finance="landlordFinance"
              @refresh-data="handleRefreshData" />
            <AddAddressModal ref="addModal" @refresh-data="handleRefreshData" />
          </v-card-title>


          <v-card-text>
            <v-row>
              <!-- Personal Information -->
              <v-col cols="12" md="6">
                <v-card elevation="2" class="rounded-lg h-100">
                  <v-card-title class="d-flex align-center py-3 px-4 primary-bg">
                    <v-avatar color="primary" size="32" class="mr-3">
                      <span class="text-white">{{ initials }}</span>
                    </v-avatar>
                    <span class="text-h6 font-weight-medium">Personal Information</span>
                  </v-card-title>

                  <v-card-text class="pa-4">
                    <v-list>
                      <v-list-item class="px-0">
                        <template v-slot:prepend>
                          <v-icon color="primary">mdi-account</v-icon>
                        </template>
                        <v-list-item-title class="font-weight-medium">{{ personalInfo.first_name }} {{
                          personalInfo.last_name
                        }}</v-list-item-title>
                        <v-list-item-subtitle>Name</v-list-item-subtitle>
                      </v-list-item>

                      <v-list-item class="px-0">
                        <template v-slot:prepend>
                          <v-icon color="primary">mdi-email</v-icon>
                        </template>
                        <v-list-item-title class="font-weight-medium">{{ personalInfo.email }}</v-list-item-title>
                        <v-list-item-subtitle>Email</v-list-item-subtitle>
                      </v-list-item>

                      <v-list-item class="px-0">
                        <template v-slot:prepend>
                          <v-icon color="primary">mdi-phone</v-icon>
                        </template>
                        <v-list-item-title class="font-weight-medium">{{ formatPhone(personalInfo.phone)
                          }}</v-list-item-title>
                        <v-list-item-subtitle>Phone</v-list-item-subtitle>
                      </v-list-item>
                    </v-list>
                  </v-card-text>
                </v-card>
              </v-col>

              <!-- Account Details -->
              <v-col cols="12" md="6">
                <v-card elevation="2" class="rounded-lg h-100">
                  <v-card-title class="d-flex align-center py-3 px-4 primary-bg">
                    <v-icon color="primary" size="32" class="mr-3">mdi-account</v-icon>
                    <span class="text-h6 font-weight-medium">Account Details</span>
                  </v-card-title>

                  <v-card-text class="pa-4">
                    <v-list>
                      <v-list-item class="px-0">
                        <template v-slot:prepend>
                          <v-icon color="primary">mdi-flag</v-icon>
                        </template>
                        <v-list-item-title class="font-weight-medium">Paying for {{ personalInfo.account_goal ===
                          'mortgage' ?
                          'Mortgage' : 'Rent' }}</v-list-item-title>
                        <v-list-item-subtitle>Account Goal</v-list-item-subtitle>
                      </v-list-item>

                      <v-list-item class="px-0">
                        <template v-slot:prepend>
                          <v-icon color="primary">mdi-account-group</v-icon>
                        </template>
                        <v-list-item-title class="font-weight-medium">{{ personalInfo.account_type
                          }}</v-list-item-title>
                        <v-list-item-subtitle>Account Type</v-list-item-subtitle>
                      </v-list-item>

                      <v-list-item class="px-0">
                        <template v-slot:prepend>
                          <v-icon color="primary">mdi-file-document-outline</v-icon>
                        </template>
                        <v-list-item-title class="font-weight-medium">{{ personalInfo.payment_plan
                          }}</v-list-item-title>
                        <v-list-item-subtitle>Account Plan</v-list-item-subtitle>
                      </v-list-item>

                      <v-list-item class="px-0">
                        <template v-slot:prepend>
                          <v-icon color="primary">mdi-cash-multiple</v-icon>
                        </template>
                        <v-list-item-title class="font-weight-medium text-capitalize">Paying for {{
                          personalInfo.payment_setup }} {{
                            personalInfo.account_goal === 'mortgage' ? 'Mortgage' : 'Rent' }}</v-list-item-title>
                        <v-list-item-subtitle>Payment Setup</v-list-item-subtitle>
                      </v-list-item>
                    </v-list>
                  </v-card-text>
                </v-card>
              </v-col>

              <!-- Build Credit Card Section -->
              <v-col cols="12" md="6" v-if="buildCredit && buildCredit.id">
                <v-card elevation="2" class="rounded-lg h-100">
                  <v-card-title class="d-flex align-center py-3 px-4 primary-bg">
                    <v-icon color="primary" size="32" class="mr-3">mdi-credit-card</v-icon>
                    <span class="text-h6 font-weight-medium">Build Credit Score</span>
                  </v-card-title>

                  <v-card-text class="pa-4">
                    <v-list>
                      <v-list-item class="px-0">
                        <template v-slot:prepend>
                          <v-icon color="primary">mdi-credit-card-check</v-icon>
                        </template>
                        <v-list-item-title class="font-weight-medium">${{ formatAmount(buildCredit.cc_limit)
                          }}</v-list-item-title>
                        <v-list-item-subtitle>Credit Limit</v-list-item-subtitle>
                      </v-list-item>

                      <v-list-item class="px-0">
                        <template v-slot:prepend>
                          <v-icon color="primary">mdi-calendar</v-icon>
                        </template>
                        <v-list-item-title class="font-weight-medium">{{ buildCredit.cc_due_date }}th of each
                          month</v-list-item-title>
                        <v-list-item-subtitle>Card Due Date</v-list-item-subtitle>
                      </v-list-item>
                    </v-list>
                  </v-card-text>
                </v-card>
              </v-col>

              <!-- Address Details -->
              <v-col cols="12" md="6" v-if="addressDetails?.name">
                <v-card elevation="2" class="rounded-lg h-100">
                  <v-card-title class="d-flex align-center py-3 px-4 primary-bg">
                    <v-icon color="primary" size="32" class="mr-3">mdi-home</v-icon>
                    <span class="text-h6 font-weight-medium">Address Details</span>
                  </v-card-title>

                  <v-card-text class="pa-4">
                    <v-list>
                      <v-list-item class="px-0">
                        <template v-slot:prepend>
                          <v-icon color="primary" class="flex-shrink-0">mdi-map-marker</v-icon>
                        </template>
                        <v-tooltip :text="addressDetails.name" location="top">
                          <template v-slot:activator="{ props }">
                            <div class="w-100">
                              <v-list-item-title class="font-weight-medium text-truncate"
                                style="width: calc(100% - 16px); display: block;" v-bind="props">
                                {{ addressDetails.name }}
                              </v-list-item-title>
                              <v-list-item-subtitle>Address</v-list-item-subtitle>
                            </div>
                          </template>
                        </v-tooltip>
                      </v-list-item>

                      <v-list-item class="px-0">
                        <template v-slot:prepend>
                          <v-icon color="primary" class="flex-shrink-0">mdi-city</v-icon>
                        </template>
                        <div class="w-100">
                          <v-list-item-title class="font-weight-medium text-truncate">
                            {{ addressDetails.city }}, {{ addressDetails.province }}
                          </v-list-item-title>
                          <v-list-item-subtitle>Location</v-list-item-subtitle>
                        </div>
                      </v-list-item>

                      <v-list-item class="px-0">
                        <template v-slot:prepend>
                          <v-icon color="primary" class="flex-shrink-0">mdi-currency-usd</v-icon>
                        </template>
                        <div class="w-100">
                          <v-list-item-title class="font-weight-medium">
                            ${{ formatAmount(addressDetails.amount) }}
                          </v-list-item-title>
                          <v-list-item-subtitle>Amount</v-list-item-subtitle>
                        </div>
                      </v-list-item>
                    </v-list>
                  </v-card-text>
                </v-card>
              </v-col>

              <!-- Payment Schedule Details -->
              <v-col cols="12" md="6" v-if="addressDetails?.reoccurring_monthly_day">
                <v-card elevation="2" class="rounded-lg h-100">
                  <v-card-title class="d-flex align-center py-3 px-4 primary-bg">
                    <v-icon color="primary" size="32" class="mr-3">mdi-calendar-clock</v-icon>
                    <span class="text-h6 font-weight-medium">Payment Schedule</span>
                  </v-card-title>

                  <v-card-text class="pa-4">
                    <v-list>
                      <v-list-item class="px-0">
                        <template v-slot:prepend>
                          <v-icon color="primary">mdi-calendar-refresh</v-icon>
                        </template>
                        <div>
                          <v-list-item-title class="font-weight-medium">
                            {{ getOrdinalDay(addressDetails.reoccurring_monthly_day) }} of every month
                          </v-list-item-title>
                          <v-list-item-subtitle>Payment Day</v-list-item-subtitle>
                        </div>
                      </v-list-item>

                      <v-list-item class="px-0">
                        <template v-slot:prepend>
                          <v-icon color="primary">mdi-calendar-start</v-icon>
                        </template>
                        <div>
                          <v-list-item-title class="font-weight-medium">
                            {{ formatDate(addressDetails.duration_from) }}
                          </v-list-item-title>
                          <v-list-item-subtitle>Start Date</v-list-item-subtitle>
                        </div>
                      </v-list-item>

                      <v-list-item class="px-0">
                        <template v-slot:prepend>
                          <v-icon color="primary">mdi-calendar-end</v-icon>
                        </template>
                        <div>
                          <v-list-item-title class="font-weight-medium">
                            {{ formatDate(addressDetails.duration_to) }}
                          </v-list-item-title>
                          <v-list-item-subtitle>End Date</v-list-item-subtitle>
                        </div>
                      </v-list-item>
                    </v-list>
                  </v-card-text>
                </v-card>
              </v-col>

              <!-- Tenancy Agreement Section -->
              <v-col cols="12" md="6" v-if="addressDetails?.tenancyAgreement">
                <v-card elevation="2" class="rounded-lg h-100">
                  <v-card-title class="d-flex align-center py-3 px-4 primary-bg">
                    <v-icon color="primary" size="32" class="mr-3">mdi-file-document</v-icon>
                    <span class="text-h6 font-weight-medium">Tenancy Agreement</span>
                  </v-card-title>

                  <v-card-text class="pa-4">
                    <v-sheet class="d-flex align-center justify-space-between flex-wrap">
                      <!-- File Icon & Name Section -->
                      <div class="d-flex align-center flex-grow-1 mb-3">
                        <v-avatar rounded="lg" color="purple-lighten-5" size="56" class="me-4">
                          <v-icon size="32" color="primary">
                            {{ getFileIcon(addressDetails.tenancyAgreement) }}
                          </v-icon>
                        </v-avatar>

                        <div class="flex-grow-1 min-width-0"> <!-- Add min-width-0 to allow text truncation -->
                          <div class="text-subtitle-1 font-weight-medium text-truncate mb-1">
                            {{ getFileName(addressDetails.tenancyAgreement) }}
                          </div>
                          <div class="text-body-2 text-medium-emphasis">
                            {{ getFileType(addressDetails.tenancyAgreement) }}
                          </div>
                        </div>
                      </div>

                      <!-- Action Buttons -->
                      <div class="d-flex align-items-center mt-md-0 mt-3">
                        <v-btn prepend-icon="mdi-eye" color="primary" variant="tonal"
                          :href="addressDetails.tenancy_agreement_url" target="_blank" class="me-2" size="small">
                          View
                        </v-btn>

                        <v-btn prepend-icon="mdi-download" color="primary" variant="elevated"
                          :href="addressDetails.tenancy_agreement_url" download size="small">
                          Download
                        </v-btn>
                      </div>
                    </v-sheet>
                  </v-card-text>

                </v-card>
              </v-col>

              <!-- Finance Details Section -->
              <!-- <v-col cols="12" md="12" v-if="personalInfo.account_goal === 'mortgage'"> -->
              <v-col cols="12" md="12" v-if="landlordFinance && landlordFinance.length > 0">
                <v-card elevation="2" class="rounded-lg h-100">
                  <v-card-title class="d-flex align-center py-3 px-4 primary-bg">
                    <v-icon color="primary" size="32" class="mr-3">mdi-bank</v-icon>
                    <span class="text-h6 font-weight-medium">{{ addressDetails.title }}</span>
                  </v-card-title>

                  <v-card-text class="pa-4">
                    <div v-for="finance in landlordFinance" :key="finance.id" class="mb-6">
                      <v-list>
                        <v-list-item class="px-0">
                          <template v-slot:prepend>
                            <v-icon color="primary" size="30">mdi-bank-transfer</v-icon>
                          </template>
                          <v-list-item-title class="text-uppercase font-weight-bold">
                            {{ finance.payment_method }}
                          </v-list-item-title>
                          <v-list-item-subtitle>Payment Method</v-list-item-subtitle>
                        </v-list-item>
                      </v-list>

                      <v-row v-if="finance.details" class="mt-2">
                        <template v-for="(item, key) in getFormattedDetails(finance.details)" :key="key">
                          <v-col cols="6" sm="4" md="3">
                            <!-- <v-card variant="flat" class="primary-bg pa-3 h-100">
                              <div class="text-h6 font-weight-bold text-truncate">{{ item.value }}</div>
                              <div class="text-body-2 text-medium-emphasis">{{ item.label }}</div>
                            </v-card> -->

                            <v-tooltip :text="item.value" location="top" open-on-hover>
                              <template v-slot:activator="{ props }">
                                <v-card variant="flat" class="primary-bg pa-3 h-100 hover-card" v-bind="props">
                                  <div class="text-h6 font-weight-bold text-truncate">{{ item.value }}</div>
                                  <div class="text-body-2 text-medium-emphasis">{{ item.label }}</div>
                                </v-card>
                              </template>
                            </v-tooltip>
                          </v-col>
                        </template>
                      </v-row>
                    </div>
                  </v-card-text>
                </v-card>
              </v-col>

              <!-- Co-applicants/Co-owners Section -->
              <v-col cols="12" v-if="coApplicant && coApplicant.length > 0">
                <v-card elevation="2" class="rounded-lg h-100">
                  <v-card-title class="d-flex align-center py-3 px-4 primary-bg">
                    <v-icon color="primary" size="32" class="mr-3">mdi-account-group</v-icon>
                    <span class="text-h6 font-weight-medium">
                      {{ personalInfo.account_goal === 'mortgage' ? 'Co-owners' : 'Co-applicants' }}
                    </span>
                  </v-card-title>

                  <v-card-text class="pa-4">
                    <v-list>
                      <v-list-item v-for="member in coApplicant" :key="member.id" class="px-0 mb-2">
                        <template v-slot:prepend>
                          <v-avatar color="primary" size="32">
                            <span class="text-white">{{ member.name.split(' ').map(n => n[0]).join('') }}</span>
                          </v-avatar>
                        </template>

                        <div class="d-flex flex-column">
                          <v-list-item-title class="font-weight-medium">{{ member.name }}</v-list-item-title>
                          <v-list-item-subtitle class="d-flex align-center">
                            <v-icon size="small" color="grey" class="mr-1">mdi-email</v-icon>
                            {{ member.email }}
                          </v-list-item-subtitle>
                        </div>

                        <template v-slot:append>
                          <div class="d-flex align-center">
                            <v-chip :color="member.status === 'accepted' ? 'success' : 'warning'" size="small"
                              class="text-capitalize mr-4">
                              {{ member.status }}
                            </v-chip>

                            <div class="text-right">
                              <div class="font-weight-medium">${{ formatAmount(member.amount) }}</div>
                              <div class="text-caption text-medium-emphasis">{{ member.percentage }}%</div>
                            </div>
                          </div>
                        </template>
                      </v-list-item>
                    </v-list>
                  </v-card-text>
                </v-card>
              </v-col>

            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import EditAddressModal from '@/components/EditAddressModal.vue'
import AddAddressModal from '@/components/SetupAddress.vue'
import ContentSkeleton from '@/components/skeleton/address.vue'
import { useAuthStore } from '@/stores/authStore';
import { storeToRefs } from 'pinia';
import { useNotifications } from '@/stores/useNotifications';
import { pageRequest, silentRequest } from "@/utils/axios"; // Updated Axios imports
export default {
  components: {
    EditAddressModal,
    AddAddressModal,
    ContentSkeleton
  },
  setup() {
    const authStore = useAuthStore();
    // Use storeToRefs for reactive state
    const { user } = storeToRefs(authStore);
    return {
      authStore,
      user,
    };
  },
  data() {
    const { unreadCount, refresh } = useNotifications();
    return {
      refreshNotifications: refresh,
      isSkeletonLoading: true,
      personalInfo: {
        first_name: "",
        last_name: "",
        email: "",
        phone: "",
        account_goal: "",
        account_type: "",
        payment_plan: "",
        payment_setup: ""
      },
      teamData: {
        team_members: [] // Default empty array for team members
      },
      coApplicant: [], // Default empty array
      buildCredit: {
        cc_limit: '',
        cc_due_date: '',
        id: null // Add id field with null default
      },
      addressDetails: {
        name: "",
        province: "",
        city: "",
        amount: "",
        tenancyAgreement: "",
        tenancy_agreement_url: "",
        title: "",
      },
      landlordFinance: [], // Default empty array
      loading: false,
    };
  },
  computed: {
    isCurrentUserAdmin() {
      const currentUserId = this.user?.id;
      if (!currentUserId || !this.teamAdmin) return false;
      return this.teamAdmin.user_id === currentUserId;
    },
    teamAdmin() {
      if (!this.teamData?.team_members?.length) return null;
      return this.teamData.team_members.find(
        member => member.role === "admin"
      );
    },
    teamMembers() {
      return this.teamData?.team_members || [];
    },
    initials() {
      const firstName = this.personalInfo?.first_name || '';
      const lastName = this.personalInfo?.last_name || '';
      return `${firstName.charAt(0)}${lastName.charAt(0)}`;
    },
    // Add this computed property to check if user can add address
    canAddAddress() {
      return !this.user?.has_address && !this.user?.is_team;
    },
    // Add this computed property to check if user can edit address
    canEditAddress() {
      // If user is in a team, only admin can edit
      if (this.user?.is_team) {
        return this.isCurrentUserAdmin && this.addressDetails;
      }

      // For individual users, only show if they have an address set up
      return this.user?.has_address && this.addressDetails;
    },
  },
  methods: {
    addNewAddress() {
      this.$refs.addModal.showModal();
    },
    editAddress() {
      if (this.addressDetails) {
        this.$refs.editModal.showModal();
      }
    },
    async handleRefreshData() {
      this.isSkeletonLoading = true; // Show skeleton
      await this.fetchData(); // Fetch new data
      this.isSkeletonLoading = false; // Hide skeleton
    },
    refreshData() {
      // Fetch updated data
    },
    getFormattedDetails(details) {
      try {
        const parsed = typeof details === 'string' ? JSON.parse(details) : details;

        // Define fields in specific order with exact labels matching the screenshot
        const fields = [
          { key: 'email', label: 'Email' },
          { key: 'businessName', label: 'Business Name' },
          { key: 'firstName', label: 'First Name' },
          { key: 'lastName', label: 'Last Name' },
          { key: 'middleName', label: 'Middle Name' },
          { key: 'accountNumber', label: 'Account Number' },
          { key: 'transitNumber', label: 'Transit Number' },
          { key: 'institutionNumber', label: 'Institution Number' },
          { key: 'bankAccountNumber', label: 'Bank Account Number' },
          { key: 'paymentFrequency', label: 'Payment Frequency' },
          { key: 'biWeeklyDueDate', label: 'Due Date' },
          { key: 'refNumber', label: 'Reference Number' },
          { key: 'lenderName', label: 'Lender Name' },
          { key: 'chequeName', label: 'Cheque Name' },
          { key: 'lenderAddress', label: 'Lender Address' }
        ];

        const formattedDetails = {};

        fields.forEach(field => {
          if (parsed[field.key]) {
            formattedDetails[field.key] = {
              label: field.label,
              value: field.key === 'biWeeklyDueDate'
                ? this.formatDate(parsed[field.key])
                : parsed[field.key]
            };
          }
        });

        return formattedDetails;
      } catch (e) {
        console.error('Error parsing details:', e);
        return {};
      }
    },

    getFileIcon(fileUrl) {
      const extension = this.getFileExtension(fileUrl).toLowerCase();
      switch (extension) {
        case 'pdf':
          return 'mdi-file-pdf-box';
        case 'jpg':
        case 'jpeg':
        case 'png':
          return 'mdi-file-image';
        default:
          return 'mdi-file-document';
      }
    },

    getFileName(fileUrl) {
      if (!fileUrl) return '';
      // Get the file name from the URL
      const parts = fileUrl.split('/');
      return parts[parts.length - 1];
    },

    getFileType(fileUrl) {
      const extension = this.getFileExtension(fileUrl);
      switch (extension.toLowerCase()) {
        case 'pdf':
          return 'PDF Document';
        case 'jpg':
        case 'jpeg':
          return 'JPEG Image';
        case 'png':
          return 'PNG Image';
        default:
          return 'Document';
      }
    },

    getFileExtension(fileUrl) {
      if (!fileUrl) return '';
      return fileUrl.split('.').pop() || '';
    },

    formatDate(dateString) {
      try {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });
      } catch (e) {
        return dateString;
      }
    },
    getOrdinalDay(day) {
      if (!day) return '';
      const suffix = ['th', 'st', 'nd', 'rd'];
      const remainder = day % 100;
      return day + (suffix[(remainder - 20) % 10] || suffix[remainder] || suffix[0]);
    },

    formatAmount(amount) {
      return parseFloat(amount).toLocaleString('en-US', { minimumFractionDigits: 2 });
    },

    async fetchData() {
      this.loading = true;
      try {
        const response = await silentRequest.get("/user-data", {
          params: {
            personal_info: true,
            address: true,
            landlord_finance: true,
            build_credit: true,
            co_applicant: true,
            team: true,
          },
        });

        const formattedAddress = {
          ...response.data.address,
          amount: response.data.address?.amount ? parseFloat(response.data.address.amount) : ""
        };

        this.addressDetails = formattedAddress ?? this.addressDetails;

        // Use nullish coalescing to provide defaults
        this.personalInfo = response.data.personal_info ?? this.personalInfo;
        this.teamData = response.data.teamData ?? { team_members: [] };
        // this.addressDetails = response.data.address ?? this.addressDetails;
        this.landlordFinance = response.data.landlord_finance ?? [];
        this.coApplicant = response.data.co_applicant ?? [];
        this.buildCredit = response.data.build_credit ?? this.buildCredit;
      } catch (error) {
        console.error("Error fetching data:", error);
      } finally {
        this.loading = false;
      }
    },

    formatPhone(phone) {
      return phone.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
    },

    formatAmount(amount) {
      return parseFloat(amount).toLocaleString('en-US', { minimumFractionDigits: 2 });
    },

  },
  async created() {
    try {
      await this.fetchData()
      console.log('Team Members:', this.addressDetails?.team?.team_members); // Debug log
      console.log('Current User:', this.user); // Debug log
    } finally {
      this.isSkeletonLoading = false
    }
  }
};
</script>

<style scoped>
.hover-card {
  transition: all 0.3s ease;
}

.hover-card:hover {
  background-color: rgba(var(--v-theme-primary), 0.1) !important;
  cursor: pointer;
}

.text-truncate {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  max-width: 100%;
}

.min-width-0 {
  min-width: 0;
}

.v-list-item__prepend {
  margin-right: 12px !important;
}

.w-100 {
  width: 100%;
}

.flex-shrink-0 {
  flex-shrink: 0;
}

/* Make sure buttons stay visible on medium screens */
@media (min-width: 960px) {
  .d-flex {
    flex-wrap: nowrap;
  }
}

/* Add some spacing for mobile view */
@media (max-width: 959px) {
  .mt-md-0 {
    margin-top: 12px;
  }
}

.text-h6 {
  font-size: 1.1rem !important;
  line-height: 1.4 !important;
}

.text-caption {
  font-size: 0.75rem !important;
  line-height: 1.2 !important;
}

.finance-details-list {
  margin-left: 2px;
}

.detail-row {
  line-height: 1.2;
}

.detail-row .text-body-2 {
  margin-bottom: 2px;
}

.details-card {
  background: #ffffff;
  border-radius: 12px;
}

.section-header {
  display: flex;
  align-items: center;
  border-bottom: 1px solid #e0e0e0;
  padding-bottom: 8px;
}

.v-list-item {
  min-height: 64px;
  padding: 12px 0;
}

.finance-details {
  background: transparent;
}

.payment-details {
  margin-bottom: 24px;
}

.detail-item {
  margin-bottom: 16px;
  display: flex;
  align-items: center;
}

.detail-item .label {
  color: rgba(0, 0, 0, 0.6);
  margin-right: 8px;
}

.detail-item .value {
  color: rgba(0, 0, 0, 0.87);
  font-weight: 500;
}

.v-chip {
  text-transform: capitalize;
}

.v-list {
  background: transparent;
}

.finance-details {
  background: transparent;
}

.details-grid {
  margin: 0 15px;
}

.detail-column {
  margin-bottom: 16px;
}

.text-truncate {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Optional: Add tooltip on hover for truncated text */
.text-truncate:hover {
  overflow: visible;
  white-space: normal;
  position: relative;
  z-index: 1;
}
</style>
