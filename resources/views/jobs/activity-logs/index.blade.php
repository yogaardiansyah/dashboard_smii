<x-app-layout>
    @section('title', 'Kanban Activity Log')

    @include('layouts.partials.vendor.datatables-searchbuilder')

    <style>
        .table-fixed {
            table-layout: fixed;
            width: 100%;
        }

        .table-fixed td,
        .table-fixed th {
            overflow-wrap: break-word;
        }

        table.table.table-bordered td,
        table.table.table-bordered th {
            border: 1px solid rgb(102, 110, 117) !important;
            vertical-align: middle;
        }

        div.card-header {
            border-bottom: 1px solid rgb(102, 110, 117) !important;
        }

        [data-bs-theme="dark"] .table-bordered th,
        [data-bs-theme="dark"] .table-bordered td {
            border-color: #495057 !important;
        }
    </style>

    <div class="container-fluid mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title text-2xl text-bold">Kanban Activity Log</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="activity-logs-table" class="table table-bordered table-striped table-hover table-fixed"
                        style="width:100%">
                        <thead class="bg-blue-500">
                            <tr>
                                <th class="text-white" style="width: 5%;">ID</th>
                                <th class="text-white" style="width: 12%;">Job ID</th>
                                <th class="text-white" style="width: 12%;">Requester</th>
                                <th class="text-white" style="width: 12%;">Area</th>
                                <th class="text-white" style="width: 24%;">Description</th>
                                <th class="text-white" style="width: 10%;">Event</th>
                                <th class="text-white" style="width: 10%;">Performed By</th>
                                <th class="text-white" style="width: 15%;">Time</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{-- DataTables akan menangani paginasi server-side --}}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(function() {
                // Inisialisasi DataTable dengan SearchBuilder
                const table = $('#activity-logs-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('activity-logs.index') }}',
                        data: function(d) {
                            d.subject_filter = $('#subject_filter').val();
                            d.event_filter = $('#event_filter').val();
                            d.causer_filter = $('#causer_filter').val();
                            d.date_from_filter = $('#date_from_filter').val();
                            d.date_to_filter = $('#date_to_filter').val();
                        }
                    },
                    dom: 'Qlfrtip',
                    searchBuilder: true,
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'job_id',
                            name: 'job_id'
                        },
                        {
                            data: 'requester',
                            name: 'requester'
                        },
                        {
                            data: 'area',
                            name: 'area'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'event',
                            name: 'event'
                        },
                        {
                            data: 'performed_by',
                            name: 'performed_by'
                        },
                        {
                            data: 'time',
                            name: 'time'
                        }
                    ],
                    order: [
                        [0, 'desc']
                    ],
                    searchDelay: 400
                });

                // Hook form submit untuk men-trigger reload DataTable
                $('#activity-filter-form').on('submit', function(e) {
                    e.preventDefault();
                    table.ajax.reload();
                });

                $('#filter-reset').on('click', function() {
                    $('#subject_filter').val('');
                    $('#event_filter').val('');
                    $('#causer_filter').val('');
                    $('#date_from_filter').val('');
                    $('#date_to_filter').val('');
                    // Reset SearchBuilder if exists
                    if (table.searchBuilder) {
                        table.searchBuilder.clear();
                    }
                    table.ajax.reload();
                });
            });
        </script>
    @endpush
</x-app-layout>
