<x-app-layout>
    @section('title')
        List Permissions
    @endsection

    @include('layouts.partials.vendor.datatables')

    @push('css')
        @include('layouts.partials.roleuser_styles')
    @endpush
    
    <div class="pl-shell mt-4">
        <div class="pl-hero">
            <span class="pl-hero-kicker">Permission Maintenance</span>
            <div class="pl-hero-title">Permission</div>
            <p class="pl-hero-copy">Kelola hak akses dan kewenangan (permissions) sistem untuk melindungi integritas data operasional.</p>
            <div class="pl-toolbar">
                <button type="button"
                    class="pl-btn pl-btn-primary"
                    id="openCreateModalBtn">
                    <i class="fa-solid fa-plus mr-2"></i> Add Permission
                </button>
            </div>
        </div>

        <div class="pl-card">
            <div class="pl-card-head">
                <div class="pl-card-title">List Permissions</div>
                <div class="pl-card-copy">Menampilkan seluruh daftar hak akses (permissions) terdaftar.</div>
            </div>
            <div class="pl-card-body">
                <div class="pl-table-shell table-responsive">
                    <table id="permissionsTable" class="table table-bordered w-full">
                        <thead>
                            <tr>
                                <th class="w-16 text-center">#</th>
                                <th>Nama Permission</th>
                                <th class="text-center w-40">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td class="text-center font-medium">{{ $loop->iteration }}</td>
                                    <td class="font-medium">{{ $permission->name }}</td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center space-x-3">
                                            <button type='button'
                                                data-id="{{ $permission->id }}"
                                                data-name="{{ $permission->name }}"
                                                class="edit-btn pl-btn pl-btn-neutral py-1.5 px-3 text-xs flex items-center">
                                                <i class="fa-solid fa-pencil mr-1"></i> Edit
                                            </button>
                                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST"
                                                class="delete-form inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="pl-btn pl-btn-neutral text-red-600 hover:bg-red-50 py-1.5 px-3 text-xs flex items-center">
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

    {{-- Modal Create --}}
    <div id="createPermissionModal" class="pl-modal-overlay">
        <div class="pl-modal-panel">
            <div class="pl-modal-header">
                <div>
                    <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.6); font-size: 10px;">Maintenance</span>
                    <h3 class="pl-modal-title">Create Permission</h3>
                </div>
                <button type="button" class="pl-modal-close" data-hide="createPermissionModal">&times;</button>
            </div>
            <form class="ajax-form" id="createPermissionForm" action="{{ route('permissions.store') }}" method="POST">
                @csrf
                <div class="pl-modal-body">
                    <div class="form-group-custom">
                        <label for="name">Nama Permission</label>
                        <input type="text" name="name" id="name"
                            class="form-control-custom"
                            placeholder="e.g. edit articles, delete users" required="">
                        <span class="error-msg hidden"></span>
                    </div>
                </div>
                <div class="pl-modal-footer">
                    <button type="button" class="pl-btn pl-btn-neutral" data-hide="createPermissionModal">Cancel</button>
                    <button type="submit" class="pl-btn pl-btn-primary">Create Permission</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit (Single Shared Modal) --}}
    <div id="editPermissionModal" class="pl-modal-overlay">
        <div class="pl-modal-panel">
            <div class="pl-modal-header">
                <div>
                    <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.6); font-size: 10px;">Maintenance</span>
                    <h3 class="pl-modal-title">Edit Permission</h3>
                </div>
                <button type="button" class="pl-modal-close" data-hide="editPermissionModal">&times;</button>
            </div>
            <form class="ajax-form" id="editPermissionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="pl-modal-body">
                    <div class="form-group-custom">
                        <label for="edit_name">Nama Permission</label>
                        <input type="text" name="name" id="edit_name"
                            class="form-control-custom" required="">
                        <span class="error-msg hidden"></span>
                    </div>
                </div>
                <div class="pl-modal-footer">
                    <button type="button" class="pl-btn pl-btn-neutral" data-hide="editPermissionModal">Cancel</button>
                    <button type="submit" class="pl-btn pl-btn-primary" style="background: #eab308; color: #fff !important;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#permissionsTable').DataTable({
                "lengthChange": false,
                "pagingType": "simple_numbers",
                "dom": 'Bfrtip',
                "buttons": ['copy', 'csv', 'excel', 'pdf', 'print']
            });

            // Open Create modal
            $('#openCreateModalBtn').on('click', function() {
                $('#createPermissionForm')[0].reset();
                $('#createPermissionForm').find('.error-msg').addClass('hidden').text('');
                $('#createPermissionForm').find('input').removeClass('border-error');
                $('#createPermissionModal').css('display', 'flex');
            });

            // Handle edit button click
            $(document).on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');

                // Clear formatting
                $('#editPermissionForm').find('.error-msg').addClass('hidden').text('');
                $('#editPermissionForm').find('input').removeClass('border-error');

                // Populate fields
                $('#edit_name').val(name);
                $('#editPermissionForm').attr('action', '/permissions/' + id);
                $('#editPermissionForm').data('row', $(this).closest('tr'));

                // Open modal
                $('#editPermissionModal').css('display', 'flex');
            });

            // Close modal events
            $(document).on('click', '[data-hide]', function() {
                var target = $(this).data('hide');
                $('#' + target).css('display', 'none');
            });

            // AJAX Form Submit (Store & Update)
            $(document).on('submit', '.ajax-form', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var method = form.find('input[name="_method"]').val() || form.attr('method');
                var data = form.serialize();

                form.find('.error-msg').addClass('hidden').text('');
                form.find('input').removeClass('border-error');

                $.ajax({
                    url: url,
                    type: method,
                    data: data,
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

                            if (form.attr('id') === 'createPermissionForm') {
                                // Add row to datatable
                                var newRowIndex = table.rows().count() + 1;
                                var actionHtml = `
                                    <div class="flex items-center justify-center space-x-3">
                                        <button type="button"
                                            data-id="${response.data.id}"
                                            data-name="${response.data.name}"
                                            class="edit-btn pl-btn pl-btn-neutral py-1.5 px-3 text-xs flex items-center">
                                            <i class="fa-solid fa-pencil mr-1"></i> Edit
                                        </button>
                                        <form action="/permissions/${response.data.id}" method="POST" class="delete-form inline">
                                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="pl-btn pl-btn-neutral text-red-600 hover:bg-red-50 py-1.5 px-3 text-xs flex items-center">
                                                <i class="fas fa-trash-alt mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                `;

                                var node = table.row.add([
                                    newRowIndex,
                                    response.data.name,
                                    actionHtml
                                ]).draw(false).node();

                                $(node).find('td:eq(0)').addClass('text-center font-medium');
                                $(node).find('td:eq(1)').addClass('font-medium');
                                $(node).find('td:eq(2)').addClass('text-center');
                            } else {
                                // Update row in datatable
                                var tr = form.data('row');
                                var rowData = table.row(tr).data();
                                
                                rowData[1] = response.data.name;
                                
                                var actionHtml = `
                                    <div class="flex items-center justify-center space-x-3">
                                        <button type="button"
                                            data-id="${response.data.id}"
                                            data-name="${response.data.name}"
                                            class="edit-btn pl-btn pl-btn-neutral py-1.5 px-3 text-xs flex items-center">
                                            <i class="fa-solid fa-pencil mr-1"></i> Edit
                                        </button>
                                        <form action="/permissions/${response.data.id}" method="POST" class="delete-form inline">
                                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="pl-btn pl-btn-neutral text-red-600 hover:bg-red-50 py-1.5 px-3 text-xs flex items-center">
                                                <i class="fas fa-trash-alt mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                `;
                                rowData[2] = actionHtml;
                                
                                table.row(tr).data(rowData).draw(false);
                            }
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, val) {
                                var input = form.find('[name="' + key + '"]');
                                input.addClass('border-error');
                                input.siblings('.error-msg').removeClass('hidden').text(val[0]);
                            });
                        } else {
                            Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                        }
                    }
                });
            });

            // AJAX Delete Form Submit
            $(document).on('submit', '.delete-form', function(e) {
                e.preventDefault();
                var form = $(this);
                var tr = form.closest('tr');
                var url = form.attr('action');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#3b82f6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
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
                                Swal.fire('Error', 'Gagal menghapus data.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
