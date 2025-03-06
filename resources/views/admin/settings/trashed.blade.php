@extends('admin.app-admin')
@section('content')

<div id="kt_app_content_container" class="app-container container-fluid">
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0">
            <!--begin::Card title-->
            <div class="card-title">
                <h2>Deleted Settings</h2>
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <a href="{{ route('admin.settings.index') }}" class="btn btn-sm btn-light-primary me-2">
                    <i class="fa fa-arrow-left me-1"></i>Back to Settings
                </a>
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_settings_table">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th width="20%">Setting Key</th>
                            <th width="20%">Name</th>
                            <th width="30%">Value</th>
                            <th width="15%">Type</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                        @forelse($settings as $setting)
                            <tr>
                                <td>{{ $setting->key }}</td>
                                <td>{{ $setting->name }}</td>
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
                                    <a href="{{ route('admin.settings.restore', $setting->id) }}" 
                                       class="btn btn-icon btn-light-success btn-sm me-1"
                                       data-bs-toggle="tooltip" title="Restore">
                                        <i class="fa fa-undo fs-3"></i>
                                    </a>
                                    
                                    <button type="button" 
                                            class="btn btn-icon btn-light-danger btn-sm" 
                                            onclick="confirmForceDelete('{{ $setting->id }}', '{{ $setting->key }}')"
                                            data-bs-toggle="tooltip" title="Delete Permanently">
                                        <i class="fa fa-trash fs-3"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No deleted settings found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>

<script>
    function confirmForceDelete(id, key) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to permanently delete the setting "${key}". This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete permanently!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit a form to perform the force delete
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/settings/force-delete/${id}`;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection