<x-app-layout>

    @section('title')
        Job Departments
    @endsection

    @include('layouts.partials.vendor.datatables')
    {{-- CDN Libraries --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Marsho Departments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            {{-- Komponen Alpine utama: otak dari seluruh interaktivitas halaman --}}
            <div x-data="{ newDepartment: { department_name: '' }, isModalOpen: false, editDepartment: { id: null, department_name: '' },
                showSuccessToast(message) { const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true, didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); } }); Toast.fire({ icon: 'success', title: message }); },
                showErrorAlert(message) { Swal.fire({ icon: 'error', title: 'An Error Occurred', text: message, confirmButtonColor: '#d33' }); },
                async addDepartment() { const response = await fetch('{{ route('marsho-departments.store') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify(this.newDepartment) }); const data = await response.json(); if (response.ok) { this.newDepartment.department_name = ''; this.showSuccessToast(data.message); window.reloadDepartmentsTable && window.reloadDepartmentsTable(); } else { this.showErrorAlert(data.message || 'Failed to add department.'); } },
                closeModal() { this.isModalOpen = false; },
                async updateDepartment() { const response = await fetch(`/marsho-departments/${this.editDepartment.id}`, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify(this.editDepartment) }); const data = await response.json(); if (response.ok) { this.closeModal(); this.showSuccessToast(data.message); window.reloadDepartmentsTable && window.reloadDepartmentsTable(); } else { this.showErrorAlert(data.message || 'Failed to update department.'); } }
            }" @open-edit.window="editDepartment = $event.detail; isModalOpen = true" class="overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <!-- Form Add New (dikontrol oleh Alpine) -->
                <form @submit.prevent="addDepartment()" class="mb-8">
                    <h3 class="text-2xl font-medium text-gray-900 mb-4">Marsho JobBoard Departments</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div class="md:col-span-2">
                            <label for="department_name" class="block text-sm font-medium text-gray-700">Department Name</label>
                            <input type="text" x-model="newDepartment.department_name" id="department_name" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 bg-white shadow-sm" required>
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition">Add Department</button>
                        </div>
                    </div>
                </form>

                <!-- Tabel Data (Yajra DataTables) -->
                <div class="mt-6 overflow-x-auto">
                    <table id="departments-table" class="min-w-full w-full display table table-bordered table-hovered" style="width:100%">
                        <thead class="bg-blue-500">
                            <tr>
                                <th class="px-6 py-3 text-left text-lg font-medium text-white uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-right text-lg font-medium text-white uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                {{-- Pagination dihapus karena tidak lagi relevan dalam arsitektur AJAX ini --}}

                <!-- Modal Edit (dikontrol oleh Alpine) -->
                <div x-show="isModalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
                    <div @click.away="closeModal()" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                        <h3 class="text-lg font-medium mb-4 text-black">Edit Department</h3>
                        <form @submit.prevent="updateDepartment()">
                            <div class="space-y-4">
                                <div>
                                    <label for="edit_department_name" class="block text-sm font-medium text-gray-700">Department Name</label>
                                    <input type="text" id="edit_department_name" x-model="editDepartment.department_name" class="mt-1 block w-full rounded-md border-gray-300 bg-white " required>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end space-x-4">
                                <button type="button" @click="closeModal()" class="modal-cancel-button bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-md transition">Cancel</button>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        window.reloadDepartmentsTable = function() {
            if (window._departmentsTable) window._departmentsTable.ajax.reload(null, false);
        }

        $(document).ready(function() {
            window._departmentsTable = $('#departments-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: { url: '{{ route('marsho-departments.index') }}', type: 'GET' },
                columns: [
                    { data: 'department_name', name: 'department_name' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                order: [[0, 'desc']]
            });

            $(document).on('click', '.edit-btn', function() {
                const detail = { id: $(this).data('id'), department_name: $(this).data('name') };
                window.dispatchEvent(new CustomEvent('open-edit', { detail }));
            });

            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                Swal.fire({ title: 'Are you sure?', text: 'This action cannot be undone!', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, delete it!' }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/marsho-departments/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(response => {
                            if (response.ok) {
                                Swal.fire({ icon: 'success', title: 'Deleted', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
                                window.reloadDepartmentsTable();
                            } else {
                                response.json().then(data => { Swal.fire('Error', data.message || 'Failed to delete the department.', 'error'); });
                            }
                        }).catch(err => { Swal.fire('Error', 'Network error', 'error'); });
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>