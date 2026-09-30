<?php

namespace App\Http\Controllers\Kanban;

use App\Http\Controllers\Controller;
use App\Models\Kanban\JobKanban;
use Illuminate\Http\Request;
use App\Exports\Kanban\KanbanJobsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Untuk menampilkan halaman laporan Kanban jobs.
     *
     * @return \Illuminate\View\View
     */
    public function showJobsExportPage()
    {
        $jobs = JobKanban::with([
            'pengaju', 
            'penutup', 
            'area', 
            'latestRoute.toDepartment'
        ])->latest()->get();

        return view('kanban.reports.jobs_export', compact('jobs'));
    }

    /**
     * Untuk memproses unduhan Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportJobs()
    {
        $fileName = 'kanban_jobs_export_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new KanbanJobsExport, $fileName);
    }
}
