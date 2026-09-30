<div id="createJobModal"
    class="hidden fixed inset-0 z-50 overflow-y-auto backdrop-blur-xl bg-gray-900/50 transition-opacity">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-xl mx-auto p-6 rounded-lg shadow-2xl border border-gray-100 dark:bg-gray-800 dark:border-gray-700">

            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Create New Kanban Job</h3>
                <button type="button" onclick="document.getElementById('createJobModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
            </div>

            <form id="createJobForm" class="space-y-4" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Area <span class="text-red-500">*</span></label>
                        <select name="area_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs" required>
                            <option value="" disabled selected>Pilih Area</option>
                            @foreach($areas as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Initial Dept <span class="text-red-500">*</span></label>
                        <select name="to_department_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs" required>
                            <option value="" disabled selected>Pilih Departemen</option>
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Ringkasan Pekerjaan (Job Title/Summary) <span class="text-red-500">*</span></label>
                    <input type="text" name="list_job" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs" required placeholder="Contoh: Perbaikan Mesin Packaging Line 2">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Alasan Pekerjaan (Reason)</label>
                        <textarea name="reason_description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs" placeholder="Penjelasan latar belakang..."></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Catatan Khusus (Remark)</label>
                        <textarea name="remark" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs" placeholder="Catatan penting atau instruksi..."></textarea>
                    </div>
                </div>

                <!-- Bagian Checklist Items Dinamis -->
                <div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Rincian Item Pekerjaan (Checklist)
                        </label>
                        <button type="button" id="addNewItemInputBtn" class="text-xs font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 flex items-center gap-1">
                            <span>+ Tambah Item</span>
                        </button>
                    </div>
                    <div id="dynamicItemsWrapper" class="space-y-2 max-h-36 overflow-y-auto">
                        <div class="flex items-center gap-2 item-row">
                            <span class="text-xs text-gray-400 font-mono item-number">1.</span>
                            <input type="text" name="items[]" class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs py-1" placeholder="Nama item (contoh: Pengecekan Sensor)">
                            <button type="button" onclick="removeItemRow(this)" class="text-red-500 hover:text-red-700 text-xs px-1">✕</button>
                        </div>
                    </div>
                </div>

                <!-- Tanggal Mulai & Deadline (Opsional: Kosongkan jika ingin masuk On Hold) -->
                <div class="border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/20 rounded-md p-3">
                    <p class="text-[11px] text-amber-800 dark:text-amber-300 mb-2 font-medium">
                        💡 <em>Tips: Jika tanggal dikosongkan, Job akan otomatis masuk ke <strong>On Hold</strong> sebagai antrean draft.</em>
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Tanggal Mulai (Opsional)</label>
                            <input type="date" name="start_date" id="createStartDate" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Deadline (Opsional)</label>
                            <input type="date" name="deadline" id="createDeadline" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Lampiran / Attachments (Opsional, Max 3 File)</label>
                    <input type="file" name="attachments[]" class="block w-full text-xs text-gray-500 mt-1" multiple>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" onclick="document.getElementById('createJobModal').classList.add('hidden')"
                        class="bg-gray-500 text-white hover:bg-gray-600 font-medium py-2 px-4 rounded-md transition shadow text-xs">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition shadow text-xs">
                        Simpan Job
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('addNewItemInputBtn')?.addEventListener('click', function() {
        const wrapper = document.getElementById('dynamicItemsWrapper');
        const count = wrapper.querySelectorAll('.item-row').length + 1;
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2 item-row';
        div.innerHTML = `
            <span class="text-xs text-gray-400 font-mono item-number">${count}.</span>
            <input type="text" name="items[]" class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs py-1" placeholder="Nama item pekerjaan...">
            <button type="button" onclick="removeItemRow(this)" class="text-red-500 hover:text-red-700 text-xs px-1">✕</button>
        `;
        wrapper.appendChild(div);
    });

    function removeItemRow(btn) {
        const wrapper = document.getElementById('dynamicItemsWrapper');
        if (wrapper.querySelectorAll('.item-row').length > 1) {
            btn.closest('.item-row').remove();
            wrapper.querySelectorAll('.item-row').forEach((row, idx) => {
                row.querySelector('.item-number').textContent = `${idx + 1}.`;
            });
        }
    }
</script>
