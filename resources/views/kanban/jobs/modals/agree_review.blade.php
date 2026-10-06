<div id="agreeReviewModal"
    class="hidden fixed inset-0 z-[1050] overflow-y-auto bg-slate-950/75 backdrop-blur-md transition-all duration-200 flex items-center justify-center p-3 sm:p-5" style="z-index: 1050;">
    <div class="relative w-full max-w-xl mx-auto my-auto rounded-2xl bg-white dark:bg-[#111827] border border-slate-200/90 dark:border-slate-800 shadow-2xl transition-all overflow-hidden flex flex-col max-h-[90vh] animate-in fade-in zoom-in-95 duration-150">

        <!-- Header Modal (Unified ERP Style) -->
        <div class="px-6 py-4 bg-slate-50/95 dark:bg-slate-900/80 border-b border-slate-200/80 dark:border-slate-800 flex justify-between items-center flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-800/60 flex items-center justify-center text-lg flex-shrink-0 shadow-sm kanban-modal-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 tracking-tight">Review & Persetujuan Job</h3>
                        <span class="bg-purple-100 dark:bg-purple-950/80 text-purple-800 dark:text-purple-300 border border-purple-300 dark:border-purple-800/80 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                            Need Review (QA)
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Verifikasi rincian tiket sebelum diteruskan ke antrean penjadwalan PPIC.
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeModal('agreeReviewModal')"
                class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-200/50 dark:hover:bg-slate-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-6 space-y-4 text-slate-800 dark:text-slate-200 flex-1 overflow-y-auto min-h-0 custom-scrollbar">
            <!-- Job Summary Card -->
            <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200 dark:border-slate-800 text-xs space-y-2 shadow-sm">
                <div class="flex justify-between items-center">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">ID Job:</span>
                    <span id="agreeJobId" class="font-mono font-bold text-blue-600 dark:text-blue-400 text-sm"></span>
                </div>
                <div class="flex justify-between items-center border-t border-slate-200/60 dark:border-slate-800/80 pt-2">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Jadwal Diajukan:</span>
                    <span id="agreeJobDates" class="font-medium text-slate-700 dark:text-slate-300"></span>
                </div>
                <div class="border-t border-slate-200/60 dark:border-slate-800/80 pt-2">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Rincian Deskripsi / Alasan Deviasi:</span>
                    <div id="agreeJobDesc" class="text-slate-700 dark:text-slate-300 text-xs leading-relaxed max-h-24 overflow-y-auto bg-white dark:bg-slate-950/60 p-2.5 rounded-lg border border-slate-200/80 dark:border-slate-800 custom-scrollbar whitespace-pre-line font-sans"></div>
                </div>
            </div>

            <!-- Tab Buttons (Unified ERP Style) -->
            <div class="flex border-b border-slate-200 dark:border-slate-700 gap-2">
                <button type="button" id="tabAgreeBtn" onclick="switchReviewTab('agree')"
                    class="py-2.5 px-4 text-xs font-bold border-b-2 border-emerald-500 text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 transition">
                    <span>✓ Setujui (➔ To Be Scheduled)</span>
                </button>
                <button type="button" id="tabReturnBtn" onclick="switchReviewTab('return')"
                    class="py-2.5 px-4 text-xs font-bold border-b-2 border-transparent text-slate-500 hover:text-rose-600 flex items-center gap-1.5 transition">
                    <span>↺ Kembalikan Catatan</span>
                </button>
            </div>

            <!-- Form Agree -->
            <form id="agreeForm" class="space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" id="agreeJobDbId" name="job_id">

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Area Lokasi <span class="text-slate-400 font-normal lowercase">(tentukan / konfirmasi area)</span>
                    </label>
                    <select name="area_id" id="agreeAreaId"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/80 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition shadow-sm outline-none">
                        <option value="">-- Tetap / Pilih Area --</option>
                        @if(isset($areas))
                            @foreach($areas as $areaId => $areaName)
                                <option value="{{ $areaId }}">{{ $areaName }}</option>
                            @endforeach
                        @endif
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1">Pilih area pekerjaan jika belum ditentukan (misal job dari integrasi) atau memerlukan penyesuaian.</p>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Catatan Persetujuan QA <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                    </label>
                    <textarea name="note" id="agreeNote" rows="3"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/80 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition shadow-sm outline-none placeholder:text-slate-400"
                        placeholder="Catatan verifikasi mutu atau instruksi QA..."></textarea>
                </div>

                <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeModal('agreeReviewModal')"
                        class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/80 border border-slate-200 dark:border-slate-700 rounded-xl transition shadow-sm">
                        Tutup
                    </button>
                    <button type="submit"
                        class="px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-1.5">
                        <span>Setujui ➔ To Be Scheduled</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Form Return to On Hold -->
            <form id="returnForm" class="space-y-4 hidden">
                @csrf
                @method('PATCH')
                <input type="hidden" id="returnJobDbId" name="job_id">

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Alasan Pengembalian / Catatan Revisi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" id="returnReason" rows="3" required
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/80 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition shadow-sm outline-none placeholder:text-slate-400"
                        placeholder="Sebutkan catatan spesifikasi yang belum lengkap atau alasan penolakan tiket..."></textarea>
                    <p class="text-[11px] text-slate-400 mt-1">Catatan ini akan tercatat dalam log aktivitas tiket.</p>
                </div>

                <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeModal('agreeReviewModal')"
                        class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/80 border border-slate-200 dark:border-slate-700 rounded-xl transition shadow-sm">
                        Tutup
                    </button>
                    <button type="submit"
                        class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-1.5">
                        <span>Simpan Catatan Pengembalian</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function switchReviewTab(tab) {
        const agreeForm = document.getElementById('agreeForm');
        const returnForm = document.getElementById('returnForm');
        const tabAgreeBtn = document.getElementById('tabAgreeBtn');
        const tabReturnBtn = document.getElementById('tabReturnBtn');

        if (tab === 'agree') {
            agreeForm.classList.remove('hidden');
            returnForm.classList.add('hidden');
            tabAgreeBtn.className = "py-2.5 px-4 text-xs font-bold border-b-2 border-emerald-500 text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 transition";
            tabReturnBtn.className = "py-2.5 px-4 text-xs font-bold border-b-2 border-transparent text-slate-500 hover:text-rose-600 flex items-center gap-1.5 transition";
        } else {
            agreeForm.classList.add('hidden');
            returnForm.classList.remove('hidden');
            tabReturnBtn.className = "py-2.5 px-4 text-xs font-bold border-b-2 border-rose-500 text-rose-600 dark:text-rose-400 flex items-center gap-1.5 transition";
            tabAgreeBtn.className = "py-2.5 px-4 text-xs font-bold border-b-2 border-transparent text-slate-500 hover:text-emerald-600 flex items-center gap-1.5 transition";
        }
    }
</script>
