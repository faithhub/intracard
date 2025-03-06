@extends('admin.app-admin')
@section('content')

    <style>
        .text-active-primary.active {
            color: var(--bs-gray-500) !important;
        }

        .nav-line-tabs .nav-item .nav-link.active {
            background-color: #a000f93b !important;
            padding: 8px !important;
            color: #310431 !important;
        }

        .nav-tabs .nav-link {
            border-top-left-radius: 0px !important;
            border-top-right-radius: 0px !important;
        }

        .img-cc {
            max-width: 70px !important;
        }

        .menu-state-bg-light-primary .menu-item:not(.here) .menu-link:hover:not(.disabled):not(.active):not(.here) {
            transition: color .2s ease;
            background-color: #a000f93b !important;
            color: var(--bs-primary);
        }
        .form-control:disabled {
    color: var(--bs-gray-500) !important;
    background-color: var(--bs-gray-200) !important;
    border-color: #efe9e1 !important;
    opacity: 1 !important;
}
    </style>
    <div id="kt_app_content_container" class="app-container container-fluid">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    App Settings
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->

                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->


            <!--begin::Content-->
            <div class="flex-lg-row-fluid ms-lg-15">
                <!--begin:::Tabs-->
                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8"
                    role="tablist">
                    <!--begin:::Tab item-->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                            href="#kt_user_view_overview_tab" aria-selected="true" role="tab">Overview</a>
                    </li>
                    <!--end:::Tab item-->

                    <!--begin:::Tab item-->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab"
                            href="#kt_user_view_overview_account_type" data-kt-initialized="1" aria-selected="false"
                            role="tab" tabindex="-1">AutoReply</a>
                    </li>
                    <!--end:::Tab item-->

                </ul>
                <!--end:::Tabs-->

                <!--begin:::Tab content-->
                <div class="tab-content" id="myTabContent">
                    <!--begin:::Tab pane-->


                    {{-- Include this in your tab content for the Overview tab --}}
<div class="tab-pane fade active show" id="kt_user_view_overview_tab" role="tabpanel">
    <!--begin::Card-->
    <div class="card pt-4 mb-6 mb-xl-9">
        <!--begin::Card header-->
        <div class="card-header border-0">
            <!--begin::Card title-->
            <div class="card-title flex-column">
                <h2 class="mb-1">Application Settings</h2>
                <div class="fs-6 fw-semibold text-muted">Manage your application settings</div>
            </div>
            <!--end::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Add-->
                <a href="{{ route('admin.settings.trashed') }}" class="btn btn-light-warning btn-sm me-2">
                    <i class="fa fa-trash fs-3"></i> Deleted Settings
                </a>
                <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_add_setting">
                    <i class="fa fa-plus fs-3 text-white"></i> Add Setting
                </button>
                <!--end::Add-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-0 pb-5">
            <!--begin::Table wrapper-->
            <div class="table-responsive">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed gy-5" id="kt_table_settings">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th width="5%">SN</th>
                            <th width="20%">Name</th>
                            <th width="20%">Setting Key</th>
                            <th width="30%">Value</th>
                            <th width="10%">Type</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fs-6 fw-semibold text-gray-600">
                        @php
                            $index = 1;
                        @endphp
                        @forelse($settings as $setting)
                            <tr>
                                <td>{{ $index++ }}</td>
                                <td>{{ $setting->name }}</td>
                                <td>{{ $setting->key }}</td>
                                <td>
                                    @if($setting->type == 'boolean')
                                        <span class="badge {{ $setting->value ? 'badge-light-success' : 'badge-light-danger' }}">
                                            {{ $setting->value ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    @elseif($setting->type == 'json')
                                        <span class="badge badge-light-primary">JSON Data</span>
                                    @elseif($setting->type == 'file')
                                        <span class="badge badge-light-info">File Path</span>
                                    @else
                                        {{ $setting->value }}
                                    @endif
                                </td>
                                <td>{{ ucfirst($setting->type) }}</td>
                                <td>
                                    <button type="button" class="btn btn-icon btn-light-success btn-sm w-30px h-30px me-3" 
                                            data-bs-toggle="modal" data-bs-target="#kt_modal_edit_setting"
                                             data-bs-toggle="tooltip" title="Edit"
                                            data-setting-id="{{ $setting->id }}"
                                            data-setting-key="{{ $setting->key }}"
                                            data-setting-name="{{ $setting->name }}"
                                            data-setting-value="{{ $setting->value }}"
                                            data-setting-type="{{ $setting->type }}">
                                        <i class="fa fa-pen-to-square fs-3"></i>
                                    </button>
                                    
                                    <button type="button" class="btn btn-icon btn-light-danger btn-sm w-30px h-30px"
                                     data-bs-toggle="tooltip" title="Delete"
                                            onclick="confirmDeleteSetting('{{ $setting->id }}', '{{ $setting->key }}')">
                                        <i class="fa fa-trash fs-3"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No settings found. Add your first setting.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Table wrapper-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>

<!-- Add Setting Modal -->
<div class="modal fade" id="kt_modal_add_setting" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Add New Setting</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="fa fa-times fs-1"></i>
                </div>
            </div>
            
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="kt_modal_add_setting_form" class="form" action="{{ route('admin.settings.store') }}" method="POST">
                    @csrf
                    <div class="d-flex flex-column mb-8">
                        <label class="fs-6 fw-semibold mb-2">Setting Key</label>
                        <input type="text" class="form-control form-control-solid" placeholder="Enter setting key" name="key" required />
                        <div class="text-muted fs-7 mt-2">Use a unique key name (e.g., enable_2fa, app_name)</div>
                    </div>
                    
                    <div class="d-flex flex-column mb-8">
                        <label class="fs-6 fw-semibold mb-2">Display Name</label>
                        <input type="text" class="form-control form-control-solid" placeholder="Enter display name" name="name" required />
                        <div class="text-muted fs-7 mt-2">A user-friendly name (e.g., Two Factor Authentication, Application Name)</div>
                    </div>
                    
                    <div class="d-flex flex-column mb-8">
                        <label class="fs-6 fw-semibold mb-2">Setting Type</label>
                        <select name="type" class="form-select form-select-solid" id="setting_type">
                            <option value="string">Text</option>
                            <option value="boolean">Boolean (On/Off)</option>
                            <option value="integer">Number</option>
                            <option value="json">JSON</option>
                            <option value="file">File Path</option>
                        </select>
                    </div>
                    
                    <div class="d-flex flex-column mb-8" id="value_text_div">
                        <label class="fs-6 fw-semibold mb-2">Value</label>
                        <input type="text" class="form-control form-control-solid" placeholder="Enter setting value" name="value" id="value_text" />
                    </div>
                    
                    <div class="d-flex flex-column mb-8 d-none" id="value_boolean_div">
                        <label class="fs-6 fw-semibold mb-2">Value</label>
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="1" id="value_boolean" name="value_boolean" />
                            <label class="form-check-label" for="value_boolean">Enabled</label>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-column mb-8 d-none" id="value_json_div">
                        <label class="fs-6 fw-semibold mb-2">JSON Value</label>
                        <textarea class="form-control form-control-solid" rows="5" name="value_json" id="value_json" placeholder='{"key": "value"}'></textarea>
                    </div>
                    
                    <div class="text-center">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Setting Modal -->
<div class="modal fade" id="kt_modal_edit_setting" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Edit Setting</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="fa fa-times fs-1"></i>
                </div>
            </div>
            
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="kt_modal_edit_setting_form" class="form" action="{{ route('admin.settings.update', ['id' => '_ID_']) }}" method="POST" data-action-url="{{ route('admin.settings.update', ['id' => '__id__']) }}">
                    @csrf
                    {{-- No @method('PUT') since we're using POST --}}
                    <input type="hidden" name="id" id="edit_id" value="">
                    
                    <div class="d-flex flex-column mb-8">
                        <label class="fs-6 fw-semibold mb-2">Setting Key</label>
                        <input type="text" class="form-control form-control-solid" id="edit_key" readonly disabled />
                    </div>
                    
                    <div class="d-flex flex-column mb-8">
                        <label class="fs-6 fw-semibold mb-2">Display Name</label>
                        <input type="text" class="form-control form-control-solid" name="name" id="edit_name" />
                    </div>
                    
                    <div class="d-flex flex-column mb-8">
                        <label class="fs-6 fw-semibold mb-2">Setting Type</label>
                        <input type="text" class="form-control form-control-solid" id="edit_type" readonly disabled />
                    </div>
                    
                    <div class="d-flex flex-column mb-8" id="edit_value_text_div">
                        <label class="fs-6 fw-semibold mb-2">Value</label>
                        <input type="text" class="form-control form-control-solid" id="edit_value_text" />
                    </div>
                    
                    <div class="d-flex flex-column mb-8 d-none" id="edit_value_boolean_div">
                        <label class="fs-6 fw-semibold mb-2">Value</label>
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="1" id="edit_value_boolean" />
                            <label class="form-check-label" for="edit_value_boolean">Enabled</label>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-column mb-8 d-none" id="edit_value_json_div">
                        <label class="fs-6 fw-semibold mb-2">JSON Value</label>
                        <textarea class="form-control form-control-solid" rows="5" id="edit_value_json"></textarea>
                    </div>
                    
                    <div class="text-center">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
                    <div class="tab-pane fade" id="kt_user_view_overview_account_type" role="tabpanel"
                        style="display: block">
                        <!--begin::Card-->
                        <div class="card pt-4 mb-6 mb-xl-9">

                            <!--begin::Card header-->
                            <div class="card-header border-0">
                                <!--begin::Card title-->
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">AutoReply Messages</h2>

                                    <div class="fs-6 fw-semibold text-muted"></div>
                                </div>
                                <!--end::Card title-->

                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Add-->
                                    <button type="button" class="btn btn-light-primary btn-sm" data-type="dark"
                                        data-size="s" data-title="Create AutoReply Message" onclick="createConfirm(event)"
                                        href="{{ route('admin.auto-replies.create') }}">
                                        <i class="fa fa-plus fs-3 text-white"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span><span
                                                class="path4"></span><span class="path5"></span></i> Add
                                    </button>
                                    <!--end::Add-->
                                </div>
                                <!--end::Card toolbar-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body pt-0 pb-5">
                                <!--begin::Table wrapper-->
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed gy-5"
                                        id="kt_table_users_login_session">
                                        <thead>
                                            <th width="5%">SN</th>
                                            <th width="20%">Keywords</th>
                                            <th width="65%">Response</th>
                                            <th width="10%">Action</th>
                                        </thead>
                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                            @isset($autoReplies)
                                                @foreach ($autoReplies as $index => $reply)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            {{ is_array($reply->keywords)
                                                                ? implode(
                                                                    ', ',
                                                                    array_map(function ($item) {
                                                                        return str_replace(',', ', ', $item); // Add space after commas in each array item
                                                                    }, $reply->keywords),
                                                                )
                                                                : implode(', ', json_decode($reply->keywords, true)) }}
                                                        </td>

                                                        <td>{{ $reply->response }}</td>
                                                        <td class="text-end">
                                                            <!-- Edit Button -->
                                                            <button type="button" class="btn btn-icon w-30px h-30px ms-auto"
                                                                data-size="s" data-title="Update AutoReply Message"
                                                                onclick="editConfirm(event)"
                                                                href="{{ route('admin.auto-replies.edit', $reply->id) }}">
                                                                <i class="fa fa-pen-to-square fs-3"></i>
                                                            </button>

                                                            <!-- Delete Button -->
                                                            <button class="btn btn-icon w-30px h-30px ms-auto"
                                                                onclick="deleteAutoReply({{ $reply->id }}, '{{ route('admin.auto-replies.destroy', $reply->id) }}')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endisset
                                        </tbody>
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Table wrapper-->
                            </div>
                            <!--end::Card body-->
                        </div>
                    </div>
                    <!--end:::Tab pane-->

                </div>
                <!--end:::Tab content-->
            </div>
            <!--end::Content-->
        </div>
    </div>

    
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script>
        function deleteAutoReply(autoReplyId, deleteUrl) {
            Swal.fire({
                text: "Are you sure you want to delete this auto-reply?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                buttonsStyling: false,
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-secondary",
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the AJAX deletion
                    $.ajax({
                        url: deleteUrl, // Pass the delete URL dynamically
                        type: "POST", // Use POST with the _method spoofed
                        data: {
                            _method: "DELETE", // Method spoofing for DELETE
                            _token: $('meta[name="csrf-token"]').attr("content"), // CSRF token
                        },
                        success: function(response) {
                            Swal.fire({
                                text: response.message || "Auto-reply deleted successfully!",
                                icon: "success",
                                buttonsStyling: false,
                                timer: 2000, // Automatically close after 2 seconds
                                showConfirmButton: false,
                                didClose: () => {
                                    location.reload(); // Reload the page when the toast closes
                                }
                            });

                            // Optionally refresh the page or remove the deleted row dynamically
                            $(`#auto-reply-row-${autoReplyId}`).remove(); // Assuming row has an ID like auto-reply-row-1
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                text: "Failed to delete the auto-reply. Please try again.",
                                icon: "error",
                                buttonsStyling: false, // Disable default SweetAlert2 button styling
                                showConfirmButton: true,
                                confirmButtonText: "Retry", // Custom button text
                                customClass: {
                                    confirmButton: "btn btn-danger", // Bootstrap or your custom class
                                },
                            });
                        },
                    });
                }
            });
        }
    </script>
@endsection
