<style>
.menu-state-bg-light-primary .menu-item:not(.here) .menu-link:hover:not(.disabled):not(.active):not(.here) {
    background-color: #d0aae5 !important;
}
</style>
<template>
    <div class="card">
        <div class="card-header border-0">
            <div class="card-title">
                Help & Support
            </div>
            <div class="card-toolbar">
            </div>
        </div>

        <div class="d-flex flex-column flex-lg-row m-md-4">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0">
                <div class="card card-flush mb-5">
    <div class="card-header border-0 pt-6">
        <!--begin::Card title-->
        <div class="card-title">
            <h5>Tickets</h5>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                <button type="button" class="btn btn-sm btn-light-primary me-1" @click="showCreateTicketModal = true">
                    <i class="fa fa-plus fs-2 text-white"></i> Create
                </button>
            </div>
        </div>
    </div>
    <div class="card-header pt-3 d-flex justify-content-between align-items-center" id="kt_chat_contacts_header">
        <select class="form-select w-100 mb-2" v-model="selectedStatus" style="background-color:#e9e8e6">
            <option value="all">All Tickets</option>
            <option value="pending">Pending</option>
            <option value="resolved">Resolved</option>
            <option value="unresolved">Unresolved</option>
        </select>
        <form class="w-100 position-relative" autocomplete="off">
            <i class="fa fa-search fs-3 text-gray-500 position-absolute top-50 ms-5 translate-middle-y"></i>
            <input type="text" class="form-control form-control-solid px-13" name="search" v-model="searchQuery"
                placeholder="Search by title or description" />
        </form>
    </div>

    <div class="card-body pt-2" id="kt_chat_contacts_body">
        <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto" data-kt-scroll="true"
            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
            data-kt-scroll-dependencies="#kt_header, #kt_app_header, #kt_toolbar, #kt_app_toolbar, #kt_footer, #kt_app_footer, #kt_chat_contacts_header"
            data-kt-scroll-wrappers="#kt_content, #kt_app_content, #kt_chat_contacts_body" data-kt-scroll-offset="5px"
            style="max-height: 684px;">
            <!-- Loop through filtered tickets -->
            <div v-for="ticket in filteredTickets" :key="ticket.id" @click="selectTicket(ticket.id)" style="cursor: pointer;">
                <!-- Ticket Item -->
                <div class="d-flex flex-stack py-4">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-45px symbol-circle">
                            <span class="symbol-label bg-light-info text-info fs-6 fw-bolder">
                                {{ ticket.subject[0].toUpperCase() }}
                            </span>
                        </div>
                        <div class="ms-5">
                            <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">
                                {{ truncate(ticket.subject, 20) }}
                            </a>
                            <div class="fw-semibold text-muted">
                                {{ truncate(ticket.description, 20) }}
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column align-items-end ms-2">
                        <span class="text-muted fs-7 mb-1">{{ formatRelativeTime(ticket.created_at) }}</span>
                        <span class="badge text-capitalize" style="letter-spacing: 1px;" :class="getStatusBadge(ticket.status)" >{{ ticket.status }}</span>
                        <span v-if="ticket.unreadMessagesCount > 0"
                            class="badge badge-sm badge-circle badge-light-warning">
                            {{ ticket.unreadMessagesCount }}
                        </span>
                    </div>
                </div>
                <div class="separator separator-dashed"></div>
            </div>
        </div>
    </div>
</div>

            </div>

            <div v-if="selectedTicket" class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                <!-- Loader -->
                <div v-if="loading" class="position-absolute top-50 start-50 translate-middle">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <!-- Chat Content -->
                <div v-else>
                    <div class="card" id="kt_chat_messenger">
                        <div class="card-header" id="kt_chat_messenger_header">
                            <div class="card-title">
                                <div class="d-flex justify-content-center flex-column me-3">
                                    <a v-if="selectedTicket" href="#"
                                        class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">{{
                                            selectedTicket.subject }}</a>
                                    <div class="mb-0 lh-1">
                                        <!-- <span class="badge badge-success badge-circle w-10px h-10px me-1"></span> -->
                                        <!-- <span class="fs-7 fw-semibold text-muted">Active</span> -->
                                        <span v-if="selectedTicket" class="badge text-capitalize"
                                            style="letter-spacing: 1px;"
                                            :class="getStatusBadge(selectedTicket.status)">{{
                                                selectedTicket.status }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-toolbar">
                                <!--begin::Menu-->
                                <div class="me-n3">
                                    <button v-if="selectedTicket && selectedTicket.status === 'resolved'"
                                        class="btn btn-sm btn-secondary" disabled>Resolved</button>
                                    <button v-if="selectedTicket && selectedTicket.status === 'pending'"
                                        class="btn btn-sm btn-danger p-2" @click="closeTicket('resolved')"> Mark as
                                        Resolved</button>
                                </div>
                            </div>
                        </div>

                        <!--begin::Card body-->
                        <div class="card-body" id="kt_chat_messenger_body">
                            <div class="scroll-y me-n5 pe-5 h-300px h-lg-auto" style="max-height: 739px;" ref="chatBody"
                                data-kt-element="messages" data-kt-scroll="true"
                                data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                                data-kt-scroll-dependencies="#kt_header, #kt_app_header, #kt_app_toolbar, #kt_toolbar, #kt_footer, #kt_app_footer, #kt_chat_messenger_header, #kt_chat_messenger_footer"
                                data-kt-scroll-wrappers="#kt_content, #kt_app_content, #kt_chat_messenger_body"
                                data-kt-scroll-offset="5px">
                                <div v-for="(group, date) in messages" :key="date">
                                    <!-- Date Header -->
                                    <div class="text-center my-3">
                                        <span class="badge bg-light-primary text-primary">{{ group.date }}</span>
                                    </div>

                                    <!-- Display Messages -->
                                    <div v-for="message in group.messages" :key="message.id"
                                        :class="message.sender_id === user.id ? 'd-flex justify-content-end mb-4' : 'd-flex justify-content-start mb-10'">
                                        <div class="d-flex flex-column align-items-end"
                                            v-if="message.sender_id === user.id">
                                            <div class="p-3 rounded bg-light-info text-gray-900 fw-semibold mw-lg-400px text-end"
                                                v-if="message.message">
                                                {{ message.message }}
                                            </div>
                                            <div v-if="message.file_url">
                                                <a :href="`/api/messages/${message.id}/download`" target="_blank"
                                                    class="text-dark">
                                                    {{ message.file_name }}
                                                </a>
                                            </div>
                                            <div class="mt-1">
                                                <span class="text-muted fs-7">{{ message.timeFormatted }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-start" v-else>
                                            <div class="p-5 rounded bg-light-warning text-gray-900 fw-semibold mw-lg-400px text-start"
                                                v-if="message.message">
                                                {{ message.message }}
                                            </div>
                                            <div v-if="message.file_url">
                                                <a :href="message.file_url" target="_blank" class="text-dark">{{
                                                    message.file_name
                                                    }}</a>
                                            </div>
                                            <div class="mt-2">
                                                <span class="text-muted fs-7">{{ message.timeFormatted }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Card body-->


                        <!--begin::Card footer-->
                        <div v-if="selectedTicket && selectedTicket.status === 'pending'"
                            class="card-footer pt-4 position-sticky bottom-0 bg-white">
                            <div id="file-preview-container" class="mb-3">
                                <div v-if="file" class="p-2 bg-light rounded mb-2 text-gray-900">
                                    <span>{{ file.name }} ({{ (file.size / 1024).toFixed(2) }} KB)</span>
                                </div>
                            </div>
                            <textarea v-model="message" class="form-control form-control-flush mb-3" rows="2"
                                placeholder="Type a message"></textarea>
                            <div class="d-flex flex-stack">
                                <div class="d-flex align-items-center me-2">
                                    <button class="btn btn-light me-2 position-relative" type="button">
                                        <i class="fa fa-paperclip fs-3"></i>
                                        <input type="file"
                                            class="position-absolute top-0 start-0 opacity-0 w-100 h-100 cursor-pointer"
                                            @change="onFileChange" />
                                    </button>
                                </div>
                                <button class="btn btn-primary" type="button" @click="sendMessage">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div v-if="showCreateTicketModal" class="modal d-block" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Ticket</h5>
                    <button type="button" class="btn-close" @click="showCreateTicketModal = false"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ticket-subject" class="form-label">Subject</label>
                        <input type="text" id="ticket-subject" class="form-control" v-model="newTicket.subject"
                            placeholder="Enter subject">
                    </div>
                    <div class="mb-3">
                        <label for="ticket-description" class="form-label">Description</label>
                        <textarea id="ticket-description" class="form-control" v-model="newTicket.description"
                            placeholder="Enter description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        @click="showCreateTicketModal = false">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="createTicket">Create</button>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";

dayjs.extend(relativeTime);

export default {
    data() {
        return {
            user: window.Laravel.user,
            tickets: [], // Tickets data
            searchQuery: "", // User input for searching
            ticketStatuses: {},
            selectedTicket: null,
            selectedTicketId: null,
            messages: [],
            message: "",
            file: null,
            selectedStatus: "all", // Selected ticket status
            showCreateTicketModal: false, // Control for modal visibility
            newTicket: {
                subject: "",
                description: "",
            },
            loading: false, // Add loading state
        };
    },

    computed: {
        filteredTickets() {
            // Filter tickets by status and search query
            return this.tickets.filter((ticket) => {
                const matchesStatus =
                    this.selectedStatus === "all" || ticket.status === this.selectedStatus;
                const matchesSearch =
                    ticket.subject.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    ticket.description.toLowerCase().includes(this.searchQuery.toLowerCase());
                return matchesStatus && matchesSearch;
            });
        },
    },
    methods: {
        closeTicket(status) {
            if (!this.selectedTicketId) {
                console.error("No ticket selected.");
                return;
            }

            // Show a confirmation dialog
            Swal.fire({
                text: `Are you sure you want to mark this ticket as ${status}?`,
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, proceed!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-secondary",
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    // If the user confirms, proceed with closing the ticket
                    this.loading = true;
                    axios.post(`/api/tickets/${this.selectedTicketId}/close`, { status })
                        .then(() => {
                            // Update the ticket's status locally
                            const ticket = this.tickets.find(ticket => ticket.id === this.selectedTicketId);
                            if (ticket) {
                                ticket.status = status;
                            }

                            // Update the selected ticket's status
                            if (this.selectedTicket) {
                                this.selectedTicket.status = status;
                            }

                            // Refresh the tickets list
                            this.groupTicketsByStatus();

                            // Show a success notification
                            Swal.fire({
                                text: `Ticket marked as ${status}.`,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-success",
                                },
                            });
                        })
                        .catch((error) => {
                            console.error("Error closing ticket:", error);

                            // Show an error notification
                            Swal.fire({
                                text: "An error occurred while closing the ticket. Please try again.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-danger",
                                },
                            });
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            });
        },

        groupTicketsByStatus() {
            this.ticketStatuses = this.tickets.reduce((acc, ticket) => {
                if (!acc[ticket.status]) acc[ticket.status] = [];
                acc[ticket.status].push(ticket);
                return acc;
            }, {});
        },

        truncate(text, limit) {
            if (!text) return '';
            return text.length > limit ? text.substring(0, limit) + '...' : text;
        },
        formatRelativeTime(date) {
            const now = dayjs(); // Current date
            const created = dayjs(date); // Ticket's creation date
            return created.from(now); // Example: "2 minutes ago"
        },
        getStatusBadge(status) {
            switch (status) {
                case 'pending':
                    return 'badge-warning'; // Replace with your actual CSS class for pending
                case 'resolved':
                    return 'badge-success'; // Replace with your actual CSS class for resolved
                case 'unresolved':
                    return 'badge-danger'; // Replace with your actual CSS class for unresolved
                default:
                    return 'badge-secondary'; // Default CSS class
            }
        },

        fetchTickets() {
            axios.get('/api/tickets')
                .then((response) => {
                    this.tickets = response.data.map((ticket) => ({
                        ...ticket,
                        unreadMessagesCount: ticket.unreadMessagesCount || 0, // Handle unread messages dynamically
                    }));
                })
                .catch((error) => {
                    console.error("Error fetching tickets:", error);
                });
        },
        groupTicketsByStatus() {
            this.ticketStatuses = this.tickets.reduce((acc, ticket) => {
                if (!acc[ticket.status]) acc[ticket.status] = [];
                acc[ticket.status].push(ticket);
                return acc;
            }, {});
        },
        selectTicket(ticketId) {
            this.loading = true; // Set loading to true
            this.selectedTicketId = ticketId;
            this.selectedTicket = this.tickets.find((ticket) => ticket.id === ticketId);

            this.fetchMessages(ticketId)
                .then(() => {
                    this.loading = false; // Stop loading after successful fetch
                })
                .catch(() => {
                    this.loading = false; // Stop loading on error
                });
        },
        fetchMessages(ticketId) {
            return axios
                .get(`/api/tickets/${ticketId}/messages`)
                .then((response) => {
                    // Group messages by date
                    const grouped = response.data.reduce((acc, message) => {
                        const messageDate = dayjs(message.created_at).format("MMMM D, YYYY");
                        if (!acc[messageDate]) acc[messageDate] = [];
                        acc[messageDate].push({
                            ...message,
                            relativeTime: dayjs(message.created_at).fromNow(),
                            timeAgo: this.formatTimeAgo(message.created_at),
                            timeFormatted: this.formatTime(message.created_at),
                        });
                        return acc;
                    }, {});

                    // Map the grouped messages into the desired structure
                    this.messages = Object.keys(grouped).map((date) => ({
                        date,
                        messages: grouped[date],
                    }));
                })
                .catch((error) => {
                    console.error("Error fetching messages:", error);
                    throw error; // Ensure a rejected Promise for chaining
                });
        },
        createTicket() {

            if (!this.newTicket.subject || !this.newTicket.description) {
                alert("Please fill in both the subject and description.");
                return;
            }

            axios
                .post('/api/tickets', this.newTicket)
                .then((response) => {
                    const newTicket = response.data;
                    this.tickets.push({
                        ...newTicket,
                        unreadMessagesCount: 0,
                        subject: newTicket.subject || 'Untitled', // Default subject if missing
                    });


                    // Automatically select the new ticket
                    this.selectedTicketId = newTicket.id;
                    this.selectedTicket = newTicket;

                    // Fetch the new ticket's messages (likely empty initially)
                    this.fetchMessages(newTicket.id);

                    this.newTicketSubject = '';
                    this.newTicketDescription = '';
                    this.showCreateTicketModal = false; // Close the modal

                    // Refresh tickets
                    this.fetchTickets();
                })
                .catch((error) => {
                    console.error('Error creating ticket:', error);
                });
        },
        formatTimeAgo(date) {
            const now = new Date();
            const messageDate = new Date(date);
            const diff = Math.floor((now - messageDate) / 1000);

            if (diff < 60) return `${diff} seconds ago`;
            if (diff < 3600) return `${Math.floor(diff / 60)} minutes ago`;
            if (diff < 86400) return `${Math.floor(diff / 3600)} hours ago`;

            const daysAgo = Math.floor(diff / 86400);
            return daysAgo === 1 ? '1 day ago' : `${daysAgo} days ago`;
        },
        formatTime(date) {
            const options = {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true,
            };
            return new Date(date).toLocaleTimeString([], options);
        },
        onFileChange(event) {
            this.file = event.target.files[0]; // Store the selected file
        },
        sendMessage() {
            if (!this.selectedTicketId) {
                console.error("No ticket selected.");
                return;
            }

            if (!this.message && !this.file) {
                console.error("Message content or file is required.");
                return;
            }

            const formData = new FormData();
            formData.append("ticket_id", this.selectedTicketId);
            formData.append("message", this.message || "");
            if (this.file) {
                formData.append("file", this.file);
            }

            axios
                .post("/api/tickets/messages", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    const newMessage = {
                        ...response.data,
                        relativeTime: dayjs(response.data.created_at).fromNow(),
                        timeAgo: this.formatTimeAgo(response.data.created_at),
                        timeFormatted: this.formatTime(response.data.created_at),
                        file_url: response.data.id
                            ? `/api/tickets/messages/${response.data.id}/download`
                            : null, // Ensure file URL uses API endpoint
                    };

                    // Find the group for the new message's date
                    const messageDate = dayjs(newMessage.created_at).format("MMMM D, YYYY");
                    const groupIndex = this.messages.findIndex((group) => group.date === messageDate);

                    if (groupIndex !== -1) {
                        // Add the new message to the existing group
                        this.messages[groupIndex].messages.push(newMessage);
                    } else {
                        // Create a new group for the new message's date
                        this.messages.push({
                            date: messageDate,
                            messages: [newMessage],
                        });
                    }

                    // Reset message and file
                    this.message = "";
                    this.file = null;
                    this.scrollToBottom();
                })
                .catch((error) => {
                    console.error("Error sending message:", error);
                });
        },
        scrollToBottom() {
            const chatBody = this.$refs.chatBody;
            if (chatBody) {
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        },
    },
    mounted() {
        this.fetchTickets();

        // Real-time updates for new tickets
        window.Echo.channel('tickets')
            .listen('TicketCreated', (event) => {
                console.log("New Ticket Created:", event.ticket);

                this.tickets.push(event.ticket);

                // Group tickets by status
                this.ticketStatuses = this.tickets.reduce((acc, ticket) => {
                    if (!acc[ticket.status]) acc[ticket.status] = [];
                    acc[ticket.status].push(ticket);
                    return acc;
                }, {});
            });
    },
};
</script>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1050;
}

.modal-dialog {
    background-color: white;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
</style>
