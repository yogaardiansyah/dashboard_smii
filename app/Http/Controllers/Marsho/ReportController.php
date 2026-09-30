<?php

namespace App\Http\Controllers\Marsho;

use App\Exports\Marsho\MarshoJobsExport;
use App\Http\Controllers\Controller;
use App\Models\Marsho\JobMarsho;
use App\Traits\ConditionQueryTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    use ConditionQueryTrait;

    public function showJobsExportPage(Request $request)
    {
        if ($request->ajax()) {
            $query = JobMarsho::with([
                'pengaju',
                'penutup',
                'area',
                'latestRoute.toDepartment'
            ])->select([
                'id', 
                'id_job', 
                'status', 
                'area_id', 
                'list_job', 
                'pengaju_id', 
                'penutup_id', 
                'tanggal_job_mulai', 
                'tanggal_job_selesai', 
                'closed_at',
                'created_at'
            ]);

            // Handle SearchBuilder criteria
            $searchBuilder = $request->input('searchBuilder.criteria', []);
            $searchLogic = strtolower($request->input('searchBuilder.logic', 'and'));

            if (!empty($searchBuilder)) {
                $query->where(function ($q) use ($searchBuilder, $searchLogic) {
                    foreach ($searchBuilder as $filter) {
                        $column = $filter['origData'] ?? $filter['data'] ?? null;
                        $condition = $filter['condition'] ?? '=';
                        $values = is_array($filter['value']) ? $filter['value'] : (isset($filter['value']) ? [$filter['value']] : []);
                        $value = $values[0] ?? null;

                        if (!$column) continue;

                        $callback = function ($subQuery) use ($column, $condition, $value, $values) {
                            if ($column === 'area' || $column === 'area.name') {
                                $subQuery->whereHas('area', function ($a) use ($condition, $value, $values) {
                                    $this->applyCondition($a, 'name', $condition, $value, $values, false);
                                });
                            } elseif ($column === 'pengaju' || $column === 'pengaju.name') {
                                $subQuery->whereHas('pengaju', function ($u) use ($condition, $value, $values) {
                                    $this->applyCondition($u, 'name', $condition, $value, $values, false);
                                });
                            } elseif ($column === 'penutup' || $column === 'penutup.name') {
                                $subQuery->whereHas('penutup', function ($u) use ($condition, $value, $values) {
                                    $this->applyCondition($u, 'name', $condition, $value, $values, false);
                                });
                            } elseif ($column === 'latest_department') {
                                $subQuery->whereHas('latestRoute.toDepartment', function ($d) use ($condition, $value, $values) {
                                    $this->applyCondition($d, 'department_name', $condition, $value, $values, false);
                                });
                            } elseif (in_array($column, ['tanggal_job_mulai', 'tanggal_job_selesai', 'closed_at', 'created_at'])) {
                                $this->applyCondition($subQuery, $column, $condition, $value, $values, true);
                            } else {
                                $this->applyCondition($subQuery, $column, $condition, $value, $values, false);
                            }
                        };

                        if ($searchLogic === 'or') {
                            $q->orWhere(function ($sub) use ($callback) { $callback($sub); });
                        } else {
                            $q->where(function ($sub) use ($callback) { $callback($sub); });
                        }
                    }
                });
            }

            return DataTables::of($query->latest('id'))
                ->addColumn('id_job_formatted', function ($row) {
                    return '<span class="font-bold text-slate-800">' . e($row->id_job) . '</span>';
                })
                ->addColumn('status_badge', function ($row) {
                    $raw = strtolower(trim((string)$row->status));
                    $s = str_replace(['.', '_', '-'], ' ', $raw);
                    
                    if (str_contains($s, 'to be scheduled') || str_contains($s, 'to be')) {
                        $cls = 'bg-rose-500 text-white';
                        $label = 'To Be Scheduled';
                    } elseif (str_contains($s, 'scheduled')) {
                        $cls = 'bg-sky-500 text-white';
                        $label = 'Scheduled';
                    } elseif (str_contains($s, 'preparation') || str_contains($s, 'preparasi')) {
                        $cls = 'bg-purple-600 text-white';
                        $label = 'Preparation';
                    } elseif (str_contains($s, 'progres') || str_contains($s, 'progress') || str_contains($s, 'ongoing') || str_contains($s, 'on going')) {
                        $cls = 'bg-amber-500 text-white';
                        $label = 'In Progress';
                    } elseif (str_contains($s, 'selesai') || str_contains($s, 'done') || str_contains($s, 'completed')) {
                        $cls = 'bg-emerald-600 text-white';
                        $label = 'Completed';
                    } elseif (str_contains($s, 'closed')) {
                        $cls = 'bg-blue-700 text-white';
                        $label = 'Closed';
                    } elseif (str_contains($s, 'cancel')) {
                        $cls = 'bg-slate-500 text-white';
                        $label = 'Cancelled';
                    } else {
                        $cls = 'bg-slate-500 text-white';
                        $label = ucfirst($row->status ?? 'Unknown');
                    }
                    return '<span class="badge px-2.5 py-1 text-xs font-semibold rounded-full ' . $cls . '">' . $label . '</span>';
                })
                ->addColumn('area', function ($row) { return optional($row->area)->name ?? '-'; })
                ->addColumn('list_job_snippet', function ($row) {
                    $desc = e($row->list_job ?? '-');
                    return '<div class="truncate max-w-xs" title="' . $desc . '">' . $desc . '</div>';
                })
                ->addColumn('latest_department', function ($row) { return optional(optional($row->latestRoute)->toDepartment)->department_name ?? '-'; })
                ->addColumn('pengaju', function ($row) { return optional($row->pengaju)->name ?? '-'; })
                ->addColumn('penutup', function ($row) { return optional($row->penutup)->name ?? '-'; })
                ->addColumn('tanggal_job_mulai_formatted', function ($row) { return $row->tanggal_job_mulai ? $row->tanggal_job_mulai->format('d M Y, H:i') : '-'; })
                ->addColumn('tanggal_job_selesai_formatted', function ($row) { return $row->tanggal_job_selesai ? $row->tanggal_job_selesai->format('d M Y, H:i') : '-'; })
                ->addColumn('closed_at_formatted', function ($row) { return $row->closed_at ? $row->closed_at->format('d M Y, H:i') : '-'; })
                ->addColumn('actions', function ($row) {
                    $viewBtn = '<button type="button" class="pl-icon-btn pl-icon-btn-view view-job-btn" data-bs-toggle="tooltip" title="View Job Details" data-id="' . $row->id . '"><i class="fa-solid fa-eye"></i></button>';
                    return '<div class="flex items-center justify-center gap-1.5">' . $viewBtn . '</div>';
                })
                ->rawColumns(['id_job_formatted', 'status_badge', 'list_job_snippet', 'actions'])
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
