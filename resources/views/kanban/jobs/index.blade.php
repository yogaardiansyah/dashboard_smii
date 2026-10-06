<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight mb-4 md:mb-0">
                {{ __('Kanban Board') }}
            </h2>
             @if(isset($user))
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    <span class="font-medium">{{ $user->name }}</span>
                    <span class="hidden sm:inline">| {{ optional(optional($user->kanbanProfile)->department)->department_name ?? 'N/A Kanban Dept.' }}</span>
                </div>
            @endif
        </div>
    </x-slot>

   <div class="py-6 md:py-8">
        <div class="max-w-full mx-auto sm:px-4 lg:px-6">
            <div class="overflow-hidden">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-bold uppercase text-slate-500 tracking-wider">Workflow:</span>
                        <div class="hidden lg:flex items-center text-[11px] text-slate-600 dark:text-slate-400 gap-1 bg-white/90 dark:bg-slate-800/90 px-3.5 py-1.5 rounded-full border border-slate-200 dark:border-slate-700 shadow-sm font-medium">
                            <span class="font-bold text-purple-600 dark:text-purple-400">1. Need Review</span> ➔ 
                            <span class="font-bold text-amber-600 dark:text-amber-400">2. To Be Scheduled</span> ➔ 
                            <span class="font-bold text-blue-600 dark:text-blue-400">3. Scheduled</span> ➔ 
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">4. On Going</span> ➔ 
                            <span class="font-bold text-teal-600 dark:text-teal-400">5. Completed</span> ➔ 
                            <span class="font-bold text-slate-500 dark:text-slate-400">6. Closed</span>
                        </div>

                        <!-- Button Quick Scroll to Archive -->
                        <button id="toggleArchiveScrollBtn" type="button"
                            class="pl-btn pl-btn-sm pl-btn-neutral flex items-center gap-1.5 cursor-pointer shadow-sm hover:shadow transition">
                            <span>Geser ke Arsip Closed</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </button>
                    </div>

                    <button id="openCreateJobModalBtn"
                            class="pl-btn pl-btn-sm pl-btn-primary flex items-center gap-2"
                            @if($areas->isEmpty() || $departments->isEmpty()) disabled title="Cannot add job: Areas or Departments are not configured." @endif>
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span>Tambah Job Baru</span>
                    </button>
                </div>

                <!-- Kolom-Kolom Kanban Board (Alur Baru: 6 Tahap) -->
                <!-- 5 Tahap Aktif (Need Review s/d Completed) Pas dalam 1 Layar, Closed Tersembunyi di Kanan (Geser) -->
                <div id="kanbanBoardContainer" class="flex flex-nowrap overflow-x-auto gap-3 pb-6 items-stretch min-h-[calc(100vh-230px)] px-1 scroll-smooth">
                    @php 
                        $columnClass = "kanban-column-responsive flex-none flex flex-col"; 
                    @endphp

                    <!-- 1. NEED REVIEW -->
                    <div class="{{ $columnClass }}">
                        <div class="flex flex-col rounded-2xl shadow-md h-full bg-purple-50/70 dark:bg-[#131b2e] border border-purple-200/80 dark:border-purple-900/50 overflow-hidden kanban-card-container">
                            <div class="bg-purple-600 p-2.5 text-center flex justify-between items-center px-4 rounded-t-2xl">
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider">1. Need Review</h3>
                                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full column-badge">{{ $needReviewJobs->count() }}</span>
                            </div>
                            <div id="need-review-column" class="p-3 space-y-3 kanban-column-body flex-1">
                                @forelse($needReviewJobs as $job) 
                                    @include('kanban.jobs.partials.job_card', ['job' => $job]) 
                                @empty 
                                    <div class="flex items-center justify-center h-40 opacity-60">
                                        <p class="text-xs text-purple-800 dark:text-purple-300 font-bold empty-text">Tidak ada job butuh review.</p>
                                    </div> 
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- 2. TO BE SCHEDULED (PPIC) -->
                    <div class="{{ $columnClass }}">
                        <div class="flex flex-col rounded-2xl shadow-md h-full bg-amber-50/70 dark:bg-[#131b2e] border border-amber-200/80 dark:border-amber-900/50 overflow-hidden kanban-card-container">
                            <div class="bg-amber-600 p-2.5 text-center flex justify-between items-center px-4 rounded-t-2xl">
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider">2. To Be Scheduled</h3>
                                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full column-badge">{{ $toBeScheduledJobs->count() }}</span>
                            </div>
                            <div id="to-be-scheduled-column" class="p-3 space-y-3 kanban-column-body flex-1">
                                @forelse($toBeScheduledJobs as $job) 
                                    @include('kanban.jobs.partials.job_card', ['job' => $job]) 
                                @empty 
                                    <div class="flex items-center justify-center h-40 opacity-60">
                                        <p class="text-xs text-amber-800 dark:text-amber-300 font-bold empty-text">Tidak ada antrean penjadwalan PPIC.</p>
                                    </div> 
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- 3. SCHEDULED -->
                    <div class="{{ $columnClass }}">
                        <div class="flex flex-col rounded-2xl shadow-md h-full bg-blue-50/70 dark:bg-[#131b2e] border border-blue-200/80 dark:border-blue-900/50 overflow-hidden kanban-card-container">
                            <div class="bg-blue-600 p-2.5 text-center flex justify-between items-center px-4 rounded-t-2xl">
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider">3. Scheduled</h3>
                                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full column-badge">{{ $scheduledJobs->count() }}</span>
                            </div>
                            <div id="scheduled-column" class="p-3 space-y-3 kanban-column-body flex-1">
                                @forelse($scheduledJobs as $job) 
                                    @include('kanban.jobs.partials.job_card', ['job' => $job]) 
                                @empty 
                                    <div class="flex items-center justify-center h-40 opacity-60">
                                        <p class="text-xs text-blue-800 dark:text-blue-300 font-bold empty-text">Tidak ada job terjadwal.</p>
                                    </div> 
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- 4. ON GOING -->
                    <div class="{{ $columnClass }}">
                        <div class="flex flex-col rounded-2xl shadow-md h-full bg-emerald-50/70 dark:bg-[#131b2e] border border-emerald-200/80 dark:border-emerald-900/50 overflow-hidden kanban-card-container">
                            <div class="bg-emerald-600 p-2.5 text-center flex justify-between items-center px-4 rounded-t-2xl">
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider">4. On Going</h3>
                                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full column-badge">{{ $onGoingJobs->count() }}</span>
                            </div>
                            <div id="on-going-column" class="p-3 space-y-3 kanban-column-body flex-1">
                                @forelse($onGoingJobs as $job) 
                                    @include('kanban.jobs.partials.job_card', ['job' => $job]) 
                                @empty 
                                    <div class="flex items-center justify-center h-40 opacity-60">
                                        <p class="text-xs text-emerald-800 dark:text-emerald-300 font-bold empty-text">Tidak ada job sedang dikerjakan.</p>
                                    </div> 
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- 5. COMPLETED -->
                    <div class="{{ $columnClass }}">
                        <div class="flex flex-col rounded-2xl shadow-md h-full bg-teal-50/70 dark:bg-[#131b2e] border border-teal-200/80 dark:border-teal-900/50 overflow-hidden kanban-card-container">
                            <div class="bg-teal-600 p-2.5 text-center flex justify-between items-center px-4 rounded-t-2xl">
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider">5. Completed</h3>
                                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full column-badge">{{ $completedJobs->count() }}</span>
                            </div>
                            <div id="completed-column" class="p-3 space-y-3 kanban-column-body flex-1">
                                @forelse($completedJobs as $job) 
                                    @include('kanban.jobs.partials.job_card', ['job' => $job]) 
                                @empty 
                                    <div class="flex items-center justify-center h-40 opacity-60">
                                        <p class="text-xs text-teal-800 dark:text-teal-300 font-bold empty-text">Tidak ada job selesai.</p>
                                    </div> 
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- 6. CLOSED (Tersembunyi di Kanan, Geser ke Kanan untuk Melihat) -->
                    <div class="{{ $columnClass }}">
                        <div class="flex flex-col rounded-2xl shadow-md h-full bg-slate-100/70 dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 overflow-hidden kanban-card-container">
                            <div class="bg-slate-700 p-2.5 text-center flex justify-between items-center px-4 rounded-t-2xl">
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider">6. Closed / Archive</h3>
                                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full column-badge">{{ $closedJobs->count() }}</span>
                            </div>
                            <div id="closed-column" class="p-3 space-y-3 kanban-column-body flex-1">
                                @forelse($closedJobs as $job) 
                                    @include('kanban.jobs.partials.job_card', ['job' => $job]) 
                                @empty 
                                    <div class="flex items-center justify-center h-40 opacity-60">
                                        <p class="text-xs text-slate-400 font-bold empty-text">Tidak ada arsip.</p>
                                    </div> 
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('kanban.jobs.modals.create')
    @include('kanban.jobs.modals.set_schedule')
    @include('kanban.jobs.modals.agree_review')
    @include('kanban.jobs.modals.re_review')
    @include('kanban.jobs.modals.split')
    @include('kanban.jobs.modals.issue')
    @include('kanban.jobs.modals.move_stage')
    @include('kanban.jobs.modals.forward')
    @include('kanban.jobs.modals.complete')
    @include('kanban.jobs.modals.close')
    @include('kanban.jobs.modals.detail')
    @include('kanban.jobs.modals.cancel')

    <!-- Spinner Loading -->
    <div id="global-spinner" class="hidden fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center">
        <div class="flex flex-col items-center">
            <div class="w-14 h-14 border-4 border-white/20 border-t-blue-500 rounded-full animate-spin"></div>
            <p class="text-white text-xs mt-3 font-semibold tracking-wider uppercase">Memproses permintaan...</p>
        </div>
    </div>

    @include('layouts.partials.roleuser_styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        /* Pill Button Variants matching Manage Areas Theme */
        .pl-btn-purple { background: #7c3aed !important; color: #ffffff !important; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.25); }
        .pl-btn-purple:hover { background: #6d28d9 !important; transform: translateY(-1px); }
        .pl-btn-amber { background: #d97706 !important; color: #ffffff !important; box-shadow: 0 4px 14px rgba(217, 119, 6, 0.25); }
        .pl-btn-amber:hover { background: #b45309 !important; transform: translateY(-1px); }
        .pl-btn-emerald { background: #059669 !important; color: #ffffff !important; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.25); }
        .pl-btn-emerald:hover { background: #047857 !important; transform: translateY(-1px); }
        .pl-btn-rose { background: #e11d48 !important; color: #ffffff !important; box-shadow: 0 4px 14px rgba(225, 29, 72, 0.25); }
        .pl-btn-rose:hover { background: #be123c !important; transform: translateY(-1px); }
        .pl-btn-slate { background: #334155 !important; color: #ffffff !important; box-shadow: 0 4px 14px rgba(51, 65, 85, 0.25); }
        .pl-btn-slate:hover { background: #1e293b !important; transform: translateY(-1px); }

        /* Responsive 5-column board layout on desktop, horizontal scroll on mobile */
        .kanban-column-responsive {
            flex: 0 0 calc((100% - 3rem) / 5);
            width: calc((100% - 3rem) / 5);
            min-width: 240px;
        }
        @media (max-width: 1279px) {
            .kanban-column-responsive {
                flex: 0 0 280px;
                width: 280px;
                min-width: 280px;
            }
        }

        .kanban-column-body { overflow-y: auto; scrollbar-width: thin; }
        .overflow-x-auto::-webkit-scrollbar { height: 8px; }
        .overflow-x-auto::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.05); border-radius: 4px; }
        .overflow-x-auto::-webkit-scrollbar-thumb { background: rgba(156, 163, 175, 0.5); border-radius: 4px; }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: rgba(107, 114, 128, 0.8); }
        .kanban-column-body::-webkit-scrollbar { width: 4px; }

        /* Unified Dark Mode Canvas and Surface Themes */
        body.dark-skin,
        html.dark body,
        body.dark-skin .wrapper,
        html.dark .wrapper,
        body.dark-skin .content-wrapper,
        html.dark .content-wrapper {
            background-color: #0b1120 !important;
            color: #e2e8f0;
        }

        .dark-skin .overflow-x-auto::-webkit-scrollbar-track,
        html.dark .overflow-x-auto::-webkit-scrollbar-track {
            background: #0f172a;
        }
        .dark-skin .overflow-x-auto::-webkit-scrollbar-thumb,
        html.dark .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #334155;
        }
        .dark-skin .overflow-x-auto::-webkit-scrollbar-thumb:hover,
        html.dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
        .dark-skin .kanban-column-body::-webkit-scrollbar-thumb,
        html.dark .kanban-column-body::-webkit-scrollbar-thumb {
            background: #334155;
        }
        .dark-skin .empty-text,
        html.dark .empty-text {
            color: #64748b !important;
        }

        /* =========================================================
           Kanban Modal Theme & style.css Conflict Overrides
           ========================================================= */
        [id$="Modal"] {
            z-index: 1050 !important;
        }
        [id$="Modal"] .w-10,
        .kanban-modal-icon {
            width: 2.5rem !important;
            min-width: 2.5rem !important;
        }
        [id$="Modal"] .h-10,
        .kanban-modal-icon {
            height: 2.5rem !important;
            min-height: 2.5rem !important;
        }
        [id$="Modal"] .w-8 {
            width: 2rem !important;
            min-width: 2rem !important;
        }
        [id$="Modal"] .h-8 {
            height: 2rem !important;
            min-height: 2rem !important;
        }
        [id$="Modal"] .w-5 {
            width: 1.25rem !important;
        }
        [id$="Modal"] .h-5 {
            height: 1.25rem !important;
        }
        [id$="Modal"] .w-4 {
            width: 1rem !important;
        }
        [id$="Modal"] .h-4 {
            height: 1rem !important;
        }
        [id$="Modal"] .w-24 {
            width: 6rem !important;
        }
        [id$="Modal"] .w-20 {
            width: 5rem !important;
        }
        [id$="Modal"] .w-16 {
            width: 4rem !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/js/app.js'])

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const spinner = document.getElementById('global-spinner');

        const showSpinner = () => spinner.classList.remove('hidden');
        const hideSpinner = () => spinner.classList.add('hidden');

        function updateKanbanUI(job, html) {
            const oldCard = document.getElementById(`job-card-${job.id}`);
            if (oldCard) oldCard.remove();

            const targetStatus = job.status.replace(/_/g, '-'); 
            const targetColumn = document.getElementById(`${targetStatus}-column`);

            if (targetColumn) {
                const placeholder = targetColumn.querySelector('.empty-text');
                if (placeholder && placeholder.closest('div.flex')) {
                    placeholder.closest('div.flex').remove();
                }

                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                targetColumn.insertAdjacentElement('afterbegin', tempDiv.firstElementChild);
            }

            refreshColumnCounters();
        }

        function refreshColumnCounters() {
            document.querySelectorAll('.kanban-card-container').forEach(container => {
                const body = container.querySelector('.kanban-column-body');
                const badge = container.querySelector('.column-badge');
                if (body && badge) {
                    const cards = body.querySelectorAll('[id^="job-card-"]');
                    badge.textContent = cards.length;

                    // Jika tidak ada kartu dan tidak ada placeholder
                    if (cards.length === 0 && !body.querySelector('.empty-text')) {
                        const emptyDiv = document.createElement('div');
                        emptyDiv.className = 'flex items-center justify-center h-40 opacity-60';
                        emptyDiv.innerHTML = '<p class="text-xs font-bold empty-text">Tidak ada antrean.</p>';
                        body.appendChild(emptyDiv);
                    }
                }
            });
        }

        async function handleFormSubmit(url, formData) {
            showSpinner();
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (!response.ok) {
                    let errorHtml = data.message || 'Terjadi kesalahan sistem.';
                    if (response.status === 422 && data.errors) {
                        errorHtml = '<ul class="text-left list-disc list-inside mt-2 text-xs">';
                        for (const field in data.errors) { errorHtml += `<li>${data.errors[field][0]}</li>`; }
                        errorHtml += '</ul>';
                    }
                    Swal.fire({ icon: 'error', title: 'Gagal', html: errorHtml });
                    return false;
                }

                Swal.fire({
                    toast: true, position: 'top-end', icon: 'success',
                    title: data.message, showConfirmButton: false, timer: 3000
                });

                if (data.job && data.html) {
                    updateKanbanUI(data.job, data.html);
                }
                return data;
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Gagal berkomunikasi dengan server.', 'error');
                return false;
            } finally {
                hideSpinner();
            }
        }

        if (window.Echo) {
            window.Echo.channel('kanban-jobs').listen('KanbanJobUpdated', (data) => updateKanbanUI(data.job, data.html));
        }

        window.openModal = function(id) { 
            const el = document.getElementById(id);
            if(el) {
                el.classList.remove('hidden');
                el.classList.add('active');
                document.body.classList.add('overflow-hidden');
            }
        };
        window.closeModal = function(id) { 
            const el = document.getElementById(id);
            if(el) {
                el.classList.add('hidden');
                el.classList.remove('active');
                const anyOpen = document.querySelectorAll('.pl-modal-overlay:not(.hidden), .fixed.z-50:not(.hidden), [id$="Modal"]:not(.hidden)');
                if(anyOpen.length === 0) {
                    document.body.classList.remove('overflow-hidden');
                }
            }
        };

        // Universal close modal on [data-close-modal] click
        $(document).on('click', '[data-close-modal]', function(e) {
            e.preventDefault();
            const modalId = $(this).attr('data-close-modal');
            if (modalId) {
                closeModal(modalId);
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.pl-modal-overlay:not(.hidden), [id$="Modal"]:not(.hidden)').forEach(modal => {
                    modal.classList.add('hidden');
                    modal.classList.remove('active');
                });
                document.body.classList.remove('overflow-hidden');
            }
        });

        // ==========================================
        // Event Delegator untuk Seluruh Tombol Aksi
        // ==========================================
        document.body.addEventListener('click', function(e) {

            // 1. Atur Jadwal (On Hold -> Need Review)
            const scheduleBtn = e.target.closest('.set-schedule-btn');
            if (scheduleBtn) {
                e.preventDefault();
                const modal = document.getElementById('setScheduleModal');
                modal.querySelector('#setScheduleJobDbId').value = scheduleBtn.dataset.jobId;
                modal.querySelector('#setScheduleJobId').textContent = scheduleBtn.dataset.idJob;
                modal.querySelector('#setScheduleStartDate').value = scheduleBtn.dataset.startDate || '';
                modal.querySelector('#setScheduleDeadline').value = scheduleBtn.dataset.deadline || '';
                openModal('setScheduleModal');
                return;
            }

            // 2. Agree Review Modal (Need Review)
            const agreeBtn = e.target.closest('.agree-review-btn');
            if (agreeBtn) {
                e.preventDefault();
                const modal = document.getElementById('agreeReviewModal');
                modal.querySelector('#agreeJobDbId').value = agreeBtn.dataset.jobId;
                modal.querySelector('#returnJobDbId').value = agreeBtn.dataset.jobId;
                modal.querySelector('#agreeJobId').textContent = agreeBtn.dataset.idJob;
                modal.querySelector('#agreeJobDates').textContent = `${agreeBtn.dataset.startDate} s/d ${agreeBtn.dataset.deadline}`;
                modal.querySelector('#agreeJobDesc').textContent = agreeBtn.dataset.desc;
                const areaSelect = modal.querySelector('#agreeAreaId');
                if (areaSelect) {
                    areaSelect.value = agreeBtn.dataset.areaId || '';
                }
                if (typeof switchReviewTab === 'function') switchReviewTab('agree');
                openModal('agreeReviewModal');
                return;
            }

            // 3. Re-Review Modal (Scheduled / On Going -> Need Review)
            const reReviewBtn = e.target.closest('.re-review-btn');
            if (reReviewBtn) {
                e.preventDefault();
                const modal = document.getElementById('reReviewModal');
                modal.querySelector('#reReviewJobDbId').value = reReviewBtn.dataset.jobId;
                modal.querySelector('#reReviewJobId').textContent = reReviewBtn.dataset.idJob;
                openModal('reReviewModal');
                return;
            }

            // 4. Toggle Issue Modal (On Going)
            const issueBtn = e.target.closest('.toggle-issue-btn');
            if (issueBtn) {
                e.preventDefault();
                const modal = document.getElementById('issueJobModal');
                modal.querySelector('#issueJobDbId').value = issueBtn.dataset.jobId;
                modal.querySelector('#issueJobId').textContent = issueBtn.dataset.idJob;
                modal.querySelector('#issueHasIssueCheckbox').checked = issueBtn.dataset.hasIssue === '1';
                modal.querySelector('#issueNote').value = issueBtn.dataset.issueNote || '';
                openModal('issueJobModal');
                return;
            }

            // 5. Split Job Modal (On Going)
            const splitBtn = e.target.closest('.split-job-btn');
            if (splitBtn) {
                e.preventDefault();
                const jobId = splitBtn.dataset.jobId;
                const idJob = splitBtn.dataset.idJob;
                const modal = document.getElementById('splitJobModal');
                modal.querySelector('#splitJobDbId').value = jobId;
                modal.querySelector('#splitJobId').textContent = idJob;

                const container = modal.querySelector('#splitItemsContainer');
                container.innerHTML = '<div class="py-8 text-center text-xs text-slate-400 flex items-center justify-center gap-2"><div class="w-4 h-4 border-2 border-amber-500 border-t-transparent rounded-full animate-spin"></div><span>Memuat rincian item pekerjaan...</span></div>';
                
                const titlePreviewInput = modal.querySelector('#splitChildTitlePreview');
                if (titlePreviewInput) titlePreviewInput.value = `SPLIT DARI ${idJob}...`;

                openModal('splitJobModal');

                fetch(`/kanban/jobs/${jobId}/details`)
                    .then(r => r.json())
                    .then(dt => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(dt.html, 'text/html');
                        const itemBoxes = doc.querySelectorAll('.kanban-item-checkbox');

                        if (itemBoxes.length === 0) {
                            container.innerHTML = '<div class="p-6 text-center text-xs text-amber-600 dark:text-amber-400 font-semibold italic">Tidak ada item individual terdaftar. Seluruh tiket akan dipisah.</div>';
                            updateSplitSummary();
                        } else {
                            let htmlList = '';
                            let index = 1;
                            itemBoxes.forEach(box => {
                                const itemId = box.dataset.itemId;
                                const itemName = box.dataset.itemName;
                                const itemCode = box.dataset.itemCode || '-';
                                const itemLot = box.dataset.itemLot || '-';
                                const itemQty = box.dataset.itemQty || 1;
                                const itemUnit = box.dataset.itemUnit || '';
                                const isDone = box.checked;

                                htmlList += `
                                    <div class="px-3 py-2.5 grid grid-cols-12 text-xs items-center gap-2 hover:bg-amber-50/50 dark:hover:bg-slate-800/60 transition ${isDone ? 'opacity-60 bg-slate-50/50 dark:bg-slate-800/30' : ''}">
                                        <div class="col-span-1 flex justify-center">
                                            <input type="checkbox" name="selected_item_ids[]" value="${itemId}" data-qty="${itemQty}" data-name="${itemName}"
                                                class="split-checkbox w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-amber-600 focus:ring-amber-500/20 cursor-pointer"
                                                ${!isDone ? 'checked' : ''}>
                                        </div>
                                        <div class="col-span-2 font-mono font-bold text-[11px] text-blue-600 dark:text-blue-400 truncate" title="${itemCode}">
                                            ${itemCode}
                                        </div>
                                        <div class="col-span-4 font-medium text-slate-800 dark:text-slate-200 truncate" title="${itemName}">
                                            ${itemName}
                                        </div>
                                        <div class="col-span-2 font-mono text-[10px] font-bold text-amber-800 dark:text-amber-300">
                                            ${itemLot !== '-' ? `<span class="bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800/80 px-1.5 py-0.5 rounded">${itemLot}</span>` : '<span class="text-slate-400">-</span>'}
                                        </div>
                                        <div class="col-span-1 text-center font-bold text-slate-700 dark:text-slate-300">
                                            ${itemQty}
                                        </div>
                                        <div class="col-span-2 text-center">
                                            ${isDone 
                                                ? '<span class="bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Selesai</span>'
                                                : '<span class="bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Belum</span>'
                                            }
                                        </div>
                                    </div>
                                `;
                                index++;
                            });
                            container.innerHTML = htmlList;

                            container.querySelectorAll('.split-checkbox').forEach(cb => {
                                cb.addEventListener('change', updateSplitSummary);
                            });
                            updateSplitSummary();
                        }
                    });
                return;
            }

            function updateSplitSummary() {
                const checkedBoxes = Array.from(document.querySelectorAll('.split-checkbox:checked'));
                const countDisplay = document.getElementById('splitSelectedCountDisplay');
                const balanceDisplay = document.getElementById('splitTotalBalanceDisplay');
                const titlePreview = document.getElementById('splitChildTitlePreview');

                let totalQty = 0;
                const names = [];
                checkedBoxes.forEach(cb => {
                    totalQty += parseFloat(cb.dataset.qty || 1);
                    if (cb.dataset.name) names.push(cb.dataset.name);
                });

                if (countDisplay) countDisplay.textContent = checkedBoxes.length;
                if (balanceDisplay) balanceDisplay.textContent = totalQty;
                if (titlePreview) {
                    const currentJobId = document.getElementById('splitJobId')?.textContent || '';
                    if (names.length > 0) {
                        titlePreview.value = `SPLIT DARI ${currentJobId}: ${names.slice(0, 2).join(', ')}${names.length > 2 ? ' +' + (names.length - 2) + ' item' : ''}`;
                    } else {
                        titlePreview.value = `SPLIT DARI ${currentJobId}`;
                    }
                }
            }

            // Select All Unfinished Button di Split Modal
            if (e.target.id === 'selectAllUnfinishedBtn' || e.target.closest('#selectAllUnfinishedBtn')) {
                e.preventDefault();
                document.querySelectorAll('.split-checkbox').forEach(cb => {
                    const row = cb.closest('div.grid');
                    if (row && !row.classList.contains('opacity-60')) {
                        cb.checked = true;
                    }
                });
                updateSplitSummary();
                return;
            }

            // Deselect All Button di Split Modal
            if (e.target.id === 'deselectAllBtn' || e.target.closest('#deselectAllBtn')) {
                e.preventDefault();
                document.querySelectorAll('.split-checkbox').forEach(cb => cb.checked = false);
                updateSplitSummary();
                return;
            }

            // 6. Move Stage / Forward / Complete / Close
            const moveBtn = e.target.closest('.move-stage-btn');
            if (moveBtn) {
                e.preventDefault();
                const jobId = moveBtn.dataset.jobId;
                const targetStatus = moveBtn.dataset.targetStatus;
                const title = moveBtn.dataset.title;

                const modal = document.getElementById('moveStageModal');
                const form = document.getElementById('moveStageForm');

                if(modal && form) {
                    form.reset(); 
                    modal.querySelector('#move_job_id').value = jobId;
                    modal.querySelector('#move_target_status').value = targetStatus;

                    const titleEl = modal.querySelector('#moveStageTitle');
                    if(titleEl) titleEl.innerText = title || 'Move Stage';

                    openModal('moveStageModal');
                }
                return;
            }

            const fwdBtn = e.target.closest('.forward-job-btn');
            if (fwdBtn) {
                e.preventDefault();
                const modal = document.getElementById('forwardJobModal');
                modal.querySelector('#forward_job_id').value = fwdBtn.dataset.jobId;
                openModal('forwardJobModal');
                return;
            }

            const completeBtn = e.target.closest('.complete-job-btn');
            if (completeBtn) {
                e.preventDefault();
                const modal = document.getElementById('completeJobModal');
                modal.querySelector('#complete_job_id').value = completeBtn.dataset.jobId;
                openModal('completeJobModal');
                return;
            }

            const closeBtn = e.target.closest('.close-job-btn');
            if (closeBtn) {
                e.preventDefault();
                const modal = document.getElementById('closeJobModal');
                modal.querySelector('#close_job_id').value = closeBtn.dataset.jobId;
                openModal('closeJobModal');
                return;
            }

            // 7. Show Details Modal
            const detailBtn = e.target.closest('.show-detail-btn');
            if (detailBtn) {
                e.preventDefault();
                const jobId = detailBtn.dataset.jobId;
                const content = document.getElementById('jobDetailContent');
                openModal('jobDetailModal');
                content.innerHTML = '<div class="flex justify-center p-10"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div></div>';

                fetch(`/kanban/jobs/${jobId}/details`)
                    .then(res => res.json())
                    .then(data => { content.innerHTML = data.html; })
                    .catch(() => { content.innerHTML = '<p class="text-red-500 text-center">Gagal memuat detail job.</p>'; });
                return;
            }

            const cancelBtn = e.target.closest('.cancel-job-btn');
            if (cancelBtn) {
                e.preventDefault();
                const modal = document.getElementById('cancelJobModal');
                if(modal) {
                    modal.querySelector('#cancel_job_id').value = cancelBtn.dataset.jobId;
                    modal.classList.remove('hidden');
                }
                return;
            }
        });

        // ==========================================
        // Event Change untuk Checklist Item (Detail Modal)
        // ==========================================
        document.body.addEventListener('change', function(e) {
            const itemCheckbox = e.target.closest('.kanban-item-checkbox');
            if (itemCheckbox) {
                const jobId = itemCheckbox.dataset.jobId;
                const itemId = itemCheckbox.dataset.itemId;
                const itemName = itemCheckbox.dataset.itemName;
                const willBeCompleted = itemCheckbox.checked;

                Swal.fire({
                    title: 'Konfirmasi Ceklis',
                    text: `Apakah Anda yakin ingin menandai item "${itemName}" sebagai ${willBeCompleted ? 'Selesai' : 'Belum Selesai'}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Konfirmasi',
                    cancelButtonText: 'Batal'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        showSpinner();
                        try {
                            const res = await fetch(`/kanban/jobs/${jobId}/items/${itemId}/toggle`, {
                                method: 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ is_completed: willBeCompleted })
                            });
                            const dt = await res.json();
                            hideSpinner();

                            if (res.ok) {
                                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: dt.message, timer: 2000, showConfirmButton: false });
                                // Refresh detail modal content (timeline & items)
                                fetch(`/kanban/jobs/${jobId}/details`)
                                    .then(r => r.json())
                                    .then(data => { document.getElementById('jobDetailContent').innerHTML = data.html; });
                                
                                // Update background card progress on board immediately
                                if (dt.job && dt.card_html) {
                                    updateKanbanUI(dt.job, dt.card_html);
                                }
                            } else {
                                itemCheckbox.checked = !willBeCompleted;
                                Swal.fire('Gagal', dt.message || 'Gagal mengubah status item.', 'error');
                            }
                        } catch (err) {
                            hideSpinner();
                            itemCheckbox.checked = !willBeCompleted;
                            Swal.fire('Error', 'Koneksi bermasalah.', 'error');
                        }
                    } else {
                        itemCheckbox.checked = !willBeCompleted; // Kembalikan ke posisi semula jika batal
                    }
                });
            }
        });

        // ==========================================
        // Form Submit Listeners with SweetAlert2 Confirmations
        // ==========================================
        const createBtn = document.getElementById('openCreateJobModalBtn');
        if(createBtn) createBtn.addEventListener('click', () => openModal('createJobModal'));

        document.getElementById('createJobForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Buat Tiket Job Baru?',
                text: 'Pastikan rincian item & nomor lot sudah benar. Setelah disubmit, data item & lot bersifat permanen dan tidak dapat diedit.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Buat Tiket',
                cancelButtonText: 'Periksa Kembali',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    closeModal('createJobModal');
                    handleFormSubmit('{{ route("kanban.jobs.store") }}', new FormData(form));
                    form.reset();
                }
            });
        });

        document.getElementById('setScheduleForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const jobId = form.querySelector('#setScheduleJobDbId').value;
            const formData = new FormData(form);
            formData.append('_method', 'PATCH');

            Swal.fire({
                title: 'Simpan Jadwal Pekerjaan?',
                text: 'Tiket akan dipindahkan ke antrean status Scheduled sesuai tanggal yang ditentukan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Jadwalkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    closeModal('setScheduleModal');
                    handleFormSubmit(`/kanban/jobs/${jobId}/schedule`, formData);
                    form.reset();
                }
            });
        });

        document.getElementById('agreeForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const jobId = form.querySelector('#agreeJobDbId').value;
            const formData = new FormData(form);
            formData.append('_method', 'PATCH');

            Swal.fire({
                title: 'Setujui Review QA?',
                text: 'Tiket pekerjaan akan disetujui dan diteruskan ke antrean penjadwalan PPIC.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    closeModal('agreeReviewModal');
                    handleFormSubmit(`/kanban/jobs/${jobId}/agree`, formData);
                    form.reset();
                }
            });
        });

        document.getElementById('returnForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const jobId = form.querySelector('#returnJobDbId').value;
            const formData = new FormData(form);
            formData.append('_method', 'PATCH');

            Swal.fire({
                title: 'Kembalikan dengan Catatan?',
                text: 'Tiket akan dikembalikan ke status On Hold / catatan peninjauan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kembalikan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    closeModal('agreeReviewModal');
                    handleFormSubmit(`/kanban/jobs/${jobId}/return-on-hold`, formData);
                    form.reset();
                }
            });
        });

        document.getElementById('reReviewForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const jobId = form.querySelector('#reReviewJobDbId').value;
            const formData = new FormData(form);
            formData.append('_method', 'PATCH');

            Swal.fire({
                title: 'Tinjau Ulang Tiket?',
                text: 'Tiket akan dimundurkan ke tahap Need Review untuk verifikasi ulang.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tinjau Ulang',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#7c3aed',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    closeModal('reReviewModal');
                    handleFormSubmit(`/kanban/jobs/${jobId}/re-review`, formData);
                    form.reset();
                }
            });
        });

        document.getElementById('issueJobForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const jobId = form.querySelector('#issueJobDbId').value;
            const formData = new FormData(form);
            formData.append('_method', 'PATCH');
            if (!form.querySelector('#issueHasIssueCheckbox').checked) {
                formData.set('has_issue', '0');
            }

            Swal.fire({
                title: 'Simpan Status Kendala?',
                text: 'Perubahan kendala lapangan akan diperbarui pada kartu dan dicatat di riwayat audit.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan Kendala',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    closeModal('issueJobModal');
                    handleFormSubmit(`/kanban/jobs/${jobId}/toggle-issue`, formData);
                    form.reset();
                }
            });
        });

        document.getElementById('splitJobForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = this;
            const selectedCount = form.querySelectorAll('.split-checkbox:checked').length;
            if (selectedCount === 0) {
                Swal.fire('Peringatan', 'Silakan pilih minimal 1 item/lot untuk dipisahkan.', 'warning');
                return;
            }
            const jobId = form.querySelector('#splitJobDbId').value;
            const formData = new FormData(form);

            Swal.fire({
                title: 'Eksekusi Pemecahan Job?',
                text: `Sebanyak ${selectedCount} item/lot akan dipisahkan menjadi tiket job anak baru.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Pecah Job',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#64748b'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    closeModal('splitJobModal');
                    const res = await handleFormSubmit(`/kanban/jobs/${jobId}/split`, formData);
                    if (res && res.status === 'success') {
                        if (res.parent) {
                            fetch(`/kanban/jobs/${res.parent.id}/details`);
                        }
                        if (res.child && res.html_child) {
                            const targetCol = document.getElementById('need-review-column');
                            if (targetCol) {
                                const placeholder = targetCol.querySelector('.empty-text');
                                if (placeholder && placeholder.closest('div.flex')) placeholder.closest('div.flex').remove();

                                const tempDiv = document.createElement('div');
                                tempDiv.innerHTML = res.html_child;
                                targetCol.insertAdjacentElement('afterbegin', tempDiv.firstElementChild);
                                refreshColumnCounters();
                            }
                        }
                    }
                    form.reset();
                }
            });
        });

        document.getElementById('moveStageForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const jobId = form.querySelector('#move_job_id').value;
            const targetStatus = form.querySelector('#move_target_status').value;

            Swal.fire({
                title: 'Konfirmasi Pindah Tahap?',
                text: `Tiket akan dipindahkan ke tahap ${targetStatus.replace('_', ' ').toUpperCase()}.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Pindahkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    closeModal('moveStageModal');
                    handleFormSubmit(`/kanban/jobs/${jobId}/change-status`, new FormData(form));
                    form.reset();
                }
            });
        });

        document.getElementById('forwardJobForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const jobId = form.querySelector('#forward_job_id').value;

            Swal.fire({
                title: 'Teruskan ke Departemen Lain?',
                text: 'Tanggung jawab pekerjaan akan dialihkan ke departemen yang dipilih.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Teruskan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    closeModal('forwardJobModal');
                    handleFormSubmit(`/kanban/jobs/${jobId}/forward`, new FormData(form));
                    form.reset();
                }
            });
        });

        document.getElementById('completeJobForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const jobId = form.querySelector('#complete_job_id').value;
            const formData = new FormData(form);
            formData.append('_method', 'PATCH');

            Swal.fire({
                title: 'Tandai Pekerjaan Selesai?',
                text: 'Pastikan seluruh checklist dan hasil pengerjaan lapangan sudah lengkap.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tandai Selesai',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    closeModal('completeJobModal');
                    handleFormSubmit(`/kanban/jobs/${jobId}/complete`, formData);
                    form.reset();
                }
            });
        });

        document.getElementById('closeJobForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const jobId = form.querySelector('#close_job_id').value;
            const formData = new FormData(form);

            Swal.fire({
                title: 'Tutup & Arsipkan Job?',
                text: 'Tiket akan ditutup secara permanen dan diarsipkan ke kolom Closed.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tutup & Arsipkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#1e293b',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    closeModal('closeJobModal');
                    handleFormSubmit(`/kanban/jobs/${jobId}/close`, formData);
                    form.reset();
                }
            });
        });

        document.getElementById('cancelJobForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const jobId = form.querySelector('#cancel_job_id').value;

            Swal.fire({
                title: 'Batalkan Tiket Pekerjaan?',
                text: 'Pekerjaan ini akan dihentikan secara permanen dan tidak dapat dipulihkan.',
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan Job',
                cancelButtonText: 'Pertahankan Job',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    closeModal('cancelJobModal');
                    handleFormSubmit(`/kanban/jobs/${jobId}/cancel`, new FormData(form));
                    form.reset();
                }
            });
        });

        // Quick Scroll to Archive Closed
        const toggleArchiveBtn = document.getElementById('toggleArchiveScrollBtn');
        const boardContainer = document.getElementById('kanbanBoardContainer');
        if (toggleArchiveBtn && boardContainer) {
            toggleArchiveBtn.addEventListener('click', () => {
                const isScrolledRight = boardContainer.scrollLeft > 250;
                if (isScrolledRight) {
                    boardContainer.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    boardContainer.scrollTo({ left: boardContainer.scrollWidth, behavior: 'smooth' });
                }
            });

            boardContainer.addEventListener('scroll', () => {
                const isScrolledRight = boardContainer.scrollLeft > 250;
                if (isScrolledRight) {
                    toggleArchiveBtn.innerHTML = '<span>⬅ Kembali ke Board Aktif</span>';
                } else {
                    toggleArchiveBtn.innerHTML = '<span>Geser ke Arsip Closed</span> <span class="text-xs">➔</span>';
                }
            });
        }

    });
    </script>
</x-app-layout>
