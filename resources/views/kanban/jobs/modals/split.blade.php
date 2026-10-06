<div id="splitJobModal"
    class="hidden fixed inset-0 z-[1050] overflow-y-auto bg-slate-950/75 backdrop-blur-md transition-all duration-200 flex items-center justify-center p-3 sm:p-5" style="z-index: 1050;">
    <div class="relative w-full max-w-3xl mx-auto my-auto rounded-2xl bg-white dark:bg-[#111827] border border-slate-200/90 dark:border-slate-800 shadow-2xl transition-all overflow-hidden flex flex-col max-h-[90vh] animate-in fade-in zoom-in-95 duration-150">

        <!-- Header Modal (Unified ERP Style) -->
        <div class="px-6 py-4 bg-slate-50/95 dark:bg-slate-900/80 border-b border-slate-200/80 dark:border-slate-800 flex justify-between items-center flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800/60 flex items-center justify-center text-lg flex-shrink-0 shadow-sm kanban-modal-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 tracking-tight">Pemecahan Pekerjaan (Split Job)</h3>
                        <span class="bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800/80 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                            Need Review Default
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Membuat Job Anak dari <strong id="splitJobId" class="text-blue-600 dark:text-blue-400 font-bold"></strong>. Item terpilih akan dipindahkan ke tiket baru.
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeModal('splitJobModal')"
                class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-200/50 dark:hover:bg-slate-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Form Split Job -->
        <form id="splitJobForm" class="flex flex-col flex-1 min-h-0 overflow-hidden">
            @csrf
            <input type="hidden" id="splitJobDbId" name="job_id">

            <div class="p-6 space-y-4 flex-1 overflow-y-auto min-h-0 custom-scrollbar text-slate-800 dark:text-slate-200">
                
                <!-- Grid 1: Departemen Tujuan & Area Tujuan (Sama seperti Tambah Job) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Departemen Tujuan <span class="text-red-500">*</span>
                        </label>
                        <select name="target_department_id" id="splitTargetDepartmentId" required
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/80 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-sm outline-none">
                            <option value="" disabled selected>Pilih Departemen Tujuan</option>
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Area Lokasi Tujuan <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                        </label>
                        <select name="target_area_id" id="splitTargetAreaId"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/80 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-sm outline-none">
                            <option value="">Sama dengan Job Induk</option>
                            @foreach($areas as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Grid 2: Ringkasan Job Anak (Job Title) -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Ringkasan Job Anak (Job Title Preview)
                    </label>
                    <input type="text" id="splitChildTitlePreview" name="child_title_preview" readonly
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-100/70 dark:bg-slate-800/40 text-slate-600 dark:text-slate-300 text-xs px-3.5 py-2.5 outline-none cursor-default font-mono"
                        placeholder="Otomatis di-generate dari item terpilih...">
                </div>

                <!-- Grid 3: Alasan & Catatan Khusus (Sama seperti Tambah Job) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Alasan Pemecahan Job (Reason) <span class="text-red-500">*</span>
                        </label>
                        <textarea name="reason_note" id="splitReasonNote" rows="2" required
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/80 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-sm outline-none placeholder:text-slate-400"
                            placeholder="Jelaskan kebutuhan pengalihan (contoh: 2 item dialihkan ke Engineering karena butuh perbaikan mesin)..."></textarea>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Catatan Khusus (Remark Tambahan)
                        </label>
                        <textarea name="remark" id="splitRemark" rows="2"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/80 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-sm outline-none placeholder:text-slate-400"
                            placeholder="Catatan tambahan untuk tim pelaksana di departemen tujuan..."></textarea>
                    </div>
                </div>

                <!-- Bagian Item Pekerjaan & Checklist Lot (Sama seperti Tambah Job) -->
                <div class="border border-amber-200 dark:border-amber-900/60 rounded-xl p-4 bg-amber-50/40 dark:bg-amber-950/20 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                <span>Pilih Item Pekerjaan & Lot yang Akan Dipisahkan</span>
                            </label>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                Pilih item dari tiket induk yang akan dialihkan ke tiket job anak.
                            </span>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button type="button" id="selectAllUnfinishedBtn"
                                class="text-xs font-semibold text-amber-700 dark:text-amber-300 hover:text-amber-900 dark:hover:text-amber-100 border border-amber-300 dark:border-amber-700 bg-white dark:bg-slate-800 px-2.5 py-1 rounded-lg shadow-sm hover:shadow transition flex items-center gap-1">
                                <span>✓ Pilih Semua Belum Selesai</span>
                            </button>
                            <button type="button" id="deselectAllBtn"
                                class="text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-2.5 py-1 rounded-lg shadow-sm hover:shadow transition">
                                Reset
                            </button>
                        </div>
                    </div>

                    <!-- Items Table Container (Same layout as Tambah Job) -->
                    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
                        <div class="bg-slate-100 dark:bg-slate-800 px-3 py-2 grid grid-cols-12 text-[10px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">
                            <span class="col-span-1 text-center">Pilih</span>
                            <span class="col-span-2">Kode Item</span>
                            <span class="col-span-4">Deskripsi Item</span>
                            <span class="col-span-2">No. Lot</span>
                            <span class="col-span-1 text-center">Qty</span>
                            <span class="col-span-2 text-center">Status Induk</span>
                        </div>
                        <div id="splitItemsContainer" class="divide-y divide-slate-100 dark:divide-slate-800 max-h-56 overflow-y-auto custom-scrollbar">
                            <div class="text-center py-6 text-xs text-slate-400 italic">
                                Memuat item dari pekerjaan induk...
                            </div>
                        </div>
                    </div>

                    <!-- Live Balance Calculation Summary (Same as Tambah Job) -->
                    <div class="flex justify-between items-center bg-white dark:bg-slate-900 border border-amber-200 dark:border-slate-700 rounded-xl px-4 py-2.5 shadow-sm">
                        <div class="text-xs text-slate-600 dark:text-slate-300">
                            <span>Item Dialihkan: <strong id="splitSelectedCountDisplay" class="text-slate-900 dark:text-white font-bold">0</strong> baris</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Total Saldo / Qty Dipindah:</span>
                            <span id="splitTotalBalanceDisplay" class="bg-amber-600 text-white text-xs font-extrabold px-3 py-1 rounded-full shadow">
                                0
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Info Tip Banner (Updated: Need Review workflow) -->
                <div class="border border-purple-200 dark:border-purple-800/80 bg-purple-50/50 dark:bg-purple-950/20 rounded-xl p-3 flex items-start gap-2.5">
                    <span class="text-sm">💡</span>
                    <p class="text-[11px] text-purple-900 dark:text-purple-300 leading-relaxed font-medium">
                        <em>Job anak yang baru otomatis masuk ke antrean <strong>Need Review</strong> pada departemen tujuan untuk diverifikasi sebelum dijadwalkan oleh PPIC.</em>
                    </p>
                </div>
            </div>

            <!-- Footer Action Bar (Unified ERP Style) -->
            <div class="px-6 py-4 bg-slate-50/95 dark:bg-slate-900/80 border-t border-slate-200/80 dark:border-slate-800 flex justify-end items-center gap-2.5 flex-shrink-0">
                <button type="button" onclick="closeModal('splitJobModal')"
                    class="px-4 py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl transition shadow-sm">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-1.5">
                    <span>Eksekusi Split Job</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
