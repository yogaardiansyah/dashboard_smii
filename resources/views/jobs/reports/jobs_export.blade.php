<x-app-layout>
    @section('title', 'Marsho Jobs Export & Report')

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
            .modal-content-container {
                max-height: 75vh;
                overflow-y: auto;
            }
        </style>
    @endpush

    <div class="pl-shell mt-4">
        <!-- Hero Header -->
        <div class="pl-hero">
            <span class="pl-hero-kicker">Marsho Analytics & Reporting</span>
            <div class="pl-hero-title">Jobs Export Report</div>
            <p class="pl-hero-copy">
                Comprehensive overview of all Marsho jobs, execution stages, routing departments, and completion records. Export records directly to Excel.
            </p>
            <div class="pl-toolbar">
                <a href="{{ route('reports.marsho-jobs.export') }}" class="pl-btn pl-btn-secondary" id="btnExportExcel">
                    <i class="fa-solid fa-file-excel mr-2"></i> Export to Excel
                </a>
                <a href="{{ route('jobs.index') }}" class="pl-btn pl-btn-primary">
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
                                <th class="text-center w-20">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Detail Modal -->
    <div id="jobDetailModal" class="pl-modal-overlay">
        <div class="pl-modal-panel pl-modal-panel-lg" style="max-width: 900px;">
            <div class="pl-modal-header">
                <div class="pl-modal-title">
                    <i class="fa-solid fa-circle-info text-blue-600 mr-2"></i> Job Details & Lifecycle
                </div>
                <button type="button" class="pl-close-btn" data-close-modal="jobDetailModal">&times;</button>
            </div>
            <div class="pl-modal-body modal-content-container" id="jobDetailModalBody">
                <div class="flex items-center justify-center py-12 text-slate-400">
                    <i class="fa-solid fa-spinner fa-spin text-2xl mr-2"></i> Loading job details...
                </div>
            </div>
            <div class="pl-modal-footer">
                <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="jobDetailModal">Close</button>
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
                const table = $('#jobsExportTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('reports.marsho-jobs.page') }}',
                        type: 'GET'
                    },
                    dom: 'Qlfrtip',
                    searchBuilder: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                    },
                    columns: [
                        { data: 'id_job_formatted', name: 'id_job' },
                        { data: 'status_badge', name: 'status', className: 'text-center' },
                        { data: 'area', name: 'area.name' },
                        { data: 'list_job_snippet', name: 'list_job' },
                        { data: 'latest_department', name: 'latestRoute.toDepartment.department_name' },
                        { data: 'pengaju', name: 'pengaju.name' },
                        { data: 'tanggal_job_mulai_formatted', name: 'tanggal_job_mulai' },
                        { data: 'tanggal_job_selesai_formatted', name: 'tanggal_job_selesai' },
                        { data: 'penutup', name: 'penutup.name' },
                        { data: 'closed_at_formatted', name: 'closed_at' },
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

                // View Job Detail Modal via AJAX (No reload)
                $(document).on('click', '.view-job-btn', function() {
                    const jobId = $(this).data('id');
                    $('#jobDetailModalBody').html(`
                        <div class="flex items-center justify-center py-12 text-slate-400">
                            <i class="fa-solid fa-spinner fa-spin text-2xl mr-2"></i> Loading job details...
                        </div>
                    `);
                    openModal('jobDetailModal');

                    $.ajax({
                        url: `/jobs/${jobId}/details`,
                        type: 'GET',
                        success: function(res) {
                            if (res.html) {
                                $('#jobDetailModalBody').html(res.html);
                            } else {
                                $('#jobDetailModalBody').html('<p class="text-center text-slate-500 py-6">No detail information available.</p>');
                            }
                        },
                        error: function(xhr) {
                            $('#jobDetailModalBody').html(`
                                <div class="p-4 bg-red-50 text-red-700 rounded-xl text-center">
                                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> Failed to retrieve job details.
                                </div>
                            `);
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
