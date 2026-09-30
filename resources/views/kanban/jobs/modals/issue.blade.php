<div id="issueJobModal"
    class="hidden fixed inset-0 z-50 overflow-y-auto backdrop-blur-xl bg-gray-900/50 transition-opacity">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-md mx-auto p-6 rounded-lg shadow-2xl border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Status Kendala / Issue Pekerjaan</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                Job: <span id="issueJobId" class="font-bold text-rose-600 dark:text-rose-400"></span>.
                Gunakan ini jika pekerjaan fisik lapangan terhenti/terkendala (misal: kehabisan material, alat rusak, atau menunggu konfirmasi).
            </p>

            <form id="issueJobForm" class="space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" id="issueJobDbId" name="job_id">

                <div class="flex items-center space-x-2 p-3 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-md">
                    <input type="checkbox" name="has_issue" id="issueHasIssueCheckbox" value="1"
                        class="rounded border-rose-300 text-rose-600 shadow-sm focus:border-rose-300 focus:ring focus:ring-rose-200">
                    <label for="issueHasIssueCheckbox" class="text-xs font-bold text-rose-800 dark:text-rose-300 cursor-pointer">
                        Tandai Job ini Sedang Mengalami Kendala (Blocked/Issue)
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Deskripsi Kendala</label>
                    <textarea name="issue_note" id="issueNote" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs"
                        placeholder="Contoh: Pekerjaan di-pause karena material sparepart habis di gudang..."></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" onclick="document.getElementById('issueJobModal').classList.add('hidden')"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition shadow text-xs">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded-md transition shadow text-xs">
                        Simpan Status Kendala
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
