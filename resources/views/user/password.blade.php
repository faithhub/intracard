@extends('app-user')

@section('content')
<style>
    .card {
    border-radius: 12px;
    border: none;
}

.input-group-text {
    border: none;
    background: #f5f5f5;
}

input:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

button {
    border-radius: 6px;
}

</style>
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="card mb-5 shadow-sm">
            <!-- Begin: Header -->
            <div class="card-header border-0">
                <div class="card-title">
                    <h3 class="fw-bold text-primary m-0">Update Password</h3>
                    <p class="text-muted mt-2">Keep your account secure by updating your password regularly.</p>
                </div>
            </div>
            <!-- End: Header -->

            <!-- Begin: Form -->
            <form id="kt_account_profile_details_form" class="form">
                <div class="card-body p-5">
                    <!-- Current Password -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold fs-6">Current Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fa fa-lock text-muted"></i>
                            </span>
                            <input type="password" class="form-control form-control-lg" placeholder="Enter current password">
                        </div>
                        <small class="text-muted">Your current password is required to make changes.</small>
                    </div>

                    <!-- New Password -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold fs-6">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fa fa-key text-muted"></i>
                            </span>
                            <input type="password" class="form-control form-control-lg" placeholder="Enter new password">
                        </div>
                        <small class="text-muted">Choose a strong password that you haven’t used before.</small>
                    </div>

                    <!-- Confirm New Password -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold fs-6">Confirm New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fa fa-check text-muted"></i>
                            </span>
                            <input type="password" class="form-control form-control-lg" placeholder="Confirm new password">
                        </div>
                        <small class="text-muted">Re-enter your new password to confirm.</small>
                    </div>
                </div>

                <!-- Begin: Actions -->
                <div class="card-footer d-flex justify-content-between p-4">
                    <a href="#" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
                <!-- End: Actions -->
            </form>
            <!-- End: Form -->
        </div>
    </div>
@endsection
