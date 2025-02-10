<template>
  <v-container>
    <!-- Team Overview -->
    <v-card class="mb-4">
      <v-card-title>Team: {{ capitalizeFirst(accountGoal) }}</v-card-title>
      <v-card-text>
        <p>Total Amount: <b class="amount-cell">{{ formatToCAD(totalAmount.toFixed(2)) }}</b></p>
        <p>Admin: {{ teamAdmin?.name || "No Admin Assigned" }}</p>
        <template v-if="isCurrentUserAdmin">
          <v-btn color="purple" rounded="lg" class="m-2" @click="openModal('add')">Add New Member</v-btn>
          <v-btn color="purple" rounded="lg" class="m-2" @click="openModal('adjust')">Adjust Amounts</v-btn>
          <v-btn color="red" rounded="lg" class="m-2" @click="openAdminLeaveModal">Leave Team</v-btn>
        </template>
      </v-card-text>
    </v-card>

    <!-- Team Member List -->
    <v-data-table :headers="tableHeaders" :loading="isTableLoading" loading-text="Loading team members..."
      :items="teamMembers" item-value="uuid" class="elevation-1" :key="teamMembers.length">

      <!-- Optional: Add a custom loading template -->
      <template v-slot:loading>
        <v-skeleton-loader type="table-row" class="my-2"></v-skeleton-loader>
      </template>

      <!-- Index/SN column -->
      <template #item.index="{ index }">
        {{ index + 1 }}
      </template>

      <!-- Role column - capitalize first letter -->
      <template #item.role="{ item }">
        {{ capitalizeFirst(item.role) }}
      </template>

      <!-- Status column -->
      <template #item.status="{ item }">
        <v-chip :color="getStatusColor(item.status)" :text="capitalizeFirst(item.status)" small></v-chip>
      </template>

      <!-- Percentage column -->
      <template #item.percentage="{ item }">
        {{ item.percentage }}%
      </template>

      <!-- Amount column -->
      <template #item.amount="{ item }">
        <span class="amount-cell">{{ formatToCAD(parseFloat(item.amount).toFixed(2)) }}</span>
      </template>

      <!-- Actions column -->
      <template #item.actions="{ item }">
        <div class="d-flex gap-2" v-if="isCurrentUserAdmin">
          <template v-if="item.status === 'pending'">
            <v-btn size="x-small" color="primary" :loading="item.isResending"
              :disabled="item.nextInviteTime && new Date() < new Date(item.nextInviteTime)"
              @click="confirmResendInvite(item)">
              {{ getResendButtonText(item) }}
            </v-btn>
          </template>
          <v-btn size="x-small" color="error" :disabled="item.role === 'admin'" @click="openModal('remove', item)">
            Remove
          </v-btn>
        </div>
      </template>
    </v-data-table>

    <!-- Add Confirmation Dialog -->
    <v-dialog v-model="showResendConfirmDialog" max-width="400px">
      <v-card>
        <v-card-title>Confirm Resend Invite</v-card-title>
        <v-card-text>
          Are you sure you want to resend invite to {{ selectedMember?.email }}?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="resendInvite">Yes</v-btn>
          <v-btn color="grey" @click="showResendConfirmDialog = false">No</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="showEditAmountsModal" persistent max-width="700px">
      <v-card>
        <v-card-title>
          {{ currentMode === 'add' ? 'Add New Member' : currentMode === 'remove' ? 'Remove Member' :
            'Adjust Contributions' }}
        </v-card-title>
        <v-card-text>
          <v-alert v-if="currentMode === 'remove'" type="warning" color="warning" class="mb-4" variant="tonal">
            Please redistribute <strong>{{ selectedMember?.name }}'s</strong> percentage
            ({{ selectedMember?.percentage }}%) among remaining members.
            Total must equal 100% to proceed.
          </v-alert>
          <!-- Balance Display -->
          <div>
            <p><strong>Total Rent/Mortgage:</strong> ${{ totalAmount.toFixed(2) }}</p>
            <p><strong>Total Assigned:</strong> ${{ totalAssigned.toFixed(2) }}
              ({{ totalAssignedPercentage.toFixed(2) }}%)
            </p>
            <p>
              <strong>Balance:</strong>
              <span :class="remainingBalance === 0 ? 'text-success' : 'text-error'">
                ${{ remainingBalance.toFixed(2) }} ({{ remainingBalancePercentage.toFixed(2) }}%)
              </span>
            </p>
          </div>
          <hr />
          <!-- Members Form -->
          <v-form ref="editAmountsForm">
            <v-container>
              <!-- Editable Members -->
              <v-row v-for="(member, index) in editableMembers" :key="member.uuid" class="align-center"
                :class="{ 'active-member': currentMode === 'remove' }">
                <v-col cols="4">
                  <p>{{ member.name }}</p>
                </v-col>
                <v-col cols="4">
                  <v-text-field v-model.number="member.percentage" label="Percentage (%)" type="number"
                    :readonly="member.readOnly" @input="updateAmountFromPercentage(member)"
                    :rules="[rules.required, rules.positivePercentage]"
                    :hint="currentMode === 'remove' ? `Previous: ${originalMembers.find(m => m.uuid === member.uuid)?.percentage}%` : ''"
                    persistent-hint required></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field v-model.number="member.amount" label="Amount ($)" type="number" readonly></v-text-field>
                </v-col>
              </v-row>

              <!-- Show Removed Member (only in remove mode) -->
              <v-row v-if="currentMode === 'remove' && selectedMember" class="align-center removed-member-row">
                <v-col cols="4">
                  <p class="text-decoration-line-through grey--text">
                    {{ selectedMember.name }} (Being Removed)
                  </p>
                </v-col>
                <v-col cols="4">
                  <v-text-field :value="selectedMember.percentage" label="Previous Percentage (%)" disabled readonly
                    class="grey--text"></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field :value="selectedMember.amount" label="Previous Amount ($)" disabled readonly
                    class="grey--text"></v-text-field>
                </v-col>
              </v-row>
            </v-container>
          </v-form>

          <v-container v-if="currentMode == 'add'">
            <!-- Form for New Member -->
            <v-form ref="addMemberForm" @submit.prevent="submitChanges" v-model="isFormValid">
              <v-container>
                <!-- New Member Fields -->
                <v-row>
                  <v-col cols="6">
                    <v-text-field v-model="newMemberForm.firstName" label="First Name" :rules="[rules.required]"
                      required></v-text-field>
                  </v-col>
                  <v-col cols="6">
                    <v-text-field v-model="newMemberForm.lastName" label="Last Name" :rules="[rules.required]"
                      required></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12">
                    <v-text-field v-model="newMemberForm.email" label="Email" :rules="[rules.required, rules.email]"
                      required></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6">
                    <!-- <v-text-field 
                          v-model.number="newMemberForm.percentage" 
                          label="Percentage (%)" 
                          type="number"
                          :rules="[rules.required, rules.positivePercentage]"
                          @input="updateAmountFromPercentage(newMemberForm)" 
                          required
                      ></v-text-field> -->
                    <v-text-field v-model.number="newMemberForm.percentage" label="Percentage (%)" type="number" min="1"
                      max="100" :rules="[rules.required, rules.positivePercentage]"
                      @input="updateAmountFromPercentage(newMemberForm)" required></v-text-field>
                  </v-col>
                  <v-col cols="6">
                    <v-text-field v-model="calculatedNewMemberAmount" label="Amount ($)" type="text"
                      readonly></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-form>
          </v-container>

        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" :disabled="remainingBalance !== 0" :loading="isSubmitting" @click="submitChanges">
            {{ currentMode === 'add' ? 'Send Invite' : currentMode === 'remove' ? 'Confirm Removal' : 'Submit Changes'
            }}
          </v-btn>
          <v-btn color="grey" @click="closeEditAmountsModal" :disabled="isSubmitting">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </v-container>

  <!-- <v-dialog v-model="showAdminLeaveModal" persistent max-width="500px">
    <v-card>
      <v-card-title>Admin Leaving Options</v-card-title>
      <v-card-text>
        <p>You are about to leave the team. Choose one of the following options:</p>
        <v-select v-if="teamMembers.length > 1" v-model="newAdminUuid" :items="promotableMembers" item-text="name"
          item-value="uuid" label="Select New Admin"></v-select>
        <p v-else>
          There are no other members in this team. You can dissolve the team.
        </p>
      </v-card-text>
      <v-card-actions>
        <v-btn color="primary" v-if="teamMembers.length > 1" @click="promoteNewAdmin">
          Promote New Admin
        </v-btn>
        <v-btn color="red" @click="dissolveTeam">Dissolve Team</v-btn>
        <v-btn color="grey" @click="closeAdminLeaveModal">Cancel</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog> -->

<!-- Admin Transfer Modal -->
<v-dialog v-model="showAdminLeaveModal" persistent max-width="500px">
    <v-card>
      <v-card-title>Admin Leaving Options</v-card-title>
      <v-card-text>
        <p>You are about to leave the team. Choose one of the following options:</p>
        <v-select 
          v-if="teamMembers.length > 1" 
          v-model="newAdminUuid" 
          :items="promotableMembers" 
          item-text="name"
          item-value="uuid" 
          label="Select New Admin"
          :error-messages="adminSelectError"
          @input="clearAdminError"
        ></v-select>
        <p v-else>There are no other members in this team. You can dissolve the team.</p>
      </v-card-text>
      <v-card-actions>
        <v-btn 
          color="primary" 
          v-if="teamMembers.length > 1" 
          @click="promoteNewAdmin"
          :loading="isPromoting"
        >
          Promote New Admin
        </v-btn>
        <v-btn color="red" @click="showDissolveConfirmation">Dissolve Team</v-btn>
        <v-btn color="grey" @click="closeAdminLeaveModal">Cancel</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <!-- Dissolve Confirmation Modal -->
  <v-dialog v-model="showDissolveModal" persistent max-width="500px">
    <v-card>
      <v-card-title>Confirm Team Dissolution</v-card-title>
      <v-card-text>
        <p>A verification code has been sent to your email.</p>
        <v-text-field
 v-model="verificationCode"
 label="Enter Verification Code"
 :error-messages="codeError"
 @input="clearCodeError"
 maxlength="6"
 :rules="[
   v => !!v || 'Code is required',
   v => /^[0-9]{6}$/.test(v) || 'Code must be 6 digits'
 ]"
 type="text"
 @keypress="numericOnly"
></v-text-field>
        <v-checkbox
          v-model="dissolveConfirmed"
          label="I understand this action cannot be undone"
          :error-messages="checkboxError"
          @change="clearCheckboxError"
        ></v-checkbox>
      </v-card-text>
      <v-card-actions>
        <v-btn 
          color="red" 
          @click="confirmDissolveTeam"
          :loading="isDissolving"
          :disabled="!dissolveConfirmed || !verificationCode"
        >
          Dissolve Team
        </v-btn>
        <v-btn color="grey" @click="closeDissolveModal">Cancel</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import {
  useToast
} from 'vue-toastification';
import {
  pageRequest,
  silentRequest
} from "@/utils/axios"; // Updated Axios imports
import { useAuthStore } from '@/stores/authStore';
import { storeToRefs } from 'pinia';
import { useNotifications } from '@/stores/useNotifications';

export default {
  setup() {
    const toast = useToast();
    const authStore = useAuthStore();
    // Use storeToRefs for reactive state
    const { user } = storeToRefs(authStore);
    return {
      authStore,
      user,
      toast
    };
  },
  data() {
    const { unreadCount, refresh } = useNotifications();
    return {
      refreshNotifications: refresh,
      isTableLoading: false,  // Add this line
      isFormValid: false,
      errorMessage: '',
      originalMembers: [], // Store original state
      editableMembers: [], // For temporary edits
      originalNewMember: {
        firstName: "",
        lastName: "",
        email: "",
        percentage: 0,
        amount: 0,
      },
      accountGoal: '',
      totalAmount: 0,
      team: {
        name: "Development Team"
      },
      team: {},
      teamMembers: [],
      isSubmitting: false,
      tableHeaders: [{
        text: "SN",
        value: "index", // Change this to "index" instead of "SN"
        align: 'center',
        width: '50px'
      },
      {
        text: "Name",
        value: "name"
      },
      {
        text: "Email",
        value: "email"
      },
      {
        text: "Role",
        value: "role"
      },
      {
        text: "Status",
        value: "status"
      },
      {
        text: "Percentage",
        value: "percentage"
      },
      {
        text: "Amount",
        value: "amount"
      },
      {
        text: "Actions",
        value: "actions",
        sortable: false
      },
      ],
      currentMode: null, // 'add', 'remove', or 'adjust'
      editableMembers: this.teamMembers,
      rules: {
        required: (value) => {
          if (value === null || value === undefined || value === '') return "This field is required.";
          return true;
        },
        email: (value) => {
          if (!value) return true; // Let required rule handle empty values
          return /.+@.+\..+/.test(value) || "Enter a valid email address.";
        },
        positiveNumber: (value) => {
          if (!value) return true; // Let required rule handle empty values
          const sanitizedValue = String(value).replace(/[^0-9.]/g, "");
          if (sanitizedValue === "") return "The amount must be a positive number.";
          const number = parseFloat(sanitizedValue);
          return number > 0 || "The amount must be a positive number.";
        },
        positivePercentage: (value) => {
          if (value === null || value === undefined || value === '') return "This field is required.";
          const number = parseFloat(value);
          if (isNaN(number)) return "Please enter a valid number";
          return (number >= 1 && number <= 100) || "Percentage must be between 1 and 100.";
        },
        // positivePercentage: (value) => {
        //   if (!value) return true; // Let required rule handle empty values
        //   const number = parseFloat(value);
        //   if (isNaN(number)) return "Please enter a valid number";
        //   return (number >= 1 && number <= 100) || "Percentage must be between 1 and 100.";
        // },
      },
      showEditAmountsModal: false,
      showModal: false,
      showAdminLeaveModal: false,
      editMode: false,
      newAdminUuid: null,
      newMemberForm: {
        firstName: "",
        lastName: "",
        email: "",
        percentage: 0,
        amount: 0,
      },
      modalForm: {
        uuid: null,
        firstName: "",
        lastName: "",
        email: "",
        role: "member",
        status: "pending",
        amount: 0,
      },
      showResendConfirmDialog: false,
      selectedMember: null,

      showDissolveModal: false,
      verificationCode: '',
      dissolveConfirmed: false,
      isPromoting: false,
      isDissolving: false,
      adminSelectError: '',
      codeError: '',
      checkboxError: '',
    };
  },

  computed: {
    isCurrentUserAdmin() {
      const currentUserId = this.user?.id;
      if (!currentUserId || !this.teamAdmin) return false;
      return this.teamAdmin.user_id === currentUserId;
    },
    sortedTeamMembers() {
      return [...this.teamMembers].sort((a, b) => {
        // Sort by role (admin first)
        if (a.role === 'admin' && b.role !== 'admin') return -1;
        if (a.role !== 'admin' && b.role === 'admin') return 1;

        // Then by status (accepted before pending)
        if (a.status === 'accepted' && b.status !== 'accepted') return -1;
        if (a.status !== 'accepted' && b.status === 'accepted') return 1;

        return 0;
      });
    },
    teamAdmin() {
      return this.teamMembers.find((member) => member.role === "admin");
    },
    activeMembers() {
      return this.teamMembers.filter((member) => member.status === "accepted");
    },
    acceptedMembers() {
      return this.teamMembers.filter((member) => member.status === "accepted");
    },
    remainingBalance() {
      return this.totalAmount - this.totalAssigned;
    },
    // totalAssigned() {
    //     return this.acceptedMembers.reduce((sum, member) => sum + (member.amount || 0), 0);
    // },
    // totalAssignedPercentage() {
    //     return (this.totalAssigned / this.totalAmount) * 100;
    // },
    remainingBalance() {
      return this.totalAmount - this.totalAssigned;
    },
    // remainingBalancePercentage() {
    //     return (this.remainingBalance / this.totalAmount) * 100;
    // },
    totalAssignedAmount() {
      return this.activeMembers.reduce((total, member) => total + member.amount, 0);
    },
    totalAssigned() {
      // When in modal, use editableMembers
      if (this.showEditAmountsModal) {
        let total = this.editableMembers.reduce((sum, member) => sum + (member.amount || 0), 0);
        // Add new member's amount if in add mode
        if (this.currentMode === 'add' && this.newMemberForm.amount) {
          total += this.newMemberForm.amount;
        }
        return total;
      }
      // Otherwise use accepted members for main display
      return this.acceptedMembers.reduce((sum, member) => sum + (member.amount || 0), 0);
    },

    totalAssignedPercentage() {
      if (this.showEditAmountsModal) {
        let totalPercentage = this.editableMembers.reduce((sum, member) =>
          sum + (member.percentage || 0), 0);
        // Add new member's percentage if in add mode
        if (this.currentMode === 'add' && this.newMemberForm.percentage) {
          totalPercentage += this.newMemberForm.percentage;
        }
        return totalPercentage;
      }
      return (this.totalAssigned / this.totalAmount) * 100;
    },

    remainingBalance() {
      return this.totalAmount - this.totalAssigned;
    },

    remainingBalancePercentage() {
      if (this.showEditAmountsModal) {
        return 100 - this.totalAssignedPercentage;
      }
      return (this.remainingBalance / this.totalAmount) * 100;
    },

    promotableMembers() {
      return this.teamMembers
        .filter((member) => member.status === "accepted" && member.role === "member")
        .map((member) => ({
          uuid: member.uuid,
          title: member.name, // Just use name since it's already combined
          subtitle: member.email
        }));
    },
    isFormValid() {
      return (
        this.newMemberForm.firstName &&
        this.newMemberForm.lastName &&
        this.newMemberForm.email &&
        this.newMemberForm.percentage > 0
      );
    },
    calculatedNewMemberAmount() {
      console.log(this.newMemberForm);
      if (!this.newMemberForm.amount) return '0.00';
      console.log(this.newMemberForm.amount);

      return this.newMemberForm.amount;
    },
  },

  methods: {
    updateAmountFromPercentage(member) {
      // Check if we're dealing with newMemberForm or existing member
      const isNewMemberForm = member === this.newMemberForm;


      // Handle percentage validation
      if (typeof member.percentage !== "number" || isNaN(member.percentage)) {
        member.percentage = 0;
      }

      // Calculate amount
      const percentage = parseFloat(member.percentage) || 0;
      const calculatedAmount = ((percentage / 100) * this.totalAmount);

      // Update the amount based on which object we're working with
      if (isNewMemberForm) {
        this.newMemberForm.percentage = percentage;
        this.newMemberForm.amount = parseFloat(calculatedAmount.toFixed(2));
      } else {
        member.amount = parseFloat(calculatedAmount.toFixed(2));
      }

      // For debugging
      // console.log('Updated member:', {
      //   isNewMemberForm,
      //   percentage,
      //   calculatedAmount,
      //   finalAmount: member.amount
      // });
    },
    async validateForm() {
      // Get the form reference
      const form = this.$refs.addMemberForm;

      // Clear any existing error messages first
      this.errorMessage = '';

      // Check if form exists and is valid
      if (!form) return false;

      // Validate all form fields
      const isValid = await form.validate();
      return isValid;
    },

    async submitChangesOld() {
      if (!this.isCurrentUserAdmin) {
        this.toast.error('Only team admin can perform this action');
        return;
      }
      // First validate the form if in add mode
      if (this.currentMode === "add") {
        const isFormValid = await this.validateForm();
        if (!isFormValid.valid) return;
      }

      if (!this.validatePercentageTotal()) {
        this.toast.error("Total percentage must equal 100%");
        return;
      }

      try {
        this.isSubmitting = true;
        if (this.currentMode === "add") {
          await this.addNewMember();
        } else if (this.currentMode === "remove") {
          await this.removeMember(this.selectedMember);
        } else {
          await this.adjustPercentages();
        }

        // Only update the original data after successful API call
        await this.fetchTeamData(); // Refresh data from server
        this.closeEditAmountsModal();
        this.isSubmitting = false;
      } catch (error) {
        // Revert changes on error
        this.isSubmitting = false;
        this.teamMembers = JSON.parse(JSON.stringify(this.originalMembers));
        this.toast.error(error.response?.data?.message || "Error saving changes");
      }
    },
    async submitChanges() {
      if (!this.isCurrentUserAdmin) {
        this.toast.error('Only team admin can perform this action');
        return;
      }

      // First validate the form if in add mode
      if (this.currentMode === "add") {
        const isFormValid = await this.validateForm();
        if (!isFormValid.valid) return;
      }

      if (!this.validatePercentageTotal()) {
        this.toast.error("Total percentage must equal 100%");
        return;
      }

      try {
        this.isSubmitting = true;
        let success = false;

        if (this.currentMode === "add") {
          success = await this.addNewMember();
        } else if (this.currentMode === "remove") {
          success = await this.removeMember();
        } else {
          success = await this.adjustPercentages();
        }

        // Only close modal and refresh data if operation was successful
        // if (success) {
        //   await this.fetchTeamData(); // Refresh data from server
        //   this.closeEditAmountsModal(); // Close modal only on success
        // }

        if (success) {
          await this.refreshNotifications(); // Refresh notifications
          // Modal is already closed in the individual methods
          this.isSubmitting = false;
        } else {
          throw new Error('Operation failed');
        }

      } catch (error) {
        this.toast.error(error.response?.data?.message || "Error saving changes");
      } finally {
        this.isSubmitting = false;
      }
    },
    // API Calls
    async fetchTeamDataOld() {
      try {
        const response = await silentRequest.get('/api/team/members');
        if (response.data.team) {
          this.team = response.data.team;
          this.accountGoal = response.data.totalAmount.user.account_goal;
          this.totalAmount = parseFloat(response.data.totalAmount.amount || 0);

          this.teamMembers = response.data.team.team_members.map(member => ({
            ...member,
            percentage: parseFloat(member.percentage || 0),
            amount: this.calculateMemberAmount(parseFloat(member.percentage || 0), this.totalAmount),
            isResending: false,
            nextInviteTime: null
          }));

        }
      } catch (error) {
        this.toast.error('Error fetching team data');
        console.error('Error:', error);
      }
    },
    async fetchTeamData2() {
      try {
        this.isTableLoading = true;  // Start loading
        const response = await silentRequest.get('/api/team/members');
        if (response.data.team) {
          this.team = response.data.team;
          this.accountGoal = response.data.totalAmount.user.account_goal;
          this.totalAmount = parseFloat(response.data.totalAmount.amount || 0);

          // Update team members with calculated values
          this.teamMembers = response.data.team.team_members.map(member => ({
            ...member,
            percentage: parseFloat(member.percentage || 0),
            amount: this.calculateMemberAmount(parseFloat(member.percentage || 0), this.totalAmount),
            isResending: false,
            nextInviteTime: member.last_invite_sent_at ?
              new Date(member.last_invite_sent_at).getTime() + (5 * 60 * 60 * 1000) : null // 5 hours cooldown
          }));


          // Sort team members if needed
          this.teamMembers.sort((a, b) => {
            // Admin first
            if (a.role === 'admin') return -1;
            if (b.role === 'admin') return 1;
            // Then by status
            if (a.status === 'accepted' && b.status !== 'accepted') return -1;
            if (a.status !== 'accepted' && b.status === 'accepted') return 1;
            return 0;
          });
          await this.refreshNotifications(); // Refresh notifications

          return true; // Return true for successful fetch
        }
        return false; // Return false if no team data
      } catch (error) {
        this.toast.error(error.response?.data?.message || 'Error fetching team data');
        console.error('Error:', error);
        return false;
      } finally {
        this.isTableLoading = false;  // Stop loading regardless of outcome
      }
    },
    async fetchTeamData() {
  try {
    this.isTableLoading = true;  // Start loading
    const response = await silentRequest.get('/api/team/members');
    
    if (response.data.team) {
      this.team = response.data.team;
      
      // Handle case where totalAmount is null
      if (!response.data.totalAmount) {
        this.toast.warning('Please add address details to manage team contributions');
        this.accountGoal = '';
        this.totalAmount = 0;
        this.teamMembers = [];
        return false;
      }

      // Set account goal and total amount
      this.accountGoal = response.data.totalAmount?.user?.account_goal || '';
      this.totalAmount = parseFloat(response.data.totalAmount?.amount || 0);

      // If team exists but address_id is null
      if (!this.team.address_id) {
        this.toast.warning('Please add address details to manage team contributions');
        this.teamMembers = [];
        return false;
      }

      // Update team members with calculated values
      this.teamMembers = response.data.team.team_members.map(member => ({
        ...member,
        percentage: parseFloat(member.percentage || 0),
        amount: this.calculateMemberAmount(
          parseFloat(member.percentage || 0), 
          this.totalAmount || 0
        ),
        isResending: false,
        nextInviteTime: member.last_invite_sent_at ?
          new Date(member.last_invite_sent_at).getTime() + (5 * 60 * 60 * 1000) : null
      }));

      // Sort team members
      this.teamMembers.sort((a, b) => {
        // Admin first
        if (a.role === 'admin') return -1;
        if (b.role === 'admin') return 1;
        // Then by status
        if (a.status === 'accepted' && b.status !== 'accepted') return -1;
        if (a.status !== 'accepted' && b.status === 'accepted') return 1;
        return 0;
      });

      await this.refreshNotifications();
      return true;
    }

    // No team data
    this.toast.warning('No team data found');
    return false;

  } catch (error) {
    this.toast.error(error.response?.data?.message || 'Error fetching team data');
    console.error('Error:', error);
    return false;
  } finally {
    this.isTableLoading = false;
  }
},
    async addNewMember() {
      try {
        const membersPercentages = this.editableMembers.map(member => ({
          uuid: member.uuid,
          percentage: member.percentage,
          amount: member.amount,
        }));

        const response = await silentRequest.post('/api/team/members', {
          name: `${this.newMemberForm.firstName} ${this.newMemberForm.lastName}`,
          email: this.newMemberForm.email,
          percentage: this.newMemberForm.percentage,
          amount: this.newMemberForm.amount,
          members_percentages: membersPercentages,
        });

        if (response.data.status === 'success') {
          this.toast.success(response.data.message || 'New member added successfully');

          this.closeEditAmountsModal();

          // Update team and members data
          if (response.data.team) {
            this.team = response.data.team;
            // Create a completely new array for teamMembers
            const updatedMembers = response.data.team.team_members.map(member => ({
              ...member,
              percentage: parseFloat(member.percentage || 0),
              amount: this.calculateMemberAmount(parseFloat(member.percentage || 0), this.totalAmount),
              isResending: false,
              nextInviteTime: null
            }));
            this.teamMembers = [...updatedMembers]; // Force a new array reference
          }

          return true;
        } else {
          throw new Error(response.data.message || 'Failed to add member');
        }

      } catch (error) {
        const errorMessage = error.response?.data?.message || error.message || 'Error adding new member';
        this.toast.error(errorMessage);
        console.error('Add member error:', error);
        return false; // Return false to indicate failure
      }
    },
    async removeMember() {
      try {
        if (!this.selectedMember) {
          this.toast.error('No member selected for removal');
          return;
        }

        // Calculate updated percentages for remaining members
        const membersPercentages = this.editableMembers.map(member => ({
          uuid: member.uuid,
          percentage: member.percentage,
          amount: member.amount
        }));

        // Make API call to remove member and update percentages
        const response = await silentRequest.delete('/api/team/members', {
          data: {
            member_uuid: this.selectedMember.uuid,
            members_percentages: membersPercentages
          }
        });

        if (response.data.status === 'success') {
          this.toast.success(`${this.selectedMember.name} has been removed from the team`);

          this.closeEditAmountsModal();

          // Update team and members data
          if (response.data.team) {
            this.team = response.data.team;
            // Create a completely new array for teamMembers
            const updatedMembers = response.data.team.team_members.map(member => ({
              ...member,
              percentage: parseFloat(member.percentage || 0),
              amount: this.calculateMemberAmount(parseFloat(member.percentage || 0), this.totalAmount),
              isResending: false,
              nextInviteTime: null
            }));
            this.teamMembers = [...updatedMembers]; // Force a new array reference

          }

          return true;
        } else {
          throw new Error(response.data.message || 'Failed to remove member');
        }

      } catch (error) {
        console.error('Remove member error:', error);
        this.toast.error(error.response?.data?.message || 'Error removing member from team');
      }
    },
    async adjustPercentages() {
      try {
        const membersPercentages = this.editableMembers.map(member => ({
          uuid: member.uuid,
          percentage: member.percentage,
          amount: member.amount,
        }));

        const response = await silentRequest.put('/api/team/members/percentages', {
          members_percentages: membersPercentages,
        });

        if (response.data.status === 'success') {
          if (response.data.team) {
            // Update the team data directly from response
            this.team = response.data.team;
            const updatedMembers = response.data.team.team_members.map(member => ({
              ...member,
              percentage: parseFloat(member.percentage || 0),
              amount: this.calculateMemberAmount(parseFloat(member.percentage || 0), this.totalAmount),
              isResending: false,
              nextInviteTime: null
            }));
            this.teamMembers = [...updatedMembers]; // Force a new array reference

            this.toast.success(response.data.message || 'Percentages updated successfully');
            return true;
          }
        }

        return false;

      } catch (error) {
        if (error.response) {
          this.toast.error(error.response.data.message || 'Error updating percentages');
        } else {
          this.toast.error('Network error occurred');
        }
        console.error('Adjust percentages error:', error);
        return false;
      }
    },
    formatToCAD(value) {
      return new Intl.NumberFormat('en-CA', {
        style: 'currency',
        currency: 'CAD',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      }).format(value);
    },
    calculateMemberAmount(percentage, total) {
      if (!percentage || !total) return 0;
      const amount = (percentage / 100) * total;
      return parseFloat(amount.toFixed(2)); // Round to 2 decimal places
    },

    getStatusColor(status) {
      switch (status) {
        case 'accepted':
          return 'success';
        case 'pending':
          return 'warning';
        case 'declined':
          return 'error';
        default:
          return 'grey';
      }
    },

    capitalizeFirst(str) {
      if (!str) return '';
      return str.charAt(0).toUpperCase() + str.slice(1);
    },

    getResendButtonText(member) {
      if (member.nextInviteTime && new Date() < new Date(member.nextInviteTime)) {
        const timeLeft = this.getTimeLeft(member.nextInviteTime);
        return `Wait ${timeLeft}`;
      }
      return 'Resend Invite';
    },

    getTimeLeft(nextTime) {
      const diff = new Date(nextTime) - new Date();
      const hours = Math.floor(diff / (1000 * 60 * 60));
      const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      return `${hours}h ${minutes}m`;
    },

    confirmResendInvite(member) {
      if (!this.isCurrentUserAdmin) {
        this.toast.error('Only team admin can perform this action');
        return;
      }
      this.selectedMember = member;
      this.showResendConfirmDialog = true;
    },

    async resendInvite() {
      if (!this.isCurrentUserAdmin) {
        this.toast.error('Only team admin can perform this action');
        return;
      }
      if (!this.selectedMember) return;

      try {
        this.selectedMember.isResending = true;
        await silentRequest.post('/api/team/members/resend-invite', {
          member_uuid: this.selectedMember.uuid
        });

        // Set next invite time to 5 hours from now
        this.selectedMember.nextInviteTime = new Date(Date.now() + (5 * 60 * 60 * 1000)).toISOString();
        this.toast.success(`Invite resent to ${this.selectedMember.email}`);
      } catch (error) {
        this.toast.error('Error resending invite');
        console.error('Error:', error);
      } finally {
        this.selectedMember.isResending = false;
        this.showResendConfirmDialog = false;
        this.selectedMember = null;
      }
    },

    openModal(mode, member = null) {
      if (!this.isCurrentUserAdmin) {
        this.toast.error('Only team admin can perform this action');
        return;
      }
      this.currentMode = mode;

      // Store original state
      this.originalMembers = JSON.parse(JSON.stringify(this.teamMembers));

      if (mode === "add") {
        // Reset new member form to original state
        this.newMemberForm = {
          ...this.originalNewMember
        };
        this.editableMembers = JSON.parse(JSON.stringify(this.teamMembers));
      } else if (mode === "remove") {
        this.editableMembers = this.teamMembers.filter((m) => m.uuid !== member.uuid)
          .map(m => ({
            ...m
          }));
        this.selectedMember = {
          ...member
        };
      } else {
        this.editableMembers = JSON.parse(JSON.stringify(this.teamMembers));
      }

      this.showEditAmountsModal = true;
    },

    validatePercentageTotal() {
      let total = this.editableMembers.reduce((sum, member) => {
        // Convert percentage to number and default to 0 if invalid
        const percentage = parseFloat(member?.percentage) || 0;
        return sum + percentage;
      }, 0);

      if (this.currentMode === 'add') {
        // Safely add new member percentage
        const newPercentage = parseFloat(this.newMemberForm?.percentage) || 0;
        total += newPercentage;
      }

      return Math.abs(total - 100) < 0.01;
    },

    async addNewMemberOld() {
      const membersPercentages = this.editableMembers.map(member => ({
        uuid: member.uuid,
        percentage: member.percentage,
        amount: member.amount,
      }));

      await silentRequest.post('/api/team/members', {
        name: `${this.newMemberForm.firstName} ${this.newMemberForm.lastName}`,
        email: this.newMemberForm.email,
        percentage: this.newMemberForm.percentage,
        amount: this.newMemberForm.amount,
        members_percentages: membersPercentages,
      });
    },

    sendInvite() {
      const newMember = this.editableMembers.find((member) => member.email === this.newMemberForm.email);
      this.teamMembers.push(newMember);
      this.toast.success(`Invite sent to ${newMember.email}`);
    },
    updateContributions() {
      this.teamMembers = [...this.editableMembers];
      this.toast.success("Contributions updated successfully.");
    },

    sanitizeInput(field) {
      const value = this.modalForm[field];
      if (value === null || value === undefined || value === "") {
        this.modalForm[field] = "";
        return;
      }
      // Remove invalid characters and keep only positive numbers
      this.modalForm[field] = String(value).replace(/[^0-9.]/g, "");
    },

    sanitizeAmount(member) {
      if (typeof member.amount !== "number" || isNaN(member.amount)) {
        member.amount = 0; // Default invalid amounts to 0
      }
    },

    sanitizePercentage(member) {
      // Ensure percentage is valid
      if (member.percentage < 0) {
        member.percentage = 0;
      } else if (member.percentage > 100) {
        member.percentage = 100;
      }
    },

    async editMember(member) {
      this.showModal = true;
      this.editMode = true;
      this.modalForm = {
        ...member
      };
    },

    openAddMemberModal() {
      this.showModal = true;
      this.editMode = false;
      this.modalForm = {
        uuid: null,
        firstName: "",
        lastName: "",
        email: "",
        role: "member",
        status: "pending",
        amount: 0,
      };
    },

    closeModal() {
      this.showModal = false;
      this.$refs.form.reset();
    },
    
    closeEditAmountsModal() {
      this.showEditAmountsModal = false;
      this.teamMembers = JSON.parse(JSON.stringify(this.originalMembers));
      this.newMemberForm = {
        ...this.originalNewMember
      };
      this.currentMode = null;
      this.editableMembers = [];
    },

    validateAmountDistribution() {
      const totalAssigned = this.totalAssignedAmount;
      if (totalAssigned !== this.totalAmount) {
        this.toast.warning(
          `Total assigned amount (${totalAssigned}) does not match the rent/mortgage amount (${this.totalAmount}). Please adjust!`
        );
      }
    },

    openAdminLeaveModal() {
      if (!this.isCurrentUserAdmin) {
        this.toast.error('Only team admin can perform this action');
        return;
      }
      this.showAdminLeaveModal = true;
    },

    closeAdminLeaveModal() {
      this.showAdminLeaveModal = false;
      this.newAdminUuid = null;
    },
    async promoteNewAdmin() {
      if (!this.newAdminUuid) {
        this.adminSelectError = "Please select a new admin";
        return;
      }

      try {
        this.isPromoting = true;
        const response = await silentRequest.post('/api/team/admin/transfer', {
          new_admin_uuid: this.newAdminUuid
        });

        if (response.data.success) {
          await this.fetchTeamData();
          this.toast.success(response.data.message);
          this.closeAdminLeaveModal();
        }
      } catch (error) {
        this.handleError(error);
      } finally {
        this.isPromoting = false;
      }
    },

    showDissolveConfirmation() {
      this.showDissolveModal = true;
      // Backend will send verification code email
      silentRequest.post('/api/team/dissolve/code');
    },
    handleError(error) {
      if (error.response?.data?.errors?.new_admin_uuid) {
        this.adminSelectError = error.response.data.errors.new_admin_uuid[0];
      } else if (error.response?.data?.errors?.verification_code) {
        this.codeError = error.response.data.errors.verification_code[0];
      } else {
        this.toast.error(error.response?.data?.message || 'An error occurred');
      }
    },

    closeDissolveModal() {
      this.showDissolveModal = false;
      this.verificationCode = '';
      this.dissolveConfirmed = false;
      this.codeError = '';
      this.checkboxError = '';
    },

    async confirmDissolveTeam() {
      if (!this.dissolveConfirmed) {
        this.checkboxError = 'You must confirm this action';
        return;
      }

      if (!this.verificationCode) {
        this.codeError = 'Verification code is required';
        return;
      }

      try {
        this.isDissolving = true;
        const response = await silentRequest.post('/api/team/dissolve', {
          verification_code: this.verificationCode
        });

        if (response.data.success) {
          await this.fetchTeamData();
          this.toast.success(response.data.message);
          this.closeDissolveModal();
          this.closeAdminLeaveModal();
          // Redirect to appropriate page after dissolution
          this.$router.push('/dashboard');
        }
      } catch (error) {
        this.handleError(error);
      } finally {
        this.isDissolving = false;
      }
    },


    clearAdminError() {
      this.adminSelectError = '';
    },

    numericOnly(e) {
   let char = String.fromCharCode(e.keyCode);
   if (/^[0-9]$/.test(char) && this.verificationCode.length < 6) {
     return true;
   }
   e.preventDefault();
 },

 clearCodeError() {
   this.codeError = '';
   this.verificationCode = this.verificationCode.replace(/[^0-9]/g, '').substring(0, 6);
 },

    clearCheckboxError() {
      this.checkboxError = '';
    },


  },
  mounted() {
    this.fetchTeamData();
    console.log("Promotable Members:", this.promotableMembers);
  },

};
</script>

<style scoped>
.team-management {
  padding: 20px;
}

.team-overview {
  margin-bottom: 20px;
}

.filters {
  margin-bottom: 20px;
}

.team-members {
  width: 100%;
  border-collapse: collapse;
}

.team-members th,
.team-members td {
  border: 1px solid #ccc;
  padding: 10px;
}

.text-success {
  color: green;
  font-weight: bold;
}

.removed-member-row {
  background-color: rgba(0, 0, 0, 0.05);
  border-radius: 4px;
  padding: 8px 0;
  margin-top: 16px;
}

.active-member {
  border-left: 3px solid transparent;
}

.active-member:hover {
  background-color: rgba(0, 0, 0, 0.02);
  border-left-color: #1976D2;
}


.text-success {
  color: #2E7D32;
  font-weight: bold;
}

.text-error {
  color: #D32F2F;
  font-weight: bold;
}

.amount-cell {
  color: #2E7D32;
  font-weight: bold;
}
</style>
