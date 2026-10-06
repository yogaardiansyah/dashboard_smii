<x-app-layout>
    @section('title', 'Kanban Activity Logs')

    @include('layouts.partials.vendor.datatables-searchbuilder')

    @push('css')
        @include('layouts.partials.roleuser_styles')
        <style>
            .pl-icon-btn {
                width: 36px;
                height: 36px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 10px;
                border: 1px solid #e2e8f0;
                background-color: #f8fafc;
                color: #475569;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
            }
            .pl-icon-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            }
            .pl-icon-btn-view:hover {
                background-color: #eff6ff;
                border-color: #93c5fd;
                color: #2563eb;
            }
            /* SearchBuilder Custom Styling for User Management Theme */
            .dtsb-searchBuilder {
                background-color: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 14px;
                padding: 16px;
                margin-bottom: 20px;
            }
            .dtsb-group {
                background: transparent !important;
            }
            .dtsb-criteria {
                margin-bottom: 8px;
            }
            .dtsb-criteria select, .dtsb-criteria input {
                border-radius: 8px !important;
                border: 1px solid #cbd5e1 !important;
                padding: 6px 12px !important;
                font-size: 13px !important;
            }
            .dtsb-button {
                border-radius: 8px !important;
                font-size: 13px !important;
                font-weight: 500 !important;
            }
            .dataTables_wrapper .dataTables_filter input {
                border: 1px solid #cbd5e1;
                border-radius: 8px;
                padding: 6px 12px;
                margin-left: 8px;
            }
            .dataTables_wrapper .dataTables_length select {
                border: 1px solid #cbd5e1;
                border-radius: 8px;
                padding: 6px 28px 6px 12px;
            }
            .log-json-viewer {
                background-color: #0f172a;
                color: #38bdf8;
                border-radius: 12px;
                padding: 14px;
                font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
                font-size: 12px;
                max-height: 260px;
                overflow-y: auto;
                white-space: pre-wrap;
                word-break: break-all;
            }
        </style>
    @endpush

    <div class="pl-shell mt-4">
        <!-- Hero Header -->
        <div class="pl-hero">
            <span class="pl-hero-kicker">Kanban Audit Trail</span>
            <div class="pl-hero-title">Activity Logs</div>
            <p class="pl-hero-copy">
                Track full audit history, job transitions, status changes, handovers, and user operations across Kanban system.
            </p>
            <div class="pl-toolbar">
                <a href="{{ route('kanban.jobs.index') }}" class="pl-btn pl-btn-secondary">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to Kanban Board
                </a>
            </div>
        </div>

        <!-- Table Card -->
        <div class="pl-card">
            <div class="pl-card-head">
                <div class="pl-card-title">Audit Trail Records</div>
                <div class="pl-card-copy">
                    Filter activity events using the SearchBuilder bar below. Click the eye action icon to inspect event properties.
                </div>
            </div>
            <div class="pl-card-body">
                <div class="pl-table-shell table-responsive">
                    <table id="activityLogsTable" class="table table-bordered table-hover w-full" style="width: 100%;">
                        <thead>
                            <tr>
                                <th class="w-16 text-center">ID</th>
                                <th class="w-32">Job ID</th>
                                <th>Requester</th>
                                <th>Area</th>
                                <th>Description</th>
                                <th class="w-24 text-center">Event</th>
                                <th class="w-36">Performed By</th>
                                <th class="w-44">Timestamp</th>
                                <th class="text-center w-20">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Log Detail Modal -->
    <div id="logDetailModal" class="pl-modal-overlay">
        <div class="pl-modal-panel pl-modal-panel-lg">
            <div class="pl-modal-header">
                <div class="pl-modal-title">
                    <i class="fa-solid fa-clock-rotate-left text-blue-600 mr-2"></i> Activity Log Details
                </div>
                <button type="button" class="pl-close-btn" data-close-modal="logDetailModal">&times;</button>
            </div>
            <div class="pl-modal-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                        <span class="text-xs uppercase text-slate-400 font-semibold block">Job ID</span>
                        <span id="detail_job_id" class="text-base font-bold text-slate-800">-</span>
                    </div>
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                        <span class="text-xs uppercase text-slate-400 font-semibold block">Event Type</span>
                        <span id="detail_event" class="text-base font-bold text-blue-600 capitalize">-</span>
                    </div>
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                        <span class="text-xs uppercase text-slate-400 font-semibold block">Performed By</span>
                        <span id="detail_causer" class="text-base font-semibold text-slate-800">-</span>
                    </div>
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                        <span class="text-xs uppercase text-slate-400 font-semibold block">Timestamp</span>
                        <span id="detail_time" class="text-base font-semibold text-slate-800">-</span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="modern-label font-bold">Action Description</label>
                    <div id="detail_desc" class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 font-medium"></div>
                </div>

                <div>
                    <label class="modern-label font-bold">Changed Properties & Context</label>
                    <pre id="detail_properties" class="log-json-viewer"></pre>
                </div>
            </div>
            <div class="pl-modal-footer">
                <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="logDetailModal">Close</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Modal Helper
                function openModal(modalId) {
                    $('#' + modalId).addClass('active').css('display', 'flex');
                }
                function closeModal(modalId) {
                    $('#' + modalId).removeClass('active').css('display', 'none');
                }

                $('[data-close-modal]').on('click', function() {
                    const target = $(this).data('close-modal');
                    closeModal(target);
                });

                $('.pl-modal-overlay').on('click', function(e) {
                    if (e.target === this) {
                        closeModal($(this).attr('id'));
                    }
                });

                $(document).on('keydown', function(e) {
                    if (e.key === 'Escape') {
                        $('.pl-modal-overlay.active').each(function() {
                            closeModal($(this).attr('id'));
                        });
                    }
                });

                // Initialize DataTable with Yajra and SearchBuilder
                const table = $('#activityLogsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('kanban.activity-logs.index') }}',
                        type: 'GET'
                    },
                    dom: 'Qlfrtip',
                    searchBuilder: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    },
                    columns: [
                        { data: 'id', name: 'id', className: 'text-center font-semibold' },
                        { data: 'job_id', name: 'subject.id_job' },
                        { data: 'requester', name: 'subject.pengaju.name', defaultContent: '-' },
                        { data: 'area', name: 'subject.area.name', defaultContent: '-' },
                        { data: 'description', name: 'description', className: 'text-slate-700' },
                        { data: 'event_badge', name: 'event', className: 'text-center' },
                        { data: 'performed_by', name: 'causer.name', defaultContent: 'System' },
                        { data: 'time', name: 'created_at' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
                    ],
                    order: [[0, 'desc']],
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    drawCallback: function() {
                        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                            tooltipTriggerList.map(function(el) {
                                return new bootstrap.Tooltip(el);
                            });
                        }
                    }
                });

                // View Detail Modal Trigger
                $(document).on('click', '.view-detail-btn', function() {
                    const jobId = $(this).data('job');
                    const event = $(this).data('event');
                    const desc = $(this).data('desc');
                    const causer = $(this).data('causer');
                    const time = $(this).data('time');
                    const props = $(this).data('properties');

                    $('#detail_job_id').text(jobId);
                    $('#detail_event').text(event);
                    $('#detail_desc').text(desc);
                    $('#detail_causer').text(causer);
                    $('#detail_time').text(time);

                    try {
                        const parsed = typeof props === 'string' ? JSON.parse(props) : props;
                        $('#detail_properties').text(JSON.stringify(parsed, null, 2));
                    } catch (e) {
                        $('#detail_properties').text(props || 'No extra properties.');
                    }

                    openModal('logDetailModal');
                });
            });
        </script>
    @endpush
</x-app-layout>
