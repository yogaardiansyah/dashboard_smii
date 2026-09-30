<div id="setScheduleModal"
    class="hidden fixed inset-0 z-50 overflow-y-auto backdrop-blur-xl bg-gray-900/50 transition-opacity">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-md mx-auto p-6 rounded-lg shadow-2xl border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Atur Jadwal Pengerjaan</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                Job: <span id="setScheduleJobId" class="font-bold text-blue-600 dark:text-blue-400"></span>.
                Setelah jadwal diatur, tiket akan berpindah ke tahap <strong>Need Review</strong> untuk ditinjau oleh pihak terkait.
            </p>

            <form id="setScheduleForm" class="space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" id="setScheduleJobDbId" name="job_id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" id="setScheduleStartDate"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white"
                        required value="{{ date('Y-m-d') }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deadline (Target Selesai) <span class="text-red-500">*</span></label>
                    <input type="date" name="deadline" id="setScheduleDeadline"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan Penjadwalan (Opsional)</label>
                    <textarea name="note" id="setScheduleNote" rows="2"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-sm"
                        placeholder="Catatan tambahan mengenai jadwal..."></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" onclick="document.getElementById('setScheduleModal').classList.add('hidden')"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition shadow text-xs">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition shadow text-xs flex items-center gap-1">
                        <span>Ajukan ke Need Review</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
