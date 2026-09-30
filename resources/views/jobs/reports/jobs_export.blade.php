<x-app-layout>

    @section('title')
        Report Job Marsho
    @endsection

    @include('layouts.partials.vendor.datatables')


        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title text-2xl text-bold">Report Job Marsho</h3>
                            <div class="card-tools">
                                {{-- Tombol ini akan mengarah ke route ekspor --}}
                                <a href="{{ route('reports.marsho-jobs.export') }}" class="btn btn-success">
                                    <i class="fas fa-file-excel"></i> Export ke Excel
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="jobs-export-table" class="table table-bordered table-striped"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID Job</th>
                                            <th>Status</th>
                                            <th>Area</th>
                                            <th>List Pekerjaan</th>
                                            <th>Dept. Terakhir</th>
                                            <th>Diajukan Oleh</th>
                                            <th>Tgl Mulai</th>
                                            <th>Tgl Selesai</th>
                                            <th>Ditutup Oleh</th>
                                            <th>Tgl Ditutup</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                $(function() {
                    $('#jobs-export-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{ route('reports.marsho-jobs.page') }}',
                            type: 'GET'
                        },
                        columns: [{
                                data: 'id_job',
                                name: 'id_job'
                            },
                            {
                                data: 'status',
                                name: 'status',
                                render: function(data) {
                                    var raw = (data || '').toString().trim();
                                    var s = raw.toLowerCase().replace(/\./g, '');
                                    var cls, label;
                                    if (s.includes('to be scheduled') || s.includes('to_be_scheduled') || s.includes('to be')) {
                                        cls = 'bg-danger';
                                        label = 'To Be Scheduled';
                                    } else if (s.includes('scheduled')) {
                                        cls = 'bg-info';
                                        label = 'Scheduled';
                                    } else if (s.includes('preparation') || s.includes('preparasi')) {
                                        cls = 'bg-purple-500 text-white';
                                        label = 'Preparation';
                                    } else if (s.includes('progres') || s.includes('in progress') || s.includes('progress') || s.includes('ongoing')) {
                                        cls = 'bg-warning';
                                        label = 'On Going';
                                    } else if (s.includes('selesai') || s.includes('done') || s.includes('completed')) {
                                        cls = 'bg-success';
                                        label = 'Completed';
                                    } else if (s.includes('closed')) {
                                        cls = 'bg-primary';
                                        label = 'Closed';
                                    } else {
                                        return '';
                                    }
                                    return '<span class="badge ' + cls + '">' + label + '</span>';
                                }
                            },
                            {
                                data: 'area',
                                name: 'area'
                            },
                            {
                                data: 'list_job',
                                name: 'list_job',
                                render: function(d) {
                                    return d ? (d.length > 50 ? d.substr(0, 50) + '...' : d) : '';
                                }
                            },
                            {
                                data: 'latest_department',
                                name: 'latest_department'
                            },
                            {
                                data: 'pengaju',
                                name: 'pengaju'
                            },
                            {
                                data: 'tanggal_job_mulai',
                                name: 'tanggal_job_mulai'
                            },
                            {
                                data: 'tanggal_job_selesai',
                                name: 'tanggal_job_selesai'
                            },
                            {
                                data: 'penutup',
                                name: 'penutup'
                            },
                            {
                                data: 'closed_at',
                                name: 'closed_at'
                            }
                        ],
                        order: [
                            [0, 'desc']
                        ]
                    });
                });
            </script>
        @endpush
    </x-app-layout>
