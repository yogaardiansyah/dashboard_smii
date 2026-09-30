<div id="splitJobModal"
    class="hidden fixed inset-0 z-50 overflow-y-auto backdrop-blur-xl bg-gray-900/50 transition-opacity">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-lg mx-auto p-6 rounded-lg shadow-2xl border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Pemecahan Pekerjaan (Split Job)</h3>
                <span class="text-xs bg-amber-100 text-amber-800 px-2 py-0.5 rounded font-bold uppercase">Split Operation</span>
            </div>
            
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                Membuat Job Anak dari <strong id="splitJobId" class="text-blue-600 dark:text-blue-400"></strong>.
                Item yang dipilih akan dialihkan ke Job baru dengan status awal <strong>On Hold</strong> pada departemen tujuan.
            </p>

            <form id="splitJobForm" class="space-y-4">
                @csrf
                <input type="hidden" id="splitJobDbId" name="job_id">

                <!-- Daftar Item untuk di-split -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Pilih Item yang Akan Dipisahkan:
                        </label>
                        <button type="button" id="selectAllUnfinishedBtn" class="text-[11px] text-blue-600 hover:underline">
                            Pilih Semua Belum Selesai
                        </button>
                    </div>

                    <div id="splitItemsContainer" class="max-h-48 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-md p-2 space-y-2 bg-gray-50 dark:bg-gray-700/50">
                        <p class="text-xs text-gray-400 italic">Memuat item...</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Departemen Tujuan <span class="text-red-500">*</span></label>
                        <select name="target_department_id" id="splitTargetDepartmentId" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs" required>
                            <option value="" disabled selected>Pilih Departemen</option>
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Area Tujuan (Opsional)</label>
                        <select name="target_area_id" id="splitTargetAreaId" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs">
                            <option value="">Sama dengan Job Induk</option>
                            @foreach($areas as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Alasan Pemecahan Job <span class="text-red-500">*</span></label>
                    <textarea name="reason_note" id="splitReasonNote" rows="2" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs"
                        placeholder="Contoh: 3 item dialihkan ke Engineering karena butuh perbaikan elektrikal..."></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" onclick="document.getElementById('splitJobModal').classList.add('hidden')"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition shadow text-xs">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded-md transition shadow text-xs flex items-center gap-1">
                        <span>Eksekusi Split Job</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
