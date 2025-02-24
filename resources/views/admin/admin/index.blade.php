@extends('admin.app-admin')
@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    Manage Admin
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->

                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        <!--begin::Filter-->
                        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_export_users">Create Admin
                        </button>
                    </div>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->

            <div class="card-header border-0 pt-0">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="fa-solid fa-magnifying-glass fs-3 position-absolute ms-5"></i>
                        <input type="text" id="adminSearch" class="form-control form-control-solid w-250px ps-13"
                            placeholder="Search admins">
                    </div>
                    <!--end::Search-->
                </div>

                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                            <i class="fa fa-filter fs-2 text-white"></i> Filter
                        </button>

                        <!--begin::Menu-->
                        <div class="menu menu-sub menu-sub-dropdown w-400px w-md-425px" data-kt-menu="true">
                            <!--begin::Header-->
                            <div class="px-7 py-5">
                                <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <div class="px-7 py-5">
                                <form id="filterForm" method="GET">
                                    <!-- Admin Role -->
                                    <div class="mb-5">
                                        <label class="form-label fs-6 fw-semibold">Admin Role:</label>
                                        <select class="form-select form-select-solid fw-bold" name="role">
                                            <option value="all" {{ request('role', 'all') === 'all' ? 'selected' : '' }}>
                                                All Roles
                                            </option>
                                            @isset($roles)
                                                @foreach ($roles as $roleGroup)
                                                    <option value="{{ $roleGroup['name'] }}"
                                                        {{ request('role') == $roleGroup['name'] ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $roleGroup['name'])) }}
                                                    </option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>

                                    <!-- Status -->
                                    <div class="mb-5">
                                        <label class="form-label fs-6 fw-semibold">Status:</label>
                                        <select class="form-select form-select-solid fw-bold" name="status">
                                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All
                                            </option>
                                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive"
                                                {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>

                                    <!-- Date Filters -->
                                    <div class="mb-5">
                                        <label class="form-label fs-6 fw-semibold">Date Filter:</label>
                                        <div class="d-flex flex-column gap-2">
                                            <!-- Specific Date -->
                                            <input class="form-control fw-bold" type="date" name="specific_date"
                                                value="{{ request('specific_date') }}">
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label class="form-label fs-6 fw-semibold">Date Range Filter:</label>
                                        <div class="d-flex gap-2">
                                            <input class="form-control fw-bold" type="date" name="start_date"
                                                value="{{ request('start_date') }}" placeholder="Start Date">
                                            <input class="form-control fw-bold" type="date" name="end_date"
                                                value="{{ request('end_date') }}" placeholder="End Date">
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="d-flex justify-content-end">
                                        <button type="reset"
                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                            onclick="window.location='{{ route('admin.admin-users.index') }}'">Reset</button>
                                        <button type="submit" class="btn btn-primary fw-semibold px-6">Apply</button>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <!--begin::Export-->
                        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_export_admins">
                            <i class="fa fa-download fs-2 text-white"><span class="path1"></span><span
                                    class="path2"></span></i> Export
                        </button>
                        <!--end::Export-->

                        <div class="modal fade" id="kt_modal_export_admins" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered mw-400px">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="fw-bold">Export Admin Data</h2>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body mx-5 mx-xl-5 my-7">
                                        <form id="exportAdminsForm" method="GET" action="{{ route('admin.export') }}">
                                            <div class="fv-row mb-10">
                                                <label class="required fs-6 fw-semibold form-label mb-2">Select Export
                                                    Format:</label>
                                                <select name="format" class="form-select form-select-solid fw-bold"
                                                    required>
                                                    <option value="excel">Excel</option>
                                                    <option value="pdf">PDF</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="table" value="admins">
                                            <div class="text-center">
                                                <button type="button" class="btn btn-light me-3"
                                                    data-bs-dismiss="modal">Discard</button>
                                                <button type="submit" class="btn btn-primary">Export</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body py-4">
                <!-- Main Table -->
                <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable" id="adminTable">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th>S/N</th>
                            <th>Admin</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                        @isset($admins)
                            @foreach ($admins as $index => $admin)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="d-flex align-items-center">
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 mb-1 fw-bolder">
                                                {{ $admin['first_name'] }} {{ $admin['last_name'] }}
                                            </span>
                                            <span class="text-gray-500">{{ $admin['phone'] ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $admin['email'] }}</td>
                                    <td>
                                        @if (isset($admin['roles']) && count($admin['roles']) > 0)
                                            @foreach ($admin['roles'] as $role)
                                                <span class="badge badge-light-primary fw-bold me-1">
                                                    {{ $role['display_name'] }}
                                                </span>
                                            @endforeach
                                        @endif
                                        {{-- @if (isset($admin['primary_role']))
                                        <span class="badge badge-light-info fw-bold">
                                            {{ $admin['primary_role'] }}
                                        </span>
                                    @endif --}}
                                    </td>
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
                                    <td>{{ $admin['created_at'] }}</td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-light btn-sm" data-kt-menu-trigger="click"
                                            data-kt-menu-placement="bottom-end">
                                            Actions
                                            <i class="fa fa-chevron-down fs-5 ms-1"></i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                            data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3"
                                                    onclick="viewAdmin({{ $admin['id'] }})">
                                                    View
                                                </a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3"
                                                    onclick="editAdmin({{ $admin['id'] }})">
                                                    Edit
                                                </a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <button type="button"
                                                    class="menu-link px-3 text-danger border-0 bg-transparent w-100 text-start"
                                                    onclick="confirmDelete('{{ $admin['id'] }}', '{{ $admin['first_name'] }} {{ $admin['last_name'] }}')">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endisset
                    </tbody>
                </table>

                <!-- View Modal -->
                <div class="modal fade" id="viewAdminModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-650px">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="fw-bold">Admin Details</h2>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7" id="viewAdminContent">
                                <!-- Content will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-650px">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="fw-bold">Edit Admin</h2>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                <form id="editAdminForm" class="form" action="" novalidate>
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">First Name</label>
                                            <input type="text" class="form-control" name="first_name"
                                                id="edit_first_name" required>
                                            <div class="invalid-feedback">First name is required.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" class="form-control" name="last_name"
                                                id="edit_last_name" required>
                                            <div class="invalid-feedback">Last name is required.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" id="edit_email"
                                                required>
                                            <div class="invalid-feedback">Valid email is required.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Phone</label>
                                            <input type="text" maxlength="10" class="form-control" name="phone"
                                                id="edit_phone" required>
                                            <div class="invalid-feedback">Phone number is required.</div>
                                        </div>
                                        <!-- Status Field -->
        <div class="col-md-12">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" id="edit_status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
                                        <div class="col-12">
                                            <label class="form-label">Roles</label>
                                            <select class="form-select" name="roles[]" id="edit_roles" required>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role['id'] }}">{{ $role['display_name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">Please select a role.</div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-primary editAdminForm-btn">
                                            <span class="spinner-border spinner-border-sm align-middle ms-2 d-none" id="loadingSpinner2"></span>
                                            <span id="btn-form-text">Save Changes</span>
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Delete Admin</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-5">
                <i class="fas fa-exclamation-circle text-danger fs-5x mb-5"></i>
                <p class="fs-4 fw-bold mb-0">Are you sure you want to delete this admin?</p>
                <p class="text-gray-600" id="deleteAdminName"></p> <!-- Display admin name here -->
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteAdminForm" action="" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>


            </div>
        </div>
    </div>

    <!--begin::Modal - Adjust Balance-->
    <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">Create Admin User</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body mx-5 mx-xl-5 my-2">
                    <form id="createAdminForm">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" required>
                            <div class="invalid-feedback">First name is required.</div>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" required>
                            <div class="invalid-feedback">Last name is required.</div>
                        </div>
                        <div class="mb-3">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                            <div class="invalid-feedback">Valid email is required.</div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                            <div class="invalid-feedback">Password is required.</div>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="" disabled selected>Select Role</option>
                                @isset($roles)
                                    @foreach ($roles as $role)
                                        <option value="{{ $role['id'] }}">{{ $role['display_name'] }}</option>
                                    @endforeach
                                @endisset
                            </select>
                            <div class="invalid-feedback">Please select a role.</div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="createButton" class="btn btn-primary">
                                <span class="spinner-border spinner-border-sm me-2 d-none" id="loadingSpinner"></span>
                                Create
                            </button>
                        </div>
                    </form>
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - New Card-->

    <script>
        function viewAdmin(id) {
            $.ajax({
                url: `/admin/admin-users/${id}`,
                method: 'GET',
                success: function(response) {
                    $('#viewAdminContent').html(response);
                    $('#viewAdminModal').modal('show');
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load admin details'
                    });
                }
            });
        }


        function editAdmin(id) {
    // Load admin details via AJAX
    $.get(`/admin/admin-users/${id}/edit`, function(admin) {
        // Populate fields with admin data
        $('#edit_first_name').val(admin.first_name);
        $('#edit_last_name').val(admin.last_name);
        $('#edit_email').val(admin.email);
        $('#edit_phone').val(admin.phone);


        // Set status field
        $('#edit_status').val(admin.status); // Set the status based on admin data

        // Set roles field directly
        const roleIds = admin.roles.map(role => role.id); // Assuming admin.roles is an array of role objects
        $('#edit_roles').val(roleIds); // Set the roles

        // Set the form action for the update (without including the ID yet)
        $('#editAdminForm').attr('action', `/admin/admin-users/update`);

        // Show the modal
        $('#editAdminModal').modal('show');
    });

    // Listen for form submission
// Listen for form submission
$('#editAdminForm').on('submit', function(event) {
    event.preventDefault(); // Prevent default form submission

    const form = event.target;
    const saveButton = document.querySelector('.editAdminForm-btn'); // Save button
    const spinner = document.getElementById('loadingSpinner2');
    const buttonText = document.getElementById('btn-form-text'); // Get the text span

    if (!saveButton || !spinner || !buttonText) {
        console.error('Button or spinner element is missing!');
        return;
    }

    // Reset validation classes
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    // Validate the form fields
    let isValid = true;
    ['first_name', 'last_name', 'email', 'phone', 'roles', 'status'].forEach(field => {
        const input = document.getElementById(`edit_${field}`);
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        }
    });

    if (!isValid) return; // Stop if validation fails

    // Change the save button to show loading state
    saveButton.setAttribute('disabled', true); // Disable button to prevent multiple submissions

    // Save the original button text and update it
    const originalText = buttonText.textContent; // Save the original text
    buttonText.textContent = 'Please wait...'; // Change button text
    spinner.classList.remove('d-none'); // Show the spinner

    // Collect form data
    const formData = new FormData(form);
    formData.append('id', id);  // Add the admin ID to the form data

    // Submit data using Fetch API
    const updateUrl = `/admin/admin-users/update`; // Use dynamic admin ID

    fetch(updateUrl, {
        method: 'POST', // We use POST, but we simulate PUT with _method
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success toast
            toastr.success(data.message, 'Success');

            // Hide modal and optionally reload the table
            $('#editAdminModal').modal('hide');
            location.reload(); // Optional: Reload the page to update the table
        } else {
            // Show validation errors
            if (data.errors) {
                Object.keys(data.errors).forEach(key => {
                    const input = document.querySelector(`[name="${key}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = input.nextElementSibling;
                        if (feedback) feedback.textContent = data.errors[key][0];
                    }
                });

                // Show error toast
                toastr.error('Please check the form and correct the errors.', 'Validation Error');
            } else if (data.message) {
                // Show generic error toast
                toastr.error(data.message, 'Error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An unexpected error occurred. Please try again.', 'Error');
    })
    .finally(() => {
        // Reset the save button
        saveButton.removeAttribute('disabled');
        buttonText.textContent = originalText; // Reset button text back to original
        spinner.classList.add('d-none'); // Hide the spinner
    });
});

}


</script>

    <script>
// Function to show the delete confirmation modal and set the correct action
function confirmDelete(id, name) {
    // Set the admin name to display in the modal
    document.getElementById('deleteAdminName').textContent = name;

    // Set the form action to the correct URL for deletion
    const form = document.getElementById('deleteAdminForm');
    form.setAttribute('action', `/admin/admin-users/${id}`);

    // Show the modal
    $('#deleteConfirmationModal').modal('show');
}

// Listen for form submission to delete the admin
document.getElementById('deleteAdminForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission

    const form = event.target;
    const deleteButton = form.querySelector('button[type="submit"]'); // Get the delete button
    const spinner = document.createElement('span'); // Create a spinner element
    spinner.classList.add('spinner-border', 'spinner-border-sm', 'align-middle', 'ms-2'); // Style the spinner

    // Disable the button and show the spinner while deleting
    deleteButton.setAttribute('disabled', true); // Disable the button to prevent multiple clicks
    deleteButton.innerHTML = 'Deleting...'; // Change button text to indicate deletion in progress
    deleteButton.appendChild(spinner); // Append the spinner to the button

    // Submit the form to delete the admin  
    fetch(form.action, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: new FormData(form)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success toast
            toastr.success(data.message, 'Success');

            // Optionally, you can reload the table or update the UI accordingly
            $('#deleteConfirmationModal').modal('hide');
            location.reload(); // Reload the page or perform other actions
        } else {
            // Show error toast
            toastr.error(data.message, 'Error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An unexpected error occurred. Please try again.', 'Error');
    })
    .finally(() => {
        // Reset the button state after the request completes
        deleteButton.removeAttribute('disabled');
        deleteButton.innerHTML = 'Delete'; // Reset button text back to normal
        spinner.remove(); // Remove the spinner
    });
});

        document.getElementById('exportAdminsForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const format = this.querySelector('select[name="format"]').value;
            const exportButton = this.querySelector('button[type="submit"]');

            if (!format) {
                toastr.error('Please select a format');
                return;
            }

            exportButton.setAttribute('disabled', true);
            exportButton.innerHTML =
                `<span class="spinner-border spinner-border-sm" role="status"></span>Exporting...`;

            try {
                const params = new URLSearchParams(new FormData(this));
                const response = await fetch(`${this.action}?${params.toString()}`, {
                    method: 'GET'
                });

                if (!response.ok) throw new Error('Failed to export data');

                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = `admins`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                toastr.success('Export completed successfully');
            } catch (error) {
                toastr.error('Failed to export data');
            } finally {
                exportButton.removeAttribute('disabled');
                exportButton.innerHTML = 'Export';
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('adminSearch');
            const tableRows = document.querySelectorAll('#adminTable tbody tr');

            searchInput.addEventListener('input', function() {
                const query = searchInput.value.toLowerCase();

                tableRows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    if (rowText.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });


        document.getElementById('createAdminForm').addEventListener('input', function(event) {
            const input = event.target;

            // Remove the 'is-invalid' class when the user starts typing
            if (input.classList.contains('is-invalid')) {
                input.classList.remove('is-invalid');
            }
        });

        document.getElementById('createButton').addEventListener('click', function() {
            const form = document.getElementById('createAdminForm');
            const createButton = document.getElementById('createButton');
            const spinner = document.getElementById('loadingSpinner');

            // Reset validation classes
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            // Validate the form fields
            let isValid = true;

            ['first_name', 'last_name', 'email', 'password', 'role'].forEach(field => {
                const input = document.getElementById(field);
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                }
            });

            if (!isValid) return;

            // Add loading state
            createButton.setAttribute('disabled', true);
            spinner.classList.remove('d-none');

            // Collect form data
            const formData = new FormData(form);

            // Submit data using Fetch API
            fetch("{{ route('admin.admin-users.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success toast
                        toastr.success(data.message, 'Success');

                        // Hide modal and optionally reload the table
                        $('#kt_modal_export_users').modal('hide');
                        location.reload(); // Optional: Reload the page to update the table
                    } else {
                        // Show validation errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(key => {
                                const input = document.querySelector(`[name="${key}"]`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    const feedback = input.nextElementSibling;
                                    if (feedback) feedback.textContent = data.errors[key][0];
                                    console.log(data.errors[key][0]);
                                    toastr.error(data.errors[key][0], 'Validation Error');
                                    
                                }
                            });

                            // Show error toast
                            toastr.error('Please check the form and correct the errors.', 'Validation Error');
                        } else if (data.message) {
                            // Show generic error toast
                            toastr.error(data.message, 'Error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('An unexpected error occurred. Please try again.', 'Error');
                })
                .finally(() => {
                    // Remove loading state
                    createButton.removeAttribute('disabled');
                    spinner.classList.add('d-none');
                });
        });

        document.getElementById('editAdminForm').addEventListener('input', function(event) {
            const input = event.target;

            // Remove the 'is-invalid' class when the user starts typing
            if (input.classList.contains('is-invalid')) {
                input.classList.remove('is-invalid');
            }
        });
    </script>
@endsection
