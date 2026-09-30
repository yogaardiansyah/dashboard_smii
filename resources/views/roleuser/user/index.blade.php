<x-app-layout>
    @section('title', 'List Users')

    @include('layouts.partials.vendor.datatables')
    @include('layouts.partials.vendor.select2')

    @push('css')
        @include('layouts.partials.roleuser_styles')
    @endpush

    <div class="pl-shell mt-4">
        <div class="pl-hero">
            <span class="pl-hero-kicker">User Maintenance</span>
            <div class="pl-hero-title">Users</div>
            <p class="pl-hero-copy">Kelola data pengguna sistem, peranan operasional, departemen terkait, serta pengaturan otentikasi login.</p>
            <div class="pl-toolbar">
                <button type="button"
                    class="pl-btn pl-btn-primary"
                    id="openCreateModalBtn">
                    <i class="fa-solid fa-user-plus mr-2"></i> Add User
                </button>
            </div>
        </div>

        <div class="pl-card">
            <div class="pl-card-head">
                <div class="pl-card-title">List Users</div>
                <div class="pl-card-copy">Menampilkan seluruh pengguna terdaftar di sistem.</div>
            </div>
            <div class="pl-card-body">
                <div class="pl-table-shell table-responsive">
                    <table id="usersTable" class="table table-bordered w-full">
                        <thead>
                            <tr>
                                <th class="w-12 text-center">#</th>
                                <th>NIK</th>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>Role</th>
                                <th class="text-center w-36">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="text-center font-medium">{{ $loop->iteration }}</td>
                                    <td class="font-medium">{{ $user->nik }}</td>
                                    <td class="font-medium">{{ $user->username }}</td>
                                    <td class="font-medium">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->position->position_name ?? '' }}</td>
                                    <td>{{ $user->department->department_name ?? '' }}</td>
                                    <td>
                                        @foreach ($user->roles as $role)
                                            <span class="badge bg-primary text-white text-xs px-2 py-1 rounded mr-1" style="background-color:#1e3a8a;">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center space-x-3">
                                            <button type="button" 
                                                class="edit-btn pl-btn pl-btn-neutral py-1.5 px-3 text-xs flex items-center"
                                                data-id="{{ $user->id }}"
                                                data-nik="{{ $user->nik }}"
                                                data-username="{{ $user->username }}"
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-position-id="{{ $user->position_id }}"
                                                data-department-id="{{ $user->department_id }}"
                                                data-roles='@json($user->roles->pluck("name"))'
                                                data-status="{{ $user->status }}"
                                                data-passwordsim="{{ $user->passwordsim }}"
                                                data-avatar-url="{{ $user->avatar ? Storage::url('public/user_avatars/' . $user->avatar) : '' }}">
                                                <i class="fa-solid fa-pencil mr-1"></i> Edit
                                            </button>
                                            <form action="{{ url('/users/' . $user->id . '/delete') }}" method="POST"
                                                class="delete-form inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="pl-btn pl-btn-neutral text-red-600 hover:bg-red-50 py-1.5 px-3 text-xs flex items-center">
                                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Create User Modal --}}
    <div id="createUserModal" class="pl-modal-overlay">
        <div class="pl-modal-panel pl-modal-panel-lg">
            <div class="pl-modal-header">
                <div>
                    <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.6); font-size: 10px;">Maintenance</span>
                    <h3 class="pl-modal-title">Create User</h3>
                </div>
                <button type="button" class="pl-modal-close" data-hide="createUserModal">&times;</button>
            </div>
            <form class="ajax-user-form" action="{{ route('users.store') }}" method="POST" id="createUserForm">
                @csrf
                <div class="pl-modal-body bg-slate-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group-custom">
                            <label>NIK <span class="text-red-500">*</span></label>
                            <input type="text" name="nik" class="form-control-custom" required placeholder="e.g. 1234">
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom">
                            <label>Username <span class="text-red-500">*</span></label>
                            <input type="text" name="username" class="form-control-custom" required placeholder="Username">
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom md:col-span-2">
                            <label>Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" class="form-control-custom" required placeholder="Full Name">
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom md:col-span-2">
                            <label>Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" class="form-control-custom" required placeholder="email@domain.com">
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom">
                            <label>Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required class="form-control-custom" placeholder="Min 8 characters">
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom">
                            <label>Position <span class="text-red-500">*</span></label>
                            <select name="position_id" required class="form-control-custom select-picker">
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}">{{ $position->position_name }}</option>
                                @endforeach
                            </select>
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom">
                            <label>Department <span class="text-red-500">*</span></label>
                            <select name="department_id" required class="form-control-custom select-picker">
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom">
                            <label>Roles <span class="text-red-500">*</span></label>
                            <select name="roles[]" id="create_roles" class="form-control-custom select2 w-full" multiple style="width: 100%" required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                            <span class="error-msg hidden"></span>
                        </div>
                    </div>
                </div>
                <div class="pl-modal-footer">
                    <button type="button" class="pl-btn pl-btn-neutral" data-hide="createUserModal">Cancel</button>
                    <button type="submit" class="pl-btn pl-btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit User Modal (Single Shared Modal) --}}
    <div id="editUserModal" class="pl-modal-overlay">
        <div class="pl-modal-panel pl-modal-panel-lg">
            <div class="pl-modal-header">
                <div>
                    <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.6); font-size: 10px;">Maintenance</span>
                    <h3 class="pl-modal-title">Edit User</h3>
                </div>
                <button type="button" class="pl-modal-close" data-hide="editUserModal">&times;</button>
            </div>
            <form action="" method="POST" class="ajax-user-form" enctype="multipart/form-data" id="editUserForm">
                @csrf
                @method('PUT')
                <div class="pl-modal-body bg-slate-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group-custom">
                            <label>NIK <span class="text-red-500">*</span></label>
                            <input type="text" name="nik" id="edit_nik" class="form-control-custom" required placeholder="NIK">
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom">
                            <label>Username <span class="text-red-500">*</span></label>
                            <input type="text" name="username" id="edit_username" class="form-control-custom" required placeholder="Username">
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom md:col-span-2">
                            <label>Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control-custom" required placeholder="Full Name">
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom md:col-span-2">
                            <label>Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="edit_email" class="form-control-custom" required placeholder="Email">
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom">
                            <label>Password <span class="text-xs text-gray-400 font-normal">(leave blank to keep current)</span></label>
                            <input type="password" name="password" id="edit_password" class="form-control-custom" placeholder="Password">
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom">
                            <label>Position <span class="text-red-500">*</span></label>
                            <select name="position_id" id="edit_position_id" required class="form-control-custom select-picker">
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}">{{ $position->position_name }}</option>
                                @endforeach
                            </select>
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom">
                            <label>Department <span class="text-red-500">*</span></label>
                            <select name="department_id" id="edit_department_id" required class="form-control-custom select-picker">
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom">
                            <label>Roles <span class="text-red-500">*</span></label>
                            <select name="roles[]" id="edit_roles" class="form-control-custom select2 w-full" multiple style="width: 100%" required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom">
                            <label>Status <span class="text-red-500">*</span></label>
                            <select name="status" id="edit_status" required class="form-control-custom select-picker">
                                <option value="active">Active</option>
                                <option value="non active">Non Active</option>
                            </select>
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom">
                            <label>Password Sim</label>
                            <input type="text" name="passwordsim" id="edit_passwordsim" class="form-control-custom" placeholder="SIM Password">
                            <span class="error-msg hidden"></span>
                        </div>
                        <div class="form-group-custom md:col-span-2 border-t pt-4 mt-2">
                            <label>Avatar</label>
                            <div class="flex items-center space-x-4 mt-2">
                                <img id="edit_avatar_preview" src="#" alt="Avatar" class="w-16 h-16 rounded-full border border-gray-200 dark:border-gray-600 object-cover hidden">
                                <input type="file" name="avatar" id="edit_avatar_input"
                                    class="form-control-custom" style="padding: 6px 12px;"
                                    onchange="previewEditAvatar()">
                            </div>
                            <span class="error-msg hidden"></span>
                        </div>
                    </div>
                </div>
                <div class="pl-modal-footer">
                    <button type="button" class="pl-btn pl-btn-neutral" data-hide="editUserModal">Cancel</button>
                    <button type="submit" class="pl-btn pl-btn-primary" style="background: #eab308; color: #fff !important;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewEditAvatar() {
            var fileInput = document.getElementById('edit_avatar_input');
            var file = fileInput.files[0];
            var reader = new FileReader();

            reader.onloadend = function () {
                var img = document.getElementById('edit_avatar_preview');
                img.src = reader.result;
                img.classList.remove('hidden');
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                var img = document.getElementById('edit_avatar_preview');
                img.src = "";
                img.classList.add('hidden');
            }
        }
    </script>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize Select2
                $('#create_roles').select2({
                    width: '100%',
                    dropdownParent: $('#createUserModal')
                });
                
                $('#edit_roles').select2({
                    width: '100%',
                    dropdownParent: $('#editUserModal')
                });

                // DataTable initialization
                var table = $('#usersTable').DataTable({
                    "lengthChange": false,
                    "pagingType": "simple_numbers",
                    "dom": 'Bfrtip',
                    "buttons": ['copy', 'csv', 'excel', 'pdf', 'print']
                });

                // Open Create modal
                $('#openCreateModalBtn').on('click', function() {
                    $('#createUserForm')[0].reset();
                    $('#create_roles').val([]).trigger('change');
                    $('#createUserForm').find('.error-msg').addClass('hidden').text('');
                    $('#createUserForm').find('input, select').removeClass('border-error');
                    $('#createUserModal').css('display', 'flex');
                });

                // Trigger edit button click
                $(document).on('click', '.edit-btn', function() {
                    var id = $(this).data('id');
                    var nik = $(this).data('nik');
                    var username = $(this).data('username');
                    var name = $(this).data('name');
                    var email = $(this).data('email');
                    var position_id = $(this).data('position-id');
                    var department_id = $(this).data('department-id');
                    var roles = $(this).data('roles'); // Expects JSON array or string
                    var status = $(this).data('status');
                    var passwordsim = $(this).data('passwordsim');
                    var avatarUrl = $(this).data('avatar-url');

                    // Clear formatting
                    $('#editUserForm').find('.error-msg').addClass('hidden').text('');
                    $('#editUserForm').find('input, select').removeClass('border-error');

                    // Populate form fields
                    $('#edit_nik').val(nik);
                    $('#edit_username').val(username);
                    $('#edit_name').val(name);
                    $('#edit_email').val(email);
                    $('#edit_position_id').val(position_id);
                    $('#edit_department_id').val(department_id);
                    $('#edit_status').val(status);
                    $('#edit_passwordsim').val(passwordsim);
                    $('#edit_password').val(''); // keep password blank

                    // Set select2 selection
                    if (typeof roles === 'string') {
                        roles = JSON.parse(roles);
                    }
                    $('#edit_roles').val(roles).trigger('change');

                    // Set avatar preview
                    if (avatarUrl) {
                        $('#edit_avatar_preview').attr('src', avatarUrl).removeClass('hidden');
                    } else {
                        $('#edit_avatar_preview').addClass('hidden').attr('src', '#');
                    }

                    // Set form action URL and row reference
                    $('#editUserForm').attr('action', '/users/' + id);
                    $('#editUserForm').data('row', $(this).closest('tr'));

                    // Show modal
                    $('#editUserModal').css('display', 'flex');
                });

                // Hide modals
                $(document).on('click', '[data-hide]', function() {
                    var target = $(this).data('hide');
                    $('#' + target).css('display', 'none');
                });

                // AJAX submit for form (Store & Update)
                $(document).on('submit', '.ajax-user-form', function(e) {
                    e.preventDefault();
                    var form = $(this);
                    var url = form.attr('action');
                    var formData = new FormData(this);

                    form.find('.error-msg').addClass('hidden').text('');
                    form.find('input, select').removeClass('border-error');

                    $.ajax({
                        url: url,
                        type: 'POST', // always POST for FormData, PUT is spoofed via _method
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                // Close modal
                                form.closest('.pl-modal-overlay').css('display', 'none');
                                form[0].reset();
                                form.find('select').trigger('change');

                                var rolesBadges = '';
                                $.each(response.data.roles, function(i, role) {
                                    rolesBadges += `<span class="badge bg-primary text-white text-xs px-2 py-1 rounded mr-1" style="background-color:#1e3a8a;">${role.name}</span>`;
                                });

                                var actionHtml = `
                                    <div class="flex items-center justify-center space-x-3">
                                        <button type="button" 
                                            class="edit-btn pl-btn pl-btn-neutral py-1.5 px-3 text-xs flex items-center"
                                            data-id="${response.data.id}"
                                            data-nik="${response.data.nik}"
                                            data-username="${response.data.username}"
                                            data-name="${response.data.name}"
                                            data-email="${response.data.email}"
                                            data-position-id="${response.data.position_id}"
                                            data-department-id="${response.data.department_id}"
                                            data-roles='${JSON.stringify(response.data.roles.map(r => r.name))}'
                                            data-status="${response.data.status}"
                                            data-passwordsim="${response.data.passwordsim || ''}"
                                            data-avatar-url="${response.data.avatar ? '/storage/user_avatars/' + response.data.avatar : ''}">
                                            <i class="fa-solid fa-pencil mr-1"></i> Edit
                                        </button>
                                        <form action="/users/${response.data.id}/delete" method="POST" class="delete-form inline">
                                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="pl-btn pl-btn-neutral text-red-600 hover:bg-red-50 py-1.5 px-3 text-xs flex items-center">
                                                <i class="fas fa-trash-alt mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                `;

                                if (form.attr('id') === 'createUserForm') {
                                    // Add to Datatable
                                    var newRowIndex = table.rows().count() + 1;
                                    var node = table.row.add([
                                        newRowIndex,
                                        response.data.nik,
                                        response.data.username,
                                        response.data.name,
                                        response.data.email,
                                        response.data.position ? response.data.position.position_name : '',
                                        response.data.department ? response.data.department.department_name : '',
                                        rolesBadges,
                                        actionHtml
                                    ]).draw(false).node();

                                    $(node).find('td:eq(0)').addClass('text-center font-medium');
                                    $(node).find('td:eq(1)').addClass('font-medium');
                                    $(node).find('td:eq(2)').addClass('font-medium');
                                    $(node).find('td:eq(3)').addClass('font-medium');
                                    $(node).find('td:eq(8)').addClass('text-center');
                                } else {
                                    // Update existing row
                                    var tr = form.data('row');
                                    var rowData = table.row(tr).data();

                                    rowData[1] = response.data.nik;
                                    rowData[2] = response.data.username;
                                    rowData[3] = response.data.name;
                                    rowData[4] = response.data.email;
                                    rowData[5] = response.data.position ? response.data.position.position_name : '';
                                    rowData[6] = response.data.department ? response.data.department.department_name : '';
                                    rowData[7] = rolesBadges;
                                    rowData[8] = actionHtml;

                                    table.row(tr).data(rowData).draw(false);
                                }
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, val) {
                                    var input = form.find('[name="' + key + '"], [name="' + key + '[]"]');
                                    input.addClass('border-error');
                                    input.closest('.form-group-custom').find('.error-msg').removeClass('hidden').text(val[0]);
                                });
                            } else {
                                Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                            }
                        }
                    });
                });

                // Delete action
                $(document).on('submit', '.delete-form', function(e) {
                    e.preventDefault();
                    var form = $(this);
                    var tr = form.closest('tr');
                    var url = form.attr('action');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#3b82f6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: form.serialize(),
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Deleted!',
                                            text: response.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });

                                        // Remove from Datatable
                                        table.row(tr).remove().draw(false);
                                    }
                                },
                                error: function(xhr) {
                                    Swal.fire('Error', 'Failed to delete user.', 'error');
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
