@php
    $currentStatusColor = \App\Enums\Kanban\JobStatus::badgeClass($job->status);
    $totalItems = $job->items->count();
    $completedItems = $job->items->where('is_completed', true)->count();
    $progressPercent = $totalItems > 0 ? (int) round(($completedItems / $totalItems) * 100) : 0;
@endphp

<div class="flex flex-col text-slate-800 dark:text-slate-200">

    <!-- Header & Info Utama -->
    <div class="p-6 space-y-4 border-b border-slate-200/80 dark:border-slate-800 bg-white dark:bg-[#111827]">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
            <div>
                <span class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider block">Job ID</span>
                <p class="font-bold text-gray-800 dark:text-gray-100 text-base">{{ $job->id_job }}</p>
                @if($job->parent_id)
                    <span class="inline-block mt-0.5 text-[10px] font-bold bg-amber-100 text-amber-800 px-1.5 py-0.2 rounded">
                        Pecahan dari: {{ $job->parent->id_job ?? '-' }}
                    </span>
                @endif
            </div>
            <div>
                <span class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider block">Pengaju</span>
                <p class="font-semibold text-gray-800 dark:text-gray-200 text-xs">{{ $job->pengaju->name ?? 'System/API' }}</p>
            </div>
            <div>
                <span class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider block">Status Saat Ini</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold border {{ $currentStatusColor }}">
                    {{ \App\Enums\Kanban\JobStatus::label($job->status) }}
                </span>
            </div>
            <div>
                <span class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider block">Departemen Aktif</span>
                <p class="font-bold text-gray-800 dark:text-gray-200 text-xs">
                    {{ $job->latestRoute->toDepartment->department_name ?? 'N/A' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs bg-white dark:bg-gray-800 px-2">
            <div>
                <span class="text-gray-400 block text-[10px]">Tanggal Mulai:</span>
                <strong class="text-gray-800 dark:text-gray-200">{{ $job->tanggal_job_mulai ? $job->tanggal_job_mulai->format('d M Y') : 'Belum Dijadwalkan' }}</strong>
            </div>
            <div>
                <span class="text-gray-400 block text-[10px]">Deadline:</span>
                <strong class="{{ $job->deadline && \Carbon\Carbon::parse($job->deadline)->isPast() && !in_array($job->status, ['completed', 'closed']) ? 'text-red-500 font-bold' : 'text-gray-800 dark:text-gray-200' }}">
                    {{ $job->deadline ? $job->deadline->format('d M Y') : 'Belum Ditentukan' }}
                </strong>
            </div>
            <div>
                <span class="text-gray-400 block text-[10px]">Lokasi / Area:</span>
                <strong class="text-gray-800 dark:text-gray-200">{{ $job->area->name ?? '-' }}</strong>
            </div>
            <div>
                <span class="text-gray-400 block text-[10px]">Saldo / Balance:</span>
                <strong class="text-gray-800 dark:text-gray-200">{{ $job->balance }} item</strong>
            </div>
        </div>

        @if($job->children->isNotEmpty())
            <div class="p-2.5 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded text-xs">
                <span class="font-bold text-blue-800 dark:text-blue-300">Pecahan Job dari Tiket ini (Job Anak):</span>
                <div class="flex flex-wrap gap-2 mt-1">
                    @foreach($job->children as $child)
                        <span class="px-2 py-0.5 bg-blue-600 text-white rounded font-mono text-[10px]">
                            {{ $child->id_job }} ({{ \App\Enums\Kanban\JobStatus::label($child->status) }})
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        @if($job->has_issue)
            <div class="p-3 bg-red-50 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-md text-xs text-red-800 dark:text-red-300">
                <strong>⚠️ Peringatan Kendala:</strong> {{ $job->issue_note ?: 'Pekerjaan sedang terkendala di lapangan.' }}
            </div>
        @endif

        <!-- Deskripsi & Keterangan -->
        <div class="space-y-2 text-xs">
            <div class="bg-gray-50 dark:bg-gray-700/40 p-3 rounded border border-gray-200 dark:border-gray-600">
                <h5 class="font-bold text-[11px] text-gray-700 dark:text-gray-300 uppercase mb-1">Deskripsi Pekerjaan</h5>
                <p class="text-gray-600 dark:text-gray-300 whitespace-pre-wrap">{{ $job->list_job }}</p>
            </div>
            @if($job->reason_description && $job->reason_description !== $job->list_job)
                <div class="bg-gray-50 dark:bg-gray-700/40 p-3 rounded border border-gray-200 dark:border-gray-600">
                    <h5 class="font-bold text-[11px] text-gray-700 dark:text-gray-300 uppercase mb-1">Alasan Kebutuhan (Reason)</h5>
                    <p class="text-gray-600 dark:text-gray-300 whitespace-pre-wrap">{{ $job->reason_description }}</p>
                </div>
            @endif
            @if($job->remark)
                <div class="bg-gray-50 dark:bg-gray-700/40 p-3 rounded border border-gray-200 dark:border-gray-600">
                    <h5 class="font-bold text-[11px] text-gray-700 dark:text-gray-300 uppercase mb-1">Catatan Khusus (Remark)</h5>
                    <p class="text-gray-600 dark:text-gray-300 whitespace-pre-wrap">{{ $job->remark }}</p>
                </div>
            @endif
        </div>

        <!-- Checklist Item Interaktif -->
        @if($totalItems > 0)
            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-3.5 bg-white dark:bg-gray-800 shadow-sm">
                <div class="flex justify-between items-center mb-2">
                    <h5 class="font-bold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-wider flex items-center gap-1.5 flex-wrap">
                        <span>Daftar Checklist Item</span>
                        <span class="text-[10px] bg-blue-100 text-blue-800 px-2 py-0.2 rounded-full font-bold">
                            {{ $completedItems }}/{{ $totalItems }} Selesai ({{ $progressPercent }}%)
                        </span>
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-slate-100 dark:bg-slate-700/80 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 px-2 py-0.5 rounded-full" title="Nomor Lot & Nama Item bersifat permanen/audit record">
                            <svg class="w-3 h-3 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Data Terkunci (Read-Only)
                        </span>
                    </h5>
                    <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden flex-shrink-0">
                        <div class="h-2 rounded-full {{ $progressPercent === 100 ? 'bg-emerald-500' : 'bg-blue-600' }}"
                            style="width: {{ $progressPercent }}%"></div>
                    </div>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-48 overflow-y-auto custom-scrollbar">
                    @foreach($job->items as $item)
                        <div class="py-2 flex items-center justify-between gap-3 text-xs hover:bg-gray-50 dark:hover:bg-gray-700/50 px-2 rounded transition">
                            <label class="flex items-center gap-2 cursor-pointer flex-1">
                                <input type="checkbox"
                                    class="kanban-item-checkbox rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200"
                                    data-job-id="{{ $job->id }}"
                                    data-item-id="{{ $item->id }}"
                                    data-item-name="{{ $item->item_name }}"
                                    data-item-code="{{ $item->item_code }}"
                                    data-item-lot="{{ $item->lot_number }}"
                                    data-item-qty="{{ $item->qty }}"
                                    data-item-unit="{{ $item->unit }}"
                                    {{ $item->is_completed ? 'checked' : '' }}
                                    {{ in_array($job->status, ['completed', 'closed', 'cancelled']) ? 'disabled' : '' }}>
                                <span class="{{ $item->is_completed ? 'line-through text-gray-400 font-medium' : 'text-gray-800 dark:text-gray-200' }}">
                                    @if($item->item_code)
                                        <span class="font-mono text-[11px] font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/40 px-1.5 py-0.5 rounded">[{{ $item->item_code }}]</span>
                                    @endif
                                    <span>{{ $item->item_name }}</span>
                                    @if($item->lot_number)
                                        <span class="inline-block ml-1 px-1.5 py-0.2 text-[10px] font-mono font-bold rounded bg-amber-50 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-700">Lot: {{ $item->lot_number }}</span>
                                    @endif
                                    @if($item->qty)
                                        <span class="inline-block ml-1 px-1.5 py-0.2 text-[10px] font-bold rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                            x{{ $item->qty }} {{ $item->unit ?? '' }}
                                        </span>
                                    @endif
                                </span>
                            </label>
                            @if($item->is_completed)
                                <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold whitespace-nowrap">
                                    ✓ Selesai
                                    @if($item->completedBy)
                                        ({{ $item->completedBy->name }})
                                    @endif
                                </span>
                            @else
                                <span class="text-[10px] text-amber-600 dark:text-amber-400 whitespace-nowrap">⏳ Pending</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Riwayat Aktivitas / Timeline -->
    <div class="p-6 bg-slate-50/60 dark:bg-slate-900/40">

        <h4 class="font-bold text-sm text-slate-800 dark:text-slate-200 mb-5 border-b border-slate-200 dark:border-slate-700 pb-2">
            Riwayat Jejak Audit & Timeline
        </h4>

        <div class="relative border-l-2 border-gray-300 dark:border-gray-600 ml-6 space-y-6 pb-4">
            @foreach($activities as $activity)
                <div class="ml-6 relative group">
                    <span class="absolute -left-[37px] flex items-center justify-center w-7 h-7 rounded-full ring-4 ring-white dark:ring-gray-800 shadow-sm {{ $activity['type'] == 'route' ? 'bg-yellow-500' : 'bg-blue-600' }}">
                        @if($activity['type'] == 'route') 
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        @else 
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        @endif
                    </span>
                    <div class="bg-white dark:bg-gray-800 p-3.5 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition w-full text-xs">
                        <div class="flex justify-between items-start mb-1.5">
                            <div>
                                @if($activity['type'] == 'route')
                                    <h5 class="font-bold text-yellow-700 dark:text-yellow-400 uppercase tracking-wide">Perpindahan / Handover</h5>
                                    <div class="mt-0.5 font-semibold text-gray-500 dark:text-gray-400 flex items-center gap-1.5 flex-wrap">
                                        <span>{{ $activity['data']->fromDepartment->department_name ?? 'Requester' }}</span>
                                        <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        <span>{{ $activity['data']->toDepartment->department_name }}</span>
                                    </div>
                                @else
                                    <h5 class="font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wide">Status / Catatan Progres</h5>
                                @endif
                                <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5 flex-wrap">
                                    <span>Oleh: <strong class="text-slate-700 dark:text-slate-300">{{ $activity['creator']->name ?? 'System' }}</strong></span>
                                    @if(!empty($activity['data']->ip_address))
                                        <span class="font-mono bg-slate-100 dark:bg-slate-700 px-1.5 py-0.2 rounded text-[9px] text-slate-600 dark:text-slate-300">IP: {{ $activity['data']->ip_address }}</span>
                                    @endif
                                </div>
                            </div>
                            <span class="text-[10px] text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded whitespace-nowrap ml-2">
                                {{ \Carbon\Carbon::parse($activity['timestamp'])->format('d M Y, H:i') }}
                            </span>
                        </div>

                        <div class="text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-2.5 rounded border-l-4 {{ $activity['type'] == 'route' ? 'border-yellow-400' : 'border-blue-400' }}">
                            {!! nl2br(e($activity['data']->note)) !!}
                        </div>

                        @if($activity['files']->count() > 0)
                            <div class="mt-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                                <p class="text-[10px] font-bold text-gray-500 mb-1.5">File Lampiran:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($activity['files'] as $file)
                                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                            class="flex items-center gap-1.5 px-2.5 py-1.5 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded text-blue-600 dark:text-blue-400 hover:underline text-[11px]">
                                            📄 {{ $file->file_name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
