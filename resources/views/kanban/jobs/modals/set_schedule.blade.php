<div id="setScheduleModal"
    class="hidden fixed inset-0 z-[1050] overflow-y-auto bg-slate-950/75 backdrop-blur-md transition-all duration-200 flex items-center justify-center p-3 sm:p-5" style="z-index: 1050;">
    <div class="relative w-full max-w-lg mx-auto my-auto rounded-2xl bg-white dark:bg-[#111827] border border-slate-200/90 dark:border-slate-800 shadow-2xl transition-all overflow-hidden flex flex-col max-h-[90vh] animate-in fade-in zoom-in-95 duration-150">

        <!-- Header Modal (Unified ERP Style) -->
        <div class="px-6 py-4 bg-slate-50/95 dark:bg-slate-900/80 border-b border-slate-200/80 dark:border-slate-800 flex justify-between items-center flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800/60 flex items-center justify-center text-lg flex-shrink-0 shadow-sm kanban-modal-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 tracking-tight">Atur Jadwal Pengerjaan</h3>
                        <span class="bg-blue-100 dark:bg-blue-950/80 text-blue-800 dark:text-blue-300 border border-blue-300 dark:border-blue-800/80 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                            Khusus Tim PPIC
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Job: <strong id="setScheduleJobId" class="text-blue-600 dark:text-blue-400 font-bold"></strong>. Tiket akan berpindah ke tahap <strong>Scheduled</strong>.
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeModal('setScheduleModal')"
                class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-200/50 dark:hover:bg-slate-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="setScheduleForm" class="flex flex-col flex-1 min-h-0 overflow-hidden">
            @csrf
            @method('PATCH')
            <input type="hidden" id="setScheduleJobDbId" name="job_id">

            <div class="p-6 space-y-4 text-slate-800 dark:text-slate-200 flex-1 overflow-y-auto min-h-0 custom-scrollbar">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="setScheduleStartDate" required value="{{ date('Y-m-d') }}"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/80 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm outline-none">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Deadline Target <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="deadline" id="setScheduleDeadline" required
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/80 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Catatan Penjadwalan PPIC <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                    </label>
                    <textarea name="note" id="setScheduleNote" rows="3"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/80 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm outline-none placeholder:text-slate-400"
                        placeholder="Instruksi jadwal, ketersediaan lini kerja, atau catatan PPIC..."></textarea>
                </div>
            </div>

            <!-- Footer Action Bar (Unified ERP Style) -->
            <div class="px-6 py-4 bg-slate-50/95 dark:bg-slate-900/80 border-t border-slate-200/80 dark:border-slate-800 flex justify-end items-center gap-2.5 flex-shrink-0">
                <button type="button" onclick="closeModal('setScheduleModal')"
                    class="px-4 py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl transition shadow-sm">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-1.5">
                    <span>Simpan Jadwal ➔ Pindahkan ke Scheduled</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
