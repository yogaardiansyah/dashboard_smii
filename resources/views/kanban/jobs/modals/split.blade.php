<div id="splitJobModal"
    class="pl-modal-overlay hidden" style="z-index: 1050;">
    <div class="pl-modal-panel pl-modal-panel-xl mx-auto my-auto max-h-[90vh]">

        <!-- Header Modal (Manage Areas Theme) -->
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Need Review Default</span>
                <h3 class="pl-modal-title">Pemecahan Pekerjaan (Split Job)</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="splitJobModal" onclick="closeModal('splitJobModal')" aria-label="Close">&times;</button>
        </div>

        <!-- Form Split Job -->
        <form id="splitJobForm" class="flex flex-col flex-1 min-h-0 overflow-hidden">
            @csrf
            <input type="hidden" id="splitJobDbId" name="job_id">

            <div class="pl-modal-body bg-slate-50 dark:bg-slate-900 space-y-4">
                
                <div class="p-3.5 bg-amber-50/80 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 rounded-2xl text-xs text-amber-900 dark:text-amber-200">
                    Membuat Job Anak dari <strong id="splitJobId" class="text-blue-600 dark:text-blue-400 font-mono font-bold"></strong>. Item terpilih akan dipindahkan ke tiket baru.
                </div>

                <!-- Grid 1: Departemen Tujuan & Area Tujuan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group-custom">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Departemen Tujuan <span class="text-red-500">*</span>
                        </label>
                        <select name="target_department_id" id="splitTargetDepartmentId" required
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-sm outline-none">
                            <option value="" disabled selected>Pilih Departemen Tujuan</option>
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group-custom">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Area Lokasi Tujuan <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                        </label>
                        <select name="target_area_id" id="splitTargetAreaId"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-sm outline-none">
                            <option value="">Sama dengan Job Induk</option>
                            @foreach($areas as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Grid 2: Ringkasan Job Anak (Job Title) -->
                <div class="form-group-custom">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Ringkasan Job Anak (Job Title Preview)
                    </label>
                    <input type="text" id="splitChildTitlePreview" name="child_title_preview" readonly
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-100/80 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 text-xs px-3.5 py-2.5 outline-none cursor-default font-mono"
                        placeholder="Otomatis di-generate dari item terpilih...">
                </div>

                <!-- Grid 3: Alasan & Catatan Khusus -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group-custom">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Alasan Pemecahan Job (Reason) <span class="text-red-500">*</span>
                        </label>
                        <textarea name="reason_note" id="splitReasonNote" rows="2" required
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-sm outline-none placeholder:text-slate-400"
                            placeholder="Jelaskan kebutuhan pengalihan (contoh: 2 item dialihkan ke Engineering karena butuh perbaikan mesin)..."></textarea>
                    </div>

                    <div class="form-group-custom">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Catatan Khusus (Remark Tambahan)
                        </label>
                        <textarea name="remark" id="splitRemark" rows="2"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-sm outline-none placeholder:text-slate-400"
                            placeholder="Catatan tambahan untuk tim pelaksana di departemen tujuan..."></textarea>
                    </div>
                </div>

                <!-- Bagian Item Pekerjaan & Checklist Lot -->
                <div class="border border-amber-200 dark:border-amber-900/60 rounded-2xl p-4 bg-amber-50/40 dark:bg-amber-950/20 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-list-check text-amber-600 dark:text-amber-400"></i>
                                <span>Pilih Item Pekerjaan & Lot yang Akan Dipisahkan</span>
                            </label>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                Pilih item dari tiket induk yang akan dialihkan ke tiket job anak.
                            </span>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button type="button" id="selectAllUnfinishedBtn"
                                class="pl-btn pl-btn-neutral text-xs py-1 px-3 shadow-sm hover:shadow transition flex items-center gap-1">
                                <span>✓ Pilih Semua Belum Selesai</span>
                            </button>
                            <button type="button" id="deselectAllBtn"
                                class="pl-btn pl-btn-neutral text-xs py-1 px-3 shadow-sm hover:shadow transition">
                                Reset
                            </button>
                        </div>
                    </div>

                    <!-- Items Table Container -->
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

                    <!-- Live Balance Calculation Summary -->
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

                <!-- Info Tip Banner -->
                <div class="border border-purple-200 dark:border-purple-800/80 bg-purple-50/60 dark:bg-purple-950/30 rounded-2xl p-3 flex items-start gap-2.5">
                    <span class="text-sm">💡</span>
                    <p class="text-[11px] text-purple-900 dark:text-purple-300 leading-relaxed font-medium">
                        <em>Job anak yang baru otomatis masuk ke antrean <strong>Need Review</strong> pada departemen tujuan untuk diverifikasi sebelum dijadwalkan oleh PPIC.</em>
                    </p>
                </div>
            </div>

            <!-- Footer Action Bar (Manage Areas Theme) -->
            <div class="pl-modal-footer">
                <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="splitJobModal" onclick="closeModal('splitJobModal')">
                    Batal
                </button>
                <button type="submit" class="pl-btn pl-btn-amber">
                    <i class="fa-solid fa-scissors mr-1.5"></i> Eksekusi Split Job
                </button>
            </div>
        </form>
    </div>
</div>
