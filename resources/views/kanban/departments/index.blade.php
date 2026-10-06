<x-app-layout>
    @section('title', 'Manage Kanban Departments')

    @include('layouts.partials.vendor.datatables-searchbuilder')

    @push('css')
        @include('layouts.partials.roleuser_styles')
        <style>
            .pl-icon-btn {
                width: 36px;
                height: 36px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 10px;
                border: 1px solid #e2e8f0;
                background-color: #f8fafc;
                color: #475569;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
            }
            .pl-icon-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            }
            .pl-icon-btn-edit:hover {
                background-color: #eff6ff;
                border-color: #93c5fd;
                color: #2563eb;
            }
            .pl-icon-btn-delete:hover {
                background-color: #fef2f2;
                border-color: #fca5a5;
                color: #dc2626;
            }
            /* SearchBuilder Custom Styling for User Management Theme */
            .dtsb-searchBuilder {
                background-color: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 14px;
                padding: 16px;
                margin-bottom: 20px;
            }
            .dtsb-group {
                background: transparent !important;
            }
            .dtsb-criteria {
                margin-bottom: 8px;
            }
            .dtsb-criteria select, .dtsb-criteria input {
                border-radius: 8px !important;
                border: 1px solid #cbd5e1 !important;
                padding: 6px 12px !important;
                font-size: 13px !important;
            }
            .dtsb-button {
                border-radius: 8px !important;
                font-size: 13px !important;
                font-weight: 500 !important;
            }
            .dataTables_wrapper .dataTables_filter input {
                border: 1px solid #cbd5e1;
                border-radius: 8px;
                padding: 6px 12px;
                margin-left: 8px;
            }
            .dataTables_wrapper .dataTables_length select {
                border: 1px solid #cbd5e1;
                border-radius: 8px;
                padding: 6px 28px 6px 12px;
            }
        </style>
    @endpush

    <div class="pl-shell mt-4">
        <!-- Hero Header -->
        <div class="pl-hero">
            <span class="pl-hero-kicker">Kanban Operations</span>
            <div class="pl-hero-title">Job Departments</div>
            <p class="pl-hero-copy">
                Manage operational departments responsible for Kanban jobs, route handovers, multi-department routing, and stage approvals.
            </p>
            <div class="pl-toolbar">
                <button type="button" class="pl-btn pl-btn-primary" id="openCreateModalBtn">
                    <i class="fa-solid fa-plus mr-2"></i> Add Department
                </button>
            </div>
        </div>

        <!-- Table Card -->
        <div class="pl-card">
            <div class="pl-card-head">
                <div class="pl-card-title">Departments Directory</div>
                <div class="pl-card-copy">
                    Browse all active Kanban operational departments. Use SearchBuilder above the table for advanced criteria.
                </div>
            </div>
            <div class="pl-card-body">
                <div class="pl-table-shell table-responsive">
                    <table id="departmentsTable" class="table table-bordered table-hover w-full" style="width: 100%;">
                        <thead>
                            <tr>
                                <th class="w-16 text-center">ID</th>
                                <th>Department Name</th>
                                <th class="text-center w-36">Assigned Users</th>
                                <th class="w-44">Created At</th>
                                <th class="text-center w-28">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Department Modal -->
    <div id="createDepartmentModal" class="pl-modal-overlay">
        <div class="pl-modal-panel pl-modal-panel-md">
            <div class="pl-modal-header">
                <div>
                    <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Department Master</span>
                    <h3 class="pl-modal-title">Add New Department</h3>
                </div>
                <button type="button" class="pl-modal-close" data-close-modal="createDepartmentModal">&times;</button>
            </div>
            <form id="createDepartmentForm">
                @csrf
                <div class="pl-modal-body bg-slate-50">
                    <div id="createModalAlert" class="alert alert-danger hidden mb-3 py-2 px-3 text-sm"></div>

                    <div class="form-group-custom">
                        <label for="create_department_name">Department Name <span class="text-red-500">*</span></label>
                        <input type="text" id="create_department_name" name="department_name" class="form-control-custom" placeholder="e.g. Electrical Engineering" required>
                        <span class="error-msg hidden" id="error_create_department_name"></span>
                    </div>
                </div>
                <div class="pl-modal-footer">
                    <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="createDepartmentModal">Cancel</button>
                    <button type="submit" class="pl-btn pl-btn-primary" id="btnSubmitCreate">
                        <i class="fas fa-check mr-1.5"></i> Save Department
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Department Modal -->
    <div id="editDepartmentModal" class="pl-modal-overlay">
        <div class="pl-modal-panel pl-modal-panel-md">
            <div class="pl-modal-header">
                <div>
                    <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Maintenance</span>
                    <h3 class="pl-modal-title">Edit Department</h3>
                </div>
                <button type="button" class="pl-modal-close" data-close-modal="editDepartmentModal">&times;</button>
            </div>
            <form id="editDepartmentForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_department_id" name="id">

                <div class="pl-modal-body bg-slate-50">
                    <div id="editModalAlert" class="alert alert-danger hidden mb-3 py-2 px-3 text-sm"></div>

                    <div class="form-group-custom">
                        <label for="edit_department_name">Department Name <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_department_name" name="department_name" class="form-control-custom" required>
                        <span class="error-msg hidden" id="error_edit_department_name"></span>
                    </div>
                </div>
                <div class="pl-modal-footer">
                    <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="editDepartmentModal">Cancel</button>
                    <button type="submit" class="pl-btn pl-btn-primary" id="btnSubmitEdit">
                        <i class="fas fa-save mr-1.5"></i> Update Department
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Toast Helper
                function showToast(icon, message) {
                    Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    }).fire({
                        icon: icon,
                        title: message
                    });
                }

                // Modal Toggle Helper
                function openModal(modalId) {
                    $('#' + modalId).addClass('active').css('display', 'flex');
                }
                function closeModal(modalId) {
                    $('#' + modalId).removeClass('active').css('display', 'none');
                }

                $('[data-close-modal]').on('click', function() {
                    const target = $(this).data('close-modal');
                    closeModal(target);
                });

                $('.pl-modal-overlay').on('click', function(e) {
                    if (e.target === this) {
                        closeModal($(this).attr('id'));
                    }
                });

                $(document).on('keydown', function(e) {
                    if (e.key === 'Escape') {
                        $('.pl-modal-overlay.active').each(function() {
                            closeModal($(this).attr('id'));
                        });
                    }
                });

                // Initialize DataTable with Yajra and SearchBuilder
                const table = $('#departmentsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('kanban.departments.index') }}',
                        type: 'GET'
                    },
                    dom: 'Qlfrtip',
                    searchBuilder: {
                        columns: [0, 1, 3]
                    },
                    columns: [
                        { data: 'id', name: 'id', className: 'text-center font-semibold' },
                        { data: 'department_name', name: 'department_name', className: 'font-semibold text-slate-800' },
                        { data: 'users_count', name: 'users_count', className: 'text-center', searchable: false },
                        { data: 'created_at_formatted', name: 'created_at' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
                    ],
                    order: [[0, 'desc']],
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    drawCallback: function() {
                        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                            tooltipTriggerList.map(function(el) {
                                return new bootstrap.Tooltip(el);
                            });
                        }
                    }
                });

                // Open Create Modal
                $('#openCreateModalBtn').on('click', function() {
                    $('#createDepartmentForm')[0].reset();
                    $('#createModalAlert').addClass('hidden').text('');
                    openModal('createDepartmentModal');
                    setTimeout(() => $('#create_department_name').focus(), 150);
                });

                // Create Form Submit via AJAX (No reload)
                $('#createDepartmentForm').on('submit', function(e) {
                    e.preventDefault();
                    const $btn = $('#btnSubmitCreate');
                    const originalText = $btn.html();
                    $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Saving...');
                    $('#createModalAlert').addClass('hidden').text('');

                    $.ajax({
                        url: '{{ route('kanban.departments.store') }}',
                        type: 'POST',
                        data: $(this).serialize(),
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        success: function(res) {
                            closeModal('createDepartmentModal');
                            showToast('success', res.message || 'Department created successfully.');
                            table.ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            const errorMsg = xhr.responseJSON?.message || 'Failed to create department. Please check inputs.';
                            $('#createModalAlert').removeClass('hidden').text(errorMsg);
                        },
                        complete: function() {
                            $btn.prop('disabled', false).html(originalText);
                        }
                    });
                });

                // Open Edit Modal
                $(document).on('click', '.edit-btn', function() {
                    const id = $(this).data('id');
                    const name = $(this).data('name');

                    $('#edit_department_id').val(id);
                    $('#edit_department_name').val(name);
                    $('#editModalAlert').addClass('hidden').text('');

                    openModal('editDepartmentModal');
                    setTimeout(() => $('#edit_department_name').focus(), 150);
                });

                // Edit Form Submit via AJAX (No reload)
                $('#editDepartmentForm').on('submit', function(e) {
                    e.preventDefault();
                    const deptId = $('#edit_department_id').val();
                    const $btn = $('#btnSubmitEdit');
                    const originalText = $btn.html();
                    $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Updating...');
                    $('#editModalAlert').addClass('hidden').text('');

                    $.ajax({
                        url: `/kanban/departments/${deptId}`,
                        type: 'PUT',
                        data: $(this).serialize(),
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        success: function(res) {
                            closeModal('editDepartmentModal');
                            showToast('success', res.message || 'Department updated successfully.');
                            table.ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            const errorMsg = xhr.responseJSON?.message || 'Failed to update department.';
                            $('#editModalAlert').removeClass('hidden').text(errorMsg);
                        },
                        complete: function() {
                            $btn.prop('disabled', false).html(originalText);
                        }
                    });
                });

                // Delete Confirmation with SweetAlert2
                $(document).on('click', '.delete-btn', function() {
                    const deptId = $(this).data('id');
                    const deptName = $(this).data('name');

                    Swal.fire({
                        title: 'Delete Department?',
                        text: `Are you sure you want to delete "${deptName}"? This action cannot be undone.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Yes, Delete',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/kanban/departments/${deptId}`,
                                type: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': csrfToken },
                                success: function(res) {
                                    showToast('success', res.message || 'Department deleted successfully.');
                                    table.ajax.reload(null, false);
                                },
                                error: function(xhr) {
                                    const errorMsg = xhr.responseJSON?.message || 'Failed to delete department.';
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Action Denied',
                                        text: errorMsg,
                                        confirmButtonColor: '#3b82f6'
                                    });
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
