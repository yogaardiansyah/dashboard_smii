<div id="setScheduleModal"
    class="pl-modal-overlay hidden" style="z-index: 1050;">
    <div class="pl-modal-panel pl-modal-panel-md mx-auto my-auto">

        <!-- Header Modal (Manage Areas Theme) -->
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Khusus Tim PPIC</span>
                <h3 class="pl-modal-title">Atur Jadwal Pengerjaan</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="setScheduleModal" onclick="closeModal('setScheduleModal')" aria-label="Close">&times;</button>
        </div>

        <form id="setScheduleForm" class="flex flex-col flex-1 min-h-0 overflow-hidden">
            @csrf
            @method('PATCH')
            <input type="hidden" id="setScheduleJobDbId" name="job_id">

            <div class="pl-modal-body bg-slate-50 dark:bg-slate-900 space-y-4">
                <div class="p-3.5 bg-amber-50/80 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 rounded-2xl text-xs text-amber-900 dark:text-amber-200">
                    Job: <strong id="setScheduleJobId" class="text-amber-700 dark:text-amber-300 font-mono font-bold"></strong>. Tiket akan berpindah ke kolom <strong>Scheduled</strong>.
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group-custom">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="setScheduleStartDate" required value="{{ date('Y-m-d') }}"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm outline-none">
                    </div>

                    <div class="form-group-custom">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Deadline Target <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="deadline" id="setScheduleDeadline" required
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm outline-none">
                    </div>
                </div>

                <div class="form-group-custom">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Catatan Penjadwalan PPIC <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                    </label>
                    <textarea name="note" id="setScheduleNote" rows="3"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm outline-none placeholder:text-slate-400"
                        placeholder="Instruksi jadwal, ketersediaan lini kerja, atau catatan PPIC..."></textarea>
                </div>
            </div>

            <!-- Footer Action Bar (Manage Areas Theme) -->
            <div class="pl-modal-footer">
                <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="setScheduleModal" onclick="closeModal('setScheduleModal')">
                    Batal
                </button>
                <button type="submit" class="pl-btn pl-btn-amber">
                    <i class="fa-solid fa-calendar-check mr-1.5"></i> Simpan Jadwal ➔ Pindahkan ke Scheduled
                </button>
            </div>
        </form>
    </div>
</div>
