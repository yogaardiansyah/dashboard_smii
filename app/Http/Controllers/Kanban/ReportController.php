<?php

namespace App\Http\Controllers\Kanban;

use App\Exports\Kanban\KanbanJobsExport;
use App\Http\Controllers\Controller;
use App\Models\Kanban\JobKanban;
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
            $query = JobKanban::with([
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
                    
                    if (str_contains($s, 'need review')) {
                        $cls = 'bg-purple-600 text-white';
                        $label = 'Need Review';
                    } elseif (str_contains($s, 'to be scheduled') || str_contains($s, 'to be')) {
                        $cls = 'bg-amber-500 text-white';
                        $label = 'To Be Scheduled';
                    } elseif (str_contains($s, 'scheduled')) {
                        $cls = 'bg-sky-500 text-white';
                        $label = 'Scheduled';
                    } elseif (str_contains($s, 'on going') || str_contains($s, 'progress')) {
                        $cls = 'bg-emerald-600 text-white';
                        $label = 'On Going';
                    } elseif (str_contains($s, 'completed') || str_contains($s, 'selesai')) {
                        $cls = 'bg-teal-600 text-white';
                        $label = 'Completed';
                    } elseif (str_contains($s, 'closed')) {
                        $cls = 'bg-slate-700 text-white';
                        $label = 'Closed';
                    } elseif (str_contains($s, 'cancelled') || str_contains($s, 'batal')) {
                        $cls = 'bg-rose-600 text-white';
                        $label = 'Cancelled';
                    } else {
                        $cls = 'bg-slate-500 text-white';
                        $label = ucfirst($raw);
                    }

                    return '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ' . $cls . '">' . e($label) . '</span>';
                })
                ->addColumn('area', function ($row) {
                    return optional($row->area)->name ?? '<span class="text-slate-400 italic">No Area</span>';
                })
                ->addColumn('list_job_short', function ($row) {
                    return '<span title="' . e($row->list_job) . '">' . e(\Illuminate\Support\Str::limit($row->list_job, 40)) . '</span>';
                })
                ->addColumn('latest_department', function ($row) {
                    return optional(optional($row->latestRoute)->toDepartment)->department_name ?? '<span class="text-slate-400 italic">-</span>';
                })
                ->addColumn('pengaju', function ($row) {
                    return optional($row->pengaju)->name ?? '<span class="text-slate-400 italic">-</span>';
                })
                ->addColumn('penutup', function ($row) {
                    return optional($row->penutup)->name ?? '<span class="text-slate-400 italic">-</span>';
                })
                ->addColumn('tanggal_mulai_formatted', function ($row) {
                    return $row->tanggal_job_mulai ? $row->tanggal_job_mulai->format('d M Y, H:i') : '-';
                })
                ->addColumn('tanggal_selesai_formatted', function ($row) {
                    return $row->tanggal_job_selesai ? $row->tanggal_job_selesai->format('d M Y, H:i') : '-';
                })
                ->addColumn('closed_at_formatted', function ($row) {
                    return $row->closed_at ? $row->closed_at->format('d M Y, H:i') : '-';
                })
                ->addColumn('created_at_formatted', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y, H:i') : '-';
                })
                ->rawColumns(['id_job_formatted', 'status_badge', 'area', 'list_job_short', 'latest_department', 'pengaju', 'penutup'])
                ->make(true);
        }

        return view('kanban.reports.jobs_export');
    }

    public function exportJobs()
    {
        $fileName = 'kanban_jobs_export_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new KanbanJobsExport, $fileName);
    }
}
