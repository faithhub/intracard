<template>

    <div class="card" id="kt_chat_messenger">
        <!-- Header -->
        <div class="card-header">
            <div class="card-title">
                <div class="d-flex justify-content-center flex-column me-3">
                    <a href="#" class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">
                        {{ user.first_name }} {{ user.last_name }}
                    </a>
                    <div class="mb-0 lh-1">
                        <span class="badge badge-success badge-circle w-10px h-10px me-1"></span>
                        <span class="fs-7 fw-semibold text-muted">Active</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Body -->
        <div class="card-body">
            <div class="scroll-y me-n5 pe-5 h-300px h-lg-auto" style="max-height: 739px;" ref="chatBody">
                <div v-for="(group, date) in messages" :key="date">
                    <!-- Date Header -->
                    <div class="text-center my-3">
                        <!-- <span class="badge bg-light-primary text-primary">{{ date }}</span> -->
                        <span class="badge bg-light-primary text-primary">{{ group.date }}</span>
                    </div>

                    <!-- Display Messages -->
                    <div v-for="message in group.messages" :key="message.id"
                        :class="message.sender_id === user.id ? 'd-flex justify-content-end mb-10' : 'd-flex justify-content-start mb-10'">
                        <div class="d-flex flex-column align-items-end" v-if="message.sender_id === user.id">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-3">
                                    <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">You</a>
                                </div>
                                <div class="symbol symbol-35px symbol-circle">
                                    <img alt="Pic"
                                        :src="user.avatar || 'https://img.freepik.com/premium-vector/avatar-icon0002_750950-43.jpg?semt=ais_hybrid'" />
                                </div>
                            </div>
                            <div class="p-5 rounded bg-light-info text-gray-900 fw-semibold mw-lg-400px text-end"
                                v-if="message.message">
                                {{ message.message }}
                            </div>
                            <div v-if="message.file_url">
                                <a :href="`/api/messages/${message.id}/download`" target="_blank" class="text-dark">
                                    {{ message.file_name }}
                                </a>
                            </div>
                            <div class="mt-2">
                                <span class="text-muted fs-7">{{ message.timeFormatted }}</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-start" v-else>
                            <div class="d-flex align-items-center mb-2">
                                <div class="symbol symbol-35px symbol-circle">
                                    <img alt="Pic"
                                        :src="message.sender_avatar || 'https://img.freepik.com/premium-vector/avatar-icon0002_750950-43.jpg?semt=ais_hybrid'" />
                                </div>
                                <div class="ms-3">
                                    <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">{{
                                        message.sender_name || 'Admin' }}</a>
                                    <span class="text-muted fs-7 mb-1">{{ message.relativeTime }}</span>
                                </div>
                            </div>
                            <div class="p-5 rounded bg-light-warning text-gray-900 fw-semibold mw-lg-400px text-start"
                                v-if="message.message">
                                {{ message.message }}
                            </div>
                            <div v-if="message.file_url">
                                <a :href="message.file_url" target="_blank" class="text-dark">{{ message.file_name
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

        <!-- Footer -->
        <div class="card-footer pt-4 position-sticky bottom-0 bg-white">
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
                        <input type="file" class="position-absolute top-0 start-0 opacity-0 w-100 h-100 cursor-pointer"
                            @change="onFileChange" />
                    </button>
                </div>
                <button class="btn btn-primary" type="button" @click="sendMessage">Send</button>
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
            receiverId: 2, // Set the initial receiver ID dynamically
            selectedConversationId: null, // Initialize as null or with a default value
            messages: [],
            message: "",
            file: null,
            conversations: [], // If you're listing conversations
        };
    },
    methods: {
        startConversation() {
            return axios.post("/api/conversations")
                .then((response) => {
                    this.currentConversationId = response.data.conversation_id;
                })
                .catch((error) => {
                    console.error("Error starting conversation:", error);
                });
        },
        groupMessagesByDate(messages) {
            return messages.reduce((acc, message) => {
                // Format the date as "MMMM DD, YYYY" (e.g., "December 8, 2024")
                const date = new Date(message.created_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                });

                if (!acc[date]) {
                    acc[date] = [];
                }
                acc[date].push(message);
                console.log('Grouped Messages by Date:', acc);
                return acc;
            }, {});
        },
        fetchMessages(conversationId = null) {
            axios
                .get("/api/messages", {
                    params: {
                        conversation_id: conversationId || this.selectedConversationId,
                    },
                })
                .then((response) => {
                    if (response.data.length === 0) {
                        console.log("No messages found for this conversation.");
                        this.messages = [];
                    } else {
                        const grouped = response.data.reduce((acc, message) => {
                            const messageDate = dayjs(message.created_at).format("MMMM D, YYYY");
                            if (!acc[messageDate]) {
                                acc[messageDate] = [];
                            }
                            acc[messageDate].push({
                                ...message,
                                relativeTime: dayjs(message.created_at).fromNow(),
                                timeAgo: this.formatTimeAgo(message.created_at),
                                timeFormatted: this.formatTime(message.created_at),
                            });
                            return acc;
                        }, {});

                        this.messages = Object.keys(grouped).map((date) => ({
                            date,
                            messages: grouped[date],
                        }));
                    }

                    this.scrollToBottom();
                })
                .catch((error) => {
                    console.error("Error fetching messages:", error);
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
            this.file = event.target.files[0];
        },
        getDefaultConversationId() {
            // Logic to determine the default conversation ID if needed
            return null; // or a specific conversation ID
        },
        sendMessage() {
            if (!this.currentConversationId) {
                // Start a new conversation
                this.startConversation().then(() => {
                    this.performSendMessage();
                });
            } else {
                this.performSendMessage();
            }
        },

        performSendMessage() {
            if (!this.message && !this.file) {
                console.error("Message content or file is required.");
                return;
            }

            const formData = new FormData();
            formData.append("conversation_id", this.currentConversationId);
            formData.append("sender_type", "user");
            formData.append("message", this.message || "");
            if (this.file) {
                formData.append("file", this.file);
            }

            axios
                .post("/api/messages", formData, {
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
                            ? `/api/messages/${response.data.id}/download`
                            : null, // Use the API endpoint
                    };

                    // Ensure consistent date format
                    const messageDate = dayjs(newMessage.created_at).format("MMMM D, YYYY");

                    // Find the group for the new message's date
                    const groupIndex = this.messages.findIndex(
                        (group) => group.date === messageDate
                    );

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

                    // Sort the messages by date for consistency
                    this.messages.sort((a, b) => new Date(a.date) - new Date(b.date));

                    // If it's a new conversation, set the conversation ID
                    if (!this.currentConversationId && response.data.conversation_id) {
                        this.currentConversationId = response.data.conversation_id; // Assume backend includes conversation_id
                    }

                    this.message = "";
                    this.file = null;
                    this.scrollToBottom();
                })
                .catch((error) => {
                    console.error("Error sending message:", error);
                });
        },

        sendMessage566() {

            if (!this.message && !this.file) return;

            const formData = new FormData();
            if (this.conversationId) {
                formData.append("conversation_id", this.conversationId);
            } else {
                formData.append("receiver_id", this.receiverId); // Use the selected receiver ID
            }
            formData.append("message", this.message);
            if (this.file) {
                formData.append("file", this.file);
            }

            axios
                .post("/api/messages", formData, {
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
                        file_url: response.data.id ? `/api/messages/${response.data.id}/download` : null, // Use the API endpoint\
                    };

                    // Ensure consistent date format
                    const messageDate = dayjs(newMessage.created_at).format("MMMM D, YYYY");

                    // Find the group for the new message's date
                    const groupIndex = this.messages.findIndex(
                        (group) => group.date === messageDate
                    );

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


                    // If it's a new conversation, set the conversation ID
                    if (!this.conversationId) {
                        this.conversationId = response.data.conversation_id; // Assume backend includes conversation_id
                    }

                    this.message = "";
                    this.file = null;
                    this.scrollToBottom();
                })
                .catch((error) => {
                    console.error("Error sending message:", error);
                });
        },
        // scrollToBottom() {
        //     const chatBody = this.$refs.chatBody;
        //     chatBody.scrollTop = chatBody.scrollHeight;
        // },
        scrollToBottom() {
            const chatBody = this.$refs.chatBody;
            if (chatBody) {
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        }
    },

    mounted() {
        this.selectedConversationId = this.getDefaultConversationId();
        this.fetchMessages();
        // Real-time updates
        window.Echo.channel(`chat.${this.user.id}`)
            .listen("MessageCreated", (event) => {
                console.log('New Message Received:', event);

                this.messages.push(event.message);
                this.scrollToBottom();
            });
    },
};
</script>


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
    /* Smaller font size for details */
    color: #6c757d;
    margin-top: 2px;
}
</style>
