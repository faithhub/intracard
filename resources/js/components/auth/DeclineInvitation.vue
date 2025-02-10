<template>
    <div class="container">
        <div class="card">
            <img alt="Logo" src="@assets/logos/intracard.png" class="logo" />
            <!-- <div class="tagline">Empowering your financial journey</div> -->

            <div v-if="loading" class="message">
                <div class="loading-spinner"></div>
                <p>Processing your request...</p>
            </div>

            <div v-else class="message">
                <div v-if="status === 'success'" class="success-message">

                    <div class="icon-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="11" fill="#f3f4f6" stroke="#6b7280" stroke-width="2" />
                            <path d="M15 9l-6 6M9 9l6 6" stroke="#6b7280" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>

                    <h2 class="title">Invitation Declined</h2>
                    <p class="description">You have successfully declined the invitation. Thank you for letting us know.
                    </p>
                    <p class="info">If you change your mind or received this by mistake, please contact the person who
                        invited you to send a new invitation.</p>

                    <div v-if="status === 'success' && countdown" class="countdown">
                        Redirecting to homepage in {{ countdown }} seconds...
                    </div>
                </div>

                <div v-else-if="status === 'error'" class="error-message">
                    <div class="icon-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="11" fill="#fee2e2" stroke="#ef4444" stroke-width="2" />
                            <path d="M12 8v5m0 3v.01" stroke="#ef4444" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h2 class="title error-title">{{ error }}</h2>
                    <p class="description">Please check your invitation link and try again.</p>
                </div>
            </div>

            <router-link to="/" class="home-button">Return to Homepage</router-link>

            <div class="footer">
                © {{ new Date().getFullYear() }} IntraCard. All rights reserved.
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'DeclineInvitation',
    data() {
        return {
            loading: true,
            status: null,
            error: null,
            token: null,
            countdown: null,  // For auto-redirect countdown
            redirectTimer: null
        }
    },
    created() {
        this.token = this.$route.params.token;
        if (!this.token) {
            this.handleError('Invalid invitation link');
            return;
        }
        this.processDecline();
    },
    methods: {
        async processDecline() {
            try {
                this.loading = true;
                const response = await axios.post(`/api/team/members/decline/${this.token}`);

                if (response.data.status === 'success') {
                    this.status = 'success';
                    this.startRedirectCountdown();
                } else {
                    this.handleError(response.data.message || 'Failed to decline invitation');
                }
            } catch (error) {
                this.handleError(
                    error.response?.data?.message ||
                    'An error occurred while processing your request'
                );
            } finally {
                this.loading = false;
            }
        },
        handleError(message) {
            this.status = 'error';
            this.error = message;
            this.loading = false;
        },
        startRedirectCountdown() {
            this.countdown = 5; // 5 seconds countdown
            this.redirectTimer = setInterval(() => {
                this.countdown--;
                if (this.countdown <= 0) {
                    clearInterval(this.redirectTimer);
                    this.$router.push('/');
                }
            }, 1000);
        }
    },
    beforeDestroy() {
        // Clean up timer if component is destroyed
        if (this.redirectTimer) {
            clearInterval(this.redirectTimer);
        }
    }
}
</script>
<style scoped>
.container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background-color: #f9fafb;
}

.card {
    max-width: 500px;
    width: 100%;
    background-color: white;
    padding: 32px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.logo {
    height: 80px;
    margin-bottom: 8px;
}

.tagline {
    color: #392F75;
    font-family: 'Kodchasan', sans-serif;
    font-size: 16px;
    margin-bottom: 32px;
}

.message {
    margin: 32px 0;
}

.icon-wrapper {
    margin-bottom: 24px;
}

.icon {
    width: 64px;
    height: 64px;
    margin: 0 auto;
}

.title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 16px;
    color: #392F75;
}

.error-title {
    color: #dc2626;
}

.description {
    color: #4b5563;
    margin-bottom: 16px;
    font-size: 16px;
    line-height: 1.5;
}

.info {
    color: #6b7280;
    font-size: 14px;
}

.home-button {
    display: block;
    width: 100%;
    padding: 12px 16px;
    background-color: #392F75;
    color: white;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    margin-top: 32px;
    transition: background-color 0.2s ease;
}

.home-button:hover {
    background-color: #2a2357;
}

.footer {
    color: #9ca3af;
    font-size: 14px;
    margin-top: 32px;
}

.loading-spinner {
    width: 48px;
    height: 48px;
    border: 4px solid #392F75;
    border-top-color: transparent;
    border-radius: 50%;
    margin: 0 auto 16px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

.success-message,
.error-message {
    animation: fade-in 0.3s ease-out;
}

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .card {
        padding: 24px;
    }

    .logo {
        height: 60px;
    }

    .title {
        font-size: 20px;
    }

    .description {
        font-size: 14px;
    }
}
</style>