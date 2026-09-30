<?php

namespace App\Http\Controllers\Kanban;

use App\Http\Controllers\Controller;
use App\Models\Kanban\JobKanban;
use App\Models\Kanban\KanbanActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Menampilkan halaman utama log aktivitas dengan filter.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = KanbanActivityLog::with([
            'causer',
            'subject'
        ]);

        // Hanya tampilkan log yang subject-nya adalah JobKanban
        $query->where('subject_type', JobKanban::class);

        // Batasan hak akses
        if (!$user->isSuperAdmin()) {
            $query->whereHasMorph('subject', [JobKanban::class], function ($jobQuery) use ($user) {
                $jobQuery->where('pengaju_id', $user->id);
            });
        }

        // Filter dinamis
        if ($request->filled('subject_filter')) {
            $jobId = $request->input('subject_filter');
            $query->whereHasMorph('subject', [JobKanban::class], function ($jobQuery) use ($jobId) {
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

        $activities = $query->latest()->paginate(20)->withQueryString();

        return view('kanban.activity-logs.index', compact('activities', 'users', 'eventNames', 'request'));
    }

    /**
     * Menampilkan log aktivitas untuk satu Job spesifik.
     */
    public function showForJob(JobKanban $job)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $job->pengaju_id !== $user->id) {
            abort(403, 'You are not authorized to view this page.');
        }

        $activities = KanbanActivityLog::with(['causer'])
            ->where('subject_type', JobKanban::class)
            ->where('subject_id', $job->id)
            ->latest()
            ->paginate(20);

        return view('kanban.activity-logs.index', [
            'activities' => $activities,
            'job' => $job,
            'users' => collect(),
            'eventNames' => collect(),
            'request' => request()
        ]);
    }
}
