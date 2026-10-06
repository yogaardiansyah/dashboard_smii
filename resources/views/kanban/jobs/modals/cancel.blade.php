<div id="cancelJobModal"
    class="pl-modal-overlay hidden" style="z-index: 1050;">
    <div class="pl-modal-panel pl-modal-panel-md mx-auto my-auto">

        <!-- Header Modal (Manage Areas Theme) -->
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Pembatalan</span>
                <h3 class="pl-modal-title">Batalkan Tiket Pekerjaan</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="cancelJobModal" onclick="closeModal('cancelJobModal')" aria-label="Close">&times;</button>
        </div>

        <form id="cancelJobForm" class="flex flex-col flex-1 min-h-0 overflow-hidden">
            @csrf
            @method('PATCH')
            <input type="hidden" id="cancel_job_id" name="job_id">

            <div class="pl-modal-body bg-slate-50 dark:bg-slate-900 space-y-4">
                <div class="form-group-custom">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Alasan Pembatalan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" rows="3" required
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition shadow-sm outline-none placeholder:text-slate-400"
                        placeholder="Mengapa pekerjaan ini dibatalkan? (misal: pesanan dibatalkan pelanggan)..."></textarea>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Tindakan ini akan menghentikan proses dan mencatat pembatalan ke log audit.</p>
                </div>
            </div>

            <!-- Footer Action Bar (Manage Areas Theme) -->
            <div class="pl-modal-footer">
                <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="cancelJobModal" onclick="closeModal('cancelJobModal')">
                    Pertahankan Job
                </button>
                <button type="submit" class="pl-btn pl-btn-rose">
                    <i class="fa-solid fa-ban mr-1.5"></i> Ya, Batalkan Job
                </button>
            </div>
        </form>
    </div>
</div>
