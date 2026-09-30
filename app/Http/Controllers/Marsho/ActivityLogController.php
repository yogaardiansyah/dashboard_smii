<?php

namespace App\Http\Controllers\Marsho;

use App\Http\Controllers\Controller;
use App\Models\Marsho\JobMarsho;
use App\Models\Marsho\MarshoActivityLog;
use App\Models\User;
use App\Traits\ConditionQueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    use ConditionQueryTrait;

    /**
     * Display activity logs with filtering.
     */
    public function index(Request $request): mixed
    {
        $user = Auth::user();

        $query = MarshoActivityLog::with([
            'causer',
            'subject',
        ]);

        $query->where('subject_type', JobMarsho::class);

        // Access control: non super-admins only see logs of jobs they requested
        if ($user && method_exists($user, 'isSuperAdmin') && !$user->isSuperAdmin()) {
            $query->whereHasMorph('subject', [JobMarsho::class], function ($jobQuery) use ($user) {
                $jobQuery->where('pengaju_id', $user->id);
            });
        }

        if ($request->filled('subject_filter')) {
            $jobId = $request->input('subject_filter');
            $query->whereHasMorph('subject', [JobMarsho::class], function ($jobQuery) use ($jobId) {
                $jobQuery->where('id_job', 'like', '%' . $jobId . '%');
            });
        }

        if ($request->filled('event_filter')) {
            $query->where('event', $request->input('event_filter'));
        }

        if ($request->filled('causer_filter')) {
            $query->where('causer_id', $request->input('causer_filter'));
        }

        if ($request->filled('date_from_filter')) {
            $query->whereDate('created_at', '>=', $request->input('date_from_filter'));
        }
        if ($request->filled('date_to_filter')) {
            $query->whereDate('created_at', '<=', $request->input('date_to_filter'));
        }

        $filterQuery = clone $query;
        $causerIds = $filterQuery->pluck('causer_id')->unique()->filter();
        $users = User::whereIn('id', $causerIds)->orderBy('name')->pluck('name', 'id');
        $eventNames = $filterQuery->select('event')->distinct()->pluck('event');

        if ($request->ajax()) {
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
                            if (in_array($column, ['job_id', 'id_job', 'subject.id_job'])) {
                                $subQuery->whereHasMorph('subject', [JobMarsho::class], function ($jobQ) use ($condition, $value, $values) {
                                    $this->applyCondition($jobQ, 'id_job', $condition, $value, $values, false);
                                });
                            } elseif (in_array($column, ['causer', 'causer.name', 'causer_id', 'performed_by'])) {
                                if ($column === 'causer' || $column === 'causer.name' || $column === 'performed_by') {
                                    $subQuery->whereHas('causer', function ($u) use ($condition, $value, $values) {
                                        $this->applyCondition($u, 'name', $condition, $value, $values, false);
                                    });
                                } else {
                                    $this->applyCondition($subQuery, 'causer_id', $condition, $value, $values, false);
                                }
                            } elseif (in_array($column, ['created_at', 'time'])) {
                                $this->applyCondition($subQuery, 'created_at', $condition, $value, $values, true);
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

            return DataTables::of($query->latest())
                ->addColumn('job_id', function ($row) {
                    return optional($row->subject)->id_job ? '<span class="font-bold text-slate-800">' . optional($row->subject)->id_job . '</span>' : '-';
                })
                ->addColumn('requester', function ($row) {
                    return optional(optional($row->subject)->pengaju)->name ?? '-';
                })
                ->addColumn('area', function ($row) {
                    return optional(optional($row->subject)->area)->name ?? '-';
                })
                ->addColumn('event_badge', function ($row) {
                    $event = strtolower($row->event ?? 'info');
                    $bgColor = match ($event) {
                        'created' => 'background-color: #10b981;', // green
                        'updated' => 'background-color: #3b82f6;', // blue
                        'deleted' => 'background-color: #ef4444;', // red
                        'forwarded' => 'background-color: #f59e0b;', // amber
                        'completed' => 'background-color: #059669;', // dark green
                        'cancelled' => 'background-color: #64748b;', // slate
                        default => 'background-color: #6366f1;',   // indigo
                    };
                    return '<span class="badge text-white px-2 py-1 rounded capitalize" style="' . $bgColor . '">' . e($row->event) . '</span>';
                })
                ->addColumn('performed_by', function ($row) {
                    return optional($row->causer)->name ?? 'System';
                })
                ->addColumn('time', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y, H:i:s') : '-';
                })
                ->addColumn('actions', function ($row) {
                    $props = json_encode($row->properties ? $row->properties->toArray() : new \stdClass());
                    $detailBtn = '<button type="button" class="pl-icon-btn pl-icon-btn-view view-detail-btn" data-bs-toggle="tooltip" title="View Activity Detail" '
                        . 'data-id="' . $row->id . '" '
                        . 'data-job="' . e(optional($row->subject)->id_job ?? '-') . '" '
                        . 'data-event="' . e($row->event) . '" '
                        . 'data-desc="' . e($row->description) . '" '
                        . 'data-causer="' . e(optional($row->causer)->name ?? 'System') . '" '
                        . 'data-time="' . ($row->created_at ? $row->created_at->format('d M Y, H:i:s') : '-') . '" '
                        . 'data-properties="' . e($props) . '">'
                        . '<i class="fa-solid fa-eye"></i>'
                        . '</button>';

                    return '<div class="flex items-center justify-center gap-1.5">' . $detailBtn . '</div>';
                })
                ->rawColumns(['job_id', 'event_badge', 'actions'])
                ->make(true);
        }

        return view('jobs.activity-logs.index', compact('users', 'eventNames'));
    }

    /**
     * Display activity logs for a specific Job.
     */
    public function showForJob(JobMarsho $job): mixed
    {
        $user = Auth::user();
        if ($user && method_exists($user, 'isSuperAdmin') && !$user->isSuperAdmin() && $job->pengaju_id !== $user->id) {
            abort(403, 'You are not authorized to view this page.');
        }

        $activities = $job->activities()->with('causer')->latest()->paginate(15);

        return view('jobs.activity-logs.show', compact('job', 'activities'));
    }
}
