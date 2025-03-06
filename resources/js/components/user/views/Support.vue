<template>
    <v-container fluid class="chat-container">
        <!-- Page Title -->
        <v-row class="mb-3">
            <v-col cols="12">
                <h2 class="text-h4 font-weight-bold">Help & Support</h2>
            </v-col>
        </v-row>

        <!-- Main Content -->
        <v-row>
            <!-- Ticket List -->
            <v-col cols="12" md="4">
                <v-card elevation="3" class="rounded-lg ticket-list">
                    <v-toolbar flat color="primary" class="rounded-t-lg">
                        <v-toolbar-title class="text-white">Tickets</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn color="white" class="m-2" variant="flat" @click="showCreateTicketModal = true">
                            <v-icon start>mdi-plus</v-icon>
                            Create
                        </v-btn>
                    </v-toolbar>

                    <v-card-text>
                        <v-select v-model="selectedStatus" :items="['All', 'Pending', 'Resolved', 'Unresolved']"
                            label="Filter by Status" variant="outlined" density="compact" class="mb-4"></v-select>

                        <v-text-field v-model="searchQuery" label="Search tickets" prepend-inner-icon="mdi-magnify"
                            variant="outlined" density="compact" class="mb-4"></v-text-field>


                        <!-- <v-card>
                            <v-btn @click="showDatePicker = !showDatePicker" color="primary" class="mr-2">
                                <v-icon>mdi-calendar</v-icon>
                                <span class="ml-2">Search by Date</span>
                            </v-btn>
                            <v-date-picker v-if="showDatePicker" v-model="selectedDateRange" range
                                @change="onDateSelected" class="mt-3"></v-date-picker>
                        </v-card> -->



                        <v-list class="ticket-list-items" style="max-height: calc(90vh - 200px); overflow-y: auto;">
                            <v-list-item v-for="ticket in filteredTickets" :key="ticket.id"
                                @click="selectTicket(ticket.id)"
                                :class="{ 'selected-ticket': selectedTicket?.id === ticket.id }"
                                class="mb-2 rounded-lg">
                                <template v-slot:prepend>
                                    <v-avatar color="primary" size="40">
                                        {{ ticket.subject[0]?.toUpperCase() || 'T' }}
                                    </v-avatar>
                                </template>

                                <v-list-item-title class="font-weight-medium">
                                    {{ truncate(ticket.subject, 20) }}
                                </v-list-item-title>

                                <v-list-item-subtitle class="text-caption">
                                    {{ truncate(ticket.last_message || ticket.description, 30) }}
                                </v-list-item-subtitle>

                                <template v-slot:append>
                                    <div class="d-flex flex-column align-end">
                                        <v-chip :color="getStatusColor(ticket.status)" size="small"
                                            class="mb-1 text-capitalize">
                                            {{ ticket.status }}
                                        </v-chip>
                                        <span class="text-caption">
                                            {{ formatRelativeTime(ticket.updated_at) }}
                                        </span>
                                    </div>
                                </template>
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>

            <!-- Chat Section -->
            <v-col cols="12" md="8">
                <v-card elevation="3" class="chat-section rounded-lg">
                    <v-toolbar flat color="primary" class="rounded-t-lg">
                        <v-toolbar-title class="text-white">
                            {{ selectedTicket?.subject || 'Select a Ticket' }}
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-menu v-if="selectedTicket && selectedTicket.status === 'pending'">
                            <template v-slot:activator="{ props }">
                                <v-btn icon v-bind="props">
                                    <v-icon>mdi-dots-vertical</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item @click="closeTicket">
                                    <template v-slot:prepend>
                                        <v-icon>mdi-check-circle</v-icon>
                                    </template>
                                    <v-list-item-title>Close Ticket</v-list-item-title>
                                </v-list-item>
                                <!-- <v-list-item @click="archiveTicket">
            <template v-slot:prepend>
                <v-icon>mdi-archive</v-icon>
            </template>
            <v-list-item-title>Archive Ticket</v-list-item-title>
        </v-list-item> -->
                            </v-list>
                        </v-menu>
                    </v-toolbar>
                    <div v-if="selectedTicket" class="ticket-details mb-4">
                        <v-card flat class="pa-4 bg-grey-lighten-4">
                            <div class="d-flex align-center">
                                <v-avatar color="primary" size="48" class="mr-4">
                                    {{ selectedTicket.subject[0]?.toUpperCase() || 'T' }}
                                </v-avatar>
                                <div>
                                    <h3 class="text-h6 mb-1">{{ selectedTicket.subject }}</h3>
                                    <p class="text-body-2 text-grey-darken-1 mb-2">{{ selectedTicket.description }}
                                    </p>
                                    <div class="d-flex align-center">
                                        <v-chip :color="getStatusColor(selectedTicket.status)" size="small"
                                            class="mr-2 text-capitalize">
                                            {{ selectedTicket.status }}
                                        </v-chip>
                                        <span class="text-caption text-grey">Created {{
                                            formatRelativeTime(selectedTicket.created_at) }}</span>
                                    </div>
                                </div>
                            </div>
                        </v-card>
                    </div>

                    <!-- Loading State -->
                    <v-progress-linear v-if="isLoadingTicket" indeterminate color="primary"></v-progress-linear>

                    <div class="chat-messages">
                        <template v-for="(group, date) in groupedMessages" :key="date">
                            <div class="date-divider">
                                <div class="date-line"></div>
                                <span class="date-text">{{ date }}</span>
                                <div class="date-line"></div>
                            </div>
                            <div v-for="message in group" :key="message.id" class="message-wrapper"
                                :class="{ 'message-sent': message.sender_id === user.id }">
                                <v-card :color="message.sender_id === user.id ? 'primary' : 'grey-lighten-4'"
                                    :class="{ 'message-bubble': true }">
                                    <v-card-text :class="{ 'text-white': message.sender_id === user.id }">
                                        <div v-if="message.file_url" class="file-message">
                                            <v-card flat
                                                :color="message.sender_id === user.id ? 'primary-lighten-1' : 'grey-lighten-3'"
                                                class="pa-2">
                                                <a :href="message.file_url" target="_blank"
                                                    class="file-link d-flex align-center">
                                                    <v-icon :color="message.sender_id === user.id ? 'white' : 'primary'"
                                                        class="mr-2">
                                                        mdi-file-document-outline
                                                    </v-icon>
                                                    <div class="text-wrap">
                                                        <div class="text-subtitle-2">{{ message.file_name }}</div>
                                                        <div class="text-caption">{{ formatFileSize(message.file_size)
                                                        }}</div>
                                                    </div>
                                                </a>
                                            </v-card>
                                        </div>
                                        <div class="message-text">{{ message.message }}</div>
                                        <!-- <div class="message-time text-caption" >{{ formatRelativeTime(message.created_at) }}</div> -->
                                        <span class="message-time">{{ formatRelativeTime(message.created_at) }}</span>
                                    </v-card-text>
                                </v-card>
                            </div>
                        </template>
                    </div>

                    <!-- File Preview -->
                    <div v-if="file" class="file-preview px-4 py-2">
                        <div class="file-preview-card">
                            <v-icon color="primary" size="24">mdi-file</v-icon>
                            <div class="file-info mx-2">
                                <span class="file-name font-weight-medium">{{ file.name }}</span>
                                <small class="text-caption">{{ (file.size / 1024).toFixed(2) }} KB</small>
                            </div>
                            <v-btn icon size="small" color="error" @click="removeFile">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <input type="file" ref="fileInput" class="d-none" @change="handleFileChange" />


                    <v-card-actions v-if="selectedTicket && selectedTicket.status === 'pending'"
                        class="chat-input pa-4">
                        <v-btn icon @click="$refs.fileInput.click()">
                            <v-icon>mdi-paperclip</v-icon>
                        </v-btn>
                        <v-textarea v-model="message" rows="1" auto-grow hide-details variant="outlined"
                            density="compact" class="mx-4" placeholder="Type a message..."></v-textarea>
                        <v-btn :loading="isSending" :disabled="!canSendMessage" color="primary" icon
                            @click="sendMessage">
                            <v-icon>mdi-send</v-icon>
                        </v-btn>
                    </v-card-actions>
                    <div class="closure-section pa-6">
                        <!-- Status Message -->
                        <v-alert v-if="selectedTicket && selectedTicket.status !== 'pending'"
                            :color="selectedTicket.status === 'resolved' ? 'success' : 'error'"
                            :icon="selectedTicket.status === 'resolved' ? 'mdi-check-circle' : 'mdi-alert-circle'"
                            variant="tonal" class="mb-6">
                            This ticket is {{ selectedTicket.status }}. No new messages can be sent.
                        </v-alert>

                        <!-- Closure Details -->
                        <v-card v-if="selectedTicket?.closure" variant="outlined" class="closure-details pa-4">
                            <div class="d-flex align-center justify-space-between mb-4">
                                <div class="text-h6">Ticket Closure Details</div>
                                <v-chip :color="selectedTicket.status === 'resolved' ? 'success' : 'error'" size="small"
                                    variant="elevated">
                                    {{ selectedTicket.status.toUpperCase() }}
                                </v-chip>
                            </div>

                            <div class="text-subtitle-2 text-grey mb-4">
                                <v-icon size="small" class="mr-1">mdi-clock-outline</v-icon>
                                Closed {{ formatRelativeTime(selectedTicket.closure.created_at) }}
                            </div>

                            <template v-if="selectedTicket.closure.reason">
                                <div class="text-subtitle-1 font-weight-medium mb-2">Reason</div>
                                <v-card-text class="pa-3 bg-grey-lighten-4 rounded mb-4">
                                    {{ selectedTicket.closure.reason }}
                                </v-card-text>
                            </template>

                            <template v-if="selectedTicket.closure.feedback">
                                <div class="text-subtitle-1 font-weight-medium mb-2">Feedback</div>
                                <v-card-text class="pa-3 bg-grey-lighten-4 rounded mb-4">
                                    {{ selectedTicket.closure.feedback }}
                                </v-card-text>
                            </template>

                            <template v-if="selectedTicket.closure.rating">
                                <div class="text-subtitle-1 font-weight-medium mb-2">Rating</div>
                                <v-rating :model-value="selectedTicket.closure.rating" color="amber" readonly
                                    half-increments size="small"></v-rating>
                            </template>
                        </v-card>
                    </div>
                </v-card>
            </v-col>
        </v-row>

        <!-- Create Ticket Modal -->
        <v-dialog v-model="showCreateTicketModal" max-width="500px">
            <v-card>
                <v-card-title>Create Ticket</v-card-title>
                <v-card-text>
                    <v-form ref="form" v-model="isFormValid" @submit.prevent="createTicket">
                        <v-text-field v-model="newTicket.subject" :rules="[v => !!v || 'Subject is required']"
                            label="Subject" required clearable></v-text-field>
                        <v-textarea v-model="newTicket.description" :rules="[v => !!v || 'Description is required']"
                            label="Description" required clearable></v-textarea>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="showCreateTicketModal = false">Cancel</v-btn>
                    <v-btn color="primary" variant="elevated" :loading="isCreating" :disabled="!isFormValid"
                        @click="createTicket">
                        Create
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>


        <!-- Close Ticket Modal -->
        <v-dialog v-model="showCloseTicketModal" max-width="500px">
            <v-card>
                <v-card-title>Close Ticket</v-card-title>
                <v-card-text>
                    <v-select v-model="closeTicketData.status" :items="statusOptions" label="Status"
                        required></v-select>

                    <v-textarea v-if="closeTicketData.status === 'unresolved'" v-model="closeTicketData.reason"
                        label="Reason" required></v-textarea>

                    <div class="rating-section my-4">
                        <div class="text-subtitle-1 mb-2">Rate your experience</div>
                        <div class="d-flex justify-center">
                            <v-rating v-model="closeTicketData.rating" color="amber" active-color="amber" hover
                                size="large"></v-rating>
                        </div>
                    </div>

                    <v-textarea v-if="closeTicketData.status === 'resolved'" v-model="closeTicketData.feedback"
                        label="Additional Feedback (Optional)"></v-textarea>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="showCloseTicketModal = false">Cancel</v-btn>
                    <v-btn color="primary" :loading="isClosing" :disabled="!isCloseFormValid"
                        @click="submitCloseTicket">Close
                        Ticket</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import { VMenu, VBtn, VIcon, VDatePicker } from 'vuetify/components';
import { useToast } from "vue-toastification";
import axios from "axios";
// import { ref, onMounted, watch, computed } from 'vue';
import { ref, onMounted, watch, computed, onBeforeUnmount } from 'vue';

export default {
    data() {
        return {
            showDateFilter: false,
            selectedStartDate: null,
            selectedEndDate: null,
            showDatePicker: false, // To toggle visibility of the date picker
            selectedDateRange: null, // Store the selected date range
            displayedTickets: [], // Stores filtered tickets
            statusOptions: [
                { title: 'RESOLVED', value: 'resolved' },
                { title: 'UNRESOLVED', value: 'unresolved' }
            ],
            showCloseTicketModal: false,
            isClosing: false,
            closeTicketData: {
                status: 'resolved',
                reason: '',
                feedback: '',
                rating: 0
            },
            defaultAvatar: "https://via.placeholder.com/40x40.png?text=User",
            tickets: [],
            messages: [],
            user: {
                id: 1,
            },
            file: null,
            selectedTicket: null,
            message: "",
            newTicket: { subject: "", description: "" },
            selectedStatus: "All",
            searchQuery: "",
            showCreateTicketModal: false,
            isFormValid: false,
            isCreating: false,
            isSending: false,
            isLoadingTicket: false,


            channelSubscriptions: [],
        };
    },

    computed: {
        isCloseFormValid() {
            return this.closeTicketData.rating > 0 &&
                (this.closeTicketData.status !== 'unresolved' || !!this.closeTicketData.reason);
        },

        filteredTickets() {
            const tickets = this.tickets.filter(ticket => {
                const matchesStatus =
                    this.selectedStatus === "All" ||
                    ticket.status.toLowerCase() === this.selectedStatus.toLowerCase();
                const matchesSearch =
                    ticket.subject.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    ticket.description.toLowerCase().includes(this.searchQuery.toLowerCase());
                const matchesDate =
                    (!this.selectedDateRange || this.selectedDateRange.length < 2) ||
                    (new Date(ticket.created_at) >= new Date(this.selectedDateRange[0]) &&
                        new Date(ticket.created_at) <= new Date(this.selectedDateRange[1]));

                return matchesStatus && matchesSearch && matchesDate;
            });
            console.log('Filtered Tickets:', tickets);
            return tickets;
        },


        formatDate(date) {
            return new Date(date).toLocaleDateString();
        },
        groupedMessages() {
            return this.messages.reduce((groups, message) => {
                const date = new Date(message.created_at).toLocaleDateString('en-US', {
                    weekday: 'long',
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });
                if (!groups[date]) groups[date] = [];
                groups[date].push(message);
                return groups;
            }, {});
        },

        canSendMessage() {
            return (this.message?.trim() || this.file) && !this.isSending;
        },
    },
    watch: {
        showDateFilter(val) {
            console.log("Menu open state:", val);
        },
        selectedDateRange(newVal) {
            console.log('Selected Date Range:', newVal);
        },

        // Add a watcher for the selected ticket to set up real-time listeners
        selectedTicket(newTicket, oldTicket) {
            if (newTicket?.id !== oldTicket?.id && newTicket?.uuid) {
                // Clear old subscriptions and set up new ones
                this.setupTicketChannels();
            }
        }
    },

    methods: {

        /**
         * Handle ticket updates received via real-time
         */
        handleTicketUpdate(data) {
            console.log('Ticket update received:', data);

            // Update the selected ticket if it matches
            if (this.selectedTicket && this.selectedTicket.id === data.id) {
                this.selectedTicket.status = data.status;

                useToast().info(`Ticket status updated to ${data.status}`);
            }

            // Update the ticket in the sidebar list
            const ticketIndex = this.tickets.findIndex(t => t.id === data.id);
            if (ticketIndex !== -1) {
                this.tickets[ticketIndex].status = data.status;
                this.tickets[ticketIndex].updated_at = data.updated_at;
            }
        },

        /**
         * Handle ticket closure events received via real-time
         */
        handleTicketClosure(data) {
            console.log('Ticket closure received:', data);

            // Update the selected ticket if it matches
            if (this.selectedTicket && this.selectedTicket.id === data.id) {
                this.selectedTicket.status = data.status;
                this.selectedTicket.closure = data.closure;

                useToast().info(`Ticket has been marked as ${data.status}`);
            }

            // Update the ticket in the sidebar list
            const ticketIndex = this.tickets.findIndex(t => t.id === data.id);
            if (ticketIndex !== -1) {
                this.tickets[ticketIndex].status = data.status;
            }
        },

        /**
         * Handle a new message received via real-time
         */
        handleNewMessage(data) {
            console.log('New message received:', data);

            // Skip our own messages (they're already added via the UI)
            if (data.sender_id === this.user.id && data.sender_type !== 'admin') {
                console.log('Ignoring own message');
                return;
            }

            // Skip if we already have this message
            if (this.messages.some(m => m.id === data.id)) {
                console.log('Message already in the list');
                return;
            }

            // Format the message to match our UI structure
            const newMessage = {
                id: data.id,
                ticket_id: data.ticket_id,
                sender_id: data.sender_id,
                sender_name: data.sender?.first_name
                    ? `${data.sender.first_name} ${data.sender.last_name}`.trim()
                    : (data.sender_type === 'admin' ? 'Support Staff' : 'Unknown'),
                message: data.message,
                file_url: data.file_url,
                file_name: data.file_name,
                file_size: data.file_size,
                created_at: data.created_at,
            };

            // Add the message to our list
            this.messages.push(newMessage);

            // Update the related ticket in the sidebar
            const ticketIndex = this.tickets.findIndex(ticket => ticket.id === data.ticket_id);
            if (ticketIndex !== -1) {
                this.tickets[ticketIndex].last_message = data.message || 'File attachment';
                this.tickets[ticketIndex].updated_at = data.created_at;
            }

            // Notify the user
            useToast().info('New message received');

            // Scroll to the new message
            this.$nextTick(() => {
                this.initChatMessagesScroll();
            });
        },

        /**
         * Clean up any existing listeners
         */
        clearExistingListeners() {
            if (this.channelSubscriptions && this.channelSubscriptions.length > 0) {
                console.log('Cleaning up existing listeners...');

                this.channelSubscriptions.forEach(sub => {
                    try {
                        7
                        console.log(`Unsubscribing from ${sub.name}`);

                        if (typeof sub.channel.stopListening === 'function') {
                            if (sub.events && Array.isArray(sub.events)) {
                                sub.events.forEach(event => {
                                    sub.channel.stopListening(event);
                                });
                            } else if (sub.event) {
                                sub.channel.stopListening(sub.event);
                            }
                        }

                        if (typeof sub.channel.unsubscribe === 'function') {
                            sub.channel.unsubscribe();
                        }
                    } catch (error) {
                        console.error(`Error cleaning up listener ${sub.name}:`, error);
                    }
                });

                this.channelSubscriptions = [];
            }
        },

        listenForNewTickets() {
            // Unsubscribe from any existing tickets channel
            const existingChannel = this.channelSubscriptions.find(sub => sub.name === 'tickets');
            if (existingChannel) {
                existingChannel.channel.unsubscribe();
                this.channelSubscriptions = this.channelSubscriptions.filter(sub => sub.name !== 'tickets');
            }

            console.log('Subscribing to general tickets channel');

            try {
                // Listen on the global tickets channel
                const ticketsChannel = window.Echo.channel('tickets');

                ticketsChannel.listen('TicketCreated', (data) => {
                    console.log('New ticket created:', data);

                    // Only add if this ticket is for the current user
                    if (data.created_by === this.user.id) {
                        // Check if we already have this ticket
                        if (!this.tickets.some(t => t.id === data.id)) {
                            this.tickets.unshift({
                                ...data,
                                avatar: this.defaultAvatar,
                                last_message: data.description || '',
                            });

                            useToast().info(`New ticket created: ${data.subject}`);
                        }
                    }
                });

                // Store the subscription for cleanup
                this.channelSubscriptions.push({
                    name: 'tickets',
                    channel: ticketsChannel,
                    event: 'TicketCreated'
                });

                console.log('Tickets listener set up successfully');
            } catch (error) {
                console.error('Error setting up tickets listener:', error);
            }
        },

        setupTicketChannels() {
            // First clear any existing ticket-specific channels
            const ticketChannels = this.channelSubscriptions.filter(sub =>
                sub.name.startsWith('ticket.') || sub.name === `ticket.${this.selectedTicket?.uuid}`);

            ticketChannels.forEach(sub => {
                sub.channel.unsubscribe();
            });

            this.channelSubscriptions = this.channelSubscriptions.filter(sub =>
                !sub.name.startsWith('ticket.') && sub.name !== `ticket.${this.selectedTicket?.uuid}`);

            if (!this.selectedTicket?.uuid) return;

            console.log(`Subscribing to ticket channel: ticket.${this.selectedTicket.uuid}`);

            try {
                // Subscribe to the specific ticket channel
                const ticketChannel = window.Echo.channel(`ticket.${this.selectedTicket.uuid}`);

                // Listen for new messages
                ticketChannel.listen('.MessageCreated', (data) => {
                    console.log('New message received:', data);

                    // Only add if it's not from the current user and not already in messages
                    if (data.sender_id !== this.user.id && !this.messages.find(m => m.id === data.id)) {
                        this.messages.push(data);

                        // Update the UI
                        this.$nextTick(() => {
                            this.initChatMessagesScroll();
                        });

                        // Show a notification
                        useToast().info('New message received');
                    }
                })
                    .error((error) => {
                        console.error('Channel subscription error:', error);
                    });;

                // Listen for ticket status updates
                ticketChannel.listen('TicketUpdated', (data) => {
                    console.log('Ticket updated:', data);

                    // Update the selected ticket
                    if (this.selectedTicket.id === data.id) {
                        this.selectedTicket.status = data.status;

                        useToast().info(`Ticket status updated to: ${data.status}`);

                        // If the ticket was closed, fetch the closure details
                        if (data.status === 'resolved' || data.status === 'unresolved') {
                            this.selectTicket(this.selectedTicket.id);
                        }
                    }

                    // Update in tickets list
                    const ticketIndex = this.tickets.findIndex(t => t.id === data.id);
                    if (ticketIndex !== -1) {
                        this.tickets[ticketIndex].status = data.status;
                    }
                })
                    .error((error) => {
                        console.error('Channel subscription error:', error);
                    });;

                // Listen for ticket closure events
                ticketChannel.listen('TicketClosed', (data) => {
                    console.log('Ticket closed:', data);

                    if (this.selectedTicket && this.selectedTicket.id === data.id) {
                        this.selectedTicket.status = data.status;
                        this.selectedTicket.closure = data.closure;

                        useToast().info(`Ticket has been marked as ${data.status}`);
                    }

                    // Update in tickets list
                    const ticketIndex = this.tickets.findIndex(t => t.id === data.id);
                    if (ticketIndex !== -1) {
                        this.tickets[ticketIndex].status = data.status;
                    }
                })
                    .error((error) => {
                        console.error('Channel subscription error:', error);
                    });;

                // Add to tracked subscriptions
                this.channelSubscriptions.push({
                    name: `ticket.${this.selectedTicket.uuid}`,
                    channel: ticketChannel
                });

                console.log(`Successfully subscribed to ticket.${this.selectedTicket.uuid} channel`);
            } catch (error) {
                console.error(`Failed to subscribe to ticket.${this.selectedTicket.uuid} channel:`, error);
            }
        },

        listenForNotifications() {
            // Unsubscribe from any existing notifications channel
            const existingChannel = this.channelSubscriptions.find(sub => sub.name === 'notifications');
            if (existingChannel) {
                existingChannel.channel.unsubscribe();
                this.channelSubscriptions = this.channelSubscriptions.filter(sub => sub.name !== 'notifications');
            }

            console.log('Subscribing to notifications channel');

            try {
                const notificationsChannel = window.Echo.channel('notifications');

                notificationsChannel.listen('NotificationEvent', (data) => {
                    console.log('Notification received:', data);

                    useToast().info(data.message, {
                        timeout: 8000,
                        position: "top-right",
                    });
                });

                // Store the subscription for cleanup
                this.channelSubscriptions.push({
                    name: 'notifications',
                    channel: notificationsChannel,
                    event: 'NotificationEvent'
                });

                console.log('Notifications listener set up successfully');
            } catch (error) {
                console.error('Error setting up notifications listener:', error);
            }
        },

        // Handle date selection
        onDateSelected() {
            this.showDatePicker = false; // Close the date picker
            if (this.selectedDateRange && this.selectedDateRange.length === 2) {
                const [start, end] = this.selectedDateRange;

                // Filter tickets by the selected date range
                this.filterTicketsByDate(start, end);
            }
        },

        // Filter tickets by date range
        filterTicketsByDate(startDate, endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);

            this.displayedTickets = this.tickets.filter((ticket) => {
                const ticketDate = new Date(ticket.created_at);
                return ticketDate >= start && ticketDate <= end;
            });

            if (this.displayedTickets.length === 0) {
                useToast().info("No tickets found for the selected date range");
            }
        },

        formatDate(date) {
            return new Date(date).toLocaleDateString();
        },
        initChatMessagesScroll() {
            const chatMessagesEl = this.$el.querySelector('.chat-messages');
            if (chatMessagesEl) {
                chatMessagesEl.scrollTop = chatMessagesEl.scrollHeight;
            } else {
                console.error('chatMessages element not found in DOM');
            }
        },
        truncate(text, limit) {
            if (!text) return "";
            return text.length > limit ? text.substring(0, limit) + "..." : text;
        },
        formatFileSize(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return `${parseFloat((bytes / Math.pow(k, i)).toFixed(2))} ${sizes[i]}`;
        },
        formatRelativeTime(date) {
            const now = new Date();
            const timeDiff = Math.floor((now - new Date(date)) / 1000);

            if (timeDiff < 60) return `${timeDiff} seconds ago`;
            if (timeDiff < 3600) return `${Math.floor(timeDiff / 60)} minutes ago`;
            if (timeDiff < 86400) return `${Math.floor(timeDiff / 3600)} hours ago`;

            return `${Math.floor(timeDiff / 86400)} days ago`;
        },


        async fetchTickets() {
            try {
                const response = await axios.get("/api/tickets");
                this.tickets = response.data.map((ticket) => ({
                    ...ticket,
                    avatar: ticket.avatar || this.defaultAvatar,
                    last_message: ticket.messages?.slice(-1)[0]?.message || "",
                }));

                // By default, display all tickets
                this.displayedTickets = this.tickets;
            } catch (error) {
                useToast().error("Failed to fetch tickets");
            }
        },

        async selectTicket(ticketId) {
            // this.isLoadingTicket = true;
            // try {
            //     const ticketResponse = await axios.get(`/api/tickets/${ticketId}`);
            //     this.selectedTicket = ticketResponse.data;

            //     const messagesResponse = await axios.get(`/api/tickets/${ticketId}/messages`);
            //     this.messages = messagesResponse.data.map(message => ({
            //         ...message,
            //         sender_name: message.sender_name || 'Unknown',
            //         message: message.message || '',
            //         created_at: message.created_at || '',
            //     }));
            //     this.$nextTick(() => {
            //         this.initChatMessagesScroll();
            //     });
            // } catch (error) {
            //     useToast().error("Failed to load ticket");
            // } finally {
            //     this.isLoadingTicket = false;
            // }

            // this.isLoadingTicket = true;

            // try {
            //     const ticketResponse = await axios.get(`/api/tickets/${ticketId}`);
            //     this.selectedTicket = ticketResponse.data;

            //     const messagesResponse = await axios.get(`/api/tickets/${ticketId}/messages`);
            //     this.messages = messagesResponse.data.map(message => ({
            //         ...message,
            //         sender_name: message.sender_name || 'Unknown',
            //         message: message.message || '',
            //         created_at: message.created_at || '',
            //     }));

            //     // Set up real-time listeners for this ticket
            //     if (this.selectedTicket?.uuid) {
            //         // Clear existing listeners and set up new ones for this ticket
            //         this.clearExistingListeners();
            //         this.setupRealTimeListeners();
            //     }

            //     this.$nextTick(() => {
            //         this.initChatMessagesScroll();
            //     });
            // } catch (error) {
            //     useToast().error("Failed to load ticket");
            // } finally {
            //     this.isLoadingTicket = false;
            // }

            this.isLoadingTicket = true;

            try {
                const ticketResponse = await axios.get(`/api/tickets/${ticketId}`);
                this.selectedTicket = ticketResponse.data;

                const messagesResponse = await axios.get(`/api/tickets/${ticketId}/messages`);
                this.messages = messagesResponse.data.map(message => ({
                    ...message,
                    sender_name: message.sender_name || 'Unknown',
                    message: message.message || '',
                    created_at: message.created_at || '',
                }));

                // Set up real-time listeners with multiple approaches
                if (this.selectedTicket?.uuid) {
                    // Clear existing listeners
                    this.clearChannelSubscriptions();

                    // Try both listener setup methods to ensure it works
                    this.setupTicketListeners(this.selectedTicket.uuid);

                    // Also listen for raw events
                    this.setupRawEventListener();
                }

                this.$nextTick(() => {
                    this.initChatMessagesScroll();
                });
            } catch (error) {
                useToast().error("Failed to load ticket");
            } finally {
                this.isLoadingTicket = false;
            }
        },


        /**
         * Listen for raw Pusher events to debug
         */
        setupRawEventListener() {
            const pusher = window.Echo?.connector?.pusher;
            if (!pusher) return;

            // Listen for all events on all channels
            pusher.bind_global((eventName, data) => {
                // Only log message related events to avoid flooding console
                if (eventName.includes('Message') || eventName.includes('message')) {
                    console.log(`🔔 Raw Pusher event: ${eventName}`, data);

                    // Try to handle the event if it might be a message
                    if (data && data.message && data.ticket_id) {
                        console.log('This looks like a message, trying to handle it...');
                        this.handleNewMessage(data);
                    }
                }
            });
        },

        setupTicketListeners(uuid) {
            console.log(`Setting up listeners for ticket ${uuid}`);

            if (typeof window.Echo === 'undefined') {
                console.error('Echo is not available for ticket listeners');
                return;
            }

            try {
                // Clean up any existing subscriptions for this ticket
                this.cleanupChannelSubscription(`ticket.${uuid}`);

                const channelName = `ticket.${uuid}`;

                // Set up the channel
                const ticketChannel = window.Echo.channel(channelName);

                console.log(channelName);

                // Add error handler for the channel
                ticketChannel.error((error) => {
                    console.error(`Channel error on ${channelName}:`, error);
                    // Maybe reconnect or show user a notification
                });

                ticketChannel.listen('MessageCreated', (data) => {
                    console.log('MessageCreated event received:', data);
                    void this.handleNewMessage(data); // void ensures no value is returned
                });

                ticketChannel.listenToAll((eventName, data) => {
                    console.log(`All ticket events - ${eventName}:`, data);

                    if ((eventName.includes('message') || eventName.includes('Message')) &&
                        data && (data.message || data.id)) {
                        void this.handleNewMessage(data); // void ensures no value is returned
                    }
                });


                // Store subscription for cleanup
                this.channelSubscriptions.push({
                    name: channelName,
                    channel: ticketChannel,
                    events: ['MessageCreated']
                });

                console.log(`Successfully subscribed to ${channelName}`);
            } catch (error) {
                console.error(`Error setting up ticket listeners for ${uuid}:`, error);
            }
        },

        // Add this helper method if you don't already have it
        cleanupChannelSubscription(channelName) {
            const index = this.channelSubscriptions.findIndex(sub => sub.name === channelName);
            if (index !== -1) {
                try {
                    console.log(`Cleaning up existing subscription to ${channelName}`);
                    window.Echo.leave(channelName);
                } catch (e) {
                    console.warn(`Error leaving channel ${channelName}:`, e);
                }
                this.channelSubscriptions.splice(index, 1);
            }
        },

        /**
         * Clean up channel subscriptions
         */
        clearChannelSubscriptions() {
            if (!this.channelSubscriptions) this.channelSubscriptions = [];

            this.channelSubscriptions.forEach(sub => {
                if (sub.channel) {
                    // Unsubscribe from each event
                    if (Array.isArray(sub.events)) {
                        sub.events.forEach(event => {
                            try {
                                sub.channel.stopListening(event);
                            } catch (error) {
                                // Ignore errors when cleaning up
                            }
                        });
                    }

                    // Leave the channel if possible
                    if (typeof sub.channel.leave === 'function') {
                        try {
                            sub.channel.leave();
                        } catch (error) {
                            // Ignore errors when cleaning up
                        }
                    }
                }
            });

            this.channelSubscriptions = [];
        },

        // Add diagnostic logging method
        logConnectionStatus() {
            if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
                const connectionState = window.Echo.connector.pusher.connection.state;
                console.log(`Pusher connection state: ${connectionState}`);

                if (connectionState === 'connected') {
                    console.log(`Socket ID: ${window.Echo.connector.pusher.connection.socket_id}`);

                    // Log subscribed channels
                    const channels = window.Echo.connector.pusher.channels.channels;
                    console.log('Subscribed channels:');
                    for (const channelName in channels) {
                        console.log(`- ${channelName}: ${channels[channelName].subscribed ? 'Subscribed' : 'Pending'}`);
                    }
                }
            } else {
                console.error('Echo or Pusher not properly initialized');
            }
        },

        async createTicket() {
            if (!this.isFormValid) return;

            this.isCreating = true;
            try {
                const response = await axios.post("/api/tickets", this.newTicket);
                this.tickets.unshift(response.data);
                this.showCreateTicketModal = false;
                this.newTicket = { subject: "", description: "" };
                useToast().success("Ticket created successfully");
            } catch (error) {
                if (error.response?.status === 422) {
                    Object.entries(error.response.data.errors).forEach(([field, [message]]) => {
                        useToast().error(`${field}: ${message}`);
                    });
                } else {
                    useToast().error("Failed to create ticket");
                }
            } finally {
                this.isCreating = false;
            }
        },

        getStatusColor(status) {
            switch (status.toLowerCase()) {
                case 'resolved':
                    return 'success';
                case 'pending':
                    return 'warning';
                case 'unresolved':
                    return 'error';
                default:
                    return 'grey';
            }
        },

        async sendMessage() {
            if (!this.canSendMessage) return;

            this.isSending = true;
            try {
                const formData = new FormData();
                formData.append("ticket_id", this.selectedTicket.id);
                if (this.message) formData.append("message", this.message);
                if (this.file) formData.append("file", this.file);

                const response = await axios.post("/api/tickets/messages", formData, {
                    headers: { "Content-Type": "multipart/form-data" }
                });

                this.messages.push(response.data);
                this.message = "";
                this.file = null;
                this.$refs.fileInput.value = "";
            } catch (error) {
                useToast().error("Failed to send message");
            } finally {
                this.isSending = false;
            }
        },

        handleFileChange(event) {
            const file = event.target.files[0];
            if (file.size > 1024 * 1024 * 10) {
                useToast().error("File size exceeds the 10MB limit");
                return;
            }
            this.file = file;
        },

        removeFile() {
            this.file = null;
            this.$refs.fileInput.value = "";
        },

        async closeTicket2() {
            if (!this.selectedTicket) return;
            try {
                await axios.post(`/api/tickets/${this.selectedTicket.id}/close`, { status: 'resolved' });
                useToast().success("Ticket closed successfully");
                this.fetchTickets();
            } catch (error) {
                useToast().error("Failed to close ticket");
            }
        },

        async submitCloseTicket() {
            // Frontend validation
            if (!this.isCloseFormValid) {
                useToast().error("Please complete all required fields");
                return;
            }

            if (this.closeTicketData.status === 'unresolved' && !this.closeTicketData.reason?.trim()) {
                useToast().error("Reason is required for unresolved tickets");
                return;
            }

            if (this.closeTicketData.rating < 1) {
                useToast().error("Please provide a rating");
                return;
            }

            this.isClosing = true;
            try {
                const payload = {
                    status: this.closeTicketData.status,
                    rating: this.closeTicketData.rating,
                    reason: this.closeTicketData.status === 'unresolved' ? this.closeTicketData.reason?.trim() : null,
                    feedback: this.closeTicketData.status === 'resolved' ? this.closeTicketData.feedback?.trim() : null
                };

                const response = await axios.post(`/api/tickets/${this.selectedTicket.id}/close`, payload);

                if (response.data.error) {
                    useToast().error(response.data.error);
                    return;
                }

                useToast().success("Ticket closed successfully");
                this.showCloseTicketModal = false;

                await this.fetchTickets();
                const ticketResponse = await axios.get(`/api/tickets/${this.selectedTicket.id}`);
                this.selectedTicket = ticketResponse.data;

            } catch (error) {
                if (error.response?.status === 422) {
                    const validationErrors = error.response.data.errors;
                    Object.values(validationErrors).forEach(messages => {
                        messages.forEach(message => useToast().error(message));
                    });
                } else {
                    useToast().error(error.response?.data?.error || "Failed to close ticket");
                }
            } finally {
                this.isClosing = false;
            }
        },

        closeTicket() {
            this.closeTicketData = {
                status: 'resolved',
                reason: '',
                feedback: '',
                rating: 0
            };
            this.showCloseTicketModal = true;
        },

        archiveTicket() {
            if (!this.selectedTicket) return;
            useToast().info("Archive ticket functionality is under development");
        }
    },

    mounted() {
        if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
            const pusher = window.Echo.connector.pusher;

            console.log('Echo instance found, socket ID:', window.Echo.socketId());
            console.log('Pusher connection state:', pusher.connection.state);

            // Global event listener for all Pusher events
            pusher.bind_global((eventName, data) => {
                // Only log message-related events to prevent console spam
                if (eventName.includes('Message') ||
                    eventName.includes('message') ||
                    eventName.startsWith('pusher:')) {
                    console.log(`Global Pusher event: ${eventName}`, data);

                    // Try to detect and handle message events
                    if (data && (data.message || data.id)) {
                        console.log('This looks like a message event, attempting to process');
                        if (typeof this.handleNewMessage === 'function') {
                            this.handleNewMessage(data);
                        }
                    }
                }
            });

            // Direct subscription test
            console.log(window.Echo, 'window.Echo');

            if (window.Echo) {
                const testChannel = window.Echo.channel('ticket.0b07acd7-f834-4182-b2fa-d2cc431ebccc');

                testChannel.listen('MessageCreated', (data) => {
                    console.log('TEST - Message received!', data);
                    alert('Message received: ' + data.message);
                });

                testChannel.listenToAll((event, data) => {
                    console.log(`TEST - Event received: ${event}`, data);
                });

                console.log('Test listeners set up successfully');
            }

            // Connection status monitoring
            pusher.connection.bind('state_change', (states) => {
                console.log(`Pusher connection changed: ${states.previous} → ${states.current}`);
            });

            pusher.connection.bind('connected', () => {
                console.log('✅ Pusher connected! Socket ID:', pusher.connection.socket_id);
            });

            pusher.connection.bind('error', (error) => {
                console.error('❌ Pusher connection error:', error);
            });
        } else {
            console.error('Echo or Pusher not properly initialized');
        }

        // Fetch tickets and initialize
        this.fetchTickets();

        // Set up a periodic connection check
        this.connectionCheckInterval = setInterval(() => {
            this.logConnectionStatus();
        }, 30000); // Check every 30 seconds

        this.$nextTick(() => {
            this.initChatMessagesScroll();
        });

        // this.fetchTickets();
        // this.$nextTick(() => {
        //     this.initChatMessagesScroll();
        // });
    },
    beforeUnmount() {
        // Clean up subscriptions when component is destroyed
        if (this.channelSubscriptions.length > 0) {
            console.log('Cleaning up channel subscriptions');

            this.channelSubscriptions.forEach(sub => {
                if (sub.channel && typeof sub.channel.unsubscribe === 'function') {
                    sub.channel.unsubscribe();
                    console.log(`Unsubscribed from ${sub.name}`);
                }
            });

            this.channelSubscriptions = [];
        }

        // Clear the connection check interval
        if (this.connectionCheckInterval) {
            clearInterval(this.connectionCheckInterval);
        }
    }
};
</script>

<style>
.message-wrapper {
    background: #fff;
    border-radius: 8px;
    padding: 6px 16px;
    box-shadow: none;
    border: none;
    display: flex;
}

.message-time {
    font-size: 11px;
    color: #666;
    text-align: right;
    margin-top: 2px;
    margin-left: auto;
    display: block;
}

.v-menu__content {
    z-index: 10000 !important;
    border: 2px solid red;
    /* To visualize the menu's boundaries */
}

.closure-details {
    border: 1px solid rgba(0, 0, 0, 0.12);
    transition: all 0.3s ease;
}

.closure-details:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.message-wrapper {
    margin-bottom: 8px;
    position: relative;
}

/* Remove any default borders */
.v-card {
    border: none !important;
    box-shadow: none !important;
}

/* Remove divider */
.v-divider {
    display: none !important;
}

.message-bubble {
    max-width: 70%;
    word-wrap: break-word;
    white-space: pre-wrap;
}

.message-text {
    margin: 4px 0;
    white-space: pre-wrap;
    word-break: break-word;
}

.date-divider {
    margin: 16px 0;
}

.file-message {
    margin-bottom: 4px;
}

.text-wrap {
    word-break: break-word;
    max-width: calc(100% - 32px);
}

.date-divider {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 24px 0;
    gap: 12px;
}

.date-line {
    height: 1px;
    background-color: rgba(98, 0, 234, 0.2);
    flex: 1;
}

.date-text {
    color: var(--primary-color);
    font-weight: 500;
    background: white;
    padding: 4px 16px;
    border-radius: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    white-space: nowrap;
}

.ticket-details {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.file-message {
    margin-bottom: 8px;
}

.v-date-picker {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 16px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}


.file-link {
    text-decoration: none;
    color: inherit;
}

.file-link:hover {
    opacity: 0.8;
}

.chat-container {
    /* background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%); */
    min-height: 100vh;
    padding: 1.5rem;
}

/* Updated primary color references */
.v-btn.primary,
.v-toolbar.primary,
.message-sent,
.v-avatar.primary {
    background-color: var(--primary-color) !important;
}

.v-btn:hover.primary {
    background-color: var(--primary-dark) !important;
}

.ticket-list,
.chat-section {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.chat-section {
    height: calc(100vh - 40px);
    display: flex;
    flex-direction: column;
}

.chat-messages {
    flex-grow: 1;
    overflow-y: auto;
    padding: 20px;
}



.message-sent {
    justify-content: flex-end;
}

.message-bubble {
    max-width: 70%;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.date-divider {
    background: white;
    padding: 8px 16px;
    border-radius: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    margin: 24px 0;
    color: var(--primary-color);
    font-weight: 500;
}

.selected-ticket {
    background: rgba(98, 0, 234, 0.08);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-thumb {
    background: var(--primary-color);
    border-radius: 3px;
}

.chat-input {
    border-top: none;
    background: white;
}

.v-text-field.v-text-field--focused .v-field__outline {
    border-color: var(--primary-color) !important;
}

.file-preview {
    background: #f8f9fa;
    border-top: 1px solid #eef0f2;
}

.file-message {
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.file-link {
    color: inherit;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}

.file-link:hover {
    text-decoration: underline;
}
</style>