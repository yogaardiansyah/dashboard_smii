<div id="forwardJobModal"
    class="pl-modal-overlay hidden" style="z-index: 1050;">
    <div class="pl-modal-panel pl-modal-panel-md mx-auto my-auto">

        <!-- Header Modal (Manage Areas Theme) -->
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Antar Departemen</span>
                <h3 class="pl-modal-title">Teruskan Pekerjaan (Forward)</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="forwardJobModal" onclick="closeModal('forwardJobModal')" aria-label="Close">&times;</button>
        </div>

        <form id="forwardJobForm" class="flex flex-col flex-1 min-h-0 overflow-hidden" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="forward_job_id" name="job_id">

            <div class="pl-modal-body bg-slate-50 dark:bg-slate-900 space-y-4">
                <div class="form-group-custom">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Departemen Tujuan Baru <span class="text-red-500">*</span>
                    </label>
                    <select name="to_department_id" required
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-sm outline-none">
                        <option value="" disabled selected>Pilih Departemen Tujuan</option>
                        @foreach($departments as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group-custom">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Catatan Instruksi Pengalihan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="note" rows="3" required
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-sm outline-none placeholder:text-slate-400"
                        placeholder="Jelaskan bagian pekerjaan yang harus dilanjutkan oleh departemen baru..."></textarea>
                </div>

                <div class="form-group-custom">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Lampiran Tambahan <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                    </label>
                    <input type="file" name="attachments[]" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 dark:file:bg-amber-950/50 file:text-amber-700 dark:file:text-amber-300 hover:file:bg-amber-100 transition" multiple>
                </div>
            </div>

            <!-- Footer Action Bar (Manage Areas Theme) -->
            <div class="pl-modal-footer">
                <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="forwardJobModal" onclick="closeModal('forwardJobModal')">
                    Batal
                </button>
                <button type="submit" class="pl-btn pl-btn-amber">
                    <i class="fa-solid fa-share mr-1.5"></i> Teruskan Pekerjaan
                </button>
            </div>
        </form>
    </div>
</div>
