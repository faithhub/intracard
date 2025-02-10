@extends('admin.app-admin')
@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid">
        <!--begin::Card-->
        <div class="card">
            <div class="card-header border-0">
                <div class="card-title">
                    Generate Reports
                </div>
            </div>

            <div class="card-header border-0 pt-0">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="fa-solid fa-magnifying-glass fs-3 position-absolute ms-5"></i>
                        <input type="text" id="adminSearch" class="form-control form-control-solid w-250px ps-13"
                            placeholder="Search">
                    </div>
                </div>

                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <form id="filterForm" method="POST" action="#" class="row g-3 align-items-center">
                        @csrf
                        <div class="col-auto">
                            <select id="table" name="table" class="form-control" onchange="fetchColumns()">
                                <option value="" selected>Select a Table</option>
                                @isset($tables)
                                    @foreach ($tables as $table)
                                        <option value="{{ $table['table'] ?? null }}">{{ $table['name'] ?? null }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" onclick="fetchData()"><i class="fa fa-filter fs-2 text-white"><span class="path1"></span><span
                                class="path2"></span></i>Fetch</button>
                        </div>
                    </form>
                        <!--begin::Export-->
                        <button type="button" class="m-3 btn btn-light-primary me-3" data-bs-toggle="modal"
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
                                        <form id="exportReportForm" method="GET" action="{{ route('admin.export') }}">
                                            <div class="fv-row mb-10">
                                                <label class="required fs-6 fw-semibold form-label mb-2">Select Export
                                                    Format:</label>
                                                <select name="format" class="form-select form-select-solid fw-bold"
                                                    required>
                                                    <option value="excel">Excel</option>
                                                    <option value="pdf">PDF</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="table" value="">
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
                <h3 id="tableName">Table Name</h3>
                <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable" id="dataTable">
                    <thead>
                        <tr id="tableHeaders" class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th>S/N</th>
                            <th>Admin</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="text-gray-600 fw-semibold"></tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .text-success {
            color: green;
            font-weight: bold;
        }

        .text-warning {
            color: orange;
            font-weight: bold;
        }

        .text-danger {
            color: red;
            font-weight: bold;
        }

        .badge.bg-success {
            background-color: #1b660a;
            color: white;
            font-weight: bold;
            padding: 0.4em 0.6em;
            border-radius: 0.25rem;
        }

        .badge.bg-warning {
            background-color: #ffc107;
            color: white;
            font-weight: bold;
            padding: 0.4em 0.6em;
            border-radius: 0.25rem;
        }

        .badge.bg-danger {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
            padding: 0.4em 0.6em;
            border-radius: 0.25rem;
        }

        .badge.bg-secondary {
            background-color: #6c757d;
            color: white;
            font-weight: bold;
            padding: 0.4em 0.6em;
            border-radius: 0.25rem;
        }
    </style>
<script>
    // Utility Functions
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    }

    function getStatusClass(status) {
        switch (status) {
            case 'active': return 'text-success text-capitalize';
            case 'pending': return 'text-warning text-capitalize';
            case 'inactive': return 'text-danger text-capitalize';
            case 'completed': return 'text-success text-capitalize';
            case 'failed': return 'text-danger text-capitalize';
            default: return '';
        }
    }

    function getStatusBadge(status) {
        switch (status) {
            case 'completed': return 'badge bg-success text-capitalize';
            case 'pending': return 'badge bg-warning text-capitalize';
            case 'failed': return 'badge bg-danger text-capitalize';
            default: return 'badge bg-secondary text-capitalize';
        }
    }

    // Fetch columns dynamically based on the selected table
    function fetchColumns() {
        const table = document.getElementById('table').value;
        const hiddenTableInput = document.querySelector('input[name="table"]');
        hiddenTableInput.value = table;

        fetch(`/api/columns/${table}`)
            .then((response) => response.json())
            .then((columns) => {
                const filtersContainer = document.getElementById('filtersContainer');
                filtersContainer.innerHTML = '';
                columns.forEach((column) => {
                    filtersContainer.innerHTML += `
                        <div class="form-group">
                            <label for="${column}">${column}</label>
                            <input type="text" id="${column}" name="filters[${column}]" class="form-control" />
                        </div>
                    `;
                });
            })
            .catch((error) => console.error('Error fetching columns:', error));
    }

    // Fetch table data dynamically
    function fetchData() {
        const form = new FormData(document.getElementById('filterForm'));
        const tableName = document.getElementById('table').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/admin/report-get-table-columns', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ table: tableName }),
        })
        .then((response) => response.json())
        .then((tableConfig) => {
            if (!tableConfig.columns) {
                throw new Error(tableConfig.error || 'No columns found for the selected table.');
            }

            const { columns, relations } = tableConfig;

            return fetch('/admin/report-query-table', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: form,
            })
            .then((response) => response.json())
            .then((data) => {
                const tableHeaders = document.getElementById('tableHeaders');
                const tableBody = document.getElementById('tableBody');

                if (!data || !data.data || data.data.length === 0) {
                    tableHeaders.innerHTML = '';
                    tableBody.innerHTML = '';
                    toastr.warning('No data found for the given filters.', 'Warning');
                    return;
                }

                // Render table headers
                tableHeaders.innerHTML = `
                    <th>S/N</th>
                    ${columns.map(col => `<th>${col.replace('_', ' ').toUpperCase()}</th>`).join('')}
                `;

                // Render table body
                tableBody.innerHTML = data.data.map((row, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        ${columns.map(col => {
                            if (col === 'status') {
                                return `<td><span class="${getStatusBadge(row[col])}">${row[col]}</span></td>`;
                            }
                            if (['amount', 'charge', 'balance'].includes(col)) {
                                return `<td style="font-weight: bold; color: #1b660a;">$${row[col]} CAD</td>`;
                            }
                            if (col === 'created_at') {
                                return `<td>${formatDate(row[col])}</td>`;
                            }
                            return `<td>${row[col] || ''}</td>`;
                        }).join('')}
                    </tr>
                `).join('');
                toastr.success('Data loaded successfully.', 'Success');
            });
        })
        .catch((error) => {
            toastr.error(error.message || 'Failed to load data. Please try again.', 'Error');
        });
    }

    // Export data dynamically
    document.getElementById('exportReportForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const table = this.querySelector('input[name="table"]').value;
            const format = this.querySelector('select[name="format"]').value;
            const exportButton = this.querySelector('button[type="submit"]');

            if (!format) {
                toastr.error('Please select a format');
                return;
            }

        if (!table) {
            toastr.error("Please select a table");
            return;
        }

            exportButton.setAttribute('disabled', true);
            exportButton.innerHTML = `
        <span class="spinner-border spinner-border-sm" role="status"></span>
        Exporting...
    `;

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
                link.download = `report-${table}`;
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

    // Search functionality
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('adminSearch');
        const tableRows = document.querySelectorAll('#dataTable tbody tr');

        searchInput.addEventListener('input', function () {
            const query = searchInput.value.toLowerCase();

            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(query) ? '' : 'none';
            });
        });
    });
</script>

@endsection
