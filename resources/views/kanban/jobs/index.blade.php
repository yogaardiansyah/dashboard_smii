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
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase text-gray-500 tracking-wider">Workflow:</span>
                        <div class="hidden lg:flex items-center text-[11px] text-gray-600 dark:text-gray-400 gap-1 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 shadow-sm">
                            <span class="font-bold text-amber-600">On Hold</span> ➔ 
                            <span class="font-bold text-purple-600">Need Review</span> ➔ 
                            <span class="font-bold text-blue-600">Scheduled</span> ➔ 
                            <span class="font-bold text-indigo-600">Preparation</span> ➔ 
                            <span class="font-bold text-emerald-600">On Going</span> ➔ 
                            <span class="font-bold text-teal-600">Completed</span>
                        </div>
                    </div>

                    <button id="openCreateJobModalBtn"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed shadow text-xs flex items-center gap-1.5"
                            @if($areas->isEmpty() || $departments->isEmpty()) disabled title="Cannot add job: Areas or Departments are not configured." @endif>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        <span>+ Tambah Job Baru</span>
                    </button>
                </div>

                <!-- Kolom-Kolom Kanban Board (7 Tahap) -->
                <div class="flex flex-nowrap overflow-x-auto gap-4 pb-6 items-stretch min-h-[calc(100vh-230px)] px-1">
                    @php 
                        $columnClass = "flex-none w-[85vw] sm:w-[360px] md:w-[380px] lg:w-[360px] flex flex-col"; 
                    @endphp

                    <!-- 1. ON HOLD -->
                    <div class="{{ $columnClass }}">
                        <div class="flex flex-col rounded-xl shadow-md h-full bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900 overflow-hidden kanban-card-container">
                            <div class="bg-amber-600 p-2.5 text-center flex justify-between items-center px-4">
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider">1. On Hold</h3>
                                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $onHoldJobs->count() }}</span>
                            </div>
                            <div id="on-hold-column" class="p-3 space-y-3 kanban-column-body flex-1">
                                @forelse($onHoldJobs as $job) 
                                    @include('kanban.jobs.partials.job_card', ['job' => $job]) 
                                @empty 
                                    <div class="flex items-center justify-center h-40 opacity-60">
                                        <p class="text-xs text-amber-800 dark:text-amber-300 font-bold empty-text">Tidak ada antrean On Hold.</p>
                                    </div> 
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- 2. NEED REVIEW -->
                    <div class="{{ $columnClass }}">
                        <div class="flex flex-col rounded-xl shadow-md h-full bg-purple-50/60 dark:bg-purple-950/20 border border-purple-200 dark:border-purple-900 overflow-hidden kanban-card-container">
                            <div class="bg-purple-600 p-2.5 text-center flex justify-between items-center px-4">
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider">2. Need Review</h3>
                                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $needReviewJobs->count() }}</span>
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

                    <!-- 3. SCHEDULED -->
                    <div class="{{ $columnClass }}">
                        <div class="flex flex-col rounded-xl shadow-md h-full bg-blue-50/60 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900 overflow-hidden kanban-card-container">
                            <div class="bg-blue-600 p-2.5 text-center flex justify-between items-center px-4">
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider">3. Scheduled</h3>
                                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $scheduledJobs->count() }}</span>
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

                    <!-- 4. PREPARATION -->
                    <div class="{{ $columnClass }}">
                        <div class="flex flex-col rounded-xl shadow-md h-full bg-indigo-50/60 dark:bg-indigo-950/20 border border-indigo-200 dark:border-indigo-900 overflow-hidden kanban-card-container">
                            <div class="bg-indigo-600 p-2.5 text-center flex justify-between items-center px-4">
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider">4. Preparation</h3>
                                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $preparationJobs->count() }}</span>
                            </div>
                            <div id="preparation-column" class="p-3 space-y-3 kanban-column-body flex-1">
                                @forelse($preparationJobs as $job) 
                                    @include('kanban.jobs.partials.job_card', ['job' => $job]) 
                                @empty 
                                    <div class="flex items-center justify-center h-40 opacity-60">
                                        <p class="text-xs text-indigo-800 dark:text-indigo-300 font-bold empty-text">Tidak ada job tahap persiapan.</p>
                                    </div> 
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- 5. ON GOING -->
                    <div class="{{ $columnClass }}">
                        <div class="flex flex-col rounded-xl shadow-md h-full bg-emerald-50/60 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900 overflow-hidden kanban-card-container">
                            <div class="bg-emerald-600 p-2.5 text-center flex justify-between items-center px-4">
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider">5. On Going</h3>
                                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $onGoingJobs->count() }}</span>
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

                    <!-- 6. COMPLETED -->
                    <div class="{{ $columnClass }}">
                        <div class="flex flex-col rounded-xl shadow-md h-full bg-teal-50/60 dark:bg-teal-950/20 border border-teal-200 dark:border-teal-900 overflow-hidden kanban-card-container">
                            <div class="bg-teal-600 p-2.5 text-center flex justify-between items-center px-4">
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider">6. Completed</h3>
                                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $completedJobs->count() }}</span>
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

                    <!-- 7. CLOSED -->
                    <div class="{{ $columnClass }}">
                        <div class="flex flex-col rounded-xl shadow-md h-full bg-gray-100 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 overflow-hidden kanban-card-container">
                            <div class="bg-gray-700 p-2.5 text-center flex justify-between items-center px-4">
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider">7. Closed / Archive</h3>
                                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $closedJobs->count() }}</span>
                            </div>
                            <div id="closed-column" class="p-3 space-y-3 kanban-column-body flex-1">
                                @forelse($closedJobs as $job) 
                                    @include('kanban.jobs.partials.job_card', ['job' => $job]) 
                                @empty 
                                    <div class="flex items-center justify-center h-40 opacity-60">
                                        <p class="text-xs text-gray-500 font-bold empty-text">Tidak ada arsip.</p>
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
    <div id="global-spinner" class="hidden fixed inset-0 z-50 bg-black bg-opacity-60 flex items-center justify-center">
        <div class="flex flex-col items-center">
            <div class="w-16 h-16 border-4 border-white border-t-blue-500 rounded-full animate-spin"></div>
            <p class="text-white text-sm mt-4 font-semibold">Memproses permintaan...</p>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        .kanban-column-body { overflow-y: auto; scrollbar-width: thin; }
        .overflow-x-auto::-webkit-scrollbar { height: 10px; }
        .overflow-x-auto::-webkit-scrollbar-track { background: #e5e7eb; border-radius: 5px; }
        .overflow-x-auto::-webkit-scrollbar-thumb { background: #9ca3af; border-radius: 5px; }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #6b7280; }
        .kanban-column-body::-webkit-scrollbar { width: 5px; }

        .dark-skin .bg-white { background-color: rgb(31 41 55 / 1); }
        .dark-skin .bg-gray-50 { background-color: rgb(55 65 81 / 1); }
        .dark-skin .bg-gray-100 { background-color: rgb(55 65 81 / 0.5); }
        .dark-skin .bg-gray-200 { background-color: rgb(75 85 99 / 1); }
        .dark-skin .border-gray-100 { border-color: rgb(55 65 81 / 1); }
        .dark-skin .border-gray-200 { border-color: rgb(75 85 99 / 1); }
        .dark-skin .border-gray-300 { border-color: rgb(107 114 128 / 1); }
        .dark-skin .text-gray-900 { color: rgb(243 244 246 / 1); }
        .dark-skin .text-gray-800 { color: rgb(229 231 235 / 1); }
        .dark-skin .text-gray-700 { color: rgb(209 213 219 / 1); }
        .dark-skin .text-gray-600 { color: rgb(156 163 175 / 1); }
        .dark-skin .text-gray-500 { color: rgb(156 163 175 / 1); }
        .dark-skin .text-gray-400 { color: rgb(156 163 175 / 1); }
        .dark-skin .text-gray-300 { color: rgb(209 213 219 / 1); }
        .dark-skin .empty-text { color: rgb(209 213 219 / 0.5) !important; }
        .dark-skin .overflow-x-auto::-webkit-scrollbar-track { background: #1f2937; }
        .dark-skin .overflow-x-auto::-webkit-scrollbar-thumb { background: #4b5563; }
        .dark-skin .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #6b7280; }
        .dark-skin .kanban-column-body::-webkit-scrollbar-thumb { background: #4b5563; }
        .dark-skin .kanban-column-body::-webkit-scrollbar-thumb:hover { background: #6b7280; }
        .dark-skin select, .dark-skin input[type="text"], .dark-skin input[type="date"], .dark-skin textarea {
            background-color: rgb(31 41 55 / 1);
            color: rgb(243 244 246 / 1);
            border-color: rgb(75 85 99 / 1);
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

        function openModal(id) { 
            const el = document.getElementById(id);
            if(el) el.classList.remove('hidden'); 
        }
        function closeModal(id) { document.getElementById(id)?.classList.add('hidden'); }

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
                const modal = document.getElementById('splitJobModal');
                modal.querySelector('#splitJobDbId').value = jobId;
                modal.querySelector('#splitJobId').textContent = splitBtn.dataset.idJob;

                const container = modal.querySelector('#splitItemsContainer');
                container.innerHTML = '<p class="text-xs text-gray-500 italic p-2">Memuat daftar item...</p>';
                openModal('splitJobModal');

                fetch(`/kanban/jobs/${jobId}/details`)
                    .then(res => res.json())
                    .then(() => {
                        // Ambil items dari card DOM atau detail
                        const card = document.getElementById(`job-card-${jobId}`);
                        // Render checkbox list
                        fetch(`/kanban/jobs/${jobId}/details`)
                            .then(r => r.json())
                            .then(dt => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(dt.html, 'text/html');
                                const itemBoxes = doc.querySelectorAll('.kanban-item-checkbox');

                                if (itemBoxes.length === 0) {
                                    container.innerHTML = '<p class="text-xs text-amber-600 p-2">Tidak ada item individual terdaftar. Seluruh pekerjaan akan dipisah.</p>';
                                } else {
                                    let htmlList = '';
                                    itemBoxes.forEach(box => {
                                        const itemId = box.dataset.itemId;
                                        const itemName = box.dataset.itemName;
                                        const isDone = box.checked;
                                        htmlList += `
                                            <label class="flex items-center gap-2 p-1.5 rounded hover:bg-white dark:hover:bg-gray-600 cursor-pointer text-xs">
                                                <input type="checkbox" name="selected_item_ids[]" value="${itemId}" class="split-checkbox rounded text-amber-600" ${!isDone ? 'checked' : ''}>
                                                <span class="${isDone ? 'line-through text-gray-400' : 'text-gray-800 dark:text-gray-200'}">
                                                    ${itemName} ${isDone ? '(Sudah Selesai)' : '(Belum Selesai)'}
                                                </span>
                                            </label>
                                        `;
                                    });
                                    container.innerHTML = htmlList;
                                }
                            });
                    });
                return;
            }

            // Select All Unfinished Button di Split Modal
            if (e.target.id === 'selectAllUnfinishedBtn') {
                e.preventDefault();
                document.querySelectorAll('.split-checkbox').forEach(cb => cb.checked = true);
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
                                // Refresh detail modal
                                fetch(`/kanban/jobs/${jobId}/details`)
                                    .then(r => r.json())
                                    .then(data => { document.getElementById('jobDetailContent').innerHTML = data.html; });
                                
                                // Refresh background card jika ada
                                fetch(`/kanban/jobs/${jobId}/details`);
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
        // Form Submit Listeners
        // ==========================================
        const createBtn = document.getElementById('openCreateJobModalBtn');
        if(createBtn) createBtn.addEventListener('click', () => openModal('createJobModal'));

        document.getElementById('createJobForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            closeModal('createJobModal');
            handleFormSubmit('{{ route("kanban.jobs.store") }}', new FormData(this));
            this.reset();
        });

        document.getElementById('setScheduleForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const jobId = this.querySelector('#setScheduleJobDbId').value;
            const formData = new FormData(this);
            formData.append('_method', 'PATCH');
            closeModal('setScheduleModal');
            handleFormSubmit(`/kanban/jobs/${jobId}/schedule`, formData);
            this.reset();
        });

        document.getElementById('agreeForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const jobId = this.querySelector('#agreeJobDbId').value;
            const formData = new FormData(this);
            formData.append('_method', 'PATCH');
            closeModal('agreeReviewModal');
            handleFormSubmit(`/kanban/jobs/${jobId}/agree`, formData);
            this.reset();
        });

        document.getElementById('returnForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const jobId = this.querySelector('#returnJobDbId').value;
            const formData = new FormData(this);
            formData.append('_method', 'PATCH');
            closeModal('agreeReviewModal');
            handleFormSubmit(`/kanban/jobs/${jobId}/return-on-hold`, formData);
            this.reset();
        });

        document.getElementById('reReviewForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const jobId = this.querySelector('#reReviewJobDbId').value;
            const formData = new FormData(this);
            formData.append('_method', 'PATCH');
            closeModal('reReviewModal');
            handleFormSubmit(`/kanban/jobs/${jobId}/re-review`, formData);
            this.reset();
        });

        document.getElementById('issueJobForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const jobId = this.querySelector('#issueJobDbId').value;
            const formData = new FormData(this);
            formData.append('_method', 'PATCH');
            if (!this.querySelector('#issueHasIssueCheckbox').checked) {
                formData.set('has_issue', '0');
            }
            closeModal('issueJobModal');
            handleFormSubmit(`/kanban/jobs/${jobId}/toggle-issue`, formData);
            this.reset();
        });

        document.getElementById('splitJobForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const jobId = this.querySelector('#splitJobDbId').value;
            const formData = new FormData(this);
            closeModal('splitJobModal');

            const res = await handleFormSubmit(`/kanban/jobs/${jobId}/split`, formData);
            if (res && res.status === 'success') {
                // Update parent card
                if (res.parent) {
                    fetch(`/kanban/jobs/${res.parent.id}/details`);
                }
                // Prepend child card ke kolom On Hold
                if (res.child && res.html_child) {
                    const onHoldCol = document.getElementById('on-hold-column');
                    if (onHoldCol) {
                        const placeholder = onHoldCol.querySelector('.empty-text');
                        if (placeholder && placeholder.closest('div.flex')) placeholder.closest('div.flex').remove();

                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = res.html_child;
                        onHoldCol.insertAdjacentElement('afterbegin', tempDiv.firstElementChild);
                    }
                }
                Swal.fire('Berhasil', res.message, 'success');
            }
            this.reset();
        });

        document.getElementById('moveStageForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const jobId = this.querySelector('#move_job_id').value;
            closeModal('moveStageModal');
            handleFormSubmit(`/kanban/jobs/${jobId}/change-status`, new FormData(this));
            this.reset();
        });

        document.getElementById('forwardJobForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const jobId = this.querySelector('#forward_job_id').value;
            closeModal('forwardJobModal');
            handleFormSubmit(`/kanban/jobs/${jobId}/forward`, new FormData(this));
            this.reset();
        });

        document.getElementById('completeJobForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const jobId = this.querySelector('#complete_job_id').value;
            const formData = new FormData(this);
            formData.append('_method', 'PATCH');
            closeModal('completeJobModal');
            handleFormSubmit(`/kanban/jobs/${jobId}/complete`, formData);
            this.reset();
        });

        document.getElementById('closeJobForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const jobId = this.querySelector('#close_job_id').value;
            const formData = new FormData(this);

            closeModal('closeJobModal');
            handleFormSubmit(`/kanban/jobs/${jobId}/close`, formData);
            this.reset();
        });

        document.getElementById('cancelJobForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const jobId = this.querySelector('#cancel_job_id').value;
            closeModal('cancelJobModal');
            handleFormSubmit(`/kanban/jobs/${jobId}/cancel`, new FormData(this));
            this.reset();
        });

    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</x-app-layout>
