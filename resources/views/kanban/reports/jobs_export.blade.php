<x-app-layout>
    @section('title', 'Kanban Jobs Export & Report')

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
        </style>
    @endpush

    <div class="pl-shell mt-4">
        <!-- Hero Header -->
        <div class="pl-hero">
            <span class="pl-hero-kicker">Kanban Analytics & Reporting</span>
            <div class="pl-hero-title">Kanban Jobs Export Report</div>
            <p class="pl-hero-copy">
                Comprehensive overview of all Kanban jobs, multi-lot status, routing departments, and completion records. Export records directly to Excel.
            </p>
            <div class="pl-toolbar">
                <a href="{{ route('kanban.reports.jobs.export') }}" class="pl-btn pl-btn-secondary" id="btnExportExcel">
                    <i class="fa-solid fa-file-excel mr-2"></i> Export to Excel
                </a>
                <a href="{{ route('kanban.jobs.index') }}" class="pl-btn pl-btn-primary">
                    <i class="fa-solid fa-briefcase mr-2"></i> Kanban Board
                </a>
            </div>
        </div>

        <!-- Table Card -->
        <div class="pl-card">
            <div class="pl-card-head">
                <div class="pl-card-title">Jobs Master Report</div>
                <div class="pl-card-copy">
                    Use the SearchBuilder tool below to build multi-condition filters across ID, status, area, or timestamps.
                </div>
            </div>
            <div class="pl-card-body">
                <div class="pl-table-shell table-responsive">
                    <table id="jobsExportTable" class="table table-bordered table-hover w-full" style="width: 100%;">
                        <thead>
                            <tr>
                                <th class="w-28">Job ID</th>
                                <th class="w-32 text-center">Status</th>
                                <th class="w-32">Area</th>
                                <th>Task Description</th>
                                <th class="w-36">Current Dept</th>
                                <th class="w-36">Requester</th>
                                <th class="w-36">Start Date</th>
                                <th class="w-36">Target End</th>
                                <th class="w-36">Closed By</th>
                                <th class="w-36">Closed At</th>
                                <th class="w-36">Created At</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize DataTable with Yajra and SearchBuilder
                const table = $('#jobsExportTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('kanban.reports.jobs.page') }}',
                        type: 'GET'
                    },
                    dom: 'Qlfrtip',
                    searchBuilder: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    },
                    columns: [
                        { data: 'id_job_formatted', name: 'id_job' },
                        { data: 'status_badge', name: 'status', className: 'text-center' },
                        { data: 'area', name: 'area.name' },
                        { data: 'list_job_short', name: 'list_job' },
                        { data: 'latest_department', name: 'latestRoute.toDepartment.department_name' },
                        { data: 'pengaju', name: 'pengaju.name' },
                        { data: 'tanggal_mulai_formatted', name: 'tanggal_job_mulai' },
                        { data: 'tanggal_selesai_formatted', name: 'tanggal_job_selesai' },
                        { data: 'penutup', name: 'penutup.name' },
                        { data: 'closed_at_formatted', name: 'closed_at' },
                        { data: 'created_at_formatted', name: 'created_at' }
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
            });
        </script>
    @endpush
</x-app-layout>
