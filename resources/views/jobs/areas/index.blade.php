<x-app-layout>
     @section('title')
        Job Areas
    @endsection

    @include('layouts.partials.vendor.datatables')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Job Areas') }}
        </h2>
    </x-slot>

    <div class=" py-12">
        <div class="card mx-auto sm:px-6 lg:px-8">

            {{-- Komponen Alpine utama, logika tidak berubah --}}
            <div x-data="{ newArea: { name: '', description: '' }, isModalOpen: false, editArea: { id: null, name: '', description: '' },
                showSuccessToast(message) { const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true, didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); } }); Toast.fire({ icon: 'success', title: message }); },
                showErrorAlert(message) { Swal.fire({ icon: 'error', title: 'An Error Occurred', text: message, confirmButtonColor: '#d33' }); },
                async addArea() { const response = await fetch('{{ route('areas.store') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify(this.newArea) }); const data = await response.json(); if (response.ok) { this.newArea = { name: '', description: '' }; this.showSuccessToast(data.message); window.reloadAreasTable && window.reloadAreasTable(); } else { this.showErrorAlert(data.message || 'Failed to add the area.'); } },
                closeModal() { this.isModalOpen = false; },
                async updateArea() { const response = await fetch(`/areas/${this.editArea.id}`, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify(this.editArea) }); const data = await response.json(); if (response.ok) { this.closeModal(); this.showSuccessToast(data.message); window.reloadAreasTable && window.reloadAreasTable(); } else { this.showErrorAlert(data.message || 'Failed to update the area.'); } }
            }" @open-edit.window="editArea = $event.detail; isModalOpen = true" class="overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <!-- Form untuk Menambah Area Baru -->
                <form @submit.prevent="addArea()" class="mb-8">
                    <h3 class="text-lg font-medium  mb-4">Marsho JobBoard Area</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div>
                            <label for="new_name" class="block text-sm font-medium">Area Name</label>
                            <input type="text" id="new_name" x-model="newArea.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-black" required>
                        </div>
                        <div>
                            <label for="new_description" class="block text-sm font-medium">Description</label>
                            <input type="text" id="new_description" x-model="newArea.description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-black">
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out">Add Area</button>
                        </div>
                    </div>
                </form>

                <!-- Tabel untuk Menampilkan Daftar Area (Yajra DataTables) -->
                <div class="mt-6 overflow-x-auto">
                    <table id="areas-table" class="min-w-full w-full display table table-bordered table-hovered" style="width:100%">
                        <thead class="bg-blue-500 ">
                            <tr>
                                <th class="px-6 py-3 text-left text-lg font-medium text-white uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-lg font-medium text-white uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-right text-lg font-medium text-white uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Modal untuk Edit Area -->
                <div x-show="isModalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
                    <div @click.away="closeModal()" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                        <h3 class="text-lg font-medium mb-4 text-black">Edit Area</h3>
                        <form @submit.prevent="updateArea()">
                            <div class="space-y-4">
                                <div>
                                    <label for="edit_name" class="block text-sm font-medium text-gray-700">Area Name</label>
                                    <input type="text" id="edit_name" x-model="editArea.name" class="mt-1 block w-full rounded-md border-gray-300 bg-white " required>
                                </div>
                                <div>
                                    <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <input type="text" id="edit_description" x-model="editArea.description" class="mt-1 block w-full rounded-md border-gray-300 bg-white ">
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
        // Helper to reload the DataTable from anywhere
        window.reloadAreasTable = function() {
            if (window._areasTable) window._areasTable.ajax.reload(null, false);
        }

        $(document).ready(function() {
            window._areasTable = $('#areas-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('areas.index') }}',
                    type: 'GET'
                },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                order: [[0, 'desc']]
            });

            // Edit button handler (delegated)
            $(document).on('click', '.edit-btn', function() {
                const detail = { id: $(this).data('id'), name: $(this).data('name'), description: $(this).data('description') };
                window.dispatchEvent(new CustomEvent('open-edit', { detail }));
            });

            // Delete button handler (delegated)
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                Swal.fire({ title: 'Are you sure?', text: 'This action cannot be undone!', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, delete it!' }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/areas/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(response => {
                            if (response.ok) {
                                Swal.fire({ icon: 'success', title: 'Deleted', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
                                window.reloadAreasTable();
                            } else {
                                response.json().then(data => { Swal.fire('Error', data.message || 'Failed to delete the area.', 'error'); });
                            }
                        }).catch(err => { Swal.fire('Error', 'Network error', 'error'); });
                    }
                });
            });
        });
    </script> 
    @endpush
    
</x-app-layout>