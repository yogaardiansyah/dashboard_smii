@props(['job'])

@php
    $user = Auth::user();
    $isSuperAdmin = $user && $user->isSuperAdmin();
    $latestRoute = $job->latestRoute;
    $currentDeptId = $latestRoute->to_department_id ?? null;
    $currentDeptName = trim($latestRoute->toDepartment->department_name ?? 'Default');

    $userKanbanDepartmentId = optional($user->kanbanProfile)->kanban_department_id;
    $canAct = ($userKanbanDepartmentId == $currentDeptId) || $isSuperAdmin;
    $isRequester = $job->pengaju_id == $user->id;

    $departmentColors = [
        'Engineering & Maintainance' => 'bg-blue-600',
        'Finance Admin' => 'bg-green-600',
        'HCD' => 'bg-pink-600',
        'Marsho' => 'bg-indigo-600',
        'Batch' => 'bg-rose-600',
        'QM & HSE' => 'bg-red-600',
        'R&D' => 'bg-purple-600',
        'Sales & Marketing' => 'bg-sky-600',
        'PPIC' => 'bg-amber-600',
        'Inward Warehouse' => 'bg-lime-600',
        'Outward Warehouse' => 'bg-cyan-600',
        'Purchasing' => 'bg-orange-600',
        'site service' => 'bg-teal-600',
        'Default' => 'bg-gray-600',
    ];
    $headerColor = $departmentColors[$currentDeptName] ?? $departmentColors['Default'];

    $totalItems = $job->items->count();
    $completedItems = $job->items->where('is_completed', true)->count();
    $progressPercent = $totalItems > 0 ? (int) round(($completedItems / $totalItems) * 100) : 0;
@endphp

<div class="rounded-xl overflow-hidden shadow-lg flex flex-col text-white relative group transition hover:shadow-2xl border {{ $job->has_issue ? 'border-red-500 ring-2 ring-red-400' : 'border-gray-200 dark:border-gray-700' }}"
    id="job-card-{{ $job->id }}">

    <!-- Header Card -->
    <div class="{{ $headerColor }} p-3 flex justify-between items-start">
        <div>
            <div class="flex items-center gap-1.5 flex-wrap">
                <span class="text-[10px] uppercase opacity-75 block tracking-wider">ID Job</span>
                @if($job->parent_id)
                    <span class="bg-amber-400 text-gray-900 text-[9px] font-extrabold px-1.5 py-0.2 rounded-full uppercase" title="Pecahan dari {{ $job->parent->id_job ?? 'Parent' }}">
                        ↳ Pecahan
                    </span>
                @endif
            </div>
            <h3 class="font-bold text-base leading-tight">{{ $job->id_job }}</h3>
        </div>
        <div class="flex flex-col items-end gap-1">
            <div class="bg-white/20 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide">
                {{ \App\Enums\Kanban\JobStatus::label($job->status) }}
            </div>
            @if($job->has_issue)
                <span class="bg-red-500 text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded-full animate-pulse flex items-center gap-0.5">
                    <span>⚠️</span> KENDALA
                </span>
            @endif
        </div>
    </div>

    <!-- Body Card -->
    <div class="p-3.5 bg-white dark:bg-gray-800 space-y-2.5 text-xs flex-1 text-gray-800 dark:text-gray-200">

        <!-- Banner Kendala jika has_issue -->
        @if($job->has_issue && !empty($job->issue_note))
            <div class="p-2 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded text-red-700 dark:text-red-300 text-[11px]">
                <strong>Kendala:</strong> {{ Str::limit($job->issue_note, 60) }}
            </div>
        @endif

        <!-- From & To Department -->
        <div class="grid grid-cols-2 gap-2">
            <div>
                <span class="text-[10px] text-gray-400 block mb-0.5">Pengaju</span>
                <p class="font-semibold text-gray-900 dark:text-gray-100 truncate" title="{{ $job->pengaju->name ?? '-' }}">
                    {{ $job->pengaju->name ?? 'System/API' }}
                </p>
            </div>
            <div>
                <span class="text-[10px] text-gray-400 block mb-0.5">Departemen Tujuan</span>
                <span class="bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300 text-[10px] font-bold px-2 py-0.5 rounded-full truncate inline-block max-w-full">
                    {{ $currentDeptName }}
                </span>
            </div>
        </div>

        <!-- Jadwal Tanggal -->
        <div class="grid grid-cols-2 gap-2 border-t border-gray-100 dark:border-gray-700 pt-2">
            <div>
                <span class="text-[10px] text-gray-400 block mb-0.5">Mulai</span>
                @if($job->tanggal_job_mulai)
                    <p class="font-medium text-gray-900 dark:text-gray-100">
                        {{ \Carbon\Carbon::parse($job->tanggal_job_mulai)->format('d M Y') }}
                    </p>
                @else
                    <span class="text-amber-500 font-bold italic text-[11px]">- Kosong -</span>
                @endif
            </div>
            <div>
                <span class="text-[10px] text-gray-400 block mb-0.5">Deadline</span>
                @if($job->deadline)
                    @php
                        $isOverdue = \Carbon\Carbon::parse($job->deadline)->isPast() && !in_array($job->status, ['completed', 'closed']);
                    @endphp
                    <p class="font-medium {{ $isOverdue ? 'text-red-600 font-bold' : 'text-gray-700 dark:text-gray-300' }}">
                        {{ \Carbon\Carbon::parse($job->deadline)->format('d M Y') }}
                    </p>
                @else
                    <span class="text-amber-500 font-bold italic text-[11px]">- Kosong -</span>
                @endif
            </div>
        </div>

        <!-- Progress Items / Checklist -->
        @if($totalItems > 0)
            <div class="border-t border-gray-100 dark:border-gray-700 pt-2">
                <div class="flex justify-between items-center text-[10px] text-gray-500 dark:text-gray-400 mb-1">
                    <span>Item Checklist ({{ $completedItems }}/{{ $totalItems }})</span>
                    <span class="font-bold {{ $progressPercent === 100 ? 'text-emerald-500' : 'text-blue-500' }}">{{ $progressPercent }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 overflow-hidden">
                    <div class="h-1.5 rounded-full transition-all duration-300 {{ $progressPercent === 100 ? 'bg-emerald-500' : 'bg-blue-600' }}"
                        style="width: {{ $progressPercent }}%"></div>
                </div>
            </div>
        @elseif($job->balance > 0)
            <div class="border-t border-gray-100 dark:border-gray-700 pt-2 flex justify-between items-center text-[10px]">
                <span class="text-gray-400">Balance Pekerjaan</span>
                <span class="font-bold text-gray-800 dark:text-gray-200">{{ $job->balance }}</span>
            </div>
        @endif

        <!-- Area Lokasi -->
        <div class="border-t border-gray-100 dark:border-gray-700 pt-2 flex justify-between items-center">
            <span class="text-[10px] text-gray-400">Lokasi / Area</span>
            <span class="font-bold text-gray-900 dark:text-gray-100">{{ $job->area->name ?? '-' }}</span>
        </div>

        <!-- Deskripsi Pekerjaan -->
        <div class="mt-1">
            <span class="text-[10px] text-gray-400 block mb-0.5">Deskripsi:</span>
            <div class="p-2 bg-gray-50 dark:bg-gray-700/50 text-[11px] text-gray-700 dark:text-gray-300 max-h-16 overflow-y-auto custom-scrollbar rounded-md">
                {{ $job->list_job }}
            </div>
        </div>
    </div>

    <!-- Footer & Aksi Card -->
    <div class="p-2.5 {{ $headerColor }} flex flex-col gap-2">
        <div class="flex justify-between items-center">
            <button type="button" class="show-detail-btn text-white hover:text-white/80 text-[11px] underline flex items-center gap-0.5"
                data-job-id="{{ $job->id }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span>Detail & Riwayat</span>
            </button>

            @if($isRequester && !in_array($job->status, ['completed', 'closed', 'cancelled']))
                <button
                    class="cancel-job-btn bg-red-600 hover:bg-red-700 text-white text-[10px] font-bold px-2 py-1 rounded shadow transition"
                    data-job-id="{{ $job->id }}">
                    Cancel
                </button>
            @endif
        </div>

        <!-- Tombol Aksi Sesuai Status Workflow -->
        <div class="flex flex-wrap items-center justify-end gap-1.5 pt-1 border-t border-white/20">

            {{-- 1. Status: ON HOLD --}}
            @if($job->status === 'on_hold')
                <button type="button"
                    class="set-schedule-btn bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-3 py-1.5 rounded shadow transition flex items-center gap-1"
                    data-job-id="{{ $job->id }}"
                    data-id-job="{{ $job->id_job }}"
                    data-start-date="{{ $job->tanggal_job_mulai ? $job->tanggal_job_mulai->format('Y-m-d') : date('Y-m-d') }}"
                    data-deadline="{{ $job->deadline ? $job->deadline->format('Y-m-d') : '' }}">
                    📅 Atur Jadwal
                </button>

            {{-- 2. Status: NEED REVIEW --}}
            @elseif($job->status === 'need_review')
                <button type="button"
                    class="agree-review-btn bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-3 py-1.5 rounded shadow transition flex items-center gap-1"
                    data-job-id="{{ $job->id }}"
                    data-id-job="{{ $job->id_job }}"
                    data-start-date="{{ $job->tanggal_job_mulai ? $job->tanggal_job_mulai->format('d M Y') : '-' }}"
                    data-deadline="{{ $job->deadline ? $job->deadline->format('d M Y') : '-' }}"
                    data-desc="{{ $job->list_job }}">
                    🔍 Review / Setujui
                </button>

            {{-- 3. Status: SCHEDULED --}}
            @elseif($job->status === 'scheduled')
                <button type="button"
                    class="re-review-btn bg-gray-700 hover:bg-gray-800 text-white text-[11px] font-medium px-2 py-1 rounded shadow transition"
                    data-job-id="{{ $job->id }}" data-id-job="{{ $job->id_job }}" title="Mundurkan tiket ke Need Review">
                    ↺ Tinjau Ulang
                </button>
                @if($canAct)
                    <button
                        class="move-stage-btn bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-3 py-1.5 rounded shadow transition"
                        data-job-id="{{ $job->id }}" data-target-status="preparation" data-title="Mulai Persiapan (Preparation)">
                        Mulai Prep
                    </button>
                @endif

            {{-- 4. Status: PREPARATION --}}
            @elseif($job->status === 'preparation')
                <button type="button"
                    class="re-review-btn bg-gray-700 hover:bg-gray-800 text-white text-[11px] font-medium px-2 py-1 rounded shadow transition"
                    data-job-id="{{ $job->id }}" data-id-job="{{ $job->id_job }}" title="Mundurkan tiket ke Need Review">
                    ↺ Tinjau Ulang
                </button>
                @if($canAct)
                    <button
                        class="move-stage-btn bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-3 py-1.5 rounded shadow transition"
                        data-job-id="{{ $job->id }}" data-target-status="on_going" data-title="Mulai Pengerjaan Fisik (On Going)">
                        Mulai Job
                    </button>
                @endif

            {{-- 5. Status: ON GOING --}}
            @elseif($job->status === 'on_going')
                <button type="button"
                    class="toggle-issue-btn {{ $job->has_issue ? 'bg-red-800 text-white' : 'bg-rose-500 hover:bg-rose-600 text-white' }} text-[11px] font-bold px-2 py-1 rounded shadow transition"
                    data-job-id="{{ $job->id }}" data-id-job="{{ $job->id_job }}" data-has-issue="{{ $job->has_issue ? '1' : '0' }}" data-issue-note="{{ $job->issue_note }}">
                    {{ $job->has_issue ? '⚠️ Edit Kendala' : '⚠️ Kendala' }}
                </button>

                @if($job->items->where('is_completed', false)->count() > 0)
                    <button type="button"
                        class="split-job-btn bg-amber-500 hover:bg-amber-600 text-white text-[11px] font-bold px-2.5 py-1 rounded shadow transition"
                        data-job-id="{{ $job->id }}" data-id-job="{{ $job->id_job }}">
                        ✂ Pecah Job
                    </button>
                @endif

                @if($canAct)
                    <button
                        class="forward-job-btn bg-yellow-500 hover:bg-yellow-600 text-white text-[11px] font-bold px-2.5 py-1 rounded shadow transition"
                        data-job-id="{{ $job->id }}">
                        Forward
                    </button>
                    <button
                        class="complete-job-btn bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-3 py-1 rounded shadow transition"
                        data-job-id="{{ $job->id }}">
                        Selesai
                    </button>
                @endif

            {{-- 6. Status: COMPLETED --}}
            @elseif($job->status === 'completed' && ($isRequester || $isSuperAdmin))
                <button
                    class="close-job-btn bg-gray-700 hover:bg-gray-800 text-white text-xs font-bold px-3 py-1.5 rounded shadow transition"
                    data-job-id="{{ $job->id }}">
                    Tutup & Arsipkan
                </button>
            @endif
        </div>
    </div>
</div>
