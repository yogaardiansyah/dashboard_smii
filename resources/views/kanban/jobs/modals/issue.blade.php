<div id="issueJobModal"
    class="pl-modal-overlay hidden" style="z-index: 1050;">
    <div class="pl-modal-panel pl-modal-panel-md mx-auto my-auto">

        <!-- Header Modal (Manage Areas Theme) -->
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Pemberitahuan Hambatan</span>
                <h3 class="pl-modal-title">Status Kendala / Blocked Issue</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="issueJobModal" onclick="closeModal('issueJobModal')" aria-label="Close">&times;</button>
        </div>

        <form id="issueJobForm" class="flex flex-col flex-1 min-h-0 overflow-hidden">
            @csrf
            @method('PATCH')
            <input type="hidden" id="issueJobDbId" name="job_id">

            <div class="pl-modal-body bg-slate-50 dark:bg-slate-900 space-y-4">
                <div class="p-3.5 bg-rose-50/80 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 rounded-2xl">
                    <p class="text-xs text-slate-600 dark:text-slate-300">
                        Job: <strong id="issueJobId" class="text-rose-600 dark:text-rose-400 font-mono font-bold"></strong>. Tandai jika pekerjaan lapangan terhenti/terkendala.
                    </p>
                </div>

                <div class="flex items-center space-x-3 p-3.5 bg-white dark:bg-slate-800 border border-rose-200 dark:border-rose-800/60 rounded-xl cursor-pointer">
                    <input type="checkbox" name="has_issue" id="issueHasIssueCheckbox" value="1"
                        class="w-4 h-4 rounded border-rose-300 text-rose-600 focus:ring-rose-500/20">
                    <label for="issueHasIssueCheckbox" class="text-xs font-bold text-rose-900 dark:text-rose-200 cursor-pointer">
                        Tandai Job ini Sedang Mengalami Kendala (Blocked Issue)
                    </label>
                </div>

                <div class="form-group-custom">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Deskripsi Kendala Lapangan
                    </label>
                    <textarea name="issue_note" id="issueNote" rows="3"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition shadow-sm outline-none placeholder:text-slate-400"
                        placeholder="Contoh: Pekerjaan di-pause karena material habis di gudang, atau mesin mengalami kendala elektrikal..."></textarea>
                </div>
            </div>

            <!-- Footer Action Bar (Manage Areas Theme) -->
            <div class="pl-modal-footer">
                <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="issueJobModal" onclick="closeModal('issueJobModal')">
                    Batal
                </button>
                <button type="submit" class="pl-btn pl-btn-rose">
                    <i class="fa-solid fa-triangle-exclamation mr-1.5"></i> Simpan Status Kendala
                </button>
            </div>
        </form>
    </div>
</div>
