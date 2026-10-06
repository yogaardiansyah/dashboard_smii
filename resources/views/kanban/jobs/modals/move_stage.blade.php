<div id="moveStageModal"
    class="pl-modal-overlay hidden" style="z-index: 1050;">
    <div class="pl-modal-panel pl-modal-panel-md mx-auto my-auto">

        <!-- Header Modal (Manage Areas Theme) -->
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Transisi Status</span>
                <h3 id="moveStageTitle" class="pl-modal-title">Pindah Tahap (Move Stage)</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="moveStageModal" onclick="closeModal('moveStageModal')" aria-label="Close">&times;</button>
        </div>

        <form id="moveStageForm" class="flex flex-col flex-1 min-h-0 overflow-hidden" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <input type="hidden" id="move_job_id" name="job_id">
            <input type="hidden" id="move_target_status" name="status">

            <div class="pl-modal-body bg-slate-50 dark:bg-slate-900 space-y-4">
                <div class="p-3.5 bg-blue-50/80 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800/60 rounded-2xl text-xs text-blue-900 dark:text-blue-200">
                    <span class="font-bold">Info:</span> Memindahkan tiket ke tahap berikutnya me-reset timer SLA 3 hari kerja dan mencatat perpindahan di riwayat aktivitas.
                </div>

                <div class="form-group-custom">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Tugaskan ke Departemen <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                    </label>
                    <select name="to_department_id"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm outline-none">
                        <option value="" selected>— Tetap di Departemen Saat Ini —</option>
                        @foreach($departments as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group-custom">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Catatan / Laporan Progres <span class="text-red-500">*</span>
                    </label>
                    <textarea name="note" rows="3" required
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm outline-none placeholder:text-slate-400"
                        placeholder="Deskripsikan pekerjaan yang telah diselesaikan atau instruksi tahap lanjut..."></textarea>
                </div>

                <div class="form-group-custom">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Bukti / Dokumen Pendukung <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                    </label>
                    <input type="file" name="attachments[]" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 dark:file:bg-blue-950/50 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 transition" multiple>
                </div>
            </div>

            <!-- Footer Action Bar (Manage Areas Theme) -->
            <div class="pl-modal-footer">
                <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="moveStageModal" onclick="closeModal('moveStageModal')">
                    Batal
                </button>
                <button type="submit" class="pl-btn pl-btn-primary">
                    <i class="fa-solid fa-arrow-right mr-1.5"></i> Konfirmasi Pindah
                </button>
            </div>
        </form>
    </div>
</div>
