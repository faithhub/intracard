@extends('admin.app-admin')

@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid">
        <!-- Begin: Dashboard Content -->
        <div class="row g-5 mb-5" style="padding: 2rem;">

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

        <!-- Recent Activity and Navigation Shortcuts -->
        <div class="row g-5">

            <!-- Recent Activity -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <div class="card-title">
                            <h3 class="fw-bold text-gray-800">Recent Activities</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (isset($recentActivities) && count($recentActivities) > 0)
                            <ul class="list-group">
                                @foreach ($recentActivities as $activity)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $activity['description'] }}</span>
                                        <span class="text-muted small">{{ $activity['time'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No recent activities to display.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Navigation Shortcuts -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <div class="card-title">
                            <h3 class="fw-bold text-gray-800">Quick Links</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <a href="{{ route('admin.users') }}"
                                    class="btn btn-link fw-semibold text-start w-100">Manage Users</a>
                            </li>
                            <li class="mb-3">
                                <a href="{{ route('admin.report.index') }}"
                                    class="btn btn-link fw-semibold text-start w-100">View Reports</a>
                            </li>
                            <li class="mb-3">
                                <a href="{{ route('admin.settings') }}"
                                    class="btn btn-link fw-semibold text-start w-100">Settings</a>
                            </li>
                            <li class="mb-3">
                                <a href="{{ route('admin.support') }}"
                                    class="btn btn-link fw-semibold text-start w-100">Help & Support</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
        <!-- End: Dashboard Content -->
    </div>
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
@endsection
