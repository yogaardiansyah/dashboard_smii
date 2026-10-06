<div id="agreeReviewModal"
    class="pl-modal-overlay hidden" style="z-index: 1050;">
    <div class="pl-modal-panel pl-modal-panel-md mx-auto my-auto max-h-[90vh]">

        <!-- Header Modal (Manage Areas Theme) -->
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Need Review (QA)</span>
                <h3 class="pl-modal-title">Review & Persetujuan Job</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="agreeReviewModal" onclick="closeModal('agreeReviewModal')" aria-label="Close">&times;</button>
        </div>

        <div class="pl-modal-body bg-slate-50 dark:bg-slate-900 space-y-4">
            <!-- Job Summary Card -->
            <div class="p-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 text-xs space-y-2 shadow-sm">
                <div class="flex justify-between items-center">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">ID Job:</span>
                    <span id="agreeJobId" class="font-mono font-bold text-blue-600 dark:text-blue-400 text-sm"></span>
                </div>
                <div class="flex justify-between items-center border-t border-slate-100 dark:border-slate-700 pt-2">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Jadwal Diajukan:</span>
                    <span id="agreeJobDates" class="font-medium text-slate-700 dark:text-slate-300"></span>
                </div>
                <div class="border-t border-slate-100 dark:border-slate-700 pt-2">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Rincian Deskripsi / Alasan Deviasi:</span>
                    <div id="agreeJobDesc" class="text-slate-700 dark:text-slate-300 text-xs leading-relaxed max-h-24 overflow-y-auto bg-slate-50 dark:bg-slate-900 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 custom-scrollbar whitespace-pre-line font-sans"></div>
                </div>
            </div>

            <!-- Tab Buttons (Pill Style) -->
            <div class="flex bg-slate-200/70 dark:bg-slate-800 p-1 rounded-full gap-1">
                <button type="button" id="tabAgreeBtn" onclick="switchReviewTab('agree')"
                    class="flex-1 py-1.5 px-3 text-xs font-bold rounded-full bg-white dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 shadow-sm flex items-center justify-center gap-1.5 transition">
                    <span>✓ Setujui (➔ PPIC)</span>
                </button>
                <button type="button" id="tabReturnBtn" onclick="switchReviewTab('return')"
                    class="flex-1 py-1.5 px-3 text-xs font-bold rounded-full text-slate-600 dark:text-slate-300 hover:text-rose-600 flex items-center justify-center gap-1.5 transition">
                    <span>↺ Kembalikan Catatan</span>
                </button>
            </div>

            <!-- Form Agree -->
            <form id="agreeForm" class="space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" id="agreeJobDbId" name="job_id">

                <div class="form-group-custom">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Area Lokasi <span class="text-slate-400 font-normal lowercase">(tentukan / konfirmasi area)</span>
                    </label>
                    <select name="area_id" id="agreeAreaId"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition shadow-sm outline-none">
                        <option value="">-- Tetap / Pilih Area --</option>
                        @if(isset($areas))
                            @foreach($areas as $areaId => $areaName)
                                <option value="{{ $areaId }}">{{ $areaName }}</option>
                            @endforeach
                        @endif
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1">Pilih area pekerjaan jika belum ditentukan atau memerlukan penyesuaian.</p>
                </div>

                <div class="form-group-custom">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Catatan Persetujuan QA <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                    </label>
                    <textarea name="note" id="agreeNote" rows="3"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition shadow-sm outline-none placeholder:text-slate-400"
                        placeholder="Catatan verifikasi mutu atau instruksi QA..."></textarea>
                </div>

                <div class="pl-modal-footer px-0 pb-0 pt-3 border-t border-slate-200 dark:border-slate-700 bg-transparent">
                    <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="agreeReviewModal" onclick="closeModal('agreeReviewModal')">
                        Tutup
                    </button>
                    <button type="submit" class="pl-btn pl-btn-emerald">
                        <i class="fa-solid fa-check mr-1.5"></i> Setujui ➔ To Be Scheduled
                    </button>
                </div>
            </form>

            <!-- Form Return to On Hold -->
            <form id="returnForm" class="space-y-4 hidden">
                @csrf
                @method('PATCH')
                <input type="hidden" id="returnJobDbId" name="job_id">

                <div class="form-group-custom">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Alasan Pengembalian / Catatan Revisi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" id="returnReason" rows="3" required
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition shadow-sm outline-none placeholder:text-slate-400"
                        placeholder="Sebutkan catatan spesifikasi yang belum lengkap atau alasan penolakan tiket..."></textarea>
                    <p class="text-[11px] text-slate-400 mt-1">Catatan ini akan tercatat dalam log aktivitas tiket.</p>
                </div>

                <div class="pl-modal-footer px-0 pb-0 pt-3 border-t border-slate-200 dark:border-slate-700 bg-transparent">
                    <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="agreeReviewModal" onclick="closeModal('agreeReviewModal')">
                        Tutup
                    </button>
                    <button type="submit" class="pl-btn pl-btn-rose">
                        <i class="fa-solid fa-rotate-left mr-1.5"></i> Simpan Catatan Pengembalian
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
            tabAgreeBtn.className = "flex-1 py-1.5 px-3 text-xs font-bold rounded-full bg-white dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 shadow-sm flex items-center justify-center gap-1.5 transition";
            tabReturnBtn.className = "flex-1 py-1.5 px-3 text-xs font-bold rounded-full text-slate-600 dark:text-slate-300 hover:text-rose-600 flex items-center justify-center gap-1.5 transition";
        } else {
            agreeForm.classList.add('hidden');
            returnForm.classList.remove('hidden');
            tabReturnBtn.className = "flex-1 py-1.5 px-3 text-xs font-bold rounded-full bg-white dark:bg-slate-700 text-rose-600 dark:text-rose-400 shadow-sm flex items-center justify-center gap-1.5 transition";
            tabAgreeBtn.className = "flex-1 py-1.5 px-3 text-xs font-bold rounded-full text-slate-600 dark:text-slate-300 hover:text-emerald-600 flex items-center justify-center gap-1.5 transition";
        }
    }
</script>
