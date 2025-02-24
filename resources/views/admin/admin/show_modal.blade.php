<div class="row g-3">
    <!-- Personal Information -->
    <div class="col-md-6">
        <h3 class="mb-4 fs-4">Personal Information</h3>
        <div class="table-responsive">
            <table class="table table-borderless">
                <tr>
                    <td class="fw-bold" style="width: 80px;">First Name</td>
                    <td>{{ $admin->first_name }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Last Name</td>
                    <td>{{ $admin->last_name }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Email</td>
                    <td>{{ $admin->email }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Phone</td>
                    <td>{{ $admin->phone ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Account Information -->
    <div class="col-md-6">
        <h3 class="mb-4 fs-4">Account Information</h3>
        <div class="table-responsive">
            <table class="table table-borderless">
                <tr>
                    <td class="fw-bold" style="width: 80px;">Status</td>
                    <td>
                        @if ($admin['status'] === 'active')
                            <div class="badge badge-light-success fw-bold">Active</div>
                        @elseif ($admin['status'] === 'inactive')
                            <div class="badge badge-light-warning fw-bold">Inactive</div>
                        @elseif ($admin['status'] === 'suspended')
                            <div class="badge badge-light-danger fw-bold">Suspended</div>
                        @else
                            <div class="badge badge-light-danger fw-bold">Not Verified</div>
                        @endif

                    </td>
                </tr>
                <tr>
                    <td class="fw-bold">Roles</td>
                    <td>
                        @foreach ($admin->roles as $role)
                            <span class="badge badge-light-primary me-2">
                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                            </span>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold">Created At</td>
                    <td>{{ $admin->created_at->format('d M Y, h:i A') }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Last Updated</td>
                    <td>{{ $admin->updated_at->format('d M Y, h:i A') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Additional Information (if you have any) -->
    @if ($admin->otp_expires_at)
        <div class="col-12">
            <h3 class="mb-4 fs-4">Security Information</h3>
            <div class="table-responsive">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold" style="width: 150px;">OTP Status</td>
                        <td>
                            @if ($admin->otp_verified)
                                <span class="badge badge-light-success">Verified</span>
                            @else
                                <span class="badge badge-light-warning">Pending Verification</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">OTP Expires At</td>
                        <td>{{ \Carbon\Carbon::parse($admin->otp_expires_at)->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    @endif
</div>
