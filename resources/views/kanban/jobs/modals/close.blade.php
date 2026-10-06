<div id="closeJobModal"
    class="hidden fixed inset-0 z-[1050] overflow-y-auto bg-slate-950/75 backdrop-blur-md transition-all duration-200 flex items-center justify-center p-3 sm:p-5" style="z-index: 1050;">
    <div class="relative w-full max-w-md mx-auto my-auto rounded-2xl bg-white dark:bg-[#111827] border border-slate-200/90 dark:border-slate-800 shadow-2xl transition-all overflow-hidden flex flex-col max-h-[90vh] animate-in fade-in zoom-in-95 duration-150">

        <!-- Header Modal (Unified ERP Style) -->
        <div class="px-6 py-4 bg-slate-50/95 dark:bg-slate-900/80 border-b border-slate-200/80 dark:border-slate-800 flex justify-between items-center flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-lg flex-shrink-0 shadow-sm kanban-modal-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 tracking-tight">Tutup & Arsipkan Job</h3>
                        <span class="bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-300 border border-slate-300 dark:border-slate-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                            Arsip Final
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Konfirmasi penutupan tiket pekerjaan setelah diverifikasi oleh pengaju.
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeModal('closeJobModal')"
                class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-200/50 dark:hover:bg-slate-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="closeJobForm" class="flex flex-col flex-1 min-h-0 overflow-hidden">
            @csrf
            <input type="hidden" id="close_job_id" name="job_id">

            <div class="p-6 space-y-4 text-slate-800 dark:text-slate-200 flex-1 overflow-y-auto min-h-0 custom-scrollbar">
                <div class="p-4 bg-amber-50/70 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 rounded-xl text-xs space-y-2">
                    <div class="flex items-center gap-2 text-amber-800 dark:text-amber-300 font-bold">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>Perhatian: Penutupan Permanen</span>
                    </div>
                    <p class="text-amber-900/90 dark:text-amber-200 leading-relaxed">
                        Apakah Anda yakin ingin menutup tiket ini? Pekerjaan akan diarsipkan ke kolom <strong>Closed</strong> dan tidak dapat diubah kembali.
                    </p>
                </div>
            </div>

            <!-- Footer Action Bar (Unified ERP Style) -->
            <div class="px-6 py-4 bg-slate-50/95 dark:bg-slate-900/80 border-t border-slate-200/80 dark:border-slate-800 flex justify-end items-center gap-2.5 flex-shrink-0">
                <button type="button" onclick="closeModal('closeJobModal')"
                    class="px-4 py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl transition shadow-sm">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-1.5">
                    <span>Ya, Tutup & Arsipkan</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
