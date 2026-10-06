<div id="closeJobModal"
    class="pl-modal-overlay hidden" style="z-index: 1050;">
    <div class="pl-modal-panel pl-modal-panel-md mx-auto my-auto">

        <!-- Header Modal (Manage Areas Theme) -->
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Arsip Final</span>
                <h3 class="pl-modal-title">Tutup & Arsipkan Job</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="closeJobModal" onclick="closeModal('closeJobModal')" aria-label="Close">&times;</button>
        </div>

        <form id="closeJobForm" class="flex flex-col flex-1 min-h-0 overflow-hidden">
            @csrf
            <input type="hidden" id="close_job_id" name="job_id">

            <div class="pl-modal-body bg-slate-50 dark:bg-slate-900 space-y-4">
                <div class="p-4 bg-amber-50/80 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 rounded-2xl text-xs space-y-2">
                    <div class="flex items-center gap-2 text-amber-800 dark:text-amber-300 font-bold">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                        <span>Perhatian: Penutupan Permanen</span>
                    </div>
                    <p class="text-amber-900/90 dark:text-amber-200 leading-relaxed">
                        Apakah Anda yakin ingin menutup tiket ini? Pekerjaan akan diarsipkan ke kolom <strong>Closed</strong> dan tidak dapat diubah kembali.
                    </p>
                </div>
            </div>

            <!-- Footer Action Bar (Manage Areas Theme) -->
            <div class="pl-modal-footer">
                <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="closeJobModal" onclick="closeModal('closeJobModal')">
                    Batal
                </button>
                <button type="submit" class="pl-btn pl-btn-primary">
                    <i class="fa-solid fa-box-archive mr-1.5"></i> Ya, Tutup & Arsipkan
                </button>
            </div>
        </form>
    </div>
</div>
