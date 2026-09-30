<x-app-layout>
    @section('title', 'Manage Marsho Users')

    @include('layouts.partials.vendor.datatables-searchbuilder')
    @include('layouts.partials.vendor.select2')

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
            .pl-modal-overlay.active {
                display: flex !important;
            }
            /* Custom Select2 in Modal */
            .select2-container--default .select2-selection--single {
                background-color: #f8fafc !important;
                border: 1px solid #cbd5e1 !important;
                border-radius: 12px !important;
                height: 44px !important;
                padding: 7px 14px !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #1e293b !important;
                line-height: 28px !important;
                font-size: 14px !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 42px !important;
            }
            .select2-dropdown {
                border-radius: 12px !important;
                border: 1px solid #cbd5e1 !important;
                box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
                z-index: 9999999 !important;
            }
        </style>
    @endpush

    <div class="pl-shell mt-4">
        <!-- Hero Header -->
        <div class="pl-hero">
            <span class="pl-hero-kicker">Marsho Operations</span>
            <div class="pl-hero-title">Marsho Users</div>
            <p class="pl-hero-copy">
                Manage employee assignments to Marsho operational departments for job workflows and routing.
            </p>
            <div class="pl-toolbar">
                <button type="button" class="pl-btn pl-btn-primary" id="openCreateModalBtn">
                    <i class="fa-solid fa-user-plus mr-2"></i> Add Marsho User
                </button>
                <a href="{{ route('jobs.index') }}" class="pl-btn pl-btn-neutral">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to Kanban
                </a>
            </div>
        </div>

        <!-- Table Card -->
        <div class="pl-card">
            <div class="pl-card-head">
                <div class="pl-card-title">Employee Department Mapping</div>
                <div class="pl-card-copy">
                    Browse all registered employees. Use SearchBuilder above the table to filter by corporate department or Marsho assignment.
                </div>
            </div>
            <div class="pl-card-body">
                <div class="pl-table-shell table-responsive">
                    <table id="marshoUsersTable" class="table table-bordered table-hover w-full" style="width: 100%;">
                        <thead>
                            <tr>
                                <th class="w-16 text-center">#</th>
                                <th>Employee Details</th>
                                <th>Corporate Department</th>
                                <th>Marsho Department</th>
                                <th class="text-center w-28">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create / Assign Marsho User Modal -->
    <div id="createMarshoUserModal" class="pl-modal-overlay">
        <div class="pl-modal-panel pl-modal-panel-md">
            <div class="pl-modal-header">
                <div>
                    <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">User Assignment</span>
                    <h3 class="pl-modal-title">Add Marsho User</h3>
                </div>
                <button type="button" class="pl-modal-close" data-hide="createMarshoUserModal">&times;</button>
            </div>
            <form id="createMarshoUserForm">
                @csrf
                <div class="pl-modal-body bg-slate-50">
                    <div id="createModalAlert" class="alert alert-danger hidden mb-3 py-2 px-3 text-sm"></div>

                    <div class="form-group-custom">
                        <label for="create_user_id">Select Employee (From Users) <span class="text-red-500">*</span></label>
                        <select name="user_id" id="create_user_id" class="form-control-custom select2 w-full" style="width: 100%;" required>
                            <option value="">-- Choose Employee --</option>
                            @foreach ($allUsers as $u)
                                <option value="{{ $u->id }}">
                                    {{ $u->name }} ({{ $u->nik ? 'NIK: ' . $u->nik : 'No NIK' }} &bull; {{ $u->department->department_name ?? 'No Dept' }})
                                </option>
                            @endforeach
                        </select>
                        <span class="error-msg hidden" id="error_create_user_id"></span>
                        <p class="text-xs text-slate-400 mt-1">Select an employee from the system users table to assign them into the Marsho workflow.</p>
                    </div>

                    <div class="form-group-custom">
                        <label for="create_marsho_department_id">Marsho Department <span class="text-red-500">*</span></label>
                        <select name="marsho_department_id" id="create_marsho_department_id" class="form-control-custom" required>
                            <option value="" disabled selected>-- Select Designated Marsho Department --</option>
                            @foreach ($marshoDepartments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                            @endforeach
                        </select>
                        <span class="error-msg hidden" id="error_create_marsho_department_id"></span>
                    </div>
                </div>

                <div class="pl-modal-footer">
                    <button type="button" class="pl-btn pl-btn-neutral" data-hide="createMarshoUserModal">Cancel</button>
                    <button type="submit" class="pl-btn pl-btn-primary" id="btnSubmitCreateUser">
                        <i class="fa-solid fa-check mr-1.5"></i> Save Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Marsho User Modal -->
    <div id="editMarshoUserModal" class="pl-modal-overlay">
        <div class="pl-modal-panel pl-modal-panel-md">
            <div class="pl-modal-header">
                <div>
                    <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Maintenance</span>
                    <h3 class="pl-modal-title">Edit Marsho Department</h3>
                </div>
                <button type="button" class="pl-modal-close" data-hide="editMarshoUserModal">&times;</button>
            </div>
            <form id="editMarshoUserForm">
                @csrf
                <input type="hidden" name="user_id" id="edit_user_id">

                <div class="pl-modal-body bg-slate-50">
                    <div id="editModalAlert" class="alert alert-danger hidden mb-3 py-2 px-3 text-sm"></div>

                    <div class="form-group-custom">
                        <label>Employee Name</label>
                        <input type="text" id="edit_user_name" class="form-control-custom" readonly style="background-color: #f1f5f9; cursor: not-allowed;">
                    </div>

                    <div class="form-group-custom">
                        <label for="edit_marsho_department_id">Marsho Department <span class="text-red-500">*</span></label>
                        <select name="marsho_department_id" id="edit_marsho_department_id" class="form-control-custom">
                            <option value="">-- Remove / Unassign from Marsho --</option>
                            @foreach ($marshoDepartments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                            @endforeach
                        </select>
                        <span class="error-msg hidden" id="error_edit_marsho_department_id"></span>
                        <p class="text-xs text-slate-400 mt-1">Select the Marsho department responsible for processing jobs for this user, or choose Unassign to revoke access.</p>
                    </div>
                </div>

                <div class="pl-modal-footer">
                    <button type="button" class="pl-btn pl-btn-neutral" data-hide="editMarshoUserModal">Cancel</button>
                    <button type="submit" class="pl-btn pl-btn-primary" id="btnSubmitEditUser">
                        <i class="fa-solid fa-save mr-1.5"></i> Update Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Modal Helper
                function openModal(modalId) {
                    $('#' + modalId).addClass('active').css('display', 'flex');
                }
                function closeModal(modalId) {
                    $('#' + modalId).removeClass('active').css('display', 'none');
                }

                $(document).on('click', '[data-hide]', function() {
                    const target = $(this).data('hide');
                    closeModal(target);
                });

                $(document).on('click', '[data-close-modal]', function() {
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

                // Initialize Select2 in modal
                if ($.fn.select2) {
                    $('#create_user_id').select2({
                        dropdownParent: $('#createMarshoUserModal'),
                        placeholder: '-- Choose Employee --',
                        allowClear: true,
                        width: '100%'
                    });
                }

                // Initialize DataTable with Yajra and SearchBuilder
                const table = $('#marshoUsersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('marsho-users.index') }}',
                        type: 'GET'
                    },
                    dom: 'Qlfrtip',
                    searchBuilder: {
                        columns: [0, 1, 2, 3]
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center font-semibold w-16', orderable: false, searchable: false },
                        { data: 'name_email', name: 'name' },
                        { data: 'main_department', name: 'department.department_name' },
                        { data: 'marsho_department', name: 'marshoProfile.department.department_name' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center w-28' }
                    ],
                    order: [[1, 'asc']],
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
                    $('#createMarshoUserForm')[0].reset();
                    $('#createModalAlert').addClass('hidden').text('');
                    $('#createMarshoUserForm .error-msg').addClass('hidden').text('');
                    if ($.fn.select2) {
                        $('#create_user_id').val('').trigger('change');
                    }
                    openModal('createMarshoUserModal');
                });

                // Open Edit Modal
                $(document).on('click', '.edit-user-dept-btn', function() {
                    const userId = $(this).data('id');
                    const userName = $(this).data('name');
                    const deptId = $(this).data('dept-id');

                    $('#editModalAlert').addClass('hidden').text('');
                    $('#editMarshoUserForm .error-msg').addClass('hidden').text('');
                    $('#edit_user_id').val(userId);
                    $('#edit_user_name').val(userName);
                    $('#edit_marsho_department_id').val(deptId || '');

                    openModal('editMarshoUserModal');
                });

                // Submit Create Form via AJAX
                $('#createMarshoUserForm').on('submit', function(e) {
                    e.preventDefault();
                    const submitBtn = $('#btnSubmitCreateUser');
                    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');
                    $('#createModalAlert').addClass('hidden').text('');
                    $('#createMarshoUserForm .error-msg').addClass('hidden').text('');

                    $.ajax({
                        url: '{{ route('marsho-users.store') }}',
                        type: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        success: function(response) {
                            closeModal('createMarshoUserModal');
                            table.ajax.reload(null, false);
                            showToast('success', response.message || 'User assigned to Marsho successfully.');
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                if (errors && errors.user_id) {
                                    $('#error_create_user_id').text(errors.user_id[0]).removeClass('hidden');
                                }
                                if (errors && errors.marsho_department_id) {
                                    $('#error_create_marsho_department_id').text(errors.marsho_department_id[0]).removeClass('hidden');
                                }
                            } else {
                                $('#createModalAlert').removeClass('hidden').text(xhr.responseJSON?.message || 'Failed to assign user.');
                            }
                        },
                        complete: function() {
                            submitBtn.prop('disabled', false).html('<i class="fa-solid fa-check mr-1.5"></i> Save Assignment');
                        }
                    });
                });

                // Submit Edit Form via AJAX
                $('#editMarshoUserForm').on('submit', function(e) {
                    e.preventDefault();
                    const submitBtn = $('#btnSubmitEditUser');
                    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');
                    $('#editModalAlert').addClass('hidden').text('');
                    $('#editMarshoUserForm .error-msg').addClass('hidden').text('');

                    $.ajax({
                        url: '{{ route('marsho-users.store') }}',
                        type: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        success: function(response) {
                            closeModal('editMarshoUserModal');
                            table.ajax.reload(null, false);
                            showToast('success', response.message || 'Department assignment updated.');
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                if (errors && errors.marsho_department_id) {
                                    $('#error_edit_marsho_department_id').text(errors.marsho_department_id[0]).removeClass('hidden');
                                }
                            } else {
                                $('#editModalAlert').removeClass('hidden').text(xhr.responseJSON?.message || 'Failed to update department.');
                            }
                        },
                        complete: function() {
                            submitBtn.prop('disabled', false).html('<i class="fa-solid fa-save mr-1.5"></i> Update Assignment');
                        }
                    });
                });

                // Unassign Button Handler (SweetAlert Confirmation)
                $(document).on('click', '.unassign-user-btn', function() {
                    const userId = $(this).data('id');
                    const userName = $(this).data('name');

                    Swal.fire({
                        title: 'Unassign User?',
                        text: `Are you sure you want to remove ${userName} from the Marsho department workflow?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Yes, Unassign',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route('marsho-users.store') }}',
                                type: 'POST',
                                data: {
                                    _token: csrfToken,
                                    user_id: userId,
                                    marsho_department_id: ''
                                },
                                dataType: 'json',
                                success: function(response) {
                                    table.ajax.reload(null, false);
                                    showToast('success', response.message || 'User unassigned successfully.');
                                },
                                error: function(xhr) {
                                    Swal.fire('Error', xhr.responseJSON?.message || 'Failed to unassign user.', 'error');
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
