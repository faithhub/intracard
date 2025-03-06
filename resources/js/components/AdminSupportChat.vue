<template>
    <v-app>
        <v-container fluid class="fill-height pa-0 w-100">
            <v-card class="rounded-lg elevation-2 fill-height overflow-hidden w-100">

                <v-row class="fill-height w-100">
                    <!-- Sidebar - Ticket List -->

                    <v-col cols="12" sm="5" md="4" lg="3" class="ticket-sidebar">
                        <v-sheet class="fill-height d-flex flex-column">
                            <!-- Add filter in sidebar header instead -->
                            <v-sheet class="pa-3 d-flex align-center">
                                <v-select v-model="statusFilter" :items="statusOptions" variant="outlined"
                                    density="compact" hide-details class="me-4" label="Status"
                                    style="max-width: 150px;"></v-select>

                                <v-btn icon size="small" @click="loadTickets" class="ms-2">
                                    <v-icon>mdi-refresh</v-icon>
                                </v-btn>

                                <v-spacer></v-spacer>
                            </v-sheet>

                            <!-- Search Bar -->
                            <v-sheet class="pa-3 pt-0">
                                <v-text-field v-model="searchQuery" prepend-inner-icon="mdi-magnify"
                                    label="Search tickets" variant="outlined" density="compact" hide-details
                                    @input="debounceSearch"></v-text-field>
                            </v-sheet>

                            <!-- Ticket List -->
                            <div class="ticket-list flex-grow-1 overflow-auto">
                                <v-list lines="two" select-strategy="single" v-model:selected="selectedTicket">
                                    <div v-if="loading" class="d-flex justify-center py-6">
                                        <v-progress-circular indeterminate></v-progress-circular>
                                    </div>

                                    <div v-else-if="tickets.length === 0"
                                        class="text-center pa-4 text-body-2 text-medium-emphasis">
                                        No tickets found
                                    </div>

                                    <v-list-item v-for="ticket in tickets" :key="ticket.uuid" :value="ticket.uuid"
                                        @click="selectTicket(ticket.uuid)" :active="currentTicketUuid === ticket.uuid"
                                        lines="two" density="compact">
                                        <template v-slot:prepend>
                                            <v-avatar :color="getInitialColor(ticket.creator)">
                                                {{ getInitial(ticket.creator) }}
                                            </v-avatar>
                                        </template>

                                        <v-list-item-title class="text-subtitle-1 font-weight-medium mb-1">
                                            {{ ticket.subject }}
                                        </v-list-item-title>

                                        <v-list-item-subtitle class="text-body-2">
                                            {{ getCreatorName(ticket.creator) }}
                                        </v-list-item-subtitle>

                                        <template v-slot:append>
                                            <div class="d-flex flex-column align-end">
                                                <span class="text-caption text-medium-emphasis mb-1">{{
                                                    getTimeAgo(ticket.created_at) }}</span>
                                                <div>
                                                    <v-chip :color="getStatusColor(ticket.status)" size="x-small"
                                                        class="text-capitalize mr-1">
                                                        {{ ticket.status }}
                                                    </v-chip>
                                                    <v-badge v-if="ticket.unread_count > 0"
                                                        :content="String(ticket.unread_count)" color="error" dot
                                                        size="small"></v-badge>
                                                </div>
                                            </div>
                                        </template>
                                    </v-list-item>
                                </v-list>
                            </div>
                        </v-sheet>
                    </v-col>

                    <!-- Chat Area -->
                    <v-col cols="12" sm="7" md="8" lg="9" class="chat-area">
                        <v-sheet v-if="!currentTicketUuid" class="fill-height d-flex align-center justify-center">
                            <div class="text-center">
                                <v-icon size="64" color="grey-lighten-1" class="mb-4">mdi-forum-outline</v-icon>
                                <h3 class="text-h6 text-medium-emphasis">Select a ticket to view messages</h3>
                            </div>
                        </v-sheet>

                        <template v-else>
                            <!-- Ticket Header -->
                            <v-sheet class="px-6 py-3 border-b d-flex align-center">
                                <div class="flex-grow-1">
                                    <h3 class="text-h6 mb-1">{{ currentTicket?.subject }}</h3>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-center mb-1">
                                            <v-chip :color="getStatusColor(currentTicket?.status)" size="small"
                                                class="text-capitalize mr-2">
                                                {{ currentTicket?.status }}
                                            </v-chip>
                                            <span class="text-caption text-medium-emphasis">
                                                Created by {{ getCreatorName(currentTicket?.creator) }}
                                            </span>
                                        </div>
                                        <span class="text-caption text-medium-emphasis">
                                            {{ formatExactDateTime(currentTicket?.created_at) }}
                                        </span>
                                    </div>
                                </div>

                                <v-menu>
                                    <template v-slot:activator="{ props }">
                                        <v-btn icon v-bind="props">
                                            <v-icon>mdi-dots-vertical</v-icon>
                                        </v-btn>
                                    </template>
                                    <v-list>
                                        <v-list-item @click="updateTicketStatus('pending')">
                                            <v-list-item-title>Mark as Pending</v-list-item-title>
                                        </v-list-item>
                                        <v-list-item @click="updateTicketStatus('resolved')">
                                            <v-list-item-title>Mark as Resolved</v-list-item-title>
                                        </v-list-item>
                                        <v-list-item @click="updateTicketStatus('unresolved')">
                                            <v-list-item-title>Mark as Unresolved</v-list-item-title>
                                        </v-list-item>
                                        <v-divider></v-divider>
                                        <v-list-item @click="showCloseDialog = true">
                                            <v-list-item-title>Close Ticket</v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                            </v-sheet>

                            <!-- Messages Area -->
                            <div ref="messagesContainer" class="messages-container flex-grow-1 overflow-auto pa-6"
                                v-chat-scroll="{ smooth: true, always: false }">
                                <template v-if="loadingMessages">
                                    <div class="d-flex justify-center py-6">
                                        <v-progress-circular indeterminate></v-progress-circular>
                                    </div>
                                </template>

                                <template v-else-if="messages.length === 0">
                                    <div class="text-center">
                                        <v-icon size="64" color="grey-lighten-1"
                                            class="mb-4">mdi-message-outline</v-icon>
                                        <h3 class="text-h6 text-medium-emphasis mb-1">No messages yet</h3>
                                        <p class="text-body-2 text-medium-emphasis">Start the conversation with the user
                                        </p>
                                    </div>
                                </template>

                                <template v-else>
                                    <div v-for="(group, date) in groupedMessages" :key="date" class="message-group">
                                        <!-- Date Separator -->
                                        <div class="date-separator text-center my-6">
                                            <span class="text-caption bg-grey-lighten-3 px-3 py-1 rounded">{{
                                                formatDate(date) }}</span>
                                        </div>

                                        <!-- Messages -->
                                        <div v-for="message in group" :key="message.id" class="mb-4">
                                            <div class="d-flex"
                                                :class="message.sender_type === 'admin' ? 'justify-end' : 'justify-start'">
                                                <div class="message-wrapper"
                                                    :class="message.sender_type === 'admin' ? 'message-out' : 'message-in'">

                                                    <!-- Message Content -->
                                                    <div class="message-bubble pa-4 rounded-lg" :class="[
                                                        message.sender_type === 'admin'
                                                            ? 'bg-primary message-admin'
                                                            : 'bg-grey-lighten-3 message-user'
                                                    ]">
                                                        <div v-if="message.message" class="text-body-1 message-text"
                                                            :class="message.sender_type === 'admin' ? 'text-white' : ''"
                                                            v-html="formatMessageText(message.message)"></div>

                                                        <!-- File Attachment -->
                                                        <div v-if="message.has_file" class="mt-2">
                                                            <div v-if="isImageFile(message.file_name)"
                                                                class="text-center mb-2">
                                                                <v-img
                                                                    :src="`/admin/api/messages/${message.id}/download`"
                                                                    max-height="200" max-width="300" cover
                                                                    class="rounded-lg"
                                                                    :error-src="'https://placehold.co/300x200?text=Image+Not+Available'"></v-img>
                                                            </div>

                                                            <v-btn variant="text" density="compact"
                                                                :class="message.sender_type === 'admin' ? 'text-white' : ''"
                                                                prepend-icon="mdi-paperclip" size="small"
                                                                :href="`/admin/api/messages/${message.id}/download`"
                                                                target="_blank" download>
                                                                {{ message.file_name }}
                                                            </v-btn>
                                                        </div>
                                                    </div>

                                                    <!-- Sender Info - Moved below bubble -->
                                                    <div class="d-flex align-center mt-1"
                                                        :class="message.sender_type === 'admin' ? 'justify-end' : 'justify-start'">
                                                        <template v-if="message.sender_type !== 'admin'">
                                                            <v-avatar size="24" color="info-lighten-1" class="mr-2">
                                                                {{ getInitial(message.sender) }}
                                                            </v-avatar>
                                                            <span class="text-caption font-weight-medium mr-2">{{
                                                                getCreatorName(message.sender) }}</span>
                                                        </template>

                                                        <span class="text-caption text-medium-emphasis">{{
                                                            formatTime(message.created_at) }}</span>

                                                        <template v-if="message.sender_type === 'admin'">
                                                            <span
                                                                class="text-caption font-weight-medium ml-2">You</span>
                                                            <v-avatar size="24" color="primary-lighten-1"
                                                                class="ml-2">A</v-avatar>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Message Input -->
                            <v-sheet class="message-input px-4 py-3 border-t">
                                <div v-if="selectedFile"
                                    class="selected-file mb-2 pa-2 rounded bg-grey-lighten-4 d-flex align-center">
                                    <v-icon :icon="getFileIcon(selectedFile.name)" class="mr-2" size="small"></v-icon>
                                    <div class="flex-grow-1">
                                        <div class="text-body-2">{{ selectedFile.name }}</div>
                                        <div class="text-caption">{{ formatFileSize(selectedFile.size) }}</div>
                                    </div>
                                    <v-btn icon size="small" @click="removeSelectedFile">
                                        <v-icon size="small">mdi-close</v-icon>
                                    </v-btn>
                                </div>

                                <div class="d-flex align-center">
                                    <v-textarea v-model="messageText" variant="outlined" hide-details density="compact"
                                        rows="2" auto-grow class="message-textarea flex-grow-1 mr-3"
                                        placeholder="Type a message..."
                                        @keydown.enter.prevent="sendMessage"></v-textarea>

                                    <div class="d-flex align-center">
                                        <v-btn icon class="mr-2" @click="triggerFileInput">
                                            <v-icon>mdi-paperclip</v-icon>
                                            <input type="file" ref="fileInput" style="display: none"
                                                @change="onFileSelected" />
                                        </v-btn>

                                        <v-btn color="primary" :disabled="!messageText && !selectedFile"
                                            :loading="sendingMessage" @click="sendMessage">
                                            Send
                                        </v-btn>
                                    </div>
                                </div>
                            </v-sheet>
                        </template>
                    </v-col>
                </v-row>
            </v-card>
        </v-container>

        <!-- Close Ticket Dialog -->
        <v-dialog v-model="showCloseDialog" max-width="500">
            <v-card>
                <v-card-title>Close Ticket</v-card-title>
                <v-card-text>
                    <v-select v-model="closeTicketStatus" :items="closeStatusOptions" label="Resolution Status"
                        variant="outlined" required></v-select>

                    <v-textarea v-model="closeTicketReason" label="Resolution Reason" variant="outlined" rows="3"
                        required></v-textarea>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="showCloseDialog = false" variant="text">Cancel</v-btn>
                    <v-btn color="primary" @click="closeTicket" :loading="closingTicket" :disabled="!closeTicketReason">
                        Close Ticket
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-app>
</template>

<script>
import { ref, computed, onMounted, onBeforeUnmount, watch, nextTick } from 'vue';
import axios from 'axios';
import Pusher from 'pusher-js';

export default {
    name: 'AdminSupportChat',

    setup() {
        // State
        const tickets = ref([]);
        const messages = ref([]);
        const currentTicketUuid = ref(null);
        const selectedTicket = ref([]);
        const messageText = ref('');
        const loading = ref(false);
        const loadingMessages = ref(false);
        const sendingMessage = ref(false);
        const searchQuery = ref('');
        const statusFilter = ref('');
        const currentChannel = ref(null);
        const messagesContainer = ref(null);
        const fileInput = ref(null);
        const selectedFile = ref(null);
        const showCloseDialog = ref(false);
        const closeTicketStatus = ref('resolved');
        const closeTicketReason = ref('');
        const closingTicket = ref(false);

        // Constants
        const statusOptions = [
            { title: 'All Tickets', value: '' },
            { title: 'Pending', value: 'pending' },
            { title: 'Resolved', value: 'resolved' },
            { title: 'Unresolved', value: 'unresolved' }
        ];

        const closeStatusOptions = [
            { title: 'Resolved', value: 'resolved' },
            { title: 'Unresolved', value: 'unresolved' }
        ];

        // Computed
        const currentTicket = computed(() => {
            if (!currentTicketUuid.value) return null;
            return tickets.value.find(t => t.uuid === currentTicketUuid.value) || null;
        });

        const groupedMessages = computed(() => {
            const groups = {};
            messages.value.forEach(message => {
                const date = new Date(message.created_at).toLocaleDateString();
                if (!groups[date]) {
                    groups[date] = [];
                }
                groups[date].push(message);
            });
            return groups;
        });

        // Methods
        const loadTickets = async () => {
            const currentTicketId = currentTicketUuid.value; // Store current selection
            loading.value = true;

            try {
                const response = await axios.get('/admin/api/tickets', {
                    params: {
                        status: statusFilter.value,
                        search: searchQuery.value
                    }
                });
                tickets.value = response.data.data;

                // Check if current ticket still exists in results
                if (currentTicketId && tickets.value.some(t => t.uuid === currentTicketId)) {
                    // If it exists, keep it selected
                    currentTicketUuid.value = currentTicketId;
                }

                // Also update unread counts for all tickets
                loadUnreadCounts();

            } catch (error) {
                console.error('Error loading tickets:', error);
                showToast('Error loading tickets. Please try again.', 'error');
            } finally {
                loading.value = false;
            }
        };



        const loadUnreadCounts = async () => {
            try {
                const response = await axios.get('/admin/api/unread-messages');
                const unreadCounts = response.data.tickets || {};

                // Update unread counts in ticket list
                tickets.value = tickets.value.map(ticket => ({
                    ...ticket,
                    unread_count: unreadCounts[ticket.uuid] || 0
                }));

            } catch (error) {
                console.error('Error loading unread counts:', error);
            }
        };



        const loadMessages = async (uuid) => {
            loadingMessages.value = true;
            try {
                const response = await axios.get(`/admin/api/tickets/${uuid}/messages`);
                messages.value = response.data;

                // Scroll to bottom after messages load
                await nextTick();
                if (messagesContainer.value) {
                    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
                }
            } catch (error) {
                console.error('Error loading messages:', error);
                showToast('Error loading messages. Please try again.', 'error');
            } finally {
                loadingMessages.value = false;
            }
        };

        const sendMessage = async () => {
            if ((!messageText.value || messageText.value.trim() === '') && !selectedFile.value) {
                return;
            }

            sendingMessage.value = true;

            try {
                const formData = new FormData();
                if (messageText.value) {
                    formData.append('message', messageText.value);
                }

                if (selectedFile.value) {
                    formData.append('file', selectedFile.value);
                }

                const response = await axios.post(
                    `/admin/api/tickets/${currentTicketUuid.value}/messages`,
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                );

                // Add new message to list
                messages.value.push({
                    ...response.data.message,
                    sender: response.data.sender
                });

                // Clear input
                messageText.value = '';
                selectedFile.value = null;

                // Scroll to bottom
                await nextTick();
                if (messagesContainer.value) {
                    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
                }
            } catch (error) {
                console.error('Error sending message:', error);
                showToast('Error sending message. Please try again.', 'error');
            } finally {
                sendingMessage.value = false;
            }
        };

        const updateTicketStatus = async (status) => {
            try {
                await axios.put(`/admin/api/tickets/${currentTicketUuid.value}/status`, { status });

                // Update local ticket status
                const ticket = tickets.value.find(t => t.uuid === currentTicketUuid.value);
                if (ticket) {
                    ticket.status = status;
                }

                showToast(`Ticket marked as ${status}`, 'success');
            } catch (error) {
                console.error('Error updating ticket status:', error);
                showToast('Error updating ticket status. Please try again.', 'error');
            }
        };

        const closeTicket = async () => {
            if (!closeTicketReason.value) {
                showToast('Please provide a resolution reason', 'warning');
                return;
            }

            closingTicket.value = true;

            try {
                await axios.post(`/admin/api/tickets/${currentTicketUuid.value}/close`, {
                    resolution_status: closeTicketStatus.value,
                    reason: closeTicketReason.value
                });

                // Update local ticket status
                const ticket = tickets.value.find(t => t.uuid === currentTicketUuid.value);
                if (ticket) {
                    ticket.status = closeTicketStatus.value;
                }

                // Close dialog and reset form
                showCloseDialog.value = false;
                closeTicketReason.value = '';
                showToast('Ticket closed successfully', 'success');
            } catch (error) {
                console.error('Error closing ticket:', error);
                showToast('Error closing ticket. Please try again.', 'error');
            } finally {
                closingTicket.value = false;
            }
        };

        const formatMessageText = (text) => {
            if (!text) return '';

            // Escape HTML and replace new lines with <br>
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML.replace(/\n/g, '<br>');
        };

        const getStatusColor = (status) => {
            switch (status) {
                case 'pending': return 'warning';
                case 'resolved': return 'success';
                case 'unresolved': return 'error';
                default: return 'grey';
            }
        };

        const getInitial = (person) => {
            if (!person) return 'U';

            if (person.first_name && person.first_name.length > 0) {
                return person.first_name.charAt(0).toUpperCase();
            } else if (person.last_name && person.last_name.length > 0) {
                return person.last_name.charAt(0).toUpperCase();
            }

            return 'U';
        };

        const formatExactDateTime = (dateString) => {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        };

        const getInitialColor = (person) => {
            const colors = ['primary', 'secondary', 'info', 'success', 'warning'];

            if (!person || !person.email) {
                return colors[0];
            }

            // Use email to consistently pick a color
            const sumChars = person.email.split('').reduce((sum, char) => sum + char.charCodeAt(0), 0);
            return colors[sumChars % colors.length];
        };

        const getCreatorName = (person) => {
            if (!person) return 'Unknown';

            let name = '';
            if (person.first_name) name += person.first_name;
            if (person.last_name) {
                if (name) name += ' ';
                name += person.last_name;
            }

            return name || person.email || 'Unknown';
        };

        const formatDate = (dateString) => {
            const date = new Date(dateString);
            const today = new Date();
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);

            if (date.toDateString() === today.toDateString()) {
                return 'Today';
            } else if (date.toDateString() === yesterday.toDateString()) {
                return 'Yesterday';
            }

            return date.toLocaleDateString('en-US', {
                weekday: 'long',
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        };

        const formatTime = (dateString) => {
            const date = new Date(dateString);
            return date.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        };

        const getTimeAgo = (dateString) => {
            if (!dateString) return '';

            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);

            if (diffInSeconds < 60) {
                return 'Just now';
            }

            const diffInMinutes = Math.floor(diffInSeconds / 60);
            if (diffInMinutes < 60) {
                return `${diffInMinutes} min${diffInMinutes > 1 ? 's' : ''} ago`;
            }

            const diffInHours = Math.floor(diffInMinutes / 60);
            if (diffInHours < 24) {
                return `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;
            }

            const diffInDays = Math.floor(diffInHours / 24);
            return `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;


            // const diffInWeeks = Math.floor(diffInDays / 7);
            // if (diffInWeeks < 4) {
            //     return `${diffInWeeks} week${diffInWeeks > 1 ? 's' : ''}`;
            // }

            // const diffInMonths = Math.floor(diffInDays / 30);
            // if (diffInMonths < 12) {
            //     return `${diffInMonths} month${diffInMonths > 1 ? 's' : ''}`;
            // }

            // const diffInYears = Math.floor(diffInDays / 365);
            // return `${diffInYears} year${diffInYears > 1 ? 's' : ''}`;
        };

        const isImageFile = (fileName) => {
            if (!fileName) return false;
            const extension = fileName.split('.').pop().toLowerCase();
            return ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension);
        };

        const getFileIcon = (fileName) => {
            if (!fileName) return 'mdi-file';

            const extension = fileName.split('.').pop().toLowerCase();

            switch (extension) {
                case 'pdf': return 'mdi-file-pdf';
                case 'doc':
                case 'docx': return 'mdi-file-word';
                case 'xls':
                case 'xlsx': return 'mdi-file-excel';
                case 'zip':
                case 'rar':
                case '7z': return 'mdi-folder-zip';
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'webp': return 'mdi-file-image';
                default: return 'mdi-file';
            }
        };

        const formatFileSize = (bytes) => {
            if (bytes < 1024) {
                return bytes + ' bytes';
            } else if (bytes < 1024 * 1024) {
                return (bytes / 1024).toFixed(1) + ' KB';
            } else {
                return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
            }
        };

        const debounceSearch = (() => {
            let timeout;
            return () => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    loadTickets();
                }, 500);
            };
        })();

        const showToast = (message, type = 'success') => {
            if (typeof toastr !== 'undefined') {
                if (type === 'success') {
                    toastr.success(message, 'Success');
                } else if (type === 'error' || type === 'danger') {
                    toastr.error(message, 'Error');
                } else if (type === 'warning') {
                    toastr.warning(message, 'Warning');
                } else if (type === 'info') {
                    toastr.info(message, 'Information');
                }
            } else {
                console.log(`[${type.toUpperCase()}] ${message}`);
            }
        };

        const triggerFileInput = () => {
            if (fileInput.value) {
                fileInput.value.click();
            }
        };

        const onFileSelected = (event) => {
            const file = event.target.files[0];
            if (!file) return;

            // Check file size (10MB max)
            if (file.size > 10 * 1024 * 1024) {
                showToast('File is too large. Maximum size is 10MB.', 'warning');
                return;
            }

            selectedFile.value = file;
        };

        const removeSelectedFile = () => {
            selectedFile.value = null;
            if (fileInput.value) {
                fileInput.value.value = null;
            }
        };

        const selectTicket = async (uuid) => {
            currentTicketUuid.value = uuid;
            selectedTicket.value = [uuid];
            await loadMessages(uuid);

            // Subscribe to real-time updates for this ticket if Echo is available
            if (typeof window.Echo !== 'undefined') {
                subscribeToTicketChannel(uuid);
            }
        };



        // WebSocket handling
        const subscribeToTicketChannel = async (uuid) => {
            // const pusherMethods = new Pusher('e667ae07c60e9c73c382', {
            //     cluster: 'us3',
            // });

            // const channelName = `ticket.${uuid}`;
            // const channelTestName = `test-channel`;

            // const channel = pusherMethods.subscribe(channelName);
            // const channelTest = pusherMethods.subscribe(channelTestName);

            // console.log("-------------------------");
            // console.log({ channel });
            // console.log("-------------------------");

            // console.log("-------------------------");
            // console.log({ channelTest });
            // console.log("-------------------------");

            // channelTest.bind("App/Events/Trigger", (data) => {
            //     console.log("trigger")
            //     console.log(data)
            //     // Method to be dispatched on trigger.
            // });

            // channel.bind(".MessageCreated", (data) => {
            //     console.log(".MessageCreated")
            //     console.log(data)
            //     // Method to be dispatched on trigger.
            // });

            // channel.bind("MessageCreated", (data) => {
            //     console.log("MessageCreated")
            //     console.log(data)
            //     // Method to be dispatched on trigger.
            // });

            // channelTest.bind('pusher:subscription_succeeded', () => {
            //     console.log('Subscribed successfully. Members:', channelTest.members);
            // });

            // channelTest.bind('pusher:member_added', (member) => {
            //     console.log('New member joined:', member);
            // });


            // channel.bind('pusher:subscription_succeeded', () => {
            //     console.log('Subscribed successfully. Members:', channelTest.members);
            // });

            // channel.bind('pusher:member_added', (member) => {
            //     console.log('New member joined:', member);
            // });

            // channel.bind(".MessageCreated", (data) => {
            //     console.log(data)
            //     // Method to be dispatched on trigger.
            // });

            // window.Echo.leaveAllChannels();

            if (typeof window.Echo === 'undefined') {
                console.error('Echo is not available');
                return;
            }

            // Leave previous channel if any
            if (currentChannel.value) {
                try {
                    await window.Echo.leave(currentChannel.value);
                } catch (e) {
                    console.error('Error leaving channel:', e);
                }
            }

            const channelName = `ticket.${uuid}`;
            currentChannel.value = channelName;

            console.log('window.Echo.channel(channelName):', window.Echo.channel(channelName));


            // Add error handling and use public channel
            try {


                // window.Echo.channel(channelName).listen('pusher:subscription_succeeded', (e) => {
                //     console.log(`Successfully subscribed to ${channelName}`);
                //     console.log(e, 'listening to MessageCreated');
                //     console.log('Subscribed successfully. Members:', window.Echo.channel(channelName).members);

                // }).error((err) => {
                //     console.error(`Channel error on ${channelName}:`, err);
                //     console.error('Error subscribing to channel:', err);
                // });

                // window.Echo.channel(channelName).listen('pusher:member_added', (e) => {
                //     console.log(`Successfully subscribed to ${channelName}`);
                //     console.log(e, 'listening to MessageCreated');
                //     console.log('Subscribed successfully. Members:', window.Echo.channel(channelName).members);

                // }).error((err) => {
                //     console.error(`Channel error on ${channelName}:`, err);
                //     console.error('Error subscribing to channel:', err);
                // });

                // window.Echo.channel('my-channel').listen("my-event", (data) => {
                //     console.log(data);
                // // add new price into the APPL widget
                // });

                // Add channel-level error handler
                // window.Echo.channel(channelName).listen('GotMessage', async (e) => {
                window.Echo.channel('channel_for_everyone').listen('GotMessage', async (e) => {
                    console.log(`Successfully subscribed to ${channelName}`);
                    console.log(e, 'listening to MessageCreated');

                }).error((err) => {
                    console.error(`Channel error on ${channelName}:`, err);
                });

                // window.Echo.channel(channelName).listen('MessageCreated', (e) => {
                //     console.log(`Successfully subscribed to ${channelName}`);
                //     console.log(e, 'listening to MessageCreated');

                // }).error((err) => {
                //     console.error(`Channel error on ${channelName}:`, err);
                //     console.error('Error subscribing to channel:', err);
                // });

            } catch (error) {
                console.error('Error subscribing to channel:', error);
                // Consider adding user notification for critical errors
                // useToast().error(`Connection error: ${error.message}`);
            }
        };
        // Add this method to properly handle new messages
        const handleNewMessage = (e) => {
            console.log('Processing new message:', e);

            // Check if message already exists to prevent duplicates
            const messageExists = messages.value.some(m => m.id === e.id);

            if (!messageExists) {
                console.log('Adding new message to chat');
                // Add message to the list
                messages.value.push({
                    id: e.id,
                    ticket_id: e.ticket_id,
                    sender_id: e.sender_id,
                    sender_type: e.sender_type || (e.sender && e.sender.is_admin ? 'admin' : 'user'),
                    message: e.message,
                    file_path: e.file_path,
                    file_name: e.file_name,
                    has_file: e.has_file,
                    created_at: e.created_at,
                    sender: e.sender
                });

                // Sort messages by timestamp to ensure correct order
                messages.value.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

                // Scroll to bottom after new message
                nextTick(() => {
                    if (messagesContainer.value) {
                        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
                    }
                });

                // Play notification sound if available
                if (typeof Audio !== 'undefined') {
                    try {
                        const audio = new Audio('/assets/sounds/notification.wav');
                        audio.play().catch(e => console.log('Could not play notification sound', e));
                    } catch (error) {
                        console.log('Audio notification not supported');
                    }
                }

                // Mark message as read if needed
                if (e.sender_type !== 'admin' && typeof markMessagesAsRead === 'function') {
                    markMessagesAsRead([e.id], uuid);
                }
            } else {
                console.log('Message already exists, skipping');
            }
        };


        // Lifecycle hooks
        onMounted(() => {
            
window.Echo.channel('channel_for_everyone')
    .listen('GotMessage', (event) => {
        console.log(event);
    });
window.Echo.channel('channel_for_everyone')
    .listen('.GotMessage', (event) => {
        console.log(event);
    });
    Echo.channel('channel_for_everyone')
            .listen('GotMessage', (e) => {
                console.log('Received message via Echo:', e);
                // Only add messages for this ticket
            });
            loadTickets();

            // Set up periodic refresh for unread counts
            const unreadInterval = setInterval(() => {
                loadUnreadCounts();
            }, 60000); // Every minute

            // Cleanup interval on component unmount
            onBeforeUnmount(() => {
                clearInterval(unreadInterval);

                // Leave channel if subscribed
                if (currentChannel.value && typeof window.Echo !== 'undefined') {
                    window.Echo.leave(currentChannel.value);
                }
            });
        });


        // Watchers
        watch(statusFilter, () => {
            loadTickets();
        });

        return {
            // State
            tickets,
            messages,
            currentTicketUuid,
            selectedTicket,
            messageText,
            loading,
            loadingMessages,
            sendingMessage,
            searchQuery,
            statusFilter,
            messagesContainer,
            fileInput,
            selectedFile,
            showCloseDialog,
            closeTicketStatus,
            closeTicketReason,
            closingTicket,

            // Computed
            currentTicket,
            groupedMessages,

            // Constants
            statusOptions,
            closeStatusOptions,

            formatExactDateTime,

            // Methods
            loadTickets,
            selectTicket,
            loadMessages,
            sendMessage,
            updateTicketStatus,
            closeTicket,
            formatMessageText,
            getStatusColor,
            getInitial,
            getInitialColor,
            getCreatorName,
            formatDate,
            formatTime,
            getTimeAgo,
            isImageFile,
            getFileIcon,
            formatFileSize,
            debounceSearch,
            triggerFileInput,
            onFileSelected,
            removeSelectedFile
        };
    },

    directives: {
        'chat-scroll': {
            mounted(el, binding) {
                const scrollOptions = binding.value || {};

                // On mount, scroll to bottom
                el.scrollTop = el.scrollHeight;

                // Watch for content changes to auto-scroll
                const observer = new MutationObserver(() => {
                    if (scrollOptions.always || el.scrollTop + el.clientHeight + 100 >= el.scrollHeight) {
                        setTimeout(() => {
                            el.scrollTop = el.scrollHeight;
                        }, 0);
                    }
                });

                observer.observe(el, { childList: true, subtree: true });

                // Save observer to be able to disconnect it later
                el._chatScrollObserver = observer;

            },
            unmounted(el) {
                if (el._chatScrollObserver) {
                    el._chatScrollObserver.disconnect();
                }
            }
        }
    }
};
</script>

<style scoped>
/* Make sure the component takes the full available space */
.v-application,
.v-container {
    height: 100%;
    width: 100%;
}

.border-b {
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
}

.border-r {
    border-right: 1px solid rgba(0, 0, 0, 0.12);
}

.border-t {
    border-top: 1px solid rgba(0, 0, 0, 0.12);
}

.message-wrapper {
    max-width: 80%;
}

.message-in .message-bubble {
    background-color: #f5f5f5;
    color: rgba(0, 0, 0, 0.87);
}

.message-out .message-bubble {
    background-color: #e3f2fd;
    color: rgba(0, 0, 0, 0.87);
}

.messages-container {
    height: calc(100vh - 224px);
}

/* Handle long file names */
.message-bubble a {
    word-break: break-word;
}

.message-text {
    white-space: pre-line;
    word-break: break-word;
}

/* Make the ticket list items more compact */
.ticket-list .v-list-item {
    min-height: 64px !important;
}

.status-select :deep(.v-field__input) {
    color: white !important;
}

.v-avatar {
    color: white;
    font-weight: 500;
}

:deep(.v-application),
:deep(.v-container),
:deep(.v-row),
:deep(.v-card) {
    width: 100% !important;
    max-width: 100% !important;
}

/* Other styles */
.border-b {
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
}

.border-r {
    border-right: 1px solid rgba(0, 0, 0, 0.12);
}

.border-t {
    border-top: 1px solid rgba(0, 0, 0, 0.12);
}

.message-wrapper {
    max-width: 80%;
}

.message-in .message-bubble {
    background-color: #f5f5f5;
    color: rgba(0, 0, 0, 0.87);
}

.message-out .message-bubble {
    background-color: #e3f2fd;
    color: rgba(0, 0, 0, 0.87);
}

.messages-container {
    height: calc(100vh - 224px);
}

/* Handle long file names */
.message-bubble a {
    word-break: break-word;
}

.message-text {
    white-space: pre-line;
    word-break: break-word;
}

/* Make the ticket list items more compact */
.ticket-list :deep(.v-list-item) {
    min-height: 64px !important;
}

/* Ensure the selector is specific enough to override Vuetify's */
:deep(.v-field__input) {
    color: inherit !important;
}

:deep(.v-avatar) {
    color: white;
    font-weight: 500;
}

.message-wrapper {
    max-width: 80%;
}

.message-user {
    background-color: #f0f2f5 !important;
    color: rgba(0, 0, 0, 0.87);
    border-radius: 18px !important;
    border-top-left-radius: 4px !important;
}

.message-admin {
    background-color: #673ab7 !important;
    color: white !important;
    border-radius: 18px !important;
    border-top-right-radius: 4px !important;
}

.message-text {
    white-space: pre-line;
    word-break: break-word;
}


/* Responsive Breakpoints and Adjustments */
@media (max-width: 600px) {

    /* On extra small screens, stack sidebar and chat area vertically */
    .ticket-sidebar,
    .chat-area {
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }

    .ticket-sidebar {
        border-bottom: thin solid rgba(0, 0, 0, 0.12);
        max-height: 50vh;
        /* Limit height on mobile */
    }

    .chat-area {
        flex-grow: 1;
    }

    /* Adjust font sizes for mobile */
    .v-list-item-title {
        font-size: 0.9rem !important;
    }

    .v-list-item-subtitle {
        font-size: 0.8rem !important;
    }
}

@media (min-width: 601px) and (max-width: 960px) {

    /* Tablet-specific adjustments */
    .ticket-sidebar {
        max-width: 40% !important;
    }

    .chat-area {
        max-width: 60% !important;
    }
}

@media (min-width: 961px) {

    /* Desktop and larger screens */
    .ticket-sidebar {
        max-width: 30% !important;
    }

    .chat-area {
        max-width: 70% !important;
    }
}

/* Ensure full height and remove unexpected spacing */
.v-application,
.v-container,
.v-card,
.v-row {
    height: 100%;
}

/* Flexbox adjustments for responsiveness */
.ticket-sidebar,
.chat-area {
    display: flex;
    flex-direction: column;
}

/* Prevent overflow on smaller screens */
.ticket-list {
    overflow-x: hidden;
}

/* Make text and elements more responsive */
.v-list-item {
    flex-wrap: nowrap;
}

.v-list-item__content {
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>