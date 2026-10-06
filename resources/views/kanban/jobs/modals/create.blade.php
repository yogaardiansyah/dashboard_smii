<div id="createJobModal"
    class="hidden fixed inset-0 z-[1050] overflow-y-auto bg-slate-950/75 backdrop-blur-md transition-all duration-200 flex items-center justify-center p-3 sm:p-5" style="z-index: 1050;">
    <div class="relative w-full max-w-3xl mx-auto my-auto max-h-[90vh] rounded-2xl bg-white dark:bg-[#111827] border border-slate-200/90 dark:border-slate-800 shadow-2xl transition-all overflow-hidden flex flex-col animate-in fade-in zoom-in-95 duration-150">

        <!-- Header Modal (Unified ERP Style) -->
        <div class="px-6 py-4 bg-slate-50/95 dark:bg-slate-900/80 border-b border-slate-200/80 dark:border-slate-800 flex justify-between items-center flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800/60 flex items-center justify-center text-lg flex-shrink-0 shadow-sm kanban-modal-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 tracking-tight">Buat Job Kanban Baru</h3>
                        <span class="bg-purple-100 dark:bg-purple-950/80 text-purple-800 dark:text-purple-300 border border-purple-300 dark:border-purple-800/80 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                            Need Review Default
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Tambahkan tiket pekerjaan baru dan cari item dari inventory SMII.
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeCreateJobModal()"
                class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-200/50 dark:hover:bg-slate-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="createJobForm" class="flex flex-col flex-1 min-h-0 overflow-hidden" enctype="multipart/form-data">
            <div class="p-6 space-y-4 flex-1 overflow-y-auto min-h-0 custom-scrollbar text-slate-800 dark:text-slate-200">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Area Lokasi <span class="text-red-500">*</span></label>
                        <select name="area_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs" required>
                            <option value="" disabled selected>Pilih Area</option>
                            @foreach($areas as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Departemen Tujuan <span class="text-red-500">*</span></label>
                        <select name="to_department_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs" required>
                            <option value="" disabled selected>Pilih Departemen</option>
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Ringkasan Pekerjaan (Job Title) <span class="text-red-500">*</span></label>
                    <input type="text" name="list_job" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs" required placeholder="Contoh: Pengambilan Minyak Palm Oil & Refined Oil Batch 3">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Alasan Pekerjaan (Reason)</label>
                        <textarea name="reason_description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs" placeholder="Penjelasan kebutuhan atau latar belakang..."></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Catatan Khusus (Remark)</label>
                        <textarea name="remark" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs" placeholder="Catatan penting atau instruksi khusus..."></textarea>
                    </div>
                </div>

                <!-- Bagian Item Pekerjaan & Pencarian Database Utama -->
                <div class="border border-blue-200 dark:border-gray-600 rounded-lg p-3.5 bg-blue-50/40 dark:bg-gray-700/40 space-y-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <span>Rincian Item Pekerjaan & Lot</span>
                            </label>
                            <span class="text-[11px] text-gray-500 dark:text-gray-400">Cari item & lot dari inventory atau tambahkan manual. Mendukung multi-lot per item.</span>
                        </div>
                        <button type="button" id="addManualItemBtn" class="text-xs font-semibold text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white border border-gray-300 dark:border-gray-500 bg-white dark:bg-gray-700 px-2.5 py-1 rounded shadow-sm flex items-center gap-1">
                            <span>+ Tambah Manual</span>
                        </button>
                    </div>

                    <!-- Search Input Bar with Autocomplete Dropdown -->
                    <div class="relative">
                        <div class="relative">
                            <input type="text" id="kanbanItemSearchInput" autocomplete="off"
                                class="w-full text-xs rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white pl-8 pr-8 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="🔍 Ketik kode (pt_part), deskripsi, atau no. lot (contoh: 0TA344, IUJ608, 2519A0007)...">
                            <span id="searchSpinner" class="hidden absolute right-2.5 top-2.5">
                                <svg class="animate-spin h-3.5 w-3.5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                            </span>
                        </div>

                        <!-- Dropdown Search Results -->
                        <div id="kanbanItemSearchResults"
                            class="hidden absolute z-50 left-0 right-0 mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl max-h-56 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                        </div>
                    </div>

                    <!-- Selected Items Table / List -->
                    <div class="bg-white dark:bg-gray-800 rounded-md border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gray-100 dark:bg-gray-700/60 px-3 py-1.5 grid grid-cols-12 text-[10px] font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            <span class="col-span-1 text-center">#</span>
                            <span class="col-span-2">Kode Item</span>
                            <span class="col-span-4">Deskripsi Item</span>
                            <span class="col-span-2">No. Lot</span>
                            <span class="col-span-2 text-center">Kuantitas</span>
                            <span class="col-span-1 text-center">Aksi</span>
                        </div>
                        <div id="selectedItemsContainer" class="divide-y divide-gray-100 dark:divide-gray-700 max-h-48 overflow-y-auto custom-scrollbar">
                            <div id="emptyItemsNotice" class="text-center py-6 text-xs text-gray-400 italic">
                                Belum ada item yang dipilih. Cari item di atas atau klik "Tambah Manual".
                            </div>
                        </div>
                    </div>

                    <!-- Live Balance Calculation Summary -->
                    <div class="flex justify-between items-center bg-white dark:bg-gray-800 border border-blue-200 dark:border-gray-700 rounded-lg px-3.5 py-2.5 shadow-sm">
                        <div class="text-xs text-gray-600 dark:text-gray-300">
                            <span>Total Item/Lot: <strong id="totalItemCountDisplay" class="text-gray-900 dark:text-white font-bold">0</strong> baris</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">Total Saldo / Balance:</span>
                            <span id="totalBalanceDisplay" class="bg-blue-600 text-white text-xs font-extrabold px-2.5 py-1 rounded-full shadow">
                                0
                            </span>
                            <input type="hidden" name="balance" id="calculatedBalanceInput" value="0">
                        </div>
                    </div>
                </div>

                <!-- Tanggal Mulai & Deadline (Opsional) -->
                <div class="border border-purple-200 dark:border-purple-800 bg-purple-50/50 dark:bg-purple-900/20 rounded-md p-3">
                    <p class="text-[11px] text-purple-900 dark:text-purple-300 mb-2 font-medium flex items-center gap-1">
                        <span>💡</span>
                        <span><em>Tips: Semua Job baru otomatis masuk ke antrean <strong>Need Review</strong> untuk diverifikasi sebelum dijadwalkan oleh PPIC.</em></span>
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Tanggal Mulai (Opsional)</label>
                            <input type="date" name="start_date" id="createStartDate" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Deadline (Opsional)</label>
                            <input type="date" name="deadline" id="createDeadline" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-white text-xs">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Lampiran / Attachments (Opsional, Max 3 File)</label>
                    <input type="file" name="attachments[]" class="block w-full text-xs text-gray-500 mt-1" multiple>
                </div>

            </div>

            <!-- Footer Action Bar (Unified ERP Style) -->
            <div class="px-6 py-4 bg-slate-50/95 dark:bg-slate-900/80 border-t border-slate-200/80 dark:border-slate-800 flex justify-end items-center gap-2.5 flex-shrink-0">
                <button type="button" onclick="closeCreateJobModal()"
                    class="px-4 py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl transition shadow-sm">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-1.5">
                    <span>Simpan Job</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let kanbanItemsList = [];
    let searchDebounceTimer = null;

    function closeCreateJobModal() {
        document.getElementById('createJobModal').classList.add('hidden');
    }

    // Hitung total balance dari seluruh kuantitas item/lot (Balance = Jumlah Lot)
    function updateKanbanBalance() {
        const rows = document.querySelectorAll('.kanban-selected-item-row');
        let totalItems = rows.length;

        rows.forEach((row, idx) => {
            const numEl = row.querySelector('.row-number');
            if (numEl) numEl.textContent = (idx + 1);
        });

        // Balance adalah jumlah baris lot/unit pekerjaan, bukan akumulasi berat/kuantitas fisik
        document.getElementById('totalItemCountDisplay').textContent = totalItems;
        document.getElementById('totalBalanceDisplay').textContent = totalItems;
        document.getElementById('calculatedBalanceInput').value = totalItems;

        const emptyNotice = document.getElementById('emptyItemsNotice');
        if (emptyNotice) {
            emptyNotice.style.display = totalItems === 0 ? 'block' : 'none';
        }
    }

    // Tambah item ke tabel (mendukung multi-lot: 1 jenis item, beda lot & balance)
    function addKanbanItemRow(item) {
        const container = document.getElementById('selectedItemsContainer');
        const emptyNotice = document.getElementById('emptyItemsNotice');
        if (emptyNotice) emptyNotice.style.display = 'none';

        const index = container.querySelectorAll('.kanban-selected-item-row').length;
        const code = item.item_code || '';
        const name = item.item_name || '';
        const unit = item.unit ? item.unit.toUpperCase() : '';
        const lot = item.lot_number || '';
        const qty = item.qty || 1;
        const hasCode = Boolean(code);
        const hasLotFromDb = Boolean(code && lot);

        const row = document.createElement('div');
        row.className = 'kanban-selected-item-row px-3 py-2 grid grid-cols-12 items-center gap-2 text-xs hover:bg-blue-50/30 dark:hover:bg-gray-700/30 transition';
        row.innerHTML = `
            <div class="col-span-1 text-center font-mono text-gray-400 font-bold row-number">${index + 1}</div>
            <div class="col-span-2">
                <input type="hidden" name="items[${index}][item_code]" value="${code}">
                ${code 
                    ? `<span class="font-mono text-[10px] font-bold bg-blue-100 dark:bg-blue-900/60 text-blue-800 dark:text-blue-300 px-1 py-0.5 rounded truncate block" title="${code}">${code}</span>` 
                    : `<span class="text-gray-400 italic text-[10px]">Manual</span>`}
            </div>
            <div class="col-span-4">
                <input type="text" name="items[${index}][item_name]" value="${name}" 
                    class="w-full text-xs font-medium py-1 px-1.5 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white ${hasCode ? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 cursor-not-allowed' : ''}" 
                    ${hasCode ? 'readonly title="Nama item terkunci dari master inventory"' : ''} required>
                ${unit ? `<input type="hidden" name="items[${index}][unit]" value="${unit}">` : ''}
            </div>
            <div class="col-span-2">
                <input type="text" name="items[${index}][lot_number]" value="${lot}" placeholder="No. Lot..."
                    class="item-lot-input w-full text-xs font-mono py-1 px-1.5 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white ${hasLotFromDb ? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 cursor-not-allowed' : ''}"
                    ${hasLotFromDb ? 'readonly title="Nomor Lot terkunci dari data inventory"' : ''}
                    title="Nomor Lot">
            </div>
            <div class="col-span-2 flex items-center justify-center gap-1">
                <input type="number" name="items[${index}][qty]" min="1" value="${qty}"
                    class="item-qty-input w-16 text-center text-xs py-1 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white font-bold"
                    oninput="updateKanbanBalance()">
                ${unit ? `<span class="text-[10px] text-gray-500 font-semibold uppercase">${unit}</span>` : ''}
            </div>
            <div class="col-span-1 flex items-center justify-center gap-1">
                <button type="button" title="Tambah lot lain untuk item ini" onclick="duplicateKanbanLot(this)"
                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-[11px] font-bold px-1 py-0.5 rounded hover:bg-blue-50 dark:hover:bg-gray-700">+Lot</button>
                <button type="button" title="Hapus item" onclick="removeKanbanItemRow(this)"
                    class="text-red-500 hover:text-red-700 font-bold text-xs p-1">✕</button>
            </div>
        `;

        container.appendChild(row);
        updateKanbanBalance();
    }

    // Duplikasi baris item untuk lot berbeda (1 jenis item tapi banyak lot & balance)
    function duplicateKanbanLot(btn) {
        const row = btn.closest('.kanban-selected-item-row');
        if (!row) return;

        const codeInput = row.querySelector('input[name*="[item_code]"]');
        const nameInput = row.querySelector('input[name*="[item_name]"]');
        const unitInput = row.querySelector('input[name*="[unit]"]');

        addKanbanItemRow({
            item_code: codeInput ? codeInput.value : '',
            item_name: nameInput ? nameInput.value : '',
            unit: unitInput ? unitInput.value : '',
            lot_number: '',
            qty: 1
        });
    }

    function removeKanbanItemRow(btn) {
        btn.closest('.kanban-selected-item-row').remove();
        updateKanbanBalance();
    }

    // Event input pencarian item dari database utama (termasuk lot & kuantitas)
    document.getElementById('kanbanItemSearchInput')?.addEventListener('input', function(e) {
        const query = e.target.value.trim();
        const resultsBox = document.getElementById('kanbanItemSearchResults');
        const spinner = document.getElementById('searchSpinner');

        clearTimeout(searchDebounceTimer);

        if (query.length < 1) {
            resultsBox.classList.add('hidden');
            resultsBox.innerHTML = '';
            spinner.classList.add('hidden');
            return;
        }

        spinner.classList.remove('hidden');

        searchDebounceTimer = setTimeout(() => {
            fetch(`{{ route('kanban.items.search') }}?q=${encodeURIComponent(query)}`)
                .then(r => r.json())
                .then(items => {
                    spinner.classList.add('hidden');
                    if (items.length === 0) {
                        resultsBox.innerHTML = `
                            <div class="p-3 text-xs text-gray-500 dark:text-gray-400 text-center">
                                Tidak ada item ditemukan untuk "<strong>${query}</strong>".
                            </div>
                        `;
                    } else {
                        let html = '';
                        items.forEach(it => {
                            const part = it.pt_part || '';
                            const desc = it.description || it.pt_desc1 || part;
                            const lot = it.lot_number || '';
                            const qty = it.qty || 1;
                            const unit = it.unit || it.pt_um || '';

                            html += `
                                <div class="search-result-item px-3 py-2 text-xs hover:bg-blue-50 dark:hover:bg-gray-700 cursor-pointer flex justify-between items-center transition"
                                     data-part="${part}"
                                     data-name="${desc}"
                                     data-lot="${lot}"
                                     data-qty="${qty}"
                                     data-unit="${unit}">
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-mono font-bold text-blue-700 dark:text-blue-400">[${part}]</span>
                                            <span class="font-medium text-gray-800 dark:text-gray-200">${desc}</span>
                                        </div>
                                        <div class="flex items-center gap-2 mt-0.5 text-[11px] text-gray-500">
                                            ${lot ? `<span class="bg-amber-100 dark:bg-amber-900/60 text-amber-800 dark:text-amber-300 font-mono px-1 py-0.2 rounded font-bold">Lot: ${lot}</span>` : ''}
                                            <span class="text-gray-600 dark:text-gray-400">Saldo: <strong>${qty}</strong> ${unit}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="text-blue-600 dark:text-blue-400 text-xs font-bold bg-blue-50 dark:bg-blue-900/40 px-2 py-1 rounded hover:bg-blue-100">+ Pilih</span>
                                    </div>
                                </div>
                            `;
                        });
                        resultsBox.innerHTML = html;
                    }
                    resultsBox.classList.remove('hidden');
                })
                .catch(() => {
                    spinner.classList.add('hidden');
                });
        }, 250);
    });

    // Klik hasil pencarian
    document.getElementById('kanbanItemSearchResults')?.addEventListener('click', function(e) {
        const itemRow = e.target.closest('.search-result-item');
        if (itemRow) {
            const part = itemRow.dataset.part;
            const name = itemRow.dataset.name;
            const lot = itemRow.dataset.lot;
            const qty = parseInt(itemRow.dataset.qty) || 1;
            const unit = itemRow.dataset.unit;

            addKanbanItemRow({
                item_code: part,
                item_name: name,
                lot_number: lot,
                unit: unit,
                qty: qty
            });

            // Reset input search
            const searchInput = document.getElementById('kanbanItemSearchInput');
            searchInput.value = '';
            this.classList.add('hidden');
            this.innerHTML = '';
            searchInput.focus();
        }
    });

    // Tambah manual
    document.getElementById('addManualItemBtn')?.addEventListener('click', function() {
        addKanbanItemRow({
            item_code: '',
            item_name: 'Item Pekerjaan Baru',
            unit: 'PCS',
            qty: 1
        });
    });

    // Sembunyikan dropdown hasil cari jika klik di luar
    document.addEventListener('click', function(e) {
        const searchBox = document.getElementById('kanbanItemSearchResults');
        const searchInput = document.getElementById('kanbanItemSearchInput');
        if (searchBox && !searchBox.contains(e.target) && e.target !== searchInput) {
            searchBox.classList.add('hidden');
        }
    });

    // Reset form dan container saat modal dibuka
    document.getElementById('openCreateJobModalBtn')?.addEventListener('click', function() {
        const container = document.getElementById('selectedItemsContainer');
        if (container) {
            container.innerHTML = `
                <div id="emptyItemsNotice" class="text-center py-6 text-xs text-gray-400 italic">
                    Belum ada item yang dipilih. Cari item di atas atau klik "Tambah Manual".
                </div>
            `;
        }
        updateKanbanBalance();
    });
</script>
