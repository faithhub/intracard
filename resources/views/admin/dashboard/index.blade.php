@extends('admin.app-admin')

@section('content')
    <style>
        .bg-light-primary {
            background-color: #a000f93d !important;
        }

        .bg-success {
            background-color: #89ea748f !important;
        }

        .bg-info {
            background-color: #cacbdc66 !important;
        }

        .table .thead-dark th {
            color: #fff;
            background-color: #212529;
            border-color: #32383e;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table td,
        .table th {
            padding: 0.9rem !important;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        .header-dark {
        background-color: #dcaddc !important;
        color: white !important;
    }
    .activity-chip {
        text-transform: uppercase;
        height: 24px;
        font-size: 0.75rem;
    }
    .status-chip {
        border-radius: 16px;
        height: 24px;
        font-size: 0.75rem;
    }
    .status-active {
        background-color: #e7d9f9 !important;
        color: #6200ea !important;
    }
    .status-completed {
        background-color: #d7f9db !important;
        color: #1b5e20 !important;
    }
    .chart-container {
        min-height: 400px;
        width: 100%;
        padding: 0;
    }
    .card-body.chart-container {
        padding: 0;
    }
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: none;
        padding: 1.25rem;
    }
    .custom-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1),
            0 1px 3px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .custom-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.15),
            0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .v-data-table td {
        padding: 12px 16px !important;
    }
    .v-data-table th {
        padding: 12px 16px !important;
        font-weight: 500 !important;
    }
    </style>
    <style>
        .custom-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1),
                0 1px 3px rgba(0, 0, 0, 0.08);
            /* Subtle shadow for a modern look */
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .custom-card:hover {
            transform: translateY(-5px);
            /* Adds a slight lift effect */
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.15),
                0 4px 6px rgba(0, 0, 0, 0.1);
            /* Enhanced shadow on hover */
        }

        .card-body {
            background-color: #ffffff;
            /* Ensure a clean white background */
            padding: 1.5rem;
            /* Adds consistent padding */
            border-radius: 10px;
            /* Matches the card radius */
        }

        .card-body h6 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: #6c757d;
            /* Text-muted color */
        }

        .card-body h3 {
            font-size: 2rem;
            color: #343a40;
            /* Darker text for emphasis */
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        .bg-light-primary {
            background-color: #a000f93d !important;
        }

        .bg-success {
            background-color: #89ea748f !important;
        }

        .bg-info {
            background-color: #cacbdc66 !important;
        }

        .table .thead-dark th {
            color: #fff;
            background-color: #212529;
            border-color: #32383e;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table td,
        .table th {
            padding: 0.9rem !important;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .custom-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1),
                0 1px 3px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.15),
                0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            background-color: #ffffff;
            padding: 1.5rem;
            border-radius: 10px;
        }

        .card-body h6 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: #6c757d;
        }

        .card-body h3 {
            font-size: 2rem;
            color: #343a40;
        }

        #app-dashboard {
            font-family: 'Roboto', sans-serif;
        }

        .chart-container {
            min-height: 350px;
        }

        .custom-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1),
                0 1px 3px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.15),
                0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .chart-container {
            min-height: min-content;
            width: 100%;
            padding: 0;
        }

        #app-dashboard {
            font-family: 'Roboto', sans-serif;
        }

        .v-application--wrap {
            min-height: unset !important;
        }
        .quick-links {
        margin: 0;
        padding: 0;
    }
    
    .quick-link-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        border-radius: 8px;
        color: #6c757d !important;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .quick-link-item:hover {
        background-color: #f8f9fa;
        color: purple !important;
        transform: translateX(5px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .quick-link-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin-right: 12px;
        transition: all 0.3s ease;
    }
    
    .quick-link-item:hover .quick-link-icon {
        background-color: #a000f93b !important;
        color: black !important;
    }
    
    .quick-link-text {
        font-weight: 500;
        font-size: 1rem;
    }
    @media screen and (max-width: 600px) {
    /* Make the table scrollable horizontally */
    .v-data-table {
        overflow-x: auto;
    }
    
    /* Reduce padding in cells */
    .v-data-table td {
        padding: 8px !important;
        white-space: nowrap;
    }
    
    /* Make chips smaller on mobile */
    .activity-chip, .status-chip {
        height: 22px !important;
        font-size: 0.7rem !important;
    }
}
    </style>
    <div id="kt_app_content_container" class="app-container container-fluid">
        <!-- Begin: Dashboard Content -->
        <div class="g-5 mb-5 p-0">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <div class="card-title">
                        <h3 class="fw-bold text-gray-800">Welcome {{ Auth::guard('admin')->user()->first_name }}!</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="card custom-card">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Total Users</h6>
                                    <h3 class="fw-bold">{{ $totalUsers ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card custom-card">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Total Rent Users</h6>
                                    <h3 class="fw-bold">{{ $rentUsers ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card custom-card">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Total Mortgage Users</h6>
                                    <h3 class="fw-bold">{{ $mortgageUsers ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card custom-card">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Earnings (This Month)</h6>
                                    <h3 class="fw-bold">${{ $monthlyEarnings ?? '0.00' }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Visualization Section -->
        <div id="app-dashboard">
            <v-app>
                <div class="row g-5 mb-5">
                    <!-- User Registration Trend -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <div class="card-title">
                                    <h3 class="fw-bold text-gray-800">User Registrations</h3>
                                </div>
                            </div>
                            <div class="card-body chart-container">
                                <user-registration-chart
                                    :chart-data='@json($userRegistrationData)'></user-registration-chart>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Trend -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <div class="card-title">
                                    <h3 class="fw-bold text-gray-800">Transaction Activity</h3>
                                </div>
                            </div>
                            <div class="card-body chart-container">
                                <transaction-chart :chart-data='@json($transactionData)'></transaction-chart>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-5 mb-5">
                    <!-- Revenue Trend -->
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <div class="card-title">
                                    <h3 class="fw-bold text-gray-800">Monthly Revenue</h3>
                                </div>
                            </div>
                            <div class="card-body chart-container">
                                <revenue-chart :chart-data='@json($revenueData)'></revenue-chart>
                            </div>
                        </div>
                    </div>

                    <!-- User Type Distribution -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <div class="card-title">
                                    <h3 class="fw-bold text-gray-800">User Distribution</h3>
                                </div>
                            </div>
                            <div class="card-body chart-container">
                                <user-distribution-chart
                                    :chart-data='@json($userDistributionData)'></user-distribution-chart>
                            </div>
                        </div>
                    </div>
                </div>
            </v-app>

        <!-- Recent Activity and Navigation Shortcuts -->

    <v-app>
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <div class="card-title">
                            <h3 class="fw-bold text-gray-800">Recent Activities</h3>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <v-data-table
                        :headers="[
                            { text: 'Time', value: 'time', sortable: false },
                            { text: 'Activity', value: 'activity', sortable: false },
                            { text: 'Details', value: 'details', sortable: false, class: 'hidden-sm-and-down' },
                            { text: 'Amount/Action', value: 'amount', sortable: false },
                            { text: 'Status', value: 'status', sortable: false }
                        ]"
                        :items="activities"
                        :items-per-page="10"
                        hide-default-footer
                        hide-default-header
                        mobile-breakpoint="0"
                        class="elevation-0">
                            <template v-slot:header>
                                <thead>
                                    <tr class="header-dark">
                                        <th style="padding: 12px 16px; color: black;">Time</th>
                                        <th style="padding: 12px 16px; color: black;">Activity</th>
                                        <th style="padding: 12px 16px; color: black;">Details</th>
                                        <th style="padding: 12px 16px; color: black;">Amount/Action</th>
                                        <th style="padding: 12px 16px; color: black;">Status</th>
                                    </tr>
                                </thead>
                            </template>

                            <template v-slot:item="{ item }">
                                <tr>
                                    <td>@{{ item.time }}</td>
                                    <td>
                                        <v-chip small label class="activity-chip" :color="getActivityColor(item.activity)"
                                            dark>
                                            @{{ item.activity }}
                                        </v-chip>
                                    </td>
                                    <td>@{{ item.details }}</td>
                                    <td>@{{ item.amount }}</td>
                                    <td>
                                        <v-chip small label class="status-chip" :class="getStatusClass(item.status)">
                                            @{{ item.status }}
                                        </v-chip>
                                    </td>
                                </tr>
                            </template>

                            <template v-slot:no-data>
                                <p class="text-muted p-4 m-0">No recent activities to display.</p>
                            </template>
                        </v-data-table>
                    </div>
                </div>
            </div>
            <!-- Quick Links Section with Hover Effects -->
<div class="col-lg-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
            <div class="card-title">
                <h3 class="fw-bold text-gray-800">Quick Links</h3>
            </div>
        </div>
        <div class="card-body">
            <ul class="list-unstyled quick-links">
                <li class="mb-3">
                    <a href="{{ route('admin.users') }}" class="quick-link-item">
                        <span class="quick-link-icon"><i class="fas fa-users"></i></span>
                        <span class="quick-link-text">Manage Users</span>
                    </a>
                </li>
                <li class="mb-3">
                    <a href="{{ route('admin.report.index') }}" class="quick-link-item">
                        <span class="quick-link-icon"><i class="fas fa-chart-bar"></i></span>
                        <span class="quick-link-text">View Reports</span>
                    </a>
                </li>
                <li class="mb-3">
                    <a href="{{ route('admin.settings.index') }}" class="quick-link-item">
                        <span class="quick-link-icon"><i class="fas fa-cog"></i></span>
                        <span class="quick-link-text">Settings</span>
                    </a>
                </li>
                <li class="mb-3">
                    <a href="{{ route('admin.support') }}" class="quick-link-item">
                        <span class="quick-link-icon"><i class="fas fa-question-circle"></i></span>
                        <span class="quick-link-text">Help & Support</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

        </div>
    </v-app>
</div>
        <!-- End: Dashboard Content -->
    </div>
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // User Registration Chart Component
        Vue.component('user-registration-chart', {
            props: ['chartData'],
            template: `
            <v-card flat>
                <canvas ref="userRegistrationChart"></canvas>
            </v-card>
        `,
            mounted() {
                this.renderChart();
            },
            methods: {
                renderChart() {
                    const ctx = this.$refs.userRegistrationChart.getContext('2d');

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: this.chartData.map(item => item.month),
                            datasets: [{
                                label: 'New Users',
                                data: this.chartData.map(item => item.users),
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 2,
                                tension: 0.4,
                                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                }
            }
        });

        // Transaction Chart Component
        Vue.component('transaction-chart', {
            props: ['chartData'],
            template: `
            <v-card flat>
                <canvas ref="transactionChart"></canvas>
            </v-card>
        `,
            mounted() {
                this.renderChart();
            },
            methods: {
                renderChart() {
                    const ctx = this.$refs.transactionChart.getContext('2d');

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: this.chartData.map(item => item.month),
                            datasets: [{
                                    label: 'Card Transactions',
                                    data: this.chartData.map(item => item.card),
                                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Wallet Transactions',
                                    data: this.chartData.map(item => item.wallet),
                                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                }
            }
        });

        // Revenue Chart Component
        Vue.component('revenue-chart', {
            props: ['chartData'],
            template: `
            <v-card flat>
                <canvas ref="revenueChart"></canvas>
            </v-card>
        `,
            mounted() {
                this.renderChart();
            },
            methods: {
                renderChart() {
                    const ctx = this.$refs.revenueChart.getContext('2d');

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: this.chartData.map(item => item.month),
                            datasets: [{
                                    label: 'Card Revenue ($)',
                                    data: this.chartData.map(item => item.card),
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 2,
                                    tension: 0.4,
                                    fill: false
                                },
                                {
                                    label: 'Wallet Revenue ($)',
                                    data: this.chartData.map(item => item.wallet),
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 2,
                                    tension: 0.4,
                                    fill: false
                                },
                                {
                                    label: 'Total Revenue ($)',
                                    data: this.chartData.map(item => item.total),
                                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                    borderColor: 'rgba(153, 102, 255, 1)',
                                    borderWidth: 2,
                                    tension: 0.4,
                                    fill: false
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            }
        });

        // User Distribution Chart Component
        Vue.component('user-distribution-chart', {
            props: ['chartData'],
            template: `
            <v-card flat>
                <canvas height="250px" ref="userDistributionChart"></canvas>
            </v-card>
        `,
            mounted() {
                this.renderChart();
            },
            methods: {
                renderChart() {
                    const ctx = this.$refs.userDistributionChart.getContext('2d');

                    new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: this.chartData.map(item => item.type),
                            datasets: [{
                                data: this.chartData.map(item => item.count),
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.6)',
                                    'rgba(54, 162, 235, 0.6)',
                                    'rgba(255, 206, 86, 0.6)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }
            }
        });

        // Initialize Vue App
        new Vue({
            el: '#app-dashboard',
            vuetify: new Vuetify(),
            data() {
                return {
                    activities: {!! json_encode(
                        $recentActivities->map(function ($activity) {
                            return [
                                'time' => \Carbon\Carbon::parse($activity->created_at)->diffForHumans(),
                                'activity' => str_replace('_', ' ', $activity->type),
                                'details' => $activity->user_name ?? $activity->first_name . ' ' . $activity->last_name,
                                'amount' => $activity->amount
                                    ? '$' . number_format($activity->amount, 2)
                                    : ($activity->type === 'USER'
                                        ? 'New Registration'
                                        : '-'),
                                'status' => ucfirst($activity->activity_status ?? 'N/A'),
                            ];
                        }),
                    ) !!}
                }
            },
            methods: {
                getActivityColor(type) {
                    const activityTypes = {
                        'USER': 'info',
                        'WALLET TRANSACTION': 'purple',
                        'BILL PAYMENT': 'blue'
                    };

                    return activityTypes[type.toUpperCase()] || 'info';
                },

                getStatusClass(status) {
                    switch (status.toLowerCase()) {
                        case 'active':
                            return 'status-active';
                        case 'completed':
                        case 'success':
                            return 'status-completed';
                        case 'pending':
                            return 'amber lighten-5 amber--text text--darken-4';
                        case 'failed':
                            return 'red lighten-5 red--text text--darken-4';
                        default:
                            return 'grey lighten-3 grey--text text--darken-1';
                    }
                }
            }
        });
    </script>
@endsection
