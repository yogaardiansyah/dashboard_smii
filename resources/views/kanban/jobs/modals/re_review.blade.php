<div id="reReviewModal"
    class="hidden fixed inset-0 z-50 overflow-y-auto backdrop-blur-xl bg-gray-900/50 transition-opacity">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-md mx-auto p-6 rounded-lg shadow-2xl border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Tinjau Ulang Tiket (Re-Review)</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                Job: <span id="reReviewJobId" class="font-bold text-purple-600 dark:text-purple-400"></span>.
                Tiket akan dimundurkan ke tahap <strong>Need Review</strong> untuk evaluasi ulang.
                <span class="block mt-1 text-emerald-600 dark:text-emerald-400 font-medium">Catatan: Jadwal tanggal yang sudah ada tetap dipertahankan.</span>
            </p>

            <form id="reReviewForm" class="space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" id="reReviewJobDbId" name="job_id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Alasan Peninjauan Ulang <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" id="reReviewReason" rows="3" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-sm"
                        placeholder="Jelaskan kendala atau hal yang mendasari peninjauan ulang..."></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" onclick="document.getElementById('reReviewModal').classList.add('hidden')"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition shadow text-xs">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-md transition shadow text-xs flex items-center gap-1">
                        <span>Ajukan Re-Review</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0019 16V8a1 1 0 00-1.6-.8l-5.333 4zM4.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0011 16V8a1 1 0 00-1.6-.8l-5.334 4z"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
