@props(['job'])

@php
    $user = Auth::user();
    $isSuperAdmin = $user && ($user->isSuperAdmin() || $user->hasRole('super-admin'));
    $latestRoute = $job->latestRoute;
    $currentDeptId = $latestRoute->to_department_id ?? null;
    $currentDeptName = trim($latestRoute->toDepartment->department_name ?? 'Default');

    $userKanbanDepartmentId = optional($user->kanbanProfile)->kanban_department_id;
    $canAct = ($userKanbanDepartmentId == $currentDeptId) || $isSuperAdmin;
    $isRequester = $job->pengaju_id == optional($user)->id;
    $lastRequester = $job->getLastRequester();
    $canClose = $job->canBeClosedBy($user);
    $isQa = $user && $user->isQa();
    $isPpic = $user && $user->isPpic();

    $departmentColors = [
        'Engineering & Maintainance' => 'bg-blue-600',
        'Engineering' => 'bg-blue-600',
        'Finance Admin' => 'bg-emerald-600',
        'HCD' => 'bg-pink-600',
        'Marsho' => 'bg-indigo-600',
        'Batch' => 'bg-rose-600',
        'QM & HSE' => 'bg-red-600',
        'Quality Assurance' => 'bg-purple-600',
        'R&D' => 'bg-purple-600',
        'Sales & Marketing' => 'bg-sky-600',
        'PPIC' => 'bg-amber-600',
        'Inward Warehouse' => 'bg-lime-600',
        'Outward Warehouse' => 'bg-cyan-600',
        'Purchasing' => 'bg-orange-600',
        'site service' => 'bg-teal-600',
        'Default' => 'bg-slate-600',
    ];
    $headerColor = $departmentColors[$currentDeptName] ?? $departmentColors['Default'];

    $totalItems = $job->items->count();
    $completedItems = $job->items->where('is_completed', true)->count();
    $progressPercent = $totalItems > 0 ? (int) round(($completedItems / $totalItems) * 100) : 0;

    $distinctLots = $job->items->pluck('lot_number')->filter()->unique();
@endphp

<div class="kanban-job-card rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-200 flex flex-col text-slate-800 dark:text-slate-100 relative group border {{ $job->has_issue ? 'border-red-500 ring-2 ring-red-400' : 'border-slate-200/90 dark:border-slate-700/80' }} bg-white dark:bg-[#151d2a]"
    id="job-card-{{ $job->id }}">

    <!-- Header Card -->
    <div class="{{ $headerColor }} p-3 flex justify-between items-start text-white">
        <div>
            <div class="flex items-center gap-1.5 flex-wrap mb-0.5">
                <span class="text-[10px] uppercase opacity-80 block tracking-wider font-semibold">ID Job</span>
                @if($job->isFromApi())
                    <span class="bg-purple-950/80 text-purple-200 border border-purple-400/80 text-[9px] font-extrabold px-1.5 py-0.2 rounded-full uppercase" title="Data dari Integrasi API">
                        ⚡ API
                    </span>
                @endif
                @if($job->parent_id)
                    <span class="bg-amber-400 text-slate-950 text-[9px] font-extrabold px-1.5 py-0.2 rounded-full uppercase" title="Pecahan dari {{ $job->parent->id_job ?? 'Parent' }}">
                        ↳ Pecahan
                    </span>
                @endif
            </div>
            <h3 class="font-bold text-base leading-tight tracking-tight font-mono">{{ $job->id_job }}</h3>
        </div>
        <div class="flex flex-col items-end gap-1">
            <div class="bg-white/20 backdrop-blur-sm px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                {{ \App\Enums\Kanban\JobStatus::label($job->status) }}
            </div>
            @if($job->has_issue)
                <span class="bg-red-500 text-white text-[9px] font-extrabold px-2 py-0.5 rounded-full animate-pulse flex items-center gap-0.5 shadow">
                    <span>⚠️</span> KENDALA
                </span>
            @endif
        </div>
    </div>

    <!-- Body Card -->
    <div class="p-3.5 bg-white dark:bg-[#151d2a] space-y-2.5 text-xs flex-1 text-slate-800 dark:text-slate-200 kanban-card-body">

        <!-- Banner Kendala jika has_issue -->
        @if($job->has_issue && !empty($job->issue_note))
            <div class="p-2.5 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/80 rounded-xl text-red-700 dark:text-red-300 text-[11px] leading-snug">
                <strong class="font-bold">Kendala:</strong> {{ Str::limit($job->issue_note, 60) }}
            </div>
        @endif

        <!-- From & To Department -->
        <div class="grid grid-cols-2 gap-2">
            <div>
                <span class="text-[10px] text-slate-400 dark:text-slate-400 font-medium block mb-0.5">Pengaju (Req. Awal)</span>
                <p class="font-semibold text-slate-900 dark:text-slate-100 truncate text-[11px]" title="{{ $job->pengaju->name ?? '-' }}">
                    {{ $job->pengaju->name ?? ($job->isFromApi() ? 'System / API' : '-') }}
                </p>
                @if($lastRequester && $lastRequester->id !== $job->pengaju_id)
                    <span class="text-[9px] text-blue-600 dark:text-blue-400 font-medium block truncate" title="Requester Terakhir: {{ $lastRequester->name }}">
                        Req. Terakhir: {{ $lastRequester->name }}
                    </span>
                @endif
            </div>
            <div>
                <span class="text-[10px] text-slate-400 dark:text-slate-400 font-medium block mb-0.5">Departemen Tujuan</span>
                <span class="bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-[10px] font-bold px-2 py-0.5 rounded-full truncate inline-block max-w-full">
                    {{ $currentDeptName }}
                </span>
            </div>
        </div>

        <!-- Jadwal Tanggal -->
        <div class="grid grid-cols-2 gap-2 border-t border-slate-100 dark:border-slate-800 pt-2">
            <div>
                <span class="text-[10px] text-slate-400 dark:text-slate-400 font-medium block mb-0.5">Mulai</span>
                @if($job->tanggal_job_mulai)
                    <p class="font-semibold text-slate-900 dark:text-slate-100 text-[11px]">
                        {{ \Carbon\Carbon::parse($job->tanggal_job_mulai)->format('d M Y') }}
                    </p>
                @else
                    <span class="text-amber-500 dark:text-amber-400 font-bold italic text-[10px]">- Menunggu PPIC -</span>
                @endif
            </div>
            <div>
                <span class="text-[10px] text-slate-400 dark:text-slate-400 font-medium block mb-0.5">Deadline</span>
                @if($job->deadline)
                    @php
                        $isOverdue = \Carbon\Carbon::parse($job->deadline)->isPast() && !in_array($job->status, ['completed', 'closed']);
                    @endphp
                    <p class="font-semibold text-[11px] {{ $isOverdue ? 'text-red-600 dark:text-red-400 font-bold' : 'text-slate-900 dark:text-slate-100' }}">
                        {{ \Carbon\Carbon::parse($job->deadline)->format('d M Y') }}
                    </p>
                @else
                    <span class="text-amber-500 dark:text-amber-400 font-bold italic text-[10px]">- Menunggu PPIC -</span>
                @endif
            </div>
        </div>

        <!-- Progress Items / Checklist / Lots -->
        @if($totalItems > 0)
            <div class="border-t border-slate-100 dark:border-slate-800 pt-2 space-y-1.5">
                <div class="flex justify-between items-center text-[10px] text-slate-500 dark:text-slate-400">
                    <span>
                        Checklist ({{ $completedItems }}/{{ $totalItems }})
                        @if($job->balance > 0)
                            <span class="text-blue-600 dark:text-blue-400 font-semibold">• Total Saldo: {{ $job->balance }}</span>
                        @endif
                    </span>
                    <span class="font-bold {{ $progressPercent === 100 ? 'text-emerald-500' : 'text-blue-500' }}">{{ $progressPercent }}%</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                    <div class="h-1.5 rounded-full transition-all duration-300 {{ $progressPercent === 100 ? 'bg-emerald-500' : 'bg-blue-600' }}"
                        style="width: {{ $progressPercent }}%"></div>
                </div>

                <!-- Indikator Multi-Lot -->
                @if($distinctLots->isNotEmpty())
                    <div class="flex items-center gap-1 flex-wrap pt-1 text-[9px]">
                        <span class="font-bold text-slate-400 dark:text-slate-400">Lot ({{ $distinctLots->count() }}):</span>
                        @foreach($distinctLots->take(3) as $lotNum)
                            <span class="bg-amber-50 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 font-mono px-1.5 py-0.2 rounded-md border border-amber-300/80 dark:border-amber-800/80 font-bold">
                                {{ $lotNum }}
                            </span>
                        @endforeach
                        @if($distinctLots->count() > 3)
                            <span class="text-[9px] font-bold text-blue-600 dark:text-blue-400">+{{ $distinctLots->count() - 3 }} lot</span>
                        @endif
                    </div>
                @endif
            </div>
        @elseif($job->balance > 0)
            <div class="border-t border-slate-100 dark:border-slate-800 pt-2 flex justify-between items-center text-[10px]">
                <span class="text-slate-400 dark:text-slate-400">Balance Pekerjaan</span>
                <span class="font-bold text-slate-900 dark:text-slate-100">{{ $job->balance }}</span>
            </div>
        @endif

        <!-- Area Lokasi -->
        <div class="border-t border-slate-100 dark:border-slate-800 pt-2 flex justify-between items-center">
            <span class="text-[10px] text-slate-400 dark:text-slate-400">Lokasi / Area</span>
            @if($job->area)
                <span class="font-semibold text-slate-900 dark:text-slate-100 text-[11px]">{{ $job->area->name }}</span>
            @else
                <span class="text-amber-500 dark:text-amber-400 font-bold italic text-[10px]">- Belum Diatur (Review QA) -</span>
            @endif
        </div>

        <!-- Deskripsi Pekerjaan -->
        <div class="mt-1">
            <span class="text-[10px] text-slate-400 dark:text-slate-400 block mb-0.5">Deskripsi:</span>
            <div class="p-2 bg-slate-50 dark:bg-slate-800/60 text-[11px] text-slate-700 dark:text-slate-300 max-h-16 overflow-y-auto custom-scrollbar rounded-xl border border-slate-100 dark:border-slate-700/60 leading-snug">
                {{ $job->list_job }}
            </div>
        </div>
    </div>

    <!-- Footer & Aksi Card -->
    <div class="p-2.5 {{ $headerColor }} flex flex-col gap-2 rounded-b-2xl text-white">
        <div class="flex justify-between items-center">
            <button type="button" class="show-detail-btn text-white/90 hover:text-white text-[11px] font-medium underline flex items-center gap-1 transition"
                data-job-id="{{ $job->id }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span>Detail & Riwayat</span>
            </button>

            @if($isRequester && !in_array($job->status, ['completed', 'closed', 'cancelled']))
                <button
                    class="cancel-job-btn bg-red-600 hover:bg-red-700 text-white text-[10px] font-bold px-2 py-1 rounded-lg shadow transition"
                    data-job-id="{{ $job->id }}">
                    Cancel
                </button>
            @endif
        </div>

        <!-- Tombol Aksi Sesuai Status Workflow Baru (Need Review -> To Be Scheduled -> Scheduled -> On Going -> Completed -> Closed) -->
        <div class="flex flex-wrap items-center justify-end gap-1.5 pt-1.5 border-t border-white/20">

            {{-- 1. Status: NEED REVIEW --}}
            @if($job->status === 'need_review')
                @if(!$job->isFromApi() || $isQa || $isSuperAdmin)
                    <button type="button"
                        class="agree-review-btn bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-3 py-1.5 rounded-xl shadow transition flex items-center gap-1"
                        data-job-id="{{ $job->id }}"
                        data-id-job="{{ $job->id_job }}"
                        data-area-id="{{ $job->area_id }}"
                        data-start-date="{{ $job->tanggal_job_mulai ? $job->tanggal_job_mulai->format('d M Y') : '-' }}"
                        data-deadline="{{ $job->deadline ? $job->deadline->format('d M Y') : '-' }}"
                        data-desc="{{ $job->list_job }}">
                        🔍 Review / Setujui (QA)
                    </button>
                @else
                    <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-1 rounded-lg" title="Hanya tim QA yang berhak mereview data dari API">
                        Menunggu Review QA
                    </span>
                @endif

            {{-- 2. Status: TO BE SCHEDULED (Khusus PPIC) --}}
            @elseif($job->status === 'to_be_scheduled' || $job->status === 'on_hold')
                @if($isPpic || $isSuperAdmin)
                    <button type="button"
                        class="set-schedule-btn bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold px-3 py-1.5 rounded-xl shadow transition flex items-center gap-1"
                        data-job-id="{{ $job->id }}"
                        data-id-job="{{ $job->id_job }}"
                        data-start-date="{{ $job->tanggal_job_mulai ? $job->tanggal_job_mulai->format('Y-m-d') : date('Y-m-d') }}"
                        data-deadline="{{ $job->deadline ? $job->deadline->format('Y-m-d') : '' }}">
                        📅 Atur Jadwal (PPIC)
                    </button>
                @else
                    <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-1 rounded-lg" title="Hanya tim PPIC yang berhak mengatur jadwal">
                        Menunggu Penjadwalan PPIC
                    </span>
                @endif

            {{-- 3. Status: SCHEDULED --}}
            @elseif($job->status === 'scheduled')
                <button type="button"
                    class="re-review-btn bg-slate-700 hover:bg-slate-800 text-white text-[11px] font-medium px-2.5 py-1.5 rounded-xl shadow transition"
                    data-job-id="{{ $job->id }}" data-id-job="{{ $job->id_job }}" title="Mundurkan tiket ke Need Review">
                    ↺ Tinjau Ulang
                </button>
                @if($canAct || $isPpic || $isSuperAdmin)
                    <button
                        class="move-stage-btn bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-3 py-1.5 rounded-xl shadow transition"
                        data-job-id="{{ $job->id }}" data-target-status="on_going" data-title="Mulai Pengerjaan Fisik (On Going)">
                        Mulai Job ➔
                    </button>
                @endif

            {{-- 4. Status: PREPARATION (Legacy Support) --}}
            @elseif($job->status === 'preparation')
                @if($canAct || $isSuperAdmin)
                    <button
                        class="move-stage-btn bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-3 py-1.5 rounded-xl shadow transition"
                        data-job-id="{{ $job->id }}" data-target-status="on_going" data-title="Mulai Pengerjaan Fisik (On Going)">
                        Mulai Job ➔
                    </button>
                @endif

            {{-- 5. Status: ON GOING --}}
            @elseif($job->status === 'on_going')
                <button type="button"
                    class="toggle-issue-btn {{ $job->has_issue ? 'bg-red-800 text-white' : 'bg-rose-500 hover:bg-rose-600 text-white' }} text-[11px] font-bold px-2.5 py-1.5 rounded-xl shadow transition"
                    data-job-id="{{ $job->id }}" data-id-job="{{ $job->id_job }}" data-has-issue="{{ $job->has_issue ? '1' : '0' }}" data-issue-note="{{ $job->issue_note }}">
                    {{ $job->has_issue ? '⚠️ Edit Kendala' : '⚠️ Kendala' }}
                </button>

                @if($job->items->where('is_completed', false)->count() > 0)
                    <button type="button"
                        class="split-job-btn bg-amber-500 hover:bg-amber-600 text-white text-[11px] font-bold px-2.5 py-1.5 rounded-xl shadow transition"
                        data-job-id="{{ $job->id }}" data-id-job="{{ $job->id_job }}">
                        ✂ Pecah Job
                    </button>
                @endif

                @if($canAct)
                    <button
                        class="forward-job-btn bg-amber-500 hover:bg-amber-600 text-white text-[11px] font-bold px-2.5 py-1.5 rounded-xl shadow transition"
                        data-job-id="{{ $job->id }}">
                        Forward
                    </button>
                    <button
                        class="complete-job-btn bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-3 py-1.5 rounded-xl shadow transition"
                        data-job-id="{{ $job->id }}">
                        Selesai
                    </button>
                @endif

            {{-- 6. Status: COMPLETED (Hanya Requester Terakhir yang boleh Close) --}}
            @elseif($job->status === 'completed')
                @if($canClose)
                    <button
                        class="close-job-btn bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold px-3 py-1.5 rounded-xl shadow transition"
                        data-job-id="{{ $job->id }}">
                        Tutup & Arsipkan
                    </button>
                @else
                    <span class="bg-white/20 text-white text-[10px] font-bold px-2.5 py-1 rounded-xl" title="Menunggu ditutup oleh requester terakhir: {{ $lastRequester?->name ?? 'Pengaju' }}">
                        🔒 Menunggu Ditutup Pengaju
                    </span>
                @endif
            @endif
        </div>
    </div>
</div>
