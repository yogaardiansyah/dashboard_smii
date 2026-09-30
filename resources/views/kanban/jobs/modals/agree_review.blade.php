<div id="agreeReviewModal"
    class="hidden fixed inset-0 z-50 overflow-y-auto backdrop-blur-xl bg-gray-900/50 transition-opacity">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-lg mx-auto p-6 rounded-lg shadow-2xl border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Review & Persetujuan Job</h3>
                <span class="text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded font-bold uppercase">Need Review</span>
            </div>
            
            <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-md text-xs space-y-1 mb-4">
                <p><strong class="text-gray-700 dark:text-gray-300">ID Job:</strong> <span id="agreeJobId" class="font-bold text-blue-600 dark:text-blue-400"></span></p>
                <p><strong class="text-gray-700 dark:text-gray-300">Jadwal Diajukan:</strong> <span id="agreeJobDates"></span></p>
                <p><strong class="text-gray-700 dark:text-gray-300">Deskripsi:</strong> <span id="agreeJobDesc"></span></p>
            </div>

            <!-- Tab Buttons -->
            <div class="flex border-b border-gray-200 dark:border-gray-700 mb-4">
                <button type="button" id="tabAgreeBtn" onclick="switchReviewTab('agree')"
                    class="py-2 px-4 text-xs font-bold border-b-2 border-emerald-500 text-emerald-600 dark:text-emerald-400">
                    ✓ Setujui (Agree & Schedule)
                </button>
                <button type="button" id="tabReturnBtn" onclick="switchReviewTab('return')"
                    class="py-2 px-4 text-xs font-bold border-b-2 border-transparent text-gray-500 hover:text-rose-600">
                    ↺ Kembalikan ke On Hold
                </button>
            </div>

            <!-- Form Agree -->
            <form id="agreeForm" class="space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" id="agreeJobDbId" name="job_id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan Persetujuan (Opsional)</label>
                    <textarea name="note" id="agreeNote" rows="2"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-sm"
                        placeholder="Catatan verifikasi persetujuan..."></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" onclick="document.getElementById('agreeReviewModal').classList.add('hidden')"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition shadow text-xs">
                        Tutup
                    </button>
                    <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-md transition shadow text-xs flex items-center gap-1">
                        <span>Setujui & Jadwalkan</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Form Return to On Hold -->
            <form id="returnForm" class="space-y-4 hidden">
                @csrf
                @method('PATCH')
                <input type="hidden" id="returnJobDbId" name="job_id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Alasan Pengembalian <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" id="returnReason" rows="3" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-sm"
                        placeholder="Sebutkan alasan penolakan/pengembalian tiket (misal: tanggal bentrok, spesifikasi item belum jelas)..."></textarea>
                    <p class="text-[11px] text-gray-500 mt-1">Catatan: Tanggal yang diajukan tetap disimpan dan tidak dihapus.</p>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" onclick="document.getElementById('agreeReviewModal').classList.add('hidden')"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition shadow text-xs">
                        Tutup
                    </button>
                    <button type="submit"
                        class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded-md transition shadow text-xs flex items-center gap-1">
                        <span>Kembalikan ke On Hold</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function switchReviewTab(tab) {
        const agreeForm = document.getElementById('agreeForm');
        const returnForm = document.getElementById('returnForm');
        const tabAgreeBtn = document.getElementById('tabAgreeBtn');
        const tabReturnBtn = document.getElementById('tabReturnBtn');

        if (tab === 'agree') {
            agreeForm.classList.remove('hidden');
            returnForm.classList.add('hidden');
            tabAgreeBtn.className = "py-2 px-4 text-xs font-bold border-b-2 border-emerald-500 text-emerald-600 dark:text-emerald-400";
            tabReturnBtn.className = "py-2 px-4 text-xs font-bold border-b-2 border-transparent text-gray-500 hover:text-rose-600";
        } else {
            agreeForm.classList.add('hidden');
            returnForm.classList.remove('hidden');
            tabReturnBtn.className = "py-2 px-4 text-xs font-bold border-b-2 border-rose-500 text-rose-600 dark:text-rose-400";
            tabAgreeBtn.className = "py-2 px-4 text-xs font-bold border-b-2 border-transparent text-gray-500 hover:text-emerald-600";
        }
    }
</script>
