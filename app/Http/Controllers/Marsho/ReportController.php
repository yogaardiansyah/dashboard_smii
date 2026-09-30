<?php

namespace App\Http\Controllers\Marsho;

use App\Exports\Marsho\MarshoJobsExport;
use App\Http\Controllers\Controller;
use App\Models\Marsho\JobMarsho;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function showJobsExportPage()
    {
        if (request()->ajax()) {
            $query = JobMarsho::with([
                'pengaju',
                'penutup',
                'area',
                'latestRoute.toDepartment'
            ])->select(['id', 'id_job', 'status', 'area_id', 'list_job', 'pengaju_id', 'penutup_id', 'tanggal_job_mulai', 'tanggal_job_selesai', 'closed_at'])->latest();

            return DataTables::of($query)
                ->addColumn('area', function ($row) { return optional($row->area)->name ?? 'N/A'; })
                ->addColumn('latest_department', function ($row) { return optional(optional($row->latestRoute)->toDepartment)->department_name ?? 'N/A'; })
                ->addColumn('pengaju', function ($row) { return optional($row->pengaju)->name ?? 'N/A'; })
                ->addColumn('penutup', function ($row) { return optional($row->penutup)->name ?? 'N/A'; })
                ->addColumn('tanggal_job_mulai', function ($row) { return $row->tanggal_job_mulai ? $row->tanggal_job_mulai->format('d-m-Y H:i') : ''; })
                ->addColumn('tanggal_job_selesai', function ($row) { return $row->tanggal_job_selesai ? $row->tanggal_job_selesai->format('d-m-Y H:i') : ''; })
                ->addColumn('closed_at', function ($row) { return $row->closed_at ? $row->closed_at->format('d-m-Y H:i') : ''; })
                ->rawColumns([])
                ->make(true);
        }

        return view('jobs.reports.jobs_export');
    }

    public function exportMarshoJobs()
    {
        $fileName = 'marsho_jobs_export_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new MarshoJobsExport, $fileName);
    }
}
