@extends('admin.app-admin')
@section('content')
    <style>
        textarea.form-control {
            resize: none;
            border-radius: 0.5rem;
        }

        .card-footer {
            border-top: 1px solid #eee;
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
        }

        .file-input-wrapper input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .form-control.form-control-flush {
            border: 0;
            background-color: #eee4e48f !important;
            outline: 0 !important;
            box-shadow: none;
            border-radius: 5px;
        }

        #file-preview-container img {
            max-width: 50px !important;
            max-height: 50px !important;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 5px;
        }

        #file-preview-container span {
            font-size: 12px;
            color: #6c757d;
            margin-top: 2px;
        }

        .unread-badge {
            background-color: #009ef7;
            color: white;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 10px;
        }

        .ticket-item {
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .ticket-item:hover {
            background-color: #f8f8f8;
        }

        .ticket-item.active {
            background-color: #f1f1f1;
        }

        .status-badge {
            text-transform: capitalize;
        }

        .status-pending {
            background-color: #ff9800;
        }

        .status-resolved {
            background-color: #4caf50;
        }

        .status-unresolved {
            background-color: #f44336;
        }
    </style>

    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    Help & Support
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->

                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <div class="d-flex">
                        <select id="status-filter" class="form-select form-select-sm me-2">
                            <option value="">All Tickets</option>
                            <option value="pending">Pending</option>
                            <option value="resolved">Resolved</option>
                            <option value="unresolved">Unresolved</option>
                        </select>
                        <button type="button" class="btn btn-sm btn-light-primary" id="refresh-btn">
                            <i class="fa fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>
                <!--end::Card toolbar-->
            </div>

            <div class="d-flex flex-column flex-lg-row">
                <!--begin::Sidebar-->
                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0"
                    style="margin-left: 2rem;">
                    <!--begin::Contacts-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header pt-7" id="kt_chat_contacts_header">
                            <!--begin::Form-->
                            <form class="w-100 position-relative" autocomplete="off">
                                <!--begin::Icon-->
                                <i
                                    class="fa fa-search fs-3 text-gray-500 position-absolute top-50 ms-5 translate-middle-y"></i>
                                <!--end::Icon-->

                                <!--begin::Input-->
                                <input type="text" id="search-input" class="form-control form-control-solid px-13"
                                    name="search" value="" placeholder="Search by username or email...">
                                <!--end::Input-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body pt-5" id="kt_chat_contacts_body">
                            <!--begin::List-->
                            <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto" data-kt-scroll="true"
                                data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                                data-kt-scroll-dependencies="#kt_header, #kt_app_header, #kt_toolbar, #kt_app_toolbar, #kt_footer, #kt_app_footer, #kt_chat_contacts_header"
                                data-kt-scroll-wrappers="#kt_content, #kt_app_content, #kt_chat_contacts_body"
                                data-kt-scroll-offset="5px" style="max-height: 684px;" id="ticket-list">
                                <!-- Tickets will be loaded here dynamically via JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <!--begin::Content-->
                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card" id="kt_chat_messenger">
                        <div class="card-header" id="kt_chat_messenger_header">
                            <div class="card-title">
                                <div class="d-flex justify-content-center flex-column me-3">
                                    <a href="#" class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1"
                                        id="current-ticket-title">
                                        Select a ticket
                                    </a>
                                    <div class="mb-0 lh-1">
                                        <span class="badge badge-primary badge-circle w-10px h-10px me-1"
                                            id="current-ticket-status-indicator"></span>
                                        <span class="fs-7 fw-semibold text-muted" id="current-ticket-status">No ticket
                                            selected</span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-toolbar">
                                <div class="dropdown" id="ticket-actions-dropdown" style="display: none;">
                                    <button class="btn btn-sm btn-icon btn-active-light-primary" type="button"
                                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li><a class="dropdown-item" href="#" id="btn-mark-resolved">Mark as
                                                Resolved</a></li>
                                        <li><a class="dropdown-item" href="#" id="btn-mark-unresolved">Mark as
                                                Unresolved</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="#" id="btn-close-ticket"
                                                data-bs-toggle="modal" data-bs-target="#closeTicketModal">Close Ticket</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body" id="kt_chat_messenger_body">
                            <!--begin::Messages-->
                            <div class="scroll-y me-n5 pe-5 h-300px h-lg-auto" data-kt-element="messages"
                                data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                                data-kt-scroll-max-height="auto"
                                data-kt-scroll-dependencies="#kt_header, #kt_app_header, #kt_app_toolbar, #kt_toolbar, #kt_footer, #kt_app_footer, #kt_chat_messenger_header, #kt_chat_messenger_footer"
                                data-kt-scroll-wrappers="#kt_content, #kt_app_content, #kt_chat_messenger_body"
                                data-kt-scroll-offset="5px" style="max-height: 539px;" id="message-container">

                                <!-- Initial empty state -->
                                <div class="d-flex flex-column align-items-center justify-content-center h-100"
                                    id="empty-state">
                                    <div class="text-center">
                                        <i class="fa fa-comments fs-3x text-muted mb-3"></i>
                                        <h3 class="fs-4 text-gray-600">Select a ticket to view messages</h3>
                                    </div>
                                </div>

                                <!-- Messages will be loaded here dynamically via JavaScript -->
                            </div>
                            <!--end::Messages-->
                        </div>
                        <!--end::Card body-->

                        <!--begin::Card footer-->
                        <div class="card-footer pt-4 position-sticky bottom-0 bg-white" id="kt_chat_messenger_footer">
                            <form id="message-form" style="display: none;">
                                <!--begin::File Preview-->
                                <div id="file-preview-container" class="mb-3"></div>
                                <!--end::File Preview-->

                                <!--begin::Input-->
                                <textarea class="form-control form-control-flush mb-3" rows="2" id="chat-input" placeholder="Type a message"></textarea>
                                <!--end::Input-->

                                <!--begin:Toolbar-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Actions-->
                                    <div class="d-flex align-items-center me-2">
                                        <button class="btn btn-light me-2 position-relative" type="button">
                                            <i class="fa fa-paperclip fs-3"></i>
                                            <input type="file"
                                                class="position-absolute top-0 start-0 opacity-0 w-100 h-100 cursor-pointer"
                                                id="file-input" />
                                        </button>
                                    </div>
                                    <!--end::Actions-->

                                    <!--begin::Send-->
                                    <button class="btn btn-primary" type="submit" id="send-button">Send</button>
                                    <!--end::Send-->
                                </div>
                                <!--end::Toolbar-->
                            </form>
                        </div>
                        <!--end::Card footer-->
                    </div>
                </div>
                <!--end::Content-->
            </div>
        </div>
    </div>

    <!-- Close Ticket Modal -->
    <div class="modal fade" id="closeTicketModal" tabindex="-1" aria-labelledby="closeTicketModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="closeTicketModalLabel">Close Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="close-ticket-form">
                        <div class="mb-3">
                            <label for="resolution-status" class="form-label">Resolution Status</label>
                            <select class="form-select" id="resolution-status" required>
                                <option value="resolved">Resolved</option>
                                <option value="unresolved">Unresolved</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="resolution-reason" class="form-label">Resolution Reason</label>
                            <textarea class="form-control" id="resolution-reason" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submit-close-ticket">Close Ticket</button>
                </div>
            </div>
        </div>
    </div>

    {{-- @section('scripts') --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables
            let currentTicketUuid = null;
            let tickets = [];
            let messages = [];
            let currentChannel = null;

            // DOM elements
            const ticketList = document.getElementById('ticket-list');
            const messageContainer = document.getElementById('message-container');
            const emptyState = document.getElementById('empty-state');
            const messageForm = document.getElementById('message-form');
            const fileInput = document.getElementById('file-input');
            const filePreviewContainer = document.getElementById('file-preview-container');
            const chatInput = document.getElementById('chat-input');
            const statusFilter = document.getElementById('status-filter');
            const searchInput = document.getElementById('search-input');
            const refreshBtn = document.getElementById('refresh-btn');
            const ticketActionsDropdown = document.getElementById('ticket-actions-dropdown');
            const btnMarkResolved = document.getElementById('btn-mark-resolved');
            const btnMarkUnresolved = document.getElementById('btn-mark-unresolved');
            const btnCloseTicket = document.getElementById('btn-close-ticket');
            const submitCloseTicket = document.getElementById('submit-close-ticket');
            const closeTicketForm = document.getElementById('close-ticket-form');

            // Initial load
            loadTickets();

            // Event listeners
            refreshBtn.addEventListener('click', loadTickets);
            statusFilter.addEventListener('change', loadTickets);
            searchInput.addEventListener('input', debounce(loadTickets, 500));

            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                sendMessage();
            });

            fileInput.addEventListener('change', handleFileSelect);

            btnMarkResolved.addEventListener('click', function() {
                updateTicketStatus('resolved');
            });

            btnMarkUnresolved.addEventListener('click', function() {
                updateTicketStatus('unresolved');
            });

            submitCloseTicket.addEventListener('click', closeTicket);

            // Setup Echo broadcasting for ticket events (if Echo is available)
            if (typeof Echo !== 'undefined') {
                // Listen for new tickets
                Echo.private('tickets')
                    .listen('TicketCreated', (e) => {
                        // Reload tickets when a new ticket is created
                        loadTickets();
                        showToast('New ticket created', 'info');
                    });
            }

            // Subscribe to ticket channel for real-time messages
            function subscribeToTicketChannel(uuid) {
                // Only proceed if Echo is available
                if (typeof Echo === 'undefined') return;

                // Unsubscribe from previous channel if any
                if (currentChannel) {
                    Echo.leave(currentChannel);
                }

                // Subscribe to the new ticket channel
                currentChannel = `private-ticket.${uuid}`;

                Echo.private(`ticket.${uuid}`)
                    .listen('MessageCreated', (e) => {
                        // Only process if it's a message for this ticket
                        if (currentTicketUuid === e.ticket_uuid || currentTicketUuid === uuid) {
                            // Don't add message if it's from the current admin (to avoid duplicates)
                            if (e.sender_id !== {{ Auth::guard('admin')->id() }}) {
                                // Add new message to the messages array
                                messages.push({
                                    id: e.id,
                                    ticket_id: e.ticket_id,
                                    sender_id: e.sender_id,
                                    message: e.message,
                                    file_path: e.file_path,
                                    file_name: e.file_name,
                                    file_url: e.file_url,
                                    has_file: e.has_file,
                                    created_at: e.created_at,
                                    sender: e.sender
                                });

                                // Re-render messages
                                renderMessages();

                                // Mark as read automatically since we're viewing it
                                markMessagesAsRead([e.id]);
                            }
                        } else {
                            // If it's for another ticket, update unread counts
                            loadUnreadCounts();
                        }
                    })
                    .listen('TicketUpdated', (e) => {
                        // Update ticket status in UI if it's the current ticket
                        if (e.uuid === currentTicketUuid) {
                            document.getElementById('current-ticket-status').textContent = e.status;

                            // Update status indicator
                            const statusIndicator = document.getElementById('current-ticket-status-indicator');
                            statusIndicator.className = 'badge badge-circle w-10px h-10px me-1';

                            if (e.status === 'pending') {
                                statusIndicator.classList.add('badge-warning');
                            } else if (e.status === 'resolved') {
                                statusIndicator.classList.add('badge-success');
                            } else {
                                statusIndicator.classList.add('badge-danger');
                            }
                        }

                        // Refresh tickets list to update status
                        loadTickets();
                    });
            }

            // Mark specific messages as read
            function markMessagesAsRead(messageIds) {
                if (!messageIds || messageIds.length === 0 || !currentTicketUuid) return;

                fetch('/admin/api/messages/mark-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        ticket_uuid: currentTicketUuid,
                        message_ids: messageIds
                    })
                }).catch(error => {
                    console.error('Error marking messages as read:', error);
                });
            }

            // Instead of polling, we'll still keep a fallback for non-realtime environments
            // but with a longer interval
            setInterval(function() {
                if (currentTicketUuid && typeof Echo === 'undefined') {
                    // Only use polling if Echo is not available
                    loadMessages(currentTicketUuid, false);
                    loadUnreadCounts();
                } else if (!currentTicketUuid) {
                    // Always refresh unread counts periodically
                    loadUnreadCounts();
                }
            }, 60000); // Every 60 seconds instead of 30 for less server load

            // Functions
            function loadTickets() {
                const status = statusFilter.value;
                const search = searchInput.value;

                fetch(`/admin/api/tickets?status=${status}&search=${search}`)
                    .then(response => response.json())
                    .then(data => {
                        tickets = data.data;
                        renderTickets();
                        loadUnreadCounts();
                    })
                    .catch(error => {
                        console.error('Error loading tickets:', error);
                        showToast('Error loading tickets. Please try again.', 'danger');
                    });
            }

            function renderTickets() {
                ticketList.innerHTML = '';

                if (tickets.length === 0) {
                    ticketList.innerHTML = `
                <div class="text-center py-5">
                    <p class="text-muted">No tickets found</p>
                </div>
            `;
                    return;
                }

                tickets.forEach(ticket => {
                    const timeAgo = getTimeAgo(new Date(ticket.created_at));
                    const statusClass = `status-${ticket.status}`;

                    // Create a full name from first_name and last_name
                    const creatorName = ticket.creator ?
                        `${ticket.creator.first_name || ''} ${ticket.creator.last_name || ''}`.trim() :
                        'Unknown';

                    // Get first letter for the avatar
                    const creatorInitial = creatorName.charAt(0);

                    const ticketElement = document.createElement('div');
                    ticketElement.classList.add('d-flex', 'flex-stack', 'py-4', 'ticket-item');
                    ticketElement.dataset.uuid = ticket.uuid;

                    if (currentTicketUuid === ticket.uuid) {
                        ticketElement.classList.add('active');
                    }

                    ticketElement.innerHTML = `
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-45px symbol-circle">
                        <span class="symbol-label bg-light-primary text-primary fs-6 fw-bolder">
                            ${creatorInitial || 'U'}
                        </span>
                    </div>
                    <div class="ms-5">
                        <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">${ticket.subject}</a>
                        <div class="fw-semibold text-muted">${ticket.creator?.email || 'Unknown'}</div>
                    </div>
                </div>
                <div class="d-flex flex-column align-items-end ms-2">
                    <span class="text-muted fs-7 mb-1">${timeAgo}</span>
                    <div>
                        <span class="badge ${statusClass} status-badge">${ticket.status}</span>
                        ${ticket.unread_count > 0 ? `<span class="unread-badge ms-1">${ticket.unread_count}</span>` : ''}
                    </div>
                </div>
            `;

                    ticketElement.addEventListener('click', () => selectTicket(ticket.uuid));
                    ticketList.appendChild(ticketElement);
                });
            }

            function selectTicket(uuid) {
                currentTicketUuid = uuid;

                // Subscribe to real-time updates for this ticket
                subscribeToTicketChannel(uuid);

                // Update UI
                document.querySelectorAll('.ticket-item').forEach(el => {
                    el.classList.remove('active');
                });

                const selectedTicket = document.querySelector(`.ticket-item[data-uuid="${uuid}"]`);
                if (selectedTicket) {
                    selectedTicket.classList.add('active');
                }

                // Show message form and actions
                messageForm.style.display = 'block';
                ticketActionsDropdown.style.display = 'block';

                // Update ticket details in header
                const ticket = tickets.find(t => t.uuid === uuid);
                if (ticket) {
                    document.getElementById('current-ticket-title').textContent = ticket.subject;
                    document.getElementById('current-ticket-status').textContent = ticket.status;

                    // Update status indicator
                    const statusIndicator = document.getElementById('current-ticket-status-indicator');
                    statusIndicator.className = 'badge badge-circle w-10px h-10px me-1';

                    if (ticket.status === 'pending') {
                        statusIndicator.classList.add('badge-warning');
                    } else if (ticket.status === 'resolved') {
                        statusIndicator.classList.add('badge-success');
                    } else {
                        statusIndicator.classList.add('badge-danger');
                    }
                }

                // Load messages
                loadMessages(uuid);
            }

            function loadMessages(uuid, scrollToBottom = true) {
                // Show loading state
                messageContainer.innerHTML =
                    '<div class="d-flex justify-content-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';

                fetch(`/admin/api/tickets/${uuid}/messages`)
                    .then(response => response.json())
                    .then(data => {
                        messages = data;
                        renderMessages(scrollToBottom);

                        // Update ticket in list (to reset unread count)
                        loadUnreadCounts();

                        // Hide empty state
                        emptyState.style.display = 'none';
                    })
                    .catch(error => {
                        console.error('Error loading messages:', error);
                        showToast('Error loading messages. Please try again.', 'danger');
                    });
            }

            function renderMessages(scrollToBottom = true) {
                messageContainer.innerHTML = '';

                if (messages.length === 0) {
                    messageContainer.innerHTML = `
                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                    <div class="text-center">
                        <i class="fa fa-comments fs-3x text-muted mb-3"></i>
                        <h3 class="fs-4 text-gray-600">No messages yet</h3>
                        <p class="text-muted">Be the first to send a message</p>
                    </div>
                </div>
            `;
                    return;
                }

                // Group messages by date
                const groupedMessages = {};
                messages.forEach(message => {
                    const date = new Date(message.created_at).toLocaleDateString();
                    if (!groupedMessages[date]) {
                        groupedMessages[date] = [];
                    }
                    groupedMessages[date].push(message);
                });

                // Render each group
                Object.keys(groupedMessages).forEach(date => {
                    // Add date separator
                    const dateElement = document.createElement('div');
                    dateElement.classList.add('d-flex', 'flex-column', 'align-items-center', 'mb-5');
                    dateElement.innerHTML = `
                <span class="text-muted fs-7 fw-semibold py-1 px-3 bg-light rounded">${formatDate(date)}</span>
            `;
                    messageContainer.appendChild(dateElement);

                    // Render messages for this date
                    groupedMessages[date].forEach(message => {
                        // const isAdmin = message.sender.id === {{ Auth::guard('admin')->id() }};
                        // Check if the message is from an admin using sender_type 
                        const isAdmin = message.sender_type === 'admin';
                        const messageElement = document.createElement('div');

                        // Get sender's full name from first_name and last_name
                        const senderFullName = isAdmin ? 'You' :
                            (message.sender ?
                                `${message.sender.first_name || ''} ${message.sender.last_name || ''}`
                                .trim() : 'Unknown');

                        // Get sender's initial
                        const senderInitial = isAdmin ? 'A' :
                            (message.sender && message.sender.first_name ? message.sender.first_name
                                .charAt(0) :
                                (message.sender && message.sender.last_name ? message.sender
                                    .last_name.charAt(0) : 'U'));


                        if (isAdmin) {
                            // Outgoing message (from admin)
                            messageElement.classList.add('d-flex', 'justify-content-end', 'mb-10');
                            messageElement.innerHTML = `
                        <div class="d-flex flex-column align-items-end">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-3">
                                    <span class="text-muted fs-7 mb-1">${formatTime(new Date(message.created_at))}</span>
                                    <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">You</a>
                                </div>
                                <div class="symbol symbol-35px symbol-circle">
                                    <img alt="Pic" src="{{ Auth::guard('admin')->user()->avatar ?? 'https://img.freepik.com/premium-vector/avatar-icon0002_750950-43.jpg?semt=ais_hybrid' }}">
                                </div>
                            </div>
                            <div class="p-5 rounded bg-light-primary text-dark fw-semibold mw-lg-400px text-end" data-kt-element="message-text">
                                ${message.message ? escapeHtml(message.message) : ''}
                                ${renderFileAttachment(message)}
                            </div>
                        </div>
                    `;
                        } else {
                            // Incoming message (from user)
                            messageElement.classList.add('d-flex', 'justify-content-start',
                            'mb-10');
                            messageElement.innerHTML = `
                        <div class="d-flex flex-column align-items-start">
                            <div class="d-flex align-items-center mb-2">
                                <div class="symbol symbol-35px symbol-circle">
                                    <span class="symbol-label bg-light-info text-info fs-6 fw-bolder">${senderInitial}</span>
                                </div>
                                <div class="ms-3">
                                    <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">${senderFullName}</a>
                                    <span class="text-muted fs-7 mb-1">${formatTime(new Date(message.created_at))}</span>
                                </div>
                            </div>
                            <div class="p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start" data-kt-element="message-text">
                                ${message.message ? escapeHtml(message.message) : ''}
                                ${renderFileAttachment(message)}
                            </div>
                        </div>
                    `;
                        }

                        messageContainer.appendChild(messageElement);
                    });
                });

                // Scroll to bottom if needed
                if (scrollToBottom) {
                    messageContainer.scrollTop = messageContainer.scrollHeight;
                }
            }

            function renderFileAttachment(message) {
                if (!message.has_file || !message.file_name) return '';

                const extension = message.file_name.split('.').pop().toLowerCase();
                const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension);

                if (isImage) {
                    return `
                <div class="mt-2">
                    <a href="/admin/api/messages/${message.id}/download" target="_blank" class="d-block">
                        <img src="/admin/api/messages/${message.id}/download" alt="Attachment" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                    </a>
                    <div class="d-flex align-items-center mt-2">
                        <i class="fa fa-paperclip text-muted me-2"></i>
                        <a href="/admin/api/messages/${message.id}/download" class="text-primary fs-7">${message.file_name}</a>
                    </div>
                </div>
            `;
                } else {
                    let fileIcon;
                    if (['pdf'].includes(extension)) {
                        fileIcon = 'fa-file-pdf';
                    } else if (['doc', 'docx'].includes(extension)) {
                        fileIcon = 'fa-file-word';
                    } else if (['xls', 'xlsx'].includes(extension)) {
                        fileIcon = 'fa-file-excel';
                    } else if (['zip', 'rar', '7z'].includes(extension)) {
                        fileIcon = 'fa-file-archive';
                    } else {
                        fileIcon = 'fa-file';
                    }

                    return `
                <div class="mt-2">
                    <div class="d-flex align-items-center mt-2">
                        <i class="fa ${fileIcon} text-muted me-2"></i>
                        <a href="/admin/api/messages/${message.id}/download" class="text-primary fs-7">${message.file_name}</a>
                    </div>
                </div>
            `;
                }
            }

            function sendMessage() {
                if (!currentTicketUuid) return;

                const message = chatInput.value.trim();
                const file = fileInput.files[0];

                if (!message && !file) {
                    showToast('Please enter a message or attach a file', 'warning');
                    return;
                }

                // Disable form while sending
                const sendButton = document.getElementById('send-button');
                sendButton.disabled = true;
                sendButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';

                // Create form data
                const formData = new FormData();
                if (message) formData.append('message', message);
                if (file) formData.append('file', file);

                // Send API request
                fetch(`/admin/api/tickets/${currentTicketUuid}/messages`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Clear input and file
                        chatInput.value = '';
                        fileInput.value = '';
                        filePreviewContainer.innerHTML = '';

                        // Add new message to the list and render
                        // With broadcasting, we might get the message twice, but that's ok
                        // because we identify messages by ID and won't show duplicates
                        messages.push({
                            ...data.message,
                            sender: data.sender
                        });
                        renderMessages();

                        // Re-enable form
                        sendButton.disabled = false;
                        sendButton.textContent = 'Send';
                    })
                    .catch(error => {
                        console.error('Error sending message:', error);
                        showToast('Error sending message. Please try again.', 'danger');

                        // Re-enable form
                        sendButton.disabled = false;
                        sendButton.textContent = 'Send';
                    });
            }

            function handleFileSelect(e) {
                const file = e.target.files[0];
                if (!file) return;

                // Check file size (10MB max)
                if (file.size > 10 * 1024 * 1024) {
                    showToast('File is too large. Maximum size is 10MB.', 'warning');
                    fileInput.value = '';
                    return;
                }

                // Preview file
                filePreviewContainer.innerHTML = '';

                const filePreview = document.createElement('div');
                filePreview.classList.add('d-flex', 'align-items-center', 'p-3', 'bg-light', 'rounded');

                const extension = file.name.split('.').pop().toLowerCase();
                const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension);

                if (isImage) {
                    const img = document.createElement('img');
                    img.classList.add('me-3');
                    img.style.width = '40px';
                    img.style.height = '40px';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '4px';

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                    filePreview.appendChild(img);
                } else {
                    let fileIcon;
                    if (['pdf'].includes(extension)) {
                        fileIcon = 'fa-file-pdf';
                    } else if (['doc', 'docx'].includes(extension)) {
                        fileIcon = 'fa-file-word';
                    } else if (['xls', 'xlsx'].includes(extension)) {
                        fileIcon = 'fa-file-excel';
                    } else if (['zip', 'rar', '7z'].includes(extension)) {
                        fileIcon = 'fa-file-archive';
                    } else {
                        fileIcon = 'fa-file';
                    }

                    const icon = document.createElement('i');
                    icon.classList.add('fa', fileIcon, 'fs-2x', 'me-3', 'text-primary');
                    filePreview.appendChild(icon);
                }

                const fileInfo = document.createElement('div');
                fileInfo.classList.add('d-flex', 'flex-column');

                const fileName = document.createElement('span');
                fileName.classList.add('fw-bold', 'text-gray-800');
                fileName.textContent = file.name;

                const fileSize = document.createElement('span');
                fileSize.classList.add('fs-7', 'text-muted');
                fileSize.textContent = formatFileSize(file.size);

                fileInfo.appendChild(fileName);
                fileInfo.appendChild(fileSize);

                const removeBtn = document.createElement('button');
                removeBtn.classList.add('btn', 'btn-icon', 'btn-sm', 'btn-light-danger', 'ms-auto');
                removeBtn.innerHTML = '<i class="fa fa-times"></i>';
                removeBtn.addEventListener('click', function() {
                    fileInput.value = '';
                    filePreviewContainer.innerHTML = '';
                });

                filePreview.appendChild(fileInfo);
                filePreview.appendChild(removeBtn);

                filePreviewContainer.appendChild(filePreview);
            }

            function updateTicketStatus(status) {
                if (!currentTicketUuid) return;

                // Show loading state
                const ticketStatus = document.getElementById('current-ticket-status');
                const originalText = ticketStatus.textContent;
                ticketStatus.innerHTML =
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...`;

                fetch(`/admin/api/tickets/${currentTicketUuid}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Update status in UI
                        ticketStatus.textContent = status;

                        // Update status indicator
                        const statusIndicator = document.getElementById('current-ticket-status-indicator');
                        statusIndicator.className = 'badge badge-circle w-10px h-10px me-1';

                        if (status === 'pending') {
                            statusIndicator.classList.add('badge-warning');
                        } else if (status === 'resolved') {
                            statusIndicator.classList.add('badge-success');
                        } else {
                            statusIndicator.classList.add('badge-danger');
                        }

                        // Update ticket in list
                        loadTickets();

                        showToast(`Ticket marked as ${status}`, 'success');
                    })
                    .catch(error => {
                        console.error('Error updating ticket status:', error);
                        ticketStatus.textContent = originalText;
                        showToast('Error updating ticket status. Please try again.', 'danger');
                    });
            }

            function closeTicket() {
                if (!currentTicketUuid) return;

                const resolutionStatus = document.getElementById('resolution-status').value;
                const reason = document.getElementById('resolution-reason').value.trim();

                if (!reason) {
                    showToast('Please provide a resolution reason', 'warning');
                    return;
                }

                // Show loading state
                const closeButton = document.getElementById('submit-close-ticket');
                closeButton.disabled = true;
                closeButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Closing...';

                fetch(`/admin/api/tickets/${currentTicketUuid}/close`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            resolution_status: resolutionStatus,
                            reason: reason
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('closeTicketModal'));
                        modal.hide();

                        // Reset form
                        document.getElementById('close-ticket-form').reset();

                        // Update ticket in list
                        loadTickets();

                        // Update status in UI
                        document.getElementById('current-ticket-status').textContent = resolutionStatus;

                        // Update status indicator
                        const statusIndicator = document.getElementById('current-ticket-status-indicator');
                        statusIndicator.className = 'badge badge-circle w-10px h-10px me-1';

                        if (resolutionStatus === 'resolved') {
                            statusIndicator.classList.add('badge-success');
                        } else {
                            statusIndicator.classList.add('badge-danger');
                        }

                        showToast('Ticket closed successfully', 'success');

                        // Re-enable button
                        closeButton.disabled = false;
                        closeButton.textContent = 'Close Ticket';
                    })
                    .catch(error => {
                        console.error('Error closing ticket:', error);
                        showToast('Error closing ticket. Please try again.', 'danger');

                        // Re-enable button
                        closeButton.disabled = false;
                        closeButton.textContent = 'Close Ticket';
                    });
            }

            function loadUnreadCounts() {
                fetch('/admin/api/unread-messages')
                    .then(response => response.json())
                    .then(data => {
                        // Update unread count for each ticket in the list
                        document.querySelectorAll('.ticket-item').forEach(el => {
                            const uuid = el.dataset.uuid;
                            const badgeContainer = el.querySelector(
                                '.d-flex.flex-column.align-items-end .d-flex');

                            if (badgeContainer) {
                                // Remove existing badge if any
                                const existingBadge = badgeContainer.querySelector('.unread-badge');
                                if (existingBadge) {
                                    existingBadge.remove();
                                }

                                // Add new badge if needed
                                if (data.tickets[uuid] && data.tickets[uuid] > 0) {
                                    const badge = document.createElement('span');
                                    badge.classList.add('unread-badge', 'ms-1');
                                    badge.textContent = data.tickets[uuid];
                                    badgeContainer.appendChild(badge);
                                }
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error loading unread counts:', error);
                    });
            }

            // When page is closed/refreshed, leave the channel
            window.addEventListener('beforeunload', function() {
                if (currentChannel && typeof Echo !== 'undefined') {
                    Echo.leave(currentChannel);
                }
            });

            // Helper functions
            function formatDate(dateString) {
                const date = new Date(dateString);
                const today = new Date();
                const yesterday = new Date(today);
                yesterday.setDate(yesterday.getDate() - 1);

                if (date.toDateString() === today.toDateString()) {
                    return 'Today';
                } else if (date.toDateString() === yesterday.toDateString()) {
                    return 'Yesterday';
                } else {
                    return date.toLocaleDateString('en-US', {
                        weekday: 'long',
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });
                }
            }

            function formatTime(date) {
                return date.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            }

            function getTimeAgo(date) {
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);

                if (diffInSeconds < 60) {
                    return 'Just now';
                }

                const diffInMinutes = Math.floor(diffInSeconds / 60);
                if (diffInMinutes < 60) {
                    return `${diffInMinutes} min${diffInMinutes > 1 ? 's' : ''}`;
                }

                const diffInHours = Math.floor(diffInMinutes / 60);
                if (diffInHours < 24) {
                    return `${diffInHours} hour${diffInHours > 1 ? 's' : ''}`;
                }

                const diffInDays = Math.floor(diffInHours / 24);
                if (diffInDays < 7) {
                    return `${diffInDays} day${diffInDays > 1 ? 's' : ''}`;
                }

                const diffInWeeks = Math.floor(diffInDays / 7);
                if (diffInWeeks < 4) {
                    return `${diffInWeeks} week${diffInWeeks > 1 ? 's' : ''}`;
                }

                const diffInMonths = Math.floor(diffInDays / 30);
                if (diffInMonths < 12) {
                    return `${diffInMonths} month${diffInMonths > 1 ? 's' : ''}`;
                }

                const diffInYears = Math.floor(diffInDays / 365);
                return `${diffInYears} year${diffInYears > 1 ? 's' : ''}`;
            }

            function formatFileSize(bytes) {
                if (bytes < 1024) {
                    return bytes + ' bytes';
                } else if (bytes < 1024 * 1024) {
                    return (bytes / 1024).toFixed(1) + ' KB';
                } else {
                    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
                }
            }

            function escapeHtml(html) {
                if (!html) return '';

                const div = document.createElement('div');
                div.textContent = html;

                // Convert newlines to <br> for proper display
                return div.innerHTML.replace(/\n/g, '<br>');
            }

            function showToast(message, type = 'success') {
                // Match existing toastr implementation with direct method calls
                if (type === 'success') {
                    toastr.success(message, 'Success');
                } else if (type === 'error' || type === 'danger') {
                    toastr.error(message, 'Error');
                } else if (type === 'warning') {
                    toastr.warning(message, 'Warning');
                } else if (type === 'info') {
                    toastr.info(message, 'Information');
                }
            }

            // Debounce function for search input
            function debounce(func, wait) {
                let timeout;
                return function(...args) {
                    const context = this;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }
        });
    </script>
    {{-- @endsection --}}
@endsection
