<div id="reReviewModal"
    class="pl-modal-overlay hidden" style="z-index: 1050;">
    <div class="pl-modal-panel pl-modal-panel-md mx-auto my-auto">

        <!-- Header Modal (Manage Areas Theme) -->
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Re-Review</span>
                <h3 class="pl-modal-title">Tinjau Ulang Tiket</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="reReviewModal" onclick="closeModal('reReviewModal')" aria-label="Close">&times;</button>
        </div>

        <form id="reReviewForm" class="flex flex-col flex-1 min-h-0 overflow-hidden">
            @csrf
            @method('PATCH')
            <input type="hidden" id="reReviewJobDbId" name="job_id">

            <div class="pl-modal-body bg-slate-50 dark:bg-slate-900 space-y-4">
                <div class="p-3.5 bg-purple-50/80 dark:bg-purple-950/30 border border-purple-200 dark:border-purple-800/60 rounded-2xl text-xs text-purple-900 dark:text-purple-200">
                    <p class="mb-1">
                        Job: <strong id="reReviewJobId" class="text-purple-600 dark:text-purple-400 font-mono font-bold"></strong>. Dimundurkan ke tahap <strong>Need Review</strong>.
                    </p>
                    <span class="font-bold">Info:</span> Jadwal tanggal yang sudah ada tetap dipertahankan saat tiket ditinjau ulang oleh tim terkait.
                </div>

                <div class="form-group-custom">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Alasan Peninjauan Ulang <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" id="reReviewReason" rows="3" required
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition shadow-sm outline-none placeholder:text-slate-400"
                        placeholder="Jelaskan kebutuhan revisi atau hal yang mendasari peninjauan ulang..."></textarea>
                </div>
            </div>

            <!-- Footer Action Bar (Manage Areas Theme) -->
            <div class="pl-modal-footer">
                <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="reReviewModal" onclick="closeModal('reReviewModal')">
                    Batal
                </button>
                <button type="submit" class="pl-btn pl-btn-purple">
                    <i class="fa-solid fa-rotate-left mr-1.5"></i> Ajukan Re-Review
                </button>
            </div>
        </form>
    </div>
</div>
