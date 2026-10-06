<div id="issueJobModal"
    class="hidden fixed inset-0 z-[1050] overflow-y-auto bg-slate-950/75 backdrop-blur-md transition-all duration-200 flex items-center justify-center p-3 sm:p-5" style="z-index: 1050;">
    <div class="relative w-full max-w-lg mx-auto my-auto rounded-2xl bg-white dark:bg-[#111827] border border-slate-200/90 dark:border-slate-800 shadow-2xl transition-all overflow-hidden flex flex-col max-h-[90vh] animate-in fade-in zoom-in-95 duration-150">

        <!-- Header Modal (Unified ERP Style) -->
        <div class="px-6 py-4 bg-slate-50/95 dark:bg-slate-900/80 border-b border-slate-200/80 dark:border-slate-800 flex justify-between items-center flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60 flex items-center justify-center text-lg flex-shrink-0 shadow-sm kanban-modal-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 tracking-tight">Status Kendala / Blocked Issue</h3>
                        <span class="bg-rose-100 dark:bg-rose-950/80 text-rose-800 dark:text-rose-300 border border-rose-300 dark:border-rose-800/80 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                            Pemberitahuan Hambatan
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Job: <strong id="issueJobId" class="text-rose-600 dark:text-rose-400 font-bold"></strong>. Tandai jika pekerjaan lapangan terhenti/terkendala.
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeModal('issueJobModal')"
                class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-200/50 dark:hover:bg-slate-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="issueJobForm" class="flex flex-col flex-1 min-h-0 overflow-hidden">
            @csrf
            @method('PATCH')
            <input type="hidden" id="issueJobDbId" name="job_id">

            <div class="p-6 space-y-4 text-slate-800 dark:text-slate-200 flex-1 overflow-y-auto min-h-0 custom-scrollbar">
                <div class="flex items-center space-x-3 p-3.5 bg-rose-50/70 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800/60 rounded-xl cursor-pointer">
                    <input type="checkbox" name="has_issue" id="issueHasIssueCheckbox" value="1"
                        class="w-4 h-4 rounded border-rose-300 text-rose-600 focus:ring-rose-500/20">
                    <label for="issueHasIssueCheckbox" class="text-xs font-bold text-rose-900 dark:text-rose-200 cursor-pointer">
                        Tandai Job ini Sedang Mengalami Kendala (Blocked Issue)
                    </label>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Deskripsi Kendala Lapangan
                    </label>
                    <textarea name="issue_note" id="issueNote" rows="3"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/80 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition shadow-sm outline-none placeholder:text-slate-400"
                        placeholder="Contoh: Pekerjaan di-pause karena material habis di gudang, atau mesin mengalami kendala elektrikal..."></textarea>
                </div>
            </div>

            <!-- Footer Action Bar (Unified ERP Style) -->
            <div class="px-6 py-4 bg-slate-50/95 dark:bg-slate-900/80 border-t border-slate-200/80 dark:border-slate-800 flex justify-end items-center gap-2.5 flex-shrink-0">
                <button type="button" onclick="closeModal('issueJobModal')"
                    class="px-4 py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl transition shadow-sm">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-1.5">
                    <span>Simpan Status Kendala</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
